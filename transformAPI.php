    <?php
    // 啟用詳細錯誤報告
    ini_set('display_errors', 1);
    ini_set('display_startup_errors', 1);
    error_reporting(E_ALL);

    // 確認 autoload.php 文件存在並被正確加載
    $autoloadPath = 'vendor/autoload.php';
    if (file_exists($autoloadPath)) {
        require $autoloadPath;
    } else {
        exit;
    }
    //require 'vendor/autoload.php'; // 引入 Composer 的自動加載文件

    use GuzzleHttp\Client;
    use GuzzleHttp\Exception\RequestException;

    // http的header
    header("Content-Type: application/json; charset=utf-8");
    header("Access-Control-Allow-Origin: *");
    header("Access-Control-Allow-Methods: POST");
    header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

    // 檢查是否為 OPTIONS 請求
    if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
        // 預檢請求，響應 200 OK
        http_response_code(200);
        exit();
    }

    // 檢查請求方法
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        // 獲取原始數據
        $choose = isset($_POST['choose']) ? json_decode($_POST['choose'], true) : [];
        $transformedDataArray = [];
        
        foreach($choose as $selectedData){
            $dataArray = explode(",", $selectedData);
            $transformedData = [
                "resourceType" => "Patient",
                "id" => isset($dataArray[0]) ? $dataArray[0] : "",
                "meta" => [
                    "versionId" => "1",
                    "lastUpdated" => "2024-05-23T16:58:33.474+00:00",
                    "source" => "#Pvo08oiL72MECZKZ"
                ],
                "identifier" => [
                    [
                        "use" => "usual",
                        "type" => [
                            "coding" => [
                                [
                                    "system" => "http://hl7.org/fhir/v2/0203",
                                    "code" => "MR"
                                ]
                            ]
                        ],
                        "system" => "http://hospital.smarthealthit.org",
                        "value" => "12345"
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
                        "value" => isset($dataArray[2]) ? $dataArray[2] : ""
                    ]
                ],
                "name" => [
                    [
                        "use" => "official",
                        "given" => [isset($dataArray[4]) ? $dataArray[4] : ""],
                        "family" => "Doe"
                    ]
                ],
                "gender" => isset($dataArray[6]) ? $dataArray[6] : "",
                "birthDate" => isset($dataArray[7]) ? $dataArray[7] : "",
                "address" => [
                    [
                        "use" => "home",
                        "line" => "123 Main St",
                        "city" => "Anytown",
                        "state" => "Anystate",
                        "postalCode" => "12345",
                        "text" => isset($dataArray[8]) ? $dataArray[8] : "",
                        "country" => "TW"
                    ]
                ]
            ];
        $transformedDataArray[] = $transformedData;
}
    // 處理轉換後的數據
    $uploadResponses = [];
    foreach ($transformedDataArray as $transformedData) {
        $uploadResponses[] = uploadToFhirServer($transformedData);
    }
    // 回傳處理好的數據
    echo json_encode($uploadResponses);
} else {
    echo json_encode(['error' => '無效的請求方法']);
}

    // 將資料上傳到 FHIR 伺服器的函數
    function uploadToFhirServer($transformedData) {
        // 建立 Guzzle HTTP 客戶端
        $client = new GuzzleHttp\Client();

        try {
            // 發送 HTTP POST 請求
            $response = $client->request('POST','http://182.233.92.77:8080/fhir/Patient/', [
                'headers' => [
                    'Content-Type' => 'application/json',
                    'Accept' => 'application/json'
                ],
                'json' => $transformedData
            ]);
            // 獲取響應主體
            $responseBody = $response->getBody()->getContents();
            // 解析 JSON 響應
            $responseData = json_decode($responseBody, true);
            // 檢查伺服器是否成功接收資料
            if (isset($responseData['id'])) {
                // 如果成功，返回伺服器的回應
                return $responseData;
            } else {
                // 如果失敗，返回一個錯誤訊息
                return ['error' => '資料上傳失敗'];
            }
        } catch (RequestException $e) {
            // 處理請求異常
            echo "RequestException: " . $e->getMessage();
            return ['error' => '無法連接到伺服器: ' . $e->getMessage()];
        }
    }
    ?>
