<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Account extends Model
{
    use HasFactory;

    public $table = 'account_info';

    public $timestamps = false;

    protected $fillable = [
        'account', 'username', 'gender', 'birthday', 'email', 'remark'
    ];
}
