<nav class="navbar navbar-expand-lg navbar-school fixed-top">
  <div class="container">
    <a class="navbar-brand" href="{{ route('tenant.home') }}">
      @php
        $logo = tenant('logo') ? tenant_asset(tenant('logo')) : (!empty($central_branding['app_logo']) ? $central_branding['app_logo'] : asset('adminlte3/dist/img/AdminLTELogo.png'));
      @endphp
      <img src="{{ $logo }}" alt="Logo">
      <span>{{ tenant('nama_sekolah') }}</span>
    </a>
    <button class="navbar-toggler border-0 shadow-none" type="button" data-bs-toggle="collapse"
      data-bs-target="#schoolNavbar" aria-controls="schoolNavbar" aria-expanded="false" aria-label="Toggle navigation">
      <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse" id="schoolNavbar">
      <ul class="navbar-nav ms-auto align-items-center mb-2 mb-lg-0">
        <li class="nav-item">
          <a class="nav-link {{ Route::is('tenant.home') ? 'active' : '' }}"
            href="{{ route('tenant.home') }}">Beranda</a>
        </li>
        <li class="nav-item">
          <a class="nav-link {{ Route::is('tenant.profile*') ? 'active' : '' }}"
            href="{{ route('tenant.profile') }}">Profil</a>
        </li>
        <li class="nav-item">
          <a class="nav-link" href="{{ route('tenant.home') }}#fitur">Fitur</a>
        </li>
        <li class="nav-item">
          <a class="nav-link" href="{{ route('tenant.home') }}#kontak">Hubungi Kami</a>
        </li>
        <li class="nav-item ms-lg-3">
          @if(Route::has('login'))
            @auth
              @if(auth()->user()->role === 'admin_sekolah')
                <a href="{{ route('adminlembaga.dashboard') }}" class="btn btn-primary-custom">Dashboard</a>
              @elseif(auth()->user()->role === 'operator')
                <a href="{{ route('operator.dashboard') }}" class="btn btn-primary-custom">Dashboard</a>
              @endif
            @else
              <a href="{{ route('login') }}" class="btn btn-primary-custom">
                <i class="fas fa-sign-in-alt me-2"></i> Login Portal
              </a>
            @endauth
          @endif
        </li>
      </ul>
    </div>
  </div>
</nav>