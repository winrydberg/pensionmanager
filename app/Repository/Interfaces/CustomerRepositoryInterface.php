<?php
namespace App\Repository\Interfaces;

use Illuminate\Contracts\Pagination\Paginator;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;

interface CustomerRepositoryInterface
{
   public function createCustomer(array $data): ?Model;

   public function searchCustomerByName($name): Collection;

   public function getClaimCustomers($claimid): Collection;
}