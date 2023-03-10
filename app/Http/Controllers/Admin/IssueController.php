<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Issue;
use App\Repository\Interfaces\ClaimRepositoryInterface;
use App\Repository\Interfaces\IssueRepositoryInterface;
use App\Repository\Interfaces\UserRepositoryInterface;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class IssueController extends Controller
{
    private IssueRepositoryInterface $issueRepository;
    private ClaimRepositoryInterface $claimRepository;
    private UserRepositoryInterface $userRepository;

    public function __construct(IssueRepositoryInterface $issueRepository, ClaimRepositoryInterface $claimRepository, UserRepositoryInterface $userRepository)
    {
        $this->issueRepository = $issueRepository;
        $this->claimRepository = $claimRepository;
        $this->userRepository = $userRepository;
    }

    public function saveClaimIssue(Request $request){
        try{
            // return $request->all();
            $claim = $this->claimRepository->getClaimById($request->claimid);

            if(!$claim){
                return response()->json([
                    'status' => 'error',
                    'message' => 'Claim Not Found'
                ]);
            }
            
            $issue = $this->issueRepository->createIssue([
                'message' => $request->message,
                'claim_id' => $claim != null ? $claim->id : null,
                'department_id' => $claim != null?$claim->departmentreached->id:null
            ]);

            $this->claimRepository->updateClaim($claim->id, [
                'has_issue' => true
            ]);

            return response()->json([
                'status' => 'success',
                'message' => 'Claim issue successfully logged'
            ]);

        }catch(Exception $e){
            Log::info($e);
            return response()->json([
                'status' => 'error',
                'errror' => $e->getMessage(),
                'message' => 'Unable to report issue. Please try again'
            ]);
        }
    }

    /**
     * REVIEW ISSUE
     */
    public function reviewClaimIssue(Request $request){
        $issue = $this->issueRepository->getIssue($request->query('ticket', null));
        if($issue){
            return view('master.reviewissue', compact('issue'));
        }else{
            return redirect()->back('error', 'Issue Ticket Not Found. Please select issue from list');
        }
    }


    /**
     * RESOLVE ISSUE
     */
    public function resolveIssue(Request $request){

       $result = $this->issueRepository->resolveIssue($request->issueticket, $request->issuefiles, $request->resolve_message);
       return redirect()->to('/success')->with($result['status'], $result['message']);
    }



    public function reportIssueonFile(Request $request){
        $result = $this->issueRepository->fileReportIssue($request->id, $request->message);
        return response()->json($result);
    }
}