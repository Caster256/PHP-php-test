<?php
header('Content-Type:text/html;charset=utf-8');

/**
 * 建立檔案
 */
function createFile($file_path, $str) {
    $fp = fopen($file_path, "a+");

    fwrite($fp, $str);
}

/**
 * 下載檔案
 * @param $file_path
 * @param $file_name
 * @return void
 */
function downFile($file_path, $file_name) {
    $file_size = filesize($file_path);
    header('Pragma: public');
    header('Expires: 0');
    header('Last-Modified: ' . gmdate('D, d M Y H:i ') . ' GMT');
    header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
    header('Cache-Control: private', false);
    header('Content-Type: application/octet-stream');
    header('Content-Length: ' . $file_size);
    header('Content-Disposition: attachment; filename="' . $file_name . '";');
    header('Content-Transfer-Encoding: binary');
    readfile($file_path);
}

/**
 * 建立資料夾
 * @param $path
 * @param array $next_floor 如果 $path 這一層底下還需要新增資料夾則透過陣列表示階層
 * @return string 回傳最後的資料夾路徑
 */
function createDir($path, array $next_floor): string {
    foreach($next_floor as $dir) {
        $path .= "/" . $dir;

        if(!is_dir($path)) {
            mkdir($path);
        }
    }

    return $path;
}
