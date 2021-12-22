<?php

namespace App\Services;

use App\Repositories\AccountRepository;
use App\Exports\AccountInfoExport;
use App\Imports\AccountInfoImport;
use Maatwebsite\Excel\Facades\Excel;
use PhpOffice\PhpSpreadsheet\Shared\Date;

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

            if($this->account->updateData($where, $data) < 0) {
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
                }

                $arr[$idx][] = $value;
            }
        }
        //將 header 合併上去
        array_unshift($arr, $header);

        //使用匯出 excel 套件
        $export = new AccountInfoExport($arr);
        //儲存落地檔
        Excel::store($export, $file_name);

        return $file_name;
    }

    /**
     * 匯入檔案
     * @param $files
     * @return array
     */
    public function import($files): array
    {
        $response = array();
        $response["status"] = 'success';
        $response["msg"] = '';

        //建立匯入用資料夾
        $dir_path = createDir(public_path(), array('import'));

        foreach($files as $file) {
            $original_name = $file->getClientOriginalName();
            //搬移檔案
            $file_name = time() . '-' . $original_name;
            $file->move($dir_path, $file_name);
            //檔案路徑
            $file_path = $dir_path . '/' . $file_name;

            //讀取 excel 檔案，並轉成 array
            $array = (new AccountInfoImport)->toArray($file_path);

            //取得整理完的資訊
            $lists = $this->checkImportData($array[0]);

            //若為 false 表示資料有誤
            if($lists) {
                foreach($lists as $list) {
                    if(!$this->account->createOrUpdateData($list['key'], $list['data'])) {
                        $response["status"] = 'failure';
                        $response['msg'] .= $original_name . " 匯入失敗，請檢查資料是否正確\n";
                    }
                }
            } else {
                $response["status"] = 'failure';
                $response['msg'] .= $original_name . " 匯入失敗，請檢查資料是否正確\n";
            }
        }

        return $response;
    }

    /**
     * 檢查匯入資料是否正確
     * @param $data
     * @return array|false
     */
    private function checkImportData($data)
    {
        //只能出現這些欄位
        $header = ['account', 'username', 'gender', 'birthday', 'email', 'remark'];
        $arr = array();

        //取得 title
        $original_header = $data[0];
        //移除 title 的值
        array_shift($data);

        //比對後若還有值表示欄位不正確
        if(!empty(array_diff($header, $original_header))) {
            return false;
        }

        //整理資料
        foreach($data as $idx => $list) {
            foreach($list as $idx2 => $value) {
                $title = $original_header[$idx2];

                //如果必填欄位是空的表示資料有誤
                if($title != 'remark' && empty($value)) {
                    return false;
                }

                //進階處理資料
                switch ($title) {
                    case "account":
                        $value = strtolower($value);
                        break;
                    case "gender":
                        $value = getGender($value);
                        break;
                    case "birthday":
                        if(is_numeric($value)) {
                            $value = Date::excelToDateTimeObject($value)->format('Y-m-d');
                        } else {
                            $value = date("Y-m-d", strtotime($value));
                        }
                        break;
                    default:
                        break;
                }

                if($title == 'account') {
                    $arr[$idx]['key'][$title] = $value;
                } else {
                    $arr[$idx]['data'][$title] = $value;
                }
            }
        }

        return $arr;
    }
}
