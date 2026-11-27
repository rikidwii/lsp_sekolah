{{-- resources/views/asesi/skema/transkrip.blade.php  (FILE BARU)
     Transkrip hasil asesmen (format FR.IA.05 / FR.AK-02). Dipakai sebagai PDF (dompdf) atau preview HTML.
     Catatan: memakai <table> dan CSS sederhana agar kompatibel dengan dompdf (tanpa flex/grid).

     KONTRAK DATA: $hasil (array) atau null -> bila null dipakai DATA DUMMY di bawah.
       asesi{nama,nis,kelas}, skema{nama,kode,no_registrasi,jenis}, asesor, tanggal,
       teori{skor,passing}, praktik{skor,passing}, rekomendasi (K|BK),
       units[]{kode,judul,standar, kuk[]{no,judul,kriteria,hasil(K|BK),catatan,bukti}}
--}}
@php
    // ===================== DATA DUMMY =====================
    $hasil ??= [
        'asesi'  => ['nama' => 'Budi Santoso', 'nis' => '202100123 / 0034567890', 'kelas' => 'Rekayasa Perangkat Lunak - XII RPL 1'],
        'skema'  => ['nama' => 'Pemrograman Berorientasi Objek (OOP Dasar)', 'kode' => 'RPL-002', 'no_registrasi' => 'REG-BNSP-2024-8831', 'jenis' => 'Klaster'],
        'asesor' => 'Dr. Ir. Budi Santoso', 'tanggal' => '15 Mei 2024',
        'teori'  => ['skor' => 88, 'passing' => 75],
        'praktik' => ['skor' => 84, 'passing' => 80],
        'rekomendasi' => 'K',
        'units' => [[
            'kode' => 'J.620100.009.01', 'judul' => 'Menggunakan Spesifikasi Program', 'standar' => 'Standar SKKNI No. 282 / Kominfo',
            'kuk' => [
                ['no' => '1.1', 'judul' => 'Metode pengembangan perangkat lunak ditentukan',
                 'kriteria' => 'Memeriksa apakah kandidat dapat menentukan metode pengembangan perangkat lunak yang tepat (seperti Agile/Scrum, Waterfall, atau RAD) berdasarkan studi kasus proyek perangkat lunak yang diberikan.',
                 'hasil' => 'K', 'catatan' => 'Asesi mampu menguraikan tahapan sprint dan backlog dengan tepat.', 'bukti' => null],
                ['no' => '1.2', 'judul' => 'Kebutuhan perangkat lunak diidentifikasi',
                 'kriteria' => 'Mampu mengidentifikasi kebutuhan fungsional dan non-fungsional dari spesifikasi sistem yang diberikan oleh studi kasus industri dengan akurat.',
                 'hasil' => 'K', 'catatan' => 'Kandidat mampu menjelaskan dengan sangat baik perbedaan antara kebutuhan fungsional dan non-fungsional, serta menyertakan use case diagram yang sesuai.', 'bukti' => 'analisis_kebutuhan_program.pdf'],
            ],
        ]],
    ];
    // =======================================================

    $h = $hasil;
    $lulus = fn ($n) => $n['skor'] >= $n['passing'];
    $totalUnit = count($h['units']);
    $dicetak = now()->locale('id')->translatedFormat('d F Y, H:i');
@endphp
<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="utf-8">
<title>Transkrip Nilai - {{ $h['skema']['kode'] }}</title>
<style>
    @page { margin: 30px 34px; }
    * { box-sizing: border-box; }
    body { font-family: 'DejaVu Sans', Helvetica, Arial, sans-serif; font-size: 10px; color: #1e293b; margin: 0; }
    table { width: 100%; border-collapse: collapse; }
    td, th { vertical-align: top; }
    .muted { color: #64748b; }
    .green { color: #0b5a49; }
    h1 { font-size: 17px; margin: 0; color: #0b5a49; }
    h2 { font-size: 11px; margin: 0 0 6px; text-transform: uppercase; letter-spacing: .4px; color: #0f172a; }
    .sec { margin-top: 16px; }
    .line { border-bottom: 2px solid #0b5a49; padding-bottom: 8px; }
    .idtbl td { padding: 3px 0; font-size: 10px; }
    .idtbl td.k { width: 110px; color: #64748b; }
    .box { border: 1px solid #cbd5e1; padding: 9px 11px; }
    .box.t { background: #eff6ff; border-color: #bfdbfe; }
    .box.p { background: #ecfdf5; border-color: #a7f3d0; }
    .score { font-size: 24px; font-weight: bold; }
    .pill { display: inline-block; padding: 2px 7px; font-size: 8.5px; font-weight: bold; }
    .pill.ok { background: #d1fae5; color: #065f46; }
    .pill.no { background: #fee2e2; color: #991b1b; }
    .unit { background: #0b5a49; color: #fff; padding: 9px 12px; }
    .unit .small { font-size: 8.5px; color: #a7f3d0; }
    .kuk th { background: #e2e8f0; font-size: 8.5px; text-transform: uppercase; text-align: left; padding: 6px 7px; border: 1px solid #cbd5e1; }
    .kuk td { padding: 7px; border: 1px solid #e2e8f0; font-size: 9.5px; }
    .kuk tr { page-break-inside: avoid; }
    .rek { border: 1.5px solid #0b5a49; background: #ecfdf5; padding: 10px 12px; }
    .rek.bk { border-color: #dc2626; background: #fef2f2; }
    .sign td { width: 50%; padding-top: 4px; }
    .foot { margin-top: 18px; padding-top: 6px; border-top: 1px solid #e2e8f0; font-size: 8px; color: #94a3b8; text-align: center; }
</style>
</head>
<body>

    {{-- Kop --}}
    <table class="line">
        <tr>
            <td>
                <h1>TRANSKRIP HASIL ASESMEN KOMPETENSI</h1>
                <div class="muted" style="margin-top:3px">Rekapitulasi penilaian asesor (FR.IA.05, Observasi &amp; Form FR.AK-02)</div>
            </td>
            <td style="text-align:right; width:170px">
                <div class="muted" style="font-size:8.5px">NO. REGISTRASI</div>
                <div style="font-size:12px; font-weight:bold">{{ $h['skema']['no_registrasi'] }}</div>
            </td>
        </tr>
    </table>

    {{-- Identitas --}}
    <table class="idtbl" style="margin-top:10px">
        <tr>
            <td style="width:50%">
                <table class="idtbl">
                    <tr><td class="k">Nama Asesi</td><td><b>{{ $h['asesi']['nama'] }}</b></td></tr>
                    <tr><td class="k">NIS / NISN</td><td>{{ $h['asesi']['nis'] }}</td></tr>
                    <tr><td class="k">Jurusan / Kelas</td><td>{{ $h['asesi']['kelas'] }}</td></tr>
                </table>
            </td>
            <td style="width:50%">
                <table class="idtbl">
                    <tr><td class="k">Skema</td><td><b>{{ $h['skema']['nama'] }}</b></td></tr>
                    <tr><td class="k">Kode / Jenis</td><td>{{ $h['skema']['kode'] }} &middot; {{ $h['skema']['jenis'] }}</td></tr>
                    <tr><td class="k">Asesor</td><td>{{ $h['asesor'] }}</td></tr>
                    <tr><td class="k">Tanggal Asesmen</td><td>{{ $h['tanggal'] }}</td></tr>
                </table>
            </td>
        </tr>
    </table>

    {{-- Skor manual --}}
    <div class="sec">
        <h2>Input Skor Asesmen Manual (FR.IA.05 &amp; Observasi)</h2>
        <table>
            <tr>
                <td style="width:50%; padding-right:5px">
                    <div class="box t">
                        <div style="font-weight:bold; font-size:9px">SKOR UJIAN TEORI / TERTULIS</div>
                        <div class="score" style="color:#1d4ed8; margin-top:4px">{{ $h['teori']['skor'] }} <span class="muted" style="font-size:10px; font-weight:normal">/ 100 Poin</span></div>
                        <div class="muted" style="margin-top:3px">Passing Grade: {{ $h['teori']['passing'] }} &nbsp;
                            <span class="pill {{ $lulus($h['teori']) ? 'ok' : 'no' }}">{{ $lulus($h['teori']) ? 'LULUS' : 'BELUM LULUS' }}</span></div>
                    </div>
                </td>
                <td style="width:50%; padding-left:5px">
                    <div class="box p">
                        <div style="font-weight:bold; font-size:9px">SKOR UJIAN PRAKTIK / OBSERVASI DEMONSTRASI</div>
                        <div class="score green" style="margin-top:4px">{{ $h['praktik']['skor'] }} <span class="muted" style="font-size:10px; font-weight:normal">/ 100 Poin</span></div>
                        <div class="muted" style="margin-top:3px">Passing Grade: {{ $h['praktik']['passing'] }} &nbsp;
                            <span class="pill {{ $lulus($h['praktik']) ? 'ok' : 'no' }}">{{ $lulus($h['praktik']) ? 'LULUS' : 'BELUM LULUS' }}</span></div>
                    </div>
                </td>
            </tr>
        </table>
    </div>

    {{-- Per unit kompetensi --}}
    @foreach ($h['units'] as $u)
        <div class="sec">
            <table class="unit">
                <tr>
                    <td>
                        <div class="small">UNIT KOMPETENSI KE-{{ $loop->iteration }} DARI {{ $totalUnit }} &nbsp;|&nbsp; {{ $u['standar'] }}</div>
                        <div style="font-size:12px; font-weight:bold; margin-top:3px">Kode Unit: {{ $u['kode'] }}</div>
                        <div style="margin-top:2px">Judul Unit: {{ $u['judul'] }}</div>
                    </td>
                    <td style="text-align:right; width:150px; font-size:8.5px; color:#a7f3d0">Total Kriteria Unjuk Kerja: {{ count($u['kuk']) }} Elemen</td>
                </tr>
            </table>
            <table class="kuk">
                <thead>
                    <tr>
                        <th style="width:28px">No</th>
                        <th>Kriteria Unjuk Kerja (KUK)</th>
                        <th style="width:46px">Hasil</th>
                        <th style="width:130px">Catatan Asesor</th>
                        <th style="width:90px">Bukti Fisik</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($u['kuk'] as $k)
                        <tr>
                            <td><b>{{ $k['no'] }}</b></td>
                            <td><b>{{ $k['judul'] }}</b><br><span class="muted">{{ $k['kriteria'] }}</span></td>
                            <td><span class="pill {{ $k['hasil'] === 'K' ? 'ok' : 'no' }}">{{ $k['hasil'] === 'K' ? 'K' : 'BK' }}</span></td>
                            <td>{{ $k['catatan'] ?: '-' }}</td>
                            <td>{{ $k['bukti'] ?: '-' }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    @endforeach

    {{-- Rekomendasi --}}
    <div class="sec" style="page-break-inside: avoid">
        <h2>Rekomendasi Keputusan Asesor (Form FR.AK-02)</h2>
        <div class="rek {{ $h['rekomendasi'] === 'K' ? '' : 'bk' }}">
            @if ($h['rekomendasi'] === 'K')
                <b style="font-size:12px" class="green">Kompeten (K)</b>
                <div style="margin-top:3px">Asesi telah memenuhi seluruh kriteria unjuk kerja unit kompetensi yang dipersyaratkan.</div>
            @else
                <b style="font-size:12px; color:#991b1b">Belum Kompeten (BK)</b>
                <div style="margin-top:3px">Terdapat satu atau lebih elemen KUK yang belum memenuhi standar unjuk kerja.</div>
            @endif
        </div>

        <table class="sign" style="margin-top:22px">
            <tr>
                <td></td>
                <td style="text-align:center">
                    Asesor Kompetensi,<br><br><br><br>
                    <b style="border-top:1px solid #334155; padding-top:3px; display:inline-block; min-width:150px">{{ $h['asesor'] }}</b>
                </td>
            </tr>
        </table>
    </div>

    <div class="foot">Dokumen ini dibuat otomatis oleh sistem pada {{ $dicetak }} berdasarkan hasil penilaian asesor.</div>
</body>
</html>