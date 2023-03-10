<?php
namespace App\Repository\Interfaces;


use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;

interface CompanyRepositoryInterface
{
   public function getAllCompanies(): Collection;

   public function createCompany(array $companyDetails): ?Model;

   public function getCompanyById($companyId): ?Model;

   public function updateCompany(int $companyId, array $newDetails): ?Model;

   public function deleteCompany($companyId);

   public function getCompanyCount():int;

   public function getCompanyByName($name):Collection;

   public function getCompanyWithClaims($company, $startdate, $enddate): Collection;
}