<?php

namespace Cmat\ReligiousMerit\Exports;

use Cmat\Base\Enums\BaseStatusEnum;
use Cmat\Table\Supports\TableExportHandler;
use Maatwebsite\Excel\Events\AfterSheet;
use PhpOffice\PhpSpreadsheet\Style\Alignment;

class MeritProjectsExport extends TableExportHandler
{
    protected function afterSheet(AfterSheet $event)
    {
        parent::afterSheet($event);
    }
}
