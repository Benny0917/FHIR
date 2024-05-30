<!DOCTYPE html>                           
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="css/common.css" rel="stylesheet" type="text/css" >
    <link href="css/nav.css" rel="stylesheet" type="text/css">
    <style>
        pre {
            border-radius: 8px;
            background-color: #f9f9f9;
            margin-top: 20px;
            margin-right: 200px;
            margin-left: 200px;
            display: flex;
            flex-direction: column; 
            align-items: center;
            justify-content: center;  
        }
    </style>
    <title>FHIR EMR Exchange Platform</title>
</head>
<body class="body">
    <header>
        <div class="container">
        <div class="logo">
            <div class="logo-image">
            <img src="img/logo1-removebg-preview.png" alt="FHIR電子病歷交換平台">
            </div>
            <div class="logo-text">
            FHIR
            <span>EMR EP</span>
            </div>
        </div>
        <!--end logo-->
        <div class="menu-box">
            <!--nav-->
            <div id="navigation">
            <a href="#" id="toggle" class="burger">
                <span></span>
            </a>
            <ul id="hz_menu">
                <a href="#" id="toggle" class="burger">
                <span></span>
                </a>

                <li class="menu-item">
                <a href="xxxx.php">資料查詢 ▽</a>
                <div class="submenu text-dark">
                    <ul>
                    <li ><a href="single_search.html">單項查詢</a></li>
                    <li ><a href="batch_search_1.php">批次查詢</a></li>
                    </ul>
                </div>
                </li>
                <li>
                <a href="xxxx.php">聯絡資訊</a>
                </li>
                <li>
                <a href="home.html">首頁</a>
                </li>

            </ul>
            </div>
            <!--end nav-->
        </div>
        </div>
    </header>
        <main>
        <section class="section-box-2 vh-20" data-pages-bg-image="img/single_search_bg1.jpg" 
            data-pages="parallax" data-bg-overlay data-overlay-opacity="0" 
            style="background-image:url(img/single_search_bg1.jpg);" 
            background-position: center 35.2355%;>
            <div class="container">

            <div class="row justify-content-center">
                <div class="text-center">
                <h2 class="b mb-2 text-black">
                    Json格式產生結果
                </h2>
                </div>
            </div>
            </div>  
        </section>
            <pre id="jsonContainers"></pre>
        </main>

    <?php



    //transform
    $choose = isset($_POST['choose']) ? $_POST['choose'] :[];
    $manyPatient = []; // 新增一個陣列用來存放病人資料
    // echo var_dump($choose);
    // echo $choose;
    foreach ($choose as $index => $selectedData){
        // echo var_dump($choose[$x]);
        $data = explode(",", $selectedData);
        //echo var_dump($data);
        echo "<div class='json-result-container-$index'>";

        $patient = [
            "resourceType" => "Patient",
            "id" => $data[0],
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
                    "value" => $data[2]
                ]
            ],
            "name" => [
                [
                    "use" => "official",
                    "given" => [$data[4]],
                    "family" => "Doe"
                ]
            ],
        /*    "telecom" => [
                [
                    "system" => "phone",
                    "value" => $data[5],
                    "use" => "mobile",
                    "period" => [
                        "start" => "2030-12-15",
                        "end" => "2050-12-15"
                    ]
                ]
            ],
        */    
            "gender" => $data[6],
            "birthDate" => $data[7],
            "address" => [
                [
                    "use" => "home",
                    "line" =>"123 Main St" ,
                    "city" => "Anytown",
                    "state" => "Anystate",
                    "postalCode" => "12345",
                    "text" => $data[8],
                    "country" => "TW"
                ]
            ],
        /*     "maritalStatus" => [
                "coding" => [
                    [
                        "system" => "http://terminology.hl7.org/CodeSystem/v3-MaritalStatus",
                        "code" => $data[9]
                    ]
                ]
            ],
            "contact" => [
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
                    "telecom" => [
                        [
                            "system" => "phone",
                            "value" => $data[11],
                            "use" => "mobile",
                            "period" => [
                                "start" => "2030-12-15",
                                "end" => "2050-12-15"
                            ]
                        ]
                    ]
                ]
            ],
            "communication" => [
                [
                    "language" => [
                        "coding" => [
                            [
                                "system" => "urn:ietf:bcp:47",
                                "code" => $data[12]
                            ]
                        ]
                    ]
                ]
            ],
            "managingOrganization" => [
                "reference" => $data[13]
            ]
        */
        ];


        $manyPatient[] = $patient; 
    
        // 轉換為 JSON 格式
        

        // 輸出 JSON
        echo "<script>document.getElementsByClassName('json-result-container-$index')[document.getElementsByClassName('json-result-container-$index').length - 1].innerHTML += '<pre>' + JSON.stringify(" . json_encode($patient, JSON_PRETTY_PRINT) . ", null, 2) + '</pre>';</script>";
        // 關閉 <div> 標籤
        echo "</div>";
    } 
    
    $jsonData = json_encode($manyPatient, JSON_PRETTY_PRINT);

?> 
        <section class="btn-layout">
            <button class="btn btn-info btn-lg" onclick="uploadPatients()">上傳</button>
        </section>

        <script>   
 // 取得要顯示 JSON 資料的容器
var jsonContainer = document.getElementById('jsonContainers');

// 在顯示新的 JSON 資料之前清空容器
jsonContainer.innerHTML = '';

// 將 JSON 資料轉換成字串，並顯示在容器中
jsonContainer.innerHTML += '<pre>' + JSON.stringify(<?php echo json_encode($jsonData); ?>, null, 2) + '</pre>';


function uploadPatients() {
    // 取得 PHP 產生的 JSON 資料
    var jsonData = <?php echo $jsonData; ?>;

    // 迭代所有選擇的病人
    jsonData.forEach(function ($manyPatient) { 
        // 使用 XMLHttpRequest 發送 POST 請求
        var xhr = new XMLHttpRequest();
        var url = "transformAPI.php";
        var jsonData = <?php echo json_encode($jsonData); ?>; 

        xhr.open("POST", url, true);
        xhr.setRequestHeader("Content-Type", "application/json");

        xhr.onreadystatechange = function () {
            if (xhr.readyState === 4) {
                if (xhr.status === 200) {
                    console.log("上傳成功:", xhr.responseText);
                } else {
                    console.error("上傳失敗:", xhr.statusText);
                }
            }
        };

        xhr.send(JSON.stringify($manyPatient));
    });
}
        </script>

    </body>
</html>
