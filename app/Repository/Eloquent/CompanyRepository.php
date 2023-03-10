<?php

namespace App\Repository\Eloquent;

use App\Models\Claim;
use App\Models\Company;
use App\Repository\Interfaces\CompanyRepositoryInterface;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Log;

class CompanyRepository implements CompanyRepositoryInterface
{
    /**
     * 
     */
    public function getAllCompanies(): Collection
    {
        return Company::all();   
    }

    /**
     * 
     * 
     */
    public function getCompanyCount(): int
    {
        return Company::count();   
    }

    /**
     * 
     * 
     */
    public function getCompanyById($companyId): ?Model
    {
        return Company::findOrFail($companyId);
    }

    /**
     * 
     * 
     */
    public function createCompany(array $userDetails): ?Model
    {
        return Company::create($userDetails);
    }

    /**
     * 
     * 
     */
    public function updateCompany($companyId, array $newDetails) :?Model
    {
        return Company::whereId($companyId)->update($newDetails);
    } 

    /**
     * 
     * 
     */
    public function deleteCompany($companyId)
    {
        Company::destroy($companyId);
    }

    /**
     * 
     * 
     */
    public function getCompanyByName($name): Collection
    {
        return Company::where('name', 'like', '%'.$name.'%')->with('claims')->get();
    }

    /**
     * 
     * 
     */
    public function getCompanyWithClaims($company, $startdate, $enddate): Collection
    {
        $startdate = $startdate == null ? Carbon::now()->subDays(30) : $startdate;
        $enddate = $enddate == null ? Carbon::now() : $enddate;

        Log::info($startdate);
        Log::info($enddate);
        if($company ==null || $company=='null'){
            return Claim::where('created_at', '>=', $startdate)->where('created_at', '<=',$enddate)->with('company','scheme')->get();
        }else{
            // $company = Company::find($company)->where('',function($q));
            $claims = Claim::with('company','scheme')
                            ->where('created_at','>=', $startdate)
                            ->where('created_at','<=',$enddate)->where('company_id', $company)->get();
            return $claims;
        }
    }

}