class DiagnosisModal {
    constructor(assetStorageFaktorRisiko, csrfToken) {
        this.assetStorageFaktorRisiko = assetStorageFaktorRisiko;
        this.csrfToken = csrfToken;
    }

    // Define a SweetAlert2 mixin for consistent styling
    DiagnosisSwal = Swal.mixin({
        customClass: {
            popup: 'diagnosis-swal-popup',
            title: 'diagnosis-swal-title',
            htmlContainer: 'diagnosis-swal-html-container',
            confirmButton: 'diagnosis-swal-confirm-button',
            denyButton: 'diagnosis-swal-deny-button',
            cancelButton: 'diagnosis-swal-cancel-button',
            closeButton: 'diagnosis-swal-close-button',
        },
        buttonsStyling: false, // Disable default styling to use custom classes
        confirmButtonColor: '#001B48', // Navy Blue
        denyButtonColor: '#E49502', // Orange
        cancelButtonColor: '#6c757d', // Grey for cancel
        reverseButtons: true, // Keep 'Ya' on right, 'Tidak' on left
        allowOutsideClick: false,
        allowEscapeKey: false,
        allowEnterKey: false,
        showCloseButton: true,
    });

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
                _token: this.csrfToken, // Use this.csrfToken
                id_faktor_risiko: element,
                value: jawaban
            },
        });
    }

    swalError = async (error) => {
        const result = await this.DiagnosisSwal.fire({
            title: 'Terjadi kesalahan',
            text: error.message,
            icon: 'error',
            showCancelButton: false, // Hapus tombol batal
            // cancelButtonText: 'Tutup', // Tidak perlu karena tombol batal dihapus
            reverseButtons: true
        });

        if (result.isConfirmed) {
            window.location.reload();
        }
    };


    async showModal() {
        const swalBeforeDiagnosis = await this.DiagnosisSwal.fire({
            title: 'Catatan Penting',
            html: '<p>Sistem ini memiliki keterbatasan dalam cakupan data tingkat risiko hipertensi. Tidak semua tingkat risiko dapat didiagnosis, hanya yang terdapat dalam daftar.</p><p>Apakah Anda ingin melanjutkan proses diagnosis?</p>',
            icon: 'info',
            // showCancelButton: true, // Hapus tombol batal
            confirmButtonText: 'Lanjutkan',
            // cancelButtonText: 'Batal', // Tidak perlu karena tombol batal dihapus
        });

        if (!swalBeforeDiagnosis.isConfirmed) return;

        //Swal mohon tunggu
        this.DiagnosisSwal.fire({
            title: 'Memuat Data...',
            text: 'Mohon tunggu sebentar',
            allowOutsideClick: false,
            showConfirmButton: false,
            showCancelButton: false,
            showCloseButton: false,
            didOpen: () => {
                Swal.showLoading()
            },
        });

        try {
            const [faktorRisiko, ruleData] = await Promise.all([this.ajaxGetFaktorRisiko(), this.ajaxGetRuleData()]);
            const totalFaktorRisiko = faktorRisiko.length;
            let isClosed = false;

            for (let i = 0; i < totalFaktorRisiko; i++) {
                let element = faktorRisiko[i];

                const { value: jawaban, dismiss: dismissReason } = await this.DiagnosisSwal.fire({
                    title: `Faktor Risiko <span class="text-primary">${i + 1}</span> dari ${totalFaktorRisiko}`,
                    html: `<div class="diagnosis-question-content">
                                <p class="diagnosis-question-name mb-0">${element.name}</p>
                                <p class="diagnosis-question-prompt mt-2">Apakah Anda memiliki faktor risiko ini?</p>
                           </div>`,
                    confirmButtonText: 'Ya',
                    denyButtonText: 'Tidak',
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