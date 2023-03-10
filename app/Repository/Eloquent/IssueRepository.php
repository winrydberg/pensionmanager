<?php

namespace App\Repository\Eloquent;

use App\Events\ClaimFileUploaded;
use App\Events\IssueResolved;
use App\Events\NewIssueRaised;
use App\Events\NewPushNotificationEvent;
use App\Models\Claim;
use App\Models\ClaimFile;
use App\Models\Customer;
use App\Models\Department;
use App\Models\Issue;
use App\Repository\Interfaces\IssueRepositoryInterface;
use App\Repository\Interfaces\UserRepositoryInterface;
use Exception;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use ZipArchive;

class IssueRepository implements IssueRepositoryInterface
{
    private UserRepositoryInterface $userRepository;
    public Issue $issue;

    public function __construct(UserRepositoryInterface $userRepository)
    {
        $this->userRepository = $userRepository;
    }

    public function createIssue(array $data): ?Model
    {
        $random = substr(md5(mt_rand()), 0 , 7);
        $issueticket = $random.''.mt_rand(1000, 9999);
        $data['issue_ticket'] = strtoupper($issueticket);
        $data['resolved'] = false;
        $issue = Issue::create($data);

        event(new NewIssueRaised($issue));
        
        return $issue;
    }

    public function getUnResolvedIssues(): Collection
    {
        return Issue::where('resolved', false)->with('claim')->latest()->take(5)->get();
    }

    public function getAllpendingIssues(): Collection
    {
        return Issue::where('resolved', false)->with('claim')->get();
    }


    public function resolveIssue(string $issueticket, ?array $files, $resolvemessage): array
    {
        try{
                $this->issue = $this->getIssue($issueticket);

                if($this->issue){
                    $user = $this->userRepository->getAuthUser();
                    $claim = Claim::find($this->issue->claim->id);
                    
                    if($files != null){
                        foreach($files as $file){
                        $this->storeIssueReviewFile($file);
                        }
                    }
                
                    $this->issue->update([
                        'resolved'=> true,
                        'resolve_message' => $resolvemessage,
                        'user_id' => $user->id,
                        'review_files_directory' => $claim->claim_directory."/ISSUE_REVIEW/".date('Y_m_d')."/",
                    ]);

                    event(new IssueResolved($this->issue));
                    
                    return [
                        'status' => 'success',
                        'message' => 'Issue resolved successfully'
                    ];
                }else{
                    return [
                        'status' => 'error',
                        'message' => 'Oops issue not found'
                    ];
                }
        }catch(Exception $e)
        {
            Log::error($e);
            return [
                'status' => 'error',
                'message' => 'Oops, Something went wrong. Please try again later'
            ];
        }
        
    }




    public function storeIssueReviewFile($file){
        $extension = $file->getClientOriginalExtension();
        $claim = Claim::find($this->issue->claim->id);
        $claimFile = ClaimFile::find($this->issue->claim_file_id);
        if( in_array(strtolower($extension) , ['xlsx', 'xls']) ){
            if($this->issue->claim_file_id != null){
                Storage::disk('local')->delete($claimFile->filename);
                $path = '';
                if($claim->audited){
                    $path = Storage::putFileAs(
                        $claim->claim_directory."/AUDITED/",
                        $file,
                        $file->getClientOriginalName()
                    );
                }else{
                    $path = Storage::putFileAs(
                         $claim->claim_directory.'/UN_AUDITED/',
                        $file,
                        $file->getClientOriginalName()
                    );
                }


                $claimFile = ClaimFile::find($this->issue->claim_file_id);
                if($claimFile){
                 
                    $claimFile->update([
                        'filename' => $path,
                        'extension' => $extension
                    ]);
                }

                $user = Auth::user();
                
                // $files = Storage::files($this->claimPath.'/UN_AUDITED/');
                $department = Department::find(2); //Claim Enrty Department

                //remove old data
                Customer::where('claim_id', $claim->id)->delete();

                $claim->update([
                    'processed' => true,
                    'claim_amount' => null
                ]);
                $this->issue->update([
                    'resolved' => true
                ]);
                
                event(new ClaimFileUploaded([$path], $user->id, $department->id, $claim->id, 1));

                
               

            }else{
                $uploadPath = $claim->claim_directory.'/ISSUE_REVIEW_FILES';
                $storageDestinationPath= storage_path('app/'.$uploadPath);
                if (!File::exists( $storageDestinationPath)) {
                    File::makeDirectory($storageDestinationPath, 0755, true);
                }
                $path = Storage::putFileAs(
                    $uploadPath,
                    $file,
                    $file->getClientOriginalName()
                );
            } 
        }else{
            //1. check if issue has claim file
            if($this->issue->claim_file_id != null){

                if($claim->audited){

                    $path = Storage::putFileAs(
                        $claim->claim_directory."/AUDITED/",
                        $file,
                        $file->getClientOriginalName()
                    );

                    //find claim file and remove it from DB and storage
                    $claimFile = ClaimFile::find($this->issue->claim_file_id);

                    if($claimFile){
                        // $claimFile->delete();
                        Storage::disk('local')->delete($claimFile->filename);
                        $claimFile->update([
                            'filename' => $path,
                            'extension' => $extension
                        ]);
                    }

                }else{
                    $path = Storage::putFileAs(
                         $claim->claim_directory.'/UN_AUDITED/',
                        $file,
                        $file->getClientOriginalName()
                    );

                    //find claim file and remove it from DB and storage
                    $claimFile = ClaimFile::find($this->issue->claim_file_id);

                    if($claimFile){
                        // $claimFile->delete();
                        Storage::disk('local')->delete($claimFile->filename);
                        $claimFile->update([
                            'filename' => $path,
                            'extension' => $extension
                        ]);
                    }
                }
                
            }else{
                $path = Storage::putFileAs(
                        $claim->claim_directory."/ISSUE_REVIEW/".date('Y_m_d')."/",
                        $file,
                        $file->getClientOriginalName()
                );
                //find claim file and remove it from DB and storage
                $claimFile = ClaimFile::find($this->issue->claim_file_id);
                if($claimFile){
                    // $claimFile->delete();
                    Storage::disk('local')->delete($claimFile->filename);
                    $claimFile->update([
                        'filename' => $path,
                        'extension' => $extension
                    ]);
                }
            }
                            
                            
        }
    }

    /**
     * GET ISSUE
     */
    public function getIssue(string $issueticket): ?Model
    {
        return Issue::where('issue_ticket', $issueticket)->with('claim')->first();
    }



    public function getIssueForClaim($claimId): Collection
    {
        return Issue::where('claim_id', $claimId)->with('claim_file')->get();
    }


    public function getIssueWithId($id): ?Model
    {
        return Issue::find($id);
    }



    public function fileReportIssue($fileid, $message): array
    {
        try{

            $claimfile = ClaimFile::find($fileid);
            if($claimfile){
                if($claimfile->issue_id != null){
                    $issue = Issue::find($claimfile->issue_id);
                    if($issue && $issue->resolved ==false){
                        return [
                            'status' => 'error',
                            'message' => 'Oops File already has unresolved issue. Issue on current file must be resolved to raise new issue on file'
                        ];
                    }
                }
                $claim = Claim::find($claimfile->claim_id);
                $random = substr(md5(mt_rand()), 0 , 7);
                $issueticket = $random.''.mt_rand(1000, 9999);
                $data = [];
                $data['issue_ticket'] = strtoupper($issueticket);
                $data['resolved'] = false;
                $data['message'] = $message;
                $data['claim_file_id'] = $fileid;
                $data['claim_id'] = $claimfile->claim_id;
                $data['department_id'] = $claim->department_reached_id;
                $issue = Issue::create($data);

                event(new NewIssueRaised($issue));

                $claimfile->update([
                    'issue_id' => $issue->id
                ]);

                $claim->update([
                    'has_issue' => true
                ]);
                return [
                    'status' => 'success',
                    'message' => 'Issue successfully reported on claim file.'
                ];

            }else{
                return [
                    'status' => 'error',
                    'message' => 'Unable to report issue. Claim File NOT found.'
                ];
            }
            
        }catch(Exception $e){

            Log::error($e);
            return [
                'status' => 'error',
                'message' => 'Unable to file issue on claim. Please try again'
            ];
        }
    }

    



}