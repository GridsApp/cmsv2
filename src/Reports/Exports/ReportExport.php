<?php

namespace twa\cmsv2\Reports\Exports;

use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class ReportExport implements FromArray, WithHeadings, WithStyles
{
    protected $rows;
    protected $columns;

    public function __construct(array $rows, array $columns)
    {
        $this->rows = $rows;
        $this->columns = $columns;
    }

    public function array(): array
    {
        return $this->rows;
    }

    public function headings(): array
    {
        return array_map(fn($col) => strip_tags($col['label']), $this->columns);
    }

    public function styles(Worksheet $sheet)
    {
        $footerRowIndex = count($this->rows) + 1; 

        return [
            $footerRowIndex => ['font' => ['bold' => true]]
        ];
    }
}
