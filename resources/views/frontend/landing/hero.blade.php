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
          <a href="{{ route('login') }}"
            class="px-8 py-4 bg-primary text-white font-bold rounded-xl shadow-xl shadow-primary/30 hover:scale-[1.02] transition-transform">Mulai
            Demo Sekarang</a>
        @endauth
        <button
          class="px-8 py-4 bg-white border border-[#e7ebf3] text-[#0d121b] font-bold rounded-xl hover:bg-slate-50 transition-colors flex items-center gap-2">
          Pelajari Selengkapnya
          <span class="material-symbols-outlined text-sm">arrow_forward</span>
        </button>
      </div>
    </div>
    <div class="relative">
      <div class="absolute -inset-4 bg-primary/5 rounded-[2rem] blur-2xl"></div>
      <div class="relative bg-white p-4 rounded-2xl shadow-2xl border border-[#e7ebf3]">
        <img class="w-full h-auto rounded-lg" data-alt="Dashboard EduArchive"
          src="{{ isset($settings['landing_hero_image']) ? asset($settings['landing_hero_image']) : 'https://lh3.googleusercontent.com/aida-public/AB6AXuDGYZYQVB6MnIw4sXp_0L-u-6d3DP0yb48gyB91kqGSVbrTn75OxFO9gDeTI-3UgTP3zgja_QAgvnBvDRkPsoEL5LFOAkT1fbDiLLGyHZWXDjFdKZBddXv870mGGnB_H2tGhw5lIRC0r00eSzuIw2ssWyt-r3WZOySeTyzV59fCInaL0GGXFI7XIbpY884EEQNXRh9wOpw0AmVh6jday2tNS3miVSxjsFR6h8BJTz3ugzKTN3fwg1Q6MEfapjMcdiio7vkRkEbbKfrK' }}" />
      </div>
    </div>
  </div>
</section>