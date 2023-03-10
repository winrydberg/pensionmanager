<?php
namespace App\Repository\Interfaces;

use App\Model\Region;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;

interface IssueRepositoryInterface
{
   public function createIssue(array $data): ?Model;

   public function getUnResolvedIssues(): Collection;

   public function getAllpendingIssues(): Collection;

   public function resolveIssue(string $issueticket, ?array $files, string $resolvemessage): array;

   public function getIssue(string $issueticket): ?Model;

   public function getIssueForClaim($claimId): Collection;

   public function getIssueWithId($id): ?Model;

   public function fileReportIssue($fileid, $message): array;


}