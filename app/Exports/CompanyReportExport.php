<?php

namespace App\Exports;

use App\Exports\Sheets\CompanyReportSheet;
use App\Models\Company;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\WithMultipleSheets;
use Maatwebsite\Excel\Concerns\Exportable;


class CompanyReportExport implements  WithMultipleSheets
{

    private $data;
    private $totalamt;
    public Company $company;
    
    public function __construct(array $data, $company)
    {
        $this->data = $data['data'];
        $this->totalamt = $data['amount'];
        $this->company = $company;
    }


    public function sheets(): array
    {
        $sheets = [];
        foreach($this->data as $title => $monthRecords) {
            $sheets[] = new CompanyReportSheet($title, $monthRecords['monthtotal'], $monthRecords['breakdown'], $this->company, $this->data[$title]['employees'] );
        }
        return $sheets;
    }

    
}