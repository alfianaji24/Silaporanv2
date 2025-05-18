<?php

namespace App\Charts;

use ArielMejiaDev\LarapexCharts\LarapexChart;
use Illuminate\Support\Facades\DB;

class StatusKaryawanChart
{
    protected $chart;

    public function __construct(LarapexChart $chart)
    {
        $this->chart = $chart;
    }

    public function build(): \ArielMejiaDev\LarapexCharts\PieChart
    {
        $rawData = DB::table('karyawan')
            ->select('status_karyawan', DB::raw('count(*) as total'))
            ->groupBy('status_karyawan')
            ->pluck('total', 'status_karyawan')
            ->toArray();

        $statusLabels = [
            'T' => 'Tetap',
            'K' => 'Kontrak',
            'O' => 'Outsourcing'
        ];

        $labels = [];
        $data = [];

        foreach ($statusLabels as $key => $label) {
            $labels[] = $label;
            $data[] = $rawData[$key] ?? 0;
        }

        return $this->chart->pieChart()
            ->addData($data)
            ->setLabels($labels)
            ->setColors(['#FF6384', '#36A2EB', '#FFCE56'])
            ->setDataLabels(true)
            ->setOptions([
                'dataLabels' => [
                    'enabled' => true,
                    'formatter' => fn($val) => round($val, 1) . '%',
                    'dropShadow' => ['enabled' => true]
                ]
            ]);
    }
}
