<?php

namespace App\Http\Controllers\Admin;

use App\Exports\CompanyReportExport;
use App\Exports\SchemeReportExport;
use App\Http\Controllers\Controller;
use App\Imports\ClaimImport;
use App\Imports\MergeExcelFile;
use Illuminate\Http\Request;
use App\Repository\Interfaces\CompanyRepositoryInterface;
use App\Repository\Interfaces\ReportRepositoryInterface;
use App\Repository\Interfaces\SchemeRepositoryInterface;
use Illuminate\Support\Facades\Log;
use Maatwebsite\Excel\Facades\Excel;

class ReportsController extends Controller
{
    public CompanyRepositoryInterface $companyRepo;
    public ReportRepositoryInterface $reportsRepo;
    public SchemeRepositoryInterface $schemeRepo;

    public function __construct(CompanyRepositoryInterface $companyRepo, ReportRepositoryInterface $reportsRepo, SchemeRepositoryInterface $schemeRepo)
    {
        $this->companyRepo = $companyRepo;
        $this->reportsRepo = $reportsRepo;
        $this->schemeRepo = $schemeRepo;
    }


    /**
     * 
     * 
     */
    public function companyReports(Request $request){
        
        $startdate = $request->query('startdate', null);
        $enddate = $request->query('enddate', null);
        $companyid = $request->query('company', null);

        $companies = $this->companyRepo->getAllCompanies();
        
        //JUST RETURN VIEW FOR SEARCHING
        if($startdate == null || $enddate ==null || $companyid == null){
            return view('reports.company', compact('companies'));
        }

        $selcompany = $this->companyRepo->getCompanyById($companyid);
        //GENERATE REPORT BY COMPANY FOR SPECIFIED DATES
        $claims = $this->reportsRepo->generateCompanyReports($startdate, $enddate, $companyid);
        
        $total_amount = 0;
        foreach($claims as $claim){
            $total_amount += (int)$claim->sums;
        }
        return view('reports.company', compact('claims', 'companies', 'total_amount', 'selcompany'));
    }


    /**
     * 
     * 
     */
    public function exportExcel(Request $request){
        $startdate = $request->query('startdate', null);
        $enddate = $request->query('enddate', null);
        $companyid = $request->query('company', null);
        $schemeid = $request->query('scheme', null);

        $data = null;
        if($companyid){
            $data = $this->reportsRepo->generateCompanyBreakdownReports($startdate, $enddate, $companyid);
            // dd($data);s
            $company = $this->companyRepo->getCompanyById($companyid);
            $export = new CompanyReportExport($data, $company);
            $fileName = strtoupper($company->name).'_'.$startdate.'_TO_'.$enddate.'REPORTS';
            return Excel::download($export, $fileName.'.xlsx');
        }else if($schemeid){
            $data = $this->reportsRepo->generateSchemeBreakdownReports($startdate, $enddate, $schemeid);
            $scheme = $this->schemeRepo->getSchemeById($schemeid);
            $export = new SchemeReportExport($data, $scheme);
            $fileName = strtoupper($scheme->name.'_'.$scheme->tiertype).'_'.$startdate.'_TO_'.$enddate.'REPORTS';
            return Excel::download($export, $fileName.'.xlsx');
        }else{

        }
        if($data == null){
            return redirect()->back()->with('error', 'Claims Not Found. Please search again');
        }
    }


    /**
     * 
     * 
     */
    public function getReportBreakdown(Request $request)
    {
        $month = $request->query('month', null);
        $company = $request->query('company', null);
        $scheme = $request->query('scheme', null);

        // if($company ==null | $month == null){
        //     return back()->with('error', 'Ooops, Unable to get breakdown. Please try again');
        // }
        $quit = true;
        if($company != null){
            $results = $this->reportsRepo->reportsBreakDown($month, $company, $scheme);
            $company = $this->companyRepo->getCompanyById($company);
            $employees = $results['employees'];
            return view('reports.breakdown', compact('employees', 'company'));
        }else if($scheme != null){
            $results = $this->reportsRepo->reportsBreakDown($month, $company, $scheme);
            $scheme = $this->schemeRepo->getSchemeById($scheme);
            $employees = $results['employees'];
            return view('reports.breakdown', compact('employees', 'company', 'scheme'));
        }else{
            return back()->with('error', 'Ooops, Unable to get breakdown. Please try again'); 
        }

    }


    /**
     * 
     * 
     */
    public function schemeReports(Request $request){
        $startdate = $request->query('startdate', null);
        $enddate = $request->query('enddate', null);
        $schemeid = $request->query('scheme', null);

        $schemes = $this->schemeRepo->getAllSchemes();
        
        //JUST RETURN VIEW FOR SEARCHING
        if($startdate == null || $enddate ==null || $schemeid == null){
            return view('reports.scheme', compact('schemes'));
        }

        $selscheme = $this->schemeRepo->getSchemeById($schemeid);
        //GENERATE REPORT BY SCHEME FOR SPECIFIED DATES
        $claims = $this->reportsRepo->generateSchemeReports($startdate, $enddate, $schemeid);
        
        $total_amount = 0;
        foreach($claims as $claim){
            $total_amount += (int)$claim->sums;
        }

        return view('reports.scheme', compact('claims', 'schemes', 'total_amount', 'selscheme'));
    }
}