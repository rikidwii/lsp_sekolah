<x-layouts.app :title="'Dashboard'">

    <div class="space-y-6">

        {{-- 1. Welcome Card --}}
        @include('asesi.dashboard.components.welcome')

        {{-- 2. Statistik --}}
        @include('asesi.dashboard.components.statistics')

        {{-- 3. Progress Sertifikasi --}}
        @include('asesi.dashboard.components.progress')

        {{-- 4. Jadwal dan Notifikasi --}}
        <div class="grid grid-cols-1 xl:grid-cols-[1.35fr_0.95fr] gap-6">

            @include('asesi.dashboard.components.schedule')

            @include('asesi.dashboard.components.notifications')

        </div>

    </div>

</x-layouts.app>