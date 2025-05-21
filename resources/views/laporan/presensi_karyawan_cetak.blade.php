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
            size: A4;
            margin: 2.5cm;
        }
        body {
            font-family: Arial, sans-serif;
            font-size: 11px;
            line-height: 1.4;
            margin: 0;
            padding: 20px;
        }
        .header {
            margin-bottom: 20px;
            border-bottom: 2px solid #000;
            padding-bottom: 15px;
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
            line-height: 1.6;
            font-size: 14px;
            padding: 5px 0;
        }
        .info-karyawan {
            margin-bottom: 20px;
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
            padding: 5px 12px;
        }
        .info-karyawan td:first-child {
            width: 120px;
            font-weight: bold;
        }
        .datatable3 {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 25px;
            font-size: 10px;
            page-break-inside: auto;
        }
        .datatable3 th, .datatable3 td {
            border: 1px solid #000;
            padding: 6px 8px;
            text-align: center;
            vertical-align: middle;
        }
        .datatable3 th {
            background-color: #f0f0f0;
            font-weight: bold;
            padding: 8px;
        }
        .datatable3 .tanggal-column {
            width: 120px;
            text-align: left;
            padding-left: 12px;
        }
        .datatable3 .status-column {
            width: 80px;
            padding: 6px 4px;
        }
        .datatable3 .jam-column {
            width: 60px;
            padding: 6px 4px;
        }
        .datatable3 .keterangan-column {
            width: 100px;
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
        .status-hadir { background-color: white; }
        .status-izin { background-color: #ffc107; color: black; }
        .status-sakit { background-color: #ffc107; color: black; }
        .status-cuti { background-color: #0164b5; color: white; }
        .status-libur { background-color: green; color: white; }
        .status-tidak-hadir { background-color: red; color: white; }
        .content {
            padding: 10px 0;
        }

        /* Print styles */
        @media print {
            body {
                -webkit-print-color-adjust: exact;
                print-color-adjust: exact;
                padding: 0;
                margin: 0;
            }
             @page {
                size: A4;
                margin: 2.5cm;
            }

            .header {
                margin: 0 20px 20px 20px;
            }
            .content {
                padding: 20px 0;
                margin: 0 20px;
            }
            .info-karyawan {
                margin: 0 20px 20px 20px;
            }
            .datatable3 {
                margin-bottom: 30px;
                margin-top: 15px;
            }
            .footer {
                margin: 0 20px;
                padding: 10px 0;
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
                    <span style="font-style: italic;">{{ $generalsetting->alamat }}</span><br>
                    <span style="font-style: italic;">{{ $generalsetting->telepon }}</span>
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
                    <th rowspan="2">Tanggal</th>
                    <th rowspan="2">Status</th>
                    <th colspan="2">Jam Kerja</th>
                    <th colspan="2">Presensi</th>
                    <th rowspan="2">Keterlambatan</th>
                    <th rowspan="2">Pulang Cepat</th>
                    <th rowspan="2">Denda</th>
                    <th rowspan="2">Pot. Jam</th>
                </tr>
                <tr>
                    <th>Masuk</th>
                    <th>Pulang</th>
                    <th>Masuk</th>
                    <th>Pulang</th>
                </tr>
            </thead>
            <tbody>
                @php
                    $tanggal_presensi = $periode_dari;
                    $total_denda = 0;
                    $total_potongan_jam = 0;
                    $total_keterlambatan = 0;
                    $row_count = 0;
                @endphp
                @while (strtotime($tanggal_presensi) <= strtotime($periode_sampai))
                    @php
                        $row_count++;
                        $denda = 0;
                        $potongan_jam = 0;
                        $keterlambatan_menit = 0;
                        $search = [
                            'nik' => $karyawan['nik'],
                            'tanggal' => $tanggal_presensi,
                        ];
                        $ceklibur = ceklibur($datalibur, $search);
                        $status = '';
                        $keterangan = '';
                        $jam_masuk = '';
                        $jam_pulang = '';
                        $jam_in = '';
                        $jam_out = '';
                        $terlambat = '';
                        $pulang_cepat = '';
                        $bgcolor = 'white';
                        $textcolor = 'black';
                    @endphp

                    @if (isset($karyawan[$tanggal_presensi]))
                        @if ($karyawan[$tanggal_presensi]['status'] == 'h')
                            @php
                                $status = 'Hadir';
                                $jam_masuk = date('H:i', strtotime($karyawan[$tanggal_presensi]['jam_masuk']));
                                $jam_pulang = date('H:i', strtotime($karyawan[$tanggal_presensi]['jam_pulang']));
                                $jam_in = !empty($karyawan[$tanggal_presensi]['jam_in'])
                                    ? date('H:i', strtotime($karyawan[$tanggal_presensi]['jam_in']))
                                    : '-';
                                $jam_out = !empty($karyawan[$tanggal_presensi]['jam_out'])
                                    ? date('H:i', strtotime($karyawan[$tanggal_presensi]['jam_out']))
                                    : '-';

                                $jam_masuk_full = $tanggal_presensi . ' ' . $karyawan[$tanggal_presensi]['jam_masuk'];
                                $terlambat_data = hitungjamterlambat($karyawan[$tanggal_presensi]['jam_in'], $jam_masuk_full);
                                $terlambat = $terlambat_data != null ? $terlambat_data['show_laporan'] : '-';

                                if ($terlambat_data != null) {
                                    if ($terlambat_data['desimal_terlambat'] < 1) {
                                        $potongan_jam_terlambat = 0;
                                        $denda = hitungdenda($denda_list, $terlambat_data['menitterlambat']);
                                        $keterlambatan_menit = $terlambat_data['menitterlambat'];
                                    } else {
                                        $potongan_jam_terlambat = $terlambat_data['desimal_terlambat'];
                                        $denda = 0;
                                        $keterlambatan_menit = $terlambat_data['menitterlambat'];
                                    }
                                }

                                $pulang_cepat = hitungpulangcepat(
                                    $tanggal_presensi,
                                    $karyawan[$tanggal_presensi]['jam_out'],
                                    $karyawan[$tanggal_presensi]['jam_pulang'],
                                    $karyawan[$tanggal_presensi]['istirahat'],
                                    $karyawan[$tanggal_presensi]['jam_awal_istirahat'],
                                    $karyawan[$tanggal_presensi]['jam_akhir_istirahat'],
                                    $karyawan[$tanggal_presensi]['lintashari']
                                );
                                $pulang_cepat = $pulang_cepat != null ? $pulang_cepat . ' Jam' : '-';
                                $potongan_jam = ($pulang_cepat != '-' ? floatval($pulang_cepat) : 0) + ($potongan_jam_terlambat ?? 0);
                            @endphp
                        @elseif($karyawan[$tanggal_presensi]['status'] == 'i')
                            @php
                                $status = 'Izin';
                                $keterangan = $karyawan[$tanggal_presensi]['keterangan_izin_absen'];
                                $bgcolor = '#ffc107';
                                $textcolor = 'black';
                            @endphp
                        @elseif($karyawan[$tanggal_presensi]['status'] == 's')
                            @php
                                $status = 'Sakit';
                                $keterangan = $karyawan[$tanggal_presensi]['keterangan_izin_sakit'];
                                $bgcolor = '#ffc107';
                                $textcolor = 'black';
                            @endphp
                        @elseif($karyawan[$tanggal_presensi]['status'] == 'c')
                            @php
                                $status = 'Cuti';
                                $keterangan = $karyawan[$tanggal_presensi]['keterangan_izin_cuti'];
                                $bgcolor = '#0164b5';
                                $textcolor = 'white';
                            @endphp
                        @endif
                    @else
                        @php
                            $status = 'Tidak Hadir';
                            $bgcolor = 'red';
                            $textcolor = 'white';
                            if (!empty($ceklibur)) {
                                $status = 'Libur';
                                $keterangan = $ceklibur[0]['keterangan'];
                                $bgcolor = 'green';
                            }
                        @endphp
                    @endif

                    @php
                        $total_denda += $denda;
                        $total_potongan_jam += $potongan_jam;
                        $total_keterlambatan += $keterlambatan_menit;
                    @endphp

                    <tr class="status-{{ strtolower($status) }}">
                        <td class="tanggal-column">{{ date('d-m-Y', strtotime($tanggal_presensi)) }} ({{ getHari($tanggal_presensi) }})</td>
                        <td class="status-column">{{ $status }}</td>
                        <td class="jam-column">{{ $jam_masuk }}</td>
                        <td class="jam-column">{{ $jam_pulang }}</td>
                        <td class="jam-column">{{ $jam_in }}</td>
                        <td class="jam-column">{{ $jam_out }}</td>
                        <td class="keterangan-column">{{ $terlambat }}</td>
                        <td class="keterangan-column">{{ $pulang_cepat }}</td>
                        <td class="jam-column">{{ $denda > 0 ? formatAngka($denda) : '-' }}</td>
                        <td class="jam-column">{{ $potongan_jam > 0 ? formatAngkaDesimal($potongan_jam) : '-' }}</td>
                    </tr>

                    @php
                        $tanggal_presensi = date('Y-m-d', strtotime('+1 day', strtotime($tanggal_presensi)));
                    @endphp
                @endwhile
            </tbody>
            <tfoot>
                <tr style="font-weight: bold; background-color: #f0f0f0">
                    <td colspan="8" style="text-align: right">Total:</td>
                    <td>{{ formatAngka($total_denda) }}</td>
                    <td>{{ formatAngkaDesimal($total_potongan_jam) }}</td>
                </tr>
                <tr style="font-weight: bold; background-color: #f0f0f0">
                    <td colspan="8" style="text-align: right">Total Keterlambatan:</td>
                    <td>
                        @php
                            $hours = floor($total_keterlambatan / 60);
                            $minutes = $total_keterlambatan % 60;
                        @endphp
                        {{ $hours }} Jam {{ $minutes }} Menit
                    </td>
                    <td></td>
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
        // Function to handle PDF download
        function downloadPDF() {
            window.print();
        }
    </script>
</body>
</html>
