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
      <a class="text-sm font-semibold hover:text-primary transition-colors" href="#fitur">Fitur</a>
      <a class="text-sm font-semibold hover:text-primary transition-colors" href="#arsitektur">Arsitektur</a>
      <a class="text-sm font-semibold hover:text-primary transition-colors" href="#keamanan">Keamanan</a>
      <a class="text-sm font-semibold hover:text-primary transition-colors" href="#">Harga</a>
    </nav>
    <div class="flex items-center gap-3">
      @auth
        <a href="{{ url('/dashboard') }}"
          class="px-5 py-2 text-sm font-bold text-white bg-primary rounded-lg shadow-lg shadow-primary/20 hover:bg-blue-700 transition-all">Dashboard</a>
      @else
        <a href="{{ route('login') }}"
          class="hidden sm:block px-5 py-2 text-sm font-bold text-[#0d121b] bg-[#e7ebf3] rounded-lg hover:bg-[#d9dee9] transition-all">Masuk</a>
        <a href="{{ route('login') }}"
          class="px-5 py-2 text-sm font-bold text-white bg-primary rounded-lg shadow-lg shadow-primary/20 hover:bg-blue-700 transition-all">Demo
          Gratis</a>
      @endauth
    </div>
  </div>
</header>