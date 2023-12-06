<?php

header('Content-Type: application/json'); // 確保正確的 JSON 頭


// 連接資料庫
$servername = "localhost"; // 你的資料庫伺服器位置
$username = "root";    // 資料庫使用者名稱
$password = "b26435851";    // 資料庫密碼
$dbname = "FHIR_patient";     // 資料庫名稱

// 建立資料庫連線
$conn = new mysqli($servername, $username, $password, $dbname);

// 檢查連線是否成功
if ($conn->connect_error) {
    die("資料庫連線失敗: " . $conn->connect_error);
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // 從前端接收用戶名
    $username = isset($_POST['username']) ? $_POST['username'] : '';

    // 執行查詢
    $query = "SELECT * FROM user_login WHERE user_account = '$username'";
    $result = $conn->query($query);

    // 檢查查詢結果
    if (!$result) {
        die("資料庫查詢錯誤: " . $conn->error);
    }

    // 將查詢結果轉換為關聯數組
    $user = $result->fetch_assoc();

    // 將結果發送回前端
    header('Content-Type: application/json');
    echo json_encode($user, JSON_PRETTY_PRINT);
}

// 關閉連接
$conn->close();
?>