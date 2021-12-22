<?php

namespace App\Exports;

//use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\FromArray;
use PhpOffice\PhpSpreadsheet\Style\NumberFormat;
use Maatwebsite\Excel\Concerns\WithColumnFormatting;

class AccountInfoExport implements FromArray, WithColumnFormatting
{
    protected $account_info;

    public function __construct(array $account_info)
    {
        $this->account_info = $account_info;
    }

    public function array(): array
    {
        return $this->account_info;
    }

    /**
     * @return array
     */
    public function columnFormats(): array
    {
        return [
            'D' => NumberFormat::FORMAT_DATE_YYYYMMDD
        ];
    }
}
