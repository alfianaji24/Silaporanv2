@extends('layouts.mobile.app')
@section('content')
    {{-- <style>
        :root {
            --bg-body: #dff9fb;
            --bg-nav: #ffffff;
            --color-nav: #32745e;
            --color-nav-active: #58907D;
            --bg-indicator: #32745e;
            --color-nav-hover: #3ab58c;
        }
    </style> --}}
    <style>
        .webcam-capture {
            display: inline-block;
            width: 100% !important;
            margin: auto;
            height: 350px !important;
            border-radius: 15px;
            overflow: hidden;
        }

        .webcam-capture video {
            display: inline-block;
            width: 100% !important;
            margin: auto;
            height: auto !important;
            border-radius: 15px;

        }

        #map {
            height: 200px;
        }
    </style>
    <style>
        .jam-digital-malasngoding {

            background-color: #27272783;
            position: absolute;
            top: 60px;
            right: 5px;
            z-index: 9999;
            width: 150px;
            border-radius: 10px;
            padding: 5px;
        }



        .jam-digital-malasngoding p {
            color: #fff;
            font-size: 16px;
            text-align: left;
            margin-top: 0;
            margin-bottom: 0;
        }
    </style>
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.3/dist/leaflet.css" />
    <script src="https://unpkg.com/leaflet@1.9.3/dist/leaflet.js"></script>
    <div id="header-section">
        <div class="appHeader bg-primary text-light">
            <div class="left">
                <a href="javascript:;" class="headerButton goBack">
                    <ion-icon name="chevron-back-outline"></ion-icon>
                </a>
            </div>
            <div class="pageTitle">E-Presensi</div>
            <div class="right"></div>
        </div>
    </div>
    <div id="content-section">
        <div class="row" style="margin-top: 60px">
            <div class="col">
                <div class="webcam-capture"></div>
            </div>
        </div>
        <div class="jam-digital-malasngoding">
            <p>{{ DateToIndo(date('Y-m-d')) }}</p>
            <p id="jam"></p>
            <p>{{ $jam_kerja->nama_jam_kerja }} </p>
            <p style="display: flex; justify-content:space-between">
                <span> Masuk</span>
                <span>{{ date('H:i', strtotime($jam_kerja->jam_masuk)) }}</span>
            </p>
            <p style="display: flex; justify-content:space-between">
                <span> Pulang</span>
                <span>{{ date('H:i', strtotime($jam_kerja->jam_pulang)) }}</span>
            </p>
        </div>
        <div class="row">
            <div class="col">
                <div id="map"></div>
            </div>
        </div>
        <div class="row mt-2">
            <div class="col d-flex justify-content-between">
                <button class="btn btn-success  bg-primary" id="absenmasuk" statuspresensi="masuk" style="height: 100px !important">
                    <ion-icon name="finger-print-outline" style="font-size: 32px !important"></ion-icon>
                    <span style="font-size:16px">Scan Masuk</span>
                </button>
                <button class="btn btn-danger" id="absenpulang" statuspresensi="pulang" style="height: 100px !important">
                    <ion-icon name="finger-print-outline" style="font-size: 32px !important"></ion-icon>
                    <span style="font-size:16px">Scan Pulang</span>
                </button>
            </div>
        </div>
    </div>
    <audio id="notifikasi_radius">
        <source src="{{ asset('assets/sound/radius.mp3') }}" type="audio/mpeg">
    </audio>
    <audio id="notifikasi_mulaiabsen">
        <source src="{{ asset('assets/sound/mulaiabsen.wav') }}" type="audio/mpeg">
    </audio>
    <audio id="notifikasi_akhirabsen">
        <source src="{{ asset('assets/sound/akhirabsen.wav') }}" type="audio/mpeg">
    </audio>
    <audio id="notifikasi_sudahabsen">
        <source src="{{ asset('assets/sound/sudahabsen.wav') }}" type="audio/mpeg">
    </audio>
    <audio id="notifikasi_absenmasuk">
        <source src="{{ asset('assets/sound/absenmasuk.wav') }}" type="audio/mpeg">
    </audio>


    <!--Pulang-->
    <audio id="notifikasi_sudahabsenpulang">
        <source src="{{ asset('assets/sound/sudahabsenpulang.mp3') }}" type="audio/mpeg">
    </audio>
    <audio id="notifikasi_absenpulang">
        <source src="{{ asset('assets/sound/absenpulang.mp3') }}" type="audio/mpeg">
    </audio>
@endsection
@push('myscript')
    <script type="text/javascript">
        window.onload = function() {
            jam();
        }

        function jam() {
            var e = document.getElementById('jam'),
                d = new Date(),
                h, m, s;
            h = d.getHours();
            m = set(d.getMinutes());
            s = set(d.getSeconds());

            e.innerHTML = h + ':' + m + ':' + s;

            setTimeout('jam()', 1000);
        }

        function set(e) {
            e = e < 10 ? '0' + e : e;
            return e;
        }
    </script>
    <script>
        $(function() {
            let lokasi;
            let notifikasi_radius = document.getElementById('notifikasi_radius');
            let notifikasi_mulaiabsen = document.getElementById('notifikasi_mulaiabsen');
            let notifikasi_akhirabsen = document.getElementById('notifikasi_akhirabsen');
            let notifikasi_sudahabsen = document.getElementById('notifikasi_sudahabsen');
            let notifikasi_absenmasuk = document.getElementById('notifikasi_absenmasuk');

            let notifikasi_sudahabsenpulang = document.getElementById('notifikasi_sudahabsenpulang');
            let notifikasi_absenpulang = document.getElementById('notifikasi_absenpulang');


            Webcam.set({
                height: 480,
                width: 640,
                image_format: 'jpeg',
                jpeg_quality: 80
            });

            Webcam.attach('.webcam-capture');

            //Tampilkan Map
            if (navigator.geolocation) {
                navigator.geolocation.getCurrentPosition(successCallback, errorCallback);
            }

            function successCallback(position) {
                // lokasi.value = position.coords.latitude + "," + position.coords.longitude;
                var map = L.map('map').setView([position.coords.latitude, position.coords.longitude], 18);
                var lokasi_kantor = "{{ $lokasi_kantor->lokasi_cabang }}";
                lokasi = lokasi_kantor;
                var lok = lokasi_kantor.split(",");
                var lat_kantor = lok[0];
                var long_kantor = lok[1];
                var radius = "{{ $lokasi_kantor->radius_cabang }}";
                L.tileLayer('https://tile.openstreetmap.org/{z}/{x}/{y}.png', {
                    maxZoom: 19,
                    attribution: '&copy; <a href="http://www.openstreetmap.org/copyright">OpenStreetMap</a>'
                }).addTo(map);
                var marker = L.marker([position.coords.latitude, position.coords.longitude]).addTo(map);
                var circle = L.circle([lat_kantor, long_kantor], {
                    color: 'red',
                    fillColor: '#f03',
                    fillOpacity: 0.5,
                    radius: radius
                }).addTo(map);

                setInterval(function() {
                    map.invalidateSize();
                }, 100);
            }

            function errorCallback() {

            }

            $("#absenmasuk").click(function() {
                // alert(lokasi);
                $("#absenmasuk").prop('disabled', true);
                $("#absenpulang").prop('disabled', true);
                $("#absenmasuk").html(
                    '<div class="spinner-border text-light mr-2" role="status"><span class="sr-only">Loading...</span></div> <span style="font-size:16px">Loading...</span>'

                );
                let status = '1';
                Webcam.snap(function(uri) {
                    image = uri;
                });
                $.ajax({
                    type: 'POST',
                    url: "{{ route('presensi.store') }}",
                    data: {
                        _token: "{{ csrf_token() }}",
                        image: image,
                        status: status,
                        lokasi: lokasi,
                        kode_jam_kerja: "{{ $jam_kerja->kode_jam_kerja }}"
                    },
                    success: function(data) {
                        if (data.status == true) {
                            notifikasi_absenmasuk.play();
                            swal.fire({
                                icon: 'success',
                                title: 'Berhasil',
                                text: data.message,
                                showConfirmButton: false,
                                timer: 4000
                            }).then(function() {
                                window.location.href = '/dashboard';
                            });
                        }
                    },
                    error: function(xhr) {
                        if (xhr.responseJSON.notifikasi == "notifikasi_radius") {
                            notifikasi_radius.play();
                        } else if (xhr.responseJSON.notifikasi == "notifikasi_mulaiabsen") {
                            notifikasi_mulaiabsen.play();
                        } else if (xhr.responseJSON.notifikasi == "notifikasi_akhirabsen") {
                            notifikasi_akhirabsen.play();
                        } else if (xhr.responseJSON.notifikasi == "notifikasi_sudahabsen") {
                            notifikasi_sudahabsen.play();
                        }
                        swal.fire({
                            icon: 'error',
                            title: 'Oops...',
                            text: xhr.responseJSON.message,
                            didClose: function() {
                                $("#absenmasuk").prop('disabled', false);
                                $("#absenpulang").prop('disabled', false);
                                $("#absenmasuk").html(
                                    '<ion-icon name="finger-print-outline" style="font-size: 32px !important"></ion-icon><span style="font-size:16px">Scan Masuk</span>'
                                );
                            }

                        });
                    }
                });
            });

            $("#absenpulang").click(function() {
                // alert(lokasi);
                $("#absenmasuk").prop('disabled', true);
                $("#absenpulang").prop('disabled', true);
                $("#absenpulang").html(
                    '<div class="spinner-border text-light mr-2" role="status"><span class="sr-only">Loading...</span></div> <span style="font-size:16px">Loading...</span>'

                );
                let status = '2';
                Webcam.snap(function(uri) {
                    image = uri;
                });
                $.ajax({
                    type: 'POST',
                    url: "{{ route('presensi.store') }}",
                    data: {
                        _token: "{{ csrf_token() }}",
                        image: image,
                        status: status,
                        lokasi: lokasi,
                        kode_jam_kerja: "{{ $jam_kerja->kode_jam_kerja }}"
                    },
                    success: function(data) {
                        if (data.status == true) {
                            notifikasi_absenpulang.play();
                            swal.fire({
                                icon: 'success',
                                title: 'Berhasil',
                                text: data.message,
                                showConfirmButton: false,
                                timer: 4000
                            }).then(function() {
                                window.location.href = '/dashboard';
                            });
                        }
                    },
                    error: function(xhr) {
                        if (xhr.responseJSON.notifikasi == "notifikasi_radius") {
                            notifikasi_radius.play();
                        } else if (xhr.responseJSON.notifikasi == "notifikasi_mulaiabsen") {
                            notifikasi_mulaiabsen.play();
                        } else if (xhr.responseJSON.notifikasi == "notifikasi_akhirabsen") {
                            notifikasi_akhirabsen.play();
                        } else if (xhr.responseJSON.notifikasi == "notifikasi_sudahabsen") {
                            notifikasi_sudahabsenpulang.play();
                        }
                        swal.fire({
                            icon: 'error',
                            title: 'Oops...',
                            text: xhr.responseJSON.message,
                            didClose: function() {
                                $("#absenmasuk").prop('disabled', false);
                                $("#absenpulang").prop('disabled', false);
                                $("#absenpulang").html(
                                    '<ion-icon name="finger-print-outline" style="font-size: 32px !important"></ion-icon><span style="font-size:16px">Scan Pulang</span>'
                                );
                            }

                        });
                    }
                });
            });
        });
    </script>
@endpush
