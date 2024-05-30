<?php
header("Content-Type: application/json");

$patient_id = $_GET['id'];

// 這裡假設使用 CURL 來從伺服器獲取資料
$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, "http://182.233.92.77:8080/fhir/Patient/$patient_id");
curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
$response = curl_exec($ch);
curl_close($ch);

$patient = json_decode($response, true);

if ($patient && isset($patient['name'][0])) {
    $patient['name_given'] = implode(' ', $patient['name'][0]['given']);
    $patient['name_family'] = $patient['name'][0]['family'];
}

echo json_encode($patient);


// 從伺服器上抓 版本1
// header("Content-Type: application/json; charset=UTF-8");

// if (isset($_GET['id'])) {
    // $id = $_GET['id'];

    // // Fetch patient data from FHIR server
    // $url = "http://182.233.92.77:8080/fhir/Patient/{$id}";

    // $response = file_get_contents($url);

    // if ($response !== false) {
        // echo $response;
    // } else {
        // http_response_code(404);
        // echo json_encode(["error" => "Patient not found"]);
    // }
// } else {
    // http_response_code(400);
    // echo json_encode(["error" => "Missing patient ID"]);
// }


// 資料庫抓

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
        // echo json_encode($patient);
    // } else {
        // echo json_encode(["message" => "Patient not found"]);
    // }
// } else {
    // echo json_encode(["message" => "Invalid ID"]);
// }

// $conn->close();
?>