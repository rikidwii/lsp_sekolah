<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ $title ?? 'Lembaga Sertifikasi' }}</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-slate-50 font-sans antialiased text-slate-800">
    <div class="min-h-screen flex flex-col lg:flex-row">
        <aside class="lg:w-[42%] bg-gradient-to-br from-emerald-950 via-emerald-900 to-emerald-800 text-white p-8 sm:p-12 flex flex-col justify-between">
            {{ $leftPanel }}
        </aside>
        <main class="flex-1 flex items-center justify-center p-6 sm:p-12">
            <div class="w-full max-w-xl">
                {{ $slot }}
            </div>
        </main>
    </div>

    <script>
        document.querySelectorAll('.toggle-password').forEach((btn) => {
            btn.addEventListener('click', () => {
                const input = document.getElementById(btn.dataset.target);
                if (!input) return;

                const isHidden = input.type === 'password';
                input.type = isHidden ? 'text' : 'password';
                btn.innerHTML = isHidden
                    ? `<svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8"><path stroke-linecap="round" stroke-linejoin="round" d="M3 3l18 18M10.6 10.6a3 3 0 004.2 4.2M9.4 5.3A10.4 10.4 0 0112 5c7 0 11 7 11 7a17.2 17.2 0 01-3.1 3.9M6.1 6.1C3.4 7.9 1 12 1 12a17.4 17.4 0 004.4 5.2A10.4 10.4 0 0012 19c1 0 2-.1 2.9-.4" /></svg>`
                    : `<svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8"><path stroke-linecap="round" stroke-linejoin="round" d="M1 12s4-7 11-7 11 7 11 7-4 7-11 7-11-7-11-7zM12 15a3 3 0 100-6 3 3 0 000 6z" /></svg>`;
            });
        });
    </script>
</body>
</html>