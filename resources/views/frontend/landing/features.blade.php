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
          <div class="p-3 bg-primary rounded-xl text-white">
            <span class="material-symbols-outlined">account_balance</span>
          </div>
          <h3 class="text-2xl font-bold">{{ $settings['landing_feat_g1_title'] ?? 'Untuk Dinas Pendidikan' }}</h3>
        </div>
        <div class="grid gap-6">
          <div class="bg-white p-6 rounded-2xl border border-[#e7ebf3] hover:shadow-md transition-shadow">
            <span class="material-symbols-outlined text-primary mb-4 text-3xl">insights</span>
            <h4 class="text-lg font-bold mb-2">Monitoring Real-time</h4>
            <p class="text-sm text-slate-600">Pantau progres pengarsipan di seluruh sekolah di bawah naungan Dinas
              secara instan melalui dashboard pusat.</p>
          </div>
          <div class="bg-white p-6 rounded-2xl border border-[#e7ebf3] hover:shadow-md transition-shadow">
            <span class="material-symbols-outlined text-primary mb-4 text-3xl">analytics</span>
            <h4 class="text-lg font-bold mb-2">Advanced Analytics</h4>
            <p class="text-sm text-slate-600">Dapatkan wawasan mendalam mengenai tren kelulusan, statistik dokumen,
              dan laporan kepatuhan administrasi sekolah.</p>
          </div>
        </div>
      </div>
      <!-- Sekolah -->
      <div class="space-y-8">
        <div class="flex items-center gap-4 mb-2">
          <div class="p-3 bg-green-500 rounded-xl text-white">
            <span class="material-symbols-outlined">school</span>
          </div>
          <h3 class="text-2xl font-bold">{{ $settings['landing_feat_g2_title'] ?? 'Untuk Satuan Pendidikan' }}</h3>
        </div>
        <div class="grid gap-6">
          <div class="bg-white p-6 rounded-2xl border border-[#e7ebf3] hover:shadow-md transition-shadow">
            <span class="material-symbols-outlined text-green-500 mb-4 text-3xl">description</span>
            <h4 class="text-lg font-bold mb-2">Kelola Ijazah Digital</h4>
            <p class="text-sm text-slate-600">Sistem penomoran otomatis dan pencarian instan untuk ribuan arsip
              ijazah siswa tanpa repot mencari manual di gudang.</p>
          </div>
          <div class="bg-white p-6 rounded-2xl border border-[#e7ebf3] hover:shadow-md transition-shadow">
            <span class="material-symbols-outlined text-green-500 mb-4 text-3xl">data_table</span>
            <h4 class="text-lg font-bold mb-2">Import Bulk Excel</h4>
            <p class="text-sm text-slate-600">Migrasi data ribuan siswa dari format Excel lama ke sistem modern
              hanya dalam hitungan detik tanpa risiko data duplikat.</p>
          </div>
        </div>
      </div>
    </div>
  </div>
</section>