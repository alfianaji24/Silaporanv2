<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Presensi Karyawan - {{ $karyawan['nama_karyawan'] }} - {{ date('Y-m-d H:i:s') }}</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        @page {
            size: A4 landscape;
            margin: 4cm 2.5cm 2.5cm 2.5cm; /* top right bottom left */
        }
        body {
            font-family: Arial, sans-serif;
            font-size: 11px;
            line-height: 1.4;
            margin: 0;
            padding: 20px;
        }
        .header {
            margin-bottom: 10px;
            border-bottom: 2px solid #000;
            padding-bottom: 0px;
        }
        .header table {
            width: 100%;
        }
        .header img {
            max-width: 70px;
            height: auto;
            padding: 5px;
        }
        .header h4 {
            margin: 0;
            line-height: 1.2;
            font-size: 14px;
            padding: 2px 0;
        }
        .header span {
            margin: 0;
            padding: 0;
            display: block; /* Ensure span takes full width */
        }
        .header table td {
            padding: 1px 0;
        }
        .info-karyawan {
            margin-bottom: 5px;
            padding: 12px;
            background-color: #f8f9fa;
            border: 1px solid #dee2e6;
            border-radius: 4px;
        }
        .info-karyawan table {
            width: 100%;
            margin-bottom: 0;
        }
        .info-karyawan td {
            padding: 3px 12px;
        }
        .info-karyawan td:first-child {
            width: 120px;
            font-weight: bold;
        }
        .datatable3 {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 5px;
            font-size: 10px;
            page-break-inside: auto;
        }
        .datatable3 th, .datatable3 td {
            border: 1px solid #000;
            padding: 2px 3px;
            text-align: center;
            vertical-align: middle;
        }
        .datatable3 .no-column {
            width: 25px;
        }
        .datatable3 .tanggal-column {
            width: 100px;
            text-align: left;
            padding-left: 12px;
        }
        .datatable3 .status-column {
            width: 70px;
            padding: 6px 4px;
        }
        .datatable3 .jam-column {
            width: 80px;
            padding: 6px 4px;
        }
        .datatable3 .keterangan-column {
            width: 80px;
            padding: 6px 8px;
        }
        .datatable3 tfoot td {
            font-weight: bold;
            background-color: #f0f0f0;
            padding: 8px;
        }
        .footer {
            text-align: center;
            font-size: 9px;
            padding: 10px 0;
            border-top: 1px solid #000;
            margin-top: 20px;
        }
        .content {
            padding: 10px 0;
        }

        /* Print styles */
        @media print {
            @page {
                size: A4 landscape !important; /* Pastikan orientasi landscape dengan !important */
                margin: 0.8cm 0.8cm 0.8cm 0.8cm;
            }
            html, body {
                width: 100%; /* Kembali ke 100% agar browser mengatur dimensi berdasarkan size landscape */
                height: 100%;
                margin: 0;
                padding: 0;
            }
            body {
                -webkit-print-color-adjust: exact;
                print-color-adjust: exact;
                padding: 0;
                margin: 0;
                font-size: 8px !important;
                transform: rotate(0deg) !important; /* Memastikan tidak ada rotasi dengan !important */
            }
            .header {
                margin: 0 5px 2px 5px;
            }
            .header h4 {
                font-size: 11px !important;
                line-height: 1.1 !important;
                padding: 1px 0 !important;
            }
            .header span {
                font-size: 8px !important;
                line-height: 1.1 !important;
            }
            .content {
                padding: 0;
                margin: 0 5px;
                padding-top: 1mm;
            }
            .info-karyawan {
                margin: 0 5px 0 5px;
                font-size: 8px !important;
            }
            .info-karyawan td {
                padding: 1px 2px !important;
                line-height: 1.1 !important;
            }
            .datatable3 {
                margin-bottom: 2px;
                font-size: 8px !important;
                width: 100% !important;
                table-layout: fixed !important;
            }
            .datatable3 th, .datatable3 td {
                padding: 1px !important;
                line-height: 1.1 !important;
                font-size: 8px !important;
                white-space: nowrap !important;
                overflow: hidden !important;
                text-overflow: ellipsis !important;
            }
            .datatable3 .no-column {
                width: 18px !important;
            }
            .datatable3 .tanggal-column {
                width: 85px !important;
            }
            .datatable3 .status-column {
                width: 35px !important;
            }
            .datatable3 .jam-column {
                width: 45px !important;
            }
            .datatable3 .keterangan-column {
                width: 55px !important;
            }
            /* Kolom Denda dan Pot. Jam */
            .datatable3 th:last-child,
            .datatable3 td:last-child,
            .datatable3 th:nth-last-child(2),
            .datatable3 td:nth-last-child(2) {
                width: 65px !important;
            }
            .datatable3 tfoot td {
                padding: 1px !important;
                line-height: 1.1 !important;
                font-size: 8px !important;
            }
            .footer {
                margin: 0 5px;
                padding: 2px 0;
                page-break-before: avoid;
                line-height: 1.1;
                font-size: 7px !important;
            }
            .no-print {
                display: none !important;
            }
            .datatable3 {
                page-break-inside: auto;
            }
            tr {
                page-break-inside: avoid;
                page-break-after: auto;
            }
            thead {
                display: table-header-group;
            }
            tfoot {
                display: table-footer-group;
            }
            .page-break {
                page-break-before: always;
            }
        }

        /* Download button styles */
        .download-btn {
            position: fixed;
            top: 20px;
            right: 20px;
            z-index: 1000;
        }
    </style>
</head>
<body>
    <!-- Download Button -->
    <div class="download-btn no-print">
        <button onclick="window.print()" class="btn btn-primary">
            <i class="fas fa-download"></i> Download PDF
        </button>
    </div>

    <div class="header">
        <table>
            <tr>
                <td style="width: 100px">
                    @if ($generalsetting->logo && Storage::exists('public/logo/' . $generalsetting->logo))
                        <img src="{{ asset('storage/logo/' . $generalsetting->logo) }}" alt="Logo Perusahaan">
                    @else
                        <img src="https://placehold.co/100x100?text=Logo" alt="Logo Default">
                    @endif
                </td>
                <td>
                    <h4>
                        LAPORAN PRESENSI KARYAWAN
                        <br>
                        {{ $generalsetting->nama_perusahaan }}
                        <br>
                        PUSKESMAS BALARAJA
                        <br>
                        PERIODE {{ date('d-m-Y', strtotime($periode_dari)) }} - {{ date('d-m-Y', strtotime($periode_sampai)) }}
                    </h4>
                    <span style="font-style: italic;">{{ $generalsetting->alamat }} | {{ $generalsetting->telepon }}</span>
                </td>
            </tr>
        </table>
    </div>

    <div class="info-karyawan">
        <table>
            <tr>
                <td>NIK</td>
                <td>: {{ $karyawan['nik'] }}</td>
                <td>Jabatan</td>
                <td>: {{ $karyawan['nama_jabatan'] }}</td>
            </tr>
            <tr>
                <td>Nama Karyawan</td>
                <td>: {{ $karyawan['nama_karyawan'] }}</td>
                <td>Total Denda</td>
                <td>: {{ formatAngka($total_denda) }}</td>
            </tr>
        </table>
    </div>

    <div class="content">
        <table class="datatable3">
            <thead>
                <tr>
                    <th rowspan="2" class="no-column">NO</th>
                    <th rowspan="2">Tanggal</th>
                    <th colspan="2">Jam Kerja</th>
                    <th colspan="2">Presensi</th>
                    <th colspan="6">Keterangan</th>
                    <th rowspan="2">Denda</th>
                    <th rowspan="2">Pot. Jam</th>
                </tr>
                <tr>
                    <th>Masuk</th>
                    <th>Pulang</th>
                    <th>Masuk</th>
                    <th>Pulang</th>
                    <th>Keterlambatan</th>
                    <th>Pulang Cepat</th>
                    <th>Status</th>
                    <th>Izin Absen</th>
                    <th>Sakit</th>
                    <th>Cuti</th>
                </tr>
            </thead>
            <tbody>
                @php
                    $tanggal_presensi = $periode_dari;
                    $total_denda = 0;
                    $total_potongan_jam = 0;
                    $total_keterlambatan_menit = 0;
                    $total_pulang_cepat_menit = 0;
                    $total_izin_absen = 0;
                    $total_sakit = 0;
                    $total_cuti = 0;
                    $total_tidak_hadir = 0;
                    $row_count = 0;
                    $nama_bulan = [
                        1 => 'Januari', 2 => 'Februari', 3 => 'Maret', 4 => 'April', 5 => 'Mei', 6 => 'Juni',
                        7 => 'Juli', 8 => 'Agustus', 9 => 'September', 10 => 'Oktober', 11 => 'November', 12 => 'Desember'
                    ];
                @endphp
                @while (strtotime($tanggal_presensi) <= strtotime($periode_sampai))
                    @php
                        $row_count++;
                        $denda = 0;
                        $potongan_jam_harian = 0;
                        $keterlambatan_menit_harian = 0;
                        $pulang_cepat_menit_harian = 0;

                        $search = [
                            'nik' => $karyawan['nik'],
                            'tanggal' => $tanggal_presensi,
                        ];
                        $ceklibur = ceklibur($datalibur, $search);
                        $status = '';
                        $keterangan = '';
                        $jam_masuk_jadwal = '';
                        $jam_pulang_jadwal = '';
                        $jam_in = '';
                        $jam_out = '';
                        $terlambat_display = '-';
                        $pulang_cepat_display = '-';
                    @endphp

                    @if (isset($karyawan[$tanggal_presensi]))
                        @if ($karyawan[$tanggal_presensi]['status'] == 'h')
                            @php
                                $status = '';
                                $jam_masuk_jadwal = date('H:i', strtotime($karyawan[$tanggal_presensi]['jam_masuk']));
                                $jam_pulang_jadwal = date('H:i', strtotime($karyawan[$tanggal_presensi]['jam_pulang']));
                                $jam_in = !empty($karyawan[$tanggal_presensi]['jam_in'])
                                    ? date('H:i', strtotime($karyawan[$tanggal_presensi]['jam_in']))
                                    : '-';
                                $jam_out = !empty($karyawan[$tanggal_presensi]['jam_out'])
                                    ? date('H:i', strtotime($karyawan[$tanggal_presensi]['jam_out']))
                                    : '-';

                                $jam_masuk_full = $tanggal_presensi . ' ' . $karyawan[$tanggal_presensi]['jam_masuk'];

                                if (!empty($karyawan[$tanggal_presensi]['jam_in']) && strtotime($karyawan[$tanggal_presensi]['jam_in']) > strtotime($jam_masuk_full)) {
                                    $terlambat_data = hitungjamterlambat($karyawan[$tanggal_presensi]['jam_in'], $jam_masuk_full);
                                    if ($terlambat_data != null) {
                                        $terlambat_display = $terlambat_data['show_laporan'];
                                        // Logika untuk menentukan denda dan potongan jam dari keterlambatan
                                        if ($terlambat_data['desimal_terlambat'] < 1) {
                                            $denda += hitungdenda($denda_list, $terlambat_data['menitterlambat']);
                                            // Potongan jam harian tetap 0 jika keterlambatan kurang dari 1 jam
                                        } else {
                                            // Tidak ada denda jika keterlambatan 1 jam atau lebih
                                            $potongan_jam_harian += $terlambat_data['desimal_terlambat']; // Tambahkan potongan jam jika >= 1 jam
                                        }
                                        $keterlambatan_menit_harian = $terlambat_data['menitterlambat'];
                                    }
                                }

                                if (!empty($karyawan[$tanggal_presensi]['jam_out']) && strtotime($karyawan[$tanggal_presensi]['jam_out']) < strtotime($tanggal_presensi . ' ' . $karyawan[$tanggal_presensi]['jam_pulang'])) {
                                    $pulang_cepat_data = hitungpulangcepat(
                                        $tanggal_presensi,
                                        $karyawan[$tanggal_presensi]['jam_out'],
                                        $karyawan[$tanggal_presensi]['jam_pulang'],
                                        $karyawan[$tanggal_presensi]['istirahat'],
                                        $karyawan[$tanggal_presensi]['jam_awal_istirahat'],
                                        $karyawan[$tanggal_presensi]['jam_akhir_istirahat'],
                                        $karyawan[$tanggal_presensi]['lintashari']
                                    );
                                    if ($pulang_cepat_data != null) {
                                        $pulang_cepat_display = $pulang_cepat_data['show_laporan'];
                                        $pulang_cepat_menit_harian = $pulang_cepat_data['menit_pulang_cepat'];
                                        $potongan_jam_harian += $pulang_cepat_data['potongan_jam'];
                                    }
                                }
                            @endphp
                        @elseif($karyawan[$tanggal_presensi]['status'] == 'i')
                            @php
                                $status = 'V';
                                $keterangan = $karyawan[$tanggal_presensi]['keterangan_izin_absen'];
                                $jam_masuk_jadwal = '-';
                                $jam_pulang_jadwal = '-';
                                $jam_in = '-';
                                $jam_out = '-';
                            @endphp
                        @elseif($karyawan[$tanggal_presensi]['status'] == 's')
                            @php
                                $status = 'V';
                                $keterangan = $karyawan[$tanggal_presensi]['keterangan_izin_sakit'];
                                $jam_masuk_jadwal = '-';
                                $jam_pulang_jadwal = '-';
                                $jam_in = '-';
                                $jam_out = '-';
                            @endphp
                        @elseif($karyawan[$tanggal_presensi]['status'] == 'c')
                            @php
                                $status = 'V';
                                $keterangan = $karyawan[$tanggal_presensi]['keterangan_izin_cuti'];
                                $jam_masuk_jadwal = '-';
                                $jam_pulang_jadwal = '-';
                                $jam_in = '-';
                                $jam_out = '-';
                            @endphp
                        @endif
                    @else
                        @php
                            $status = 'V';
                            $jam_masuk_jadwal = '-';
                            $jam_pulang_jadwal = '-';
                            $jam_in = '-';
                            $jam_out = '-';
                            if (!empty($ceklibur)) {
                                $status = 'L';
                                $keterangan = $ceklibur[0]['keterangan'];
                            }
                        @endphp
                    @endif

                    @php
                        // Increment counters based on status
                        if (isset($karyawan[$tanggal_presensi])) {
                            if ($karyawan[$tanggal_presensi]['status'] == 'i') {
                                $total_izin_absen++;
                            } elseif ($karyawan[$tanggal_presensi]['status'] == 's') {
                                $total_sakit++;
                            } elseif ($karyawan[$tanggal_presensi]['status'] == 'c') {
                                $total_cuti++;
                            }
                        } else {
                            // Jika tidak ada data (tidak hadir), hitung sebagai V
                            if (!empty($ceklibur)) {
                                // Jika libur, tidak dihitung
                            } else {
                                $total_tidak_hadir++;
                            }
                        }

                        $total_denda += $denda;
                        $total_potongan_jam += $potongan_jam_harian;
                        $total_keterlambatan_menit += $keterlambatan_menit_harian;
                        $total_pulang_cepat_menit += $pulang_cepat_menit_harian;
                    @endphp

                    <tr style="{{ strtolower($status) == 'v' ? 'background-color: #d3d3d3 !important; color: black !important;' : '' }}">
                         <td class="no-column">{{ $row_count }}</td>
                        <td class="tanggal-column">
                            @php
                                $hari = getHari($tanggal_presensi);
                                $tanggal = date('d', strtotime($tanggal_presensi));
                                $bulan = $nama_bulan[date('n', strtotime($tanggal_presensi))];
                                $tahun = date('Y', strtotime($tanggal_presensi));
                            @endphp
                            {{ $hari }}, {{ $tanggal }} {{ $bulan }} {{ $tahun }}
                        </td>
                        <td class="jam-column">{{ $jam_masuk_jadwal }}</td>
                        <td class="jam-column">{{ $jam_pulang_jadwal }}</td>
                        <td class="jam-column">{{ $jam_in }}</td>
                        <td class="jam-column">{{ $jam_out }}</td>
                        <td class="keterangan-column">{{ $terlambat_display }}</td>
                        <td class="keterangan-column">{{ $pulang_cepat_display }}</td>
                        <td class="status-column">{{ $status }}</td>
                        <td class="keterangan-column">{{ $status == 'Izin' ? $keterangan : '-' }}</td>
                        <td class="keterangan-column">{{ $status == 'Sakit' ? $keterangan : '-' }}</td>
                        <td class="keterangan-column">{{ $status == 'Cuti' ? $keterangan : '-' }}</td>
                        <td class="jam-column">{{ $denda > 0 ? formatAngka($denda) : '-' }}</td>
                        <td class="jam-column">
                            @php
                                // Convert decimal hours to hours and minutes for display
                                $harian_hours = floor($potongan_jam_harian);
                                $harian_minutes = round(($potongan_jam_harian - $harian_hours) * 60);

                                // Handle case where minutes round up to 60
                                if ($harian_minutes >= 60) {
                                    $harian_hours += 1;
                                    $harian_minutes = 0;
                                }
                                $potongan_jam_display = '-';
                                if ($potongan_jam_harian > 0) {
                                    $potongan_jam_display = $harian_hours . ' Jam ' . $harian_minutes . ' Menit';
                                }
                            @endphp
                            {{ $potongan_jam_display }}
                        </td>
                    </tr>

                    @php
                        $tanggal_presensi = date('Y-m-d', strtotime('+1 day', strtotime($tanggal_presensi)));
                    @endphp
                @endwhile
            </tbody>
            <tfoot>
                <tr>
                    <td colspan="6" style="text-align: right;">Jumlah:</td>
                    <td>
                        @php
                            $hours_k = floor($total_keterlambatan_menit / 60);
                            $minutes_k = $total_keterlambatan_menit % 60;
                            $minutes_k_display = $minutes_k < 10 ? '0' . $minutes_k : $minutes_k;
                        @endphp
                        {{ $total_keterlambatan_menit > 0 ? $hours_k . ' Jam ' . $minutes_k_display . ' Menit' : '-' }}
                    </td>
                    <td>
                        @php
                            $hours_pc = floor($total_pulang_cepat_menit / 60);
                            $minutes_pc = $total_pulang_cepat_menit % 60;
                            $minutes_pc_display = $minutes_pc < 10 ? '0' . $minutes_pc : $minutes_pc;
                        @endphp
                        {{ $total_pulang_cepat_menit > 0 ? $hours_pc . ' Jam ' . $minutes_pc_display . ' Menit' : '-' }}
                    </td>
                    <td>{{ isset($total_tidak_hadir) && $total_tidak_hadir > 0 ? $total_tidak_hadir : '-' }}</td>
                    <td>{{ $total_izin_absen > 0 ? $total_izin_absen : '-' }}</td>
                    <td>{{ $total_sakit > 0 ? $total_sakit : '-' }}</td>
                    <td>{{ $total_cuti > 0 ? $total_cuti : '-' }}</td>
                    <td class="jam-column">{{ $total_denda > 0 ? 'Rp ' . number_format($total_denda, 0, ',', '.') : '-' }}</td>
                    <td class="jam-column">
                        @php
                            // Convert decimal hours to hours and minutes
                            $total_hours = floor($total_potongan_jam);
                            $total_minutes = round(($total_potongan_jam - $total_hours) * 60);

                            // Handle case where minutes round up to 60
                            if ($total_minutes >= 60) {
                                $total_hours += 1;
                                $total_minutes = 0;
                            }
                        @endphp
                        {{ $total_potongan_jam > 0 ? $total_hours . ' Jam ' . $total_minutes . ' Menit' : '-' }}
                    </td>
                </tr>
            </tfoot>
        </table>
    </div>

    <div class="footer">
        Dicetak pada: {{ date('d-m-Y H:i:s') }}
    </div>

    <!-- Scripts -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://kit.fontawesome.com/your-fontawesome-kit.js"></script>
    <script>
        function printPDF() {
            // Set orientasi landscape sebelum print
            let style = document.createElement('style');
            style.innerHTML = '@page { size: landscape; }';
            document.head.appendChild(style);

            // Tunggu sebentar untuk memastikan style diterapkan
            setTimeout(() => {
                window.print();
                // Hapus style setelah print
                document.head.removeChild(style);
            }, 100);
        }
    </script>
</body>
</html>
