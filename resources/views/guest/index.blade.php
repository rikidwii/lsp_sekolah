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

        #fitur-utama { --ease: cubic-bezier(.22, 1, .36, 1); }
    #fitur-utama .fitur-outer { transition: height .7s var(--ease); }
    #fitur-utama [data-stage] { transition: none; }

    #fitur-utama .fcard, #fitur-utama .ftext {
        position: absolute; left: 0; top: 0; overflow: hidden;
        transition: transform .7s var(--ease), width .7s var(--ease), height .7s var(--ease), border-radius .7s var(--ease);
        will-change: transform, width, height;
    }
    #fitur-utama .fcard { background: #c7d9dc; box-shadow: 0 6px 18px rgba(15, 23, 42, .08); cursor: pointer; outline-offset: 3px; }
    #fitur-utama .fcard[data-role="L"], #fitur-utama .fcard[data-role="M"] { border-radius: 30px; }
    #fitur-utama .fcard[data-role="S"], #fitur-utama .fcard[data-role="B"] { border-radius: 24px; }
    #fitur-utama .fcard[data-role="S"] { cursor: default; }

    #fitur-utama .fcard-img { position: absolute; inset: 0; width: 100%; height: 100%; object-fit: cover; transition: opacity .5s, transform .7s var(--ease); }
    #fitur-utama .fcard[data-role="B"] .fcard-img { opacity: 0; }
    #fitur-utama .fcard:is([data-role="L"], [data-role="M"]):hover .fcard-img { transform: scale(1.06); }

    #fitur-utama :is(.fcard-top, .fcard-label, .fcard-tile, .fcard-blue) { opacity: 0; pointer-events: none; transition: opacity .25s; }
    #fitur-utama .fcard:is([data-role="L"], [data-role="M"]) :is(.fcard-top, .fcard-label) { opacity: 1; transition: opacity .4s .3s; }
    #fitur-utama .fcard[data-role="S"] .fcard-tile { opacity: 1; transition: opacity .4s .3s; }
    #fitur-utama .fcard[data-role="B"] .fcard-blue { opacity: 1; transition: opacity .4s .3s; }
    #fitur-utama .fcard[data-role="B"]:hover { filter: brightness(.97); }

    #fitur-utama .ftext { display: flex; flex-direction: column; gap: 10px; padding: 10px; border-radius: 28px; background: #eceeed; box-shadow: 0 2px 8px rgba(15, 23, 42, .05); }
    #fitur-utama .ftext-card { flex: 1; display: flex; flex-direction: column; justify-content: center; text-align: center; padding: 16px; border-radius: 22px; }
    #fitur-utama .ftext-card > * { transition: opacity .2s, transform .3s var(--ease); }
    #fitur-utama .ftext.is-swap .ftext-card > * { opacity: 0; transform: translateY(6px); }

    #fitur-utama .is-static, #fitur-utama .is-static * { transition: none !important; }
    @media (prefers-reduced-motion: reduce) {
        #fitur-utama .fcard, #fitur-utama .ftext, #fitur-utama .fitur-outer { transition-duration: .01s !important; }
    }
     #cara-kerja { --ease: cubic-bezier(.22, 1, .36, 1); }

    /* Viewport bisa di-scroll ke samping (sentuh, trackpad, shift+wheel, drag mouse) */
    #cara-kerja .ck-viewport { container-type: inline-size; overflow-x: auto; overflow-y: hidden; scrollbar-width: none; overscroll-behavior-x: contain; user-select: none; cursor: grab; }
    #cara-kerja .ck-viewport::-webkit-scrollbar { display: none; }
    #cara-kerja .ck-viewport.is-dragging { cursor: grabbing; }

    /* Rumus --cw HARUS sama dengan fungsi sizes() di JS. */
    #cara-kerja .ck-track {
        --gap: 16px;
        --cw: clamp(150px, calc((100cqw - 4 * var(--gap)) / 5), 250px);
        --dw: calc(var(--cw) * 1.8);
        --h: max(var(--cw), 210px);
        display: flex; gap: var(--gap); width: max-content;
    }
    #cara-kerja .ck-item {
        --x: 60px; --y: 0px;
        position: relative; flex: none; width: var(--cw); height: var(--h);
        transition: width .7s var(--ease), opacity .9s var(--ease) var(--d, 0s), transform .9s var(--ease) var(--d, 0s);
    }
    #cara-kerja .ck-item.is-active { width: var(--dw); }

    /* Wajah "card kecil" */
    #cara-kerja .ck-card {
        position: absolute; inset: 0; display: flex; flex-direction: column; justify-content: space-between; align-items: flex-start;
        padding: 18px; border-radius: 22px; background: #dce5e7; cursor: pointer; outline-offset: 3px;
        transition: opacity .4s .3s, visibility 0s .3s, background-color .3s;
    }
    #cara-kerja .ck-card:hover { background: #cfdcdf; }
    #cara-kerja .is-active .ck-card { opacity: 0; visibility: hidden; pointer-events: none; transition: opacity .2s, visibility 0s .2s; }

    /* Wajah "detail" (teks + tombol panah) */
    #cara-kerja .ck-detail {
        position: absolute; inset: 0; display: flex; flex-direction: column; justify-content: space-between; align-items: flex-start;
        padding: 4px 10px 4px 0; opacity: 0; visibility: hidden; transition: opacity .2s, visibility 0s .2s;
    }
    #cara-kerja .is-active .ck-detail { opacity: 1; visibility: visible; transition: opacity .45s .3s, visibility 0s .3s; }

    /* Animasi muncul saat section masuk layar (aktif hanya jika JS jalan: class ck-ready) */
    #cara-kerja .ck-reveal { --x: 0px; --y: 28px; transition: opacity .9s var(--ease) var(--d, 0s), transform .9s var(--ease) var(--d, 0s); }
    #cara-kerja.ck-ready .ck-reveal, #cara-kerja.ck-ready .ck-item { opacity: 0; transform: translate(var(--x), var(--y)); }
    #cara-kerja.ck-ready .ck-reveal.is-in, #cara-kerja.ck-ready .ck-viewport.is-in .ck-item { opacity: 1; transform: none; }

    #cara-kerja .is-static, #cara-kerja .is-static * { transition: none !important; }
    @media (prefers-reduced-motion: reduce) {
        #cara-kerja .ck-item, #cara-kerja .ck-reveal { transition-duration: .01s !important; transition-delay: 0s !important; --x: 0px !important; --y: 0px !important; }
    }

     #testimoni { --ease: cubic-bezier(.22, 1, .36, 1); }

    /* Card = container: semua ukuran isi pakai cqw sehingga proporsional di lebar berapa pun */
    #testimoni .tm-card { position: relative; container-type: inline-size; overflow: hidden; border-radius: 28px; background: #64748b; cursor: pointer; aspect-ratio: 304 / 428; outline-offset: 4px; box-shadow: 0 10px 30px rgba(15, 23, 42, .10); }
    #testimoni .tm-card.is-short { aspect-ratio: 304 / 285; }
    #testimoni .tm-img { position: absolute; inset: 0; width: 100%; height: 100%; object-fit: cover; transition: transform .9s var(--ease); }
    #testimoni .tm-card:hover .tm-img { transform: scale(1.05); }
    #testimoni .tm-shade { position: absolute; inset: 0; background: rgba(6, 16, 32, .42); opacity: 0; transition: opacity .5s; }
    #testimoni .tm-card.is-open .tm-shade { opacity: 1; }

    /* Tampilan awal: tombol Learn more + panah */
    #testimoni .tm-pill { position: absolute; left: 7.9cqw; bottom: 7.9cqw; padding: 3.3cqw 6.6cqw; border-radius: 999px; background: #fff; color: #0f172a; font-size: clamp(13px, 5.2cqw, 17px); font-weight: 500; line-height: 1; }
    #testimoni .tm-arrow { position: absolute; right: 7.9cqw; width: max(36px, 14.5cqw); aspect-ratio: 1; border-radius: 50%; display: grid; place-items: center; background: rgba(255, 255, 255, .45); backdrop-filter: blur(8px); color: #0f172a; }
    #testimoni .is-tall .tm-arrow { bottom: 7.9cqw; }
    #testimoni .is-short .tm-arrow { top: 6cqw; }
    #testimoni .tm-card:hover .tm-arrow { background: rgba(255, 255, 255, .75); }
    #testimoni .tm-closed > * { transition: opacity .4s .25s, transform .5s var(--ease) .25s, background-color .3s; }
    #testimoni .is-open .tm-closed > * { opacity: 0; transform: scale(.85); pointer-events: none; transition: opacity .2s, transform .3s; }

    /* Tampilan setelah diklik: profil + kutipan */
    #testimoni .tm-open { position: absolute; inset: 0; display: flex; flex-direction: column; justify-content: space-between; padding: 7.9cqw; pointer-events: none; }
    #testimoni .is-short .tm-open { flex-direction: column-reverse; padding: 6cqw 7.9cqw; gap: 4cqw; }
    #testimoni .is-short .tm-quote { padding: 6.5cqw 5.9cqw; }
    #testimoni .tm-who { display: flex; align-items: center; gap: 2.3cqw; min-width: 0; }
    #testimoni .tm-av { flex: none; width: max(38px, 18.5cqw); aspect-ratio: 1; border-radius: 50%; display: grid; place-items: center; background: #8aeae1; color: #0b5d57; font-weight: 800; font-size: clamp(14px, 6.6cqw, 22px); }
    #testimoni .tm-name { color: #fff; font-weight: 700; font-size: clamp(15px, 9cqw, 30px); line-height: 1.1; white-space: nowrap; overflow: hidden; text-overflow: ellipsis; }
    #testimoni .tm-cap { margin-top: 2.6cqw; padding-left: 1cqw; color: #fff; font-weight: 600; letter-spacing: .04em; font-size: clamp(10px, 3.9cqw, 13px); }
    #testimoni .tm-quote { margin: 0; padding: 9cqw 5.9cqw; border-radius: 9cqw; background: rgba(255, 255, 255, .55); backdrop-filter: blur(10px); color: #0f172a; font-style: italic; font-weight: 500; line-height: 1.42; font-size: clamp(12px, 5.6cqw, 18px); }

    #testimoni .tm-profile, #testimoni .tm-quote { opacity: 0; transform: translateY(var(--ty)); transition: opacity .25s, transform .3s; }
    #testimoni .is-tall .tm-profile { --ty: -16px; } #testimoni .is-tall .tm-quote { --ty: 28px; }
    #testimoni .is-short .tm-profile { --ty: 16px; } #testimoni .is-short .tm-quote { --ty: -28px; }
    #testimoni .is-open .tm-profile { opacity: 1; transform: none; transition: opacity .5s .15s, transform .6s var(--ease) .15s; }
    #testimoni .is-open .tm-quote { opacity: 1; transform: none; transition: opacity .5s .28s, transform .7s var(--ease) .28s; }

    /* Animasi muncul saat masuk layar (aktif jika JS jalan: class tm-ready) */
    #testimoni .tm-reveal { --y: 40px; transition: opacity .9s var(--ease) var(--d, 0s), transform .9s var(--ease) var(--d, 0s); }
    #testimoni.tm-ready .tm-reveal { opacity: 0; transform: translateY(var(--y)); }
    #testimoni.tm-ready .tm-reveal.is-in { opacity: 1; transform: none; }

    @media (prefers-reduced-motion: reduce) { #testimoni * { transition-duration: .01s !important; transition-delay: 0s !important; } }
    
    </style>

</head>

<body class="bg-[#f4f7fa] font-['Plus_Jakarta_Sans',sans-serif] antialiased text-slate-900 selection:bg-teal-100 selection:text-teal-900 min-h-screen flex flex-col overflow-x-hidden">
    <x-navbar />

    <main class="flex-1 flex flex-col justify-between overflow-x-clip">
        <!-- HERO SECTION -->
        <section id="home" class="w-full max-w-[1400px] mx-auto px-6 sm:px-10 lg:px-16 pt-2 sm:pt-4 lg:pt-6 pb-10 lg:py-16">
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
        <section id="about" class="w-full max-w-[1400px] mx-auto px-6 sm:px-10 lg:px-16 py-12 lg:py-20">
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
                                <div class="flex flex-col pt-20">
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

        <!-- FEATURE SECTION -->
        {{-- resources/views/components/fitur-utama.blade.php  →  pakai dengan <x-fitur-utama /> --}}
            @php
                $arrow = '<path d="M7 17 17 7M7 7h10v10"/>';
                // Urutan card TETAP: Akses → Jadwal → Unggah → Penilaian
                $features = [
                    [
                        'label' => ['Akses', 'Multi-peran'], 'top' => 'square', 'arrowLabel' => false, 'tileIcon' => false,
                        'img'   => 'images/features/feature-left.jpg',
                        'icon'  => '<circle cx="9" cy="8" r="3.5"/><path d="M2.5 20c0-3.6 2.9-6 6.5-6s6.5 2.4 6.5 6"/><path d="M19 8v6M16 11h6"/>',
                        'texts' => [
                            ['Akses Khusus', 'Dashboard dan hak akses disesuaikan untuk setiap pengguna'],
                            ['Multiperan', 'Satu platform untuk asesi, asesor, dan admin LSP'],
                        ],
                    ],
                    [
                        'label' => ['Penjadwalan', 'Otomatis'], 'top' => 'circle', 'arrowLabel' => false, 'tileIcon' => true,
                        'img'   => 'images/features/feature-center.jpg',
                        'icon'  => '<path d="M21 12a9 9 0 1 0-9 9"/><path d="M12 7v5l3 2"/><path d="m16 19 2 2 4-4"/>',
                        'texts' => [
                            ['Atur Jadwal', 'Tentukan jadwal asesmen dan asesor dengan cepat'],
                            ['Otomatis', 'Pengingat terkirim otomatis tanpa input manual'],
                        ],
                    ],
                    [
                        'label' => ['Unggah', 'Dokumen', 'Penunjang'], 'top' => 'badge', 'arrowLabel' => true, 'tileIcon' => false,
                        'img'   => 'images/features/feature-right.jpg',
                        'icon'  => '',
                        'texts' => [
                            ['Portofolio', 'Unggah portofolio dan bukti kerja sebagai pendukung asesmen'],
                            ['Sertifikat', 'Simpan sertifikat dan dokumen penunjang di satu tempat'],
                        ],
                    ],
                    [
                        'label' => ['Penilaian', 'Terpusat'], 'top' => 'square', 'arrowLabel' => false, 'tileIcon' => false,
                        'img'   => 'images/features/feature-left.jpg', // ganti dengan gambar khusus Penilaian bila sudah ada
                        'icon'  => '<path d="m6 16 6-12 6 12"/><path d="M8 12h8"/><path d="m16 20 2 2 4-4"/>',
                        'texts' => [
                            ['Hasil Nilai', 'Pantau hasil asesmen dan status kompetensi secara langsung'],
                            ['Presentasi', 'Rekap penilaian asesor tersaji rapi dalam satu tempat'],
                        ],
                    ],
                ];
                $svg = 'fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"';
            @endphp

        <section id="fitur-utama" class="w-full max-w-[1400px] mx-auto px-6 sm:px-10 lg:px-16 py-12 lg:py-20">
            <div class="text-center max-w-2xl mx-auto mb-12 sm:mb-16">
                <h2 class="text-3xl sm:text-4xl font-bold tracking-tight text-slate-900 mb-3">Fitur Utama Platform</h2>
                <p class="text-sm sm:text-base text-slate-600 leading-relaxed">
                    Sistem terintegrasi untuk mendukung seluruh proses asesmen dengan efisien dan transparan
                </p>
            </div>

            <div data-outer class="fitur-outer relative w-full" style="height:440px">
                <div data-stage class="absolute top-0 left-1/2 origin-top">

                    @foreach ($features as $i => $f)
                        <div data-feature="{{ $i }}" data-role="{{ $i === 0 ? 'S' : 'L' }}" role="button" tabindex="0"
                            aria-pressed="{{ $i === 0 ? 'true' : 'false' }}" aria-label="{{ implode(' ', $f['label']) }}"
                            class="fcard group">
                            <img src="{{ asset($f['img']) }}" alt="{{ implode(' ', $f['label']) }}" class="fcard-img" draggable="false">

                            {{-- Dekorasi atas (saat card besar) --}}
                            @if ($f['top'] === 'badge')
                                <span class="fcard-top absolute top-4 left-4 inline-flex items-center px-3.5 py-1 rounded-full border border-white/85 bg-black/15 backdrop-blur-xs text-white text-[11px] font-bold tracking-wider uppercase">Sertifikat</span>
                            @elseif ($f['top'] === 'circle')
                                <span class="fcard-top absolute top-5 left-1/2 -ml-6 w-12 h-12 rounded-full bg-white text-slate-800 grid place-items-center shadow-md">
                                    <svg class="w-5 h-5" stroke-width="1.8" {!! $svg !!}>{!! $f['icon'] !!}</svg>
                                </span>
                            @else
                                <span class="fcard-top absolute top-4 left-4 w-12 h-12 rounded-2xl bg-white text-slate-800 grid place-items-center shadow-md">
                                    <svg class="w-5 h-5" stroke-width="1.8" {!! $svg !!}>{!! $f['icon'] !!}</svg>
                                </span>
                            @endif

                            {{-- Ikon tengah (saat card jadi tile kecil aktif) --}}
                            @if ($f['tileIcon'])
                                <span class="fcard-tile absolute top-1/2 left-1/2 -mt-6 -ml-6 w-12 h-12 rounded-full bg-white text-slate-800 grid place-items-center shadow-md">
                                    <svg class="w-5 h-5" stroke-width="1.8" {!! $svg !!}>{!! $f['icon'] !!}</svg>
                                </span>
                            @endif

                            {{-- Label kaca (saat card besar) --}}
                            <div class="fcard-label absolute bottom-3.5 left-3.5 right-3.5 bg-white/85 backdrop-blur-md rounded-[20px] py-4 px-5 shadow-sm flex items-center gap-2 {{ $f['arrowLabel'] ? 'justify-between' : 'justify-center text-center' }}">
                                <span class="text-xs font-extrabold text-slate-900 uppercase leading-snug">{!! implode('<br>', array_map('e', $f['label'])) !!}</span>
                                @if ($f['arrowLabel'])
                                    <span class="w-7 h-7 rounded-full border border-slate-700 text-slate-800 grid place-items-center shrink-0">
                                        <svg class="w-3.5 h-3.5" stroke-width="2.4" {!! $svg !!}>{!! $arrow !!}</svg>
                                    </span>
                                @endif
                            </div>

                            {{-- Konten card biru kecil --}}
                            <div class="fcard-blue absolute inset-0 flex flex-col items-center justify-center gap-3 p-4 text-center">
                                <span class="w-8 h-8 rounded-full border border-slate-700 text-slate-800 grid place-items-center">
                                    <svg class="w-3.5 h-3.5" stroke-width="2.4" {!! $svg !!}>{!! $arrow !!}</svg>
                                </span>
                                <span class="text-xs font-bold text-slate-800 uppercase leading-snug">{{ implode(' ', $f['label']) }}</span>
                            </div>
                        </div>
                    @endforeach

                    {{-- Card teks bertumpuk (isi berganti sesuai card aktif) --}}
                    <div data-text class="ftext">
                        @foreach ($features[0]['texts'] as $k => $t)
                            <div class="ftext-card {{ $k === 0 ? 'bg-[#f4f5f4]' : 'bg-[#dce5e7]' }}">
                                <h3 class="text-xs font-extrabold text-slate-900 uppercase tracking-wider mb-2">{{ $t[0] }}</h3>
                                <p class="text-[11px] font-medium text-slate-600 leading-relaxed uppercase">{{ $t[1] }}</p>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </section>

            @php
        $ckSvg = 'fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"';
        $ckSteps = [
            ['title' => 'Pendaftaran Skema Kompetensi',
            'detail' => 'Calon asesi memilih skema sertifikasi yang sesuai dengan bidang keahlian dan melengkapi formulir pendaftaran melalui sistem.',
            'icon' => '<path d="M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M19 8v6M22 11h-6"/>'],
            ['title' => 'Verifikasi Berkas Oleh Admin',
            'detail' => 'Tim admin LSP akan melakukan pemeriksaan terhadap kelengkapan dokumen persyaratan atau portofolio bukti kerja milik asesi.',
            'icon' => '<path d="M15 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V7z"/><path d="M14 2v4a2 2 0 0 0 2 2h4"/><path d="m9 15 2 2 4-4"/>'],
            ['title' => 'Pelaksanaan Uji Kompetensi',
            'detail' => 'Asesi mengikuti proses asesmen melalui tes tertulis, observasi praktik, atau wawancara teknis bersama asesor berlisensi.',
            'icon' => '<rect width="8" height="4" x="8" y="2" rx="1"/><path d="M16 4h2a2 2 0 0 1 2 2v14a2 2 0 0 1-2 2H6a2 2 0 0 1-2-2V6a2 2 0 0 1 2-2h2"/><path d="M12 11h4M12 16h4M8 11h.01M8 16h.01"/>'],
            ['title' => 'Keputusan Hasil Asesmen',
            'detail' => 'Asesor memberikan rekomendasi akhir berupa status Kompeten (K) atau Belum Kompeten (BK) berdasarkan bukti yang telah dikumpulkan.',
            'icon' => '<path d="m3 17 2 2 4-4"/><path d="m3 7 2 2 4-4"/><path d="M13 6h8M13 12h8M13 18h8"/>'],
            ['title' => 'Penerbitan Sertifikat Resmi',
            'detail' => 'Bagi asesi yang telah dinyatakan Kompeten, LSP akan mengajukan penerbitan sertifikat resmi yang diakui oleh industri.',
            'icon' => '<circle cx="12" cy="8" r="6"/><path d="M15.477 12.89 17 22l-5-3-5 3 1.523-9.11"/>'],
        ];
    @endphp

    <section id="cara-kerja" class="w-full max-w-[1400px] mx-auto px-6 sm:px-10 lg:px-16 py-12 lg:py-20">
        {{-- Baris atas: kartu gambar + judul --}}
        <div class="grid grid-cols-1 lg:grid-cols-12 gap-10 lg:gap-16 items-center">
            <div style="--x:-48px;--y:0px" class="ck-reveal relative lg:col-span-5 rounded-[28px] overflow-hidden bg-slate-700 aspect-[4/3] lg:aspect-[400/254] group shadow-md">
                <img src="{{ asset('images/cara-kerja/cara-kerja-hero.jpg') }}" alt="Persiapkan diri Anda"
                    class="absolute inset-0 w-full h-full object-cover transition-transform duration-700 group-hover:scale-105">
                <div class="absolute inset-0" style="background:linear-gradient(to top, rgba(0,0,0,.65), rgba(0,0,0,.08) 60%, transparent)"></div>

                <span class="absolute top-4 left-4 inline-flex items-center px-4 py-1.5 rounded-full border border-white/85 bg-black/10 backdrop-blur-xs text-white text-[11px] font-semibold tracking-wider uppercase">Informasi</span>
                <span class="absolute top-4 right-4 w-9 h-9 rounded-full bg-[#006A69] text-white grid place-items-center">
                    <svg class="w-[18px] h-[18px]" stroke-width="1.8" {!! $ckSvg !!}><path d="M21.42 10.922a1 1 0 0 0-.019-1.838L12.83 5.18a2 2 0 0 0-1.66 0L2.6 9.08a1 1 0 0 0 0 1.832l8.57 3.908a2 2 0 0 0 1.66 0z"/><path d="M22 10v6"/><path d="M6 12.5V16a6 3 0 0 0 12 0v-3.5"/></svg>
                </span>

                <div class="absolute left-4 right-4 bottom-4 flex items-end justify-between gap-3">
                    <div>
                        <p class="text-white font-bold uppercase text-base sm:text-lg tracking-wide mb-3">Persiapkan Diri Anda</p>
                        <a href="{{ route('placeholder', ['title' => 'Daftar']) }}" class="inline-flex items-center px-5 py-2 rounded-full border border-white/85 text-white text-xs font-semibold uppercase tracking-wide hover:bg-white hover:text-slate-900 transition-colors">Daftar Sekarang</a>
                    </div>
                    <div class="flex gap-2 shrink-0">
                        {{-- Ganti href dengan kontak asli LSP --}}
                        <a href="https://wa.me/6200000000000" aria-label="WhatsApp" class="w-9 h-9 rounded-full bg-[#006A69] hover:bg-[#095446] text-white grid place-items-center transition-colors">
                            <svg class="w-[18px] h-[18px]" stroke-width="1.8" {!! $ckSvg !!}><path d="M7.9 20A9 9 0 1 0 4 16.1L2 22z"/></svg>
                        </a>
                        <a href="https://instagram.com/" aria-label="Instagram" class="w-9 h-9 rounded-full bg-[#006A69] hover:bg-[#095446] text-white grid place-items-center transition-colors">
                            <svg class="w-[18px] h-[18px]" stroke-width="1.8" {!! $ckSvg !!}><rect width="18" height="18" x="3" y="3" rx="5"/><circle cx="12" cy="12" r="4"/><path d="M17.5 6.5h.01"/></svg>
                        </a>
                        <a href="mailto:info@lsp.example" aria-label="Email" class="w-9 h-9 rounded-full bg-[#006A69] hover:bg-[#095446] text-white grid place-items-center transition-colors">
                            <svg class="w-[18px] h-[18px]" stroke-width="1.8" {!! $ckSvg !!}><rect width="20" height="16" x="2" y="4" rx="2"/><path d="m22 7-10 6L2 7"/></svg>
                        </a>
                    </div>
                </div>
            </div>

            <div class="lg:col-span-7 flex flex-col items-start gap-5">
                <div style="--d:.1s" class="ck-reveal flex flex-wrap items-center gap-x-4 gap-y-2">
                    <span class="inline-flex items-center px-4 py-2 rounded-full bg-[#006A69] text-white text-[15px] font-semibold">Cara Kerja Platform</span>
                    <span class="text-base text-slate-700">Tahapan yang dilalui untuk meraih pengakuan industri.</span>
                </div>
                <h2 style="--d:.2s" class="ck-reveal max-w-3xl text-[2rem] sm:text-5xl lg:text-[3.1rem] font-bold tracking-tight leading-[1.15] text-[#0f172a]">
                    Proses Sertifikasi Yang Mudah Dan Terstruktur Dari Awal Hingga Akhir
                </h2>
            </div>
        </div>

        {{-- Baris bawah: slider langkah --}}
        <div class="mt-10 lg:mt-12 flex flex-col md:flex-row md:items-center gap-6 md:gap-8">
            <div data-ck-viewport class="ck-viewport flex-1 min-w-0">
                <div data-ck-track class="ck-track">
                    @foreach ($ckSteps as $i => $s)
                        <article data-step="{{ $i }}" style="--d: {{ round(.2 + $i * .1, 2) }}s" class="ck-item {{ $i === 0 ? 'is-active' : '' }}">
                            <div class="ck-card" role="button" tabindex="0" aria-label="Lihat detail: {{ $s['title'] }}">
                                <span class="w-9 h-9 rounded-full bg-[#006A69] text-white grid place-items-center">
                                    <svg class="w-[18px] h-[18px]" stroke-width="1.8" {!! $ckSvg !!}>{!! $s['icon'] !!}</svg>
                                </span>
                                <span class="text-[22px] sm:text-base font-semibold text-slate-900 leading-snug">{{ $s['title'] }}</span>
                                <span class="rounded-full bg-white px-4 py-2 text-sm font-semibold text-slate-900">Learn More</span>
                            </div>
                            <div class="ck-detail">
                                <p class="text-base sm:text-[23px] font-semibold leading-[1.4] text-slate-900">{{ $s['detail'] }}</p>
                                <a href="{{ route('placeholder', ['title' => $s['title']]) }}" aria-label="Buka {{ $s['title'] }}"
                                class="w-11 h-11 rounded-full bg-slate-900 hover:bg-[#095446] text-white grid place-items-center transition-colors">
                                    <svg class="w-4 h-4" stroke-width="2.2" {!! $ckSvg !!}><path d="M7 17 17 7M7 7h10v10"/></svg>
                                </a>
                            </div>
                        </article>
                    @endforeach
                </div>
            </div>

            <div style="--d:.6s" class="ck-reveal flex md:flex-col gap-3 shrink-0 self-end md:self-center">
                <button type="button" data-ck-prev aria-label="Langkah sebelumnya"
                        class="w-11 h-11 rounded-full grid place-items-center border border-slate-900 bg-slate-900 text-white transition-opacity disabled:opacity-35 disabled:cursor-not-allowed">
                    <svg class="w-3.5 h-3.5" viewBox="0 0 24 24" fill="currentColor"><path d="M16 5v14L6 12z"/></svg>
                </button>
                <button type="button" data-ck-next aria-label="Langkah berikutnya"
                        class="w-11 h-11 rounded-full grid place-items-center border border-slate-900 text-slate-900 hover:bg-slate-900 hover:text-white transition disabled:opacity-35 disabled:cursor-not-allowed disabled:hover:bg-transparent disabled:hover:text-slate-900">
                    <svg class="w-3.5 h-3.5" viewBox="0 0 24 24" fill="currentColor"><path d="M8 5v14l10-7z"/></svg>
                </button>
            </div>
        </div>
    </section>

    <!-- PARTNERS / LOGO MITRA SECTION -->
        <section class="w-full max-w-[1400px] mx-auto px-6 sm:px-10 lg:px-16 py-12 lg:py-16">
            <div class="border-t border-b border-slate-200/80 py-8 sm:py-10">
                <div class="text-center mb-8">
                    <p class="text-xs sm:text-sm font-bold tracking-widest text-slate-400 uppercase">
                        Didukung & Telah Bekerjasama Dengan Lembaga Terpercaya
                    </p>
                </div>
                
                <!-- Logo Grid / Flex Container -->
                <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-5 gap-8 sm:gap-12 items-center justify-items-center">
                    
                    <!-- Logo 1: BNSP -->
                    <div class="flex items-center justify-center p-4 transition-all duration-300 hover:scale-105 group">
                        <img 
                            src="{{ asset('images/partners/bnsp.png') }}" 
                            alt="BNSP" 
                            class="max-h-12 w-auto object-contain filter grayscale opacity-75 group-hover:grayscale-0 group-hover:opacity-100 transition-all duration-300"
                        />
                    </div>

                    <!-- Logo 2: Tut Wuri Handayani / Kemdikbud -->
                    <div class="flex items-center justify-center p-4 transition-all duration-300 hover:scale-105 group">
                        <img 
                            src="{{ asset('images/partners/tut-wuri.png') }}" 
                            alt="Kemdikbud" 
                            class="max-h-12 w-auto object-contain filter grayscale opacity-75 group-hover:grayscale-0 group-hover:opacity-100 transition-all duration-300"
                        />
                    </div>

                    <!-- Logo 3: SMK Bisa Hebat -->
                    <div class="flex items-center justify-center p-4 transition-all duration-300 hover:scale-105 group">
                        <img 
                            src="{{ asset('images/partners/smk-bisa.png') }}" 
                            alt="SMK Bisa Hebat" 
                            class="max-h-12 w-auto object-contain filter grayscale opacity-75 group-hover:grayscale-0 group-hover:opacity-100 transition-all duration-300"
                        />
                    </div>

                    <!-- Logo 4: DOT -->
                    <div class="flex items-center justify-center p-4 transition-all duration-300 hover:scale-105 group">
                        <img 
                            src="{{ asset('images/partners/dot.png') }}" 
                            alt="DOT" 
                            class="max-h-10 w-auto object-contain filter grayscale opacity-75 group-hover:grayscale-0 group-hover:opacity-100 transition-all duration-300"
                        />
                    </div>

                    <!-- Logo 5: Telkom Indonesia -->
                    <div class="flex items-center justify-center p-4 transition-all duration-300 hover:scale-105 group col-span-2 sm:col-span-1">
                        <img 
                            src="{{ asset('images/partners/telkom.png') }}" 
                            alt="Telkom Indonesia" 
                            class="max-h-10 w-auto object-contain filter grayscale opacity-75 group-hover:grayscale-0 group-hover:opacity-100 transition-all duration-300"
                        />
                    </div>

                </div>
            </div>
        </section>

        <!-- testimonials -->
                @php
            $tmItems = [
                ['tall' => true,  'label' => null, 'img' => 'images/testimonials/testimonial-1.jpg', 'ini' => 'AS', 'name' => 'Andi Setiawan', 'role' => 'Lulusan Rekayasa Perangkat Lunak',
                'quote' => 'Sertifikat dari LSP sangat membantu saya saat melamar kerja. Perusahaan langsung percaya dengan kemampuan programming saya karena sudah tervalidasi secara resmi.'],
                ['tall' => false, 'label' => 'Cerita Sukses Alumni', 'img' => 'images/testimonials/testimonial-2.jpg', 'ini' => 'AM', 'name' => 'Dr. Andrian M', 'role' => 'Lulusan Kedokteran',
                'quote' => 'Sertifikat LSP bikin CV makin gacor. Lulus langsung auto direkrut RS ternama!'],
                ['tall' => true,  'label' => null, 'img' => 'images/testimonials/testimonial-3.jpg', 'ini' => 'DN', 'name' => 'Dina Nurhaliza', 'role' => 'Lulusan Akuntansi',
                'quote' => 'Proses asesmennya sangat profesional. Asesor tidak hanya menguji, tapi juga memberikan feedback yang membangun untuk karir saya ke depannya.'],
                ['tall' => false, 'label' => 'BLOG/ARTICLE', 'img' => 'images/testimonials/testimonial-4.jpg', 'ini' => 'TM', 'name' => 'Tiara Maharani', 'role' => 'Lulusan Ilmu Keperawatan',
                'quote' => 'Validasi LSP emang ngaruh banget! Apply kerja di klinik elit jadi super easy.'],
            ];
        @endphp

        <section id="testimoni" class="w-full max-w-[1400px] mx-auto px-6 sm:px-10 lg:px-16 py-12 lg:py-20">
            <div class="flex flex-col lg:flex-row lg:items-start lg:justify-between gap-5 lg:gap-12">
                <h2 class="tm-reveal max-w-[640px] text-[2.1rem] sm:text-5xl font-bold tracking-tight leading-[1.15] text-[#0f172a]">
                    Jejak Langkah Profesional Bersama LSP
                </h2>
                <p style="--d:.15s" class="tm-reveal max-w-[420px] text-base text-slate-600 leading-relaxed lg:mt-2">
                    Pengalaman mereka yang telah membuktikan kompetensinya melalui Lembaga Sertifikasi Profesional.
                </p>
            </div>

            <div class="mt-12 lg:mt-20 grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6 items-end">
                @foreach ($tmItems as $i => $t)
                    <div style="--y:70px; --d: {{ round(.25 + $i * .12, 2) }}s" class="tm-reveal w-full max-w-[380px] sm:max-w-none mx-auto">
                        @if ($t['label'])
                            <p class="mb-4 text-lg xl:text-[22px] font-semibold text-slate-900">{{ $t['label'] }}</p>
                        @endif

                        <article class="tm-card {{ $t['tall'] ? 'is-tall' : 'is-short' }}" role="button" tabindex="0"
                                aria-expanded="false" aria-label="Lihat cerita {{ $t['name'] }}">
                            <img src="{{ asset($t['img']) }}" alt="{{ $t['name'] }}" class="tm-img" draggable="false">
                            <div class="tm-shade"></div>

                            {{-- Tampilan awal --}}
                            <div class="tm-closed" aria-hidden="true">
                                @if ($t['tall'])
                                    <span class="tm-pill">Learn more</span>
                                @endif
                                <span class="tm-arrow">
                                    <svg class="w-[42%]" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M7 17 17 7M7 7h10v10"/></svg>
                                </span>
                            </div>

                            {{-- Tampilan setelah diklik --}}
                            <div class="tm-open">
                                <div class="tm-profile">
                                    <div class="tm-who">
                                        <span class="tm-av">{{ $t['ini'] }}</span>
                                        <span class="tm-name">{{ $t['name'] }}</span>
                                    </div>
                                    <p class="tm-cap">{{ $t['role'] }}</p>
                                </div>
                                <blockquote class="tm-quote">"{{ $t['quote'] }}"</blockquote>
                            </div>
                        </article>
                    </div>
                @endforeach
            </div>
        </section>
    </main>
    <footer id="footer" class="w-full mt-8">

    {{-- Bagian atas dengan background & sudut melengkung di kedua sisi atas --}}
    <div class="bg-[#dce5e7]/50 border-t border-slate-200/80 rounded-t-[32px] sm:rounded-t-[40px]">
        <div class="max-w-[1400px] mx-auto px-6 sm:px-10 lg:px-16 pt-12 lg:pt-16 pb-12 lg:pb-16 grid grid-cols-1 lg:grid-cols-12 gap-10 lg:gap-8">

            {{-- Kolom Kiri: Heading + 1 Baris Sub-teks --}}
            <div class="lg:col-span-6 max-w-[34rem] lg:max-w-none flex flex-col items-start">
                <h2 class="text-xl sm:text-2xl lg:text-[1.75rem] font-bold tracking-tight leading-[1.25] text-[#0f172a]">
                    Membangun generasi profesional dengan kompetensi yang diakui industri
                </h2>
                <p class="mt-3 text-sm sm:text-base text-slate-600 leading-relaxed">
                    Layanan sertifikasi profesi terstandar 
                </p>
            </div>

            {{-- Kolom Kanan: Navigasi & Newsletter --}}
            <div class="lg:col-span-6 grid grid-cols-1 sm:grid-cols-[1fr_1fr_1.4fr] gap-8 sm:gap-6 lg:gap-8">

                <nav aria-label="Layanan kami">
                    <h3 class="text-[17px] font-semibold uppercase text-slate-900 mb-5">Layanan Kami</h3>
                    <ul class="space-y-3 text-[15px] uppercase text-slate-800">
                        <li><a href="{{ route('placeholder', ['title' => 'Skema Sertifikasi']) }}" class="hover:text-[#095446] transition-colors">Skema Sertifikasi</a></li>
                        <li><a href="{{ route('placeholder', ['title' => 'Jadwal Asesmen']) }}" class="hover:text-[#095446] transition-colors">Jadwal Asesmen</a></li>
                        <li><a href="{{ route('placeholder', ['title' => 'Cek Sertifikasi']) }}" class="hover:text-[#095446] transition-colors">Cek Sertifikasi</a></li>
                    </ul>
                </nav>

                <nav aria-label="Tentang LSP">
                    <h3 class="text-[17px] font-semibold uppercase text-slate-900 mb-5">Tentang LSP</h3>
                    <ul class="space-y-3 text-[15px] uppercase text-slate-800">
                        <li><a href="{{ route('placeholder', ['title' => 'Visi & Misi']) }}" class="hover:text-[#095446] transition-colors">Visi &amp; Misi</a></li>
                        <li><a href="{{ route('placeholder', ['title' => 'Kontak Kami']) }}" class="hover:text-[#095446] transition-colors">Kontak Kami</a></li>
                    </ul>
                </nav>

                <div>
                    <h3 class="text-[17px] font-semibold uppercase text-slate-900 mb-5">Newsletter</h3>
                    {{-- Ganti action dengan route newsletter asli (method POST sudah ada @csrf) --}}
                    <form action="#" method="POST" onsubmit="event.preventDefault()"
                          class="flex items-center rounded-full border border-[#006A69] bg-white/70 pl-6 pr-1.5 py-1.5 transition-shadow focus-within:ring-4 focus-within:ring-[#006A69]/15">
                        @csrf
                        <input type="email" name="email" required placeholder="Email" aria-label="Alamat email"
                               class="min-w-0 flex-1 bg-transparent outline-none text-base text-slate-900 placeholder:text-slate-700">
                        <button type="submit" aria-label="Berlangganan newsletter"
                                class="w-11 h-11 shrink-0 rounded-full bg-[#095446] hover:bg-[#074237] text-white grid place-items-center transition-colors">
                            <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round"><path d="M5 12h14M12 5l7 7-7 7"/></svg>
                        </button>
                    </form>
                </div>

            </div>
        </div>
    </div>

        {{-- Bagian bawah --}}
        <div class="bg-[#ccdadd]">
            <div class="max-w-[1400px] mx-auto px-6 sm:px-10 lg:px-16 py-2 lg:py-4 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-5">
                <p class="text-[15px] text-slate-900">{{ date('Y') }} LSP SMKN 1 Purwosari | All rights reserved.</p>
                <div class="flex flex-wrap items-center gap-2.5">
                    <a href="{{ route('placeholder', ['title' => 'Terms & Condition']) }}"
                    class="inline-flex items-center px-3.5 py-1.5 rounded-full border border-slate-900 text-xs sm:text-[13px] uppercase text-slate-900 hover:bg-slate-900 hover:text-white transition-colors">
                        Terms &amp; Condition
                    </a>
                    <a href="{{ route('placeholder', ['title' => 'Privacy Policy']) }}"
                    class="inline-flex items-center px-3.5 py-1.5 rounded-full border border-slate-900 text-xs sm:text-[13px] uppercase text-slate-900 hover:bg-slate-900 hover:text-white transition-colors">
                        Privacy Policy
                    </a>
                </div>
            </div>
        </div>
    </footer>


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

        (() => {
        const sec = document.getElementById('fitur-utama');
        if (!sec) return;

        const outer = sec.querySelector('[data-outer]');
        const stage = sec.querySelector('[data-stage]');
        const cards = [...stage.querySelectorAll('[data-feature]')];
        const box = stage.querySelector('[data-text]');
        const TEXTS = @json(array_column($features, 'texts'));

        // Ukuran tiap peran: L = besar, M = sedang, S = tile kecil (aktif), B = kotak biru, T = card teks
        const ROW = { L: [260, 420], M: [220, 360], S: [160, 170], B: [160, 170], T: [220, 300] }; // desktop [lebar, tinggi]
        const COL = { L: 230, M: 190, S: 120, B: 100, T: 210 };                                    // mobile (tinggi)
        const GAP_ROW = 16, GAP_COL = 12, STAGE_W = 1140, STAGE_H = 440;

        let active = 0, timer;

        // Card aktif = S. Yang paling jauh dari aktif = B. Sisanya: jarak 1 = L, jarak 2 = M.
        const getRoles = (a) => {
            const others = [0, 1, 2, 3].filter(i => i !== a);
            const far = Math.max(...others.map(i => Math.abs(i - a)));
            const r = { [a]: 'S' };
            others.forEach(i => {
                const d = Math.abs(i - a);
                r[i] = d === far ? 'B' : d === 1 ? 'L' : 'M';
            });
            return r;
        };

        // Urutan tampil: card teks selalu menempel di sisi aktif yang menghadap ke tengah
        const getOrder = (a) => {
            const after = a <= 1 ? a : a - 1;
            const list = [];
            for (let i = 0; i < 4; i++) {
                list.push({ i });
                if (i === after) list.push({ t: true });
            }
            return list;
        };

        const place = (el, x, y, w, h) => {
            el.style.width = w + 'px';
            el.style.height = h + 'px';
            el.style.transform = `translate(${x}px, ${y}px)`;
        };

        function layout() {
            const roles = getRoles(active);
            const items = getOrder(active).map(o => ({ ...o, role: o.t ? 'T' : roles[o.i] }));
            const W = outer.clientWidth;
            const row = window.innerWidth >= 1024;

            cards.forEach((c, i) => c.dataset.role = roles[i]);

            if (row) {
                const total = items.reduce((s, it) => s + ROW[it.role][0], 0) + GAP_ROW * (items.length - 1);
                let x = (STAGE_W - total) / 2;
                items.forEach(it => {
                    const [w, h] = ROW[it.role];
                    place(it.t ? box : cards[it.i], x, (STAGE_H - h) / 2, w, h);
                    x += w + GAP_ROW;
                });
                const s = Math.min(1, W / STAGE_W);
                stage.style.width = STAGE_W + 'px';
                stage.style.height = STAGE_H + 'px';
                stage.style.transform = `translateX(-50%) scale(${s})`;
                outer.style.height = STAGE_H * s + 'px';
            } else {
                const w = Math.min(W, 420);
                let y = 0;
                items.forEach(it => {
                    const h = COL[it.role];
                    place(it.t ? box : cards[it.i], (W - w) / 2, y, w, h);
                    y += h + GAP_COL;
                });
                stage.style.width = W + 'px';
                stage.style.height = (y - GAP_COL) + 'px';
                stage.style.transform = 'translateX(-50%)';
                outer.style.height = (y - GAP_COL) + 'px';
            }
        }

        function fillText(a) {
            box.querySelectorAll('.ftext-card').forEach((c, k) => {
                c.children[0].textContent = TEXTS[a][k][0];
                c.children[1].textContent = TEXTS[a][k][1];
            });
        }

        function go(i) {
            if (i === active) return;
            active = i;
            layout();
            cards.forEach((c, k) => c.setAttribute('aria-pressed', k === i ? 'true' : 'false'));
            box.classList.add('is-swap');
            clearTimeout(timer);
            timer = setTimeout(() => { fillText(i); box.classList.remove('is-swap'); }, 220);
        }

        // Render tanpa animasi (awal & saat resize)
        const snap = () => {
            outer.classList.add('is-static');
            layout();
            void outer.offsetWidth;
            requestAnimationFrame(() => outer.classList.remove('is-static'));
        };

        cards.forEach((c, i) => {
            c.addEventListener('click', () => go(i));
            c.addEventListener('keydown', (e) => {
                if (e.key === 'Enter' || e.key === ' ') { e.preventDefault(); go(i); }
            });
        });

        let rt;
        window.addEventListener('resize', () => { clearTimeout(rt); rt = setTimeout(snap, 120); });
        snap();
    })();

     (() => {
        const sec = document.getElementById('cara-kerja');
        if (!sec) return;

        const vp = sec.querySelector('[data-ck-viewport]');
        const track = sec.querySelector('[data-ck-track]');
        const items = [...track.querySelectorAll('[data-step]')];
        const prev = sec.querySelector('[data-ck-prev]');
        const next = sec.querySelector('[data-ck-next]');
        const GAP = 16; // sama dengan --gap di CSS
        let cur = 0, drag = null, moved = false;

        // Sama dengan rumus --cw / --dw di CSS
        const sizes = () => {
            const vw = vp.clientWidth;
            const cw = Math.min(250, Math.max(150, (vw - 4 * GAP) / 5));
            return { vw, cw, dw: cw * 1.8 };
        };

        // Geser (scroll) seperlunya agar card aktif terlihat penuh
        function reveal(smooth = true) {
            const { vw, cw, dw } = sizes();
            const left = cur * (cw + GAP), right = left + dw;
            const total = dw + (items.length - 1) * (cw + GAP);
            let to = vp.scrollLeft;
            if (right > to + vw) to = right - vw;
            if (left < to) to = left;
            to = Math.min(Math.max(0, to), Math.max(0, total - vw));
            vp.scrollTo({ left: to, behavior: smooth ? 'smooth' : 'auto' });
        }

        function render() {
            items.forEach((el, i) => el.classList.toggle('is-active', i === cur));
            prev.disabled = cur === 0;
            next.disabled = cur === items.length - 1;
        }

        function go(i, byKey = false) {
            const t = Math.max(0, Math.min(items.length - 1, i));
            if (t === cur) return;
            cur = t;
            render();
            reveal(true);
            if (byKey) setTimeout(() => items[cur].querySelector('.ck-detail a')?.focus({ preventScroll: true }), 320);
        }

        prev.addEventListener('click', () => go(cur - 1));
        next.addEventListener('click', () => go(cur + 1));
        items.forEach((el, i) => el.querySelector('.ck-card').addEventListener('click', () => go(i)));

        // Drag dengan mouse (sentuh & trackpad sudah native)
        vp.addEventListener('pointerdown', (e) => {
            moved = false;
            if (e.pointerType === 'mouse' && e.button === 0) drag = { x: e.clientX, s: vp.scrollLeft };
        });
        window.addEventListener('pointermove', (e) => {
            if (!drag) return;
            const dx = e.clientX - drag.x;
            if (Math.abs(dx) > 5) { moved = true; vp.classList.add('is-dragging'); }
            if (moved) vp.scrollLeft = drag.s - dx;
        });
        const endDrag = () => { drag = null; vp.classList.remove('is-dragging'); };
        window.addEventListener('pointerup', endDrag);
        window.addEventListener('pointercancel', endDrag);
        // Setelah drag, jangan anggap sebagai klik (card / link)
        vp.addEventListener('click', (e) => { if (moved) { e.preventDefault(); e.stopPropagation(); moved = false; } }, true);

        // Keyboard
        vp.addEventListener('keydown', (e) => {
            moved = false;
            if (e.key === 'ArrowRight') go(cur + 1, true);
            else if (e.key === 'ArrowLeft') go(cur - 1, true);
            else if ((e.key === 'Enter' || e.key === ' ') && e.target.classList.contains('ck-card')) {
                e.preventDefault();
                go(items.indexOf(e.target.closest('[data-step]')), true);
            }
        });

        // Resize: tanpa animasi
        let rt;
        window.addEventListener('resize', () => {
            clearTimeout(rt);
            rt = setTimeout(() => {
                vp.classList.add('is-static');
                reveal(false);
                void vp.offsetWidth;
                requestAnimationFrame(() => vp.classList.remove('is-static'));
            }, 120);
        });

        // Animasi muncul saat masuk layar
        const targets = sec.querySelectorAll('.ck-reveal, [data-ck-viewport]');
        if ('IntersectionObserver' in window) {
            sec.classList.add('ck-ready');
            const io = new IntersectionObserver((entries) => entries.forEach((en) => {
                if (en.isIntersecting) { en.target.classList.add('is-in'); io.unobserve(en.target); }
            }), { threshold: 0.2 });
            targets.forEach((el) => io.observe(el));
        }

        render();
    })();

    (() => {
        const sec = document.getElementById('testimoni');
        if (!sec) return;

        // Klik card = buka/tutup (tiap card independen, bisa terbuka semua)
        const toggle = (card) => {
            const open = card.classList.toggle('is-open');
            card.setAttribute('aria-expanded', open ? 'true' : 'false');
        };
        sec.querySelectorAll('.tm-card').forEach((card) => {
            card.addEventListener('click', () => toggle(card));
            card.addEventListener('keydown', (e) => {
                if (e.key === 'Enter' || e.key === ' ') { e.preventDefault(); toggle(card); }
            });
        });

        // Animasi muncul saat masuk layar
        if ('IntersectionObserver' in window) {
            sec.classList.add('tm-ready');
            const io = new IntersectionObserver((entries) => entries.forEach((en) => {
                if (en.isIntersecting) { en.target.classList.add('is-in'); io.unobserve(en.target); }
            }), { threshold: 0.15 });
            sec.querySelectorAll('.tm-reveal').forEach((el) => io.observe(el));
        }
    })();
    </script>
</body>
</html>