<section class="py-12 border-y border-[#e7ebf3] bg-white">
  <div class="max-w-7xl mx-auto px-6 text-center">
    <div class="mb-8">
      <h3 class="text-xl font-bold text-[#0d121b] mb-2">{{ $settings['landing_partner_title'] ?? 'DIPERCAYA OLEH' }}
      </h3>
      <p class="text-slate-500 text-sm max-w-2xl mx-auto">
        {{ $settings['landing_partner_desc'] ?? 'Instansi pendidikan terkemuka di seluruh Indonesia' }}
      </p>
    </div>

    <div
      class="flex flex-wrap justify-center items-center gap-8 md:gap-12 opacity-70 hover:opacity-100 transition-opacity">
      @for($i = 1; $i <= 5; $i++)
        @if(isset($settings['landing_partner_logo_' . $i]) && $settings['landing_partner_logo_' . $i])
          <img class="h-10 w-auto object-contain grayscale hover:grayscale-0 transition-all duration-300"
            src="{{ asset($settings['landing_partner_logo_' . $i]) }}" alt="Partner {{ $i }}" />
        @else
          @if(config('app.env') === 'local')
            <!-- Placeholder for local dev if empty -->
            <div
              class="h-10 w-32 bg-slate-100 rounded flex items-center justify-center text-xs text-slate-400 border border-slate-200 border-dashed">
              Slot Partner {{ $i }}
            </div>
          @endif
        @endif
      @endfor
    </div>
  </div>
</section>