@extends('layouts.app')

@section('title', 'Skrining Hipertensi')

@section('content')

@if(!$isProfileComplete)
    <!-- Modal Blocking untuk Profil Belum Lengkap -->
    <div class="fixed inset-0 z-[999] overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
        <!-- Backdrop Blur -->
        <div class="fixed inset-0 bg-[#001B48]/80 backdrop-blur-sm transition-opacity"></div>

        <div class="flex min-h-full items-center justify-center p-4 text-center sm:p-0">
            <div class="relative transform overflow-hidden rounded-2xl bg-white text-left shadow-2xl transition-all sm:my-8 sm:w-full sm:max-w-lg border border-white/20">
                
                <!-- Modal Content -->
                <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                    <div class="sm:flex sm:items-start">
                        <div class="mx-auto flex h-14 w-14 flex-shrink-0 items-center justify-center rounded-full bg-orange-100 sm:mx-0 sm:h-12 sm:w-12">
                            <i data-lucide="user-plus" class="h-6 w-6 text-[#E3943B]"></i>
                        </div>
                        <div class="mt-3 text-center sm:ml-4 sm:mt-0 sm:text-left">
                            <h3 class="text-xl font-bold leading-6 text-[#001B48]" id="modal-title">Lengkapi Data Diri</h3>
                            <div class="mt-2">
                                <p class="text-sm text-gray-500 leading-relaxed">
                                    Mohon maaf, fitur skrining membutuhkan data identitas dasar Anda. Silakan lengkapi <b>Nama, Umur, dan Jenis Kelamin</b> pada menu profil untuk melanjutkan.
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- Modal Actions -->
                <div class="bg-gray-50 px-4 py-4 sm:flex sm:flex-row-reverse sm:px-6 gap-2">
                    <a href="{{ route('client.profile.index', ['redirect_from' => 'screening']) }}" class="inline-flex w-full justify-center rounded-xl bg-[#E3943B] px-5 py-2.5 text-sm font-bold text-white shadow-lg hover:bg-orange-600 hover:shadow-orange-500/30 sm:w-auto transition-all transform hover:-translate-y-0.5">
                        Lengkapi Sekarang
                    </a>
                    <a href="{{ route('home') }}" class="mt-3 inline-flex w-full justify-center rounded-xl bg-white px-5 py-2.5 text-sm font-bold text-gray-700 shadow-sm ring-1 ring-inset ring-gray-300 hover:bg-gray-50 sm:mt-0 sm:w-auto transition">
                        Kembali
                    </a>
                </div>
            </div>
        </div>
    </div>
@else

<div class="h-[calc(100vh-5rem)] bg-gray-50 flex flex-col justify-between py-4 px-4 sm:px-6 lg:px-8 relative overflow-hidden"
     x-data="screeningQuiz({{ $factors->toJson() }})">
    
    <!-- MODAL PERSETUJUAN -->
    <div x-show="!consentGiven" style="display: none;" 
         class="fixed inset-0 z-[100] overflow-y-auto" 
         aria-labelledby="modal-title" role="dialog" aria-modal="true">
        
        <!-- Backdrop -->
        <div x-show="!consentGiven" x-transition.opacity 
             class="fixed inset-0 bg-gray-900/90 backdrop-blur-sm transition-opacity"></div>

        <div class="flex min-h-full items-center justify-center p-4 text-center sm:p-0">
            <div x-show="!consentGiven" 
                 x-transition:enter="ease-out duration-300"
                 x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                 x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100"
                 class="relative transform overflow-hidden rounded-2xl bg-white text-left shadow-xl transition-all sm:my-8 sm:w-full sm:max-w-lg">
                
                <!-- Modal Header -->
                <div class="bg-[#001B48] px-4 py-6 sm:px-6 text-center">
                    <div class="mx-auto flex h-12 w-12 items-center justify-center rounded-full bg-white/10 mb-4">
                        <i data-lucide="shield-check" class="h-6 w-6 text-[#E3943B]"></i>
                    </div>
                    <h3 class="text-xl font-bold text-white" id="modal-title">Persetujuan Skrining</h3>
                </div>

                <!-- Modal Body -->
                <div class="px-6 py-6 sm:p-8 text-center">
                    <p class="text-gray-600 mb-6 text-sm leading-relaxed">
                        Skrining ini bertujuan untuk mendeteksi dini risiko hipertensi berdasarkan faktor risiko yang Anda miliki. 
                        Hasil ini <b>bukan diagnosa medis final</b>, melainkan prediksi awal untuk membantu Anda mengambil langkah pencegahan.
                    </p>
                    
                    <p class="text-gray-500 text-xs mb-8">
                        Dengan melanjutkan, Anda setuju untuk memberikan jawaban yang jujur demi keakuratan hasil.
                    </p>
                    
                    <div class="flex flex-col gap-3">
                        <button type="button" class="w-full justify-center rounded-lg bg-[#E3943B] px-4 py-3 text-sm font-bold text-white shadow-sm hover:bg-orange-600 transition transform hover:scale-105" @click="consentGiven = true">
                            Setuju & Lanjutkan
                        </button>
                        <a href="/" class="w-full justify-center rounded-lg bg-white px-4 py-3 text-sm font-semibold text-gray-500 shadow-sm ring-1 ring-inset ring-gray-200 hover:bg-gray-50 transition">
                            Batal
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- SPLASH SCREEN (PROCESSING) -->
    <div x-show="processing" style="display: none;" 
         class="fixed inset-0 z-[200] bg-white flex flex-col items-center justify-center text-center"
         x-transition:enter="transition ease-out duration-300"
         x-transition:enter-start="opacity-0"
         x-transition:enter-end="opacity-100">
        
        <div class="relative">
            <div class="w-24 h-24 rounded-full border-4 border-gray-100 border-t-[#E3943B] animate-spin"></div>
            <div class="absolute inset-0 flex items-center justify-center">
                <i data-lucide="activity" class="w-10 h-10 text-[#001B48]"></i>
            </div>
        </div>
        
        <h2 class="mt-8 text-2xl font-bold text-[#001B48]" x-text="processingText">Menganalisis Jawaban...</h2>
        <p class="text-gray-500 mt-2 text-sm">Mohon tunggu sebentar, sistem sedang memproses data Anda.</p>
    </div>

    <!-- KONTEN KUIS (Hanya tampil jika consentGiven = true) -->
    <div class="max-w-3xl mx-auto w-full flex flex-col justify-center flex-grow" x-show="consentGiven && !processing" x-transition:enter="transition ease-out duration-500" x-transition:enter-start="opacity-0 transform scale-95" x-transition:enter-end="opacity-100 transform scale-100">
        
        <!-- Progress Bar & Close Button -->
        <div class="mb-4 flex items-end gap-4">
            <div class="flex-grow">
                <div class="flex justify-between text-xs font-medium text-gray-500 mb-1">
                    <span x-text="'Pertanyaan ' + (currentIndex + 1) + ' dari ' + questions.length"></span>
                    <span x-text="Math.round(((currentIndex + 1) / questions.length) * 100) + '%'"></span>
                </div>
                <div class="w-full bg-gray-200 rounded-full h-1.5">
                    <div class="bg-[#E3943B] h-1.5 rounded-full transition-all duration-500 ease-out"
                         :style="'width: ' + (((currentIndex + 1) / questions.length) * 100) + '%'"></div>
                </div>
            </div>
            <button @click="confirmCancel()" class="text-gray-400 hover:text-red-500 transition-colors p-1 rounded-full hover:bg-red-50" title="Batalkan Skrining">
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="w-6 h-6"><path d="M18 6 6 18"/><path d="m6 6 12 12"/></svg>
            </button>
        </div>

        <!-- Question Card -->
        <div class="bg-white rounded-3xl shadow-2xl p-8 md:p-16 text-center relative overflow-hidden flex flex-col justify-center items-center flex-grow mx-auto w-full max-w-4xl border border-gray-50">
            
            <!-- Dekorasi Background -->
            <div class="absolute top-0 left-0 w-full h-3 bg-gradient-to-r from-[#001B48] via-[#E3943B] to-[#001B48]"></div>
            
            <!-- Watermark Kode (Background) -->
            <span class="absolute -bottom-6 -right-6 text-[10rem] font-black text-gray-50 opacity-50 select-none z-0 transform -rotate-12" x-text="questions[currentIndex].code"></span>
            
            <!-- Konten Pertanyaan -->
            <div class="relative z-10 w-full" x-show="true" 
                 x-transition:enter="transition ease-out duration-500" 
                 x-transition:enter-start="opacity-0 translate-y-10" 
                 x-transition:enter-end="opacity-100 translate-y-0">
                
                <span class="inline-block px-2 py-0.5 rounded-full bg-blue-50 text-[#001B48] text-xs font-bold mb-8 tracking-wide uppercase shadow-sm">
                    <i data-lucide="activity" class="w-3 h-3 mr-2 text-[#E3943B]"></i> Pertanyaan Skrining
                </span>
                
                <h2 class="text-2xl md:text-3xl font-extrabold text-[#001B48] mb-12 leading-snug tracking-tight" x-text="questions[currentIndex].question_text ? questions[currentIndex].question_text : questions[currentIndex].name"></h2>
                
                <!-- Tombol Aksi -->
                <div class="flex gap-5 justify-center w-full max-w-lg mx-auto">
                    <button @click="answer(false)" class="group flex-1 py-4 px-6 rounded-2xl border-2 border-gray-200 bg-white text-gray-500 font-bold hover:border-red-200 hover:bg-red-50 hover:text-red-600 transition-all duration-300 focus:ring-4 focus:ring-red-100 focus:outline-none flex items-center justify-center text-base">
                        <span class="w-8 h-8 rounded-full bg-gray-100 group-hover:bg-red-200 flex items-center justify-center mr-3 transition-colors">
                            <i data-lucide="x" class="w-5 h-5"></i>
                        </span>
                        Tidak
                    </button>
                    
                    <button @click="answer(true)" class="group flex-1 py-4 px-6 rounded-2xl bg-[#001B48] text-white font-bold hover:bg-[#E3943B] transition-all duration-300 shadow-xl hover:shadow-2xl hover:-translate-y-1 focus:ring-4 focus:ring-orange-200 focus:outline-none flex items-center justify-center text-base">
                        <span class="w-8 h-8 rounded-full bg-white/20 flex items-center justify-center mr-3">
                            <i data-lucide="check" class="w-5 h-5"></i>
                        </span>
                        Ya, Saya Alami
                    </button>
                </div>
            </div>

        </div>

        <!-- Navigasi Back (Opsional) -->
        <div class="mt-4 text-center" x-show="currentIndex > 0">
            <button @click="currentIndex--" class="text-xs text-gray-400 hover:text-gray-600 underline">
                Kembali ke pertanyaan sebelumnya
            </button>
        </div>

    </div>

    <!-- Hidden Form untuk Submit -->
    <form id="screeningForm" action="{{ route('client.screening.store') }}" method="post">
        @csrf
        <template x-for="id in selectedAnswers">
            <input type="hidden" name="answers[]" :value="id">
        </template>
    </form>

</div>

<script>
    document.addEventListener('alpine:init', () => {
        Alpine.data('screeningQuiz', (factors) => ({
            questions: factors,
            currentIndex: 0,
            selectedAnswers: [], // Menyimpan ID faktor yang dijawab YA
            consentGiven: false, // State persetujuan
            processing: false,   // State splash screen
            processingText: 'Menganalisis Jawaban...',

            confirmCancel() {
                Swal.fire({
                    title: 'Batalkan Skrining?',
                    text: "Progres Anda saat ini tidak akan tersimpan.",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#d33',
                    cancelButtonColor: '#3085d6',
                    confirmButtonText: 'Ya, Batalkan',
                    cancelButtonText: 'Lanjut Skrining',
                    reverseButtons: true
                }).then((result) => {
                    if (result.isConfirmed) {
                        window.location.href = "{{ route('home') }}";
                    }
                });
            },

            answer(isYes) {
                if (isYes) {
                    const factorId = this.questions[this.currentIndex].id;
                    if (!this.selectedAnswers.includes(factorId)) {
                        this.selectedAnswers.push(factorId);
                    }
                } else {
                    // Jika user kembali dan ubah jawaban jadi Tidak, hapus dari array
                    const factorId = this.questions[this.currentIndex].id;
                    this.selectedAnswers = this.selectedAnswers.filter(id => id !== factorId);
                }

                // Next Question or Finish
                if (this.currentIndex < this.questions.length - 1) {
                    this.currentIndex++;
                } else {
                    this.submit();
                }
            },

            submit() {
                this.processing = true;
                
                // Gimmick Loading Text
                setTimeout(() => { this.processingText = 'Mengecek Riwayat Kesehatan...'; }, 800);
                setTimeout(() => { this.processingText = 'Menghitung Tingkat Risiko...'; }, 1600);
                setTimeout(() => { this.processingText = 'Finalisasi Diagnosa...'; }, 2200);

                // Submit Form Real
                setTimeout(() => {
                    document.getElementById('screeningForm').submit();
                }, 2800);
            }
        }))
    });
</script>

@endif

@endsection