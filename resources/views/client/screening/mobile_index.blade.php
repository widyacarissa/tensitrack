@extends('layouts.mobile')

@section('title', 'Mulai Skrining')

@section('content')

@if(!$isProfileComplete)
    <!-- Mobile Alert: Profil Belum Lengkap -->
    <div class="flex flex-col items-center justify-center h-full p-6 text-center flex-grow">
        <div class="w-20 h-20 bg-orange-100 rounded-full flex items-center justify-center mb-6 animate-bounce">
            <i data-lucide="user-plus" class="w-10 h-10 text-[#E3943B]"></i>
        </div>
        <h3 class="text-xl font-bold text-[#001B48] mb-2">Data Belum Lengkap</h3>
        <p class="text-sm text-gray-500 leading-relaxed mb-8 px-4">
            Untuk hasil yang akurat, kami memerlukan data <b>Nama, Umur, dan Jenis Kelamin</b> Anda.
        </p>
        
        <a href="{{ route('client.profile.index', ['redirect_from' => 'screening']) }}" class="w-full py-4 bg-[#E3943B] text-white rounded-2xl font-bold shadow-lg shadow-orange-500/30 hover:bg-orange-600 transition flex items-center justify-center">
            Lengkapi Profil <i data-lucide="arrow-right" class="w-5 h-5 ml-2"></i>
        </a>
        <a href="{{ route('home') }}" class="mt-4 text-sm font-semibold text-gray-400">Kembali ke Beranda</a>
    </div>
@else

<div class="flex flex-col h-full flex-grow relative overflow-hidden" x-data="screeningQuiz({{ $factors->toJson() }})">
    
    <!-- SPLASH SCREEN (PROCESSING) -->
    <div x-show="processing" style="display: none;" 
         class="absolute inset-0 z-[200] bg-white flex flex-col items-center justify-center text-center px-6"
         x-transition:enter="transition ease-out duration-300"
         x-transition:enter-start="opacity-0"
         x-transition:enter-end="opacity-100">
        
        <div class="relative mb-6">
            <div class="w-20 h-20 rounded-full border-4 border-gray-100 border-t-[#E3943B] animate-spin"></div>
            <div class="absolute inset-0 flex items-center justify-center">
                <i data-lucide="activity" class="w-8 h-8 text-[#001B48]"></i>
            </div>
        </div>
        
        <h2 class="text-xl font-bold text-[#001B48]" x-text="processingText">Menganalisis...</h2>
        <p class="text-gray-400 text-xs mt-2">Mohon tunggu sebentar.</p>
    </div>

    <!-- CONSENT SCREEN -->
    <div x-show="!consentGiven" 
         class="flex flex-col h-full p-6 bg-white justify-between"
         x-transition:leave="transition ease-in duration-300"
         x-transition:leave-start="opacity-100 transform translate-x-0"
         x-transition:leave-end="opacity-0 transform -translate-x-full">
        
        <div class="mt-4 flex flex-col items-center text-center">
            <div class="w-14 h-14 bg-blue-50 rounded-2xl flex items-center justify-center mb-6">
                <i data-lucide="shield-check" class="w-8 h-8 text-[#001B48]"></i>
            </div>
            <h1 class="text-2xl font-extrabold text-[#001B48] mb-4">Halo, {{ Auth::user()->name }}!</h1>
            <p class="text-gray-600 text-sm leading-relaxed mb-4">
                Kita akan melakukan pengecekan kesehatan singkat. Jawablah beberapa pertanyaan berikut dengan <b>jujur</b> untuk mengetahui risiko hipertensi Anda.
            </p>
            <div class="bg-orange-50 p-4 rounded-xl border border-orange-100 w-full text-left">
                <p class="text-xs text-orange-800 font-medium flex items-start">
                    <i data-lucide="info" class="w-4 h-4 mr-2 mt-0.5 flex-shrink-0"></i>
                    Hasil ini adalah prediksi awal, bukan diagnosa medis pengganti dokter.
                </p>
            </div>
        </div>

        <div class="mt-8">
            <button @click="consentGiven = true" class="w-full py-4 bg-[#001B48] text-white rounded-2xl font-bold shadow-xl hover:bg-blue-900 transition flex items-center justify-center">
                Mulai Sekarang <i data-lucide="arrow-right" class="w-5 h-5 ml-2"></i>
            </button>
        </div>
    </div>

    <!-- QUIZ SCREEN -->
    <div x-show="consentGiven && !processing" style="display: none;" class="flex flex-col h-full relative bg-gray-50">
        
        <!-- Progress Bar -->
        <div class="px-6 pt-6 pb-2 bg-white">
            <div class="flex justify-between text-[10px] font-bold text-gray-400 uppercase mb-2">
                <span x-text="'Pertanyaan ' + (currentIndex + 1)"></span>
                <span x-text="questions.length">Total</span>
            </div>
            <div class="w-full bg-gray-100 rounded-full h-2 overflow-hidden">
                <div class="bg-[#E3943B] h-full rounded-full transition-all duration-500 ease-out"
                     :style="'width: ' + (((currentIndex + 1) / questions.length) * 100) + '%'"></div>
            </div>
        </div>

        <!-- Question Card Area -->
        <div class="flex-grow flex flex-col justify-center px-6 pb-32 relative overflow-y-auto">
            
            <!-- Card -->
            <div class="bg-white rounded-3xl shadow-sm border border-gray-100 p-8 w-full h-full max-h-[400px] flex flex-col items-center justify-center text-center relative overflow-hidden mt-4">
                
                <!-- Decorative Circle -->
                <div class="absolute -top-10 -left-10 w-32 h-32 bg-blue-50 rounded-full opacity-50 blur-2xl"></div>
                <div class="absolute -bottom-10 -right-10 w-32 h-32 bg-orange-50 rounded-full opacity-50 blur-2xl"></div>

                <span class="inline-flex items-center px-3 py-1 rounded-full bg-blue-50 text-[#001B48] text-[10px] font-bold uppercase tracking-wider mb-6 relative z-10">
                    <i data-lucide="activity" class="w-3 h-3 mr-1 text-[#E3943B]"></i> Faktor Risiko
                </span>
                
                <h2 class="text-xl font-bold text-[#001B48] mb-8 leading-snug relative z-10" 
                    x-text="questions[currentIndex].question_text ? questions[currentIndex].question_text : questions[currentIndex].name">
                </h2>

                <!-- Floating Code -->
                <div class="absolute bottom-2 text-[4rem] font-black text-gray-50 select-none z-0 pointer-events-none" x-text="questions[currentIndex].code"></div>

            </div>

        </div>

        <!-- Action Buttons (Fixed Bottom) -->
        <div class="fixed bottom-0 left-0 w-full bg-white p-6 pb-8 rounded-t-3xl shadow-[0_-4px_20px_rgba(0,0,0,0.05)] z-20">
            <div class="flex gap-4">
                <button @click="answer(false)" class="group flex-1 py-4 rounded-2xl bg-white border-2 border-gray-100 text-gray-500 font-bold hover:border-red-200 hover:bg-red-50 hover:text-red-600 transition-all duration-300 active:scale-95 active:bg-red-100 flex flex-col items-center justify-center gap-1">
                    <span class="w-8 h-8 rounded-full bg-gray-100 group-hover:bg-red-200 flex items-center justify-center transition-colors mb-1">
                        <i data-lucide="x" class="w-5 h-5 group-hover:text-red-600 text-gray-400 transition-colors"></i>
                    </span>
                    <span class="text-sm">Tidak</span>
                </button>
                
                <button @click="answer(true)" class="group flex-1 py-4 rounded-2xl bg-[#001B48] text-white font-bold shadow-lg shadow-blue-900/20 hover:bg-[#E3943B] hover:shadow-orange-500/30 transition-all duration-300 active:scale-95 active:bg-orange-700 flex flex-col items-center justify-center gap-1">
                    <span class="w-8 h-8 rounded-full bg-white/20 flex items-center justify-center mb-1">
                        <i data-lucide="check" class="w-5 h-5"></i>
                    </span>
                    <span class="text-sm">Ya, Benar</span>
                </button>
            </div>
            
            <div class="text-center mt-4 h-6">
                <button x-show="currentIndex > 0" @click="currentIndex--" class="text-xs text-gray-400 font-medium hover:text-gray-600 flex items-center justify-center mx-auto transition-colors">
                    <i data-lucide="chevron-left" class="w-3 h-3 mr-1"></i> Koreksi Jawaban
                </button>
            </div>
        </div>

    </div>

    <!-- Hidden Form -->
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
            selectedAnswers: [],
            consentGiven: false,
            processing: false,
            processingText: 'Menganalisis...',

            answer(isYes) {
                const factorId = this.questions[this.currentIndex].id;
                
                if (isYes) {
                    if (!this.selectedAnswers.includes(factorId)) {
                        this.selectedAnswers.push(factorId);
                    }
                } else {
                    this.selectedAnswers = this.selectedAnswers.filter(id => id !== factorId);
                }

                if (this.currentIndex < this.questions.length - 1) {
                    this.currentIndex++;
                } else {
                    this.submit();
                }
            },

            submit() {
                this.processing = true;
                setTimeout(() => { this.processingText = 'Cek Riwayat...'; }, 800);
                setTimeout(() => { this.processingText = 'Hitung Risiko...'; }, 1500);
                
                setTimeout(() => {
                    document.getElementById('screeningForm').submit();
                }, 2000);
            }
        }))
    });
</script>
@endif

@endsection