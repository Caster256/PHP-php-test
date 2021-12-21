<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\AccountService;

class AccountController extends Controller
{
    private $account;
    private $request;

    public function __construct(AccountService $account, Request $request)
    {
        $this->account = $account;
        $this->request = $request;
    }

    /**
     * 首頁
     */
    public function index()
    {
        $binding = [
            'title' => 'account list',
            'list' => $this->account->getLists()
        ];
        return view('account', $binding);
    }

    /**
     * 處理新增與編輯
     * @return array
     */
    public function edit(): array
    {
        $data = $this->request->all();

        return $this->account->CreateOrUpdate($data["values"]);
    }

    /**
     * 刪除資料
     * @return array
     */
    public function delete(): array
    {
        $data = $this->request->all();

        return $this->account->deleteData($data['values']);
    }

    /**
     * 匯出檔案
     * @return array
     */
    public function export(): array
    {
        $data = $this->request->all();

        return $this->account->export($data['values']);
    }

    /**
     * 下載檔案
     * @param $file_name
     * @return void
     */
    public function download($file_name)
    {
        $type = pathinfo($file_name, PATHINFO_EXTENSION);
        $file_path = $type == 'txt' ? public_path('export') . '/' . $file_name :
            storage_path('app') . '/' . $file_name;
        downFile($file_path, 'account_info.' . pathinfo($file_name, PATHINFO_EXTENSION));
    }
}
