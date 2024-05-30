function getPatientName(patient) {
    if (patient.name_given && patient.name_family) {
        return patient.name_given + " " + patient.name_family;
    } else {
        return "N/A";
    }
}

function displaySearchResult(data) {
    console.log("接收到的結果：", data);
    const tableBody = document.querySelector(".result-table-body");
    tableBody.innerHTML = "";
    if (data && data.id) {
        const row = `
            <tr>
                <td>${data.id}</td>
                <td>${getPatientName(data)}</td>
                <td>${data.gender}</td>
                <td>${data.birthDate}</td>
                <td><button onclick="downloadPatientData('${data.id}')">下載</button></td>
            </tr>
        `;
        tableBody.innerHTML = row;
        document.querySelector(".result-section").style.display = "block";
    } else {
        console.log("未找到結果");
        tableBody.innerHTML = "<tr><td colspan='5'>找不到相符的病人資料</td></tr>";
    }
}

function searchPatient() {
    const id = document.querySelector(".query-input").value;
    fetch(`http://localhost/php/fetch_data.php?id=${id}`)
        .then(response => {
            if (!response.ok) {
                throw new Error('Network response was not ok');
            }
            return response.json();
        })
        .then(data => displaySearchResult(data))
        .catch(error => console.error('Error fetching data:', error));
}

function downloadPatientData(patientId) {
    fetch(`http://localhost/php/download.php?id=${patientId}`)
        .then(response => {
            if (!response.ok) {
                throw new Error('Network response was not ok');
            }
            return response.json();
        })
        .then(data => {
            const blob = new Blob([JSON.stringify(data, null, 2)], { type: 'application/json' });
            const url = URL.createObjectURL(blob);
            const a = document.createElement('a');
            a.href = url;
            a.download = `${patientId}.json`;
            document.body.appendChild(a);
            a.click();
            document.body.removeChild(a);
        })
        .catch(error => console.error('Error downloading data:', error));
}