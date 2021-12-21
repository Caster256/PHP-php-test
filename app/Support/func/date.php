<?php
/**
 * 回傳指定的日期字串，用於顯示前端
 * @param $date
 * @param int $ver 0 => 'Y年m月d日'
 * @return string
 */
function getDate4View($date, int $ver = 0): string
{
    switch ($ver) {
        case 0:
            $new_date = date('Y年m月d日', strtotime($date));
            break;
        default:
            $new_date = $date;
            break;
    }

    return $new_date;
}

/**
 * 取得資料庫用的日期格式
 * @param $date
 * @param int $ver 0 => 'Y年m月d日'
 * @return false|string
 */
function getDate4DB($date, int $ver = 0) {
    switch ($ver) {
        case 0:
            $new_date = substr($date, 0, -3);
            $new_date = str_replace('年', '-', $new_date);
            $new_date = str_replace('月', '-', $new_date);
            break;
        default:
            $new_date = $date;
            break;
    }

    return $new_date;
}
