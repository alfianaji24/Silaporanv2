<div class="row">
    <div class="col">
        <table class="table">
            <tr>
                <th>NIK</th>
                <td class="text-end">{{ $karyawan->nik }}</td>
            </tr>
            <tr>
                <th>Nama Karyawan</th>
                <td class="text-end">{{ $karyawan->nama_karyawan }}</td>
            </tr>
            <tr>
                <th>Dept</th>
                <td class="text-end">{{ $karyawan->kode_dept }}</td>
            </tr>
            <tr>
                <th>Cabang</th>
                <td class="text-end">{{ $karyawan->kode_cabang }}</td>
            </tr>
        </table>
    </div>
</div>

<div class="row mt-2">
    <div class="col">
        <table class="table table-bordered">
            <thead class="table-dark">
                <tr>
                    <th colspan="3">Absen Masuk</th>
                </tr>
                <tr>
                    <th>Foto</th>
                    <th>Jam</th>
                    <th>Lokasi</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td class="text-center">
                        @if ($presensi && $presensi->foto_in != null)
                            @php
                                $path = Storage::url('uploads/absensi/' . $presensi->foto_in);
                            @endphp
                            <img src="{{ url($path) }}" alt="" class="d-block rounded" width="100">
                        @else
                            <i class="ti ti-camera" style="font-size: 60px"></i>
                        @endif
                    </td>
                    <td>
                        @php
                            $jam_in = $presensi && $presensi->jam_in != null ? $presensi->jam_in : null;
                        @endphp
                        {{ $jam_in }}
                    </td>
                    <td></td>
                </tr>
            </tbody>
        </table>
    </div>
</div>

<div class="row mt-2">
    <div class="col">
        <table class="table table-bordered">
            <thead class="table-dark">
                <tr>
                    <th colspan="3">Absen Pulang</th>
                </tr>
                <tr>
                    <th>Foto</th>
                    <th>Jam</th>
                    <th>Lokasi</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td class="text-center">
                        @if ($presensi && $presensi->foto_out != null)
                            @php
                                $path = Storage::url('uploads/absensi/' . $presensi->foto_out);
                            @endphp
                            <img src="{{ url($path) }}" alt="" class="d-block rounded" width="100">
                        @else
                            <i class="ti ti-camera" style="font-size: 60px"></i>
                        @endif
                    </td>
                    <td>
                        @php
                            $jam_out = $presensi && $presensi->jam_out != null ? $presensi->jam_out : null;
                        @endphp
                        {{ $jam_out }}
                    </td>
                    <td></td>
                </tr>
            </tbody>
        </table>
    </div>
</div>
