<?php

namespace App\Repository\Eloquent;

use App\Models\Claim;
use App\Models\Scheme;
use App\Repository\Interfaces\SchemeRepositoryInterface;
use Exception;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;

class SchemeRepository implements SchemeRepositoryInterface
{
    public function getSchemes(): Collection
    {
        return Scheme::all();
    }
    /**
     * gets all users/ staff
     */
    public function getAllSchemes(): Collection
    {
        return Scheme::withCount('claims')->get();
    }

    /**
     * Gte user record by id
     */
    public function getSchemeById($schemeId): ?Model
    {
        return Scheme::findOrFail($schemeId);
    }

    public function deleteScheme($schemeId): bool
    {
        try{
            Scheme::destroy($schemeId); 
            return true;
        }catch(Exception $e){
            return false;
        }
        
    }

    public function createScheme(array $details): ?Model
    {
        return Scheme::create($details);
    }


    public function updateScheme(int $schemeId, array $newDetails): ?Model
    {
        return Scheme::whereId($schemeId)->update($newDetails);
    }

    public function getAllSchemesPaidCount():Collection
    {
        $schemes = Scheme::withCount(['claims' => function ($query) {
                $query->where('paid', false)->where('audited', true);
        }])->get();
        return $schemes;
    }


    public function getSchemeAuditedClaims($schemeid): Collection
    {
       return Claim::where('scheme_id', $schemeid)->where('audited', true)->where('paid', false)->with('company', 'scheme')->get();
    }



}