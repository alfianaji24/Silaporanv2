<?php

namespace App\Http\Controllers;

use App\Charts\JeniskelaminkaryawanChart;
use App\Charts\PendidikankaryawanChart;
use App\Charts\StatusKaryawanChart;
use App\Charts\DailyPresenceChart;
use App\Models\Karyawan;
use App\Models\Presensi;
use App\Models\User;
use App\Models\Userkaryawan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Jenssegers\Agent\Agent;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function index(StatusKaryawanChart $chart, JeniskelaminkaryawanChart $jkchart, PendidikankaryawanChart $pddchart, DailyPresenceChart $dpchart)
    {
        $agent = new Agent();
        $user = User::where('id', auth()->user()->id)->first();
        $hari_ini = date("Y-m-d");
        $monthName = Carbon::now()->translatedFormat('F');

        if ($user->hasRole('karyawan')) {
            $userkaryawan = Userkaryawan::where('id_user', auth()->user()->id)->first();
            $data['karyawan'] = Karyawan::where('nik', $userkaryawan->nik)
                ->join('jabatan', 'karyawan.kode_jabatan', '=', 'jabatan.kode_jabatan')
                ->join('departemen', 'karyawan.kode_dept', '=', 'departemen.kode_dept')
                ->join('cabang', 'karyawan.kode_cabang', '=', 'cabang.kode_cabang')
                ->first();

            $data['presensi'] = Presensi::where('presensi.nik', $userkaryawan->nik)->where('presensi.tanggal', $hari_ini)->first();
            $data['datapresensi'] = Presensi::join('presensi_jamkerja', 'presensi.kode_jam_kerja', '=', 'presensi_jamkerja.kode_jam_kerja')
                ->where('presensi.nik', $userkaryawan->nik)
                ->leftJoin('presensi_izinabsen_approve', 'presensi.id', '=', 'presensi_izinabsen_approve.id_presensi')
                ->leftJoin('presensi_izinabsen', 'presensi_izinabsen_approve.kode_izin', '=', 'presensi_izinabsen.kode_izin')

                ->leftJoin('presensi_izinsakit_approve', 'presensi.id', '=', 'presensi_izinsakit_approve.id_presensi')
                ->leftJoin('presensi_izinsakit', 'presensi_izinsakit_approve.kode_izin_sakit', '=', 'presensi_izinsakit.kode_izin_sakit')

                ->leftJoin('presensi_izincuti_approve', 'presensi.id', '=', 'presensi_izincuti_approve.id_presensi')
                ->leftJoin('presensi_izincuti', 'presensi_izincuti_approve.kode_izin_cuti', '=', 'presensi_izincuti.kode_izin_cuti')
                ->select(
                    'presensi.*',
                    'presensi_jamkerja.nama_jam_kerja',
                    'presensi_jamkerja.jam_masuk',
                    'presensi_jamkerja.jam_pulang',
                    'presensi_jamkerja.total_jam',
                    'presensi_jamkerja.lintashari',
                    'presensi_izinabsen.keterangan as keterangan_izin',
                    'presensi_izinsakit.keterangan as keterangan_izin_sakit',
                    'presensi_izincuti.keterangan as keterangan_izin_cuti'
                )
                ->orderBy('tanggal', 'desc')
                ->limit(30)
                ->get();
            $data['rekappresensi'] = Presensi::select(
                DB::raw("SUM(IF(status='h',1,0)) as hadir"),
                DB::raw("SUM(IF(status='i',1,0)) as izin"),
                DB::raw("SUM(IF(status='s',1,0)) as sakit"),
                DB::raw("SUM(IF(status='a',1,0)) as alpa"),
                DB::raw("SUM(IF(status='c',1,0)) as cuti")
            )
                ->groupBy('presensi.nik')
                ->limit(30)
                ->where('presensi.nik', $userkaryawan->nik)
                ->first();
            return view('dashboard.karyawan', $data);
        } else {
            $sk = new Karyawan();
            $data['status_karyawan'] = $sk->getRekapstatuskaryawan();
            $data['chart'] = $chart->build();
            $data['jkchart'] = $jkchart->build();
            $data['pddchart'] = $pddchart->build();
            $data['dpchart'] = $dpchart->build();
            $data['monthName'] = $monthName;

            // Get ulang tahun karyawan - 10 terdekat
            $data['ulang_tahun'] = Karyawan::select([
                'nama_karyawan',
                'tanggal_lahir',
                DB::raw('TIMESTAMPDIFF(YEAR, tanggal_lahir, CURDATE()) as umur'),
                DB::raw('DATE_FORMAT(tanggal_lahir, "%m-%d") as birth_date'),
                DB::raw('DATE_FORMAT(CURDATE(), "%m-%d") as today_date')
            ])
            ->where('status_aktif_karyawan', 1)
            ->whereRaw('DATE_FORMAT(tanggal_lahir, "%m-%d") >= DATE_FORMAT(CURDATE(), "%m-%d")')
            ->orderByRaw('DATE_FORMAT(tanggal_lahir, "%m-%d")')
            ->paginate(8, ['*'], 'ulang_tahun_page');

            // Get today's attendance statistics
            $data['presensi_hari_ini'] = Presensi::join('presensi_jamkerja', 'presensi.kode_jam_kerja', '=', 'presensi_jamkerja.kode_jam_kerja')
                ->where('presensi.tanggal', $hari_ini)
                ->select(
                    DB::raw("COUNT(CASE WHEN presensi.jam_in <= presensi_jamkerja.jam_masuk AND presensi.jam_out >= presensi_jamkerja.jam_pulang THEN 1 END) as tepat_waktu"),
                    DB::raw("COUNT(CASE WHEN presensi.jam_in > presensi_jamkerja.jam_masuk THEN 1 END) as terlambat"),
                    DB::raw("COUNT(CASE WHEN presensi.jam_out < presensi_jamkerja.jam_pulang THEN 1 END) as pulang_cepat"),
                    DB::raw("(SELECT COUNT(*) FROM karyawan WHERE status_aktif_karyawan = 1) - COUNT(presensi.id) as tidak_absen")
                )
                ->first();

            // Get list of employees who are late
            $data['karyawan_terlambat'] = Presensi::join('presensi_jamkerja', 'presensi.kode_jam_kerja', '=', 'presensi_jamkerja.kode_jam_kerja')
                ->join('karyawan', 'presensi.nik', '=', 'karyawan.nik')
                ->where('presensi.jam_in', '>', DB::raw('presensi_jamkerja.jam_masuk'))
                ->where('karyawan.status_aktif_karyawan', 1)
                ->select(
                    'karyawan.nama_karyawan',
                    DB::raw('COUNT(*) as jumlah_terlambat'),
                    DB::raw('SEC_TO_TIME(SUM(TIME_TO_SEC(presensi.jam_in) - TIME_TO_SEC(presensi_jamkerja.jam_masuk))) as total_keterlambatan')
                )
                ->groupBy('karyawan.nik', 'karyawan.nama_karyawan')
                ->orderBy(DB::raw('SUM(TIME_TO_SEC(presensi.jam_in) - TIME_TO_SEC(presensi_jamkerja.jam_masuk))'), 'desc')
                ->paginate(8, ['*'], 'terlambat_page');

            // Get list of employees who are on time today
            $data['karyawan_tepat_waktu'] = Presensi::join('presensi_jamkerja', 'presensi.kode_jam_kerja', '=', 'presensi_jamkerja.kode_jam_kerja')
                ->join('karyawan', 'presensi.nik', '=', 'karyawan.nik')
                ->join('departemen', 'karyawan.kode_dept', '=', 'departemen.kode_dept')
                ->where('presensi.tanggal', $hari_ini)
                ->where('presensi.jam_in', '<=', DB::raw('presensi_jamkerja.jam_masuk'))
                ->where(function($query) {
                    $query->where('presensi.jam_out', '>=', DB::raw('presensi_jamkerja.jam_pulang'))
                          ->orWhereNull('presensi.jam_out');
                })
                ->select(
                    'presensi.nik',
                    'karyawan.nama_karyawan',
                    'departemen.nama_dept',
                    'presensi.jam_in',
                    'presensi.jam_out'
                )
                ->orderBy('karyawan.nama_karyawan')
                ->get();

            // Get list of employees who haven't checked out today
            $data['karyawan_belum_pulang'] = Presensi::join('karyawan', 'presensi.nik', '=', 'karyawan.nik')
                ->whereBetween('presensi.tanggal', [Carbon::now()->startOfMonth(), Carbon::now()->endOfMonth()])
                ->whereNull('presensi.jam_out')
                ->where('karyawan.status_aktif_karyawan', 1)
                ->select(
                    'karyawan.nama_karyawan',
                    DB::raw('COUNT(*) as jumlah_tidak_pulang'),
                    DB::raw('MAX(presensi.tanggal) as tanggal_terakhir'),
                    DB::raw('MIN(presensi.tanggal) as tanggal_pertama')
                )
                ->groupBy('karyawan.nik', 'karyawan.nama_karyawan')
                ->having('jumlah_tidak_pulang', '>', 0)
                ->orderBy('jumlah_tidak_pulang', 'desc')
                ->paginate(7, ['*'], 'belum_pulang_page');

            return view('dashboard.dashboard', $data);
        }
    }
}
