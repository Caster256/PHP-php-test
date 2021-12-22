<?php

namespace App\Imports;

use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\Importable;

class AccountInfoImport implements ToModel
{
    use Importable;

    public function model(array $row) {}
}
