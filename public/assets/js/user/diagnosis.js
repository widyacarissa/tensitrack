class DiagnosisModal {
    constructor(assetStorageFaktorRisiko, csrfToken) {
        this.assetStorageFaktorRisiko = assetStorageFaktorRisiko;
        this.csrfToken = csrfToken;
    }

    DiagnosisSwal = Swal.mixin({
        customClass: {
            popup: 'diagnosis-swal-popup',
            title: 'diagnosis-swal-title',
            htmlContainer: 'diagnosis-swal-html-container',
            actions: 'diagnosis-swal-actions',
            confirmButton: 'diagnosis-swal-confirm-button',
            denyButton: 'diagnosis-swal-deny-button',
            cancelButton: 'diagnosis-swal-cancel-button',
            closeButton: 'diagnosis-swal-close-button',
        },
        buttonsStyling: false,
        confirmButtonColor: '#001B48',
        denyButtonColor: '#E49502',
        cancelButtonColor: '#6c757d',
        reverseButtons: true,
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

    async submitDiagnosis(selectedFactors) {
        return $.ajax({
            url: "/diagnosis",
            type: "POST",
            data: {
                _token: this.csrfToken,
                selected_factors: selectedFactors
            },
            dataType: 'json',
        });
    }

    swalError = async (error) => {
        const result = await this.DiagnosisSwal.fire({
            title: 'Terjadi kesalahan',
            html: `<div class="text-center mt-3 mb-4"><p class="modal-text-content lead">${error.message || 'Request failed.'}</p><p class="modal-text-content text-muted">Coba muat ulang halaman atau hubungi administrator.</p></div>`,
            icon: 'error',
            showCancelButton: false, 
            confirmButtonText: 'Muat Ulang',
            reverseButtons: true
        });

        if (result.isConfirmed) {
            window.location.reload();
        }
    };

    async showModal() {
        const swalBeforeDiagnosis = await this.DiagnosisSwal.fire({
            title: 'Catatan Penting',
            html: '<div class="text-center mt-3 mb-4"><p class="modal-text-content lead">Sistem ini hanya memberikan indikasi awal dan tidak menggantikan diagnosis medis profesional.</p><p class="modal-text-content text-muted">Apakah Anda ingin melanjutkan proses diagnosis?</p></div>',
            icon: 'info',
            confirmButtonText: 'Lanjutkan',
            showDenyButton: true,
            denyButtonText: 'Batal',
            reverseButtons: true
        });

        if (!swalBeforeDiagnosis.isConfirmed) return;

        this.DiagnosisSwal.fire({
            title: 'Memuat Data...',
            text: 'Mohon tunggu sebentar',
            allowOutsideClick: false,
            showConfirmButton: false,
            didOpen: () => { Swal.showLoading() },
        });

        try {
            const faktorRisiko = await this.ajaxGetFaktorRisiko();
            const totalFaktorRisiko = faktorRisiko.length;
            let selectedFactors = [];

            for (let i = 0; i < totalFaktorRisiko; i++) {
                let element = faktorRisiko[i];

                const { value: jawaban, dismiss: dismissReason } = await this.DiagnosisSwal.fire({
                    title: `<span class="text-primary">Faktor Risiko ${i + 1}</span> dari ${totalFaktorRisiko}`,
                    html: `<div class="question-highlight-card">
                                <i class="bi bi-question-diamond question-card-icon"></i>
                                <h3 class="question-card-name">${element.name}</h3>
                                <p class="question-card-prompt">Apakah Anda memiliki faktor risiko ini?</p>
                           </div>`,
                    showDenyButton: true,
                    confirmButtonText: 'Ya',
                    denyButtonText: 'Tidak',
                });

                if (dismissReason === Swal.DismissReason.close) {
                    return; // End the process if user closes the modal
                }

                if (jawaban) { // 'Ya' was clicked
                    selectedFactors.push(element.id);
                }
            }

            // After loop, show processing message and send all data at once
            this.DiagnosisSwal.fire({
                title: 'Menganalisis Hasil...',
                text: 'Mohon tunggu sebentar',
                allowOutsideClick: false,
                showConfirmButton: false,
                didOpen: () => { Swal.showLoading() },
            });

            const response = await this.submitDiagnosis(selectedFactors);

            await Swal.close();
            // Call the same result handler function as before
            return getTingkatRisikoFromDiagnose(response, true);

        } catch (error) {
            await this.swalError(error.responseJSON ?? error);
        }
    }
}