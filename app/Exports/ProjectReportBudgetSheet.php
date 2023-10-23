<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithTitle;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class ProjectReportBudgetSheet implements ShouldAutoSize, FromCollection, WithTitle, WithHeadings, WithStyles
{
    /**
    * @return \Illuminate\Support\Collection
    */

    protected $budgets;

    public function __construct($budgets)
    {
        $this->budgets = $budgets;
    }

    public function collection()
    {
        return collect($this->budgets);
    }

    public function title(): string
    {
        return 'Dự trù kinh phí';
    }

    public function headings(): array
    {
        return [
            'Tên hạng mục',
            'Số lượng',
            'Đơn vị tính',
            'Thành tiền',
            'Trạng thái',
        ];
    }

    public function styles(Worksheet $sheet)
    {
        return [
            1 => ['font' => ['bold' => true]],
        ];
    }
}
