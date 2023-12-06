<?php
// 設定 MySQL 的連線資訊並開啟連線
$link = mysqli_connect("localhost", "root", "b26435851", "FHIR_patient");

if (!$link) {
    die("Connection failed: " . mysqli_connect_error());
}

$link->set_charset("UTF8"); // 設定語系避免亂碼

// SQL 指令，從 table1 中擷取資料
$sqlTable1 = "SELECT * FROM patient";
$resultTable1 = $link->query($sqlTable1);

// SQL 指令，從 table2 中擷取資料
$sqlTable2 = "SELECT * FROM cancer_reg_short_form";
$resultTable2 = $link->query($sqlTable2);

$sqlTable3 = "SELECT * FROM user_login";
$resultTable3 = $link->query($sqlTable3);

// 初始化 $output 陣列
$output = array();

// 當指令執行有回傳
while ($row = $resultTable1->fetch_assoc()) {
    $output[] = $row; // 將 table1 的資料加入 $output 陣列
}

// 再次執行同樣的操作，將 table2 的資料加入 $output 陣列
while ($row = $resultTable2->fetch_assoc()) {
    $output[] = $row; // 將 table2 的資料加入 $output 陣列
}

while ($row = $resultTable3->fetch_assoc()) {
    $output[] = $row; // 將 table2 的資料加入 $output 陣列
}
// 判斷是否有資料返回
if (!empty($output)) {
    // 將資料陣列轉成 JSON 並顯示在網頁上，並要求不把中文編成 UNICODE
    print(json_encode($output, JSON_UNESCAPED_UNICODE));
} else {
    // 若沒有資料返回，顯示相應訊息
    print("No data found.");
}

$link->close(); // 關閉資料庫連線
?>

