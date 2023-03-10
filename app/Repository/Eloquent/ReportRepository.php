<?php

namespace App\Repository\Eloquent;

use App\Models\Claim;
use App\Models\Customer;
use App\Models\User;
use App\Repository\Interfaces\ReportRepositoryInterface;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;

class ReportRepository implements ReportRepositoryInterface
{
    public function generateCompanyReports($startdate, $enddate, $companyid): Collection
    {
        $claims = Claim::select(
                            DB::raw('sum(claim_amount) as sums'), 
                            DB::raw("DATE_FORMAT(created_at,'%M %Y') as months")
                        )->where('created_at','>=', $startdate)
                        ->where('created_at', '<=',$enddate)
                        ->where('paid', true)
                        ->where('company_id', $companyid)
                        ->groupBy('months','scheme_id')
                        ->get();
        return $claims;
    }

    public function generateCompanyBreakdownReports($startdate, $enddate, $companyid): array
    {
        $claims = Claim::where('created_at','>=', $startdate)
                        ->where('created_at', '<=',$enddate)
                        ->where('company_id', $companyid)
                        ->with('scheme', 'customers')
                        ->get();
        $data = [];

        $sum = 0.0;
        
        foreach($claims as $claim){
            $createdmonth = date('F Y', strtotime($claim->created_at)); //date_format($claim->created_at,"M, Y"); //date("%m %Y", strtotime($claim->created_at));
            $createdat = date('d-m-Y', strtotime($claim->created_at));
            $sum += (float)$claim->claim_amount;
            if(array_key_exists($createdmonth, $data)){
                if(array_key_exists($createdat, $data[$createdmonth]['breakdown'])){
                    $data[$createdmonth]['monthtotal'] += (float)$claim->claim_amount;
                    if($claim->customers->count() > 0){
                     array_push($data[$createdmonth]['employees'], $claim->customers->toArray());
                    }
                    $data[$createdmonth]['breakdown'][$createdat] += (float)$claim->claim_amount;
                }else{
                    $data[$createdmonth]['monthtotal'] += (float)$claim->claim_amount;
                    if($claim->customers->count() > 0){
                     array_push($data[$createdmonth]['employees'], $claim->customers->toArray());
                    }
                    $data[$createdmonth]['breakdown'][$createdat] = (float)$claim->claim_amount;
                }
            }else{
                $data[$createdmonth] = [
                    'monthtotal' => (float)$claim->claim_amount,
                    'employees' => [$claim->customers->toArray()], //->count() > 0 ? [$claim->customers]: [],
                    'breakdown' =>  [$createdat => (float)$claim->claim_amount]
                ];
            }
        }
        return [
            'amount' => $sum,
            'data' => $data
        ];
    }

    /**
     * 
     * 
     */
    public function reportsBreakDown($month, $company=null, $scheme=null): array
    {
        if($company == null && $scheme==null){
            return [
                'status' => 'error',
                'message' => 'Unable to generate reports. SchemeId and Company Id not provided'
            ];
        }
        if($company != null){
            //get breakdown for company and selected month
            $claimsIds = Claim::where('company_id', $company)->whereMonth('created_at', date_parse($month)['month'])->whereYear('created_at', date_parse($month)['year'])->pluck('id');
           // Log::info($claimsIds);
            $employees = Customer::whereIn('claim_id', $claimsIds)->get();
            return [
                'status' => 'success',
                'employees' => $employees
            ];
        }
        if($scheme != null){
            //get breakdown for company and selected month
            $claimsIds = Claim::where('scheme_id', $scheme)->whereMonth('created_at', date_parse($month)['month'])->whereYear('created_at', date_parse($month)['year'])->pluck('id');
            //Log::info($claimsIds);
            $employees = Customer::whereIn('claim_id', $claimsIds)->get();
            return [
                'status' => 'success',
                'employees' => $employees
            ];
        }
        return [];
    }


    /**
     * 
     * 
     */
    public function generateSchemeReports($startdate, $enddate, $scheme): Collection
    {
        $claims = Claim::select(
                        DB::raw('sum(claim_amount) as sums'), 
                        DB::raw("DATE_FORMAT(created_at,'%M %Y') as months")
                    )->where('created_at','>=', $startdate)
                    ->where('created_at', '<=',$enddate)
                    ->where('paid', true)
                    ->where('scheme_id', $scheme)
                    ->groupBy('months')
                    ->get();
        return $claims; 
    }



    /**
     * 
     * 
     */
    public function generateSchemeBreakdownReports($startdate, $enddate, $schemeid): array 
    {
         $claims = Claim::where('created_at','>=', $startdate)
                        ->where('created_at', '<=',$enddate)
                        ->where('scheme_id', $schemeid)
                        ->with('customers')
                        ->get();
        $data = [];

        $sum = 0.0;
        
        foreach($claims as $claim){
            $createdmonth = date('F Y', strtotime($claim->created_at)); //date_format($claim->created_at,"M, Y"); //date("%m %Y", strtotime($claim->created_at));
            $createdat = date('d-m-Y', strtotime($claim->created_at));
            $sum += (float)$claim->claim_amount;
            if(array_key_exists($createdmonth, $data)){
                if(array_key_exists($createdat, $data[$createdmonth]['breakdown'])){
                    $data[$createdmonth]['monthtotal'] += (float)$claim->claim_amount;
                    if($claim->customers->count() > 0){
                     array_push($data[$createdmonth]['employees'], $claim->customers->toArray());
                    }
                    $data[$createdmonth]['breakdown'][$createdat] += (float)$claim->claim_amount;
                }else{
                    $data[$createdmonth]['monthtotal'] += (float)$claim->claim_amount;
                    if($claim->customers->count() > 0){
                     array_push($data[$createdmonth]['employees'], $claim->customers->toArray());
                    }
                    $data[$createdmonth]['breakdown'][$createdat] = (float)$claim->claim_amount;
                }
            }else{
                $data[$createdmonth] = [
                    'monthtotal' => (float)$claim->claim_amount,
                    'employees' => [$claim->customers->toArray()], //->count() > 0 ? [$claim->customers]: [],
                    'breakdown' =>  [$createdat => (float)$claim->claim_amount]
                ];
            }
        }
        return [
            'amount' => $sum,
            'data' => $data
        ];
    }


}