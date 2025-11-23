class DiagnosisModal {
    constructor(assetStorageFaktorRisiko, csrfToken) {
        this.assetStorageFaktorRisiko = assetStorageFaktorRisiko;
        this.csrfToken = csrfToken;
    }

    async ajaxGetFaktorRisiko() {
        return $.ajax({
            url: '/get-faktor-risiko',
            method: 'GET',
            dataType: 'json',
        });
    }

    async ajaxGetRuleData() {
        return $.ajax({
            url: '/get-rule-data',
            method: 'GET',
        });
    }

    async ajaxRequestToDiagnosis(element, jawaban) {
        return $.ajax({
            url: "/diagnosis",
            type: "POST",
            data: {
                _token: csrfToken,
                id_faktor_risiko: element,
                value: jawaban
            },
        });
    }

    swalError = async (error) => {
        const result = await Swal.mixin({
            title: 'Terjadi kesalahan',
            text: error.message,
            icon: 'error',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Muat Ulang',
            cancelButtonText: 'Tutup',
            reverseButtons: true
        }).fire();

        if (result.isConfirmed) {
            window.location.reload();
        }
    };


    async showModal() {
        const swalBeforeDiagnosis = await Swal.fire({
            title: 'Catatan',
            text: 'Sistem ini memiliki keterbatasan dalam cakupan data tingkat risiko hipertensi, sehingga tidak semua tingkat risiko dapat didiagnosis. Hanya tingkat risiko yang terdapat dalam daftar yang dapat didiagnosis. Apakah Anda ingin melanjutkan proses diagnosis?',
            icon: 'info',
            showCancelButton: true,
            confirmButtonText: 'Lanjutkan',
            cancelButtonText: 'Batal',
            reverseButtons: true
        });

        if (!swalBeforeDiagnosis.isConfirmed) return;

        //Swal mohon tunggu
        Swal.fire({
            title: 'Mohon tunggu',
            allowOutsideClick: false,
            didOpen: () => {
                Swal.showLoading()
            },
        });

        try {
            const [faktorRisiko, ruleData] = await Promise.all([this.ajaxGetFaktorRisiko(), this.ajaxGetRuleData()]);

            let isClosed = false;

            for (let i = 0; i < faktorRisiko.length; i++) {
                let element = faktorRisiko[i];

                const { value: jawaban, dismiss: dismissReason } = await Swal.fire({
                    title: 'Pertanyaan ' + (i + 1),
                    imageUrl: `${this.assetStorageFaktorRisiko}/${element.image}`,
                    imageHeight: '300px',
                    imageAlt: `Gambar Faktor Risiko ${element.name}`,
                    text: 'Apakah ' + element.name + '?',
                    confirmButtonColor: '#3085d6',
                    confirmButtonText: 'Ya',
                    showDenyButton: true,
                    denyButtonColor: '#d33',
                    denyButtonText: 'Tidak',
                    showCloseButton: true,
                    allowOutsideClick: false,
                    allowEscapeKey: false,
                    allowEnterKey: false,
                    reverseButtons: true,
                });

                if (dismissReason == Swal.DismissReason.close) {
                    isClosed = true;
                    break;
                }

                try {
                    const response = await this.ajaxRequestToDiagnosis(element.id, jawaban);

                    if (response.idTingkatRisiko != null || response.tingkatRisikoUnidentified === true) {
                        await Swal.close();
                        return getTingkatRisikoFromDiagnose(response, true);
                    }

                    if (!jawaban) {
                        for(let j in ruleData) {
                            for(let k in ruleData[j]) {
                                if(ruleData[j][k] == element.id) {
                                    const iteration = parseInt(j) + 1;

                                    if(ruleData[iteration] == null) {
                                        await Swal.close();
                                        return getTingkatRisikoFromDiagnose(response, true);
                                    }

                                    i = ruleData[iteration][0] - 2;
                                    break;
                                }
                            }
                        }
                    }
                } catch (error) {
                    await this.swalError(error.responseJSON ?? error);
                }
            }
        } catch (error) {
            await this.swalError(error.responseJSON ?? error);
        }
    }
}
