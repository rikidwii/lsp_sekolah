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

    <main class="flex-1 flex items-center overflow-x-clip">
        <section class="w-full max-w-[1400px] mx-auto px-6 sm:px-10 lg:px-16 pt-2 sm:pt-4 lg:pt-6 pb-10 lg:py-16">
            <div class="grid grid-cols-1 lg:grid-cols-12 gap-12 lg:gap-12 items-center">
                
                <!-- Left Column: Content (Animasi Masuk dari Bawah) -->
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
                            <!-- Pill Badge Sertifikat dengan Efek Hover -->
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
                        <!-- Pelajari Lebih Lanjut Button -->
                        <a 
                            href="{{ route('placeholder', ['title' => 'Tentang']) }}" 
                            class="inline-flex items-center gap-3 px-6 py-3.5 rounded-full bg-[#e8edf1] hover:bg-[#dfe5eb] text-slate-800 text-sm font-semibold transition-all duration-300 hover:scale-[1.03] hover:shadow-md active:scale-95"
                        >
                            <svg class="w-4 h-4 text-slate-800 shrink-0 transition-transform duration-300 group-hover:rotate-12" viewBox="0 0 24 24" fill="currentColor">
                                <path d="M19 4h-1V2h-2v2H8V2H6v2H5c-1.11 0-1.99.9-1.99 2L3 20a2 2 0 0 0 2 2h14c1.1 0 2-.9 2-2V6c0-1.1-.9-2-2-2zm0 16H5V10h14v10zm0-12H5V6h14v2zM7 12h5v5H7v-5z"/>
                            </svg>
                            <span>Pelajari Lebih Lanjut</span>
                        </a>

                        <!-- Jelajahi Skema Button -->
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

                <!-- Right Column: Visual Showcase + Floating Cards (Animasi Skala Masuk) -->
                <div class="lg:col-span-6 flex justify-center lg:justify-end animate-fade-in-scale">
                    <div class="relative w-full max-w-[540px] sm:max-w-[580px] mx-auto lg:mr-0">
                        
                        <!-- Main Background Card Image -->
                        <div class="relative rounded-[28px] overflow-hidden shadow-lg bg-slate-200 aspect-[4/4.1] group">
                            <img 
                                src="{{ asset('images/hero/hero-main-bg.jpg') }}"
                                alt="Asesmen Kompetensi"
                                class="w-full h-full object-cover transition-transform duration-700 group-hover:scale-105"
                            />
                            
                            <!-- Top Right Arrow Button -->
                            <a href="{{ route('placeholder', ['title' => 'Detail']) }}" class="absolute top-4 right-4 w-9 h-9 rounded-full bg-white/30 backdrop-blur-md text-white flex items-center justify-center hover:bg-white/75 hover:text-slate-900 transition-all duration-300 hover:scale-110">
                                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M7 17L17 7M7 7h10v10" />
                                </svg>
                            </a>
                        </div>

                        <!-- Floating Card 1: Top Left (Dr. Eng. Khoirul - Asesor) with Float & Hover -->
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
                            <!-- Vertical Green Label "Assesor" -->
                            <div class="w-7 rounded-xl bg-[#7aa39c] text-white flex items-center justify-center shrink-0">
                                <span class="text-[10px] font-semibold tracking-wider [writing-mode:vertical-rl]">Assesor</span>
                            </div>
                        </div>

                        <!-- Floating Card 2: Middle Right (Prof. Dr. Eko Prosojo) with Float & Hover -->
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

                        <!-- Floating Card 3: Bottom Left Pill (Prof. Saiful Mujani) with Hover -->
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
    </main>
</body>
</html>