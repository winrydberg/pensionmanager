<?php

namespace App\Imports;

use App\DTO\ExcelCustomerData;
use App\Models\Claim;
use App\Models\Customer;
use App\Repository\Interfaces\CustomerRepositoryInterface;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class CustomersFirstSheetImport implements ToCollection, WithHeadingRow
{

    public $claimid;

    public function __construct($claimid)
    {
        $this->claimid = $claimid;
    }


    /**
    * @param Collection $collection
    */
    public function collection(Collection $rows)
    {
        Log::info($rows);
        foreach ($rows as $row) 
        {
            

            $row = $row->toArray();

            if(array_key_exists('no', $row) && trim($row['no']) == 'TOTAL'){
                break;
            }


            $customer = Customer::create([
                    'claim_id' => $this->claimid,
                    'policy_number' => array_key_exists('policy_number', $row) ? ($row['policy_number'] == null?'':$row['policy_number']): '',
                    'name' => array_key_exists('claimant', $row) ? ($row['claimant'] ==null?'':$row['claimant']): '',
                    'claimtype' => array_key_exists('type_of_claim', $row) ? ($row['type_of_claim'] ==null?'':$row['type_of_claim']): '',
                    'amount' => array_key_exists('amount', $row) ? ( $row['amount'] == null ? 0.0:  $row['amount'] ): '',
                    'date_of_approval' => array_key_exists('date', $row) ? ($row['date'] ==null?'':$row['date']): '',
                    'company' => array_key_exists('company', $row) ? ($row['company'] ==null?'':$row['company']) : '',
                    'name_on_cheque' => array_key_exists('name_on_cheque', $row) ? ($row['name_on_cheque']==null?'':$row['name_on_cheque']) : '',
                    'cheque_number' => array_key_exists('cheque_number', $row) ? ($row['cheque_number']==null?'':$row['cheque_number']): '',
                    'beneficiaries' => array_key_exists('beneficiaries', $row) ? ($row['beneficiaries']==null?'':$row['beneficiaries']) : '',
                    'accname' => array_key_exists('account_name', $row) ? ( $row['account_name'] ==null?'': $row['account_name']) : '',
                    'bank' => array_key_exists('bank', $row) ? ($row['bank'] ==null? '' : $row['bank'] ) : '',
                    'bankbranch' => array_key_exists('bank_branch', $row) ? ($row['bank_branch']==null?'':$row['bank_branch']) : '',
                    'accnumber' => array_key_exists('account_number', $row) ? ($row['account_number'] == null ? '' : $row['account_number'] ): '',
            ]);

            DB::table('cutomer_claims')->insert([
                'customer_id' => $customer->id,
                'claim_id' => $this->claimid
            ]);

            $claim = Claim::find($this->claimid);

            if($claim){
                $amount = array_key_exists('amount', $row) ? ( $row['amount'] == null ? 0.0:  $row['amount'] ): 0.0;
                if($claim->claim_amount == null){
                    $claim->claim_amount = $amount;
                    $claim->save();
                }else{
                    $claim->increment('claim_amount', $amount);
                } 
            }

        }
    }
}