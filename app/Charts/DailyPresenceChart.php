<?php

namespace App\Charts;

use ArielMejiaDev\LarapexCharts\LarapexChart;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use App\Models\Karyawan;

class DailyPresenceChart
{
    protected $chart;

    public function __construct(LarapexChart $chart)
    {
        $this->chart = $chart;
    }

    public function build(?int $month = null, ?int $year = null): \ArielMejiaDev\LarapexCharts\BarChart
    {
        $targetMonth = $month ?? Carbon::now()->month;
        $targetYear = $year ?? Carbon::now()->year;

        $date = Carbon::create($targetYear, $targetMonth, 1);
        $monthName = $date->translatedFormat('F Y');

        $totalActiveEmployees = Karyawan::where('status_aktif_karyawan', 1)->count();

        $presenceData = DB::table('presensi')
            ->join('presensi_jamkerja', 'presensi.kode_jam_kerja', '=', 'presensi_jamkerja.kode_jam_kerja')
            ->select(
                DB::raw('DAY(tanggal) as day'),
                DB::raw('SUM(CASE
                            WHEN presensi.status = "h"
                              AND TIME(presensi.jam_in) <= TIME(presensi_jamkerja.jam_masuk)
                              AND (presensi.jam_out IS NULL OR TIME(presensi.jam_out) >= TIME(presensi_jamkerja.jam_pulang))
                            THEN 1 ELSE 0 END) as hadir_count'),

                DB::raw('SUM(CASE
                            WHEN presensi.status = "h"
                              AND TIME(presensi.jam_in) > TIME(presensi_jamkerja.jam_masuk)
                            THEN 1 ELSE 0 END) as terlambat_count'),

                DB::raw('SUM(CASE
                            WHEN presensi.status = "h"
                              AND presensi.jam_out IS NOT NULL
                              AND TIME(presensi.jam_out) < TIME(presensi_jamkerja.jam_pulang)
                            THEN 1 ELSE 0 END) as pulang_cepat_count'),

                DB::raw('SUM(CASE
                            WHEN presensi.status IN ("i", "s", "c", "a")
                            THEN 1 ELSE 0 END) as izin_sakit_cuti_alpa_count'),

                DB::raw('COUNT(presensi.id) as total_presensi_hari_ini')
            )
            ->whereMonth('tanggal', '=', $targetMonth)
            ->whereYear('tanggal', '=', $targetYear)
            ->groupBy(DB::raw('DAY(tanggal)'))
            ->orderBy(DB::raw('DAY(tanggal)'))
            ->get();

        $daysInMonth = Carbon::create($targetYear, $targetMonth, 1)->daysInMonth;
        $days = range(1, $daysInMonth);

        $hadirData = array_fill_keys($days, 0);
        $terlambatData = array_fill_keys($days, 0);
        $pulangCepatData = array_fill_keys($days, 0);
        $izinSakitCutiAlpaData = array_fill_keys($days, 0);
        $belumAbsenData = array_fill_keys($days, 0);

        foreach ($presenceData as $data) {
            $hadirData[$data->day] = $data->hadir_count;
            $terlambatData[$data->day] = $data->terlambat_count;
            $pulangCepatData[$data->day] = $data->pulang_cepat_count;
            $izinSakitCutiAlpaData[$data->day] = $data->izin_sakit_cuti_alpa_count;

            $totalHariIni = $data->hadir_count + $data->terlambat_count + $data->pulang_cepat_count + $data->izin_sakit_cuti_alpa_count;
            $belumAbsenData[$data->day] = max(0, $totalActiveEmployees - $totalHariIni);
        }

        // Label X-Axis dengan format hari singkat (Sen, Sel, Rab, ...)
        $xAxisLabels = [];
foreach ($days as $day) {
    $tanggal = Carbon::create($targetYear, $targetMonth, $day);
    $label = $tanggal->format('d') . "\n" . $tanggal->translatedFormat('D');
    $xAxisLabels[] = $label;
}

        return $this->chart->barChart()
            ->setTitle('Rekapitulasi Presensi Harian ' . $monthName)
            ->addData('Hadir Tepat Waktu', array_values($hadirData))
            ->addData('Terlambat', array_values($terlambatData))
            ->addData('Pulang Cepat', array_values($pulangCepatData))
            ->addData('Izin/Sakit/Cuti/Alpa', array_values($izinSakitCutiAlpaData))
            ->addData('Belum Absen', array_values($belumAbsenData))
            ->setXAxis($xAxisLabels)
            ->setStacked(true)
            ->setColors(['#36A2EB', '#FF6384', '#FFCE56', '#9966CC', '#B0B0B0'])
            ->setHeight(400);
    }
}
