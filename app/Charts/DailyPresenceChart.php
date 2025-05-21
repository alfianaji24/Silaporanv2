<?php

namespace App\Charts;

use ArielMejiaDev\LarapexCharts\LarapexChart;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class DailyPresenceChart
{
    protected $chart;

    public function __construct(LarapexChart $chart)
    {
        $this->chart = $chart;
    }

    public function build(): \ArielMejiaDev\LarapexCharts\BarChart
    {
        $mayData = DB::table('presensi')
            ->join('presensi_jamkerja', 'presensi.kode_jam_kerja', '=', 'presensi_jamkerja.kode_jam_kerja')
            ->select(
                DB::raw('DAY(tanggal) as day'),
                DB::raw('SUM(CASE WHEN presensi.jam_in <= presensi_jamkerja.jam_masuk AND (presensi.jam_out >= presensi_jamkerja.jam_pulang OR presensi.jam_out IS NULL) THEN 1 ELSE 0 END) as hadir_count'),
                DB::raw('SUM(CASE WHEN presensi.jam_in > presensi_jamkerja.jam_masuk THEN 1 ELSE 0 END) as terlambat_count'),
                DB::raw('SUM(CASE WHEN presensi.jam_out < presensi_jamkerja.jam_pulang AND presensi.jam_out IS NOT NULL THEN 1 ELSE 0 END) as pulang_cepat_count')
            )
            ->whereMonth('tanggal', '=', 5) // Filter for May
            ->groupBy(DB::raw('DAY(tanggal)'))
            ->orderBy(DB::raw('DAY(tanggal)'))
            ->get();

        $days = $mayData->pluck('day')->toArray();
        $hadirData = $mayData->pluck('hadir_count')->toArray();
        $terlambatData = $mayData->pluck('terlambat_count')->toArray();
        $pulangCepatData = $mayData->pluck('pulang_cepat_count')->toArray();

        return $this->chart->barChart()
            ->setTitle('Rekapitulasi Presensi Harian Bulan Mei')
            ->setSubtitle('Hadir, Terlambat, dan Pulang Cepat')
            ->addData('Hadir', $hadirData)
            ->addData('Terlambat', $terlambatData)
            ->addData('Pulang Cepat', $pulangCepatData)
            ->setXAxis($days)
            ->setStacked(true); // Set the chart to be stacked
    }
}
