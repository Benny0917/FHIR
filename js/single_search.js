var currentPatientData = {};

// 送出按鈕
function searchPatient() {
  // 取得使用者輸入的 id
  var patient_identifier = $(".query-input").val();

  // 驗證病歷號是否為空
  if (patient_identifier === "") {
      alert("請輸入要查詢的 ID");
      return;
  }

  // 執行搜尋
  searchPatientInDatabase(patient_identifier);
}

function searchPatientInDatabase(patient_identifier) {
  // 設定後端 API 的 URL
  var apiUrl = "http://localhost/search.php"; 

  // 使用 AJAX 進行後端通信
  $.ajax({
      type: "GET",
      url: apiUrl,
      data: { patient_identifier: patient_identifier },
      success: function (response) {
          // 處理後端回傳的資料
          displaySearchResult(response);
      },
      error: function (error) {
          console.log("搜尋失敗: " + JSON.stringify(error));
          alert("搜尋失敗，請稍後再試或聯絡系統管理員");
      }
  });
}
// 獲取病人姓名
function getPatientName(patient) {
    if (patient.name && patient.name.name) {
        return patient.name.name;
    } else {
        return "N/A";
    }
}
// 獲取病歷號
function getPatientIdentifier(patient) {
    if (patient.identifier && patient.identifier[0] && patient.identifier[0].value) {
        return patient.identifier[0].value.toString();
    } else {
        console.log("Patient object structure:", patient);
        return "N/A";
    }
}


// 代替 JSON.stableStringify 的函數
function stableStringify(obj, options) {
  var allKeys = [];
  JSON.stringify(obj, function (key, value) {
    allKeys.push(key);
    return value;
  });

  allKeys.sort();

  return JSON.stringify(obj, allKeys, options);
}

function displaySearchResult(result) {
    console.log("接收到的结果：", result);

    var resultTableBody = $(".result-table-body");

    // 清空表格內容
    resultTableBody.empty();

    console.log("result type:", typeof result); 

    if (result && Array.isArray(result) && result.length > 0) {
        console.log("顯示结果：", result);

        console.log("Patient object structure:", result[0]);

        // 加入搜尋結果到表格
        result.forEach(function(patient) {
            var patientName = getPatientName(patient);
            var rowHtml = "<tr><td><input type='checkbox' class='result-checkbox'></td><td>"
                        + getPatientName(patient)+ "</td><td>"
                        + getPatientIdentifier(patient) + "</td><td>"
                        + patient.gender + "</td><td>"
                        + patient.birthDate + "</td></tr>";
            resultTableBody.append(rowHtml);
        });

        // 把完整病人資訊輸入到 currentPatientData
        currentPatientData = result;

        // 顯示搜尋結果區域
        $(".result-section").show();
    } else {
        console.log("未找到结果");
        // 未找到相符的資料
        resultTableBody.append("<tr><td colspan='4'>找不到相符的病人資料</td></tr>");
    }
}

// 監聽 checkbox
$(document).on('change', '.result-checkbox', function () {
    var isChecked = $(this).prop('checked');
    // 更新病人資料的 isChecked 屬性
    currentPatientData.isChecked = isChecked;
});

// 轉換按鈕點擊後
function singleSelect() {
    // 在這裡可以處理選擇的病人資料
    if (currentPatientData.isChecked) {
        // 排除 isChecked 屬性
        var selectedData = {
            resourceType: "Patient", 
            name: currentPatientData.name,
            id: getPatientIdentifier(currentPatientData),
            gender: currentPatientData.gender,
            birthDate: currentPatientData.birthDate
        };

        // 移除非 FHIR 資源的屬性
        delete currentPatientData.isChecked;

        // 添加 resourceType 屬性
        currentPatientData.resourceType = "Patient";

        // 處理選擇的資料
        var jsonData = JSON.stringify(currentPatientData, null, 2);
        console.log("選擇的資料：" + jsonData);

        // 顯示 JSON 
        displayJsonData(jsonData);

    } else {
        alert("請先勾選病人資料");
    }
}

// 網頁顯示 JSON
function displayJsonData(jsonData) {
    // 獲取顯示 JSON 的元素
    var jsonOutputDiv = $("#jsonOutput");

    // 清空元素內容
    jsonOutputDiv.empty();

    // 解析 JSON 字符串為對象
    // var jsonObj = JSON.parse(jsonData);

    // 使用 JSON.stringify 格式化 JSON 字符串
    // var jsonString = JSON.stringify(jsonObj, null, 2);
    
    // 使用 json-stable-stringify 格式化 JSON
    // var formattedJsonData = JSON.stableStringify(JSON.parse(jsonData), { space: 2 });

    // 創建 pre 元素，用於格式化顯示 JSON
    var preElement = document.createElement("pre");

    // 設置 pre 元素內容為 JSON 數據
    preElement.textContent = jsonData;

    // 將 pre 元素添加到頁面上
    jsonOutputDiv.append(preElement);
}



