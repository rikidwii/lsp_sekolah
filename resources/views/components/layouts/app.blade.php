<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ $title ?? 'Dashboard' }} — Lembaga Sertifikasi</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-slate-50 font-sans antialiased text-slate-800">
    <div class="lg:pl-72">
        <x-sidebar />

        <header class="sticky top-0 z-20 bg-white border-b border-slate-100 h-16 flex items-center justify-between px-4 sm:px-8">
            <button id="sidebar-toggle" class="lg:hidden text-slate-700">
                <x-icon name="menu" />
            </button>
            <h1 class="font-semibold text-slate-800">{{ $title ?? 'Dashboard' }}</h1>
            <div></div>
        </header>

        <main class="p-4 sm:p-8">
            {{ $slot }}
        </main>
    </div>

    <script>
        const sidebar = document.getElementById('app-sidebar');
        const backdrop = document.getElementById('sidebar-backdrop');
        document.getElementById('sidebar-toggle')?.addEventListener('click', () => {
            sidebar.classList.toggle('-translate-x-full');
            backdrop.classList.toggle('hidden');
        });
        backdrop?.addEventListener('click', () => {
            sidebar.classList.add('-translate-x-full');
            backdrop.classList.add('hidden');
        });
    </script>
</body>
</html>