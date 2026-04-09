{{-- Document Progress Table with Validation Status --}}
<div class="bg-white rounded-2xl shadow-sm border border-slate-100 overflow-hidden">

  {{-- Summary Cards --}}
  @php
    $totalApprovedAll  = collect($items)->sum('approved');
    $totalPendingAll   = collect($items)->sum('pending');
    $totalRejectedAll  = collect($items)->sum('rejected');
    $totalDocsAll      = collect($items)->sum('total_docs');
  @endphp

  <div class="grid grid-cols-2 md:grid-cols-4 gap-0 border-b border-slate-100">
    <div class="p-5 text-center border-r border-slate-100">
      <div class="text-2xl font-bold text-blue-600">{{ number_format($totalDocsAll) }}</div>
      <div class="text-xs text-slate-500 mt-1 uppercase tracking-wide">Total File Terupload</div>
    </div>
    <div class="p-5 text-center border-r border-slate-100">
      <div class="text-2xl font-bold text-emerald-600">{{ number_format($totalApprovedAll) }}</div>
      <div class="text-xs text-slate-500 mt-1 uppercase tracking-wide">Disetujui ✓</div>
    </div>
    <div class="p-5 text-center border-r border-slate-100">
      <div class="text-2xl font-bold text-amber-500">{{ number_format($totalPendingAll) }}</div>
      <div class="text-xs text-slate-500 mt-1 uppercase tracking-wide">Menunggu Validasi</div>
    </div>
    <div class="p-5 text-center">
      <div class="text-2xl font-bold text-red-500">{{ number_format($totalRejectedAll) }}</div>
      <div class="text-xs text-slate-500 mt-1 uppercase tracking-wide">Ditolak / Revisi</div>
    </div>
  </div>

  {{-- Overall Bar --}}
  <div class="px-6 py-3 bg-slate-50 border-b border-slate-100">
    <div class="flex justify-between text-xs text-slate-500 mb-1">
      <span>Siswa yang sudah upload minimal 1 dokumen dari total seluruh siswa</span>
      <span class="font-semibold">{{ $avgPct }}%</span>
    </div>
    <div class="w-full bg-slate-100 rounded-full h-2 flex overflow-hidden">
      @if($totalDocsAll > 0)
        <div class="h-full bg-emerald-500 transition-all" style="width: {{ $totalDocsAll > 0 ? round(($totalApprovedAll/$totalDocsAll)*100) : 0 }}%" title="Disetujui"></div>
        <div class="h-full bg-amber-400 transition-all" style="width: {{ $totalDocsAll > 0 ? round(($totalPendingAll/$totalDocsAll)*100) : 0 }}%" title="Pending"></div>
        <div class="h-full bg-red-400 transition-all" style="width: {{ $totalDocsAll > 0 ? round(($totalRejectedAll/$totalDocsAll)*100) : 0 }}%" title="Ditolak"></div>
      @else
        <div class="h-full bg-slate-200 w-full"></div>
      @endif
    </div>
    <div class="flex gap-4 mt-1.5 text-xs text-slate-400">
      <span class="flex items-center gap-1"><span class="w-2 h-2 rounded-full bg-emerald-500 inline-block"></span> Disetujui: {{ $totalApprovedAll }}</span>
      <span class="flex items-center gap-1"><span class="w-2 h-2 rounded-full bg-amber-400 inline-block"></span> Pending: {{ $totalPendingAll }}</span>
      <span class="flex items-center gap-1"><span class="w-2 h-2 rounded-full bg-red-400 inline-block"></span> Ditolak: {{ $totalRejectedAll }}</span>
    </div>
  </div>

  {{-- Table --}}
  <div class="overflow-x-auto">
    <table class="w-full text-sm">
      <thead>
        <tr class="bg-slate-50 border-b border-slate-100">
          <th class="px-3 py-3 text-left text-xs font-semibold text-slate-500 uppercase tracking-wider w-8">No</th>
          <th class="px-3 py-3 text-left text-xs font-semibold text-slate-500 uppercase tracking-wider">Nama Sekolah</th>
          <th class="px-3 py-3 text-center text-xs font-semibold text-slate-500 uppercase tracking-wider w-20">Upload %</th>
          <th class="px-3 py-3 text-center text-xs font-semibold text-slate-500 uppercase tracking-wider w-20">Total Siswa</th>
          <th class="px-3 py-3 text-center text-xs font-semibold text-slate-500 uppercase tracking-wider w-20">Berdokumen</th>
          <th class="px-3 py-3 text-center text-xs font-semibold text-slate-500 uppercase tracking-wider w-20">Total File</th>
          <th class="px-3 py-3 text-center text-xs font-semibold text-emerald-600 uppercase tracking-wider w-24">✓ Disetujui</th>
          <th class="px-3 py-3 text-center text-xs font-semibold text-amber-500 uppercase tracking-wider w-24">⏳ Pending</th>
          <th class="px-3 py-3 text-center text-xs font-semibold text-red-500 uppercase tracking-wider w-24">✗ Ditolak</th>
        </tr>
      </thead>
      <tbody class="divide-y divide-slate-50">
        @forelse($items as $idx => $row)
          <tr class="hover:bg-slate-50 transition-colors">
            <td class="px-3 py-3 text-slate-400 text-center text-xs">{{ $idx + 1 }}</td>
            <td class="px-3 py-3">
              <div class="font-semibold text-slate-800 text-sm">{{ $row['nama_sekolah'] }}</div>
              <div class="text-xs text-slate-400 mt-0.5">{{ $row['jenjang'] }} &bull; NPSN: {{ $row['npsn'] }}</div>
            </td>
            <td class="px-3 py-3">
              <div class="flex items-center gap-1.5">
                <div class="flex-1 bg-slate-100 rounded-full h-1.5">
                  <div class="h-1.5 rounded-full
                    {{ $row['pct'] == 100 ? 'bg-emerald-500' : ($row['pct'] >= 75 ? 'bg-blue-500' : ($row['pct'] >= 50 ? 'bg-amber-400' : 'bg-red-400')) }}"
                    style="width: {{ $row['pct'] }}%"></div>
                </div>
                <span class="text-xs font-bold
                  {{ $row['pct'] == 100 ? 'text-emerald-600' : ($row['pct'] >= 75 ? 'text-blue-600' : ($row['pct'] >= 50 ? 'text-amber-500' : 'text-red-500')) }}">{{ $row['pct'] }}%</span>
              </div>
            </td>
            <td class="px-3 py-3 text-center text-slate-700 font-medium text-xs">{{ number_format($row['total']) }}</td>
            <td class="px-3 py-3 text-center font-medium text-xs {{ $row['sisa'] > 0 ? 'text-slate-600' : 'text-emerald-600' }}">
              {{ number_format($row['sent']) }}
              @if($row['sisa'] > 0)
                <div class="text-red-400 text-xs">({{ $row['sisa'] }} belum)</div>
              @endif
            </td>
            <td class="px-3 py-3 text-center text-slate-600 font-medium text-xs">{{ number_format($row['total_docs']) }}</td>
            <td class="px-3 py-3 text-center">
              @if($row['approved'] > 0)
                <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-full text-xs font-semibold bg-emerald-100 text-emerald-700">
                  ✓ {{ number_format($row['approved']) }}
                </span>
              @else
                <span class="text-slate-300 text-xs">-</span>
              @endif
            </td>
            <td class="px-3 py-3 text-center">
              @if($row['pending'] > 0)
                <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-full text-xs font-semibold bg-amber-100 text-amber-700">
                  ⏳ {{ number_format($row['pending']) }}
                </span>
              @else
                <span class="text-slate-300 text-xs">-</span>
              @endif
            </td>
            <td class="px-3 py-3 text-center">
              @if($row['rejected'] > 0)
                <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-full text-xs font-semibold bg-red-100 text-red-700">
                  ✗ {{ number_format($row['rejected']) }}
                </span>
              @else
                <span class="text-slate-300 text-xs">-</span>
              @endif
            </td>
          </tr>
        @empty
          <tr>
            <td colspan="9" class="px-4 py-10 text-center text-slate-400 text-sm">Belum ada data dokumen lembaga.</td>
          </tr>
        @endforelse
      </tbody>
      @if(count($items) > 0)
      <tfoot>
        <tr class="bg-slate-50 border-t-2 border-slate-200">
          <td colspan="3" class="px-3 py-3 text-xs font-bold text-slate-600 uppercase">Total Keseluruhan</td>
          <td class="px-3 py-3 text-center text-xs font-bold text-slate-700">{{ number_format(collect($items)->sum('total')) }}</td>
          <td class="px-3 py-3 text-center text-xs font-bold text-slate-700">{{ number_format(collect($items)->sum('sent')) }}</td>
          <td class="px-3 py-3 text-center text-xs font-bold text-slate-700">{{ number_format($totalDocsAll) }}</td>
          <td class="px-3 py-3 text-center text-xs font-bold text-emerald-600">{{ number_format($totalApprovedAll) }}</td>
          <td class="px-3 py-3 text-center text-xs font-bold text-amber-500">{{ number_format($totalPendingAll) }}</td>
          <td class="px-3 py-3 text-center text-xs font-bold text-red-500">{{ number_format($totalRejectedAll) }}</td>
        </tr>
      </tfoot>
      @endif
    </table>
  </div>

  {{-- Legend --}}
  <div class="px-6 py-4 border-t border-slate-100 flex flex-wrap items-center gap-4 text-xs text-slate-500">
    <span class="flex items-center gap-1.5 text-emerald-700 font-semibold">✓ Disetujui</span> — dokumen sudah divalidasi Super Admin
    <span class="flex items-center gap-1.5 text-amber-600 font-semibold">⏳ Pending</span> — menunggu validasi
    <span class="flex items-center gap-1.5 text-red-600 font-semibold">✗ Ditolak</span> — perlu revisi
    <span class="ml-auto text-slate-400"><i class="fas fa-sync-alt mr-1" style="font-size:10px"></i>Data real-time</span>
  </div>
</div>
