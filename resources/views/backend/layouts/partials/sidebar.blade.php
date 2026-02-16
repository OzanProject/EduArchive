<!-- Main Sidebar Container -->
<aside class="main-sidebar sidebar-dark-primary elevation-4">
  @php
    /** @var \App\Models\User $user */
    $user = auth()->user();
  @endphp
  <!-- Brand Logo -->
  <a href="#" class="brand-link">
    @if(auth()->user()->role === 'admin_sekolah' || auth()->user()->role === 'operator')
      {{-- Tenant Logo --}}
      <img
        src="{{ tenant('logo') ? tenant_asset(tenant('logo')) : (!empty($central_branding['app_logo']) ? asset($central_branding['app_logo']) : asset('adminlte3/dist/img/AdminLTELogo.png')) }}"
        alt="School Logo" class="brand-image img-circle elevation-3" style="opacity: .8">
      <span class="brand-text font-weight-bold" style="white-space: normal; line-height: 1.2; font-size: 0.9rem;">
        {{ $app_settings['school_name'] ?? tenant('nama_sekolah') }}
      </span>
    @else
      {{-- Central App Logo --}}
      <img
        src="{{ !empty($app_settings['app_logo']) ? asset($app_settings['app_logo']) : asset('adminlte3/dist/img/AdminLTELogo.png') }}"
        alt="App Logo" class="brand-image img-circle elevation-3" style="opacity: .8">
      <span class="brand-text font-weight-bold" style="white-space: normal; line-height: 1.2;">
        {{ $app_settings['app_name'] ?? 'EduArchive' }}
      </span>
    @endif
  </a>

  <!-- Sidebar -->
  <div class="sidebar">
    <!-- Sidebar user (optional) -->
    <div class="user-panel mt-3 pb-3 mb-3 d-flex align-items-center">
      <div class="image">
        @if($user->avatar)
          @php
            $avatarUrl = tenant() ? tenant_asset($user->avatar) : asset('storage/' . $user->avatar);
          @endphp
          <img src="{{ $avatarUrl }}" class="img-circle elevation-2" alt="User Image"
            style="width: 34px; height: 34px; object-fit: cover;">
        @else
          <img src="{{ asset('adminlte3/dist/img/user2-160x160.jpg') }}" class="img-circle elevation-2" alt="User Image">
        @endif
      </div>
      <div class="info w-100">
        @php
          $profileRoute = auth()->user()->role === 'superadmin' ? route('profile.edit') : route('tenant.profile.edit', ['tenant' => tenant('id')]);
        @endphp
        <a href="{{ $profileRoute }}" class="d-block font-weight-medium" style="white-space: normal; line-height: 1.2;">
          {{ $user->name }}
        </a>
        <small
          class="text-muted text-xs">{{ $user->role === 'admin_sekolah' ? 'Admin Sekolah' : ($user->role === 'operator' ? 'Operator' : 'Administrator') }}</small>
      </div>
    </div>

    <!-- Sidebar Menu -->
    <nav class="mt-2">
      <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
        <!-- Add icons to the links using the .nav-icon class
               with font-awesome or any other icon font library -->

        {{-- Super Admin Menu --}}
        @if($user->role === 'superadmin')
          {{-- CONTROL --}}
          <li class="nav-header">CONTROL</li>
          <li class="nav-item">
            <a href="{{ route('superadmin.dashboard') }}"
              class="nav-link {{ Request::routeIs('superadmin.dashboard') ? 'active' : '' }}">
              <i class="nav-icon fas fa-chart-line"></i>
              <p>Dashboard</p>
            </a>
          </li>
          <li
            class="nav-item has-treeview {{ (Request::routeIs('superadmin.monitoring.*') && !Request::routeIs('superadmin.monitoring.audit_logs')) ? 'menu-open' : '' }}">
            <a href="#"
              class="nav-link {{ (Request::routeIs('superadmin.monitoring.*') && !Request::routeIs('superadmin.monitoring.audit_logs')) ? 'active' : '' }}">
              <i class="nav-icon fas fa-desktop"></i>
              <p>
                Monitoring
                <i class="right fas fa-angle-left"></i>
              </p>
            </a>
            <ul class="nav nav-treeview">
              <li class="nav-item">
                <a href="{{ route('superadmin.monitoring.index', ['category' => 'students']) }}"
                  class="nav-link {{ ((Request::routeIs('superadmin.monitoring.index') && request('category', 'students') == 'students') || (Request::routeIs('superadmin.monitoring.school') && request('status', 'aktif') == 'aktif')) ? 'active' : '' }}">
                  <i class="far fa-circle nav-icon"></i>
                  <p>Siswa Aktif</p>
                </a>
              </li>
              <li class="nav-item">
                <a href="{{ route('superadmin.monitoring.index', ['category' => 'graduates']) }}"
                  class="nav-link {{ ((Request::routeIs('superadmin.monitoring.index') && request('category') == 'graduates') || (Request::routeIs('superadmin.monitoring.school') && request('status') == 'lulus')) ? 'active' : '' }}">
                  <i class="far fa-circle nav-icon"></i>
                  <p>Data Lulusan</p>
                </a>
              </li>
            </ul>
          </li>

          {{-- MANAGEMENT --}}
          <li class="nav-header">MANAGEMENT</li>
          <li class="nav-item">
            <a href="{{ route('superadmin.school-levels.index') }}"
              class="nav-link {{ Request::routeIs('superadmin.school-levels.*') ? 'active' : '' }}">
              <i class="nav-icon fas fa-layer-group"></i>
              <p>Data Jenjang</p>
            </a>
          </li>
          <li class="nav-item">
            <a href="{{ route('superadmin.tenants.index') }}"
              class="nav-link {{ Request::routeIs('superadmin.tenants.*') ? 'active' : '' }}">
              <i class="nav-icon fas fa-school"></i>
              <p>Data Sekolah</p>
            </a>
          </li>
          <li class="nav-item">
            <a href="{{ route('superadmin.document-types.index') }}"
              class="nav-link {{ request()->routeIs('superadmin.document-types.*') ? 'active' : '' }}">
              <i class="nav-icon fas fa-file-alt"></i>
              <p>Jenis Dokumen</p>
            </a>
          </li>
          <li class="nav-item">
            <a href="{{ route('superadmin.broadcasts.index') }}"
              class="nav-link {{ Request::routeIs('superadmin.broadcasts.*') ? 'active' : '' }}">
              <i class="nav-icon fas fa-bullhorn"></i>
              <p>Broadcast Info</p>
            </a>
          </li>
          <li class="nav-item">
            <a href="{{ route('superadmin.pages.index') }}"
              class="nav-link {{ Request::routeIs('superadmin.pages.*') ? 'active' : '' }}">
              <i class="nav-icon fas fa-columns"></i>
              <p>Halaman Statis</p>
            </a>
          </li>

          {{-- SECURITY --}}
          <li class="nav-header">SECURITY</li>
          <li class="nav-item">
            <a href="{{ route('superadmin.monitoring.audit_logs') }}"
              class="nav-link {{ Request::routeIs('superadmin.monitoring.audit_logs') ? 'active' : '' }}">
              <i class="nav-icon fas fa-shield-alt"></i>
              <p>Audit Logs</p>
            </a>
          </li>

          {{-- SYSTEM --}}
          <li class="nav-header">SYSTEM</li>

          <li
            class="nav-item {{ request()->routeIs('superadmin.users.*') || request()->routeIs('superadmin.roles.*') ? 'menu-open' : '' }}">
            <a href="#"
              class="nav-link {{ request()->routeIs('superadmin.users.*') || request()->routeIs('superadmin.roles.*') ? 'active' : '' }}">
              <i class="nav-icon fas fa-users-cog"></i>
              <p>
                Users & Roles
                <i class="right fas fa-angle-left"></i>
              </p>
            </a>
            <ul class="nav nav-treeview">
              <li class="nav-item">
                <a href="{{ route('superadmin.users.index') }}"
                  class="nav-link {{ request()->routeIs('superadmin.users.*') ? 'active' : '' }}">
                  <i class="far fa-circle nav-icon"></i>
                  <p>Manajemen User</p>
                </a>
              </li>
              <li class="nav-item">
                <a href="{{ route('superadmin.roles.index') }}"
                  class="nav-link {{ request()->routeIs('superadmin.roles.*') ? 'active' : '' }}">
                  <i class="far fa-circle nav-icon"></i>
                  <p>Roles & Permissions</p>
                </a>
              </li>
            </ul>
          </li>

          <li class="nav-item has-treeview {{ Request::routeIs('superadmin.settings.*') ? 'menu-open' : '' }}">
            <a href="#" class="nav-link {{ Request::routeIs('superadmin.settings.*') ? 'active' : '' }}">
              <i class="nav-icon fas fa-cogs"></i>
              <p>
                Pengaturan
                <i class="right fas fa-angle-left"></i>
              </p>
            </a>
            <ul class="nav nav-treeview">
              <li class="nav-item">
                <a href="{{ route('superadmin.settings.general') }}"
                  class="nav-link {{ Request::routeIs('superadmin.settings.general') ? 'active' : '' }}">
                  <i class="far fa-circle nav-icon"></i>
                  <p>Umum</p>
                </a>
              </li>
              <li class="nav-item">
                <a href="{{ route('superadmin.settings.landing') }}"
                  class="nav-link {{ Request::routeIs('superadmin.settings.landing') ? 'active' : '' }}">
                  <i class="far fa-circle nav-icon"></i>
                  <p>Landing Page</p>
                </a>
              </li>
              <li class="nav-item">
                <a href="{{ route('superadmin.settings.footer') }}"
                  class="nav-link {{ Request::routeIs('superadmin.settings.footer') ? 'active' : '' }}">
                  <i class="far fa-circle nav-icon"></i>
                  <p>Footer & Social</p>
                </a>
              </li>
              <li class="nav-item">
                <a href="{{ route('superadmin.settings.smtp') }}"
                  class="nav-link {{ Request::routeIs('superadmin.settings.smtp') ? 'active' : '' }}">
                  <i class="far fa-circle nav-icon"></i>
                  <p>Email Server</p>
                </a>
              </li>
              <li class="nav-item">
                <a href="{{ route('superadmin.settings.whatsapp') }}"
                  class="nav-link {{ Request::routeIs('superadmin.settings.whatsapp') ? 'active' : '' }}">
                  <i class="far fa-circle nav-icon"></i>
                  <p>WhatsApp Gateway</p>
                </a>
              </li>
            </ul>
          </li>
        @endif

        {{-- Admin Lembaga Menu --}}
        @if($user->role === 'admin_sekolah')
          <li class="nav-header">ADMINISTRASI SEKOLAH</li>

          <li class="nav-item">
            <a href="{{ route('adminlembaga.dashboard') }}"
              class="nav-link {{ Request::routeIs('adminlembaga.dashboard') ? 'active' : '' }}">
              <i class="nav-icon fas fa-tachometer-alt"></i>
              <p>Dashboard</p>
            </a>
          </li>

          {{-- Data Sekolah (Group) --}}
          <li
            class="nav-item has-treeview {{ Request::routeIs('adminlembaga.teachers.*') || Request::routeIs('adminlembaga.classrooms.*') ? 'menu-open' : '' }}">
            <a href="#"
              class="nav-link {{ Request::routeIs('adminlembaga.teachers.*') || Request::routeIs('adminlembaga.classrooms.*') ? 'active' : '' }}">
              <i class="nav-icon fas fa-school"></i>
              <p>
                Data Sekolah
                <i class="right fas fa-angle-left"></i>
              </p>
            </a>
            <ul class="nav nav-treeview">
              <li class="nav-item">
                <a href="{{ route('adminlembaga.teachers.index') }}"
                  class="nav-link {{ Request::routeIs('adminlembaga.teachers.*') ? 'active' : '' }}">
                  <i class="far fa-circle nav-icon"></i>
                  <p>Guru & Tendik</p>
                </a>
              </li>
              <li class="nav-item">
                <a href="{{ route('adminlembaga.users.index') }}"
                  class="nav-link {{ Request::routeIs('adminlembaga.users.*') ? 'active' : '' }}">
                  <i class="far fa-circle nav-icon"></i>
                  <p>Operator Sekolah</p>
                </a>
              </li>
              <li class="nav-item">
                <a href="{{ route('adminlembaga.classrooms.index') }}"
                  class="nav-link {{ Request::routeIs('adminlembaga.classrooms.*') ? 'active' : '' }}">
                  <i class="far fa-circle nav-icon"></i>
                  <p>Data Kelas</p>
                </a>
              </li>
            </ul>
          </li>

          {{-- Data Siswa Aktif --}}
          <li class="nav-item">
            <a href="{{ route('adminlembaga.students.index', ['status' => 'Aktif']) }}"
              class="nav-link {{ Request::routeIs('adminlembaga.students.*') && request('status') != 'Lulus' ? 'active' : '' }}">
              <i class="nav-icon fas fa-user-graduate"></i>
              <p>Data Siswa Aktif</p>
            </a>
          </li>

          {{-- Data Siswa Lulusan --}}
          <li class="nav-item">
            <a href="{{ route('adminlembaga.students.index', ['status' => 'Lulus']) }}"
              class="nav-link {{ Request::routeIs('adminlembaga.students.*') && request('status') == 'Lulus' ? 'active' : '' }}">
              <i class="nav-icon fas fa-user-check"></i>
              <p>Data Siswa Lulusan</p>
            </a>
          </li>

          {{-- Arsip Dokumen Lembaga --}}
          <li class="nav-item">
            <a href="{{ route('adminlembaga.school-documents.index') }}"
              class="nav-link {{ request()->routeIs('adminlembaga.school-documents.*') ? 'active' : '' }}">
              <i class="nav-icon fas fa-file-archive"></i>
              <p>Arsip Dokumen Lembaga</p>
            </a>
          </li>

          {{-- Dokumen Siswa (New) --}}
          <li class="nav-item">
            <a href="{{ route('adminlembaga.documents.index') }}"
              class="nav-link {{ request()->routeIs('adminlembaga.documents.*') ? 'active' : '' }}">
              <i class="nav-icon fas fa-folder-open"></i>
              <p>Dokumen Siswa</p>
            </a>
          </li>

          <li class="nav-item">
            <a href="{{ route('adminlembaga.reports.index') }}"
              class="nav-link {{ request()->routeIs('adminlembaga.reports.index') ? 'active' : '' }}">
              <i class="nav-icon fas fa-chart-pie"></i>
              <p>Laporan & Statistik</p>
            </a>
          </li>

          {{-- Settings Group --}}
          <li class="nav-item has-treeview {{ request()->routeIs('adminlembaga.settings.*') ? 'menu-open' : '' }}">
            <a href="#" class="nav-link {{ request()->routeIs('adminlembaga.settings.*') ? 'active' : '' }}">
              <i class="nav-icon fas fa-cogs"></i>
              <p>
                Pengaturan Sekolah
                <i class="right fas fa-angle-left"></i>
              </p>
            </a>
            <ul class="nav nav-treeview">
              <li class="nav-item">
                <a href="{{ route('adminlembaga.settings.profile') }}"
                  class="nav-link {{ request()->routeIs('adminlembaga.settings.profile') ? 'active' : '' }}">
                  <i class="far fa-circle nav-icon text-info"></i>
                  <p>Profil Publik</p>
                </a>
              </li>
              <li class="nav-item">
                <a href="{{ route('adminlembaga.settings.index') }}#general" class="nav-link">
                  <i class="far fa-circle nav-icon"></i>
                  <p>Konfigurasi Umum</p>
                </a>
              </li>
              <li class="nav-item">
                <a href="{{ route('adminlembaga.settings.index') }}#doc" class="nav-link">
                  <i class="far fa-circle nav-icon"></i>
                  <p>Dokumen & Kop Surat</p>
                </a>
              </li>
              <li class="nav-item">
                <a href="{{ route('adminlembaga.settings.index') }}#account" class="nav-link">
                  <i class="far fa-circle nav-icon"></i>
                  <p>Info Akun</p>
                </a>
              </li>
            </ul>
          </li>
        @endif

        {{-- Operator Menu --}}
        @if($user->role === 'operator')
          <li class="nav-header">OPERATOR</li>
          <li class="nav-item">
            <a href="{{ route('operator.dashboard') }}"
              class="nav-link {{ Request::routeIs('operator.dashboard') ? 'active' : '' }}">
              <i class="nav-icon fas fa-tachometer-alt"></i>
              <p>Dashboard</p>
            </a>
          </li>

          <li class="nav-item">
            <a href="{{ route('operator.students.index') }}"
              class="nav-link {{ Request::routeIs('operator.students.*') ? 'active' : '' }}">
              <i class="nav-icon fas fa-user-graduate"></i>
              <p>Data Siswa</p>
            </a>
          </li>

          <li class="nav-item">
            <a href="{{ route('operator.documents.index') }}"
              class="nav-link {{ Request::routeIs('operator.documents.*') ? 'active' : '' }}">
              <i class="nav-icon fas fa-folder-open"></i>
              <p>Dokumen Siswa</p>
            </a>
          </li>

          <li class="nav-item">
            <a href="{{ route('operator.school-documents.index') }}"
              class="nav-link {{ Request::routeIs('operator.school-documents.*') ? 'active' : '' }}">
              <i class="nav-icon fas fa-file-archive"></i>
              <p>Arsip Dokumen</p>
            </a>
          </li>
        @endif

      </ul>
    </nav>
    <!-- /.sidebar-menu -->
  </div>
  <!-- /.sidebar -->
</aside>