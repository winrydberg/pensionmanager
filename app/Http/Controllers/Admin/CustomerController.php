<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Repository\Interfaces\ClaimRepositoryInterface;
use App\Repository\Interfaces\CustomerRepositoryInterface;
use Illuminate\Http\Request;

class CustomerController extends Controller
{
    private ClaimRepositoryInterface $claimRepository;
    private CustomerRepositoryInterface $customerRepository;

    public function __construct(ClaimRepositoryInterface $claimRepository, CustomerRepositoryInterface $customerRepository)
    {
        $this->claimRepository = $claimRepository;
        $this->customerRepository = $customerRepository;
    }

    public function getCustomers(Request $request){
         $claimid = $request->query('claimid', null);
         if($claimid == null){
            return redirect()->back()->with('error', 'No Claim Selected');
         }
         $customers = $this->customerRepository->getClaimCustomers($claimid);
         $claim = $this->claimRepository->getClaimById($claimid);
         return view('master.claimcustomers', compact('claim', 'customers'));
    }
}
