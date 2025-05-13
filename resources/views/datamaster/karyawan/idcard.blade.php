@extends('layouts.mobile.app')
@section('content')
<style>
    body {
        background: #f1f2f6;
    }

    .idcard-container {
        width: 300px;
        height: 500px;
        margin: 50px auto;
        background: #fff;
        border: 2px solid #000;
        border-radius: 10px;
        overflow: hidden;
        font-family: 'Arial', sans-serif;
        text-align: center;
        position: relative;
    }

    .idcard-header {
        padding: 10px;
    }

    .idcard-header img.logo {
        width: 60px;
        margin-bottom: 6px;
    }

    .idcard-header .title {
        font-size: 14px;
        font-weight: bold;
        color: #000;
        line-height: 1.3;
    }

    .photo-section {
        margin-top: 8px;
    }

    .photo-section img {
        width: 110px;
        height: 140px;
        object-fit: cover;
        border: 1px solid #000;
        background: #ccc;
    }

    .info-section {
        margin-top: 10px;
        font-size: 14px;
        color: #000;
    }

    .info-section .name {
        font-weight: bold;
        font-size: 16px;
        margin-top: 8px;
    }

    .footer-section {
        position: absolute;
        bottom: 10px;
        width: 100%;
        font-size: 12px;
        color: #000;
    }
</style>

<div class="idcard-container" id="idcard-area">
    <div class="idcard-header">
        <img src="{{ asset('assets\template\img\logo_kabtgr.png') }}" class="logo" alt="Logo">
        <div class="title">
            PEMERINTAH<br>
            KABUPATEN TANGERANG<br>
            DINAS KESEHATAN<br>
            <span style="color: #3498db;">UPTD PUSKESMAS BALARAJA</span>
        </div>
    </div>

    <div class="photo-section">
        @if (!empty($karyawan->foto) && Storage::disk('public')->exists('/karyawan/' . $karyawan->foto))
            <img src="{{ getfotoKaryawan($karyawan->foto) }}" alt="Foto">
        @else
            <img src="{{ asset('assets/template/img/sample/avatar/avatar1.jpg') }}" alt="Foto">
        @endif
    </div>

    <div class="info-section">
        <div class="name">{{ strtoupper($karyawan->nama_karyawan) }}</div>
        <div>{{ $karyawan->nama_jabatan }}</div>
    </div>
</div>

<div style="text-align:center; margin-top: 24px;">
    <button id="download-idcard" class="btn btn-success">
        <i class="fa-solid fa-download"></i> Download JPG
    </button>
</div>

<script src="https://cdnjs.cloudflare.com/ajax/libs/html2canvas/1.4.1/html2canvas.min.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const btn = document.getElementById('download-idcard');
        if (btn) {
            btn.addEventListener('click', function () {
                const area = document.getElementById('idcard-area');
                if (!area) {
                    alert('ID Card tidak ditemukan!');
                    return;
                }

                html2canvas(area, { backgroundColor: null, scale: 2 }).then(function (canvas) {
                    const link = document.createElement('a');
                    link.download = 'idcard-{{ $karyawan->nik }}.jpg';
                    link.href = canvas.toDataURL('image/jpeg', 0.95);
                    link.click();
                }).catch(function (e) {
                    alert('Gagal membuat gambar: ' + e);
                });
            });
        }
    });
</script>
@endsection
