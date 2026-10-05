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
</body>
</html>