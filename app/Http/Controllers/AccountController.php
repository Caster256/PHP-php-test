<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\AccountService;

class AccountController extends Controller
{
    private $account;

    public function __construct(AccountService $account)
    {
        $this->account = $account;
    }

    /**
     * 首頁
     */
    public function index()
    {
        $binding = [
            'title' => 'account list',
            'list' => array()
        ];
        return view('account', $binding);
    }

    /**
     * 處理新增與編輯
     */
    public function edit(Request $request): array
    {
        $data = $request->all();

        return $this->account->CreateOrUpdate($data["values"]);
    }
}
