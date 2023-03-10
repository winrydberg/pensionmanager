<?php
namespace App\Repository\Interfaces;

use Illuminate\Contracts\Pagination\Paginator;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;

interface ClaimRepositoryInterface
{
   public function getPendingClaimCount(): int;

   public function getMonthlyClaimCount(): int;

   public function getClaimWithIssueCount(): int;

   public function getUnProcessedClaimCount(): int;

   public function getClaimByMonth($date): Collection;

   public function getUnAuditedClaims($filterby, $startdate, $enddate): Collection;

   public function getSchemeClaims($filterby, $startdate, $enddate, $schemeid) : Collection;

   public function createClaim(array $claimDetails): ?Model;

   public function getClaimById($claimId): ?Model;

   public function getClaimByClaimId($claimid): Collection;

   public function getSingleClaimByClaimId($claimid): ?Model;

   public function storeClaimFiles($file, $companyName, $claimid, $schemeid, $claimstate): array;

   public function quickSearch($year, $month): Collection;

   public function getClaimsWithIssue(): Collection;

   public function updateClaim($claimid, $newdata): ?Model;

   public function searchClaimByDateAndScheme($date, $schemeid): Collection;

   // public function getClaimCustomers($claimid): Collection;

   public function uploadAuditedFiles($file, $claimid): array;

   public function getAuditedClaims($startdate, $enddate, $filterBy, $companyid, $schemeid): Collection;

   public function getUnProcessedClaims( $schemeid,  $startdate,  $enddate): Collection;

   public function receiveClaimBySchemeAdmin($claimid): array;

   public function deleteClaim(int $id) : array;

   public function getSchemeAdminReceiptCount() : int;

   public function getInvalidClaims($schemeid, $startdate, $enddate) : ?Collection;

   public function updateClaimState(int $claimid, int $state): array;


}