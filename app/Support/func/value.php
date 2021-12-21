<?php
/**
 * 取得相對應的性別
 * @param $val
 * @return string
 */
function getGender($val): string {
    //判斷若為數字則回傳中文，反之
    if(is_numeric($val)) {
        return $val == '1' ? '男' : '女';
    } else {
        return $val == '男' ? '1' : '0';
    }
}
