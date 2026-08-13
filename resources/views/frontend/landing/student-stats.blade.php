{{-- Section: Statistik Siswa Aktif & Lulusan --}}
<section class="py-14 bg-gradient-to-br from-[#0f172a] via-[#1e3a5f] to-[#0f172a] relative overflow-hidden">

  {{-- Background decoration --}}
  <div class="absolute inset-0 pointer-events-none" aria-hidden="true">
    <div class="absolute top-0 left-1/4 w-72 h-72 bg-blue-500/10 rounded-full blur-3xl"></div>
    <div class="absolute bottom-0 right-1/4 w-72 h-72 bg-emerald-500/10 rounded-full blur-3xl"></div>
    <div class="absolute top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 w-96 h-96 bg-primary/5 rounded-full blur-3xl"></div>
  </div>

  <div class="max-w-6xl mx-auto px-6 relative z-10">

    {{-- Section Label --}}
    <div class="text-center mb-10">
      <span class="inline-block px-3 py-1 text-xs font-semibold text-emerald-300 bg-emerald-500/10 border border-emerald-500/20 rounded-full uppercase tracking-wider mb-3">
        Data Real-Time
      </span>
      <h2 class="text-2xl md:text-3xl font-bold text-white">Rekap Data Siswa Seluruh Lembaga</h2>
      <p class="text-slate-400 mt-2 text-sm max-w-xl mx-auto">
        Jumlah siswa terdaftar berdasarkan status kelulusan secara agregat dari seluruh lembaga yang bergabung.
      </p>
    </div>

    {{-- Stats Grid --}}
    <div class="grid grid-cols-2 lg:grid-cols-4 gap-4 md:gap-6">

      {{-- Card: Total Semua Siswa --}}
      <div class="stat-card group bg-white/5 hover:bg-white/10 border border-white/10 hover:border-white/20 rounded-2xl p-6 text-center backdrop-blur-sm transition-all duration-300 hover:-translate-y-1">
        <div class="w-12 h-12 mx-auto mb-4 rounded-xl bg-blue-500/20 flex items-center justify-center group-hover:scale-110 transition-transform duration-300">
          <span class="material-symbols-outlined text-blue-400 text-2xl">groups</span>
        </div>
        <div
          class="text-3xl md:text-4xl font-black text-white mb-1 counter"
          data-target="{{ $studentStats['total_siswa'] ?? 0 }}">
          0
        </div>
        <div class="text-xs font-semibold text-slate-400 uppercase tracking-wide">Total Semua Siswa</div>
        <div class="mt-3 h-0.5 w-8 mx-auto bg-blue-400/40 rounded-full group-hover:w-16 group-hover:bg-blue-400 transition-all duration-500"></div>
      </div>

      {{-- Card: Siswa Aktif --}}
      <div class="stat-card group bg-emerald-500/10 hover:bg-emerald-500/15 border border-emerald-500/20 hover:border-emerald-400/40 rounded-2xl p-6 text-center backdrop-blur-sm transition-all duration-300 hover:-translate-y-1">
        <div class="w-12 h-12 mx-auto mb-4 rounded-xl bg-emerald-500/20 flex items-center justify-center group-hover:scale-110 transition-transform duration-300">
          <span class="material-symbols-outlined text-emerald-400 text-2xl">school</span>
        </div>
        <div
          class="text-3xl md:text-4xl font-black text-emerald-300 mb-1 counter"
          data-target="{{ $studentStats['total_aktif'] ?? 0 }}">
          0
        </div>
        <div class="text-xs font-semibold text-emerald-400/70 uppercase tracking-wide">Siswa Aktif</div>
        <div class="mt-3 h-0.5 w-8 mx-auto bg-emerald-400/40 rounded-full group-hover:w-16 group-hover:bg-emerald-400 transition-all duration-500"></div>
      </div>

      {{-- Card: Siswa Lulusan --}}
      <div class="stat-card group bg-violet-500/10 hover:bg-violet-500/15 border border-violet-500/20 hover:border-violet-400/40 rounded-2xl p-6 text-center backdrop-blur-sm transition-all duration-300 hover:-translate-y-1">
        <div class="w-12 h-12 mx-auto mb-4 rounded-xl bg-violet-500/20 flex items-center justify-center group-hover:scale-110 transition-transform duration-300">
          <span class="material-symbols-outlined text-violet-400 text-2xl">workspace_premium</span>
        </div>
        <div
          class="text-3xl md:text-4xl font-black text-violet-300 mb-1 counter"
          data-target="{{ $studentStats['total_lulus'] ?? 0 }}">
          0
        </div>
        <div class="text-xs font-semibold text-violet-400/70 uppercase tracking-wide">Siswa Lulusan</div>
        <div class="mt-3 h-0.5 w-8 mx-auto bg-violet-400/40 rounded-full group-hover:w-16 group-hover:bg-violet-400 transition-all duration-500"></div>
      </div>

      {{-- Card: Total Lembaga --}}
      <div class="stat-card group bg-amber-500/10 hover:bg-amber-500/15 border border-amber-500/20 hover:border-amber-400/40 rounded-2xl p-6 text-center backdrop-blur-sm transition-all duration-300 hover:-translate-y-1">
        <div class="w-12 h-12 mx-auto mb-4 rounded-xl bg-amber-500/20 flex items-center justify-center group-hover:scale-110 transition-transform duration-300">
          <span class="material-symbols-outlined text-amber-400 text-2xl">apartment</span>
        </div>
        <div
          class="text-3xl md:text-4xl font-black text-amber-300 mb-1 counter"
          data-target="{{ $studentStats['total_sekolah'] ?? 0 }}">
          0
        </div>
        <div class="text-xs font-semibold text-amber-400/70 uppercase tracking-wide">Total Lembaga</div>
        <div class="mt-3 h-0.5 w-8 mx-auto bg-amber-400/40 rounded-full group-hover:w-16 group-hover:bg-amber-400 transition-all duration-500"></div>
      </div>

    </div>

    {{-- Breakdown Bar --}}
    @php
      $totalSiswa  = $studentStats['total_siswa'] ?? 0;
      $totalAktif  = $studentStats['total_aktif'] ?? 0;
      $totalLulus  = $studentStats['total_lulus'] ?? 0;
      $pctAktif    = $totalSiswa > 0 ? round(($totalAktif / $totalSiswa) * 100) : 0;
      $pctLulus    = $totalSiswa > 0 ? round(($totalLulus / $totalSiswa) * 100) : 0;
      $pctLainnya  = max(0, 100 - $pctAktif - $pctLulus);
    @endphp

    @if($totalSiswa > 0)
    <div class="mt-8 bg-white/5 border border-white/10 rounded-2xl p-6 backdrop-blur-sm">
      <div class="flex items-center justify-between mb-3">
        <span class="text-xs font-semibold text-slate-400 uppercase tracking-wide">Distribusi Status Siswa</span>
        <span class="text-xs text-slate-500">Total: {{ number_format($totalSiswa) }} siswa</span>
      </div>

      {{-- Bar --}}
      <div class="flex rounded-full overflow-hidden h-4 bg-white/5">
        @if($pctAktif > 0)
          <div class="bg-emerald-400 h-full transition-all duration-1000" style="width: {{ $pctAktif }}%" title="Aktif: {{ $pctAktif }}%"></div>
        @endif
        @if($pctLulus > 0)
          <div class="bg-violet-400 h-full transition-all duration-1000" style="width: {{ $pctLulus }}%" title="Lulus: {{ $pctLulus }}%"></div>
        @endif
        @if($pctLainnya > 0)
          <div class="bg-slate-500 h-full transition-all duration-1000" style="width: {{ $pctLainnya }}%" title="Lainnya: {{ $pctLainnya }}%"></div>
        @endif
      </div>

      {{-- Legend --}}
      <div class="flex flex-wrap gap-4 mt-3 text-xs text-slate-400">
        <span class="flex items-center gap-1.5">
          <span class="w-3 h-3 rounded-full bg-emerald-400 inline-block flex-shrink-0"></span>
          Aktif <strong class="text-emerald-300 ml-1">{{ number_format($totalAktif) }}</strong>
          <span class="text-slate-500">({{ $pctAktif }}%)</span>
        </span>
        <span class="flex items-center gap-1.5">
          <span class="w-3 h-3 rounded-full bg-violet-400 inline-block flex-shrink-0"></span>
          Lulusan <strong class="text-violet-300 ml-1">{{ number_format($totalLulus) }}</strong>
          <span class="text-slate-500">({{ $pctLulus }}%)</span>
        </span>
        @if($pctLainnya > 0)
        <span class="flex items-center gap-1.5">
          <span class="w-3 h-3 rounded-full bg-slate-500 inline-block flex-shrink-0"></span>
          Lainnya <strong class="text-slate-300 ml-1">{{ number_format($totalSiswa - $totalAktif - $totalLulus) }}</strong>
          <span class="text-slate-500">({{ $pctLainnya }}%)</span>
        </span>
        @endif
        <span class="ml-auto flex items-center gap-1 text-slate-500">
          <span class="material-symbols-outlined" style="font-size:12px">sync</span>
          Diperbarui otomatis
        </span>
      </div>
    </div>
    @endif

  </div>
</section>

{{-- Counter Animation Script --}}
<script>
(function() {
  function animateCounter(el) {
    const target = parseInt(el.dataset.target, 10);
    if (!target || target === 0) { el.textContent = '0'; return; }
    const duration = 1800;
    const start = performance.now();
    function step(now) {
      const elapsed = now - start;
      const progress = Math.min(elapsed / duration, 1);
      // easeOutExpo
      const eased = progress === 1 ? 1 : 1 - Math.pow(2, -10 * progress);
      const current = Math.floor(eased * target);
      el.textContent = current.toLocaleString('id-ID');
      if (progress < 1) requestAnimationFrame(step);
      else el.textContent = target.toLocaleString('id-ID');
    }
    requestAnimationFrame(step);
  }

  const observer = new IntersectionObserver((entries) => {
    entries.forEach(entry => {
      if (entry.isIntersecting) {
        entry.target.querySelectorAll('.counter').forEach(animateCounter);
        observer.unobserve(entry.target);
      }
    });
  }, { threshold: 0.3 });

  document.querySelectorAll('.stat-card').forEach(card => {
    // Observe the parent section instead
  });

  // Observe the whole section
  const section = document.currentScript
    ? document.currentScript.closest('section') || document.querySelector('section:has(.stat-card)')
    : null;

  if (section) {
    observer.observe(section);
  } else {
    // fallback: observe each counter's closest section
    document.querySelectorAll('.counter').forEach(counter => {
      const sec = counter.closest('section');
      if (sec && !sec._observed) {
        sec._observed = true;
        observer.observe(sec);
      }
    });
  }
})();
</script>
