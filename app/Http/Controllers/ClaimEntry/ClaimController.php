<?php

namespace App\Http\Controllers\ClaimEntry;

use App\Http\Controllers\Controller;
use App\Http\Requests\ClaimFormRequest;
use App\Models\Claim;
use App\Models\Customer;
use App\Repository\Interfaces\ActivityRepositoryInterface;
use App\Repository\Interfaces\ClaimRepositoryInterface;
use App\Repository\Interfaces\CompanyRepositoryInterface;
use App\Repository\Interfaces\CustomerRepositoryInterface;
use App\Repository\Interfaces\IssueRepositoryInterface;
use App\Repository\Interfaces\SchemeRepositoryInterface;
use DateTime;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

class ClaimController extends Controller
{

    private CompanyRepositoryInterface $compRepository;
    private ActivityRepositoryInterface $activityRepository;
    private ClaimRepositoryInterface $claimRepository;
    private SchemeRepositoryInterface $schemeRepository;
    private CustomerRepositoryInterface $customerRepository;
    private IssueRepositoryInterface $issueRepo;

    public function __construct(CompanyRepositoryInterface $compRepository, 
    ClaimRepositoryInterface $claimRepository, 
    SchemeRepositoryInterface $schemeRepository, 
    CustomerRepositoryInterface $customerRepository, 
    ActivityRepositoryInterface $activityRepository,
    IssueRepositoryInterface $issueRepo)
    {
        $this->compRepository = $compRepository;
        $this->claimRepository = $claimRepository;
        $this->schemeRepository = $schemeRepository;
        $this->customerRepository = $customerRepository;
        $this->activityRepository = $activityRepository;
        $this->issueRepo = $issueRepo;
    }
    
    /**
     * RENDER NEW CLAIM VIEW
     */
    public function newClaim(){
        $companies = $this->compRepository->getAllCompanies();
        $schemes = $this->schemeRepository->getSchemes();
        return view('claimentry.newclaim', compact('companies', 'schemes'));
    }

    /**
     * SAVE CLAIM
     */
    public function saveNewClaim(ClaimFormRequest $request){
        try{
            $validated = $request->validated();

            $claim = $this->claimRepository->createClaim($validated);
          
            if($claim){
                return response()->json([
                    'status' => 'success',
                    'request_url' => url('/claim-files?claimid='.$claim->claimid),
                    'processed_url' => url('/processed-files?claimid='.$claim->claimid),
                    'home_url' => url('/dashboard'),
                    'message' => 'Claim Successfully created. Please upload Processed Files or Request Documents'
                ]);
                // return redirect()->to('/claim-files?claimid='.$claim->claimid)->with('success', 'Claim Successfully created. Please Upload Claim Files');
            }else{
                return response()->json([
                    'status' => 'error',
                    'message' => 'Oops something went wrong. Please try again'
                ]);
                // return redirect()->back()->with('error', 'Oops something went wrong. Please try again');
            }
        }catch(Exception $e){
            Log::info("=============== NEW CLAIM ====================");
            Log::info($e->getMessage());
            Log::info("=============== NEW CLAIM ====================");
        }
    }


    public function claimFiles(Request $request){
        $claimid = $request->query('claimid', null);
        return view('claimentry.claimfiles', compact('claimid'));
    }

    public function processedFiles(Request $request){
        $claimid = $request->query('claimid', null);
        return view('claimentry.processedfiles', compact('claimid'));
    }

    public function saveProcessedClaimFiles(Request $request){
        try{
            if($request->hasFile('claimfiles')){
                //create claim
                $claim = $this->claimRepository->getSingleClaimByClaimId($request->claimid);

                //upload file
                $uploadedFiles = $request->file('claimfiles');

                //extract store claim zip file
                $result =  $this->claimRepository->storeClaimFiles($uploadedFiles,strtoupper(str_replace(' ','_',$claim->company->name)), $claim->id, $claim->scheme_id, 1);
                
                return redirect()->to('/success')->with($result['status'], $result['message'] );
            }else{
                return redirect()->to('/success')->with('error', 'Please upload a zip save claim files');
            }
        }catch(Exception $e){
            Log::error($e);
            return redirect()->back()->with('error', 'Oops Unable to upload files '.$e->getMessage());
        }
    }

    public function saveClaimFiles(Request $request){
        try{
            if($request->hasFile('claimfiles')){
                //create claim
                $claim = $this->claimRepository->getSingleClaimByClaimId($request->claimid);

                //add comment to claim
                if($claim){
                    $claim->update([
                        'comment' => $request->comment
                    ]);
                }

                //get uploaded files
                $uploadedFiles = $request->file('claimfiles');

                
                $result =  $this->claimRepository->storeClaimFiles($uploadedFiles,strtoupper(str_replace(' ','_',$claim->company->name)), $claim->id, $claim->scheme_id, 0);
                
                return redirect()->to('/success')->with($result['status'], $result['message'] );
            }else{
                return redirect()->to('/success')->with('error', 'Please upload a zip save claim files');
            }
        }catch(Exception $e){
            Log::error($e);
            return redirect()->back()->with('error', 'Oops Unable to upload files '.$e->getMessage());
        }
    }

    /**
     * SEARCH CLAIM BY CLAIMID OR CUSTOMER NAME
     */
    public function searchClaim(Request $request){
        $searchby = $request->query('searchby', null);
        $term = $request->query('term', null);

        if($searchby==null & $term == null){
           return view('claimentry.searchclaim');
        }

        switch($searchby){
            case 'claimid':
                $claims = $this->claimRepository->getClaimByClaimId($term);
                return view('claimentry.searchclaim', compact('claims'));
            break;
            case 'customer_name':
                $customers = $this->customerRepository->searchCustomerByName($term);
                return view('claimentry.searchclaim', compact('customers'));
            break;
            default:
                return view('claimentry.searchclaim');
        }
    }

    /**
     * GET LATEST CLAIMS EG. LAST 200 CLAIMS
     */
    public function getClaims(Request $request){
        $filterby = $request->query('filterby', 0);
        $startdate = $request->query('startdate', null);
        $enddate = $request->query('enddate', null);

        $claims = $this->claimRepository->getUnAuditedClaims($filterby, $startdate, $enddate);
        // dd($claims);
        return view('claimentry.claims', compact('claims'));
    }


    /**
     * 
     * SCHEME CLAIMS
     */
    public function schemeClaims(Request $request){
        $filterby = $request->query('filterby', 0);
        $startdate = $request->query('startdate', null);
        $enddate = $request->query('enddate', null);
        $schemeid = $request->query('schemeid', null);
        
        $schemes = $this->schemeRepository->getAllSchemes();

        $claims = $this->claimRepository->getSchemeClaims($filterby, $startdate, $enddate, $schemeid);
        return view('claimentry.schemeclaims', compact('claims','schemes'));
    }


    /**
     * 
     */
    public function findClaim(Request $request){
        $id = $request->query('claimid', null);
        $claim = $this->claimRepository->getClaimById($id);

        
        if(!$claim){
            return back()->with('error', 'Claim Not Found');
        }
        $activities = $this->activityRepository->getClaimActivities($claim->id);
        $issues = $this->issueRepo->getIssueForClaim($claim->id);

        // dd($issues);

        // dd($activities);
        return view('claimentry.singleclaim', compact('claim', 'activities', 'issues'));
    }

    /**
     * 
     * 
     */
    public function getUnProcessedClaims(Request $request){
        $schemeid = $request->query('schemeid', null);
        $startdate = $request->query('startdate', null);
        $enddate = $request->query('enddate', null);

        $claims = $this->claimRepository->getUnProcessedClaims($schemeid, $startdate, $enddate);
        $schemes = $this->schemeRepository->getAllSchemes();
        return view('claimentry.unprocessed', compact('claims', 'schemes'));
    }


    /**
     * 
     * 
     */
    public function getClaimsWithIssues(){
        $claims = $this->claimRepository->getClaimsWithIssue();
        return view('claimentry.claimswithissues', compact('claims'));
    }


    /**
     * 
     * 
     */
    public function receiveClaim(Request $request){
        $claimid = $request->claimid;

        $result = $this->claimRepository->receiveClaimBySchemeAdmin($claimid);
        return response()->json($result);
    }


    /**
     * 
     * 
     */
    public function deleteClaim(Request $request){
        $result = $this->claimRepository->deleteClaim($request->id);
        return response()->json($result);
    }


    /**
     * 
     * 
     */
    public function invalidClaims(Request $request){
        $schemeid = $request->query('schemeid', null);
        $startdate = $request->query('startdate', null);
        $enddate = $request->query('enddate', null);

        $claims = $this->claimRepository->getInvalidClaims($schemeid, $startdate, $enddate);
        $schemes = $this->schemeRepository->getAllSchemes();
        return view('claimentry.invalid', compact('claims', 'schemes'));
    }


    public function updateClaimState(Request $request){
        $state = (int)$request->state;
        $claimid = (int)$request->claimid;

        $result = $this->claimRepository->updateClaimState($claimid, $state);
        return response()->json($result);
    }
}