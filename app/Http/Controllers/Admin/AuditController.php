<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Repository\Interfaces\ClaimRepositoryInterface;
use App\Repository\Interfaces\CompanyRepositoryInterface;
use App\Repository\Interfaces\SchemeRepositoryInterface;
use Carbon\Carbon;
use Illuminate\Http\Request;

class AuditController extends Controller
{

    private ClaimRepositoryInterface $claimRepository;
    private CompanyRepositoryInterface $companyRepository;
    private SchemeRepositoryInterface $schemeRepository;
   
    public function __construct(ClaimRepositoryInterface $claimRepository, CompanyRepositoryInterface $companyRepository, SchemeRepositoryInterface $schemeRepository)
    {
        $this->claimRepository = $claimRepository;
        $this->companyRepository = $companyRepository;
        $this->schemeRepository = $schemeRepository;
    }

    public function audit(Request $request){
        $claimid = $request->query("claimid", null);
        if($claimid == null){
            return redirect()->back()->with('error', 'Claim Not Found. Please select a claim');
        }

        $claim = $this->claimRepository->getSingleClaimByClaimId($claimid);
        if($claim == null){
            return redirect()->back()->with('error', 'Claim Not Found. Please select a claim');
        }
        return view('claimentry.audit', compact('claim'));
    }
    
    /**
     * UPLOAD AUDITED CLAIMS
     */
    public function uploadAuditFiles(Request $request){
        if($request->hasFile('audit-file')){
            $result = $this->claimRepository->uploadAuditedFiles($request->file('audit-file'), $request->claimid);
            if($result['status'] =='success'){
                return redirect()->to('/success')->with('success', $result['message']);
            }else{
                return redirect()->to('/success')->with('error', $result['message']);
            }
        }else{
            return redirect()->back()->with('error', 'Please upload the audited files');
        }
    }


    /**
     * GET AUDITED CLAIMS
     */
    public function getAuditedClaims(Request $request){

        $startdate = $request->query('startdate', Carbon::today()->startOfMonth()->toDateString());
        $enddate = $request->query('enddate', Carbon::today()->endOfMonth()->toDateString());
        // $startdate = $request->query('startdate', Carbon::today()->startOfMonth()->toDateString());
        // $enddate = $request->query('enddate', Carbon::today()->endOfMonth()->toDateString());
        $companyid = $request->query('company', null);
        $schemeid = $request->query('scheme', null);
        $filterBy = $request->query('filterby', null);

        $companies = $this->companyRepository->getAllCompanies();
        $schemes = $this->schemeRepository->getSchemes();

        $claims = $this->claimRepository->getAuditedClaims($startdate, $enddate, $filterBy, $companyid, $schemeid);
        return view('claimentry.auditedclaims', compact('claims', 'startdate', 'enddate', 'companies', 'schemes'));
    }
}