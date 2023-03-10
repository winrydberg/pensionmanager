<?php

namespace App\DTO;

use Spatie\LaravelData\Data;
use Spatie\LaravelData\Optional;

class ExcelCustomerBreakdownData extends Data {
    public function __construct(
        public string|Optional $staff_no,
        public string|Optional $policy_number_non_comp,
        public string|Optional $policy_number_comp,
        public string|Optional $policy_number,
        public string|Optional $claimant,
        public string|Optional $compliant_portion,
        public string|Optional $non_compliant_portion,
        public float|Optional $employee_contribution,
        public float|Optional $employer_contribution,
        public string|Optional $employee_portion_earned,
        public string|Optional $employer_portion_earned,
        public float|Optional $withdrawal_amount,
        public string|Optional $taxable_portion,
        public float|Optional $tax_application,
        public float|Optional $deductible,
        public float|Optional $refund,
        public float|Optional $outstanding_loadn_deduction,
        public float|Optional $employer_deduction,
        public float|Optional $amount_payable_to_ssnit,
        public float|Optional $amount_payable,
        public float|Optional $deductions,
        public string|Optional $beneficiaries,
        public float|Optional $suspense
    ) {
    }
}