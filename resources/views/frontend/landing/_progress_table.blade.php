{{-- Reusable progress table partial --}}
{{-- Variables: $items, $totalSent, $totalAll, $totalSisa, $avgPct, $labelSent, $labelSisa, $labelPct, $labelSentCard, $labelSisaCard, $noteBar --}}
<div class="bg-white rounded-2xl shadow-sm border border-slate-100 overflow-hidden">

  {{-- Summary Cards --}}
  <div class="grid grid-cols-2 md:grid-cols-4 gap-0 border-b border-slate-100">
    <div class="p-5 text-center border-r border-slate-100">
      <div class="text-2xl font-bold text-blue-600">{{ count($items) }}</div>
      <div class="text-xs text-slate-500 mt-1 uppercase tracking-wide">Total Lembaga</div>
    </div>
    <div class="p-5 text-center border-r border-slate-100">
      <div class="text-2xl font-bold text-emerald-600">{{ number_format($totalSent) }}</div>
      <div class="text-xs text-slate-500 mt-1 uppercase tracking-wide">{{ $labelSentCard }}</div>
    </div>
    <div class="p-5 text-center border-r border-slate-100">
      <div class="text-2xl font-bold text-amber-500">{{ number_format($totalSisa) }}</div>
      <div class="text-xs text-slate-500 mt-1 uppercase tracking-wide">{{ $labelSisaCard }}</div>
    </div>
    <div class="p-5 text-center">
      <div class="text-2xl font-bold {{ $avgPct >= 80 ? 'text-emerald-600' : ($avgPct >= 50 ? 'text-amber-500' : 'text-red-500') }}">{{ $avgPct }}%</div>
      <div class="text-xs text-slate-500 mt-1 uppercase tracking-wide">Rata-rata Progres</div>
    </div>
  </div>

  {{-- Overall Bar --}}
  <div class="px-6 py-3 bg-slate-50 border-b border-slate-100">
    <div class="flex justify-between text-xs text-slate-500 mb-1">
      <span>{{ $noteBar }}</span>
      <span class="font-semibold">{{ $avgPct }}%</span>
    </div>
    <div class="w-full bg-slate-200 rounded-full h-2">
      <div class="h-2 rounded-full transition-all duration-700
        {{ $avgPct >= 80 ? 'bg-emerald-500' : ($avgPct >= 50 ? 'bg-amber-400' : 'bg-red-400') }}"
        style="width: {{ $avgPct }}%"></div>
    </div>
  </div>

  {{-- Table --}}
  <div class="overflow-x-auto">
    <table class="w-full text-sm">
      <thead>
        <tr class="bg-slate-50 border-b border-slate-100">
          <th class="px-4 py-3 text-left text-xs font-semibold text-slate-500 uppercase tracking-wider w-10">No</th>
          <th class="px-4 py-3 text-left text-xs font-semibold text-slate-500 uppercase tracking-wider">Nama Sekolah</th>
          <th class="px-4 py-3 text-center text-xs font-semibold text-slate-500 uppercase tracking-wider w-32">{{ $labelPct }}</th>
          <th class="px-4 py-3 text-center text-xs font-semibold text-slate-500 uppercase tracking-wider w-24">Total</th>
          <th class="px-4 py-3 text-center text-xs font-semibold text-slate-500 uppercase tracking-wider w-28">{{ $labelSent }}</th>
          <th class="px-4 py-3 text-center text-xs font-semibold text-slate-500 uppercase tracking-wider w-28">{{ $labelSisa }}</th>
        </tr>
      </thead>
      <tbody class="divide-y divide-slate-50">
        @forelse($items as $idx => $row)
          <tr class="hover:bg-slate-50 transition-colors">
            <td class="px-4 py-3 text-slate-400 text-center text-xs">{{ $idx + 1 }}</td>
            <td class="px-4 py-3">
              <div class="font-semibold text-slate-800 text-sm">{{ $row['nama_sekolah'] }}</div>
              <div class="text-xs text-slate-400 mt-0.5">{{ $row['jenjang'] }} &bull; NPSN: {{ $row['npsn'] }}</div>
            </td>
            <td class="px-4 py-3">
              <div class="flex items-center gap-2">
                <div class="flex-1 bg-slate-100 rounded-full h-1.5 min-w-[50px]">
                  <div class="h-1.5 rounded-full
                    {{ $row['pct'] == 100 ? 'bg-emerald-500' : ($row['pct'] >= 75 ? 'bg-blue-500' : ($row['pct'] >= 50 ? 'bg-amber-400' : 'bg-red-400')) }}"
                    style="width: {{ $row['pct'] }}%"></div>
                </div>
                <span class="text-xs font-bold w-10 text-right
                  {{ $row['pct'] == 100 ? 'text-emerald-600' : ($row['pct'] >= 75 ? 'text-blue-600' : ($row['pct'] >= 50 ? 'text-amber-500' : 'text-red-500')) }}">
                  {{ $row['pct'] }}%
                </span>
              </div>
            </td>
            <td class="px-4 py-3 text-center font-medium text-slate-700">{{ number_format($row['total']) }}</td>
            <td class="px-4 py-3 text-center font-medium text-emerald-600">{{ number_format($row['sent']) }}</td>
            <td class="px-4 py-3 text-center font-medium {{ $row['sisa'] > 0 ? 'text-red-500' : 'text-slate-300' }}">
              {{ $row['sisa'] > 0 ? number_format($row['sisa']) : '✓' }}
            </td>
          </tr>
        @empty
          <tr>
            <td colspan="6" class="px-4 py-10 text-center text-slate-400 text-sm">Belum ada data lembaga.</td>
          </tr>
        @endforelse
      </tbody>
      @if(count($items) > 0)
      <tfoot>
        <tr class="bg-slate-50 border-t-2 border-slate-200">
          <td colspan="2" class="px-4 py-3 text-xs font-bold text-slate-600 uppercase">Total Keseluruhan</td>
          <td class="px-4 py-3 text-center text-xs font-bold text-slate-700">{{ $avgPct }}%</td>
          <td class="px-4 py-3 text-center text-xs font-bold text-slate-700">{{ number_format($totalAll) }}</td>
          <td class="px-4 py-3 text-center text-xs font-bold text-emerald-600">{{ number_format($totalSent) }}</td>
          <td class="px-4 py-3 text-center text-xs font-bold text-red-500">{{ number_format($totalSisa) }}</td>
        </tr>
      </tfoot>
      @endif
    </table>
  </div>

  {{-- Legend --}}
  <div class="px-6 py-4 border-t border-slate-100 flex flex-wrap items-center gap-4 text-xs text-slate-500">
    <span class="font-medium text-slate-600 mr-1">Keterangan:</span>
    <span class="flex items-center gap-1.5"><span class="w-3 h-3 rounded-full bg-emerald-500 inline-block"></span> 100% Lengkap</span>
    <span class="flex items-center gap-1.5"><span class="w-3 h-3 rounded-full bg-blue-500 inline-block"></span> ≥ 75%</span>
    <span class="flex items-center gap-1.5"><span class="w-3 h-3 rounded-full bg-amber-400 inline-block"></span> 50–74%</span>
    <span class="flex items-center gap-1.5"><span class="w-3 h-3 rounded-full bg-red-400 inline-block"></span> &lt; 50%</span>
    <span class="ml-auto text-slate-400"><i class="fas fa-sync-alt mr-1" style="font-size:10px"></i>Data real-time</span>
  </div>
</div>
