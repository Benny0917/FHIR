<?php

// 獲取請求的ID
$id = $_GET['id'];

// 定義FHIR伺服器的URL
$url = "http://182.233.92.77:8080/fhir/Patient/$id";

// 使用cURL從FHIR伺服器獲取資料
$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, $url);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
$response = curl_exec($ch);
curl_close($ch);

// 設置回應標頭
header('Content-Type: application/json');
header('Content-Disposition: attachment; filename="' . $id . '.json"');

// 返回從伺服器獲取的資料
echo $response;

// 從伺服器上抓 版本1
// if (isset($_GET['id'])) {
    // $id = $_GET['id'];

    // // Fetch patient data from FHIR server
    // $url = "http://182.233.92.77:8080/fhir/Patient/{$id}";

    // $response = file_get_contents($url);

    // if ($response !== false) {
        // header('Content-Type: application/json');
        // header('Content-Disposition: attachment; filename="'.$id.'.json"');
        // echo $response;
    // } else {
        // http_response_code(404);
        // echo json_encode(["error" => "Patient not found"]);
    // }
// } else {
    // http_response_code(400);
    // echo json_encode(["error" => "Missing patient ID"]);
// }

// 資料庫下載

// header("Content-Type: application/json; charset=UTF-8");

// // 包含資料庫連接文件
// include('connection.php');

// if (isset($_GET['id'])) {
    // $id = $_GET['id'];

    // // 查詢患者資料
    // $sql = "SELECT * FROM Patients WHERE id = ?";
    // $stmt = $conn->prepare($sql);
    // $stmt->bind_param("s", $id);
    // $stmt->execute();
    // $result = $stmt->get_result();
    
    // if ($result->num_rows > 0) {
        // $patient = $result->fetch_assoc();
        // // 生成下载文件
        // $filename = $patient['id'] . ".json";
        // header('Content-Disposition: attachment; filename="' . $filename . '"');
        // echo json_encode($patient);
    // } else {
        // echo json_encode(["message" => "Patient not found"]);
    // }
// } else {
    // echo json_encode(["message" => "Invalid ID"]);
// }

// $conn->close();
?>