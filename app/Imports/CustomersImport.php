<?php

namespace App\Imports;

use App\Models\Customer;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Log;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithMultipleSheets;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithConditionalSheets;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Events\BeforeImport;

class CustomersImport implements WithMultipleSheets, WithEvents
{
    // use WithConditionalSheets;

    public $claimid;
    public $totalSheets = 1;

    public function __construct($claimid)
    {
        $this->claimid = $claimid;
    }

    public function registerEvents(): array
    {
        return [
            BeforeImport::class => function (BeforeImport $event) {
                $totalRows = $event->getReader()->getTotalRows();
                if (!empty($totalRows)) {
                    $this->totalSheets = count($totalRows);
                }
            }
        ];
    }
    
    public function sheets(): array
    {
        Log::info("Totttalsheet=================". $this->totalSheets);
        if($this->totalSheets > 1){
            return [
                new CustomersFirstSheetImport($this->claimid),
                new CustomersSecondSheetImport($this->claimid),
             ];
        }else{
            return [
                new CustomersFirstSheetImport($this->claimid),
             ];
        }  
    }

    // public function conditionalSheets(): array
    // {
    //     return [
    //         new CustomersFirstSheetImport($this->claimid),
    //         new CustomersSecondSheetImport($this->claimid),
    //     ];
    // }
}
