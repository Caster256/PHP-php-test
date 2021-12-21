<?php
// /app/Support/Helpers/Helpers.php

// Helper 檔案路徑
$helpers = [
    'date.php',
    'file.php',
    'value.php'
];

// 載入 Helper 檔案
foreach ($helpers as $helperFileName) {
    include __DIR__ . '/' .$helperFileName;
}
