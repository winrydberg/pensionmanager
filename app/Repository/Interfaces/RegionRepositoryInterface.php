<?php
namespace App\Repository\Interfaces;

use App\Model\Region;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;

interface RegionRepositoryInterface
{
   public function getAllRegions(): Collection;

//    public function createRegion(array $userDetails): ?Model;

   public function getRegionById($regionId): ?Model;

   public function updateRegion(int $regionId, array $newDetails): ?Model;

   public function deleteRegion($regionId);
}