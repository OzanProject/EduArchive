<!DOCTYPE html>
<html lang="id">

<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>{{ tenant('nama_sekolah') }} | Portal Sekolah</title>

  <!-- Google Font: Inter -->
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
  <!-- Font Awesome -->
  <link rel="stylesheet" href="{{ asset('adminlte3/plugins/fontawesome-free/css/all.min.css') }}">
  <!-- AdminLTE Theme -->
  <link rel="stylesheet" href="{{ asset('adminlte3/dist/css/adminlte.min.css') }}">
  <!-- Custom CSS -->
  <link rel="stylesheet" href="{{ asset('css/modern-admin.css') }}">

  <style>
    /* Reset & Base */
    body {
      font-family: 'Inter', sans-serif;
      background-color: #f8fafc;
      overflow-x: hidden;
    }

    /* Navbar */
    .school-navbar {
      background-color: #ffffff;
      box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.05);
      padding: 0.75rem 0;
    }

    .navbar-brand {
      font-weight: 700;
      color: var(--primary);
      font-size: 1.25rem;
    }

    .nav-link {
      color: var(--secondary) !important;
      font-weight: 500;
      margin-left: 1rem;
      transition: color 0.2s;
    }

    .nav-link:hover {
      color: var(--primary) !important;
    }

    .btn-login-nav {
      background-color: var(--primary);
      color: white !important;
      padding: 0.5rem 1.25rem;
      border-radius: 9999px;
      font-size: 0.9rem;
      box-shadow: 0 4px 6px -1px rgba(15, 23, 42, 0.2);
    }

    .btn-login-nav:hover {
      background-color: var(--primary-light);
      transform: translateY(-1px);
    }

    /* Hero Section */
    .hero-section {
      position: relative;
      background: radial-gradient(circle at top right, #f1f5f9 0%, #ffffff 100%);
      padding: 6rem 0 4rem;
      overflow: hidden;
    }

    .hero-badge {
      display: inline-flex;
      align-items: center;
      padding: 0.5rem 1rem;
      background-color: rgba(59, 130, 246, 0.1);
      color: var(--info);
      border-radius: 9999px;
      font-size: 0.875rem;
      font-weight: 600;
      margin-bottom: 1.5rem;
    }

    .school-logo-hero {
      width: 100px;
      height: 100px;
      object-fit: contain;
      margin-bottom: 1.5rem;
      filter: drop-shadow(0 4px 6px rgba(0, 0, 0, 0.1));
    }

    /* Features Section */
    .feature-card {
      background: white;
      padding: 2rem;
      border-radius: 1rem;
      transition: all 0.3s ease;
      border: 1px solid #e2e8f0;
      height: 100%;
    }

    .feature-card:hover {
      transform: translateY(-5px);
      box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.05);
      border-color: var(--info);
    }

    .feature-icon {
      width: 3rem;
      height: 3rem;
      background-color: rgba(59, 130, 246, 0.1);
      color: var(--info);
      border-radius: 0.75rem;
      display: flex;
      align-items: center;
      justify-content: center;
      font-size: 1.5rem;
      margin-bottom: 1.5rem;
    }

    /* Footer */
    .footer-section {
      background-color: #1e293b;
      color: #94a3b8;
      padding: 3rem 0;
      margin-top: auto;
    }
  </style>
</head>

<body class="d-flex flex-column min-vh-100">

  <!-- 1. Navbar -->
  <nav class="navbar navbar-expand-lg school-navbar fixed-top">
    <div class="container">
      <a class="navbar-brand d-flex align-items-center" href="#">
        @php
          $dinas_logo = \Illuminate\Support\Facades\Cache::get('dinas_app_logo');
          $logo = tenant('logo') ? tenant_asset(tenant('logo')) : ($dinas_logo ?? asset('adminlte/dist/img/AdminLTELogo.png'));
         @endphp
        <img src="{{ $logo }}" alt="Logo" height="30" class="mr-2 rounded-circle">
        {{ tenant('nama_sekolah') }}
      </a>
      <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav">
        <span class="fas fa-bars text-secondary"></span>
      </button>
      <div class="collapse navbar-collapse" id="navbarNav">
        <ul class="navbar-nav ml-auto align-items-center">
          <li class="nav-item"><a class="nav-link" href="#">Beranda</a></li>
          <li class="nav-item"><a class="nav-link" href="#fitur">Fitur</a></li>
          <li class="nav-item"><a class="nav-link" href="#kontak">Kontak</a></li>
          <li class="nav-item">
            @if(Route::has('login'))
              @auth
                @if(auth()->user()->role === 'admin_sekolah')
                  <a href="{{ route('adminlembaga.dashboard') }}" class="btn btn-login-nav ml-3">Dashboard</a>
                @elseif(auth()->user()->role === 'operator')
                  <a href="{{ route('operator.dashboard') }}" class="btn btn-login-nav ml-3">Dashboard</a>
                @endif
              @else
                <a href="{{ route('login') }}" class="btn btn-login-nav ml-3">
                  <i class="fas fa-sign-in-alt mr-1"></i> Login Portal
                </a>
              @endauth
            @endif
          </li>
        </ul>
      </div>
    </div>
  </nav>

  <!-- 2. Hero Section -->
  <section class="hero-section">
    <div class="container">
      <div class="row align-items-center">
        <div class="col-lg-6 mb-5 mb-lg-0 text-center text-lg-left">
          <div class="hero-badge">
            <i class="fas fa-certificate mr-2"></i> Akreditasi A
          </div>
          <h1 class="display-4 font-weight-bold text-dark mb-3" style="line-height: 1.2;">
            Selamat Datang di Portal <br> <span class="text-primary">{{ tenant('nama_sekolah') }}</span>
          </h1>
          <p class="lead text-muted mb-4">
            Pusat layanan informasi dan administrasi akademik digital.
            Mengelola data siswa, guru, dan arsip dokumen sekolah secara terintegrasi.
          </p>

          <div class="d-flex justify-content-center justify-content-lg-start">
            <a href="{{ route('login') }}" class="btn btn-primary btn-lg px-4 mr-3 rounded-pill shadow-sm">
              Akses Portal Siswa
            </a>
            <a href="#fitur" class="btn btn-outline-secondary btn-lg px-4 rounded-pill">
              Pelajari Lebih Lanjut
            </a>
          </div>

          <!-- Quick Stats -->
          <div class="row mt-5">
            <div class="col-auto">
              <h4 class="font-weight-bold text-dark mb-0">
                {{ \App\Models\Student::count() }}
              </h4>
              <small class="text-muted text-uppercase font-weight-bold">Siswa Aktif</small>
            </div>
            <div class="col-auto border-left pl-4 ml-3">
              <h4 class="font-weight-bold text-dark mb-0">
                {{ \App\Models\Teacher::count() }}
              </h4>
              <small class="text-muted text-uppercase font-weight-bold">Pengajar</small>
            </div>
          </div>

        </div>
        <div class="col-lg-6 text-center">
          <img src="https://cdni.iconscout.com/illustration/premium/thumb/school-building-2911964-2428665.png"
            class="img-fluid" alt="School Illustration" style="max-height: 450px;">
        </div>
      </div>
    </div>
  </section>

  <!-- 3. Features -->
  <section id="fitur" class="py-5 bg-white">
    <div class="container">
      <div class="text-center mb-5">
        <h2 class="font-weight-bold text-dark">Layanan Unggulan</h2>
        <p class="text-muted">Fasilitas digital yang tersedia di portal ini.</p>
      </div>
      <div class="row">
        <div class="col-md-4 mb-4">
          <div class="feature-card">
            <div class="feature-icon text-primary bg-light-primary">
              <i class="fas fa-file-alt"></i>
            </div>
            <h5 class="font-weight-bold text-dark">Arsip Digital</h5>
            <p class="text-muted">Akses dokumen akademik seperti rapor dan ijazah secara digital kapan saja.</p>
          </div>
        </div>
        <div class="col-md-4 mb-4">
          <div class="feature-card">
            <div class="feature-icon text-success bg-light-success">
              <i class="fas fa-user-graduate"></i>
            </div>
            <h5 class="font-weight-bold text-dark">Data Siswa</h5>
            <p class="text-muted">Informasi data diri dan histori akademik siswa yang terintegrasi.</p>
          </div>
        </div>
        <div class="col-md-4 mb-4">
          <div class="feature-card">
            <div class="feature-icon text-warning bg-light-warning">
              <i class="fas fa-bullhorn"></i>
            </div>
            <h5 class="font-weight-bold text-dark">Pengumuman</h5>
            <p class="text-muted">Update informasi terbaru kegiatan sekolah dan jadwal akademik.</p>
          </div>
        </div>
      </div>
    </div>
  </section>

  <!-- 4. Footer -->
  <footer class="footer-section" id="kontak">
    <div class="container">
      <div class="row align-items-center">
        <div class="col-md-6 text-center text-md-left mb-3 mb-md-0">
          <h5 class="font-weight-bold text-white mb-1">{{ tenant('nama_sekolah') }}</h5>
          <p class="small mb-0">{{ tenant('alamat') ?? 'Alamat sekolah belum diatur.' }}</p>
          <div class="mt-2 text-white-50">
            <i class="fas fa-envelope mr-2"></i> admin@{{ tenant()->domains->first()->domain ?? 'sekolah.id' }}
          </div>
        </div>
        <div class="col-md-6 text-center text-md-right">
          <p class="small text-white-50 mb-0">
            &copy; {{ date('Y') }} <strong>{{ config('app.name') }}</strong>. <br>
            Dikelola oleh Dinas Pendidikan.
          </p>
        </div>
      </div>
    </div>
  </footer>

  <!-- jQuery -->
  <script src="{{ asset('adminlte3/plugins/jquery/jquery.min.js') }}"></script>
  <!-- Bootstrap 4 -->
  <script src="{{ asset('adminlte3/plugins/bootstrap/js/bootstrap.bundle.min.js') }}"></script>

</body>

</html>