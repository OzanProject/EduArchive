<header class="sticky top-0 z-50 w-full bg-white/80 backdrop-blur-md border-b border-[#e7ebf3] px-6 md:px-20 py-4">
  <div class="max-w-7xl mx-auto flex items-center justify-between">
    <div class="flex items-center gap-3">
      @if(isset($app_settings['app_logo']))
        <img src="{{ asset($app_settings['app_logo']) }}" alt="Logo" class="h-10 w-auto rounded-lg">
      @else
        <div class="bg-primary p-1.5 rounded-lg text-white">
          <span class="material-symbols-outlined block">folder_zip</span>
        </div>
      @endif
      <h2 class="text-[#0d121b] text-xl font-bold tracking-tight">{{ $settings['app_name'] ?? 'EduArchive' }}</h2>
    </div>
    <nav class="hidden md:flex items-center gap-8">
      <a class="text-sm font-semibold hover:text-primary transition-colors {{ Route::is('features') ? 'text-primary' : '' }}"
        href="{{ route('features') }}">Fitur</a>
      <a class="text-sm font-semibold hover:text-primary transition-colors {{ Route::is('architecture') ? 'text-primary' : '' }}"
        href="{{ route('architecture') }}">Arsitektur</a>
      <a class="text-sm font-semibold hover:text-primary transition-colors {{ Route::is('security') ? 'text-primary' : '' }}"
        href="{{ route('security') }}">Keamanan</a>
    </nav>
    <div class="flex items-center gap-3">
      @auth
        <a href="{{ url('/dashboard') }}"
          class="hidden md:inline-flex px-5 py-2 text-sm font-bold text-white bg-primary rounded-lg shadow-lg shadow-primary/20 hover:bg-blue-700 transition-all">Dashboard</a>
      @else
        <a href="{{ route('login') }}"
          class="hidden sm:inline-flex px-5 py-2 text-sm font-bold text-[#0d121b] bg-[#e7ebf3] rounded-lg hover:bg-[#d9dee9] transition-all">Masuk</a>
        <a href="{{ route('register') }}"
          class="hidden md:inline-flex px-5 py-2 text-sm font-bold text-white bg-primary rounded-lg shadow-lg shadow-primary/20 hover:bg-blue-700 transition-all">Daftar</a>
      @endauth

      <!-- Mobile Menu Button -->
      <button id="mobile-menu-btn" class="md:hidden p-2 text-slate-600 hover:bg-slate-100 rounded-lg transition-colors">
        <span class="material-symbols-outlined">menu</span>
      </button>
    </div>
  </div>

  <!-- Mobile Menu Dropdown -->
  <div id="mobile-menu"
    class="hidden md:hidden border-t border-slate-100 bg-white absolute top-full left-0 w-full shadow-lg p-4 flex-col gap-4 animate-fade-in-down">
    <a class="block px-4 py-2 hover:bg-slate-50 rounded-lg text-sm font-semibold {{ Route::is('features') ? 'text-primary bg-primary/5' : '' }}"
      href="{{ route('features') }}">Fitur</a>
    <a class="block px-4 py-2 hover:bg-slate-50 rounded-lg text-sm font-semibold {{ Route::is('architecture') ? 'text-primary bg-primary/5' : '' }}"
      href="{{ route('architecture') }}">Arsitektur</a>
    <a class="block px-4 py-2 hover:bg-slate-50 rounded-lg text-sm font-semibold {{ Route::is('security') ? 'text-primary bg-primary/5' : '' }}"
      href="{{ route('security') }}">Keamanan</a>
    <hr class="border-slate-100">
    @auth
      <a href="{{ url('/dashboard') }}"
        class="block px-4 py-2 bg-primary text-white text-center rounded-lg font-bold">Dashboard</a>
    @else
      <a href="{{ route('login') }}"
        class="block px-4 py-2 text-center font-bold text-slate-700 hover:bg-slate-50 rounded-lg">Masuk</a>
      <a href="{{ route('register') }}"
        class="block px-4 py-2 bg-primary text-white text-center rounded-lg font-bold">Daftar Sekarang</a>
    @endauth
  </div>

  <script>
    document.getElementById('mobile-menu-btn').addEventListener('click', function () {
      const menu = document.getElementById('mobile-menu');
      menu.classList.toggle('hidden');
      menu.classList.toggle('flex');
    });
  </script>
</header>