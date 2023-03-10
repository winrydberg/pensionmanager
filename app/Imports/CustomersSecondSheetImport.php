<?php

namespace App\Imports;

use App\DTO\ExcelCustomerBreakdownData;
use App\Models\CustomerBreakdown;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Log;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class CustomersSecondSheetImport implements ToCollection, WithHeadingRow
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
        foreach ($rows as $row) 
        {
            // Log::info($row);
            // return;
            if($row['policy_number'] =='' || $row['policy_number'] == null || $row['claimant']==''||$row['claimant'] ==null){
                continue;
            }
            // $data = ExcelCustomerBreakdownData::from($row);
            $row = $row->toArray();
            CustomerBreakdown::create([
               'claim_id' => $this->claimid,
               'staff_no' => array_key_exists('staff_no', $row) ? ($row['staff_no'] ==null ? '' : $row['staff_no']) : '',
               'policy_number_non_comp' => array_key_exists('policy_number_non_comp', $row) ? ($row['policy_number_non_comp'] ==null ? '' : $row['policy_number_non_comp']) : '',
               'policy_number_comp' => array_key_exists('policy_number_comp', $row) ? ($row['policy_number_comp'] ==null ? '' : $row['policy_number_comp']) : '',
               'policy_number' => array_key_exists('policy_number', $row) ? ($row['policy_number'] ==null ? '' : $row['policy_number']) : '',
               'claimant' => array_key_exists('claimant', $row) ? ($row['claimant'] ==null ? '' : $row['claimant']) : '',
               'claimant_portion' => array_key_exists('claimant_portion', $row) ? ($row['claimant_portion'] ==null ? '' : $row['claimant_portion']) : '',
               'non_claimant_portion' => array_key_exists('non_claimant_portion', $row) ? ($row['non_claimant_portion'] ==null ? '' : $row['non_claimant_portion']) : '',
               'employee_contribution' => array_key_exists('employee_contribution', $row) ? ($row['employee_contribution'] ==null ? '' : $row['employee_contribution']) : '',
               'employer_contribution' =>  array_key_exists('employer_contribution', $row) ? ($row['employer_contribution'] ==null ? '' : $row['employer_contribution']) : '',
               'employee_portion_earned' => array_key_exists('employee_portion_earned', $row) ? ($row['employee_portion_earned'] ==null ? '' : $row['employee_portion_earned']) : '',
               'employer_portion_earned' => array_key_exists('employer_portion_earned', $row) ? ($row['employer_portion_earned'] ==null ? '' : $row['employer_portion_earned']) : '',
               'withdrawal_amount' => array_key_exists('withdrawal_amount', $row) ? ($row['withdrawal_amount'] ==null ? '' : $row['withdrawal_amount']) : '',
               'taxable_portion' => array_key_exists('taxable_portion', $row) ? ($row['taxable_portion'] ==null ? '' : $row['taxable_portion']) : '',
               'tax_application' => array_key_exists('tax_application', $row) ? ($row['tax_application'] ==null ? '' : $row['tax_application']) : '',
               'deductible' => array_key_exists('deductible', $row) ? ($row['deductible'] ==null ? '' : $row['deductible']) : '',
               'refund' => array_key_exists('refund', $row) ? ($row['refund'] ==null ? '' : $row['refund']) : '',
               'outstanding_loadn_deduction' => array_key_exists('outstanding_loadn_deduction', $row) ? ($row['outstanding_loadn_deduction'] ==null ? '' : $row['outstanding_loadn_deduction']) : '',
               'employer_deduction' => array_key_exists('employer_deduction', $row) ? ($row['employer_deduction'] ==null ? '' : $row['employer_deduction']) : '',
               'amount_payable_to_ssnit' => array_key_exists('amount_payable_to_ssnit', $row) ? ($row['amount_payable_to_ssnit'] ==null ? '' : $row['amount_payable_to_ssnit']) : '',
               'amount_payable' => array_key_exists('amount_payable', $row) ? ($row['amount_payable'] ==null ? '' : $row['amount_payable']) : '',
               'deductions' => array_key_exists('deductions', $row) ? ($row['deductions'] ==null ? '' : $row['deductions']) : '',
               'beneficiaries' => array_key_exists('beneficiaries', $row) ? ($row['beneficiaries'] ==null ? '' : $row['beneficiaries']) : '',
               'suspense' => array_key_exists('suspense', $row) ? ($row['suspense'] ==null ? '' : $row['suspense']) : ''
            ]);
        }
    }
}
