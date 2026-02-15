<section class="py-24 px-6 md:px-20 bg-[#f8f9fc]" id="fitur">
  <div class="max-w-7xl mx-auto">
    <div class="text-center mb-20">
      <h2 class="text-3xl md:text-4xl font-bold mb-4">
        {{ $settings['landing_feat_title'] ?? 'Satu Platform, Beragam Solusi' }}
      </h2>
      <p class="text-slate-600 max-w-2xl mx-auto">
        {!! $settings['landing_feat_subtitle'] ?? 'Kami menghadirkan fitur spesifik yang dirancang khusus untuk memenuhi kebutuhan birokrasi pendidikan yang kompleks namun tetap efisien.' !!}
      </p>
    </div>
    <div class="grid md:grid-cols-2 gap-12">
      <!-- Dinas Pendidikan -->
      <div class="space-y-8">
        <div class="flex items-center gap-4 mb-2">
          <div class="p-3 bg-gradient-to-br from-primary to-blue-600 rounded-xl text-white shadow-lg shadow-primary/30">
            <span class="material-symbols-outlined">account_balance</span>
          </div>
          <h3 class="text-2xl font-bold tracking-tight">
            {{ $settings['landing_feat_g1_title'] ?? 'Untuk Dinas Pendidikan' }}</h3>
        </div>
        <div class="grid gap-6">
          <div
            class="group bg-white p-8 rounded-3xl border border-slate-100 shadow-sm hover:shadow-xl hover:-translate-y-1 transition-all duration-300 relative overflow-hidden">
            <div
              class="absolute top-0 right-0 w-32 h-32 bg-primary/5 rounded-full -mr-10 -mt-10 group-hover:scale-150 transition-transform duration-500">
            </div>
            <span
              class="material-symbols-outlined text-primary mb-6 text-4xl bg-primary/10 p-3 rounded-2xl">insights</span>
            <h4 class="text-xl font-bold mb-3 text-slate-800">Monitoring Real-time</h4>
            <p class="text-slate-500 leading-relaxed">Pantau progres pengarsipan di seluruh sekolah di bawah naungan
              Dinas
              secara instan melalui dashboard pusat yang intuitif.</p>
          </div>
          <div
            class="group bg-white p-8 rounded-3xl border border-slate-100 shadow-sm hover:shadow-xl hover:-translate-y-1 transition-all duration-300 relative overflow-hidden">
            <div
              class="absolute top-0 right-0 w-32 h-32 bg-primary/5 rounded-full -mr-10 -mt-10 group-hover:scale-150 transition-transform duration-500">
            </div>
            <span
              class="material-symbols-outlined text-primary mb-6 text-4xl bg-primary/10 p-3 rounded-2xl">analytics</span>
            <h4 class="text-xl font-bold mb-3 text-slate-800">Advanced Analytics</h4>
            <p class="text-slate-500 leading-relaxed">Dapatkan wawasan mendalam mengenai tren kelulusan, statistik
              dokumen,
              dan laporan kepatuhan administrasi sekolah.</p>
          </div>
        </div>
      </div>
      <!-- Sekolah -->
      <div class="space-y-8">
        <div class="flex items-center gap-4 mb-2">
          <div class="p-3 bg-gradient-to-br from-green-500 to-emerald-700 rounded-xl text-white shadow-lg shadow-green-500/30">
            <span class="material-symbols-outlined">school</span>
          </div>
          <h3 class="text-2xl font-bold tracking-tight">{{ $settings['landing_feat_g2_title'] ?? 'Untuk Satuan Pendidikan' }}</h3>
        </div>
        <div class="grid gap-6">
          <div class="group bg-white p-8 rounded-3xl border border-slate-100 shadow-sm hover:shadow-xl hover:-translate-y-1 transition-all duration-300 relative overflow-hidden">
             <div class="absolute top-0 right-0 w-32 h-32 bg-green-500/5 rounded-full -mr-10 -mt-10 group-hover:scale-150 transition-transform duration-500"></div>
            <span class="material-symbols-outlined text-green-500 mb-6 text-4xl bg-green-500/10 p-3 rounded-2xl">description</span>
            <h4 class="text-xl font-bold mb-3 text-slate-800">Kelola Ijazah Digital</h4>
            <p class="text-slate-500 leading-relaxed">Sistem penomoran otomatis dan pencarian instan untuk ribuan arsip
              ijazah siswa tanpa repot mencari manual di gudang.</p>
          </div>
          <div class="group bg-white p-8 rounded-3xl border border-slate-100 shadow-sm hover:shadow-xl hover:-translate-y-1 transition-all duration-300 relative overflow-hidden">
             <div class="absolute top-0 right-0 w-32 h-32 bg-green-500/5 rounded-full -mr-10 -mt-10 group-hover:scale-150 transition-transform duration-500"></div>
            <span class="material-symbols-outlined text-green-500 mb-6 text-4xl bg-green-500/10 p-3 rounded-2xl">data_table</span>
            <h4 class="text-xl font-bold mb-3 text-slate-800">Import Bulk Excel</h4>
            <p class="text-slate-500 leading-relaxed">Migrasi data ribuan siswa dari format Excel lama ke sistem modern
              hanya dalam hitungan detik tanpa risiko data duplikat.</p>
          </div>
        </div>
      </div>
    </div>
  </div>
</section>