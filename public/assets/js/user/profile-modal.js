const modalEditProfile = document.getElementById('editProfileModal');
const modalEditProfileInstance = bootstrap.Modal.getOrCreateInstance(modalEditProfile);

modalEditProfile.addEventListener('show.bs.modal', async () => {
    drawHistoriDiagnosisTable();

    const btnSubmitEditProfile = document.getElementById('btnSubmitEditProfile');
    btnSubmitEditProfile.addEventListener('click', async (e) => {
        e.preventDefault();
        Swal.fire({
            title: 'Mohon tunggu',
            html: 'Sedang memproses data',
            allowOutsideClick: false,
            didOpen: () => {
                Swal.showLoading()
            },
        });
        try {
            const response = await ajaxPostEditProfile();
            await new Promise(resolve => setTimeout(resolve, 1000)); // Delay selama 1 detik
            await Swal.fire({
                icon: 'success',
                title: 'Berhasil',
                text: response.message,
                showConfirmButton: false,
                timer: 1500
            });
            return window.location.reload();
        } catch (error) {
            swalError(error.responseJSON);
        }
    });

    const setElementAttributes = (element, value, disabled = false) => {
        element.value = value;
        element.disabled = disabled;
    };

    const elements = {
        nameInput: document.querySelector('input[name="name"]'),
        emailInput: document.querySelector('input[name="email"]'),
    };

    try {
        const response = await ajaxRequestEditProfile();
        
        // Ensure user profile exists
        if (response.user.profile == null) {
            response.user.profile = {};
        }

        // Set basic form values
        setElementAttributes(elements.nameInput, response.user.name);
        setElementAttributes(elements.emailInput, response.user.email);

    } catch (error) {
        console.error('Failed to load profile data:', error);
        swalError(error.responseJSON || { message: 'Gagal memuat data profil' });
    }
});


