<?php

namespace App\Exports;

//use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\FromArray;

class AccountInfoExport implements FromArray
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
}
