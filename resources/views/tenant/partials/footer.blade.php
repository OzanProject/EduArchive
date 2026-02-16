<footer id="kontak">
  <div class="container">
    <div class="row gy-4">
      <div class="col-lg-5 col-md-12 footer-info">
        <h5>{{ tenant('nama_sekolah') }}</h5>
        <p class="text-secondary mb-4">{{ tenant('alamat') ?? 'Alamat sekolah belum diatur.' }}</p>
        <div class="social-links d-flex mt-4">
          @if(!empty($app_settings['school_twitter']))
            <a href="{{ $app_settings['school_twitter'] }}" class="twitter me-3" target="_blank"><i
                class="fab fa-twitter"></i></a>
          @endif
          @if(!empty($app_settings['school_facebook']))
            <a href="{{ $app_settings['school_facebook'] }}" class="facebook me-3" target="_blank"><i
                class="fab fa-facebook"></i></a>
          @endif
          @if(!empty($app_settings['school_instagram']))
            <a href="{{ $app_settings['school_instagram'] }}" class="instagram me-3" target="_blank"><i
                class="fab fa-instagram"></i></a>
          @endif
          @if(!empty($app_settings['school_youtube']))
            <a href="{{ $app_settings['school_youtube'] }}" class="youtube me-3" target="_blank"><i
                class="fab fa-youtube"></i></a>
          @endif
        </div>
      </div>

      <div class="col-lg-2 col-6 footer-links">
        <h6>Link Cepat</h6>
        <ul class="list-unstyled">
          <li class="mb-2"><a href="{{ route('tenant.home') }}">Beranda</a></li>
          <li class="mb-2"><a href="{{ route('tenant.profile') }}">Profil Sekolah</a></li>
          <li class="mb-2"><a href="{{ route('login') }}">Login Portal</a></li>
        </ul>
      </div>

      <div class="col-lg-2 col-6 footer-links">
        <h6>Layanan</h6>
        <ul class="list-unstyled">
          <li class="mb-2"><a href="#">Info PPDB</a></li>
          <li class="mb-2"><a href="#">Agenda Sekolah</a></li>
          <li class="mb-2"><a href="#">Galeri Kegiatan</a></li>
        </ul>
      </div>

      <div class="col-lg-3 col-md-12 footer-contact text-center text-md-start">
        <h6>Kontak</h6>
        <p>
          <strong>Phone:</strong> {{ $app_settings['school_phone'] ?? '-' }}<br>
          <strong>Email:</strong> {{ $app_settings['school_email'] ?? '-' }}<br>
        </p>
      </div>

    </div>
  </div>
  <div class="container mt-4 pt-4 border-top border-secondary border-opacity-25 text-center">
    <div class="copyright">
      &copy; Copyright <strong><span>{{ tenant('nama_sekolah') }}</span></strong>. All Rights Reserved
    </div>
    <div class="credits text-secondary small mt-2">
      Dikelola oleh Dinas Pendidikan
    </div>
  </div>
</footer>