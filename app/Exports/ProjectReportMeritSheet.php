<?php

namespace App\Exports;

use Cmat\ReligiousMerit\Enums\PaymentGateTypeEnum;
use Cmat\ReligiousMerit\Enums\ReligiousTypeEnum;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithTitle;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class ProjectReportMeritSheet implements ShouldAutoSize, FromCollection, WithTitle, WithHeadings, WithMapping, WithStyles
{
    /**
    * @return \Illuminate\Support\Collection
    */

    protected $merits;

    public function __construct($merits)
    {
        $this->merits = $merits;
    }

    public function collection()
    {
        return $this->merits;
    }

    public function title(): string
    {
        return 'Danh sách đóng góp';
    }

    public function headings(): array
    {
        return [
            'Người đóng góp',
            'Thời gian',
            'Loại đóng góp',
            'Giá trị',
        ];
    }

    public function map($merit): array
    {
        $type = '';

        if ($merit->type == ReligiousTypeEnum::MONEY) {
            if ($merit->payment_gate == PaymentGateTypeEnum::CASH) {
                $type = 'Tiền mặt';
            } else if ($merit->payment_gate == PaymentGateTypeEnum::TRANSFER) {
                $type = 'Chuyển khoản';
            } else {
                $type = 'Vnpay';
            }
        } else if ($merit->type == ReligiousTypeEnum::EFFORT) {
            $type = 'Công sức';
        } else {
            $type = 'Hiện vật';
        }

        return [
            $merit->is_hidden ? 'Nhà hảo tâm ẩn danh' : $merit->name,
            $merit->created_at->format('h:m d/m/Y'),
            $type,
            $merit->amount,
        ];
    }

    public function styles(Worksheet $sheet)
    {
        return [
            1 => ['font' => ['bold' => true]],
        ];
    }
}
