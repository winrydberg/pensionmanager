<?php
namespace App\Repository\Interfaces;

use App\Model\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;

interface ReportRepositoryInterface
{
   public function generateCompanyReports($startdate, $enddate, $companyid): Collection;

   public function generateCompanyBreakdownReports($startdate, $enddate, $companyid): array;

   public function reportsBreakDown($month, $company, $scheme): array;

   public function generateSchemeReports($startdate, $enddate, $schemeid): Collection;

   public function generateSchemeBreakdownReports($startdate, $enddate, $schemeid): array;
}