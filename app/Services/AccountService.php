<?php

namespace App\Services;

use App\Repositories\AccountRepository;

class AccountService
{
    private $account;

    public function __construct(AccountRepository $account)
    {
        $this->account = $account;
    }

    /**
     * 取得資料
     * @return void
     */
    public function getLists()
    {
        //取得清單資料
        $lists = $this->account->getLists();
    }
    /**
     * 新增或修改資料
     * @param $data
     * @return array
     */
    public function CreateOrUpdate($data)
    {
        $response = array();
        $response["status"] = 'success';

        /* 移除不必要的值 */
        //token
        array_shift($data);

        //判斷若有 data_id 表示為更新
        if(array_key_exists('data_id', $data)) {
            $id = array_pop($data);
            $where = [['id' => $id]];

            if(!$this->account->updateData($where, $data)) {
                $response["status"] = 'failure';
                $response["msg"] = 'update data error!';
            }
        } else {
            if(!$this->account->insertData($data)) {
                $response["status"] = 'failure';
                $response["msg"] = 'insert data error!';
            }
        }

        return $response;
    }
}
