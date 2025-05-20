<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>LOGIN | Silaporan Puskesmas Balaraja</title>

  <!-- Tailwind CDN -->
  <script src="https://cdn.tailwindcss.com"></script>

  <!-- Optional custom config -->
  <script>
    tailwind.config = {
      theme: {
        extend: {
          colors: {
            primary: '#1e40af',
          }
        }
      }
    }
  </script>

  <style>
    .animated-background {
      position: fixed;
      top: 0;
      left: 0;
      width: 100%;
      height: 100%;
      z-index: -1;
      background: #1e40af;
      overflow: hidden;
    }

    .wave {
      position: absolute;
      width: 200%;
      height: 200%;
      background: rgba(255, 255, 255, 0.1);
      border-radius: 40%;
      transform-origin: 50% 48%;
      animation: wave 12s infinite linear;
    }

    .wave:nth-child(2) {
      background: rgba(255, 255, 255, 0.15);
      animation: wave 16s infinite linear;
    }

    .wave:nth-child(3) {
      background: rgba(255, 255, 255, 0.05);
      animation: wave 20s infinite linear;
    }

    .floating-particles {
      position: absolute;
      width: 100%;
      height: 100%;
      background: radial-gradient(circle at center, rgba(255,255,255,0.1) 2px, transparent 3px);
      background-size: 50px 50px;
      animation: float 8s infinite linear;
    }

    @keyframes wave {
      from {
        transform: rotate(0deg);
      }
      to {
        transform: rotate(360deg);
      }
    }

    @keyframes float {
      0% {
        transform: translateY(0);
      }
      50% {
        transform: translateY(-20px);
      }
      100% {
        transform: translateY(0);
      }
    }
  </style>
</head>

<body class="bg-gray-100 min-h-screen flex items-center justify-center p-4">
  <div class="animated-background">
    <div class="wave"></div>
    <div class="wave"></div>
    <div class="wave"></div>
    <div class="floating-particles"></div>
  </div>

  <main class="w-full max-w-5xl bg-white shadow-md rounded-lg flex flex-col md:flex-row overflow-hidden">

    <!-- Form Section -->
    <div class="w-full md:w-1/2 p-8">
      <div class="flex flex-col items-center mb-6">
        <img src="{{ asset('assets/img/logo/logo_silaporan.png') }}" alt="Logo" class="w-20 h-20 mb-2">
        <h4 class="text-xl font-semibold text-gray-700">SILAPORAN</h4>
      </div>

      <h2 class="text-2xl font-bold text-center text-gray-800 mb-4">Welcome Back</h2>

      @if (session('error'))
        <div class="bg-red-100 border border-red-400 text-red-700 p-3 rounded mb-4">
          {{ session('error') }}
        </div>
      @endif

      @if ($errors->any())
        <div class="bg-red-100 border border-red-400 text-red-700 p-3 rounded mb-4">
          @foreach ($errors->all() as $error)
            <p>{{ $error }}</p>
          @endforeach
        </div>
      @endif

      <form action="{{ route('login') }}" method="POST" class="space-y-4">
        @csrf
        <input type="text" name="id_user" placeholder="Username / Email" value="{{ old('id_user') }}"
          class="w-full border border-gray-300 px-4 py-2 rounded focus:outline-none focus:ring-2 focus:ring-primary"
          required minlength="4" />

        <div class="relative">
          <input type="password" name="password" id="password" placeholder="Password"
            class="w-full border border-gray-300 px-4 py-2 rounded focus:outline-none focus:ring-2 focus:ring-primary"
            required minlength="4" />
          <button type="button" id="togglePassword" class="absolute right-3 top-1/2 transform -translate-y-1/2 text-gray-500 hover:text-gray-700">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
            </svg>
          </button>
        </div>

        <div class="flex items-center text-sm text-gray-600">
          <input type="checkbox" name="remember" id="remember" class="mr-2 h-4 w-4">
          <label for="remember" class="cursor-pointer">Remember Me</label>
        </div>

        <button type="submit"class="w-full bg-primary text-white py-2 rounded hover:bg-blue-700 transition">Sign In</button>

        <!-- bug jika sehabis login menggunakan google makan password selalu salah !!! -->
        <!-- <div class="mt-6">
            <a href="{{ route('google.login') }}" class="flex items-center justify-center bg-red-500 text-white py-2 rounded hover:bg-red-600 transition">
            <svg class="w-5 h-5 mr-2" viewBox="0 0 48 48"><path fill="#fff" d="M44.5 20H24v8.5h11.8C33.2 33.4 29 36.5 24 36.5c-7.2 0-13-5.8-13-13s5.8-13 13-13c3.1 0 5.9 1.1 8.1 3l6-6C34.5 3.8 29.6 2 24 2 12.4 2 3.5 10.9 3.5 22.5S12.4 43 24 43c11.3 0 20.5-9.2 20.5-20.5 0-1.5-.2-2.9-.5-4.5z"/></svg>
            Sign in with Google</a>
        </div> -->
        <p class="text-center text-sm text-gray-600">
          Forgot your password or login details?
          <a href="" class="text-primary hover:underline">Get help</a> signing in.
        </p>
      </form>
    </div>

    <!-- Image Slider Section -->
    <div class="w-full md:w-1/2 relative">
      <div class="relative w-full h-64 md:h-full overflow-hidden">
        <div id="slider" class="flex transition-transform duration-500 h-full">
          <img src="{{ asset('assets/login/slider/image1.png') }}" class="w-full object-cover" alt="Slide 1">
          <img src="{{ asset('assets/login/slider/image2.png') }}" class="w-full object-cover" alt="Slide 2">
          <img src="{{ asset('assets/login/slider/image3.png') }}" class="w-full object-cover" alt="Slide 3">
        </div>
      </div>

      <!-- Bullets -->
      <div class="absolute bottom-4 left-1/2 transform -translate-x-1/2 flex gap-2">
        <span class="bullet w-3 h-3 bg-white rounded-full opacity-70 cursor-pointer" data-index="0"></span>
        <span class="bullet w-3 h-3 bg-white rounded-full opacity-70 cursor-pointer" data-index="1"></span>
        <span class="bullet w-3 h-3 bg-white rounded-full opacity-70 cursor-pointer" data-index="2"></span>
      </div>
    </div>
  </main>

  <!-- Slider Script -->
  <script>
    // Password toggle functionality
    const togglePassword = document.getElementById('togglePassword');
    const password = document.getElementById('password');

    togglePassword.addEventListener('click', function() {
      const type = password.getAttribute('type') === 'password' ? 'text' : 'password';
      password.setAttribute('type', type);

      // Toggle eye icon - correct logic based on standard UX
      this.innerHTML = type === 'password'
        ? `<svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
           </svg>`
        : `<svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21" />
           </svg>`;
    });
    const slider = document.getElementById('slider');
    const bullets = document.querySelectorAll('.bullet');
    let index = 0;

    function showSlide(i) {
      slider.style.transform = `translateX(-${i * 100}%)`;
      bullets.forEach(b => b.classList.remove('bg-primary', 'opacity-100'));
      bullets[i].classList.add('bg-primary', 'opacity-100');
    }

    bullets.forEach((b, i) => {
      b.addEventListener('click', () => {
        index = i;
        showSlide(index);
      });
    });

    setInterval(() => {
      index = (index + 1) % bullets.length;
      showSlide(index);
    }, 4000);
  </script>
</body>

</html>
