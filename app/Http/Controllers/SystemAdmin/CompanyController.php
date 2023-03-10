<?php

namespace App\Http\Controllers\SystemAdmin;

use App\Http\Controllers\Controller;
use App\Http\Requests\NewCompanyFormRequest;
use App\Repository\Interfaces\CompanyRepositoryInterface;
use App\Repository\Interfaces\RegionRepositoryInterface;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class CompanyController extends Controller
{
    private RegionRepositoryInterface $regionRepository;
    private CompanyRepositoryInterface $compRepository;

    public function __construct(RegionRepositoryInterface $regionRepository, CompanyRepositoryInterface $compRepository)
    {
        $this->regionRepository = $regionRepository;
        $this->compRepository = $compRepository;
    }

    public function newCompany(){
        $regions = $this->regionRepository->getAllRegions();
        return view('systemadmins.newcompany', compact('regions'));
    }

    public function saveCompany(NewCompanyFormRequest $request){
        try{
            $validated = $request->validated();
            $validated['region_id'] = 8;
            $this->compRepository->createCompany($validated);
            return redirect()->back()->with('success', 'Company successfully registered');
        }catch(Exception $e){
            Log::info("====================NEW COMPANY REGISTSTRATION=======================");
            Log::info($e->getMessage());
            Log::info("====================NEW COMPANY REGISTSTRATION=======================");
            return redirect()->back()->with('error', 'Oops Something went wrong. Please try again');
        }
    }


    public function searchCompany(Request $request){
        $company = $request->query('company', null);
        $startdate = $request->query('startdate', null);
        $enddate = $request->query('enddate', null);
        $companies = $this->compRepository->getAllCompanies();
        $claims = $this->compRepository->getCompanyWithClaims($company, $startdate, $enddate);
        // dd($request->all());
        return view('master.companysearch', compact('claims', 'companies'));
    }
}