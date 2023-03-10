<?php

namespace App\Repository\Eloquent;

use App\Models\Region;
use App\Repository\Interfaces\RegionRepositoryInterface;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;

class RegionRepository implements RegionRepositoryInterface
{
    public function getAllRegions():Collection{
        return Region::all();
    }


    public function getRegionById($regionId): ?Model
    {
        return Region::findOrFail($regionId);
    }

    
    public function createRegion(array $userDetails): ?Model
    {
        return Region::create($userDetails);
    }


    public function updateRegion($regionId, array $newDetails) :?Model
    {
        return Region::whereId($regionId)->update($newDetails);
    } 

    public function deleteRegion($regionId)
    {
        Region::destroy($regionId);
    }

}