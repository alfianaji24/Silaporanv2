<div class="row">
    <div class="col-12">
        {{-- Display general error if any --}}
        @isset($general_error)
            <div class="alert alert-danger">
                {{ $general_error }}
            </div>
        @endisset

        {{-- Display validation errors if any --}}
        @if ($errors->any() && !isset($general_error))
            <div class="alert alert-danger">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        @if (empty($filtered_array))
            {{-- Display a message if no data is found --}}
            <div class="alert alert-warning">
                Tidak ada data presensi dari mesin untuk tanggal ini.
            </div>
        @else
            {{-- Display the table if data exists --}}
            <table class="table">
                <thead class="table-dark">
                    <tr>
                        <th colspan="4">Mesin 1</th>
                    </tr>
                    <tr>
                        <th>PIN</th>
                        <th>Status Scan</th>
                        <th>Scan Date</th>
                        <th>#</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($filtered_array as $d)
                        {{-- Add a check to ensure $d is a valid object or array --}}
                        @if (is_object($d) || is_array($d))
                            <tr>
                                <td>
                                    {{-- Check if $d->pin is a string or number before displaying --}}
                                    @if (isset($d->pin) && (is_string($d->pin) || is_numeric($d->pin)))
                                        {{ $d->pin }}
                                    @else
                                        N/A
                                    @endif
                                </td>
                                <td>
                                    {{-- Check if $d->status_scan is a string or number before displaying --}}
                                    @if (isset($d->status_scan) && (is_string($d->status_scan) || is_numeric($d->status_scan)))
                                        {{ $d->status_scan % 2 == 0 ? 'IN' : 'OUT' }} ({{ $d->status_scan }})
                                    @else
                                        N/A
                                    @endif
                                </td>
                                <td>
                                    {{-- Check if $d->scan_date is a string before processing --}}
                                    @if (isset($d->scan_date) && is_string($d->scan_date))
                                        {{ date('d-m-Y H:i:s', strtotime($d->scan_date)) }}
                                    @else
                                        N/A
                                    @endif
                                </td>
                                <td>
                                    <div class="d-flex">
                                        <form method="POST" name="updatemasuk" class="updatemasuk me-1"
                                            action="{{ route('presensi.updatefrommachine', [Crypt::encrypt($d->pin), 0]) }}">
                                            @csrf
                                            {{-- Ensure scan_date is a string for the hidden input value --}}
                                            <input type="hidden" name="scan_date" value="{{ isset($d->scan_date) && is_string($d->scan_date) ? date('Y-m-d H:i:s', strtotime($d->scan_date)) : '' }}">
                                            <button href="#" class="btn btn-success btn-sm me-1">
                                                <i class="ti ti-login me-1"></i> Masuk
                                            </button>
                                        </form>
                                        <form method="POST" name="updatepulang" class="updatepulang"
                                            action="{{ route('presensi.updatefrommachine', [Crypt::encrypt($d->pin), 1]) }}">
                                            @csrf
                                            {{-- Ensure scan_date is a string for the hidden input value --}}
                                            <input type="hidden" name="scan_date" value="{{ isset($d->scan_date) && is_string($d->scan_date) ? date('Y-m-d H:i:s', strtotime($d->scan_date)) : '' }}">
                                            <button href="#" class="btn btn-danger btn-sm me-1">
                                                <i class="ti ti-logout me-1"></i> Pulang
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @else
                            {{-- Optionally, log or display a message for malformed data --}}
                            <tr>
                                <td colspan="4" class="text-center text-danger">Malformed data received from API.</td>
                            </tr>
                        @endif
                    @endforeach
                </tbody>
            </table>
        @endif
    </div>
</div>


</div>
