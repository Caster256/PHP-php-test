<?php

namespace App\Services;

use App\Repositories\AccountRepository;
use App\Exports\AccountInfoExport;
use Maatwebsite\Excel\Facades\Excel;

class AccountService
{
    private $account;

    public function __construct(AccountRepository $account)
    {
        $this->account = $account;
    }

    /**
     * 取得資料
     * @return array
     */
    public function getLists(): array
    {
        //取得清單資料
        $lists = $this->account->getLists();
        $arr = array();

        foreach($lists as $list) {
            $arr[] = array(
                'id' => $list['id'],
                'account' => $list['account'],
                'username' => $list['username'],
                'gender' => getGender($list['gender']),
                'birthday' => getDate4View($list['birthday']),
                'email' => $list['email'],
                'remark' => $list['remark']
            );
        }

        return $arr;
    }
    /**
     * 新增或修改資料
     * @param $data
     * @return array
     */
    public function CreateOrUpdate($data): array
    {
        $response = array();
        $response["status"] = 'success';

        /* 移除不必要的值 */
        //token
        array_shift($data);

        //帳號轉小寫
        $data['account'] = strtolower($data['account']);

        //判斷若有 data_id 表示為更新
        if(array_key_exists('data_id', $data)) {
            $id = array_pop($data);
            $where = [['id', $id]];

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

    /**
     * 刪除資料
     * @param $data
     * @return array
     */
    public function deleteData($data): array
    {
        $response = array();
        $response["status"] = 'success';

        if(!$this->account->deleteData($data)) {
            $response["status"] = 'failure';
            $response["msg"] = 'delete data error!';
        }

        return $response;
    }

    /**
     * 匯出資料
     * @param $data
     * @return array
     */
    public function export($data): array
    {
        $response = array();
        $response["status"] = 'success';

        //判斷是否有勾選資料
        if(array_key_exists("ids", $data)) {
            $where['whereIn'] = true;
            $where['ids'] = $data['ids'];
            $lists = $this->account->getLists($where);
        } else {
            $lists = $this->account->getLists();
        }

        //選擇匯出的類型
        switch ($data['type']) {
            case 'txt':
                $response['file_name'] = $this->exportTxt($lists);
                break;
            case 'xlsx':
                $response['file_name'] = $this->exportXlsx($lists);
                break;
            default:
                $response['status'] = 'failure';
                $response['msg'] = '匯出的 type 錯誤!';
                break;
        }

        return $response;
    }

    /**
     * 寫入檔案
     * @param $lists
     * @return string
     */
    private function exportTxt($lists): string
    {
        //建立資料夾與檔案
        $dir_path = createDir(public_path(), array('export'));
        $file_name = time() . '.txt';
        $file_path = $dir_path . '/' . $file_name;
        $header = 'account, username, gender, birthday, email, remark' . PHP_EOL;

        //寫入 title
        createFile($file_path, $header);

        $str = '';
        foreach($lists as $list) {
            //轉成陣列
            $list = json_decode($list, true);

            foreach($list as $key => $value) {
                //排除不顯示的欄位
                if($key == 'id' || $key == 'created_at' || $key == 'updated_at') { continue; }

                //資料處理
                if($key == 'gender') {
                    $value = getGender($value);
                } else if($key == 'birthday') {
                    $value = getDate4View($value);
                }

                $str .= $value . ', ';
            }
            $str = substr($str, 0, -2) . PHP_EOL;
        }

        //寫入檔案
        createFile($file_path, $str);

        return $file_name;
    }

    /**
     * @param $lists
     * @return string
     */
    private function exportXlsx($lists): string
    {
        $file_name = time() . '.xlsx';
        $header = ['account', 'username', 'gender', 'birthday', 'email', 'remark'];
        $arr = array();

        foreach($lists as $idx => $list) {
            //轉成陣列
            $list = json_decode($list, true);

            foreach($list as $key => $value) {
                //排除不顯示的欄位
                if($key == 'id' || $key == 'created_at' || $key == 'updated_at') { continue; }

                //資料處理
                if($key == 'gender') {
                    $value = getGender($value);
                } else if($key == 'birthday') {
                    $value = getDate4View($value);
                }

                $arr[$idx][] = $value;
            }
        }
        //將 header 合併上去
        array_unshift($arr, $header);

        $export = new AccountInfoExport($arr);
        Excel::store($export, $file_name);

        return $file_name;
    }
}
