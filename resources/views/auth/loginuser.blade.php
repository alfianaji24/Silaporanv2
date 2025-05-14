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
</head>

<body class="bg-gray-100 min-h-screen flex items-center justify-center p-4">
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

        <input type="password" name="password" placeholder="Password"
          class="w-full border border-gray-300 px-4 py-2 rounded focus:outline-none focus:ring-2 focus:ring-primary"
          required minlength="4" />

        <div class="flex items-center text-sm text-gray-600">
          <input type="checkbox" name="remember" id="remember" class="mr-2 h-4 w-4">
          <label for="remember" class="cursor-pointer">Remember Me</label>
        </div>

        <button type="submit"
          class="w-full bg-primary text-white py-2 rounded hover:bg-blue-700 transition">Sign In</button>

          <div class="mt-6">
  <a href="{{ route('google.login') }}"
    class="flex items-center justify-center bg-red-500 text-white py-2 rounded hover:bg-red-600 transition">
    <svg class="w-5 h-5 mr-2" viewBox="0 0 48 48"><path fill="#fff" d="M44.5 20H24v8.5h11.8C33.2 33.4 29 36.5 24 36.5c-7.2 0-13-5.8-13-13s5.8-13 13-13c3.1 0 5.9 1.1 8.1 3l6-6C34.5 3.8 29.6 2 24 2 12.4 2 3.5 10.9 3.5 22.5S12.4 43 24 43c11.3 0 20.5-9.2 20.5-20.5 0-1.5-.2-2.9-.5-4.5z"/></svg>
    Sign in with Google
  </a>
</div>

        <p class="text-center text-sm text-gray-600">
          Forgot your password or login details?
          <a href="#" class="text-primary hover:underline">Get help</a> signing in.
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
