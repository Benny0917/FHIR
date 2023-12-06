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

function searchPatientInDatabase($patient_identifier, $conn) {
    $sql = "SELECT * FROM patient WHERE patient_identifier = '$patient_identifier'";
    $result = $conn->query($sql);   

    if ($result->num_rows > 0) {
        $patientData = array();
        while ($row = $result->fetch_assoc()) {
            // 將每一行資料轉換為 FHIR 標準的格式
            $patient = array(
                "resourceType" => "Patient",
                "id" => $row["patient_id"],
                "meta" => [
                    "profile" => null
                ],
                "text" => array(
                    "status" => "generated",
                    "div" => "<div>{$row["patient_text"]}</div>",
                ),
                "extension" => [
                    [
                        "url" => "https://twcore.mohw.gov.tw/ig/twcore/StructureDefinition/person-age",
                        "valueAge" => [
                            "value" => 32,
                            "system" => "http://unitsofmeasure.org",
                            "code" => "a"
                        ]
                    ],
                    [
                        "extension" => [
                            [
                                "url" => "code",
                                "valueCodeableConcept" => [
                                    "coding" => [
                                        [
                                            "system" => "urn:iso:std:iso:3166",
                                            "code" => "TW"
                                        ]
                                    ]
                                ]
                            ]
                        ],
                        "url" => "http://hl7.org/fhir/StructureDefinition/patient-nationality"
                    ]
                ],
                "identifier" => array(
                    [
                        "use" => "official",
                        "type" => [
                            "coding" => [
                                [
                                    "system" => "http://terminology.hl7.org/CodeSystem/v2-0203",
                                    "code" => "NNxxx",
                                    "_code" => [
                                        "extension" => [
                                            [
                                                "extension" => [
                                                    [
                                                        "url" => "suffix",
                                                        "valueString" => "TWN"
                                                    ],
                                                    [
                                                        "url" => "valueSet",
                                                        "valueCanonical" => null,
                                                    ]
                                                ],
                                                "url" => "https://twcore.mohw.gov.tw/ig/twcore/StructureDefinition/identifier-suffix"
                                            ]
                                        ]
                                    ]
                                ]
                            ]
                        ],
                        "system" => "http://www.moi.gov.tw/",
                        "value" => $row["patient_identifier"]
                    ],
                    [
                        "use" => "official",
                        "type" => [
                            "coding" => [
                                [
                                    "system" => "http://terminology.hl7.org/CodeSystem/v2-0203",
                                    "code" => "MR"
                                ]
                            ]
                        ],
                        "system" => "https://www.tph.mohw.gov.tw/",
                        "value" => $row["patient_identifier"],
                    ]
                    ),
                "name" => array(
                    "use" => "official",
                    "name" => $row["patient_name"]
                ),
                "gender" => $row["patient_gender"],
                "birthDate" => $row["patient_birthDate"],
                "active" => $row["patient_active"],
                "address" => array(
                    "use" => "home",
                    "type" => "both",
                    "text" => $row["patient_address"],
                    "line" => "534 Erewhon St",
                    "city" => "Taipei",
                    "district" => "Rainbow",
                    "state" => "none",
                    "postalCode" => "3999",
                ),
                "contact" => array(
                    [
                        "relationship" => [
                            [
                                "coding" => [
                                    [
                                        "system" => "http://terminology.hl7.org/CodeSystem/v3-RoleCode",
                                        "code" => "FTH"
                                    ]
                                ]
                            ]
                        ],
                        "name" => null,
                        "telecom" => [
                            [
                                "system" => "phone",
                                "value" => $row["patient_contact"], 
                                "use" => "mobile",
                                "period" => [
                                    "start" => null,
                                    "end" => null
                                ]
                            ]
                        ]
                    ]
                ),
                "telecom" => array(
                    "system" => "phone",
                    "value" =>  $row["patient_telecom"],
                    "use" => "mobile",
                    "period" => [
                        "start" => "2030-12-15",
                        "end" => null
                    ]
                ),
                "maritalStatus" => array(
                    "coding" => [
                        [
                            "system" => "http://terminology.hl7.org/CodeSystem/v3-MaritalStatus",
                            "code" => $row["patient_maritalStatus"]
                        ]
                    ]
                ),


                // 添加更多屬性...
            );

            // 將每個病人的資料添加到陣列中
            $patientData[] = $patient;
        }

        // 將整個陣列轉換為 JSON 格式並返回給前端
        echo json_encode($patientData, JSON_PRETTY_PRINT | JSON_NUMERIC_CHECK);
    } else {
        // 添加調試語句
        echo "No results found.";
        echo json_encode([], JSON_PRETTY_PRINT | JSON_NUMERIC_CHECK);
    }
}

$patient_identifier = $_GET["patient_identifier"];

if (empty($patient_identifier)) {
    die("請輸入要查詢的 ID");
}

searchPatientInDatabase($patient_identifier, $conn);

// 取得前端送來的病歷號
// $identifier = $_GET['identifier'];


// 查詢資料庫
/*$sql = "SELECT * FROM patient WHERE identifier = '$identifier'";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    // 找到相符的資料
    $row = $result->fetch_assoc();

    // 初始化 'isChecked' 屬性
    // $row['isChecked'] = false;

    // 添加 resourceType 屬性
    $row['resourceType'] = "Patient";

    // 轉換成 JSON 格式回傳給前端
    header('Content-Type: application/json');
    echo json_encode($row, JSON_PRETTY_PRINT);

} else {
     //沒有找到相符的資料
    echo "找不到病患資料";
}

//不轉成json直接以表格形式顯現
/**if ($result->num_rows > 0) {
     找到相符的資料
    echo "<table border='1'>
            <tr>
                <th>病人姓名</th>
                <th>病歷號</th>
                <th>性別</th>
                <th>出生日期</th>
            </tr>";

    while ($row = $result->fetch_assoc()) {
        echo "<tr>
                <td>" . $row['name'] . "</td>
                <td>" . $row['patient_id'] . "</td>
                <td>" . $row['gender'] . "</td>
                <td>" . $row['birthdate'] . "</td>
              </tr>";
    }

    echo "</table>";
}**/

// 關閉資料庫連線
$conn->close();
?>