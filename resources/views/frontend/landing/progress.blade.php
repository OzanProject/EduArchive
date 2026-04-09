{{-- Section: Progres Lembaga (Multi-Tab) --}}
<section id="progres-pendataan" class="py-16 bg-[#f8fafc]">
  <div class="max-w-6xl mx-auto px-6">
    <div class="text-center mb-6">
      <span class="inline-block px-3 py-1 text-xs font-semibold text-blue-700 bg-blue-100 rounded-full uppercase tracking-wider mb-3">Transparansi Data</span>
      <h2 class="text-3xl font-bold text-[#0d121b]">Progres Pendataan Lembaga</h2>
      <p class="text-slate-500 mt-3 max-w-2xl mx-auto text-sm">
        Pantau kelengkapan data setiap lembaga secara real-time dalam tiga aspek penting.
      </p>
    </div>

    {{-- Filter Kecamatan & NPSN --}}
    <div class="flex flex-col sm:flex-row justify-center items-center mb-8 gap-3">
      <form action="{{ route('progress') }}" method="GET" class="flex flex-col sm:flex-row items-center gap-2 w-full max-w-2xl bg-white p-2 rounded-xl shadow-sm border border-slate-200">
        <input type="text" name="npsn" value="{{ $selectedNpsn ?? '' }}" placeholder=" Cari NPSN..." class="border-none bg-slate-50 rounded-lg px-4 py-2 text-sm focus:ring-2 focus:ring-blue-100 flex-1 w-full" />
        <div class="h-6 w-px bg-slate-200 hidden sm:block"></div>
        <select name="district" class="border-none bg-slate-50 rounded-lg px-4 py-2 text-sm focus:ring-2 focus:ring-blue-100 flex-1 w-full">
          <option value="">-- Semua Kecamatan --</option>
          @foreach($districts as $dist)
            <option value="{{ $dist }}" {{ (isset($selectedDistrict) && $selectedDistrict == $dist) ? 'selected' : '' }}>{{ $dist }}</option>
          @endforeach
        </select>
        <button type="submit" class="bg-blue-600 text-white px-5 py-2 rounded-lg text-sm font-semibold hover:bg-blue-700 transition w-full sm:w-auto mt-2 sm:mt-0">Filter</button>
      </form>
      @if(!empty($selectedDistrict) || !empty($selectedNpsn))
        <a href="{{ route('progress') }}" class="bg-slate-200 text-slate-700 px-5 py-2 rounded-lg text-sm font-semibold hover:bg-slate-300 transition shrink-0 whitespace-nowrap">Reset</a>
      @endif
    </div>

    {{-- Tab Nav --}}
    <div class="flex gap-1 bg-slate-100 rounded-xl p-1 mb-6 max-w-xl mx-auto">
      <button onclick="switchTab('tab-nisn')" id="btn-tab-nisn"
        class="tab-btn flex-1 py-2 px-3 rounded-lg text-sm font-semibold transition-all duration-200 bg-white text-blue-700 shadow-sm">
        📋 Kelengkapan Data Siswa
      </button>
      <button onclick="switchTab('tab-profil')" id="btn-tab-profil"
        class="tab-btn flex-1 py-2 px-3 rounded-lg text-sm font-semibold transition-all duration-200 text-slate-500 hover:text-slate-700">
        🏫 Profil Lembaga
      </button>
      <button onclick="switchTab('tab-dokumen')" id="btn-tab-dokumen"
        class="tab-btn flex-1 py-2 px-3 rounded-lg text-sm font-semibold transition-all duration-200 text-slate-500 hover:text-slate-700">
        📁 Dokumen Siswa
      </button>
    </div>

    {{-- ===== TAB 1: NISN / Data Siswa ===== --}}
    <div id="tab-nisn" class="tab-content">
      @php
        $totalSent1  = collect($schoolProgress)->sum('sent');
        $totalAll1   = collect($schoolProgress)->sum('total');
        $totalSisa1  = collect($schoolProgress)->sum('sisa');
        $avgPct1     = $totalAll1 > 0 ? round(($totalSent1 / $totalAll1) * 100) : 0;
      @endphp
      @include('frontend.landing._progress_table', [
        'items'        => $schoolProgress,
        'totalSent'    => $totalSent1,
        'totalAll'     => $totalAll1,
        'totalSisa'    => $totalSisa1,
        'avgPct'       => $avgPct1,
        'labelSent'    => 'Sudah Ber-NISN',
        'labelSisa'    => 'Belum NISN',
        'labelPct'     => 'Progres NISN',
        'labelSentCard'=> 'Siswa Ber-NISN',
        'labelSisaCard'=> 'Belum Ber-NISN',
        'noteBar'      => 'Rata-rata kelengkapan NISN siswa seluruh lembaga',
      ])
    </div>

    {{-- ===== TAB 2: Profil Lembaga ===== --}}
    <div id="tab-profil" class="tab-content hidden">
      @php
        $totalSent2  = collect($profileProgress)->sum('sent');
        $totalAll2   = collect($profileProgress)->sum('total');
        $totalSisa2  = collect($profileProgress)->sum('sisa');
        $avgPct2     = $totalAll2 > 0 ? round(($totalSent2 / $totalAll2) * 100) : 0;
      @endphp
      @include('frontend.landing._progress_table', [
        'items'        => $profileProgress,
        'totalSent'    => $totalSent2,
        'totalAll'     => $totalAll2,
        'totalSisa'    => $totalSisa2,
        'avgPct'       => $avgPct2,
        'labelSent'    => 'Field Terisi',
        'labelSisa'    => 'Field Kosong',
        'labelPct'     => 'Kelengkapan Profil',
        'labelSentCard'=> 'Total Field Terisi',
        'labelSisaCard'=> 'Field Belum Diisi',
        'noteBar'      => 'Dari 9 field profil penting lembaga (akreditasi, kurikulum, kepala sekolah, dll)',
      ])
    </div>

    {{-- ===== TAB 3: Dokumen Siswa ===== --}}
    <div id="tab-dokumen" class="tab-content hidden">
      @php
        $totalSent3  = collect($documentProgress)->sum('sent');
        $totalAll3   = collect($documentProgress)->sum('total');
        $totalSisa3  = collect($documentProgress)->sum('sisa');
        $avgPct3     = $totalAll3 > 0 ? round(($totalSent3 / $totalAll3) * 100) : 0;
      @endphp
      @include('frontend.landing._document_table', [
        'items'   => $documentProgress,
        'avgPct'  => $avgPct3,
      ])
    </div>

  </div>
</section>

<script>
  function switchTab(activeId) {
    document.querySelectorAll('.tab-content').forEach(el => el.classList.add('hidden'));
    document.querySelectorAll('.tab-btn').forEach(btn => {
      btn.classList.remove('bg-white', 'shadow-sm', 'text-blue-700');
      btn.classList.add('text-slate-500');
    });
    document.getElementById(activeId).classList.remove('hidden');
    const btnId = 'btn-' + activeId;
    const activeBtn = document.getElementById(btnId);
    activeBtn.classList.add('bg-white', 'shadow-sm', 'text-blue-700');
    activeBtn.classList.remove('text-slate-500');
  }
</script>
