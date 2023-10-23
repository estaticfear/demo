<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\WithMultipleSheets;

class ProjectReportExport implements WithMultipleSheets
{
    /**
    * @return \Illuminate\Support\Collection
    */

    protected $data;

    public function __construct($data)
    {
        $this->data = $data;
    }

    public function sheets(): array
    {
        return [
            new ProjectReportBudgetSheet($this->data->budgets),
            new ProjectReportMeritSheet($this->data->merits),
        ];
    }
}
