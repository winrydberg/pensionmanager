<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Repository\Interfaces\ClaimRepositoryInterface;
use App\Repository\Interfaces\SchemeRepositoryInterface;
use Illuminate\Http\Request;

class SearchController extends Controller
{
    private ClaimRepositoryInterface $claimRepository;
    private SchemeRepositoryInterface $schemeRepository;

    public function __construct( ClaimRepositoryInterface $claimRepository, SchemeRepositoryInterface $schemeRepository)
    {
        $this->claimRepository = $claimRepository;
        $this->schemeRepository = $schemeRepository;
    }


    public function quickSearch(Request $request){
        $year = $request->query('year', null);
        $month = $request->query('month', null);

        

        if($year != null && $month != null){
            $claims = $this->claimRepository->quickSearch($year, $month);
            $months = array(
                1   =>  'January',
                2   =>  'February',
                3   =>  'March',
                4   =>  'April',
                5   =>  'May',
                6   =>  'June',
                7   =>  'July',
                8   =>  'August',
                9   =>  'September',
                10  =>  'October',
                11  =>  'November',
                12  =>  'December'
            );
            $month = $months[$month];
    
            return view('master.quicksearch', compact('month', 'year', 'claims'));
        }else{
            return redirect()->back()->with('quick-error', 'Invalid Search Parameters');
        }
    }


    public function generalSearch(Request $request){
        $schemes = $this->schemeRepository->getSchemes();
        $date = $request->query('date', null);
        $schemeid = $request->query('schemeid', null);

        if($schemeid != null){
            $scheme = $this->schemeRepository->getSchemeById($schemeid);
            $claims = $this->claimRepository->searchClaimByDateAndScheme($date, $schemeid);
            return view('claimentry.generalsearch', compact('schemes', 'claims', 'date', 'scheme'));
        }else{
            $claims = $this->claimRepository->searchClaimByDateAndScheme($date, $schemeid);
            return view('claimentry.generalsearch', compact('schemes', 'claims', 'date'));
        }
       
    }
}
