<footer class="bg-white border-t border-[#e7ebf3] pt-20 pb-10 px-6 md:px-20">
  <div class="max-w-7xl mx-auto">
    <div class="grid grid-cols-2 md:grid-cols-4 lg:grid-cols-5 gap-12 mb-16">
      <div class="col-span-2">
        <div class="flex items-center gap-3 mb-6">
          @if(isset($app_settings['app_logo']))
            <img src="{{ asset($app_settings['app_logo']) }}" alt="Logo" class="h-8 w-auto rounded">
          @else
            <div class="bg-primary p-1.5 rounded-lg text-white">
              <span class="material-symbols-outlined block">folder_zip</span>
            </div>
          @endif
          <h2 class="text-[#0d121b] text-xl font-bold tracking-tight">{{ $app_settings['app_name'] ?? 'EduArchive' }}
          </h2>
        </div>
        <p class="text-slate-400 text-sm leading-relaxed mb-6">
          {!! $app_settings['footer_description'] ?? ($app_settings['app_description'] ?? 'Platform berstandar nasional untuk arsip digital sekolah.') !!}
        </p>
        <div class="flex gap-4">
          @if(!empty($app_settings['social_facebook']))
            <a class="w-10 h-10 rounded-full bg-slate-100 flex items-center justify-center hover:bg-primary hover:text-white transition-all"
              href="{{ $app_settings['social_facebook'] }}" target="_blank"><i class="fab fa-facebook-f"></i></a>
          @endif
          @if(!empty($app_settings['social_twitter']))
            <a class="w-10 h-10 rounded-full bg-slate-100 flex items-center justify-center hover:bg-primary hover:text-white transition-all"
              href="{{ $app_settings['social_twitter'] }}" target="_blank"><i class="fab fa-twitter"></i></a>
          @endif
          @if(!empty($app_settings['social_instagram']))
            <a class="w-10 h-10 rounded-full bg-slate-100 flex items-center justify-center hover:bg-primary hover:text-white transition-all"
              href="{{ $app_settings['social_instagram'] }}" target="_blank"><i class="fab fa-instagram"></i></a>
          @endif
          @if(!empty($app_settings['social_youtube']))
            <a class="w-10 h-10 rounded-full bg-slate-100 flex items-center justify-center hover:bg-primary hover:text-white transition-all"
              href="{{ $app_settings['social_youtube'] }}" target="_blank"><i class="fab fa-youtube"></i></a>
          @endif
        </div>
      </div>
      <div>
        <h5 class="font-bold mb-6">Produk</h5>
        <ul class="space-y-4 text-sm text-slate-500">
          @for($i = 1; $i <= 4; $i++)
            @if(!empty($app_settings['footer_prod_text_' . $i]))
              <li><a class="hover:text-primary transition-colors"
                  href="{{ $app_settings['footer_prod_url_' . $i] ?? '#' }}">{{ $app_settings['footer_prod_text_' . $i] }}</a>
              </li>
            @endif
          @endfor
          @if(empty($app_settings['footer_prod_text_1']))
            <!-- Default links if no settings -->
            <li><a class="hover:text-primary transition-colors" href="#">Untuk Dinas</a></li>
            <li><a class="hover:text-primary transition-colors" href="#">Untuk Sekolah</a></li>
          @endif
        </ul>
      </div>
      <div>
        <h5 class="font-bold mb-6">Perusahaan</h5>
        <ul class="space-y-4 text-sm text-slate-500">
          @for($i = 1; $i <= 4; $i++)
            @if(!empty($app_settings['footer_comp_text_' . $i]))
              <li><a class="hover:text-primary transition-colors"
                  href="{{ $app_settings['footer_comp_url_' . $i] ?? '#' }}">{{ $app_settings['footer_comp_text_' . $i] }}</a>
              </li>
            @endif
          @endfor
          @if(empty($app_settings['footer_comp_text_1']))
            <li><a class="hover:text-primary transition-colors" href="#">Tentang Kami</a></li>
            <li><a class="hover:text-primary transition-colors" href="#">Kontak</a></li>
          @endif
        </ul>
      </div>
      <div>
        <h5 class="font-bold mb-6">Legal</h5>
        <ul class="space-y-4 text-sm text-slate-500">
          @for($i = 1; $i <= 3; $i++)
            @if(!empty($app_settings['footer_legal_text_' . $i]))
              <li><a class="hover:text-primary transition-colors"
                  href="{{ $app_settings['footer_legal_url_' . $i] ?? '#' }}">{{ $app_settings['footer_legal_text_' . $i] }}</a>
              </li>
            @endif
          @endfor
          @if(empty($app_settings['footer_legal_text_1']))
            <li><a class="hover:text-primary transition-colors" href="#">Kebijakan Privasi</a></li>
          @endif
        </ul>
      </div>
    </div>
    <div
      class="border-t border-[#e7ebf3] pt-8 flex flex-col md:flex-row justify-between items-center gap-4 text-sm text-slate-400">
      <p>{!! $app_settings['app_footer'] ?? '© ' . date('Y') . ' EduArchive. Seluruh hak cipta dilindungi.' !!}</p>
      <p>Digitalisasi Masa Depan Pendidikan Indonesia</p>
    </div>
  </div>
</footer>