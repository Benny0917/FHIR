<?php
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: GET, POST, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type");
header("Access-Control-Allow-Credentials: true");
header('Content-Type: application/json');

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
    // 從前端接收用戶名和密碼
    $data = json_decode(file_get_contents("php://input"));

    $username = isset($data->username) ? $data->username : '';
    $password = isset($data->password) ? $data->password : '';

    // 執行查詢
    $stmt = $conn->prepare("SELECT * FROM user_login WHERE user_account = ? AND user_password = ?");
    $stmt->bind_param("ss", $username, $password);
    $stmt->execute();
    $result = $stmt->get_result();
    
    // 檢查查詢結果
    if (!$stmt) {
        die("資料庫查詢錯誤: " . $conn->error);
    }
    
    if ($result->num_rows > 0) {
        $user = $result->fetch_assoc();
        // 密碼匹配，將用戶發送回前端
        echo json_encode($user, JSON_PRETTY_PRINT);
    } else {
        // 密碼不匹配或用戶不存在，返回錯誤訊息
        echo json_encode(['error' => 'Invalid username or password. Please try again.']);
        http_response_code(401);  // Unauthorized
        error_log("Login Error: " . json_encode(['error' => 'Invalid username or password. Please try again.']));
    }
}
?>
