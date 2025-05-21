@extends('layouts.app')
@section('titlepage', 'Laporan Presensi')

@section('content')
@section('navigasi')
    <span>Laporan Presensi</span>
@endsection
<div class="row">
    <div class="col-lg-6 col-sm-12 col-xs-12">
        <div class="card">
            <div class="card-body">
                <form action="{{ route('laporan.cetakpresensi') }}" method="POST" target="_blank" id="formPresensi">
                    @csrf
                    <div class="form-group mb-3">
                        <label class="form-label">Cabang</label>
                        <select name="kode_cabang" id="kode_cabang_presensi" class="form-select select2Kodecabangpresensi">
                            <option value="">Semua Cabang</option>
                            @foreach ($cabang as $d)
                                <option value="{{ $d->kode_cabang }}">{{ textUpperCase($d->nama_cabang) }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group mb-3">
                        <label class="form-label">Departemen</label>
                        <select name="kode_dept" id="kode_dept_presensi" class="form-select select2Kodedeptpresensi">
                            <option value="">Semua Departemen</option>
                        </select>
                    </div>
                    <div class="form-group mb-3">
                        <label class="form-label">Periode Laporan</label>
                        <select name="periode_laporan" id="periode_laporan" class="form-select">
                            <option value="">Pilih Periode Laporan</option>
                            <option value="1">Periode Gaji</option>
                            <option value="2">Bulan Berjalan</option>
                        </select>
                    </div>

                    <div class="row">
                        <div class="col">
                            <div class="form-group mb-3">
                                <label class="form-label">Bulan</label>
                                <select name="bulan" id="bulan" class="form-select">
                                    <option value="">Pilih Bulan</option>
                                    @foreach ($list_bulan as $d)
                                        <option {{ date('m') == $d['kode_bulan'] ? 'selected' : '' }} value="{{ $d['kode_bulan'] }}">
                                            {{ $d['nama_bulan'] }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col">
                            <div class="form-group mb-3">
                                <label class="form-label">Tahun</label>
                                <select name="tahun" id="tahun" class="form-select">
                                    <option value="">Pilih Tahun</option>
                                    @for ($t = $start_year; $t <= date('Y'); $t++)
                                        <option {{ date('Y') == $t ? 'selected' : '' }} value="{{ $t }}">{{ $t }}</option>
                                    @endfor
                                </select>
                            </div>
                        </div>
                    </div>

                    <div class="form-group mb-3">
                        <label class="form-label">Karyawan</label>
                        <select name="nik" id="nik" class="form-select select2Nik">
                            <option value="">Pilih Karyawan</option>
                        </select>
                        <small class="text-muted">Pilih karyawan untuk laporan individual</small>
                    </div>

                    <div class="row">
                        <div class="col">
                            <button type="submit" class="btn btn-primary w-100" id="btnCetakSemua">
                                <i class="ti ti-printer me-1"></i>
                                Cetak Semua
                            </button>
                        </div>
                        <div class="col">
                            <button type="button" class="btn btn-success w-100" id="btnCetakKaryawan">
                                <i class="ti ti-file-text me-1"></i>
                                Cetak Per Karyawan
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
@push('myscript')
<script>
    $(function() {
        // Inisialisasi Select2
        const select2Kodecabangpresensi = $(".select2Kodecabangpresensi");
        if (select2Kodecabangpresensi.length) {
            select2Kodecabangpresensi.each(function() {
                var $this = $(this);
                $this.wrap('<div class="position-relative"></div>').select2({
                    placeholder: 'Pilih Cabang',
                    allowClear: true,
                    dropdownParent: $this.parent()
                });
            });
        }

        const select2Nik = $(".select2Nik");
        if (select2Nik.length) {
            select2Nik.each(function() {
                var $this = $(this);
                $this.wrap('<div class="position-relative"></div>').select2({
                    placeholder: 'Pilih Karyawan',
                    allowClear: true,
                    dropdownParent: $this.parent()
                });
            });
        }

        // Load karyawan berdasarkan cabang dan departemen
        function loadKaryawan() {
            const kode_cabang = $("#kode_cabang_presensi").val();
            const kode_dept = $("#kode_dept_presensi").val();

            if (kode_cabang || kode_dept) {
                $.ajax({
                    type: 'POST',
                    url: '/karyawan/getkaryawanbycabangdept',
                    data: {
                        _token: "{{ csrf_token() }}",
                        kode_cabang: kode_cabang,
                        kode_dept: kode_dept
                    },
                    cache: false,
                    success: function(respond) {
                        $("#nik").html(respond);
                        $("#nik").trigger('change');
                    }
                });
            } else {
                $("#nik").html('<option value="">Pilih Karyawan</option>');
                $("#nik").trigger('change');
            }
        }

        // Load departemen berdasarkan cabang
        function loadDepartemen() {
            const kode_cabang = $("#kode_cabang_presensi").val();

            if (kode_cabang) {
                $.ajax({
                    type: 'POST',
                    url: '/departemen/getdepartemenbycabang',
                    data: {
                        _token: "{{ csrf_token() }}",
                        kode_cabang: kode_cabang
                    },
                    cache: false,
                    success: function(respond) {
                        $("#kode_dept_presensi").html(respond);
                        $("#kode_dept_presensi").trigger('change');
                    }
                });
            } else {
                $("#kode_dept_presensi").html('<option value="">Semua Departemen</option>');
                $("#kode_dept_presensi").trigger('change');
            }
        }

        // Event handlers
        $("#kode_cabang_presensi").change(function() {
            loadDepartemen();
            loadKaryawan();
        });

        $("#kode_dept_presensi").change(function() {
            loadKaryawan();
        });

        // Validasi form
        function validateForm(isIndividual = false) {
            const periode_laporan = $("#periode_laporan").val();
            const bulan = $("#bulan").val();
            const tahun = $("#tahun").val();
            const nik = $("#nik").val();

            if (!periode_laporan) {
                Swal.fire({
                    icon: 'warning',
                    title: 'Oops...',
                    text: 'Periode Laporan harus diisi!',
                    showConfirmButton: true,
                    didClose: () => {
                        $("#periode_laporan").focus();
                    }
                });
                return false;
            }

            if (!bulan) {
                Swal.fire({
                    icon: 'warning',
                    title: 'Oops...',
                    text: 'Bulan harus diisi!',
                    showConfirmButton: true,
                    didClose: () => {
                        $("#bulan").focus();
                    }
                });
                return false;
            }

            if (!tahun) {
                Swal.fire({
                    icon: 'warning',
                    title: 'Oops...',
                    text: 'Tahun harus diisi!',
                    showConfirmButton: true,
                    didClose: () => {
                        $("#tahun").focus();
                    }
                });
                return false;
            }

            if (isIndividual && !nik) {
                Swal.fire({
                    icon: 'warning',
                    title: 'Oops...',
                    text: 'Karyawan harus dipilih untuk laporan individual!',
                    showConfirmButton: true,
                    didClose: () => {
                        $("#nik").focus();
                    }
                });
                return false;
            }

            return true;
        }

        // Handle cetak per karyawan
        $("#btnCetakKaryawan").click(function(e) {
            e.preventDefault();
            if (validateForm(true)) {
                const form = $("#formPresensi");
                form.attr('action', "{{ route('laporan.cetakpresensikaryawan') }}");
                form.submit();
                form.attr('action', "{{ route('laporan.cetakpresensi') }}");
            }
        });

        // Handle cetak semua
        $("#formPresensi").submit(function(e) {
            if (!validateForm(false)) {
                e.preventDefault();
            }
        });
    });
</script>
@endpush
