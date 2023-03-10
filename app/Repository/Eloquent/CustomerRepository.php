<?php

namespace App\Repository\Eloquent;

use App\Models\Customer;
use App\Repository\Interfaces\CustomerRepositoryInterface;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;

class CustomerRepository implements CustomerRepositoryInterface
{    
    public function createCustomer(array $data): ?Model
    {
        return Customer::create($data);
    }

    public function searchCustomerByName($name): Collection 
    {
        return Customer::where('name', 'like', '%'.$name.'%')->with('claims')->orderBy('id', 'desc')->get();
    }

    /**
    * GET CLAIM CUSTOMERS
    */
    public function getClaimCustomers($claimid):Collection
    {
            return Customer::where('claim_id', $claimid)->get();
    }
}