<?php

namespace App\Repositories;

interface CRUD
{
    //取得全部資料
    public function getLists();

    //取得單筆資料
    public function getSingleList($where);

    //新增資料
    public function insertData($data);

    //修改資料
    public function updateData($where, $data);

    //刪除資料
    public function deleteData($ids);
}
