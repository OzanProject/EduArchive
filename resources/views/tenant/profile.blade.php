@extends('tenant.layouts.master')

@section('title', 'Profil Sekolah')

@section('styles')
  <style>
    /* Profile Specific Styles - leveraging master layout vars */
    .profile-header-card {
      background: white;
      border-radius: 16px;
      border: 1px solid rgba(226, 232, 240, 0.8);
      overflow: hidden;
      height: 100%;
    }

    .profile-cover {
      height: 120px;
      background: linear-gradient(135deg, var(--primary) 0%, #60a5fa 100%);
    }

    .profile-avatar-wrapper {
      margin-top: -60px;
      padding: 0 2rem;
      margin-bottom: 1rem;
    }

    .profile-avatar {
      width: 120px;
      height: 120px;
      border-radius: 16px;
      border: 4px solid white;
      box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1);
      background: white;
      object-fit: contain;
      padding: 10px;
    }

    .info-list-item {
      padding: 1rem 0;
      border-bottom: 1px dashed var(--card-border);
      display: flex;
      align-items: flex-start;
      gap: 1rem;
    }

    .info-list-item:last-child {
      border-bottom: none;
    }

    .info-icon {
      width: 40px;
      height: 40px;
      background: #eff6ff;
      color: var(--primary);
      border-radius: 10px;
      display: flex;
      align-items: center;
      justify-content: center;
      flex-shrink: 0;
    }

    /* Detail Cards */
    .detail-card {
      background: white;
      border-radius: 12px;
      padding: 1.5rem;
      border: 1px solid var(--card-border);
      transition: all 0.2s ease;
      cursor: pointer;
      height: 100%;
      position: relative;
      overflow: hidden;
    }

    .detail-card:hover {
      transform: translateY(-3px);
      box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.05);
      border-color: var(--primary);
    }

    .detail-card::after {
      content: '\f054';
      font-family: 'Font Awesome 6 Free';
      font-weight: 900;
      position: absolute;
      right: 1.5rem;
      top: 50%;
      transform: translateY(-50%);
      color: var(--card-border);
      transition: right 0.2s;
    }

    .detail-card:hover::after {
      right: 1rem;
      color: var(--primary);
    }

    .detail-icon {
      width: 48px;
      height: 48px;
      border-radius: 10px;
      display: flex;
      align-items: center;
      justify-content: center;
      font-size: 1.5rem;
      margin-bottom: 1rem;
    }

    /* Stat Box in Grid */
    .stat-grid-box {
      background: white;
      border-radius: 12px;
      border: 1px solid var(--card-border);
      padding: 1.5rem;
      text-align: center;
      height: 100%;
    }

    .stat-grid-box h3 {
      font-size: 2rem;
      font-weight: 800;
      color: var(--primary);
      margin-bottom: 0.25rem;
    }
  </style>
@endsection

@section('content')
  <div class="container py-5">

    <!-- Breadcrumb -->
    <nav aria-label="breadcrumb" class="mb-4">
      <ol class="breadcrumb bg-transparent p-0">
        <li class="breadcrumb-item"><a href="{{ route('tenant.home') }}"
            class="text-decoration-none text-muted">Beranda</a></li>
        <li class="breadcrumb-item active text-primary" aria-current="page">Profil Sekolah</li>
      </ol>
    </nav>

    <div class="row g-4">
      <!-- Left Column: School Identity -->
      <div class="col-lg-4">
        <div class="profile-header-card shadow-sm" data-aos="fade-up">
          <div class="profile-cover"></div>
          <div class="profile-avatar-wrapper text-center">
            @if(tenant('logo'))
              <img src="{{ tenant_asset(tenant('logo')) }}" alt="Logo" class="profile-avatar">
            @else
              <img
                src="{{ !empty($central_branding['app_logo']) ? $central_branding['app_logo'] : asset('adminlte3/dist/img/AdminLTELogo.png') }}"
                alt="Default Logo" class="profile-avatar">
            @endif
            <h4 class="mt-3 fw-bold mb-1">{{ tenant('nama_sekolah') }}</h4>
            <span class="badge bg-primary bg-opacity-10 text-primary rounded-pill px-3">Terakreditasi A</span>
            <p class="text-muted small mt-2">{{ tenant('alamat') ?? 'Alamat belum diisi.' }}</p>
          </div>

          <div class="p-4 pt-2">
            <hr class="text-muted alpha-10">
            <div class="info-list">
              <div class="info-list-item">
                <div class="info-icon"><i class="fas fa-user-tie"></i></div>
                <div>
                  <small class="text-muted d-block uppercase tracking-wide" style="font-size: 0.75rem;">Kepala
                    Sekolah</small>
                  <span class="fw-bold text-dark">{{ $settings['school_headmaster_name'] ?? '-' }}</span>
                </div>
              </div>
              <div class="info-list-item">
                <div class="info-icon"><i class="fas fa-headset"></i></div>
                <div>
                  <small class="text-muted d-block uppercase tracking-wide" style="font-size: 0.75rem;">Operator</small>
                  <span class="fw-bold text-dark">{{ $settings['school_operator_name'] ?? '-' }}</span>
                </div>
              </div>
              <div class="info-list-item">
                <div class="info-icon"><i class="fas fa-phone"></i></div>
                <div>
                  <small class="text-muted d-block uppercase tracking-wide" style="font-size: 0.75rem;">Telepon</small>
                  <span class="fw-bold text-dark">{{ $settings['school_phone'] ?? '-' }}</span>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>

      <!-- Right Column: Details & Stats -->
      <div class="col-lg-8">

        <!-- Stats Overview -->
        <div class="row g-3 mb-4" data-aos="fade-up" data-aos-delay="100">
          <div class="col-6 col-md-3">
            <div class="stat-grid-box">
              <h3>{{ \App\Models\Teacher::count() }}</h3>
              <span class="text-muted small fw-bold text-uppercase">Guru</span>
            </div>
          </div>
          <div class="col-6 col-md-3">
            <div class="stat-grid-box">
              <h3>{{ \App\Models\Student::count() }}</h3>
              <span class="text-muted small fw-bold text-uppercase">Siswa</span>
            </div>
          </div>
          <div class="col-6 col-md-3">
            <div class="stat-grid-box">
              <h3>{{ \App\Models\Classroom::count() }}</h3>
              <span class="text-muted small fw-bold text-uppercase">Rombel</span>
            </div>
          </div>
          <div class="col-6 col-md-3">
            <div class="stat-grid-box">
              <h3>{{ $settings['school_capacity'] ?? '-' }}</h3>
              <span class="text-muted small fw-bold text-uppercase">Daya Tampung</span>
            </div>
          </div>
        </div>

        <!-- Detail Menu Grid -->
        <h5 class="fw-bold mb-3 text-dark border-start border-4 border-primary ps-3">Detail Informasi</h5>
        <div class="row g-3">
          <!-- Siswa -->
          <div class="col-md-6" data-aos="fade-up" data-aos-delay="150">
            <div class="detail-card" onclick="openDetailModal('Siswa')">
              <div class="detail-icon bg-primary bg-opacity-10 text-primary">
                <i class="fas fa-user-graduate"></i>
              </div>
              <h5 class="fw-bold text-dark mb-1">Data Siswa</h5>
              <p class="text-muted small mb-0">Statistik, Jumlah, dan Data Kelulusan.</p>
            </div>
          </div>

          <!-- Guru -->
          <div class="col-md-6" data-aos="fade-up" data-aos-delay="200">
            <div class="detail-card" onclick="openDetailModal('Guru & Tendik')">
              <div class="detail-icon bg-success bg-opacity-10 text-success">
                <i class="fas fa-chalkboard-teacher"></i>
              </div>
              <h5 class="fw-bold text-dark mb-1">Guru & Tendik</h5>
              <p class="text-muted small mb-0">Daftar Tenaga Pendidik dan Kependidikan.</p>
            </div>
          </div>

          <!-- Rombel -->
          <div class="col-md-6" data-aos="fade-up" data-aos-delay="250">
            <div class="detail-card" onclick="openDetailModal('Rombongan Belajar')">
              <div class="detail-icon bg-info bg-opacity-10 text-info">
                <i class="fas fa-users"></i>
              </div>
              <h5 class="fw-bold text-dark mb-1">Rombongan Belajar</h5>
              <p class="text-muted small mb-0">Informasi Kelas dan Wali Kelas.</p>
            </div>
          </div>

          <!-- Sarpras -->
          <div class="col-md-6" data-aos="fade-up" data-aos-delay="300">
            <div class="detail-card" onclick="openDetailModal('Sarana Prasarana')">
              <div class="detail-icon bg-warning bg-opacity-10 text-warning">
                <i class="fas fa-building"></i>
              </div>
              <h5 class="fw-bold text-dark mb-1">Sarana Prasarana</h5>
              <p class="text-muted small mb-0">Fasilitas Ruangan, Lab, dan Perpustakaan.</p>
            </div>
          </div>

          <!-- Sanitasi -->
          <div class="col-md-6" data-aos="fade-up" data-aos-delay="350">
            <div class="detail-card" onclick="openDetailModal('Sanitasi')">
              <div class="detail-icon bg-danger bg-opacity-10 text-danger">
                <i class="fas fa-pump-soap"></i>
              </div>
              <h5 class="fw-bold text-dark mb-1">Sanitasi</h5>
              <p class="text-muted small mb-0">Ketersediaan Air Bersih dan Jamban.</p>
            </div>
          </div>

          <!-- Akreditasi -->
          <div class="col-md-6" data-aos="fade-up" data-aos-delay="400">
            <div class="detail-card" onclick="openDetailModal('Nilai Akreditasi')">
              <div class="detail-icon bg-secondary bg-opacity-10 text-secondary">
                <i class="fas fa-star"></i>
              </div>
              <h5 class="fw-bold text-dark mb-1">Nilai Akreditasi</h5>
              <p class="text-muted small mb-0">Riwayat dan Status Akreditasi Sekolah.</p>
            </div>
          </div>

        </div>

      </div>
    </div>
  </div>

  <!-- Modal Detail -->
  <div class="modal fade" id="detailModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
      <div class="modal-content border-0 shadow-lg" style="border-radius: 16px;">
        <div class="modal-header border-bottom-0 pb-0">
          <h5 class="modal-title fw-bold" id="detailModalLabel">Detail Data</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body p-4" id="detailModalBody">
          <div class="text-center py-5">
            <div class="spinner-border text-primary" role="status">
              <span class="visually-hidden">Loading...</span>
            </div>
            <p class="mt-2 text-muted">Memuat data...</p>
          </div>
        </div>
        <div class="modal-footer border-top-0 pt-0">
          <button type="button" class="btn btn-light rounded-pill px-4" data-bs-dismiss="modal">Tutup</button>
        </div>
      </div>
    </div>
  </div>
@endsection

@push('scripts')
  <script>
    function openDetailModal(type) {
      $('#detailModalLabel').text('Detail ' + type);
      $('#detailModalBody').html(`
              <div class="text-center py-5">
                  <div class="spinner-border text-primary" role="status">
                      <span class="visually-hidden">Loading...</span>
                  </div>
                  <p class="mt-2 text-muted">Memuat data...</p>
              </div>
          `);
      // Use Bootstrap 5 modal
      var myModal = new bootstrap.Modal(document.getElementById('detailModal'));
      myModal.show();

      $.ajax({
        url: "{{ route('tenant.profile.detail', 'TYPE_PLACEHOLDER') }}".replace('TYPE_PLACEHOLDER', type),
        type: 'GET',
        success: function (response) {
          if (response.html) {
            $('#detailModalBody').html(response.html);
          } else {
            $('#detailModalBody').html('<p class="text-center text-muted">Tidak ada data tersedia.</p>');
          }
        },
        error: function () {
          $('#detailModalBody').html(`
                      <div class="alert alert-danger text-center">
                          Terjadi kesalahan saat memuat data. Silakan coba lagi.
                      </div>
                  `);
        }
      });
    }
  </script>
@endpush