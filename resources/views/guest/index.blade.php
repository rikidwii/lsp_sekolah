<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Lembaga Sertifikasi Profesi</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <style>
        /* Keyframes untuk Animasi Awal Saat Render */
        @keyframes fadeInUp {
            from {
                opacity: 0;
                transform: translateY(25px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        @keyframes fadeInScale {
            from {
                opacity: 0;
                transform: scale(0.95);
            }
            to {
                opacity: 1;
                transform: scale(1);
            }
        }

        @keyframes floatSlow {
            0%, 100% { transform: translateY(0); }
            50% { transform: translateY(-6px); }
        }

        .animate-fade-in-up {
            animation: fadeInUp 0.8s cubic-bezier(0.16, 1, 0.3, 1) forwards;
        }

        .animate-fade-in-scale {
            animation: fadeInScale 0.9s cubic-bezier(0.16, 1, 0.3, 1) forwards;
        }

        .animate-float {
            animation: floatSlow 4s ease-in-out infinite;
        }

        .animate-float-delayed {
            animation: floatSlow 5s ease-in-out infinite 1s;
        }
    </style>
</head>
<body class="bg-[#f4f7fa] font-['Plus_Jakarta_Sans',sans-serif] antialiased text-slate-900 selection:bg-teal-100 selection:text-teal-900 min-h-screen flex flex-col overflow-x-hidden">
    <x-navbar />

    <main class="flex-1 flex flex-col justify-between overflow-x-clip">
        <!-- HERO SECTION -->
        <section class="w-full max-w-[1400px] mx-auto px-6 sm:px-10 lg:px-16 pt-2 sm:pt-4 lg:pt-6 pb-10 lg:py-16">
            <div class="grid grid-cols-1 lg:grid-cols-12 gap-12 lg:gap-12 items-center">
                
                <!-- Left Column: Content -->
                <div class="lg:col-span-6 flex flex-col items-start animate-fade-in-up">
                    
                    <!-- Social Proof / Active Users -->
                    <div class="flex items-center gap-4 -mt-2 sm:-mt-4 mb-6 sm:mb-20 group cursor-default">
                        <div class="flex items-center -space-x-3.5 select-none">
                            <img 
                                src="{{ asset('images/hero/avatar-1.jpg') }}" 
                                alt="Avatar 1" 
                                class="h-11 w-11 sm:h-12 sm:w-12 rounded-full object-cover ring-2 ring-white shadow-xs transition-transform duration-300 hover:scale-110 hover:z-20"
                            />
                            <img 
                                src="{{ asset('images/hero/avatar-2.jpg') }}" 
                                alt="Avatar 2" 
                                class="h-11 w-11 sm:h-12 sm:w-12 rounded-full object-cover ring-2 ring-white shadow-xs transition-transform duration-300 hover:scale-110 hover:z-20"
                            />
                            <img 
                                src="{{ asset('images/hero/avatar-3.jpg') }}" 
                                alt="Avatar 3" 
                                class="h-11 w-11 sm:h-12 sm:w-12 rounded-full object-cover ring-2 ring-white shadow-xs transition-transform duration-300 hover:scale-110 hover:z-20"
                            />
                        </div>
                        <div class="flex flex-col justify-center">
                            <div class="text-2xl sm:text-[26px] font-extrabold text-slate-900 tracking-tight leading-none flex items-baseline">
                                <span>1,5</span>
                                <span class="text-xs font-bold text-slate-800 ml-1">K+</span>
                            </div>
                            <div class="text-[10px] sm:text-[11px] font-bold tracking-wider text-slate-700 uppercase mt-1.5">
                                PENGGUNA AKTIF
                            </div>
                        </div>
                    </div>

                    <!-- Main Headline -->
                    <h1 class="text-[2.15rem] sm:text-5xl lg:text-[3.1rem] xl:text-[3.45rem] font-bold tracking-tight text-[#0f172a] leading-[1.16] mb-9 sm:mb-11">
                        <span class="block">Validasi Kompetensi</span>
                        <span class="block mt-1">
                            Bangun <span class="text-[#126658] transition-colors duration-300 hover:text-[#0b4a3f]">Masa Depan</span>
                        </span>
                        <span class="flex items-center gap-3 sm:gap-4 mt-2 flex-wrap sm:flex-nowrap">
                            <!-- Pill Badge Sertifikat -->
                            <span class="inline-flex items-center justify-center bg-[#dce5e7] rounded-full h-9 sm:h-10 px-2.5 sm:px-3.5 overflow-visible shrink-0 transition-all duration-300 hover:bg-[#ccd8dc] hover:scale-105 cursor-pointer shadow-xs">
                                <img 
                                    src="{{ asset('images/hero/certificate-hero.png') }}" 
                                    alt="Sertifikat" 
                                    class="h-14 sm:h-16 w-auto object-contain select-none scale-125 transform -translate-y-0.5 transition-transform duration-300 hover:rotate-3"
                                />
                            </span>
                            <span>Profesional.</span>
                        </span>
                    </h1>

                    <!-- Action Buttons / CTA -->
                    <div class="flex flex-wrap items-center gap-4">
                        <a 
                            href="{{ route('placeholder', ['title' => 'Tentang']) }}" 
                            class="inline-flex items-center gap-3 px-6 py-3.5 rounded-full bg-[#e8edf1] hover:bg-[#dfe5eb] text-slate-800 text-sm font-semibold transition-all duration-300 hover:scale-[1.03] hover:shadow-md active:scale-95"
                        >
                            <svg class="w-4 h-4 text-slate-800 shrink-0 transition-transform duration-300 group-hover:rotate-12" viewBox="0 0 24 24" fill="currentColor">
                                <path d="M19 4h-1V2h-2v2H8V2H6v2H5c-1.11 0-1.99.9-1.99 2L3 20a2 2 0 0 0 2 2h14c1.1 0 2-.9 2-2V6c0-1.1-.9-2-2-2zm0 16H5V10h14v10zm0-12H5V6h14v2zM7 12h5v5H7v-5z"/>
                            </svg>
                            <span>Pelajari Lebih Lanjut</span>
                        </a>

                        <a 
                            href="{{ route('placeholder', ['title' => 'Skema']) }}" 
                            class="inline-flex items-center gap-3 pl-2 pr-6 py-2 rounded-full bg-[#095446] hover:bg-[#074237] text-white text-sm font-semibold shadow-sm transition-all duration-300 hover:scale-[1.03] hover:shadow-lg active:scale-95"
                        >
                            <span class="w-8 h-8 rounded-full bg-white text-[#095446] flex items-center justify-center shrink-0 transition-transform duration-300 hover:rotate-45">
                                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.2">
                                    <rect x="3" y="4" width="18" height="16" rx="2.5"></rect>
                                    <path stroke-linecap="round" d="M7 9h6M7 13h4M16 14a1.5 1.5 0 1 0 0-3 1.5 1.5 0 0 0 0 3z" />
                                </svg>
                            </span>
                            <span>Jelajahi Skema</span>
                        </a>
                    </div>
                </div>

                <!-- Right Column: Visual Showcase + Floating Cards -->
                <div class="lg:col-span-6 flex justify-center lg:justify-end animate-fade-in-scale">
                    <div class="relative w-full max-w-[540px] sm:max-w-[580px] mx-auto lg:mr-0">
                        
                        <div class="relative rounded-[28px] overflow-hidden shadow-lg bg-slate-200 aspect-[4/4.1] group">
                            <img 
                                src="{{ asset('images/hero/hero-main-bg.jpg') }}" 
                                alt="Asesmen Kompetensi" 
                                class="w-full h-full object-cover transition-transform duration-700 group-hover:scale-105"
                            />
                            
                            <a href="{{ route('placeholder', ['title' => 'Detail']) }}" class="absolute top-4 right-4 w-9 h-9 rounded-full bg-white/30 backdrop-blur-md text-white flex items-center justify-center hover:bg-white/75 hover:text-slate-900 transition-all duration-300 hover:scale-110">
                                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M7 17L17 7M7 7h10v10" />
                                </svg>
                            </a>
                        </div>

                        <!-- Floating Card 1 -->
                        <div class="hidden sm:flex items-stretch bg-white rounded-2xl p-3 shadow-[0_12px_32px_rgba(15,23,42,0.08)] border border-slate-100 absolute top-8 -left-24 lg:-left-32 z-10 w-[265px] animate-float transition-all duration-300 hover:scale-105 hover:shadow-xl hover:-translate-y-1">
                            <div class="flex-1 pr-2.5">
                                <div class="flex items-center gap-2.5">
                                    <img src="{{ asset('images/hero/khoirul.jpg') }}" alt="Dr. Eng. Khoirul" class="w-11 h-11 rounded-xl object-cover bg-amber-100 shrink-0" />
                                    <div>
                                        <p class="text-xs font-bold text-slate-900 leading-tight">Dr. Eng. Khoirul</p>
                                        <div class="flex items-center -space-x-1.5 mt-1.5">
                                            <img src="{{ asset('images/hero/mini-1.jpg') }}" class="w-5 h-5 rounded-full object-cover ring-1 ring-white" alt="" />
                                            <img src="{{ asset('images/hero/mini-2.jpg') }}" class="w-5 h-5 rounded-full object-cover ring-1 ring-white" alt="" />
                                            <img src="{{ asset('images/hero/mini-3.jpg') }}" class="w-5 h-5 rounded-full object-cover ring-1 ring-white" alt="" />
                                            <span class="text-[10px] font-bold text-slate-700 pl-2.5">+2</span>
                                        </div>
                                    </div>
                                </div>
                                <div class="flex items-center justify-between mt-3 pt-2 border-t border-slate-100">
                                    <div class="flex items-center gap-1 text-[11px] font-medium text-slate-500">
                                        <svg class="w-3.5 h-3.5 text-slate-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                            <circle cx="12" cy="12" r="9"></circle>
                                            <path stroke-linecap="round" d="M12 7v5l3 2" />
                                        </svg>
                                        <span>09:00 - 14:00</span>
                                    </div>
                                    <span class="px-2.5 py-0.5 rounded-full bg-[#80a8a1] text-white text-[10px] font-semibold">
                                        Pakar
                                    </span>
                                </div>
                            </div>
                            <div class="w-7 rounded-xl bg-[#7aa39c] text-white flex items-center justify-center shrink-0">
                                <span class="text-[10px] font-semibold tracking-wider [writing-mode:vertical-rl]">Assesor</span>
                            </div>
                        </div>

                        <!-- Floating Card 2 -->
                        <div class="bg-white rounded-2xl p-3 shadow-[0_14px_34px_rgba(15,23,42,0.1)] border border-slate-100 absolute top-1/2 -translate-y-1/2 right-5 sm:right-8 z-10 w-[210px] sm:w-[235px] animate-float-delayed transition-all duration-300 hover:scale-105 hover:shadow-2xl hover:-translate-y-[calc(50%+4px)]">
                            <div class="rounded-xl overflow-hidden bg-slate-100 aspect-[4/3.6] mb-2.5">
                                <img 
                                    src="{{ asset('images/hero/eko-prosojo.jpg') }}" 
                                    alt="Prof. Dr. Eko Prosojo" 
                                    class="w-full h-full object-cover object-top transition-transform duration-500 hover:scale-110"
                                />
                            </div>
                            <p class="text-[11px] sm:text-xs font-bold text-slate-900 leading-snug">
                                Administrasi Publik/Negara dan Bisnis
                            </p>
                            <div class="h-px bg-slate-200 my-2"></div>
                            <p class="text-[11px] sm:text-xs font-bold text-slate-900">Prof. Dr. Eko Prosojo</p>
                            <p class="text-[10px] font-medium text-slate-500">Asesor</p>
                        </div>

                        <!-- Floating Card 3 -->
                        <div class="inline-flex items-center gap-2.5 bg-white rounded-full pl-1.5 pr-4 py-1.5 shadow-[0_10px_25px_rgba(15,23,42,0.1)] border border-slate-100 absolute bottom-7 left-4 sm:left-8 z-10 transition-all duration-300 hover:scale-105 hover:shadow-lg cursor-pointer">
                            <img 
                                src="{{ asset('images/hero/saiful-mujani.jpg') }}" 
                                alt="Prof. Saiful Mujani" 
                                class="w-8 h-8 rounded-full object-cover shrink-0"
                            />
                            <span class="w-2 h-2 rounded-full bg-[#199e84] shrink-0 animate-pulse"></span>
                            <span class="text-xs font-bold text-slate-800 whitespace-nowrap">Prof. Saiful Mujani</span>
                        </div>

                    </div>
                </div>

            </div>
        </section>

        <!-- STATS SECTION -->
        <section class="w-full bg-[#719D94]/20 py-12 sm:py-10 my-8 border-y border-[#719D94]/30">
            <div class="max-w-[1400px] mx-auto px-6 sm:px-10 lg:px-16">
                <div class="grid grid-cols-2 md:grid-cols-4 gap-8 lg:gap-12 text-center">
                    
                    <!-- Stat 1 -->
                    <div class="flex flex-col items-center justify-center transition-transform duration-300 hover:-translate-y-1">
                        <div class="text-3xl sm:text-4xl lg:text-5xl font-extrabold text-slate-900 tracking-tight">
                            <span class="counter" data-target="15" data-decimals="1" data-suffix="K+">0</span>
                        </div>
                        <div class="text-xs sm:text-sm font-semibold text-slate-700 uppercase tracking-wider mt-2">
                            Total Asesi
                        </div>
                    </div>

                    <!-- Stat 2 -->
                    <div class="flex flex-col items-center justify-center transition-transform duration-300 hover:-translate-y-1">
                        <div class="text-3xl sm:text-4xl lg:text-5xl font-extrabold text-slate-900 tracking-tight">
                            <span class="counter" data-target="90" data-suffix="%">0</span>
                        </div>
                        <div class="text-xs sm:text-sm font-semibold text-slate-700 uppercase tracking-wider mt-2">
                            Tingkat Kelulusan
                        </div>
                    </div>

                    <!-- Stat 3 -->
                    <div class="flex flex-col items-center justify-center transition-transform duration-300 hover:-translate-y-1">
                        <div class="text-3xl sm:text-4xl lg:text-5xl font-extrabold text-slate-900 tracking-tight">
                            <span class="counter" data-target="1" data-suffix="K+">0</span>
                        </div>
                        <div class="text-xs sm:text-sm font-semibold text-slate-700 uppercase tracking-wider mt-2">
                            Asesor Terverifikasi
                        </div>
                    </div>

                    <!-- Stat 4 -->
                    <div class="flex flex-col items-center justify-center transition-transform duration-300 hover:-translate-y-1">
                        <div class="text-3xl sm:text-4xl lg:text-5xl font-extrabold text-slate-900 tracking-tight">
                            <span class="counter" data-target="60" data-suffix="">0</span>
                        </div>
                        <div class="text-xs sm:text-sm font-semibold text-slate-700 uppercase tracking-wider mt-2">
                            Skema Aktif
                        </div>
                    </div>

                </div>
            </div>
        </section>

        <!-- ABOUT US SECTION -->
        <section class="w-full max-w-[1400px] mx-auto px-6 sm:px-10 lg:px-16 py-12 lg:py-20">
            <div class="grid grid-cols-1 lg:grid-cols-12 gap-12 lg:gap-16 items-center">
                
                <!-- Left Column: Card with Image & Badges -->
                <div class="lg:col-span-6 flex justify-center lg:justify-start">
                    <div class="relative w-full max-w-[580px] bg-[#dce5e7]/60 border border-slate-200/80 rounded-[32px] p-6 sm:p-7 shadow-sm">
                        
                        <!-- Inner Layout: Flex row (Image on left, Content on right) -->
                        <div class="flex flex-col sm:flex-row gap-6 items-start">
                            
                            <!-- Image Box (Penuh di sebelah kiri) -->
                            <div class="relative w-full sm:w-[260px] shrink-0 rounded-[24px] overflow-hidden aspect-[4/4.5] shadow-md group">
                                <img 
                                    src="{{ asset('images/about/about-card.jpg') }}" 
                                    alt="Sertifikasi Profesi" 
                                    class="w-full h-full object-cover transition-transform duration-500 group-hover:scale-105"
                                />
                                
                                <!-- Badge Kardiologi -->
                                <div class="absolute bottom-3 left-3 flex items-center justify-between bg-white/95 backdrop-blur-md rounded-full px-4 py-3 shadow-md">
                                    <span class="text-slate-900 text-sm font-bold">Kardiologi</span>
                                </div>
                            </div>

                            <!-- Right Side: Badges & Text Content -->
                            <div class="flex-1 flex flex-col h-full justify-between w-full">
                                
                                <!-- Top Badges -->
                                <div class="flex items-center justify-start gap-3 mb-6">
                                    <span class="inline-flex items-center px-4 py-2 rounded-full bg-transparent border border-[#006A69] text-xs sm:text-sm font-bold text-slate-700 shadow-2xs">
                                        Lisensi BNSP
                                    </span>
                                    <span class="w-11 h-11 rounded-full bg-[#095446] text-white flex items-center justify-center shadow-sm shrink-0">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="1.2em" height="1.2em" viewBox="0 0 20 20">
                                            <path d="M0 0h20v20H0z" fill="none" />
                                            <path fill="currentColor" fill-rule="evenodd" d="M9.655 2.575c.22-.1.47-.1.69 0l9.167 4.167A.83.83 0 0 1 20 7.5v5.834a.833.833 0 0 1-1.667 0v-4.54l-1.666.757v3.201a2.5 2.5 0 0 1-1.32 2.204l-4.166 2.231a2.5 2.5 0 0 1-2.362 0l-4.167-2.231a2.5 2.5 0 0 1-1.319-2.203V9.55L.488 8.258a.834.834 0 0 1 0-1.516zm.69 9.85c-.22.1-.47.1-.69 0L5 10.31v2.444c0 .306.17.588.44.733l4.166 2.232a.84.84 0 0 0 .788 0l4.166-2.232a.83.83 0 0 0 .44-.734V10.31zM2.847 7.5L10 10.752L17.152 7.5L10 4.248z" />
                                        </svg>
                                    </span>
                                </div>

                                <!-- Text Content -->
                                <div class="flex flex-col">
                                    <span class="text-xs font-bold text-slate-500 mb-2.5">30/11/2024</span>
                                    <h3 class="text-xl sm:text-2xl font-extrabold text-slate-900 leading-snug">
                                        Buka Peluang <br> Kerja Lebih Luas dengan Sertifikasi
                                    </h3>
                                </div>

                            </div>

                        </div>

                    </div>
                </div>

                <!-- Right Column: Text & CTA -->
                <div class="lg:col-span-6 flex flex-col items-start">
                    <!-- About Us Tag -->
                    <span class="inline-flex items-center px-4 py-1.5 rounded-full bg-[#095446] text-white text-xs font-bold tracking-wider uppercase mb-6 shadow-sm">
                        About Us
                    </span>

                    <!-- Main Heading -->
                    <h2 class="text-3xl sm:text-[32px] font-bold tracking-tight text-slate-900 leading-[1.3] mb-8">
                        Langkah pasti menuju pengakuan <br class="hidden sm:inline">
                        <span class="bg-[#e2edf2] px-2 py-0.5 rounded-md text-[#095446]">kompetensi</span> 
                        bersama lembaga sertifikasi yang terpercaya dan <br> terlisensi.
                    </h2>

                    <!-- Action Buttons -->
                    <div class="flex flex-wrap items-center gap-4">
                        <a 
                            href="{{ route('placeholder', ['title' => 'Skema']) }}" 
                            class="inline-flex items-center gap-4 pl-6 pr-2 py-2 rounded-full border border-slate-300 hover:border-slate-400 bg-white hover:bg-slate-50 text-slate-900 text-sm font-semibold transition-all duration-300 shadow-2xs group"
                        >
                            <span>Jelajahi Skema</span>
                            <span class="w-9 h-9 rounded-full bg-slate-900 group-hover:bg-[#095446] text-white flex items-center justify-center transition-colors duration-300">
                                <svg class="w-4 h-4 transform group-hover:translate-x-0.5 transition-transform" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M5 12h14M12 5l7 7-7 7" />
                                </svg>
                            </span>
                        </a>

                        <div class="w-11 h-11 rounded-full bg-[#095446] text-white flex items-center justify-center shadow-sm">
                            <svg xmlns="http://www.w3.org/2000/svg" width="1em" height="1em" viewBox="0 0 20 20">
                                <path d="M0 0h20v20H0z" fill="none" />
                                <path fill="currentColor" fill-rule="evenodd" d="M9.655 2.575c.22-.1.47-.1.69 0l9.167 4.167A.83.83 0 0 1 20 7.5v5.834a.833.833 0 0 1-1.667 0v-4.54l-1.666.757v3.201a2.5 2.5 0 0 1-1.32 2.204l-4.166 2.231a2.5 2.5 0 0 1-2.362 0l-4.167-2.231a2.5 2.5 0 0 1-1.319-2.203V9.55L.488 8.258a.834.834 0 0 1 0-1.516zm.69 9.85c-.22.1-.47.1-.69 0L5 10.31v2.444c0 .306.17.588.44.733l4.166 2.232a.84.84 0 0 0 .788 0l4.166-2.232a.83.83 0 0 0 .44-.734V10.31zM2.847 7.5L10 10.752L17.152 7.5L10 4.248z" />
                            </svg>
                        </div>
                    </div>
                </div>

            </div>
        </section>

        <!-- FITUR UTAMA PLATFORM SECTION (Baru ditambahkan di bawah About Us Section) -->
        <section class="w-full max-w-[1400px] mx-auto px-6 sm:px-10 lg:px-16 py-12 lg:py-20">
            <!-- Section Header -->
            <div class="text-center max-w-2xl mx-auto mb-12 sm:mb-16">
                <h2 class="text-3xl sm:text-4xl font-bold tracking-tight text-slate-900 mb-3">
                    Fitur Utama Platform
                </h2>
                <p class="text-sm sm:text-base text-slate-600 leading-relaxed">
                    Sistem terintegrasi untuk mendukung seluruh proses asesmen dengan efisien dan transparan
                </p>
            </div>

            <!-- Staggered Bento / Card Showcase -->
            <div class="flex flex-col lg:flex-row items-center justify-center gap-4 sm:gap-5">
                
                <!-- Kolom 1: Gambar Kecil Kiri -->
                <div class="w-full sm:w-[220px] lg:w-[155px] h-[180px] rounded-[24px] overflow-hidden shadow-sm shrink-0 group">
                    <img 
                        src="{{ asset('images/features/feature-left.jpg') }}" 
                        alt="Ekosistem Terpadu" 
                        class="w-full h-full object-cover transition-transform duration-500 group-hover:scale-105"
                    />
                </div>

                <!-- Kolom 2: Card Bertumpuk (Akses Khusus & Multiperan) -->
                <div class="w-full sm:w-[320px] lg:w-[215px] bg-[#eceeed] rounded-[28px] p-2.5 flex flex-col gap-2.5 shrink-0 shadow-xs">
                    <!-- Sub-card Atas: Akses Khusus -->
                    <div class="bg-[#f4f5f4] rounded-[22px] p-5 text-center transition-transform duration-300 hover:-translate-y-0.5">
                        <h3 class="text-xs font-extrabold text-slate-900 uppercase tracking-wider mb-2">
                            Akses Khusus
                        </h3>
                        <p class="text-[11px] font-medium text-slate-600 leading-relaxed uppercase">
                            More than 685 trees were planted last year with the help of our activists
                        </p>
                    </div>
                    <!-- Sub-card Bawah: Multiperan -->
                    <div class="bg-[#dce5e7] rounded-[22px] p-5 text-center transition-transform duration-300 hover:-translate-y-0.5">
                        <h3 class="text-xs font-extrabold text-slate-900 uppercase tracking-wider mb-2">
                            Multiperan
                        </h3>
                        <p class="text-[11px] font-medium text-slate-700 leading-relaxed uppercase">
                            More than 685 trees were planted last year with the help of our activists
                        </p>
                    </div>
                </div>

                <!-- Kolom 3: Card Utama Tengah (Penjadwalan Otomatis) -->
                <div class="relative w-full sm:w-[340px] lg:w-[270px] h-[390px] sm:h-[430px] rounded-[32px] overflow-hidden shadow-md shrink-0 group">
                    <img 
                        src="{{ asset('images/features/feature-center.jpg') }}" 
                        alt="Penjadwalan Otomatis" 
                        class="w-full h-full object-cover transition-transform duration-700 group-hover:scale-105"
                    />
                    <!-- Ikon Lingkaran Putih di Atas -->
                    <div class="absolute top-5 left-1/2 -translate-x-1/2 w-12 h-12 rounded-full bg-white text-slate-800 flex items-center justify-center shadow-md">
                        <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <circle cx="12" cy="12" r="9"></circle>
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 7v5l3 2" />
                        </svg>
                    </div>
                    <!-- Label Kaca Buram di Bawah -->
                    <div class="absolute bottom-4 left-4 right-4 bg-white/85 backdrop-blur-md rounded-[20px] py-4 px-5 text-center shadow-sm">
                        <span class="block text-xs sm:text-sm font-extrabold text-slate-900 uppercase tracking-wide leading-snug">
                            Penjadwalan <br> Otomatis
                        </span>
                    </div>
                </div>

                <!-- Kolom 4: Card Vertikal Kanan (Sertifikat & Unggah Dokumen) -->
                <div class="relative w-full sm:w-[320px] lg:w-[225px] h-[340px] sm:h-[365px] rounded-[28px] overflow-hidden shadow-md shrink-0 group">
                    <img 
                        src="{{ asset('images/features/feature-right.jpg') }}" 
                        alt="Unggah Dokumen Penunjang" 
                        class="w-full h-full object-cover transition-transform duration-700 group-hover:scale-105"
                    />
                    <!-- Badge Outline Putih di Atas -->
                    <div class="absolute top-4 left-4">
                        <span class="inline-flex items-center px-3.5 py-1 rounded-full border border-white/85 bg-black/15 backdrop-blur-xs text-white text-[11px] font-bold tracking-wider uppercase">
                            Sertifikat
                        </span>
                    </div>
                    <!-- Kotak Kaca Buram di Bawah -->
                    <div class="absolute bottom-3.5 left-3.5 right-3.5 bg-white/90 backdrop-blur-md rounded-[20px] p-4 flex items-end justify-between gap-2 shadow-sm">
                        <span class="text-xs font-extrabold text-slate-900 uppercase leading-snug">
                            Unggah <br> Dokumen <br> Penunjang
                        </span>
                        <span class="w-7 h-7 rounded-full border border-slate-700 text-slate-800 flex items-center justify-center shrink-0 transition-transform duration-300 group-hover:rotate-45">
                            <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M7 17L17 7M7 7h10v10" />
                            </svg>
                        </span>
                    </div>
                </div>

                <!-- Kolom 5: Card Kecil Kanan (Penilaian Terpusat) -->
                <div class="w-full sm:w-[220px] lg:w-[155px] h-[180px] bg-[#c7d9dc] rounded-[24px] p-5 flex flex-col items-center justify-center text-center shadow-xs shrink-0 transition-transform duration-300 hover:-translate-y-1 group cursor-pointer">
                    <span class="w-8 h-8 rounded-full border border-slate-700 text-slate-800 flex items-center justify-center mb-3 transition-transform duration-300 group-hover:rotate-45">
                        <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M7 17L17 7M7 7h10v10" />
                        </svg>
                    </span>
                    <span class="text-xs font-bold text-slate-800 uppercase tracking-wide leading-snug">
                        Penilaian <br> Terpusat
                    </span>
                </div>

            </div>
        </section>
    </main>

    <!-- Script JavaScript untuk Animasi Angka (Counter) -->
    <script>
        document.addEventListener("DOMContentLoaded", () => {
            const counters = document.querySelectorAll(".counter");
            const speed = 150;

            const startCounting = (counter) => {
                const target = +counter.getAttribute("data-target");
                const decimals = +counter.getAttribute("data-decimals") || 0;
                const suffix = counter.getAttribute("data-suffix") || "";
                let current = 0;

                const increment = target / speed;

                const updateCount = () => {
                    current += increment;
                    if (current < target) {
                        if (decimals > 0) {
                            counter.innerText = (current / 10).toFixed(decimals).replace('.', ',') + suffix;
                        } else {
                            counter.innerText = Math.ceil(current) + suffix;
                        }
                        setTimeout(updateCount, 20);
                    } else {
                        if (decimals > 0) {
                            counter.innerText = (target / 10).toFixed(decimals).replace('.', ',') + suffix;
                        } else {
                            counter.innerText = target + suffix;
                        }
                    }
                };

                updateCount();
            };

            const observer = new IntersectionObserver((entries, observer) => {
                entries.forEach(entry => {
                    if (entry.isIntersecting) {
                        startCounting(entry.target);
                        observer.unobserve(entry.target);
                    }
                });
            }, { threshold: 0.5 });

            counters.forEach(counter => {
                observer.observe(counter);
            });
        });
    </script>
</body>
</html>