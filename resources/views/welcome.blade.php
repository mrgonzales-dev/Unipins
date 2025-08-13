<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="light">
<head>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>Unipins - University Merch Platform</title>

  <!-- Fonts -->
  <link rel="preconnect" href="https://fonts.bunny.net" />
  <link href="https://fonts.bunny.net/css?family=instrument-sans:400,500,600" rel="stylesheet" />

  <!-- Styles and Scripts -->
  @vite(['resources/css/app.css', 'resources/js/app.js'])

  <style>
    /* Smooth color mode transition */
    html {
      transition: background-color 0.3s ease, color 0.3s ease;
    }
  </style>
</head>
<body class="bg-white text-gray-900 dark:bg-[#121212] dark:text-gray-100 font-sans antialiased">

  <!-- Navbar -->
  <nav class="sticky top-0 z-50 bg-white dark:bg-[#121212] border-b border-gray-200 dark:border-gray-700 shadow-sm transition-colors duration-300">
    <div class="max-w-7xl mx-auto px-6 lg:px-12 flex items-center justify-between h-16">
      <!-- Logo -->
      <a href="/" class="text-2xl font-extrabold tracking-wide text-indigo-600 hover:text-indigo-500 dark:text-indigo-400 dark:hover:text-indigo-300 transition">
        Unipins
      </a>

      <!-- Auth Links -->
      <div class="space-x-6 text-sm font-medium">
        @if (Route::has('login'))
          @auth
            <a href="{{ url('/dashboard') }}" class="text-indigo-600 hover:text-indigo-500 dark:text-indigo-400 dark:hover:text-indigo-300 transition">
              Dashboard
            </a>
          @else
            <a href="{{ route('login') }}" class="text-gray-700 hover:text-indigo-600 dark:text-gray-300 dark:hover:text-indigo-400 transition">
              Log in
            </a>
            @if (Route::has('register'))
              <a href="{{ route('register') }}" class="px-4 py-2 border border-indigo-600 rounded-md text-indigo-600 hover:bg-indigo-600 hover:text-white dark:hover:bg-indigo-400 dark:hover:text-gray-900 transition">
                Register
              </a>
            @endif
          @endauth
        @endif
      </div>
    </div>
  </nav>

  <!-- Hero Section -->
  <section class="flex flex-col items-center text-center px-6 py-20 max-w-4xl mx-auto space-y-6">
    <h1 class="text-5xl font-extrabold tracking-tight leading-tight max-w-3xl">
      <span class="text-indigo-600 dark:text-indigo-400">Unipins</span> — Your Official University Merch Platform
    </h1>
    <p class="text-lg max-w-xl text-gray-700 dark:text-gray-300">
      Seamlessly browse, order, and track your favorite university merchandise with real-time updates and hassle-free payments.
    </p>
    <div class="flex space-x-4">
      <a href="{{ route('register') }}" class="px-8 py-3 bg-indigo-600 rounded-md text-white font-semibold hover:bg-indigo-700 transition">
        Get Started
      </a>
      <a href="{{ route('login') }}" class="px-8 py-3 border border-indigo-600 rounded-md text-indigo-600 font-semibold hover:bg-indigo-600 hover:text-white transition">
        Log In
      </a>
    </div>
  </section>

  <!-- Features Section -->
  <section class="bg-gray-50 dark:bg-[#1e1e1e] py-16">
    <div class="max-w-6xl mx-auto px-6 lg:px-12 grid grid-cols-1 md:grid-cols-3 gap-10 text-center">

      <div class="space-y-4">
        <div class="mx-auto w-16 h-16 flex items-center justify-center rounded-full bg-indigo-100 dark:bg-indigo-900 text-indigo-600 dark:text-indigo-400 text-3xl">
          📦
        </div>
        <h3 class="text-xl font-semibold">Official University Stores</h3>
        <p class="text-gray-600 dark:text-gray-400 max-w-xs mx-auto">
          Authentic merchandise directly from your university’s official stores.
        </p>
      </div>

      <div class="space-y-4">
        <div class="mx-auto w-16 h-16 flex items-center justify-center rounded-full bg-indigo-100 dark:bg-indigo-900 text-indigo-600 dark:text-indigo-400 text-3xl">
          🧾
        </div>
        <h3 class="text-xl font-semibold">Real-time Tracking</h3>
        <p class="text-gray-600 dark:text-gray-400 max-w-xs mx-auto">
          Track your orders live with detailed logs and receipts for every transaction.
        </p>
      </div>

      <div class="space-y-4">
        <div class="mx-auto w-16 h-16 flex items-center justify-center rounded-full bg-indigo-100 dark:bg-indigo-900 text-indigo-600 dark:text-indigo-400 text-3xl">
          💸
        </div>
        <h3 class="text-xl font-semibold">Hassle-Free Payments</h3>
        <p class="text-gray-600 dark:text-gray-400 max-w-xs mx-auto">
          Secure and simple payment options, no more messy manual transfers.
        </p>
      </div>

    </div>
  </section>

  <!-- Footer -->
  <footer class="text-center py-8 text-sm text-gray-500 dark:text-gray-400 border-t border-gray-200 dark:border-gray-700 transition-colors duration-300">
    &copy; {{ date('Y') }} Unipins. All rights reserved.
  </footer>

</body>
</html>
