<?php

namespace App\Repository\Eloquent;

use App\Events\ClaimDeleted;
use App\Events\ClaimFileUploaded;
use App\Events\NewClaimAudited;
use App\Events\NewClaimRegistered;
use App\Events\NewPushNotificationEvent;
use App\Models\Claim;
use App\Models\Company;
use App\Models\Department;
use App\Models\Payment;
use App\Models\Scheme;
use App\Models\User;
use App\Repository\Interfaces\ClaimRepositoryInterface;
use Carbon\Carbon;
use DateTime;
use Exception;
use Illuminate\Contracts\Pagination\Paginator;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Maatwebsite\Excel\Facades\Excel;
use Spatie\Activitylog\Contracts\Activity;
use ZipArchive;

class ClaimRepository implements ClaimRepositoryInterface
{


        private $claimPath = '';
        private $unAuditedPath = '';
        
        public function getPendingClaimCount(): int
        {
            // $user = Auth::user();
            return Claim::where('processed', true)->where('active', true)->where('audited', false)->where('has_issue', false)->count();
           
        }


        /**
         * 
         * 
         */
        public function getUnProcessedClaimCount(): int {
            return Claim::where('processed', false)->where('active', true)->count();
        }

        /**
         * 
         * 
         */
        public function getMonthlyClaimCount(): int
        {
            return Claim::where('paid', true)->whereMonth('created_at', date('m'))->count();
        }


        public function getSchemeAdminReceiptCount() : int
        {
            return Claim::where('audited', true)->where('paid', false)->count();
        }


        /**
         * 
         * 
         */
        public function getClaimByMonth($date):Collection
        {
            if($date == null){
                $month = date('m');
                $year = date('Y');
            }else{
                $month = date('m', strtotime($date));
                $year = date('Y', strtotime($date));
            }

            $claims = Claim::whereYear('created_at', $year)->whereMonth('created_at', $month)->orderBy('created_at', 'desc')->with('company')->get()->groupBy(function($data) {
                return $data->created_at->format('Y-m-d');
            });
            return $claims;
        }


        /**
         * 
         * 
         */
        public function getClaimWithIssueCount(): int
        {
            return Claim::where('has_issue', 1)->count();
        }

        /**
         * 
         * 
         */
        public function getAuditedClaims($startdate, $enddate, $filterBy, $companyid, $schemeid): Collection
        {
                if($filterBy == 'bycompany'){
                    $claims = Claim::whereBetween('created_at', [$startdate, $enddate])
                                    ->where('audited', true)
                                    ->where('paid', false)
                                    ->where('company_id', $companyid)
                                    ->with('scheme', 'departmentreached')
                                    ->orderBy('id', 'DESC')
                                    ->get();
                }else if($filterBy == 'byscheme'){
                    $claims = Claim::whereBetween('created_at', [$startdate, $enddate])
                                    ->where('audited', true)
                                    ->where('paid', false)
                                    ->where('scheme_id', $schemeid)
                                    ->with('departmentreached', 'company')
                                    ->orderBy('id', 'DESC')
                                    ->get();
                }else{
                    $claims = Claim::whereBetween('created_at', [$startdate, $enddate])
                                    ->where('audited', true)
                                    ->where('paid', false)
                                    ->with('scheme', 'departmentreached', 'company')
                                    ->orderBy('id', 'DESC')
                                    ->get();
                }

                return $claims;
        }

        /**
         * 
         * 
         */
        public function getUnAuditedClaims($filterBy, $startdate, $enddate): Collection
        {
            $startdate  = $startdate == null ? Carbon::now()->subDays(30) : $startdate;
            $enddate = $enddate == null ? Carbon::now() : $enddate;

            if($startdate != null && $enddate != null){
                return Claim::where('audited', $filterBy)->where('processed', true)->where('has_issue', false)->orderBy('id', 'DESC')->with('scheme', 'departmentreached', 'company')->get();
            }else{
                return Claim::where('audited', $filterBy)->where('processed', true)->whereBetween('created_at', [$startdate, $enddate])->where('has_issue', false)->orderBy('id', 'DESC')->with('scheme', 'departmentreached', 'company')->get();
            }

        }


        /**
         * 
         * 
         */
        public function getSchemeClaims($filterBy, $startdate, $enddate, $schemeid): Collection
        {

            if($filterBy != null){
                $startdate  = $startdate == null ? Carbon::now()->subDays(30) : Carbon::createFromFormat('Y-m-d H:i:s',$startdate.' 00:00:00');
                $enddate = $enddate == null ? Carbon::now() : Carbon::createFromFormat('Y-m-d H:i:s',$enddate.' 23:59:59');
                if($schemeid == 0){
                    return Claim::where('paid', $filterBy)->where('audited', true)->whereBetween('created_at', [$startdate, $enddate])->orderBy('id', 'DESC')->with('scheme', 'departmentreached', 'company')->get();
                }else{
                    return Claim::where('paid', $filterBy)->where('audited', true)->where('scheme_id', $schemeid)->whereBetween('created_at', [$startdate, $enddate])->orderBy('id', 'DESC')->with('scheme', 'departmentreached', 'company')->get();
                }
            }else{
                $startdate  = $startdate == null ? Carbon::now()->subDays(30) : Carbon::createFromFormat('Y-m-d H:i:s',$startdate.' 00:00:00');
                $enddate = $enddate == null ? Carbon::now() :  Carbon::createFromFormat('Y-m-d H:i:s',$enddate.' 23:59:59');
                return Claim::where('audited', true)->where('paid', false)->whereBetween('created_at', [$startdate, $enddate])->orderBy('id', 'DESC')->with('scheme', 'departmentreached', 'company')->get();
            }
        }

        /**
         * 
         * 
         */
        public function getSingleClaimByClaimId($claimid): ?Model
        {
            return Claim::where('claimid', $claimid)->with('company')->first();
        }

        /**
         * 
         * 
         */
        public function createClaim(array $claimdetails): ?Model
        {
        
            $user = Auth::user();
            $department = $user->department;
            $claim = Claim::create([
                'claimid' => 'CLM'.mt_rand(10000,99999),
                'user_id' => $user->id,
                'scheme_id' => $claimdetails['scheme_id'],
                'department_id' => $user->department->id,
                'company_id' => $claimdetails['company_id'],
                'description' => $claimdetails['description'],
                'state' => 'Uploaded',
                'payment_status' => 'Pending',
                'department_reached' => $department->name,
                'department_reached_id' => $department->id
            ]);

            $company = Company::select('id', 'name')->where('id',$claimdetails['company_id'])->first();
            activity()
                ->causedBy($user)
                ->performedOn($claim)
                ->withProperties(['company' => $company])
                ->log(config('enums.NEW_CLAIM_REGISTERED'), config('enums.NEW_CLAIM_REGISTERED'));

            //send push notification to users
            event(new NewPushNotificationEvent("New Claim", "A new claim has just been registered by".$user->firstname.". Claim ID: ".$claim->claimid, $claim->id));

            return $claim;
        
        }

        /**
         * 
         * GET CLAIM BY DB COLUMN ID
         */
        public function getClaimById($claimId): ?Model
        {
            return Claim::with('customers', 'issues','departmentreached','company','scheme', 'claim_files')->find(trim($claimId));
        }

        /**
         * 
         * GET CLAIM BY HUMAN READABLE CLAIMID ie. DB COLUMN CLAIMID
         */
        public function getClaimByClaimId($claimid): Collection
        {
            return Claim::where('claimid', trim($claimid))->with('customers', 'issues','departmentreached','company','scheme')->get();
        }

        /**
         * 
         * STORE CLAIM FILES
         */
        public function storeClaimFiles($files, $companyName, $claimid, $schemeid, $claimstate): array
        {
            try{       
                $excelfileError = false; 
                $files_contain_excel = false;        
                $claim = Claim::where('id', $claimid)->first();
                
                // $claim->processed = $claim->processed == 1 ? $claim->processed : (int)$claimstate;
                // $claim->save();

                if($claim->claim_directory == null){
                    $folder = (new DateTime())->format('Y_m_d');
                    $month = Carbon::now()->isoFormat('MMMM');
                    $year = Carbon::now()->isoFormat('Y');
                    $scheme = Scheme::find($schemeid);
                    $this->claimPath = "files/".$year.'/'.$month.'/'.$folder.'/'.$scheme->name.'/'.$companyName.'/'.$claim->claimid.'/';
                    $this->unAuditedPath = "app/".$this->claimPath.'/UN_AUDITED/';
                }else{
                    $scheme = Scheme::find($schemeid);
                    $this->claimPath = $claim->claim_directory;
                    $this->unAuditedPath = "app/".$this->claimPath.'/UN_AUDITED/';
                }
                

                foreach($files as $file){
                    // $filename = time().$file->getClientOriginalName();
                    $extension = $file->getClientOriginalExtension();
                    if(in_array(strtolower($extension), ['xlsx', 'xls'])){
                        $files_contain_excel = true;
                        if($claimstate ==1){
                            //validate the file for all fields
                            $data = Excel::toArray([],$file)[0][0];
                            if($data != null && is_array($data)){
                                $trimedData = [];
                                foreach($data as $d){
                                    array_push($trimedData, trim($d));
                                }
                                $validated = $this->validateData($trimedData);
                                if($validated){
                                    //store uploaded file
                                    $storeResult = $this->storeFile($file);
                                    if($storeResult['status'] ==true){
                                        $user = Auth::user();
                                        // $files = Storage::files($this->claimPath.'/UN_AUDITED/');
                                        $department = Department::find(2); //Claim Enrty Department
                                        event(new ClaimFileUploaded([$storeResult['file']], $user->id, $department->id, $claimid, $claimstate));
                                    }
                                    $claim->processed = true;
                                    $claim->save();
                                    $excelfileError==false;
                                    //send push notification to users
                                    event(new NewPushNotificationEvent("Claim Processed", "The claim with Claim ID: " . $claim->claimid . " has been been processed, pending auditing.", $claim->id));

                                }else{
                                    $excelfileError = true;
                                }

                            }else{
                                $excelfileError = true;
                            }
                        }else{

                        }                      
                    }else{
                       
                        //store uploaded file
                        $storeResult = $this->storeFile($file);
                        if($storeResult['status'] ==true){
                            $user = Auth::user();
                            // $files = Storage::files($this->claimPath.'/UN_AUDITED/');
                            $department = Department::find(2); //Claim Enrty Department
                            event(new ClaimFileUploaded([$storeResult['file']], $user->id, $department->id, $claimid, $claimstate));
                        }
                    }
                }



                if($excelfileError == false){
                    $company = $claim->company;
                    if($claim){
                    $claim->update([
                            'processed' =>  $claimstate, //check to make sure uploaded files contain excel 
                            'claim_directory' => $this->claimPath,
                            'head_directory' => $this->claimPath,
                            'department_reached' => 'Audit Department'
                        ]);
                    }
                    activity()
                        ->causedBy($user)
                        ->performedOn($claim)
                        ->withProperties(['company' => $company])
                        ->log(config('enums.CLAIM_FILES_UPLOADED'), config('enums.CLAIM_FILES_UPLOADED'));
                    if($claimstate==1){
                        return [
                            'status' => 'success',
                            'message' => 'Processed file(s) uploaded',
                        ];
                    }else if($claimstate==0){
                        return [
                            'status' => 'success',
                            'message' => 'Request file(s) uploaded. Awaiting processing.',
                        ];
                    }else{
                        return [
                            'status' => 'success',
                            'message' => 'Files uploaded.'
                        ];
                    }
                }else{
                    $claim->update([
                        'processed' =>false,
                        'claim_directory' => $this->claimPath,
                        'head_directory' => $this->claimPath,
                    ]);
                    return [
                        'status' => 'error',
                        'message' => 'Unable to read Excel file. Please check the excel file and upload it again',
                    ];
                }
               
                
            }catch(Exception $e){
                Log::info($e);
                return [
                    'status' => 'error',
                    'message' => 'Oops, unable to store files. Please try again'
                ]; 
            }               
        }



        public function storeFile($file){
            try{
                $filename = time().$file->getClientOriginalName();
                $storageDestinationPath= storage_path($this->unAuditedPath);
                if (!File::exists( $storageDestinationPath)) {
                    File::makeDirectory($storageDestinationPath, 0755, true);
                }
                $path = Storage::putFileAs(
                    $this->claimPath.'/UN_AUDITED/',
                    $file,
                    $filename
                );
                
                return [
                    'status' => true,
                    'file' => $path
                ];
            }catch(Exception $e){
                Log::error($e);
                return [
                    'status' => false,
                    'file'=> null
                ];
            }
        }


        public function validateData(array $columns ){
           
            $validated = ["NO", "POLICY NUMBER", "CLAIMANT", "TYPE OF CLAIM", "AMOUNT", "DATE", "COMPANY"];
            $intersect = array_intersect($validated, $columns);

            if(count($validated) == count($intersect)){
                return true;
            }else{
                return false;
            }
        }





        /**
         * 
         * STORE CLAIM FILES - old
         */
        public function storeClaimFilesOldCopy($file, $companyName, $claimid, $schemeid): array
        {
                $filename = time().$file->getClientOriginalName();
                $extension = $file->getClientOriginalExtension();

                $scheme = Scheme::find($schemeid);
                //get folder name
                $date = new DateTime();
                $folder = $date->format('Y_m_d');
                $month = Carbon::now()->isoFormat('MMMM');
                $year = Carbon::now()->isoFormat('Y');

                $claim = Claim::where('id', $claimid)->first();

                try{
                $this->claimPath = "files/".$year.'/'.$month.'/'.$folder.'/'.$scheme->name.'/'.$companyName.'/'.$claim->claimid.'/';
                // $this->claimPath = "files/".$year.'/'.$month.'/'.$folder.'/'.$scheme->name.'/'.$companyName;
                $this->unAuditedPath = "app/".$this->claimPath.'/UN_AUDITED/';
                }catch(Exception $e){
                    return [
                        'status' => 'error',
                        'message' => $e->getMessage()
                    ];
                }

                if(in_array($extension, ['zip'])){
                    //unzip file into the folder
                    try{
                        $zip = new ZipArchive();
                        $zip->open($file->getRealPath());

                        $storageDestinationPath= storage_path($this->unAuditedPath);

                        if (!File::exists( $storageDestinationPath)) {
                            File::makeDirectory($storageDestinationPath, 0755, true);
                        }
                        $zip->extractTo($storageDestinationPath);
                        $zip->close();

                        //Update Claim with claim directory and head directory
                        if($claim){
                            $claim->update([
                                'claim_directory' => $this->claimPath,
                                'head_directory' => $storageDestinationPath,
                                'processed' => true,
                                'department_reached' => 'Audit Department'
                            ]);
                        }
    
                        //Get extracted files
                        $files = Storage::files($this->claimPath.'/UN_AUDITED/');

                        Log::info($files);
                        $user = Auth::user();
                        $department = Department::find(2); //Claim Enrty Department

                        event(new ClaimFileUploaded($files, $user->id, $department->id, $claimid,1));

                        return [
                            'status' => 'success',
                            'message' => 'Claim successfully saved'
                        ];
                    }catch(Exception $e){
                        Log::error("============UNZIPPING FILE FAILED===========");
                        Log::error($e->getMessage());
                        Log::error("============UNZIPPING FILE FAILED===========");

                        return [
                            'status' => 'error',
                            'message' => 'Oops Something went wrong. Unable to save claim'
                        ];
                    }
                    
                }else{
                    try{

                    }catch(Exception $e){
                        return [
                            'status' => 'error',
                            'message' => 'Please select a zip or an excel file'
                        ];
                    }
                }
                
        }


        /**
         * 
         * 
         * QUICK SEARCH CLAIM BASED ON MONTH AND YEAR
         */
        public function quickSearch($year, $month): Collection
        {
            return Claim::whereYear('created_at', $year)->whereMonth('created_at', $month)->get();
        }

        /**
         * 
         * 
         * GET CLAIMS WITH ISSUE NOT SOLVED
         */
        public function getClaimsWithIssue(): Collection
        {
            return Claim::where("has_issue", true)->with('issues','departmentreached','company')->get(); 
        }

        /**
         * 
         * 
         * UPDATE CLAIM
         */
        public function updateClaim($claimid, $newdata): ?Model
        {
            $claim = Claim::find($claimid);

            if($claim){
                $claim->update($newdata);
            }else{
                return null;
            }
            return $claim;
        }

        /**
         * 
         * 
         * GET CLAIMS BY DATE AND OR SCHEME
         */
        public function searchClaimByDateAndScheme($date, $schemeid=null):Collection
        {
            if($schemeid != null){
                $claims = Claim::whereDate('created_at', date("Y-m-d",strtotime($date)))->where('scheme_id', $schemeid)->with('customers', 'issues','departmentreached','company','scheme')->get();
            }else{
                $claims = Claim::whereDate('created_at', date("Y-m-d",strtotime($date)))->with('customers', 'issues','departmentreached','company','scheme')->get();
            }
            return $claims;
        }


        /**
         * 
         * 
         * UPLOAD AUDITED FILES
         */
        public function uploadAuditedFiles($uploadedFiles, $claimid): array
        {
            try{
                $claim = Claim::where('claimid', $claimid)->first();
                if($claim){

                    foreach($uploadedFiles as $file){
                        $filename = time().$file->getClientOriginalName();

                        $uploadPath = $claim->claim_directory.'/AUDITED';
                        $storageDestinationPath= storage_path('app/'.$uploadPath);

                        if (!File::exists( $storageDestinationPath)) {
                            File::makeDirectory($storageDestinationPath, 0755, true);
                        }

                        Storage::putFileAs(
                            $uploadPath,
                            $file,
                            $filename
                        );
                    }        

                    event(new NewClaimAudited($claim->id));

                    return [
                        'status' => 'success',
                        'message' => 'Audited files successfully uploaded'
                    ];
                }else{
                    return [
                        'status' => 'error',
                        'message' => 'Claim with Claim ID '.$claimid.' not found',
                    ];
                }
            }catch(Exception $e){
                return [
                    'status' => 'error',
                    'message' => $e->getMessage(),
                ];
            }   
        }


        /**
         * 
         * 
         * GET UNPROCESSED CLAIMS
         */
        public function getUnProcessedClaims($schemeid, $startdate, $enddate): Collection
        {
            if($schemeid != null){
                if($startdate != null && $enddate != null){
                    return Claim::where('processed', false)->where('active', true)->where('scheme_id', $schemeid)->whereBetween('created_at', [$startdate, $enddate])->get();
                }else{
                    return Claim::where('processed', false)->where('active', true)->where('scheme_id', $schemeid)->get();
                }
            }else{
                return Claim::where('processed', false)->where('active', true)->get();
            }
        }


        /**
         * 
         * RECEIVE CAIM
         */
        public function receiveClaimBySchemeAdmin($claimid): array
        {
            try{
                $invchecque = mt_rand(1000, 99999);
                $payment = Payment::create([
                    'chequeno' => $invchecque,
                    'invoice' => $invchecque,
                    'user_id' => Auth::user()->id,
                    'claim_id' => $claimid
                ]);
                Claim::where('id', $claimid)->update([
                    'paid' => true,
                    'department_reached' => 'Accounting | Scheme Administrators',
                    'payment_status' => 'Paid',
                    'state' => 'Received',
                    'payment_id' => $payment->id
                ]);

                $claim = Claim::find($claimid);
               
                $company = Company::select('id', 'name')->where('id',$claim->company_id)->first();
                activity()
                        ->causedBy(Auth::user())
                        ->performedOn($claim)
                        ->withProperties(['company' => $company])
                        ->log(config('enums.CLAIM_RECEIVED'), config('enums.CLAIM_RECEIVED'));
                return [
                    'status' => 'success',
                    'message' => 'Claim has been received!!!'
                ];
            }catch(Exception $e){
                Log::error($e);
                return [
                    'status' => 'error',
                    'message' => 'Unable to process action. Please try again'
                ];
            }


        }

    /**
     * 
     * 
     */
    public function deleteClaim($id) : array
    {
        $claim = Claim::find($id);
        if($claim){
            event(new ClaimDeleted($claim));
            // $claim->delete();
            return [
                'status' => 'success',
                'message' => 'Claim successfully deleted'
            ];
        }else{
            return [
                'status' => 'error',
                'message' => 'Claim Not Found.'
           ]; 
        }
    }


    public function getInvalidClaims($schemeid, $startdate, $enddate ): ?Collection
    {
        if($schemeid != null){
                if($startdate != null && $enddate != null){
                    return Claim::where('active', false)->where('scheme_id', $schemeid)->whereBetween('created_at', [$startdate, $enddate])->get();
                }else{
                    return Claim::where('active', false)->where('scheme_id', $schemeid)->get();
                }
        }else{
                return Claim::where('active', false)->get();
        }
    }


    public function updateClaimState($claimid, int $state) : array
    {
         $claim = Claim::find($claimid);
         if($claim){
            $claim->update([
                'active' => $state
            ]);
            return [
                'status' => 'success',
                'message' => $state== 1 ? 'Claim state successfully validated' : 'Claim state successfully set to invalid'
            ];
         }else{
            return [
                'status' => 'error',
                'message' => 'Claim Not Found. Please try again'
            ];
         }
    }




}