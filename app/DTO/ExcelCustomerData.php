<?php

namespace App\DTO;

use Spatie\LaravelData\Data;
use Spatie\LaravelData\Optional;

class ExcelCustomerData extends Data {
    public function __construct(
        public string|Optional $policy_number,
        public string|Optional $claimant,
        public string|Optional $type_of_claim,
        public float|Optional $amount,
        public string|Optional $date_of_approval,
        public string|Optional $company,
        public string|Optional $name_on_cheque,
        public string|Optional $cheque_number,
        public string|Optional $beneficiaries,
        public string|Optional $account_name,
        public string|Optional $bank,
        public string|Optional $branch,
        public string|Optional $account_number
    ) {
    }
}