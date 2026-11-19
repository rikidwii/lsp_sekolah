{{-- resources/views/components/navbar.blade.php  →  dipakai dengan <x-navbar /> --}}
@php
    // Menu = anchor ke section di halaman beranda (id section harus ada, lihat catatan)
    $navLinks = [
        ['id' => 'home',        'label' => 'Homepage'],
        ['id' => 'about',       'label' => 'About'],
        ['id' => 'fitur-utama', 'label' => 'Feature'],
        ['id' => 'testimoni',   'label' => 'Testimonial'],
    ];
    $navBase = route('guest.index');
    $onHome  = request()->routeIs('guest.index');
@endphp

<style>
    html { scroll-behavior: smooth; scroll-padding-top: 5.5rem; }
    #navbar { --ease: cubic-bezier(.22, 1, .36, 1); }

    /* ---------- Menu desktop ---------- */
    #navbar .nv-links { position: relative; }
    #navbar .nv-link {
        position: relative; z-index: 1; display: inline-block; padding: .5rem 1rem; border-radius: 999px; text-align: center;
        font-size: 14px; font-weight: 500; color: #1e293b; transition: color .3s, transform .3s var(--ease);
    }
    @media (min-width: 1280px) { #navbar .nv-link { font-size: 16px; padding: .55rem 1.1rem; } }
    /* Cadangan lebar teks tebal supaya lebar link tidak berubah saat aktif */
    #navbar .nv-link::after { content: attr(data-label); display: block; height: 0; overflow: hidden; visibility: hidden; font-weight: 700; }

    /* Hover: warna hijau, naik sedikit, garis bawah memanjang dari kiri */
    #navbar .nv-link::before {
        content: ""; position: absolute; left: 1.1rem; right: 1.1rem; bottom: .32rem; height: 2px; border-radius: 2px; background: #006A69;
        transform: scaleX(0); transform-origin: left; transition: transform .35s var(--ease);
    }
    #navbar .nv-link:hover { color: #006A69; transform: translateY(-1px); }
    #navbar .nv-link:hover::before { transform: scaleX(1); }

    /* Aktif (scrollspy): teks hijau tebal + "pop", pill putih meluncur ke menu aktif */
    #navbar .nv-link[aria-current="page"] { color: #006A69; font-weight: 700; transform: none; }
    #navbar .nv-link[aria-current="page"]::before { transform: scaleX(0); }
    @keyframes nvPop { 40% { transform: scale(1.12); } }
    #navbar .nv-link.nv-pop { animation: nvPop .5s var(--ease); }

    #navbar .nv-ind {
        position: absolute; left: 0; top: 0; border-radius: 999px; background: rgba(255, 255, 255, .92); pointer-events: none; opacity: 0;
        box-shadow: 0 0 0 1.5px rgba(0, 106, 105, .55), 0 6px 16px rgba(0, 106, 105, .16);
        transition: transform .55s var(--ease), width .55s var(--ease), height .3s, opacity .3s;
    }
    #navbar .nv-ind.is-on { opacity: 1; }
    #navbar .nv-ind.no-anim { transition: none; }

    /* ---------- Tombol ---------- */
    #navbar .nv-login {
        background: #fff linear-gradient(#006A69, #006A69) no-repeat bottom / 100% 0; transition: background-size .35s var(--ease), color .35s, transform .2s;
    }
    #navbar .nv-login:hover { background-size: 100% 100%; color: #fff; }
    #navbar .nv-sign { transition: transform .3s var(--ease), box-shadow .3s, background-color .3s; }
    #navbar .nv-sign:hover { transform: translateY(-2px); box-shadow: 0 8px 18px rgba(0, 106, 105, .3); }
    #navbar .nv-login:active, #navbar .nv-sign:active { transform: scale(.96); }

    /* ---------- Menu mobile ---------- */
    #navbar .nv-m { display: block; padding: .65rem 1rem; border-radius: 1rem; font-size: 16px; font-weight: 500; color: #1e293b; transition: background-color .3s, color .3s, transform .3s var(--ease); }
    #navbar .nv-m:hover { background: rgba(255, 255, 255, .6); transform: translateX(4px); }
    #navbar .nv-m[aria-current="page"] { background: #fff; color: #006A69; font-weight: 700; }

    @media (prefers-reduced-motion: reduce) {
        html { scroll-behavior: auto; }
        #navbar * { transition-duration: .01s !important; animation-duration: .01s !important; }
    }
</style>

<header id="navbar" class="sticky top-0 z-50 bg-[#f4f7fa]/85 backdrop-blur-md">
    <div class="max-w-[1400px] mx-auto px-6 sm:px-10 lg:px-16 py-4 lg:py-5">
        <div class="flex items-center gap-2">

            {{-- Pill memanjang: brand di kiri, menu di kanan --}}
            <div class="flex-1 min-w-0 flex items-center justify-between rounded-full bg-[#dce5e7] p-1.5">
                <a href="{{ $navBase }}#home" class="group flex items-center gap-3 min-w-0 pr-4 lg:pr-6">
                    <span class="w-10 h-10 xl:w-11 xl:h-11 shrink-0 rounded-full bg-[#006A69] text-white grid place-items-center transition-transform duration-500 group-hover:-rotate-12 group-hover:scale-110">
                        <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><path d="M21.42 10.922a1 1 0 0 0-.019-1.838L12.83 5.18a2 2 0 0 0-1.66 0L2.6 9.08a1 1 0 0 0 0 1.832l8.57 3.908a2 2 0 0 0 1.66 0z"/><path d="M22 10v6"/><path d="M6 12.5V16a6 3 0 0 0 12 0v-3.5"/></svg>
                    </span>
                    <span class="max-w-[9.5rem] sm:max-w-none text-xs leading-tight sm:text-sm lg:text-base xl:text-xl 2xl:text-[22px] font-bold uppercase text-[#006A69] transition-colors group-hover:text-[#095446]">Lembaga Sertifikasi Profesi</span>
                </a>

                <nav class="hidden lg:block pr-4 xl:pr-10" aria-label="Menu utama">
                    <div data-nav-links class="nv-links flex items-center gap-1 xl:gap-3">
                        <span data-nav-ind class="nv-ind" aria-hidden="true"></span>
                        @foreach ($navLinks as $l)
                            <a data-nav-link="{{ $l['id'] }}" data-label="{{ $l['label'] }}" href="{{ $navBase }}#{{ $l['id'] }}"
                               @if ($loop->first && $onHome) aria-current="page" @endif
                               class="nv-link">{{ $l['label'] }}</a>
                        @endforeach
                    </div>
                </nav>
            </div>

            {{-- Tombol Login / SignUp (desktop) --}}
            <div class="hidden lg:flex items-center gap-2 shrink-0">
                <a href="{{ route('login') }}" class="nv-login px-5 xl:px-6 py-2 xl:py-2.5 rounded-full border border-[#006A69] text-sm xl:text-base font-medium text-slate-900">Login</a>
                <a href="{{ route('register') }}" class="nv-sign px-5 xl:px-6 py-2 xl:py-2.5 rounded-full bg-[#006A69] hover:bg-[#095446] text-sm xl:text-base font-medium text-white">SignUp</a>
            </div>

            {{-- Tombol menu (mobile) --}}
            <button id="navbar-toggle" type="button" aria-label="Menu" aria-expanded="false" aria-controls="navbar-mobile"
                    class="lg:hidden shrink-0 w-11 h-11 rounded-full bg-[#dce5e7] text-[#006A69] grid place-items-center">
                <svg data-i-open class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2" stroke-linecap="round"><path d="M4 7h16M4 12h16M4 17h16"/></svg>
                <svg data-i-close class="w-5 h-5 hidden" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2" stroke-linecap="round"><path d="M6 6l12 12M18 6 6 18"/></svg>
            </button>
        </div>

        {{-- Menu mobile --}}
        <div id="navbar-mobile" inert class="lg:hidden grid grid-rows-[0fr] data-[open]:grid-rows-[1fr] transition-[grid-template-rows] duration-300 ease-out">
            <div class="overflow-hidden">
                <div class="mt-3 rounded-3xl bg-[#dce5e7] p-3 space-y-1">
                    @foreach ($navLinks as $l)
                        <a data-nav-link="{{ $l['id'] }}" href="{{ $navBase }}#{{ $l['id'] }}"
                           @if ($loop->first && $onHome) aria-current="page" @endif
                           class="nv-m">{{ $l['label'] }}</a>
                    @endforeach
                    <div class="flex gap-3 pt-3">
                        <a href="{{ route('login') }}" class="flex-1 text-center px-4 py-2.5 rounded-full border border-[#006A69] bg-white text-sm font-medium text-slate-900">Login</a>
                        <a href="{{ route('register') }}" class="flex-1 text-center px-4 py-2.5 rounded-full bg-[#006A69] text-sm font-medium text-white">SignUp</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</header>

<script>
    (() => {
        const nav = document.getElementById('navbar');
        if (!nav) return;

        // ---- Menu mobile ----
        const toggle = nav.querySelector('#navbar-toggle');
        const panel = nav.querySelector('#navbar-mobile');
        const setOpen = (open) => {
            panel.toggleAttribute('data-open', open);
            panel.inert = !open;
            toggle.setAttribute('aria-expanded', open ? 'true' : 'false');
            toggle.querySelector('[data-i-open]').classList.toggle('hidden', open);
            toggle.querySelector('[data-i-close]').classList.toggle('hidden', !open);
        };
        toggle.addEventListener('click', () => setOpen(!panel.hasAttribute('data-open')));
        panel.querySelectorAll('a').forEach((a) => a.addEventListener('click', () => setOpen(false)));

        // ---- Menu aktif + pill yang meluncur ----
        const links = [...nav.querySelectorAll('[data-nav-link]')];
        const wrap = nav.querySelector('[data-nav-links]');
        const ind = nav.querySelector('[data-nav-ind]');
        const deskLinks = [...wrap.querySelectorAll('[data-nav-link]')];
        let lastId = links.find((a) => a.hasAttribute('aria-current'))?.dataset.navLink ?? null;
        let lockUntil = 0;

        function moveInd(animate = true) {
            const a = deskLinks.find((l) => l.hasAttribute('aria-current'));
            if (!a || wrap.offsetParent === null) { ind.classList.remove('is-on'); return; }
            if (!animate) ind.classList.add('no-anim');
            ind.style.width = a.offsetWidth + 'px';
            ind.style.height = a.offsetHeight + 'px';
            ind.style.transform = `translate(${a.offsetLeft}px, ${a.offsetTop}px)`;
            ind.classList.add('is-on');
            if (!animate) { void ind.offsetWidth; ind.classList.remove('no-anim'); }
        }

        function setActive(id) {
            links.forEach((a) => {
                if (a.dataset.navLink === id) a.setAttribute('aria-current', 'page');
                else a.removeAttribute('aria-current');
            });
            const a = deskLinks.find((l) => l.dataset.navLink === id);
            if (a && id !== lastId) {          // animasi "pop" hanya saat berpindah menu
                a.classList.remove('nv-pop');
                void a.offsetWidth;
                a.classList.add('nv-pop');
            }
            lastId = id;
            moveInd(true);
        }
        deskLinks.forEach((a) => a.addEventListener('animationend', () => a.classList.remove('nv-pop')));

                // Klik menu di halaman yang sama: langsung aktifkan, tahan scrollspy selama gulir halus
        let clicking = false;
        links.forEach((a) => a.addEventListener('click', () => {
            if (new URL(a.href).pathname === location.pathname) {
                clicking = true;
                lockUntil = Date.now() + 250;
                setActive(a.dataset.navLink);
            }
        }));

        // Scrollspy berbasis posisi.
        // Section dicari SAAT scroll, bukan saat script dimuat, karena script ini
        // ada di atas <body> dan section di bawahnya belum ada saat script berjalan.
        const spyIds = [...new Set(links.map((a) => a.dataset.navLink))];
        let ticking = false, tail;

        function spy() {
            ticking = false;
            if (Date.now() < lockUntil) return;
            const secs = spyIds.map((id) => document.getElementById(id)).filter(Boolean);
            if (!secs.length) return;
            const probe = window.innerHeight * 0.35;          // garis acuan: 35% dari atas layar
            let id = secs[0].id, best = -Infinity;
            secs.forEach((s) => {                              // section terdekat yang sudah melewati garis acuan
                const top = s.getBoundingClientRect().top;
                if (top <= probe && top > best) { best = top; id = s.id; }
            });
            const atBottom = window.innerHeight + window.scrollY >= document.documentElement.scrollHeight - 4;
            if (atBottom) id = secs[secs.length - 1].id;      // mentok di bawah = menu terakhir
            if (id !== lastId) setActive(id);
        }

        const onScroll = () => {
            if (clicking) lockUntil = Date.now() + 150;       // selama gulir halus dari klik menu
            if (!ticking) { ticking = true; requestAnimationFrame(spy); }
            clearTimeout(tail);
            tail = setTimeout(() => { clicking = false; spy(); }, 200);
        };
        document.addEventListener('scroll', onScroll, { capture: true, passive: true });
        window.addEventListener('resize', onScroll);

        document.addEventListener('DOMContentLoaded', () => {
            const missing = spyIds.filter((id) => !document.getElementById(id));
            if (missing.length) console.warn('[navbar] Section tidak ditemukan untuk id: ' + missing.join(', '));
            spy();
        });
        window.addEventListener('load', spy);                 // hitung ulang setelah gambar selesai dimuat

        // Posisi awal tanpa animasi + ukur ulang saat font dimuat / layar berubah
        moveInd(false);
        document.fonts?.ready.then(() => moveInd(false));
        window.addEventListener('resize', () => {
            if (window.innerWidth >= 1024) setOpen(false);
            moveInd(false);
        });
    })();
</script>