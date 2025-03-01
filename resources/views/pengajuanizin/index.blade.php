@extends('layouts.mobile.app')
@section('content')
    <style>
        .avatar {
            position: relative;
            width: 2.5rem;
            height: 2.5rem;
            cursor: pointer;
        }


        .avatar-sm {
            width: 2rem;
            height: 2rem;
        }

        .avatar-sm .avatar-initial {
            font-size: .8125rem;
        }

        .avatar .avatar-initial {
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            text-transform: uppercase;
            display: flex;
            align-items: center;
            justify-content: center;
            color: #fff;
            background-color: #eeedf0;
            font-size: .9375rem;
        }

        .rounded-circle {
            border-radius: 50% !important;
        }
    </style>
    <div id="header-section">
        <div class="appHeader bg-primary text-light">
            <div class="left">
                <a href="{{ route('dashboard.index') }}" class="headerButton goBack">
                    <ion-icon name="chevron-back-outline"></ion-icon>
                </a>
            </div>
            <div class="pageTitle">Pengajuan Izin</div>
            <div class="right"></div>
        </div>
    </div>
    <div id="content-section" style="margin-top: 70px">
        <div class="row">
            <div class="col">
                <div class="transactions">
                    @foreach ($pengajuan_izin as $d)
                        <a href="#" class="item">
                            <div class="detail">
                                <div class="avatar avatar-sm me-4"><span class="avatar-initial rounded-circle bg-success">
                                        {{ textUpperCase($d->ket) }}
                                    </span></div>
                                <div>
                                    <strong>
                                        @php
                                            if ($d->ket == 'i') {
                                                $ket = 'Izin Absen';
                                            } elseif ($d->ket == 's') {
                                                $ket = 'Izin Sakit';
                                            } elseif ($d->ket == 'c') {
                                                $ket = 'Izin Cuti';
                                            }
                                        @endphp
                                        {{ $ket }}
                                    </strong>
                                    <p>{{ DateToIndo($d->dari) }} - {{ DateToIndo($d->sampai) }}</p>
                                    <p>{{ $d->keterangan }}</p>
                                </div>
                            </div>
                            <div class="right">
                                <div class="price">
                                    @if ($d->status_izin == '0')
                                        <span class="badge bg-warning">Pending</span>
                                    @elseif ($d->status_izin == '1')
                                        <span class="badge bg-success">Disetujui</span>
                                    @elseif ($d->status_izin == '2')
                                        <span class="badge bg-danger">Ditolak</span>
                                    @endif
                                </div>
                                <div class="status">

                                </div>
                            </div>
                        </a>
                    @endforeach
                </div>
            </div>
        </div>
        <div class="fab-button animate bottom-right dropdown" style="margin-bottom:70px">
            <a href="#" class="fab bg-primary" data-toggle="dropdown">
                <ion-icon name="add-outline" role="img" class="md hydrated" aria-label="add outline"></ion-icon>
            </a>
            <div class="dropdown-menu">
                <a class="dropdown-item bg-primary" href="{{ route('izinabsen.create') }}">
                    <ion-icon name="document-outline" role="img" class="md hydrated" aria-label="image outline"></ion-icon>
                    <p>Izin Absen</p>
                </a>

                <a class="dropdown-item bg-primary" href="{{ route('izinsakit.create') }}">
                    <ion-icon name="document-outline" role="img" class="md hydrated" aria-label="videocam outline"></ion-icon>
                    <p>Sakit</p>
                </a>
                <a class="dropdown-item bg-primary" href="{{ route('izincuti.create') }}">
                    <ion-icon name="document-outline" role="img" class="md hydrated" aria-label="videocam outline"></ion-icon>
                    <p>Cuti</p>
                </a>
            </div>
        </div>
    </div>
@endsection
