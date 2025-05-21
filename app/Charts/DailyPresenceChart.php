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
            ->select(
                DB::raw('DAY(tanggal) as day'),
                DB::raw('SUM(CASE WHEN status = "hadir" THEN 1 ELSE 0 END) as hadir_count'),
                DB::raw('SUM(CASE WHEN status = "terlambat" THEN 1 ELSE 0 END) as terlambat_count'),
                DB::raw('SUM(CASE WHEN status = "pulang_cepat" THEN 1 ELSE 0 END) as pulang_cepat_count')
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
            ->setXAxis($days);
    }
}
