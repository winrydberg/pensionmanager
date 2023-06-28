<?php

namespace App\Http\Controllers\Admin;

use App\Events\ClaimDownloaded;
use App\Http\Controllers\Controller;
use App\Models\ClaimFile;
use App\Repository\Interfaces\ClaimRepositoryInterface;
use App\Repository\Interfaces\CompanyRepositoryInterface;
use App\Repository\Interfaces\IssueRepositoryInterface;
use Exception;
use FilesystemIterator;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use RecursiveDirectoryIterator;
use RecursiveIteratorIterator;
use ZipArchive;

class DownloadController extends Controller
{
    private ClaimRepositoryInterface $claimRepository;
    private CompanyRepositoryInterface $compRepository;
    private IssueRepositoryInterface $issueRepo;

    public function __construct(ClaimRepositoryInterface $claimRepository, CompanyRepositoryInterface $compRepository, IssueRepositoryInterface $issueRepo )
    {
        $this->claimRepository = $claimRepository;
        $this->compRepository = $compRepository;
        $this->issueRepo = $issueRepo;
    }

    /**
     * DOWNLOAD CLAIM FILES
     */
    public function downloadClaimFiles($id, $claimid){
        $claim = $this->claimRepository->getClaimById($id);

        if($claim){
            $zip = new ZipArchive();
            try{
                if($claim->claim_directory == null){
                    return redirect()->back()->with('error', 'Files Not Found. Upload files for claim');
                }
                // $name = 'CLAIM_ID_'.$claim->claimid.'_FILES.zip';
                $name = strtoupper($claim->description).'_'.time().'_FILES.zip';

                if(($zip->open(public_path($name), ZipArchive::CREATE)==TRUE)){
                    $storePath = storage_path('app/files/downloads/'.$name);
                    $filesPath = storage_path('app/'.$claim->claim_directory);

                    if (!File::exists(storage_path('app/files/downloads/'))) {
                        File::makeDirectory(storage_path('app/files/downloads/'), 0755, true);
                    }
                    $zip = new \ZipArchive();
                
                    if ($zip->open($storePath, \ZipArchive::CREATE) !== true) {
                        throw new \RuntimeException('Cannot open ' . $storePath);
                    }
                
                    $this->addContent($zip, $filesPath);
                    $zip->close();

                    event(new ClaimDownloaded($claim));
                    
                    return response()->download($storePath);
                }else{
                    return back();
                }
            }catch(Exception $e){
                Log::error('====================DOWNLOAD ERROR=========================');
                Log::error($e->getMessage());
                Log::error('====================DOWNLOAD ERROR=========================');
                return redirect()->back()->with('error', 'Unable to download files. Files Not Found');
               
            }
        }else{
            return redirect()->back()->with('error', 'Claim Not Found');
        }
    }

    private function addContent(\ZipArchive $zip, string $path)
    {
        /** @var SplFileInfo[] $files */
        $iterator = new \RecursiveIteratorIterator(
            new \RecursiveDirectoryIterator(
                $path,
                \FilesystemIterator::FOLLOW_SYMLINKS
            ),
            \RecursiveIteratorIterator::SELF_FIRST
        );
    
        while ($iterator->valid()) {
            if (!$iterator->isDot()) {
                $filePath = $iterator->getPathName();
                $relativePath = substr($filePath, strlen($path) + 1);
    
                if (!$iterator->isDir()) {
                    $zip->addFile($filePath, $relativePath);
                } else {
                    if ($relativePath !== false) {
                        $zip->addEmptyDir($relativePath);
                    }
                }
            }
            $iterator->next();
        }
    }



    public function downloadExcelClaimFormat(){
  
        $path = Storage::path('public/format/COMPANY_NAME_CLAIMS_format.xlsx');
        if(file_exists($path)){
            return response()->download($path);
        }else{
            return back()->with('error', 'File not available now. Please try again later');
        }        
    }


    public function downloadReviewFiles($issueid){

        $issue = $this->issueRepo->getIssueWithId($issueid);
        if($issue){
               if($issue->claim_file_id != null){
                  $claim = $this->claimRepository->getClaimById($issue->claim_id);
                  $claimFile = ClaimFile::find($issue->claim_file_id);
                  if($claimFile == null){
                    return back()->with('downloaderror', 'File Not Found. File may have been deleted');
                  }
                  if(Storage::disk('local')->exists($claimFile->filename)){
                    $path = Storage::path($claimFile->filename);
                     event(new ClaimDownloaded($claim));
                    return response()->download($path);
                  }else{
                    return back()->with('downloaderror', 'File Not Found. File may have been deleted');
                  }
                 
               }else {
                  return back()->with('downloaderror', 'File No Found.');
               }

                $zip = new ZipArchive();
                $name = strtoupper($issue->issue_ticket).'_'.time().'_REVIEW_FILES.zip';

        }else{
            return back()->with('downloaderror', 'Unable to download files');
        }
    }


}