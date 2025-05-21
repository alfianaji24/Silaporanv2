@extends('layouts.app')
@section('titlepage', 'Status Pesan WhatsApp')
@section('navigasi')
    <span>Status Pesan WhatsApp</span>
@endsection

@push('css')
<!-- DataTables CSS -->
<link rel="stylesheet" href="https://cdn.datatables.net/1.13.7/css/dataTables.bootstrap5.min.css">
<link rel="stylesheet" href="https://cdn.datatables.net/responsive/2.5.0/css/responsive.bootstrap5.min.css">
<!-- Toastr CSS -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css">
@endpush

@section('content')
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="card-title mb-0">Status Pesan WhatsApp</h5>
                    <div class="card-tools">
                        <div class="btn-group">
                            <button type="button" class="btn btn-sm btn-outline-primary" id="refreshBtn">
                                <i class="ti ti-refresh me-1"></i> Refresh
                            </button>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    <!-- Filter Section -->
                    <div class="row mb-3">
                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="statusFilter">Status</label>
                                <select class="form-select" id="statusFilter">
                                    <option value="">Semua Status</option>
                                    <option value="pending">Pending</option>
                                    <option value="sent">Terkirim</option>
                                    <option value="delivered">Terdeliver</option>
                                    <option value="read">Dibaca</option>
                                    <option value="failed">Gagal</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="dateFilter">Tanggal</label>
                                <input type="date" class="form-control" id="dateFilter">
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="searchNIK">Cari NIK</label>
                                <input type="text" class="form-control" id="searchNIK" placeholder="Masukkan NIK">
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="searchPhone">Cari Nomor</label>
                                <input type="text" class="form-control" id="searchPhone" placeholder="Masukkan nomor">
                            </div>
                        </div>
                    </div>

                    <!-- Table Section -->
                    <div class="table-responsive">
                        <table class="table table-hover" id="messageStatusTable">
                            <thead>
                                <tr>
                                    <th>NIK</th>
                                    <th>Nama Karyawan</th>
                                    <th>Nomor WhatsApp</th>
                                    <th>Status</th>
                                    <th>Pesan</th>
                                    <th>Waktu Kirim</th>
                                    <th>Waktu Deliver</th>
                                    <th>Waktu Baca</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                <!-- Data will be loaded dynamically -->
                            </tbody>
                        </table>
                    </div>

                    <!-- Pagination -->
                    <div class="d-flex justify-content-between align-items-center mt-3">
                        <div class="text-muted">
                            Menampilkan <span id="startRecord">0</span> - <span id="endRecord">0</span> dari <span id="totalRecords">0</span> data
                        </div>
                        <nav aria-label="Page navigation">
                            <ul class="pagination mb-0" id="pagination">
                                <!-- Pagination will be loaded dynamically -->
                            </ul>
                        </nav>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Status Badge Template -->
    <template id="statusBadgeTemplate">
        <span class="badge bg-{color}">{status}</span>
    </template>

    <!-- Error Modal -->
    <div class="modal fade" id="errorModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Detail Error</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <p id="errorMessage"></p>
                </div>
            </div>
        </div>
    </div>

    <style>
        .status-badge {
            padding: 0.5em 0.75em;
            border-radius: 0.25rem;
            font-weight: 500;
            font-size: 0.875rem;
        }

        .status-badge.pending {
            background-color: #ffc107;
            color: #000;
        }

        .status-badge.sent {
            background-color: #0dcaf0;
            color: #fff;
        }

        .status-badge.delivered {
            background-color: #198754;
            color: #fff;
        }

        .status-badge.read {
            background-color: #0d6efd;
            color: #fff;
        }

        .status-badge.failed {
            background-color: #dc3545;
            color: #fff;
        }

        .message-preview {
            max-width: 200px;
            overflow: hidden;
            text-overflow: ellipsis;
            white-space: nowrap;
        }

        .table td {
            vertical-align: middle;
        }

        .action-btn {
            padding: 0.25rem 0.5rem;
            font-size: 0.875rem;
        }
    </style>

@endsection

@push('myscript')
<!-- jQuery (if not already included) -->
<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
<!-- DataTables JS -->
<script src="https://cdn.datatables.net/1.13.7/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.13.7/js/dataTables.bootstrap5.min.js"></script>
<script src="https://cdn.datatables.net/responsive/2.5.0/js/dataTables.responsive.min.js"></script>
<script src="https://cdn.datatables.net/responsive/2.5.0/js/responsive.bootstrap5.min.js"></script>
<!-- Moment.js -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.29.4/moment.min.js"></script>
<!-- Toastr JS -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        let currentPage = 1;
        const perPage = 10;
        let filters = {
            status: '',
            date: '',
            nik: '',
            phone: ''
        };

        // Initialize DataTable
        const table = $('#messageStatusTable').DataTable({
            processing: true,
            serverSide: true,
            ajax: {
                url: '{{ route("wa.message.status.data") }}',
                data: function(d) {
                    d.status = filters.status;
                    d.date = filters.date;
                    d.nik = filters.nik;
                    d.phone = filters.phone;
                },
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                error: function(xhr, error, thrown) {
                    if (xhr.status === 401) {
                        toastr.error('Sesi Anda telah berakhir. Silakan login kembali.');
                        setTimeout(() => {
                            window.location.href = '{{ route("login") }}';
                        }, 2000);
                    } else if (xhr.status === 403) {
                        toastr.error('Anda tidak memiliki akses ke halaman ini.');
                    } else {
                        toastr.error('Terjadi kesalahan saat memuat data. Silakan coba lagi.');
                    }
                    console.error('DataTables error:', error, thrown);
                }
            },
            columns: [
                { data: 'nik' },
                {
                    data: 'employee.name',
                    render: function(data, type, row) {
                        return data || '-';
                    }
                },
                { data: 'phone_number' },
                {
                    data: 'status',
                    render: function(data) {
                        const statusMap = {
                            'pending': { text: 'Pending', class: 'warning' },
                            'sent': { text: 'Terkirim', class: 'info' },
                            'delivered': { text: 'Terdeliver', class: 'success' },
                            'read': { text: 'Dibaca', class: 'primary' },
                            'failed': { text: 'Gagal', class: 'danger' }
                        };
                        const status = statusMap[data] || { text: data, class: 'secondary' };
                        return `<span class="badge bg-${status.class}">${status.text}</span>`;
                    }
                },
                {
                    data: 'message_content',
                    render: function(data) {
                        return `<div class="message-preview" title="${data}">${data}</div>`;
                    }
                },
                {
                    data: 'sent_at',
                    render: function(data) {
                        return data ? moment(data).format('DD/MM/YYYY HH:mm') : '-';
                    }
                },
                {
                    data: 'delivered_at',
                    render: function(data) {
                        return data ? moment(data).format('DD/MM/YYYY HH:mm') : '-';
                    }
                },
                {
                    data: 'read_at',
                    render: function(data) {
                        return data ? moment(data).format('DD/MM/YYYY HH:mm') : '-';
                    }
                },
                {
                    data: null,
                    render: function(data, type, row) {
                        let buttons = '';
                        if (row.status === 'failed') {
                            buttons += `<button class="btn btn-sm btn-danger action-btn view-error" data-error="${row.error_message}">
                                <i class="ti ti-alert-circle"></i>
                            </button>`;
                        }
                        if (row.status === 'pending') {
                            buttons += `<button class="btn btn-sm btn-primary action-btn resend-message" data-id="${row.id}">
                                <i class="ti ti-send"></i>
                            </button>`;
                        }
                        return buttons;
                    }
                }
            ],
            order: [[5, 'desc']], // Sort by sent_at by default
            language: {
                url: '//cdn.datatables.net/plug-ins/1.10.24/i18n/Indonesian.json'
            }
        });

        // Filter handlers
        $('#statusFilter').on('change', function() {
            filters.status = $(this).val();
            table.ajax.reload();
        });

        $('#dateFilter').on('change', function() {
            filters.date = $(this).val();
            table.ajax.reload();
        });

        $('#searchNIK').on('keyup', function() {
            filters.nik = $(this).val();
            table.ajax.reload();
        });

        $('#searchPhone').on('keyup', function() {
            filters.phone = $(this).val();
            table.ajax.reload();
        });

        // Refresh button handler
        $('#refreshBtn').on('click', function() {
            table.ajax.reload();
        });

        // View error modal handler
        $(document).on('click', '.view-error', function() {
            const errorMessage = $(this).data('error');
            $('#errorMessage').text(errorMessage);
            $('#errorModal').modal('show');
        });

        // Handle resend message
        $(document).on('click', '.btn-resend', function() {
            const messageId = $(this).data('id');
            const $btn = $(this);

            // Show confirmation dialog
            if (!confirm('Apakah Anda yakin ingin mengirim ulang pesan ini?')) {
                return;
            }

            // Disable button and show loading state
            $btn.prop('disabled', true)
                .html('<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Mengirim...');

            // Send request
            $.ajax({
                url: `{{ url('wa-message/resend') }}/${messageId}`,
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                success: function(response) {
                    if (response.success) {
                        // Show success message
                        toastr.success(response.message);
                        // Refresh table
                        table.ajax.reload();
                    } else {
                        // Show error message
                        toastr.error(response.message);
                        // Reset button state
                        $btn.prop('disabled', false)
                            .html('<i class="ti ti-refresh"></i> Kirim Ulang');
                    }
                },
                error: function(xhr) {
                    // Show error message
                    const message = xhr.responseJSON?.message || 'Terjadi kesalahan saat mengirim ulang pesan';
                    toastr.error(message);
                    // Reset button state
                    $btn.prop('disabled', false)
                        .html('<i class="ti ti-refresh"></i> Kirim Ulang');
                }
            });
        });

        // Auto refresh every 30 seconds
        setInterval(function() {
            table.ajax.reload(null, false); // false means don't reset paging
        }, 30000);
    });
</script>
@endpush
