<?php
namespace App\Repository\Interfaces;

use App\Model\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;

interface SchemeRepositoryInterface
{
   public function getSchemes(): Collection;
   
   public function getAllSchemes(): Collection;

   public function createScheme(array $details): ?Model;

   public function getSchemeById($schemeId): ?Model;

   public function updateScheme(int $schemeId, array $newDetails): ?Model;

   public function deleteScheme($schemeId);

   public function getAllSchemesPaidCount(): Collection;

   public function getSchemeAuditedClaims($schemeid): Collection; 

}