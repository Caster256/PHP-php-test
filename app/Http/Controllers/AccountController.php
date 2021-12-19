<?php

namespace App\Http\Controllers;

//use Illuminate\Http\Request;
use App\Services\AccountService;

class AccountController extends Controller
{
    private $account;

    public function __construct(AccountService $account)
    {
        $this->account = $account;
    }

    public function index()
    {
        echo 'abc';
    }
}
