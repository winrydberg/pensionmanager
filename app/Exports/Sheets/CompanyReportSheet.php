<?php

namespace App\Exports\Sheets;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\Log;
use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Concerns\WithColumnWidths;
// use Maatwebsite\Excel\Concerns\WithColumnFormatting;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use Maatwebsite\Excel\Events\AfterSheet;
use Maatwebsite\Excel\Events\BeforeSheet;
use PhpParser\ErrorHandler\Collecting;

class CompanyReportSheet implements FromArray, WithTitle, WithHeadings, ShouldAutoSize, WithStyles, WithEvents, WithColumnWidths
{
    private $title;
    private $breakdown;
    private $subtotal;
    private $company;
    private $employees;

    /**
     * 
     * 
     */
    public function __construct(string $title, $subtotal, array $breakdown, $company,  $employees)
    {
        $this->title = $title;
        $this->subtotal  = $subtotal;
        $this->breakdown  = $breakdown;
        $this->company = $company;    
        $this->employees = array_merge(...$employees);  
    }

    /**
     * 
     * 
     */
    public function registerEvents(): array
    {
        return [            
           AfterSheet::class => function(AfterSheet $event) {
                $sheet = $event->getSheet();
                $sheet->mergeCells('E1:J1');
                $sheet->setCellValue('E1', 'EMPLOYEE BREAKDOWN');
                $sheet->setCellValue('E2', 'Employee Name');
                $sheet->setCellValue('F2', 'Amount(GHC)');
                $sheet->setCellValue('G2', 'Account No');
                $sheet->setCellValue('H2', 'Claim Type');
                $sheet->setCellValue('I2', 'Cheque No.');
                $sheet->getStyle('E1')->getFont()->setBold(true);
                $sheet->getStyle('E2:I2')->getFont()->setBold(true);
                $sheet->getStyle('A1:C1')->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID) ->getStartColor()->setARGB('007bff');
                $sheet->getStyle('E1')->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID) ->getStartColor()->setARGB('007bff');
                $sheet->getStyle('D')->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID) ->getStartColor()->setARGB('6c757d');

                foreach($this->employees as $key => $emp){
                    $sheet->setCellValue('E'.((int)$key+3), $emp['name']);
                    $sheet->setCellValue('F'.((int)$key+3), $emp['amount']);
                    $sheet->setCellValue('G'.((int)$key+3), $emp['accnumber']);
                    $sheet->setCellValue('H'.((int)$key+3), $emp['claimtype']);
                    $sheet->setCellValue('I'.((int)$key+3), $emp['cheque_number']);
                }
            },
                        
        ];
    }

    /**
     * 
     * 
     */
    public function array(): array
    {
        $data = [];
        foreach($this->breakdown as $datecreated => $value){
            array_push($data, [
                $datecreated,
                $value,
                strtoupper($this->company->name)
            ]);
        }
        array_push($data, ['', '', '']);
        array_push($data, ['', '', '']);
        array_push($data, ['TOTAL ( GHC ) ', $this->subtotal]);

        return $data;
    }

    /**
     * 
     * 
     */
    public function title(): string
    {
        return $this->title;
    }

    /**
     * 
     * 
     */
    public function headings(): array
    {
        return [
            'DATE',
            'AMOUNT (GHC)',
            'COMPANY NAME'
        ];
    }

    /**
     * 
     * 
     */
    public function styles(Worksheet $sheet)
    {
        // $sheet->getStyle('A1:B2')->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID) ->getStartColor()->setARGB('FFFF0000');
        return [
            // Style the first row as bold text.
            1    => ['font' => ['bold' => true]],
            ((int)count($this->breakdown)+4) => ['font' => ['bold' => true]],
        ];
    }

    /**
     * 
     * 
     */
    public function columnWidths(): array
    {
        return [
            'D' => 3,
            'E' => 25,
            'F' => 15,            
            'G' => 25,  
            'H' => 20,  
            'I' => 25,         
            'J' => 15
        ];
    }
}