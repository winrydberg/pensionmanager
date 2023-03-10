<?php

namespace App\Http\Controllers\SystemAdmin;

use App\Http\Controllers\Controller;
use App\Http\Requests\NewSchemeFormRequest;
use App\Repository\Eloquent\SchemeRepository;
use App\Repository\Interfaces\SchemeRepositoryInterface;
use Exception;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class SchemeController extends Controller
{
    private SchemeRepositoryInterface $schemeRepository;

    public function __construct(SchemeRepositoryInterface $schemeRepository)
    {
        $this->schemeRepository = $schemeRepository;
    }

    public function newScheme(){
        return view('systemadmins.scheme');
    }

    public function saveScheme(NewSchemeFormRequest $request){
        try{
            $scheme = $this->schemeRepository->createScheme($request->validated());
            return redirect()->back()->with('success', 'Scheme Successfully Created');
        }catch(Exception $e){
            return redirect()->back()->with('error', 'Unable to create');
        }
    }

    public function schemes(){
        $schemes = $this->schemeRepository->getAllSchemesPaidCount();
        $permissions = Permission::all();
        return view('systemadmins.schemes', compact('schemes', 'permissions'));
    }


    public function getSchemeAuditedClaims(Request $request){
        $schemeid = $request->query('schemeid', null);

        $scheme = $this->schemeRepository->getSchemeById($schemeid);
        if($scheme ==null){
            return back()->with('error', 'Scheme Not Found');
        }

        $claims = $this->schemeRepository->getSchemeAuditedClaims($schemeid);

        return view('systemadmins.scheme_unpaid', compact('claims', 'scheme'));
    }
}