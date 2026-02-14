<section class="py-20 px-6 md:px-20">
  <div class="max-w-5xl mx-auto bg-primary rounded-[2rem] p-12 md:p-20 text-center relative overflow-hidden">
    <div class="absolute top-0 right-0 w-64 h-64 bg-white/10 rounded-full -mr-32 -mt-32"></div>
    <div class="absolute bottom-0 left-0 w-48 h-48 bg-white/10 rounded-full -ml-24 -mb-24"></div>
    <div class="relative z-10">
      <h2 class="text-3xl md:text-5xl font-bold text-white mb-6 leading-tight">
        {{ $settings['landing_cta_title'] ?? 'Siap Mendigitalkan Arsip Pendidikan Anda?' }}
      </h2>
      <p class="text-white/80 text-lg mb-10 max-w-2xl mx-auto">
        {!! $settings['landing_cta_desc'] ?? ('Bergabunglah dengan puluhan instansi lain yang telah meningkatkan efisiensi administrasi mereka bersama ' . ($settings['app_name'] ?? 'EduArchive') . '.') !!}
      </p>
      <div class="flex flex-wrap justify-center gap-4">
        @auth
          <a href="{{ url('/dashboard') }}"
            class="px-8 py-4 bg-white text-primary font-bold rounded-xl shadow-xl shadow-black/10 hover:bg-slate-50 transition-colors">Ke
            Dashboard</a>
        @else
          <a href="{{ route('login') }}"
            class="px-8 py-4 bg-white text-primary font-bold rounded-xl shadow-xl shadow-black/10 hover:bg-slate-50 transition-colors">Jadwalkan
            Demo Gratis</a>
        @endauth
        <button
          class="px-8 py-4 border border-white/30 text-white font-bold rounded-xl hover:bg-white/10 transition-colors">Hubungi
          Tim Sales</button>
      </div>
    </div>
  </div>
</section>