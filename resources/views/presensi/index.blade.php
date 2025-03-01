@extends('layouts.app')
@section('titlepage', 'Monitoring Presensi')

@section('content')
@section('navigasi')
    <span>Monitoring Presensi</span>
@endsection
<div class="row">
    <div class="col-lg-12 col-sm-12 col-xs-12">
        <div class="card">
            <div class="card-header">
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-12">
                        <form action="{{ route('presensi.index') }}">
                            <x-input-with-icon label="Tanggal" value="{{ Request('tanggal') }}" name="tanggal" icon="ti ti-calendar"
                                datepicker="flatpickr-date" />
                            <div class="row">
                                <div class="col-lg-12 col-sm-12 col-md-12">
                                    <x-select label="Cabang" name="kode_cabang_search" :data="$cabang" key="kode_cabang" textShow="nama_cabang"
                                        selected="{{ Request('kode_cabang_search') }}" upperCase="true" select2="select2Kodecabangsearch" />
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-lg-12 col-sm-12 col-md-12">
                                    <x-input-with-icon label="Cari Nama Karyawan" value="{{ Request('nama_karyawan') }}" name="nama_karyawan"
                                        icon="ti ti-search" />
                                </div>
                            </div>
                            <div class="row mb-3">
                                <div class="col">
                                    <button class="btn btn-primary w-100"><i class="ti ti-icons ti-search me-1"></i>Cari</button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
                <div class="row">
                    <div class="col-12">
                        <div class="table-responsive mb-2">
                            <table class="table table-bordered">
                                <thead class="table-dark">
                                    <tr>
                                        <th>NIK</th>
                                        <th>Nama Karyawan</th>
                                        <th>Dept</th>
                                        <th>Cbg</th>
                                        <th class="text-center">Jam Kerja</th>
                                        <th class="text-center">Jam Masuk</th>
                                        <th class="text-center">Jam Pulang</th>
                                        <th class="text-center">Status</th>
                                        {{-- <th class="text-center">Keluar</th> --}}
                                        <th class="text-center">Terlambat</th>
                                        {{-- <th class="text-center">Total</th> --}}
                                        <th class="text-center">#</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($karyawan as $d)
                                        @php
                                            $tanggal_presensi = !empty(Request('tanggal')) ? Request('tanggal') : date('Y-m-d');
                                        @endphp
                                        <tr>
                                            <td>{{ $d->nik }}</td>
                                            <td>{{ $d->nama_karyawan }}</td>
                                            <td>{{ $d->kode_dept }}</td>
                                            <td>{{ $d->kode_cabang }}</td>
                                            <td class="text-center">
                                                @if ($d->kode_jam_kerja != null)
                                                    {{ $d->nama_jam_kerja }} {{ date('H:i', strtotime($d->jam_masuk)) }} -
                                                    {{ date('H:i', strtotime($d->jam_pulang)) }}
                                                @else
                                                    <i class="ti ti-hourglass-low text-warning"></i>
                                                @endif
                                            </td>
                                            <td class="text-center">
                                                {!! $d->jam_in != null ? date('H:i', strtotime($d->jam_in)) : '<i class="ti ti-hourglass-low text-warning"></i>' !!}
                                            </td>
                                            <td class="text-center">
                                                {!! $d->jam_out != null ? date('H:i', strtotime($d->jam_out)) : '<i class="ti ti-hourglass-low text-warning"></i>' !!}
                                            </td>
                                            <td class="text-center">
                                                @if ($d->status == 'h')
                                                    <span class="badge bg-success">H</span>
                                                @elseif($d->status == 'i')
                                                    <span class="badge bg-info">I</span>
                                                @elseif($d->status == 's')
                                                    <span class="badge bg-warning">S</span>
                                                @elseif($d->status == 'a')
                                                    <span class="badge bg-danger">A</span>
                                                @else
                                                    <i class="ti ti-hourglass-low text-warning"></i>
                                                @endif
                                            </td>
                                            <td class="text-center">
                                                @php
                                                    $jam_masuk = $tanggal_presensi . ' ' . $d->jam_masuk;
                                                    $terlambat = hitungjamterlambat($d->jam_in, $jam_masuk);
                                                @endphp
                                                {!! $terlambat != null ? $terlambat['show'] : '<i class="ti ti-hourglass-low text-warning"></i>' !!}
                                            </td>
                                            <td>
                                                <div class="d-flex">
                                                    <a href="#" class="me-1 koreksiPresensi" nik="{{ Crypt::encrypt($d->nik) }}"
                                                        tanggal="{{ $tanggal_presensi }}"><i class="ti ti-edit text-success"></i></a>
                                                    <a href="#" class="btnShow" nik="{{ Crypt::encrypt($d->nik) }}"
                                                        tanggal="{{ $tanggal_presensi }}">
                                                        <i class="ti ti-file-description text-primary"></i>
                                                    </a>
                                                </div>
                                            </td>
                                        </tr>
                                    @endforeach

                                </tbody>
                            </table>
                        </div>
                        <div style="float: right;">
                            {{ $karyawan->links() }}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<x-modal-form id="modal" size="" show="loadmodal" title="" />
@endsection
@push('myscript')
<script>
    $(function() {
        $(document).on('click', '.koreksiPresensi', function() {
            let nik = $(this).attr('nik');
            let tanggal = $(this).attr('tanggal');
            $.ajax({
                type: 'POST',
                url: "{{ route('presensi.edit') }}",
                data: {
                    _token: "{{ csrf_token() }}",
                    nik: nik,
                    tanggal: tanggal
                },
                cache: false,
                success: function(res) {
                    $('#modal').modal('show');
                    $('#modal').find('.modal-title').text('Koreksi Presensi');
                    $('#loadmodal').html(res);
                }
            });
        });


        $(document).on('click', '.btnShow', function() {
            let nik = $(this).attr('nik');
            let tanggal = $(this).attr('tanggal');
            $.ajax({
                type: 'POST',
                url: "{{ route('presensi.show') }}",
                data: {
                    _token: "{{ csrf_token() }}",
                    nik: nik,
                    tanggal: tanggal
                },
                cache: false,
                success: function(res) {
                    $('#modal').modal('show');
                    $('#modal').find('.modal-title').text('Detail Presensi');
                    $('#loadmodal').html(res);
                }
            });
        });
    });
</script>
@endpush
