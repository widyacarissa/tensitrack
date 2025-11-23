const detailDiagnosisModal = document.querySelector('#detailDiagnosisModal');
const titleDetailDiagnosisModal = detailDiagnosisModal.querySelector('.modal-title');
const instanceDetailDiagnosisModal = bootstrap.Modal.getOrCreateInstance(detailDiagnosisModal);
const headerDetailDiagnosis = document.getElementById('headerDetailDiagnosis');
const subheaderDetailDiagnosis = document.getElementById('subheaderDetailDiagnosis');
const containerImageTingkatRisikoDetailDiagnosisModal = document.getElementById('containerImageTingkatRisikoDetailDiagnosisModal');
const headerTingkatRisikoSolution = document.getElementById('headerTingkatRisikoSolution');
const rowDetailTingkatRisiko = document.getElementById('rowDetailTingkatRisiko');
const detailJawabanDiagnosisTable = document.getElementById('detailJawabanDiagnosisTable');
const tableBody = detailJawabanDiagnosisTable.querySelector('tbody');
const placeholder = document.querySelectorAll('.placeholder');

let idTingkatRisiko = null;
let idDiagnosis = null;
let noHistoriDiagnosis = null;
let diagnosed = false;
let labelChart = null;
let valueChart = null;
let chartDiagnosisTingkatRisiko = null;

function getTingkatRisikoIdFromHistori(data, no) {
    idDiagnosis = data;
    noHistoriDiagnosis = no;
    diagnosed = false;
    instanceDetailDiagnosisModal.show();
}

function getTingkatRisikoFromDiagnose(data, wasDiagnosed) {
    idTingkatRisiko = data.idTingkatRisiko;
    idDiagnosis = data.idDiagnosis;
    diagnosed = wasDiagnosed;
    instanceDetailDiagnosisModal.show();
}

function ajaxRequestDetailDiagnosis() {
    return $.ajax({
        url: '/detail-diagnosis',
        method: 'GET',
        data: {
            id_tingkat_risiko: idTingkatRisiko,
            id_diagnosis: idDiagnosis,
        },
    });
}
function ajaxRequestChartDiagnosisTingkatRisiko() {
    return $.ajax({
        url: '/chart-diagnosis-tingkat-risiko',
        method: 'GET',
        data: {
            id_diagnosis: idDiagnosis,
        },
    });
}

detailDiagnosisModal.addEventListener('show.bs.modal', async () => {
    try {
        const response = await ajaxRequestDetailDiagnosis();
        drawDetailDiagnosis(response, diagnosed);
        drawDetailJawabanDiagnosis(response.answerLog);
    } catch (error) {
        swalError(error.responseJSON);
    }
});



function drawDetailDiagnosis(response, diagnosed) {
    if (diagnosed === false) {
        titleDetailDiagnosisModal.innerText = 'Detail Diagnosis No. ' + noHistoriDiagnosis;
    }

    if (response.tingkatRisiko == null || response.tingkatRisikoUnidentified === true) {
        headerDetailDiagnosis.innerText = "Tingkat Risiko Tidak Ditemukan!";
        subheaderDetailDiagnosis.innerHTML = 'Tidak ada tingkat risiko yang cocok dengan faktor risiko yang anda masukkan.';
        rowDetailTingkatRisiko.classList.add('d-none');
        headerDetailDiagnosis.classList.remove('d-none');
        subheaderDetailDiagnosis.classList.remove('d-none');
    } else {
        headerDetailDiagnosis.innerText = "Tingkat Risiko Ditemukan!";
        subheaderDetailDiagnosis.innerHTML = "Tingkat risiko yang diderita adalah " + `<u>${response.tingkatRisiko.name}</u>`;
        headerDetailDiagnosis.classList.remove('d-none');
        subheaderDetailDiagnosis.classList.remove('d-none');

        const tingkatRisikoName = document.getElementById('tingkatRisikoName');
        const tingkatRisikoReason = document.getElementById('tingkatRisikoReason');
        tingkatRisikoName.innerHTML = response.tingkatRisiko.name;
        tingkatRisikoReason.innerHTML = response.tingkatRisiko.reason;

        let tingkatRisikoSolution = response.tingkatRisiko.solution;
        let regex = /(\d+\.)\s*(.*?)(?=(\d+\.|$))/gs;
        let matches = [...tingkatRisikoSolution.matchAll(regex)];
        let nomorAsOlTag = '<ol>';
        for (let i = 0; i < matches.length; i++) {
            nomorAsOlTag += '<li>' + matches[i][2] + '</li>';
        }
        nomorAsOlTag += '</ol>';
        headerTingkatRisikoSolution.insertAdjacentHTML('afterend', nomorAsOlTag);

        const imageTingkatRisiko = new Image();
        imageTingkatRisiko.src = assetStorageTingkatRisiko + '/' + response.tingkatRisiko.image;
        imageTingkatRisiko.alt = response.tingkatRisiko.name;
        imageTingkatRisiko.id = 'imageTingkatRisiko';
        imageTingkatRisiko.classList.add('img-fluid');
        containerImageTingkatRisikoDetailDiagnosisModal.appendChild(imageTingkatRisiko);

        new bootstrap.Tooltip(imageTingkatRisiko, {
            title: response.tingkatRisiko.name,
        });

        imageTingkatRisiko.addEventListener('click', () => {
            const lebarLayar = window.innerWidth || document.documentElement.clientWidth || document
                .body.clientWidth;

            if (lebarLayar >= 992) {
                const chocolatInstance = Chocolat([{
                    src: assetStorageTingkatRisiko + '/' + response.tingkatRisiko.image,
                    title: response.tingkatRisiko.name,
                }], {});
                chocolatInstance.api.open();
            }
        });
    }

    //remove class placeholder
    placeholder.forEach((item) => {
        item.classList.remove('placeholder');
    });
}

function drawDetailJawabanDiagnosis(data) {
    const response = data;
    response.forEach((item, index) => {
        const tableRow = document.createElement('tr');
        const tableData = document.createElement('td');
        const tableData2 = document.createElement('td');
        const tableData3 = document.createElement('td');
        tableData.innerHTML = item.id;
        tableData2.innerHTML = item.name;
        tableData3.innerHTML = item.answer;
        tableRow.appendChild(tableData);
        tableRow.appendChild(tableData2);
        tableRow.appendChild(tableData3);
        tableBody.appendChild(tableRow);
    });
}

detailDiagnosisModal.addEventListener('hide.bs.modal', () => {
    containerImageTingkatRisikoDetailDiagnosisModal.innerHTML = '';
    if (headerTingkatRisikoSolution.nextElementSibling) {
        headerTingkatRisikoSolution.nextElementSibling.remove();
    }
    headerDetailDiagnosis.classList.add('d-none');
    subheaderDetailDiagnosis.classList.add('d-none');

    //remove all child element in table body
    while (tableBody.firstChild) {
        tableBody.removeChild(tableBody.firstChild);
    }
    if (chartDiagnosisTingkatRisiko != null) {
        chartDiagnosisTingkatRisiko.destroy();
    }

    rowDetailTingkatRisiko.classList.remove('d-none');
});

detailDiagnosisModal.addEventListener('shown.bs.modal', async () => {
    try {
        const chartData = await ajaxRequestChartDiagnosisTingkatRisiko();
        drawChart(chartData);
    } catch (error) {
        swalError(error.responseJSON);
    }
});

detailDiagnosisModal.addEventListener('hidden.bs.modal', () => {
    if (!document.body.classList.contains('modal-open')) {
        document.body.classList.add('modal-open');
    } else {
        document.body.classList.remove('modal-open');
    }
    //add class placeholder
    placeholder.forEach((item) => {
        item.classList.add('placeholder');
    });
});

async function drawChart(data) {
    let bobot = data;
    labelChart = Object.entries(bobot).map(([nama, nilai]) => nama);
    valueChart = Object.entries(bobot).map(([nama, nilai]) => nilai);

    var ctx = document.getElementById("chartDiagnosisTingkatRisiko").getContext('2d');
    chartDiagnosisTingkatRisiko = new Chart(ctx, {
        type: 'bar',
        data: {
            labels: labelChart,
            datasets: [{
                label: 'Persentase',
                data: valueChart,
                borderWidth: 2,
                backgroundColor: '#001B48',
                borderColor: '#001B48',
                borderWidth: 2.5,
                pointBackgroundColor: '#ffffff',
                pointRadius: 4
            }]
        },
        options: {
            legend: {
                display: false
            },
            scales: {
                yAxes: [{
                    gridLines: {
                        drawBorder: false,
                        color: '#f2f2f2',
                    },
                    ticks: {
                        beginAtZero: true,
                        stepSize: 25,
                        max: 100,
                        callback: function (value) {
                            return value + "%"
                        }
                    },
                }],
                xAxes: [{
                    ticks: {
                        display: true
                    },
                    gridLines: {
                        display: true
                    }
                }]
            },
            responsive: true,
            maintainAspectRatio: false,
        }
    });
}

