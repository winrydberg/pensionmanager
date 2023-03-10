<?php

namespace App\Exports;

use App\Exports\Sheets\SchemeReportSheet;
use App\Models\Scheme;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithMultipleSheets;

class SchemeReportExport implements WithMultipleSheets
{
    private $data;
    private $totalamt;
    public Scheme $scheme;
    
    public function __construct(array $data, $scheme)
    {
        $this->data = $data['data'];
        $this->totalamt = $data['amount'];
        $this->scheme = $scheme;
    }

    /**
     * 
     * 
     */
    public function sheets(): array
    {
        $sheets = [];
        foreach($this->data as $title => $monthRecords) {
            $sheets[] = new SchemeReportSheet($title, $monthRecords['monthtotal'], $monthRecords['breakdown'], $this->scheme, $this->data[$title]['employees'] );
        }
        return $sheets;
    }
}