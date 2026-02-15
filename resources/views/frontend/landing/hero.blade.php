<section class="relative overflow-hidden pt-16 pb-20 px-6 md:px-20">
  <div class="max-w-7xl mx-auto grid lg:grid-cols-2 gap-12 items-center">
    <div class="flex flex-col gap-8">
      <div
        class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-primary/10 text-primary text-xs font-bold w-fit">
        <span class="material-symbols-outlined text-sm">verified</span>
        {{ $settings['landing_hero_tagline'] ?? 'Dipercaya oleh 50+ Dinas Pendidikan' }}
      </div>
      <h1 class="text-5xl md:text-6xl font-black leading-[1.1] tracking-tight text-[#0d121b]">
        {{ $settings['landing_hero_title_1'] ?? 'Solusi Modern' }} <span
          class="text-primary">{{ $settings['landing_hero_title_highlight'] ?? 'Arsip Digital' }}</span>
        {{ $settings['landing_hero_title_2'] ?? 'Pendidikan' }}
      </h1>
      <p class="text-lg text-slate-600 leading-relaxed max-w-[540px]">
        {!! $settings['landing_hero_desc'] ?? 'Platform multi-tenant yang aman dan terintegrasi untuk Dinas Pendidikan dan Sekolah. Kelola dokumen ijazah, SK, dan administrasi sekolah dalam satu ekosistem terpusat.' !!}
      </p>
      <div class="flex flex-wrap gap-4">
        @auth
          <a href="{{ url('/dashboard') }}"
            class="px-8 py-4 bg-primary text-white font-bold rounded-xl shadow-xl shadow-primary/30 hover:scale-[1.02] transition-transform">Ke
            Dashboard</a>
        @else
          <a href="{{ route('register') }}"
            class="px-8 py-4 bg-primary text-white font-bold rounded-xl shadow-xl shadow-primary/30 hover:scale-[1.02] transition-transform">Daftarkan
            Sekolah</a>
        @endauth
        <button onclick="window.location.href='{{ route('features') }}'"
          class="px-8 py-4 bg-white border border-[#e7ebf3] text-[#0d121b] font-bold rounded-xl hover:bg-slate-50 transition-colors flex items-center gap-2">
          Pelajari Selengkapnya
          <span class="material-symbols-outlined text-sm">arrow_forward</span>
        </button>
      </div>
    </div>
    <div class="relative">
      <div
        class="absolute -inset-4 bg-gradient-to-r from-primary/30 to-purple-600/30 rounded-[2rem] blur-3xl opacity-50 animate-pulse">
      </div>
      <div
        class="relative bg-white/80 backdrop-blur-xl p-4 rounded-2xl shadow-2xl shadow-primary/20 border border-white/50">
        <img class="w-full h-auto rounded-lg shadow-inner" data-alt="Dashboard EduArchive"
          src="{{ isset($settings['landing_hero_image']) ? asset($settings['landing_hero_image']) : 'https://placehold.co/800x600/e2e8f0/64748b?text=Dashboard+Preview' }}" />

        <!-- Floating Badge 1 -->
        <div
          class="absolute -bottom-6 -left-6 bg-white p-4 rounded-2xl shadow-xl border border-slate-100 animate-bounce"
          style="animation-duration: 3s;">
          <div class="flex items-center gap-3">
            <div class="w-12 h-12 rounded-full bg-green-100 flex items-center justify-center text-green-600">
              <span class="material-symbols-outlined">verified_user</span>
            </div>
            <div>
              <p class="text-xs text-slate-500 font-bold uppercase tracking-wider">Status Sistem</p>
              <p class="text-sm font-black text-slate-800">100% Aman</p>
            </div>
          </div>
        </div>

        <!-- Floating Badge 2 -->
        <div class="absolute -top-6 -right-6 bg-white p-4 rounded-2xl shadow-xl border border-slate-100 animate-bounce"
          style="animation-duration: 4s;">
          <div class="flex items-center gap-3">
            <div class="w-12 h-12 rounded-full bg-blue-100 flex items-center justify-center text-blue-600">
              <span class="material-symbols-outlined">cloud_upload</span>
            </div>
            <div>
              <p class="text-xs text-slate-500 font-bold uppercase tracking-wider">Total Arsip</p>
              <p class="text-sm font-black text-slate-800">1M+ Dokumen</p>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</section>