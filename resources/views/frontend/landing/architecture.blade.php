<section class="py-24 px-6 md:px-20 bg-white" id="arsitektur">
  <div class="max-w-7xl mx-auto flex flex-col lg:flex-row items-center gap-16">
    <div class="lg:w-1/2 relative">
      <div class="absolute inset-0 bg-gradient-to-tr from-primary/20 to-transparent rounded-full blur-3xl -z-10"></div>
      <img class="w-full h-auto rounded-2xl shadow-lg border border-slate-100" data-alt="Architecture Diagram"
        src="{{ isset($settings['landing_arch_image']) ? asset($settings['landing_arch_image']) : 'https://lh3.googleusercontent.com/aida-public/AB6AXuDJWAxo7rRFUzhGH9ObTvBOWpngaHC_UIjnL6A7ATkb0aJ6xI5grq3Tn7L70jxsCm9D7X_uHKKnFsx0KIgGiIYIUOpqe1jGKq86pZVAsA1rIOeTbcp2xtkhAaUeMNnM_PV-YJ_2rMtdnSG2YiPeJZ2Cnpwlbq2uXg2nU8nqPrZYoC6JdcojtFwfDOd4KcaOLze86rEa1wD2Geqn99FbbgrwK-Ovkf_QKKy8eHEdp_Y3iYmftyq88ldlvxo-x0MgT3IP2fpgSHPl_A87' }}" />
    </div>
    <div class="lg:w-1/2 space-y-6">
      <h2 class="text-3xl md:text-4xl font-bold leading-tight text-[#0d121b]">
        {{ $settings['landing_arch_title'] ?? 'Arsitektur Multi-Tenant yang Aman & Terukur' }}
      </h2>
      <p class="text-lg text-slate-600">
        {!! $settings['landing_arch_desc'] ?? (($settings['app_name'] ?? 'EduArchive') . ' dirancang dengan teknologi Multi-Tenant tingkat lanjut. Artinya, satu infrastruktur besar melayani ribuan sekolah, namun data antar sekolah tetap terisolasi secara total.') !!}
      </p>
      <ul class="space-y-6">
        <li
          class="flex gap-5 bg-white p-5 rounded-2xl border border-slate-100 shadow-sm hover:shadow-md transition-shadow">
          <div class="flex-shrink-0 w-12 h-12 bg-primary/10 rounded-full flex items-center justify-center text-primary">
            <span class="material-symbols-outlined">cloud_done</span>
          </div>
          <div>
            <h5 class="font-bold text-lg mb-1">Skalabilitas Tinggi</h5>
            <p class="text-sm text-slate-500 leading-relaxed">Mampu menangani lonjakan data saat musim kelulusan tanpa
              penurunan
              performa berkat infrastruktur cloud-native.</p>
          </div>
        </li>
        <li
          class="flex gap-5 bg-white p-5 rounded-2xl border border-slate-100 shadow-sm hover:shadow-md transition-shadow">
          <div class="flex-shrink-0 w-12 h-12 bg-primary/10 rounded-full flex items-center justify-center text-primary">
            <span class="material-symbols-outlined">storage</span>
          </div>
          <div>
            <h5 class="font-bold text-lg mb-1">Isolasi Data Penuh</h5>
            <p class="text-sm text-slate-500 leading-relaxed">Masing-masing sekolah memiliki ruang penyimpanan privat
              yang tidak
              dapat diakses pihak lain untuk menjamin privasi total.</p>
          </div>
        </li>
      </ul>
    </div>
  </div>
</section>