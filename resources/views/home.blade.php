@extends('layouts.app')

@section('title', 'Selamat Datang')

@section('content')

    <!-- Section: Hero -->
    <section id="beranda" class="bg-white h-auto md:h-[calc(100vh-5rem)] overflow-hidden">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 h-full">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-8 md:gap-0 h-full py-8 md:py-0">
                
                <!-- Image (Mobile: Atas, Desktop: Kanan) -->
                <div class="order-1 md:order-last h-64 md:h-full w-full relative animate-slide-right rounded-2xl md:rounded-none overflow-hidden md:overflow-visible">
                    <img class="absolute inset-0 w-full h-full object-cover md:rounded-bl-[4rem]" 
                         src="/img/home.png" 
                         alt="Gambar Home"
                         onerror="this.src='https://images.unsplash.com/photo-1576091160399-112ba8d25d1d?ixlib=rb-1.2.1&auto=format&fit=crop&w=1000&q=80'">
                </div>

                <!-- Text -->
                <div class="order-2 md:order-first flex flex-col justify-center space-y-6 pr-0 md:pr-12 md:py-12 animate-slide-left text-center md:text-left">
                    <div class="flex justify-center md:justify-start">
                        <span class="inline-flex items-center px-3 py-1 rounded-full text-xs md:text-sm font-semibold bg-[#001B48]/10 text-[#001B48]">
                            <i data-lucide="star" class="w-4 h-4 mr-2"></i> Sistem Cerdas Deteksi Dini
                        </span>
                    </div>
                
                    <h1 class="text-3xl md:text-5xl lg:text-6xl font-bold text-[#001B48] leading-tight">
                        Ketahui Risiko Hipertensi <br />
                        <span class="text-[#E3943B]">Sebelum Terlambat</span>
                    </h1>
                
                    <p class="text-base md:text-lg text-gray-600 leading-relaxed px-4 md:px-0">
                        Hipertensi sering datang tanpa gejala. <b class="font-bold text-[#001B48]">TensiTrack</b> membantu Anda memprediksi potensi risiko di masa depan berdasarkan gaya hidup Anda saat ini.
                    </p>
                
                    <div class="flex flex-wrap gap-4 justify-center md:justify-start">
                        <button @click="document.getElementById('diagnosis').scrollIntoView({ behavior: 'smooth', block: 'center' })" class="inline-flex items-center justify-center px-6 md:px-8 py-3 text-sm md:text-base font-medium text-white bg-[#001B48] rounded-lg hover:bg-blue-900 transition shadow-lg hover:shadow-xl">
                            Mulai Skrining Gratis
                        </button>
                        
                        <a href="#kalkulator-bmi" class="inline-flex items-center justify-center px-6 md:px-8 py-3 text-sm md:text-base font-medium text-[#001B48] bg-white border-2 border-[#001B48] rounded-lg hover:bg-[#E3943B] hover:border-[#E3943B] hover:text-white transition duration-300 shadow-sm hover:shadow-md">
                            Kalkulator Berat Badan
                        </a>
                    </div>
                </div>

            </div>
        </div>
    </section>

    <!-- Section: Alur Interaksi -->
    <section id="alur-interaksi" class="py-12 md:py-16 bg-gray-50 scroll-mt-24" x-data="{ shown: false }" x-intersect.half="shown = true">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center transition-all duration-1000 transform"
             :class="shown ? 'opacity-100 translate-y-0' : 'opacity-0 translate-y-10'">

            <h2 class="text-2xl md:text-4xl font-bold text-[#001B48] mb-4">Alur Interaksi Pengguna</h2>
            <p class="text-gray-600 mb-8 md:mb-12 max-w-2xl mx-auto text-sm md:text-base">Ikuti langkah sederhana berikut untuk mulai menggunakan TensiTrack dan dapatkan hasil diagnosa yang akurat.</p>

            <!-- Placeholder Gambar Alur -->
            <div class="flex justify-center">
                 <img class="max-w-full h-auto rounded-xl shadow-sm" src="/img/alur-interaksi.png" alt="Alur Interaksi Pengguna"
                      onerror="this.src='https://via.placeholder.com/800x400?text=Diagram+Alur+Interaksi'">
            </div>

        </div>
    </section>

    <!-- Section: Kalkulator BMI (AlpineJS Logic) -->
    <section id="kalkulator-bmi" class="py-12 md:py-20 bg-[#F4F7FB] scroll-mt-24">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8" x-data="bmiCalculator()">

            <!-- Section Title -->
            <div class="text-center mb-10 md:mb-16">
                <h2 class="text-2xl md:text-4xl font-bold text-[#001B48] mb-4">Kalkulator Berat Badan Ideal</h2>
                <p class="text-gray-600 max-w-2xl mx-auto text-sm md:text-base">
                    Obesitas adalah salah satu faktor risiko utama Hipertensi. Cek apakah berat badan Anda sudah ideal?
                </p>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-2 gap-8 md:gap-12 items-start">
                <!-- Kolom Kiri: Info -->
                <div class="space-y-6 text-center md:text-left">
                    <div>
                        <h4 class="font-bold text-[#E3943B] text-lg md:text-xl mb-2">Apa itu BMI?</h4>
                        <p class="text-gray-600 leading-relaxed text-sm md:text-base">
                            Body Mass Index (BMI) adalah cara menghitung berat badan ideal berdasarkan tinggi dan berat badan. 
                            Angka ini menjadi indikator awal apakah proporsi tubuh Anda sehat.
                        </p>
                    </div>
                    <div>
                        <h4 class="font-bold text-[#E3943B] text-lg md:text-xl mb-2">Apa fungsi kalkulator ini?</h4>
                        <p class="text-gray-600 leading-relaxed text-sm md:text-base">
                            Alat ini membantu mengidentifikasi kategori berat badan Anda (Kurus, Normal, Gemuk, atau Obesitas). 
                            Penting untuk diketahui karena berat badan berlebih meningkatkan beban kerja jantung.
                        </p>
                    </div>
                    <div class="mt-8 text-center hidden md:block">
                        <img src="https://img.freepik.com/premium-vector/weight-loss-bmi-man-woman-before-after-diet-fitness-fat-thin-man-woman_162329-342.jpg"
                             alt="Ilustrasi BMI" class="max-w-full h-auto rounded shadow-sm object-contain max-h-[300px] mx-auto">
                    </div>
                </div>

                <!-- Kolom Kanan: Form Kalkulator -->
                <div class="bg-white p-6 md:p-8 rounded-2xl shadow-sm">
                    <h3 class="text-xl md:text-2xl font-bold text-[#001B48] mb-6 flex items-center justify-center md:justify-start">
                        <i data-lucide="calculator" class="w-6 h-6 mr-2 text-[#E3943B]"></i> Hitung BMI Anda
                    </h3>

                    <div class="space-y-6 md:space-y-8">
                        <!-- Gender -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Jenis Kelamin</label>
                            <div class="grid grid-cols-2 gap-4">
                                <button @click="gender = 'male'" :class="gender === 'male' ? 'bg-[#001B48] text-white border-[#001B48]' : 'bg-white text-gray-600 border-gray-300 hover:bg-gray-50'"
                                        class="py-2 px-4 border rounded-lg font-medium transition flex items-center justify-center text-sm">
                                    <i data-lucide="user" class="w-4 h-4 mr-2"></i> Pria
                                </button>
                                <button @click="gender = 'female'" :class="gender === 'female' ? 'bg-[#001B48] text-white border-[#001B48]' : 'bg-white text-gray-600 border-gray-300 hover:bg-gray-50'"
                                        class="py-2 px-4 border rounded-lg font-medium transition flex items-center justify-center text-sm">
                                    <i data-lucide="user-check" class="w-4 h-4 mr-2"></i> Wanita
                                </button>
                            </div>
                        </div>

                        <!-- Input Tinggi & Berat -->
                        <div class="grid grid-cols-2 gap-4 md:gap-6">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Tinggi Badan (cm)</label>
                                <input type="number" x-model="height" class="w-full rounded-lg border border-gray-300 focus:ring-[#E3943B] focus:border-[#E3943B] p-3 shadow-sm text-sm" placeholder="0">
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Berat Badan (kg)</label>
                                <input type="number" x-model="weight" class="w-full rounded-lg border border-gray-300 focus:ring-[#E3943B] focus:border-[#E3943B] p-3 shadow-sm text-sm" placeholder="0">
                            </div>
                        </div>

                        <!-- Button Hitung -->
                        <button @click="calculateBMI()" class="w-full bg-[#E3943B] text-white font-bold py-3 rounded-lg hover:bg-orange-600 transition shadow-md text-sm md:text-base">
                            Hitung BMI Sekarang
                        </button>

                        <!-- Hasil -->
                        <div x-show="result" x-transition class="mt-6 p-6 bg-gray-50 rounded-xl border border-gray-200 text-center">
                            <p class="text-gray-500 text-xs uppercase tracking-wide mb-1">Nilai BMI Anda</p>
                            <div class="text-3xl md:text-4xl font-extrabold text-[#001B48] mb-2" x-text="bmiValue"></div>
                            
                            <div class="inline-flex items-center px-4 py-1 rounded-full text-xs md:text-sm font-bold mb-3"
                                 :class="{
                                    'bg-blue-100 text-blue-800': category === 'Kurus',
                                    'bg-green-100 text-green-800': category === 'Normal',
                                    'bg-yellow-100 text-yellow-800': category === 'Gemuk',
                                    'bg-red-100 text-red-800': category === 'Obesitas'
                                 }">
                                <span x-text="category"></span>
                            </div>
                            
                            <p class="text-gray-600 text-sm leading-relaxed" x-text="advice"></p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Section: CTA Banner -->
    <section id="diagnosis" class="py-12 md:py-20 bg-white scroll-mt-24" x-data="{ shown: false }" x-intersect.half="shown = true">
        <div class="max-w-6xl mx-auto px-4 transition-all duration-1000 transform"
             :class="shown ? 'opacity-100 scale-100' : 'opacity-0 scale-95'">
            <div class="relative overflow-hidden bg-[#001B48] rounded-3xl shadow-2xl p-8 md:p-20 text-center">
                
                <!-- Background Decor -->
                <div class="absolute top-0 right-0 -mt-20 -mr-20 w-80 h-80 bg-[#E3943B] rounded-full opacity-20 blur-3xl"></div>
                <div class="absolute bottom-0 left-0 -mb-20 -ml-20 w-80 h-80 bg-blue-400 rounded-full opacity-20 blur-3xl"></div>

                <div class="relative z-10">
                    <div class="inline-flex items-center px-4 py-1.5 rounded-full bg-[#E3943B]/20 text-[#E3943B] font-bold text-xs md:text-sm mb-6 border border-[#E3943B]/30">
                        <i data-lucide="shield-check" class="w-4 h-4 mr-2"></i> PENCEGAHAN DINI
                    </div>
                    
                    <h2 class="text-2xl md:text-5xl font-bold text-white mb-6 leading-tight">
                        Jangan Tunggu Sakit, <br>Deteksi Dini Sekarang.
                    </h2>
                    
                    <p class="text-base md:text-lg text-blue-100 mb-10 max-w-2xl mx-auto">
                        Hipertensi dijuluki <i>"The Silent Killer"</i> karena sering muncul tanpa gejala. Sistem pakar <b>TensiTrack</b> membantu Anda mengenali sinyal bahaya sebelum terlambat.
                    </p>
                    
                    <a href="@auth {{ route('client.screening.index') }} @else {{ route('login') }} @endauth" class="inline-flex items-center px-8 py-4 text-base md:text-lg font-bold text-[#001B48] bg-white rounded-lg hover:bg-gray-100 transition transform hover:scale-105 shadow-lg">
                        Mulai Skrining
                        <i data-lucide="arrow-right" class="w-5 h-5 ml-2"></i>
                    </a>
                </div>
            </div>
        </div>
    </section>

    <!-- Section: Artikel Terbaru (Carousel) -->
    <section id="artikel" class="py-12 md:py-20 bg-white overflow-hidden" x-data="{ shown: false }" x-intersect.half="shown = true">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 transition-all duration-1000 transform"
             :class="shown ? 'opacity-100 translate-y-0' : 'opacity-0 translate-y-10'">
            <div class="text-center mb-12">
                <h2 class="text-2xl md:text-3xl font-bold text-[#001B48] mb-4">Artikel Terbaru</h2>
                <p class="text-gray-600 max-w-2xl mx-auto text-sm md:text-base">
                    Dapatkan informasi terbaru seputar hipertensi, pola hidup sehat, dan tips menjaga tekanan darah tetap stabil.
                </p>
            </div>

            <!-- Carousel Wrapper (Keeping existing logic) -->
            <div x-data="{
                    activeSlide: 0,
                    itemsVisible: 1,
                    // ... (rest of carousel logic unchanged) ...
                    touchStartX: 0,
                    touchEndX: 0,
                    init() {
                        this.updateItemsVisible();
                        window.addEventListener('resize', () => this.updateItemsVisible());
                    },
                    updateItemsVisible() {
                        this.itemsVisible = window.innerWidth >= 1024 ? 3 : 1;
                    },
                    get maxIndex() {
                        return this.slides.length - this.itemsVisible;
                    },
                    next() {
                        this.activeSlide = this.activeSlide >= this.maxIndex ? 0 : this.activeSlide + 1;
                    },
                    prev() {
                        this.activeSlide = this.activeSlide <= 0 ? this.maxIndex : this.activeSlide - 1;
                    },
                    handleTouchStart(e) {
                        this.touchStartX = e.changedTouches[0].screenX;
                    },
                    handleTouchEnd(e) {
                        this.touchEndX = e.changedTouches[0].screenX;
                        if (this.touchEndX < this.touchStartX - 50) this.next();
                        if (this.touchEndX > this.touchStartX + 50) this.prev();
                    },
                    slides: [
                        {
                            title: 'Masalah Hipertensi di Indonesia & Aturan Konsumsi Garam',
                            desc: 'Mengapa deteksi dini dan pembatasan konsumsi garam sangat penting untuk mencegah hipertensi yang semakin marak di Indonesia?',
                            img: 'https://images.unsplash.com/photo-1628771065518-0d82f1938462?ixlib=rb-1.2.1&auto=format&fit=crop&w=500&q=60',
                            date: 'Artikel VOI',
                            link: 'https://voi.id/kesehatan/462260/masalah-hipertensi-di-indonesia-mengapa-deteksi-dini-dan-aturan-konsumsi-garam-itu-penting'
                        },
                        {
                            title: 'Bahaya Hipertensi: Upaya Pencegahan dan Pengendalian',
                            desc: 'Ketahui bahaya jangka panjang hipertensi dan langkah-langkah pengendalian yang disarankan oleh Kementerian Kesehatan RI.',
                            img: 'https://images.unsplash.com/photo-1576091160550-2173dba999ef?ixlib=rb-1.2.1&auto=format&fit=crop&w=500&q=60',
                            date: 'Artikel Kemkes',
                            link: 'https://kemkes.go.id/id/bahaya-hipertensi-upaya-pencegahan-dan-pengendalian-hipertensi'
                        },
                        {
                            title: 'Penerapan Diet DASH untuk Stop Hipertensi',
                            desc: 'Dietary Approach to Stop Hypertension (DASH) adalah metode diet yang terbukti efektif menurunkan tekanan darah tinggi. Pelajari caranya di sini.',
                            img: 'https://images.unsplash.com/photo-1490645935967-10de6ba17061?ixlib=rb-1.2.1&auto=format&fit=crop&w=500&q=60',
                            date: 'Artikel Keslan Kemkes',
                            link: 'https://keslan.kemkes.go.id/view_artikel/2681/penerapan-dietary-approach-to-stop-hypertension-dash'
                        },
                        {
                            title: '9 Faktor Risiko Hipertensi yang Perlu Diwaspadai',
                            desc: 'Selain konsumsi garam, ada banyak faktor lain pemicu darah tinggi. Ketahui 9 faktor risiko utama agar Anda bisa menghindarinya.',
                            img: 'https://images.unsplash.com/photo-1581056771107-24ca5f033842?ixlib=rb-1.2.1&auto=format&fit=crop&w=500&q=60',
                            date: 'Artikel Kompas',
                            link: 'https://www.kompas.com/tren/read/2023/03/31/073000465/9-faktor-risiko-hipertensi-yang-perlu-diwaspadai'
                        },
                        {
                            title: 'Olahraga Aman & Efektif untuk Penderita Hipertensi',
                            desc: 'Panduan lengkap jenis olahraga yang aman dilakukan menurut WHO dan Kemenkes RI untuk membantu mengontrol tekanan darah.',
                            img: 'https://images.unsplash.com/photo-1571019614242-c5c5dee9f50b?ixlib=rb-1.2.1&auto=format&fit=crop&w=500&q=60',
                            date: 'Public Health',
                            link: 'https://www.indonesian-publichealth.com/olahraga-untuk-hipertensi-panduan-lengkap-menurut-who-kemenkes-ri-aman-efektif/'
                        },
                        {
                            title: 'Tips Mengendalikan Hipertensi untuk Hidup Sehat',
                            desc: 'Hipertensi tidak bisa sembuh total, tapi bisa dikendalikan. Simak tips praktis dari dokter untuk menjaga kualitas hidup Anda.',
                            img: 'https://images.unsplash.com/photo-1505751172876-fa1923c5c528?ixlib=rb-1.2.1&auto=format&fit=crop&w=500&q=60',
                            date: 'Artikel Halodoc',
                            link: 'https://www.halodoc.com/artikel/tips-mengendalikan-hipertensi-untuk-hidup-sehat'
                        },
                        {
                            title: 'Daftar Makanan Penurun Darah Tinggi yang Ampuh',
                            desc: 'Selain obat, beberapa jenis makanan alami seperti seledri, bawang putih, dan buah bit dipercaya ampuh menurunkan tensi.',
                            img: 'https://images.unsplash.com/photo-1512621776951-a57141f2eefd?ixlib=rb-1.2.1&auto=format&fit=crop&w=500&q=60',
                            date: 'Artikel Alodokter',
                            link: 'https://www.alodokter.com/makanan-penurun-darah-tinggi'
                        },
                        {
                            title: 'Hubungan Stres dan Tekanan Darah Tinggi',
                            desc: 'Stres kronis dapat memicu lonjakan tekanan darah. Pelajari mekanisme biologisnya dan teknik relaksasi yang tepat.',
                            img: 'https://images.unsplash.com/photo-1476480862126-209bfaa8edc8?ixlib=rb-1.2.1&auto=format&fit=crop&w=500&q=60',
                            date: 'Artikel KlikDokter',
                            link: 'https://www.klikdokter.com/penyakit/masalah-jantung-dan-pembuluh-darah/hipertensi/stres-bisa-sebabkan-hipertensi'
                        },
                        {
                            title: 'Pentingnya Tidur Cukup bagi Penderita Hipertensi',
                            desc: 'Kurang tidur bisa memperburuk kondisi hipertensi. Temukan durasi tidur ideal dan tips mengatasi insomnia.',
                            img: 'https://images.unsplash.com/photo-1520206183501-b80df61043c2?ixlib=rb-1.2.1&auto=format&fit=crop&w=500&q=60',
                            date: 'Artikel HelloSehat',
                            link: 'https://hellosehat.com/jantung/hipertensi/tidur-dan-hipertensi/'
                        },
                        {
                            title: 'Cara Mengukur Tekanan Darah yang Benar di Rumah',
                            desc: 'Hasil tensimeter rumahan bisa tidak akurat jika caranya salah. Ikuti panduan langkah demi langkah agar hasil valid.',
                            img: 'https://images.unsplash.com/photo-1631815589968-fdb09a223b1e?ixlib=rb-1.2.1&auto=format&fit=crop&w=500&q=60',
                            date: 'Panduan Kemenkes',
                            link: 'https://ayosehat.kemkes.go.id/cara-mengukur-tekanan-darah-sendiri-di-rumah'
                        }
                    ]
                }" class="relative px-4 md:px-12">
                
                <!-- Slides Container -->
                <div class="overflow-hidden" @touchstart="handleTouchStart($event)" @touchend="handleTouchEnd($event)">
                    <div class="flex transition-transform duration-500 ease-out" :style="'transform: translateX(-' + (activeSlide * (100 / itemsVisible)) + '%)'">                        
                        <!-- Slide Item (Loop) -->
                        <template x-for="(slide, index) in slides" :key="index">
                            <div class="w-full lg:w-1/3 flex-shrink-0 px-4">
                                <div class="bg-white rounded-2xl border border-gray-100 shadow-lg overflow-hidden hover:shadow-xl transition-shadow duration-300 h-full flex flex-col">
                                    <!-- Image -->
                                    <div class="h-48 relative flex-shrink-0">
                                        <img :src="slide.img" alt="Artikel" class="absolute inset-0 w-full h-full object-cover">
                                    </div>
                                    <!-- Content -->
                                    <div class="p-6 flex flex-col flex-grow">
                                        <div class="text-xs text-[#E3943B] font-bold mb-2 uppercase tracking-wide" x-text="slide.date"></div>
                                        <a :href="slide.link" target="_blank" class="text-base md:text-lg font-bold text-[#001B48] mb-2 hover:text-blue-800 transition cursor-pointer block leading-tight line-clamp-2" x-text="slide.title"></a>
                                        <p class="text-gray-600 leading-relaxed mb-4 text-sm line-clamp-3 flex-grow" x-text="slide.desc"></p>
                                        <a :href="slide.link" target="_blank" class="inline-flex items-center text-[#001B48] font-bold hover:text-[#E3943B] transition group mt-auto text-sm">
                                            Baca Selengkapnya 
                                            <i data-lucide="arrow-right" class="w-4 h-4 ml-2 transform group-hover:translate-x-1 transition-transform"></i>
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </template>
                    </div>
                </div>

                <!-- Controls -->
                <button @click="prev()" 
                        class="hidden md:block absolute top-1/2 left-0 -translate-y-1/2 bg-white p-3 rounded-full shadow-lg text-[#001B48] hover:bg-gray-50 focus:outline-none z-10 border border-gray-100 ml-4">
                    <i data-lucide="chevron-left" class="w-6 h-6"></i>
                </button>
                <button @click="next()" 
                        class="hidden md:block absolute top-1/2 right-0 -translate-y-1/2 bg-white p-3 rounded-full shadow-lg text-[#001B48] hover:bg-gray-50 focus:outline-none z-10 border border-gray-100 mr-4">
                    <i data-lucide="chevron-right" class="w-6 h-6"></i>
                </button>

                <!-- Dots -->
                <div class="flex justify-center mt-8 space-x-2">
                    <template x-for="(slide, index) in slides" :key="index">
                        <button @click="activeSlide = index > maxIndex ? maxIndex : index" 
                                class="w-2.5 h-2.5 rounded-full transition-all duration-300"
                                :class="activeSlide === index ? 'bg-[#E3943B] w-8' : 'bg-gray-300 hover:bg-gray-400'"
                                x-show="index <= maxIndex"></button>
                    </template>
                </div>

            </div>
        </div>
    </section>

    <!-- Section: FAQ -->
    <section id="faq" class="py-12 md:py-20 bg-gray-50" x-data="{ shown: false }" x-intersect.half="shown = true">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 transition-all duration-1000 transform"
             :class="shown ? 'opacity-100 translate-y-0' : 'opacity-0 translate-y-10'">
            <div class="text-center mb-12">
                <h2 class="text-2xl md:text-3xl font-bold text-[#001B48] mb-4">Pertanyaan Umum</h2>
                <p class="text-gray-600 text-sm md:text-base">Temukan jawaban untuk pertanyaan yang sering diajukan.</p>
            </div>

            <div class="space-y-4" x-data="{ active: null }">
                <!-- FAQ Item 1 -->
                <div class="bg-white rounded-lg shadow-sm overflow-hidden border border-orange-300">
                    <button @click="active = (active === 1 ? null : 1)" class="flex justify-between items-center w-full p-5 text-left text-[#001B48] font-medium hover:bg-gray-50 transition text-sm md:text-base">
                        <span>Apakah hasil skrining ini merupakan diagnosis medis final?</span>
                        <i :class="active === 1 ? 'rotate-180' : ''" data-lucide="chevron-down" class="w-5 h-5 transition-transform duration-300 flex-shrink-0 ml-2"></i>
                    </button>
                    <div x-show="active === 1" x-collapse class="p-5 pt-0 text-gray-600 border-t border-gray-100 text-sm">
                        Tidak. TensiTrack hanya membantu memberikan indikasi awal terhadap risiko hipertensi, dan hasilnya tidak menggantikan diagnosis medis dari dokter.
                    </div>
                </div>
                
                <!-- FAQ Item 2 -->
                <div class="bg-white rounded-lg shadow-sm overflow-hidden border border-orange-300">
                    <button @click="active = (active === 2 ? null : 2)" class="flex justify-between items-center w-full p-5 text-left text-[#001B48] font-medium hover:bg-gray-50 transition text-sm md:text-base">
                        <span>Bagaimana cara kerja sistem TensiTrack?</span>
                        <i :class="active === 2 ? 'rotate-180' : ''" data-lucide="chevron-down" class="w-5 h-5 transition-transform duration-300 flex-shrink-0 ml-2"></i>
                    </button>
                    <div x-show="active === 2" x-collapse class="p-5 pt-0 text-gray-600 border-t border-gray-100 text-sm">
                        Sistem TensiTrack menggunakan metode forward chaining untuk menganalisis data pengguna dan menentukan potensi risiko hipertensi berdasarkan faktor-faktor kesehatan tertentu.
                    </div>
                </div>
                
                <!-- FAQ Item 3 -->
                <div class="bg-white rounded-lg shadow-sm overflow-hidden border border-orange-300">
                    <button @click="active = (active === 3 ? null : 3)" class="flex justify-between items-center w-full p-5 text-left text-[#001B48] font-medium hover:bg-gray-50 transition text-sm md:text-base">
                        <span>Apakah kalkulator BMI bisa digunakan oleh semua orang?</span>
                        <i :class="active === 3 ? 'rotate-180' : ''" data-lucide="chevron-down" class="w-5 h-5 transition-transform duration-300 flex-shrink-0 ml-2"></i>
                    </button>
                    <div x-show="active === 3" x-collapse class="p-5 pt-0 text-gray-600 border-t border-gray-100 text-sm">
                        Ya, kalkulator BMI dapat digunakan oleh siapa saja, tetapi hasilnya perlu disesuaikan dengan kondisi individu seperti usia dan massa otot.
                    </div>
                </div>

                <!-- FAQ Item 4 -->
                <div class="bg-white rounded-lg shadow-sm overflow-hidden border border-orange-300">
                    <button @click="active = (active === 4 ? null : 4)" class="flex justify-between items-center w-full p-5 text-left text-[#001B48] font-medium hover:bg-gray-50 transition text-sm md:text-base">
                        <span>Apakah data pribadi saya aman di TensiTrack?</span>
                        <i :class="active === 4 ? 'rotate-180' : ''" data-lucide="chevron-down" class="w-5 h-5 transition-transform duration-300 flex-shrink-0 ml-2"></i>
                    </button>
                    <div x-show="active === 4" x-collapse class="p-5 pt-0 text-gray-600 border-t border-gray-100 text-sm">
                        TensiTrack menerapkan sistem keamanan data dengan enkripsi agar informasi pribadi pengguna tetap terlindungi.
                    </div>
                </div>

                <!-- FAQ Item 5 -->
                <div class="bg-white rounded-lg shadow-sm overflow-hidden border border-orange-300">
                    <button @click="active = (active === 5 ? null : 5)" class="flex justify-between items-center w-full p-5 text-left text-[#001B48] font-medium hover:bg-gray-50 transition text-sm md:text-base">
                        <span>Apakah skrining perlu dilakukan secara berkala?</span>
                        <i :class="active === 5 ? 'rotate-180' : ''" data-lucide="chevron-down" class="w-5 h-5 transition-transform duration-300 flex-shrink-0 ml-2"></i>
                    </button>
                    <div x-show="active === 5" x-collapse class="p-5 pt-0 text-gray-600 border-t border-gray-100 text-sm">
                        Ya, pengguna disarankan melakukan skrining secara berkala untuk memantau perubahan risiko dan menjaga kesehatan secara berkelanjutan.
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- AlpineJS Logic for Calculator -->
    <script>
        document.addEventListener('alpine:init', () => {
            Alpine.data('bmiCalculator', () => ({
                gender: 'male',
                weight: '',
                height: '',
                bmiValue: 0,
                category: '',
                advice: '',
                result: false,

                calculateBMI() {
                    if (this.weight > 0 && this.height > 0) {
                        // Rumus BMI: BB / (TB/100)^2
                        const hInMeters = this.height / 100;
                        const bmiRaw = this.weight / (hInMeters * hInMeters);
                        this.bmiValue = bmiRaw.toFixed(1);
                        const bmi = parseFloat(this.bmiValue);
                        this.result = true;

                        // Kategori (Standar Asia/Indonesia)
                        if (bmi < 18.5) {
                            this.category = 'Kurus';
                            this.advice = 'Berat badan kurang. Tingkatkan asupan nutrisi dan konsultasi ahli gizi.';
                        } else if (bmi >= 18.5 && bmi <= 22.9) {
                            this.category = 'Normal';
                            this.advice = 'Selamat! Berat badan ideal. Pertahankan pola hidup sehat.';
                        } else if (bmi >= 23.0 && bmi <= 24.9) {
                            this.category = 'Gemuk';
                            this.advice = 'Kelebihan berat badan. Kurangi gula/lemak dan rutin olahraga.';
                        } else {
                            this.category = 'Obesitas';
                            this.advice = 'Waspada! Risiko penyakit tinggi. Segera konsultasi ke dokter untuk program diet.';
                        }
                    } else {
                        // Simple alert fallback if toast not available
                        alert('Harap masukkan berat dan tinggi badan yang valid.');
                    }
                }
            }))
        })
    </script>

@endsection