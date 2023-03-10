<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Repository\Interfaces\ActivityRepositoryInterface;
use App\Repository\Interfaces\ClaimRepositoryInterface;
use App\Repository\Interfaces\CompanyRepositoryInterface;
use App\Repository\Interfaces\IssueRepositoryInterface;
use App\Repository\Interfaces\SchemeRepositoryInterface;
// use Illuminate\Contracts\Session\Session;
use Illuminate\Http\Request;
use Session;

class DashboardController extends Controller
{
    private ClaimRepositoryInterface $claimRepository;
    private CompanyRepositoryInterface $compRepository;
    private IssueRepositoryInterface $issueRepository;
    private ActivityRepositoryInterface $activityRepository;
    private SchemeRepositoryInterface $schemeRepo;

    public function __construct(ClaimRepositoryInterface $claimRepository, CompanyRepositoryInterface $compRepository, IssueRepositoryInterface $issueRepository, ActivityRepositoryInterface $activityRepository, SchemeRepositoryInterface $schemeRepo)
    {
        $this->claimRepository = $claimRepository;
        $this->compRepository = $compRepository;
        $this->issueRepository = $issueRepository;
        $this->activityRepository = $activityRepository;
        $this->schemeRepo = $schemeRepo;
    }

    public function index(Request $request){
        // $activities = $this->activityRepository->getActivityByDate(date('Y-m-d'));
        $pendingClaim = $this->claimRepository->getPendingClaimCount();
        $pendingSchemeRecept = $this->claimRepository->getSchemeAdminReceiptCount();
        // $companyCount = $this->compRepository->getCompanyCount();
        $pendingIssues = $this->issueRepository->getUnResolvedIssues();
        $claimsWithIssueCount = $this->claimRepository->getClaimWithIssueCount();
        $unProcessedCount = $this->claimRepository->getUnProcessedClaimCount();
        $schemes = $this->schemeRepo->getAllSchemes();

        if($request->query('date', null) != null){
            $currentMonthYear = date($request->query('date'));
            $claimsByDate = $this->claimRepository->getClaimByMonth($request->query('date'));
        }else{
            $currentMonthYear = date('Y-m');
            $claimsByDate = $this->claimRepository->getClaimByMonth(null);
        }
        
        return view('master.index', compact('pendingClaim', 'pendingSchemeRecept', 'claimsWithIssueCount', 'pendingIssues', 'claimsByDate', 'currentMonthYear', 'unProcessedCount', 'schemes'));
    }



    public function successPage(){
        // Session::flash('error', 'Error message here');
        return view('claimentry.success');
    }


}