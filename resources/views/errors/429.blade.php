<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Error 429 - Terlalu Banyak Permintaan</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://unpkg.com/aos@2.3.1/dist/aos.css" rel="stylesheet">
    <script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Inter', sans-serif;
            background: linear-gradient(135deg, #dc2626 0%, #f97316 100%);
            min-height: 100vh;
            position: relative;
            overflow: hidden;
        }

        .glass-effect {
            background: rgba(255, 255, 255, 0.85);
            backdrop-filter: blur(10px);
            border: 1px solid rgba(255, 255, 255, 0.2);
        }

        .floating {
            animation: floating 3s ease-in-out infinite;
        }

        @keyframes floating {
            0% { transform: translateY(0px); }
            50% { transform: translateY(-20px); }
            100% { transform: translateY(0px); }
        }

        .bg-pattern {
            background-image: url("data:image/svg+xml,%3Csvg width='60' height='60' viewBox='0 0 60 60' xmlns='http://www.w3.org/2000/svg'%3E%3Cg fill='none' fill-rule='evenodd'%3E%3Cg fill='%23ffffff' fill-opacity='0.05'%3E%3Cpath d='M36 34v-4h-2v4h-4v2h4v4h2v-4h4v-2h-4zm0-30V0h-2v4h-4v2h4v4h2V6h4V4h-4zM6 34v-4H4v4H0v2h4v4h2v-4h4v-2H6zM6 4V0H4v4H0v2h4v4h2V6h4V4H6z'/%3E%3C/g%3E%3C/g%3E%3C/svg%3E");
        }
    </style>
</head>

<body class="flex items-center justify-center p-4 bg-pattern">
    <div class="absolute top-0 left-0 w-full h-full overflow-hidden pointer-events-none">
        <div class="absolute top-10 left-10 w-20 h-20 bg-[#dc2626] opacity-20 rounded-full"></div>
        <div class="absolute bottom-10 right-10 w-32 h-32 bg-[#f97316] opacity-20 rounded-full"></div>
        <div class="absolute top-1/2 left-1/2 transform -translate-x-1/2 -translate-y-1/2 w-64 h-64 bg-[#dc2626] opacity-10 rounded-full"></div>
    </div>

    <div class="w-full max-w-md relative z-10">
        <div class="glass-effect rounded-2xl p-8 shadow-xl" data-aos="fade-up" data-aos-duration="1000">
            <div class="text-center">
                <div class="mb-6 floating" data-aos="zoom-in" data-aos-delay="200">
                    <img src="https://cdni.iconscout.com/illustration/premium/thumb/too-many-requests-8691017-6981263.png"
                         alt="429 Too Many Requests" class="w-48 h-48 mx-auto">
                </div>

                <h1 class="text-3xl font-bold text-[#dc2626] mb-4" data-aos="fade-up" data-aos-delay="400">
                    429 - Terlalu Banyak Permintaan
                </h1>

                <p class="text-gray-600 mb-8" data-aos="fade-up" data-aos-delay="600">
                    Anda telah mengirim terlalu banyak permintaan dalam waktu singkat. Mohon tunggu sebentar sebelum mencoba lagi.
                </p>

                <a href="{{ url()->current() }}"
                   class="inline-flex items-center px-6 py-3 bg-gradient-to-r from-[#dc2626] to-[#f97316] text-white font-semibold rounded-lg shadow-lg hover:from-[#f97316] hover:to-[#dc2626] transition duration-300 ease-in-out transform hover:scale-105"
                   data-aos="fade-up" data-aos-delay="800">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="M12 4v16m8-8H4" />
                    </svg>
                    Coba Lagi
                </a>
            </div>
        </div>
    </div>

    <script>
        AOS.init({ once: true, offset: 50 });
    </script>
</body>

</html>
