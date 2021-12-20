<?php

namespace App\Repositories;

use App\Models\Account;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;

class AccountRepository
{
    /**
     * 取得全部的資料
     * @return Builder[]|Collection
     */
    public function getLists()
    {
        return Account::query()->get();
    }

    /**
     * 取得單筆資料
     * @return void
     */
    public function getSingleList($where)
    {

    }

    /**
     * 新增資料
     * @param $data
     * @return bool
     */
    public function insertData($data): bool
    {
        return Account::query()->insert($data);
    }

    /**
     * 更新資料
     * @param $where
     * @param $data
     * @return bool
     */
    public function updateData($where, $data): bool
    {
        return Account::query()->where($where)->update($data);
    }

    /**
     * 刪除資料
     * @return void
     */
    public function deleteData($where)
    {

    }
}
