<?php
  if(isset($_POST['searchOption'])){
    $searchOption = $_POST['searchOption'];
    $gender = $_POST['gender'];
    $Sdatetime = $_POST['Sdatetime'];
    $Edatetime = $_POST['Edatetime'];
    $residencyCode = $_POST['residencyCode'];
    $diagnosisAge = $_POST['diagnosisAge'];
    $classify = $_POST['classify'];
    $primarySite = $_POST['primarySite'];
    $firstVisitDate = $_POST['firstVisitDate'];
    // 連接到資料庫
    $servername = "localhost";
    $username = "root";
    $password = "b26435851";
    $dbname = "FHIR_patient";

    $conn = new mysqli($servername, $username, $password, $dbname);


    // 檢查連接是否成功
    if ($conn->connect_error) {
        die(json_encode(['error' => '連接資料庫失敗：' . $conn->connect_error], JSON_UNESCAPED_UNICODE));
    }
    else{
      
    // 將数组的值合并成逗号分隔的字符串
      $classifyString = implode(',', $classify);
      if($gender == ''){
        $sql = "SELECT * FROM patient
        LEFT JOIN cancer_reg_short_form ON patient.patient_id = cancer_reg_short_form.patient_id
        WHERE  
        (`patient_organization` = '$searchOption' OR
        `resid` = '$residencyCode' OR
        `diagage` = '$diagnosisAge' OR
        `casite` = '$primarySite' OR
        `cont_dt` = '$firstVisitDate'
        ) AND
        (`patient_birthDate` BETWEEN '$Sdatetime' AND '$Edatetime')
        AND `grade_c` IN ($classifyString)
        ";
      } else{
        $sql = "SELECT * FROM patient
        LEFT JOIN cancer_reg_short_form ON patient.patient_id = cancer_reg_short_form.patient_id
        WHERE  
        (`patient_organization` = '$searchOption' OR 
        `resid` = '$residencyCode' OR
        `diagage` = '$diagnosisAge' OR
        `grade_c` = '$classify' OR
        `casite` = '$primarySite' OR
        `cont_dt` = '$firstVisitDate'
        ) AND
        (`patient_birthDate` BETWEEN '$Sdatetime' AND '$Edatetime') 
        AND `patient_gender` = '$gender'
      ";
      }
      
      $result = $conn->query($sql);
      // 檢查查詢結果
      if ($result->num_rows > 0) {
        // 將查詢結果轉換成關聯數組
        $data = array();
        while ($row = $result->fetch_assoc()) {
            $data[] = $row;
        }

        // 將結果以 JSON 格式返回給前端
         $test = json_encode($data, JSON_UNESCAPED_UNICODE);
      } else {
        // 如果沒有符合的資料，返回錯誤信息
         echo json_encode(array('error' => '沒有找到相符的資料'));
    
      }
      $conn->close();
    }
  }
  
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="css/common.css" rel="stylesheet" type="text/css" >
    <link href="css/nav.css" rel="stylesheet" type="text/css">
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
        style="background-image:url(img/single_search_bg1.jpg);background-position: center 35.2355%;">
        <div class="container">
          
          <div class="row justify-content-center">
            <div class="text-center">
              <h2 class="b mb-2 text-black">
                批次查詢
              </h2>
              <p class="font-size-1">(根據需求條件一次查詢多個病人)</p>
            </div>
          </div>
        </div>
      </section>
      <div class="bg-light-2">
        <div style="height: auto; margin-bottom: 0px; margin-right: 0px;">
            <hr>
            <form method="post">
                <div class="form-layout1">
                    <div class="form-layout2">
                      <label  for="searchOption">申報醫院代碼:</label>
                      <input type="number" name="searchOption"placeholder="請輸入醫院機構代碼" style="font-size: 1rem;" required>
                      <hr>
                    </div>
                    <div class="form-layout2">
                      <label for="gender">生理性別:</label>
                      <select name="gender">
                        <option value="">皆要</option>
                        <option value="男">男</option>
                        <option value="女">女</option>
                      </select>
                      <hr>
                    </div>
                    <div class="form-layout2">
                      <label for="datetime">出生年份(起始):</label>
                      <input type="date" name="Sdatetime"placeholder="請輸入西元年" style="font-size: 1rem;" required>
                      <hr>
                    </div>
                    <div class="form-layout2">
                      <label for="datetime">出生年份(結束):</label>
                      <input type="date" name="Edatetime"placeholder="請輸入西元年" style="font-size: 1rem;" required>
                      <hr>
                    </div>
                    <div class="form-layout2">
                      <label for="residencyCode">戶籍地代碼:</label>
                      <input type="number" name="residencyCode" placeholder="請輸入戶籍地代碼" style="font-size: 1rem;">
                      <hr>
                    </div>
                    <div class="form-layout2">
                      <label for="diagnosisAge">診斷年齡:</label>
                      <input type="number" name="diagnosisAge">
                      <hr>
                    </div>
                </div>
                <div class="form-layout1">
                    <div class="form-layout2">
                      <label for="classify">臨床分級化:</label>
                      <select name="classify[]" multiple="multiple">
                        <option value="0">0</option>
                        <option value="1">1</option>
                        <option value="2">2</option>
                        <option value="3">3</option>
                        <option value="5">5</option>
                        <option value="7">7</option>
                        <option value="8">8</option>
                        <option value="9">9</option>
                      </select>
                      <hr>
                    </div>
                    <div class="form-layout2">
                      <label for="primarySite">原發部位:</label>
                      <input type="text" name="primarySite" placeholder="請輸入原發部位編碼" style="font-size: 1rem;"></input>
                      <hr>
                    </div>
                    <div class="form-layout2">
                      <label for="firstVisitDate">首次就診日期:</label>
                      <input type="date" name="firstVisitDate">
                      <hr>
                    </div>
                  <div class="btn-layout">
                    <input type="submit" class="btn btn-info btn-lg vertical-center" style="align-items: center;" value="查詢">
                    <!-- <input class="btn btn-info btn-lg" style="align-items: center;" onclick="searchPatients()">送出</> -->
                  </div>
                </div>
            </form>
            
        </div>
      </div>
      <form method="post" action="transform.php">
          <section class="result-section">
            <?php
              if($test != null){
            ?>
            <table class="table" style="width: 100%;">
                <thead class="bg-light-3">
                    <tr>
                      <th>選擇</th>
                      <th scope="col">申報醫院代碼</th>
                      <th scope="col">病人姓名</th>
                      <th scope="col">病歷號</th>
                      <th scope="col">性別</th>
                      <th scope="col">出生年份</th>
                      <th scope="col">戶籍地代碼</th>
                      <th scope="col">診斷年齡</th>
                      <th scope="col">臨床分級化</th>
                      <th scope="col">原發部位</th>
                      <th scope="col">首次就診日期</th>
                    </tr>
                </thead>
                <tbody class="result-table-body" style="text-align:center;">
                    <?php
                      foreach($data as $row){
                        $res = '';
                        foreach($row as $key => $value){
                          $res.= $value . ",";
                        }
                        $res = substr($res, 0, -1);
                    ?>
                    <tr>
                      <td><input type='checkbox' class='patient-checkbox' name="choose[]" value="<?php echo $res;  ?>"></td>
                      <td><?php echo $row["patient_organization"]; ?></td>
                      <td><?php echo $row["patient_name"]; ?></td>
                      <td><?php echo $row["patient_identifier"]; ?></td>
                      <td><?php echo $row["patient_gender"]; ?></td>
                      <td><?php echo $row["patient_birthDate"]; ?></td>
                      <td><?php echo $row["resid"]; ?></td>
                      <td><?php echo $row["diagage"]; ?></td>
                      <td><?php echo $row["grade_c"]; ?></td>
                      <td><?php echo $row["casite"]; ?></td>
                      <td><?php echo $row["cont_dt"]; ?></td>
                    </tr>
                    <?php }?>
                </tbody>
            </table>
            <?php }?>
            <section class="btn-layout">
                <!-- <button class="btn btn-info btn-lg vertical-center" >轉換</button> -->
                <input type="submit" class="btn btn-info btn-lg vertical-center" style="align-items: center;" value="產生Json格式">
            </section>
        </section>
      </form>
      
    </main>
</body>
</html>


