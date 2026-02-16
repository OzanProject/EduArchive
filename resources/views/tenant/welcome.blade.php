@extends('tenant.layouts.master')

@section('title', 'Beranda')

@section('styles')
  <style>
    /* Hero Section */
    .hero-section {
      position: relative;
      padding: 10rem 0 6rem;
      /* Increased top padding */
      overflow: hidden;
      background: radial-gradient(circle at top right, rgba(37, 99, 235, 0.1) 0%, rgba(255, 255, 255, 0) 60%);
    }

    .hero-title {
      font-size: 3.5rem;
      font-weight: 800;
      line-height: 1.2;
      letter-spacing: -1px;
      margin-bottom: 1.5rem;
      margin-top: 1rem;
      /* Added margin top */
      background: -webkit-linear-gradient(25deg, #0f172a 0%, #2563eb 100%);
      -webkit-background-clip: text;
      -webkit-text-fill-color: transparent;
    }

    .hero-subtitle {
      font-size: 1.25rem;
      color: var(--text-muted);
      line-height: 1.6;
      margin-bottom: 2.5rem;
      font-weight: 400;
    }

    /* Feature Cards */
    .feature-card {
      background: white;
      border-radius: 16px;
      padding: 2.5rem;
      border: 1px solid rgba(226, 232, 240, 0.8);
      transition: all 0.3s ease;
      height: 100%;
      position: relative;
      overflow: hidden;
    }

    .feature-card:hover {
      transform: translateY(-5px);
      box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1), 0 10px 10px -5px rgba(0, 0, 0, 0.04);
      border-color: var(--primary);
    }

    .feature-icon-wrapper {
      width: 64px;
      height: 64px;
      border-radius: 12px;
      display: flex;
      align-items: center;
      justify-content: center;
      font-size: 1.75rem;
      margin-bottom: 1.5rem;
    }

    /* Stats Section */
    .stats-section {
      background: #0f172a;
      color: white;
      padding: 5rem 0;
      position: relative;
    }

    .stat-item h3 {
      font-size: 3rem;
      font-weight: 800;
      margin-bottom: 0.5rem;
      color: #60a5fa;
    }

    .stat-item p {
      font-size: 1.1rem;
      color: #94a3b8;
      font-weight: 500;
    }
  </style>
@endsection

@section('content')
  <!-- Hero Section -->
  <section class="hero-section text-center text-lg-start">
    <div class="container">
      <div class="row align-items-center">
        <div class="col-lg-6" data-aos="fade-right">
          <div class="d-inline-flex align-items-center bg-white border rounded-pill px-3 py-1 mb-4 shadow-sm">
            <span class="badge bg-primary rounded-pill me-2">Baru</span>
            <small class="fw-bold text-dark">Portal Akademik Terintegrasi</small>
          </div>
          <h1 class="hero-title">
            Selamat Datang di <br> {{ tenant('nama_sekolah') }}
          </h1>
          <p class="hero-subtitle pe-lg-5">
            Platform digital komprehensif untuk mendukung kegiatan belajar mengajar, administrasi, dan informasi akademik
            secara real-time dan transparan.
          </p>
          <div class="d-flex flex-column flex-sm-row gap-3 justify-content-center justify-content-lg-start">
            <a href="{{ route('login') }}" class="btn btn-primary-custom btn-lg px-5 shadow-lg">
              Akses Portal <i class="fas fa-arrow-right ms-2"></i>
            </a>
            <a href="#fitur" class="btn btn-outline-secondary btn-lg px-5 rounded-pill fw-bold border-2">
              Pelajari Fitur
            </a>
          </div>

          <div class="mt-4 d-flex flex-wrap align-items-center justify-content-center justify-content-lg-start gap-4">
            <div class="d-flex align-items-center">
              <div class="bg-primary bg-opacity-10 text-primary p-2 rounded-circle me-3">
                <i class="fas fa-check"></i>
              </div>
              <small class="fw-bold text-dark">Terakreditasi A</small>
            </div>
            <div class="d-flex align-items-center">
              <div class="bg-success bg-opacity-10 text-success p-2 rounded-circle me-3">
                <i class="fas fa-check"></i>
              </div>
              <small class="fw-bold text-dark">Kurikulum Merdeka</small>
            </div>
          </div>
        </div>
        <div class="col-lg-6 mt-5 mt-lg-0" data-aos="fade-left">
          <div class="position-relative">
            <div
              class="absolute w-100 h-100 bg-primary opacity-10 rounded-circle filter blur-3xl position-absolute top-50 start-50 translate-middle"
              style="z-index: -1; width: 300px; height: 300px; filter: blur(80px);"></div>
            <img
              src="{{ !empty($app_settings['school_hero_image']) ? $app_settings['school_hero_image'] : asset('adminlte3/dist/img/photo1.png') }}"
              class="img-fluid position-relative z-1 rounded-3 shadow-lg" alt="School Illustration"
              style="object-fit: cover; width: 100%; max-height: 400px; border-radius: 20px;">
          </div>
        </div>
      </div>
    </div>
  </section>

  <!-- Stats Section -->
  <section class="stats-section">
    <div class="container">
      <div class="row text-center g-4">
        <div class="col-md-4" data-aos="fade-up" data-aos-delay="100">
          <div class="stat-item">
            <h3 class="counter">{{ \App\Models\Student::count() }}</h3>
            <p class="text-uppercase tracking-wider">Siswa Aktif</p>
          </div>
        </div>
        <div class="col-md-4" data-aos="fade-up" data-aos-delay="200">
          <div class="stat-item">
            <h3 class="counter">{{ \App\Models\Teacher::count() }}</h3>
            <p class="text-uppercase tracking-wider">Guru & Tendik</p>
          </div>
        </div>
        <div class="col-md-4" data-aos="fade-up" data-aos-delay="300">
          <div class="stat-item">
            <h3 class="counter">{{ \App\Models\Classroom::count() }}</h3>
            <p class="text-uppercase tracking-wider">Rombongan Belajar</p>
          </div>
        </div>
      </div>
    </div>
  </section>

  <!-- Fitur Section -->
  <section id="fitur" class="py-5 bg-white">
    <div class="container py-5">
      <div class="row text-center mb-5">
        <div class="col-lg-6 mx-auto" data-aos="fade-up">
          <span class="text-primary fw-bold text-uppercase tracking-wider">Fitur Unggulan</span>
          <h2 class="fw-bold mt-2 mb-3 text-dark">Solusi Digital Sekolah</h2>
          <p class="text-muted">Memudahkan akses informasi dan manajemen data sekolah dalam satu platform terintegrasi.
          </p>
        </div>
      </div>

      <div class="row g-4">
        <div class="col-md-4" data-aos="fade-up" data-aos-delay="100">
          <div class="feature-card">
            <div class="feature-icon-wrapper bg-primary bg-opacity-10 text-primary">
              <i class="fas fa-laptop-code"></i>
            </div>
            <h4 class="fw-bold mb-3 text-dark">Digital Learning</h4>
            <p class="text-muted mb-0">Mendukung pembelajaran berbasis teknologi dengan akses materi dan tugas secara
              online.</p>
          </div>
        </div>
        <div class="col-md-4" data-aos="fade-up" data-aos-delay="200">
          <div class="feature-card">
            <div class="feature-icon-wrapper bg-success bg-opacity-10 text-success">
              <i class="fas fa-database"></i>
            </div>
            <h4 class="fw-bold mb-3 text-dark">Manajemen Data</h4>
            <p class="text-muted mb-0">Pengelolaan data siswa, guru, dan nilai yang terstruktur, aman, dan mudah diakses.
            </p>
          </div>
        </div>
        <div class="col-md-4" data-aos="fade-up" data-aos-delay="300">
          <div class="feature-card">
            <div class="feature-icon-wrapper bg-warning bg-opacity-10 text-warning">
              <i class="fas fa-bullhorn"></i>
            </div>
            <h4 class="fw-bold mb-3 text-dark">Pusat Informasi</h4>
            <p class="text-muted mb-0">Saluran informasi resmi sekolah untuk pengumuman, agenda, dan berita terkini.</p>
          </div>
        </div>
      </div>
    </div>
  </section>
@endsection