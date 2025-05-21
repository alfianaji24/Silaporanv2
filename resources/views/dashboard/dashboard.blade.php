@extends('layouts.app')
@section('titlepage', 'Dashboard')
@section('content')
@section('navigasi')
    <span>Dashboard</span> | <span id="datetime"></span>
@endsection
<div class="row">
    <div class="col-lg-12 col-sm-12 col-xs-12">
        <div class="card mb-6">
            <div class="card-widget-separator-wrapper">
                <div class="card-body card-widget-separator">
                    <div class="row gy-4 gy-sm-1">
                        <div class="col-sm-6 col-lg-3">
                            <div class="d-flex justify-content-between align-items-start card-widget-1 border-end pb-4 pb-sm-0">
                                <div>
                                    <p class="mb-1">Presensi Tepat Waktu</p>
                                    <h4 class="mb-1">{{ $presensi_hari_ini->tepat_waktu }}</h4>
                                </div>
                                <img src="{{ asset('assets/img/illustrations/tepat_waktu.png') }}" height="70" alt="view sales" class="me-3">
                            </div>
                        </div>
                        <div class="col-sm-6 col-lg-3">
                            <div class="d-flex justify-content-between align-items-start card-widget-2 border-end pb-4 pb-sm-0">
                                <div>
                                    <p class="mb-1">Terlambat Masuk</p>
                                    <h4 class="mb-1">{{ $presensi_hari_ini->terlambat }}</h4>
                                </div>
                                <img src="{{ asset('assets/img/illustrations/terlambat.png') }}" height="70" alt="view sales" class="me-3">
                            </div>
                        </div>
                        <div class="col-sm-6 col-lg-3">
                            <div class="d-flex justify-content-between align-items-start border-end pb-4 pb-sm-0 card-widget-3">
                                <div>
                                    <p class="mb-1">Pulang Cepat</p>
                                    <h4 class="mb-1">{{ $presensi_hari_ini->pulang_cepat }}</h4>
                                </div>
                                <img src="{{ asset('assets/img/illustrations/pulang_awal.png') }}" height="70" alt="view sales" class="me-3">
                            </div>
                        </div>
                        <div class="col-sm-6 col-lg-3">
                            <div class="d-flex justify-content-between align-items-start">
                                <div>
                                    <p class="mb-1">Tidak Absen</p>
                                    <h4 class="mb-1">{{ $presensi_hari_ini->tidak_absen }}</h4>
                                </div>
                                <img src="{{ asset('assets/img/illustrations/tidak_absen.png') }}" height="70" alt="view sales" class="me-3">
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="row mt-3">
    <div class="col-lg-12 col-sm-12 col-xs-12">
        <div class="card mb-6">
            <div class="card-widget-separator-wrapper">
                <div class="card-body card-widget-separator">
                    <div class="row gy-4 gy-sm-1">
                        <div class="col-sm-6 col-lg-3">
                            <div class="d-flex justify-content-between align-items-start card-widget-1 border-end pb-4 pb-sm-0">
                                <div>
                                    <p class="mb-1">Data Karyawn Aktif</p>
                                    <h4 class="mb-1">{{ $status_karyawan->jml_aktif }}</h4>
                                </div>
                                <img src="{{ asset('assets/img/illustrations/karyawan1.png') }}" height="70" alt="view sales" class="me-3">
                            </div>
                        </div>
                        <div class="col-sm-6 col-lg-3">
                            <div class="d-flex justify-content-between align-items-start card-widget-2 border-end pb-4 pb-sm-0">
                                <div>
                                    <p class="mb-1">Karyawan Tetap</p>
                                    <h4 class="mb-1">{{ $status_karyawan->jml_tetap }}</h4>
                                </div>
                                <img src="{{ asset('assets/img/illustrations/karyawan2.webp') }}" height="70" alt="view sales" class="me-3">
                            </div>
                        </div>
                        <div class="col-sm-6 col-lg-3">
                            <div class="d-flex justify-content-between align-items-start border-end pb-4 pb-sm-0 card-widget-3">
                                <div>
                                    <p class="mb-1">Karyawan Kontrak</p>
                                    <h4 class="mb-1">{{ $status_karyawan->jml_kontrak }}</h4>
                                </div>
                                <img src="{{ asset('assets/img/illustrations/karyawan3.png') }}" height="70" alt="view sales" class="me-3">
                            </div>
                        </div>
                        <div class="col-sm-6 col-lg-3">
                            <div class="d-flex justify-content-between align-items-start">
                                <div>
                                    <p class="mb-1">Outsourcing</p>
                                    <h4 class="mb-1">{{ $status_karyawan->jml_outsourcing }}</h4>
                                </div>
                                <img src="{{ asset('assets/img/illustrations/karyawan4.webp') }}" height="70" alt="view sales" class="me-3">
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="row mt-3">
    <div class="col-lg-4 col-md-6 col-sm-12 d-flex align-items-stretch">
        <div class="card w-100">
            <div class="card-header">
                <h4 class="card-title">Daftar Ulang Tahun Karyawan</h4>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-bordered table-striped">
                        <thead class="table-dark">
                            <tr>
                                <th style="width: 40%">Nama Karyawan</th>
                                <th>Tgl Lahir</th>
                                <th>Umur</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($ulang_tahun as $d)
                            <tr>
                                <td style="white-space: normal; word-wrap: break-word;">{{ $d->nama_karyawan }}</td>
                                <td>{{ date('d-M-Y', strtotime($d->tanggal_lahir)) }}</td>
                                <td>{{ $d->umur }} Thn</td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="d-flex justify-content-end mt-3">
                {{ $ulang_tahun->links() }}
            </div>
        </div>
    </div>
    <div class="col-lg-4 col-md-6 col-sm-12 d-flex align-items-stretch">
        <div class="card w-100">
            <div class="card-header">
                <h4 class="card-title">Jenis Kelamin</h4>
            </div>
            <div class="card-body">
                {!! $jkchart->container() !!}
            </div>
        </div>
    </div>
    <div class="col-lg-4 col-md-6 col-sm-12 d-flex align-items-stretch">
        <div class="card w-100">
            <div class="card-header">
                <h4 class="card-title">Pendidikan Karyawan</h4>
            </div>
            <div class="card-body">
                {!! $pddchart->container() !!}
            </div>
        </div>
    </div>

</div>
<div class="row mt-3">
<div class="col-lg-12 col-sm-12 col-xs-12">
        <div class="card w-100">
            <div class="card-header">
                <h4 class="card-title">Rekapitulasi Presensi Harian Bulan Mei</h4>
            </div>
            <div class="card-body">
                {!! $dpchart->container() !!}
            </div>
        </div>
    </div>
</div>
@endsection
@push('myscript')
<script src="{{ $chart->cdn() }}"></script>
{{ $jkchart->script() }}
{{ $pddchart->script() }}
{{ $dpchart->script() }}
<script>
    function updateDateTime() {
        const now = new Date();
        const options = {
            weekday: 'long',
            year: 'numeric',
            month: 'long',
            day: 'numeric',
            hour: '2-digit',
            minute: '2-digit',
            second: '2-digit'
        };
        document.getElementById('datetime').textContent = now.toLocaleDateString('id-ID', options);
    }

    // Update immediately
    updateDateTime();

    // Update every second
    setInterval(updateDateTime, 1000);
</script>
@endpush