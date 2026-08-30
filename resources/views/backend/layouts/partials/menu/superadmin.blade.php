          <li class="nav-item">
            <a href="{{ route('superadmin.dashboard') }}"
              class="nav-link {{ Request::routeIs('superadmin.dashboard') ? 'active' : '' }}">
              <i class="nav-icon fas fa-tachometer-alt"></i>
              <p>Dashboard</p>
            </a>
          </li>

          <li class="nav-header">DATA SEKOLAH</li>
          
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
              <p>Manajemen Sekolah</p>
            </a>
          </li>
          <li class="nav-item">
            <a href="{{ route('superadmin.document-types.index') }}"
              class="nav-link {{ request()->routeIs('superadmin.document-types.*') ? 'active' : '' }}">
              <i class="nav-icon fas fa-file-alt"></i>
              <p>Jenis Dokumen</p>
            </a>
          </li>

          <li class="nav-header">MONITORING LINTAS SEKOLAH</li>
          
          <li class="nav-item">
            <a href="{{ route('superadmin.monitoring.index', ['category' => 'students']) }}"
              class="nav-link {{ request('category') == 'students' ? 'active' : '' }}">
              <i class="nav-icon fas fa-user-graduate"></i>
              <p>Siswa Aktif</p>
            </a>
          </li>
          <li class="nav-item">
            <a href="{{ route('superadmin.monitoring.index', ['category' => 'graduates']) }}"
              class="nav-link {{ request('category') == 'graduates' ? 'active' : '' }}">
              <i class="nav-icon fas fa-user-check"></i>
              <p>Data Lulusan</p>
            </a>
          </li>
          <li class="nav-item">
            <a href="{{ route('superadmin.mutations.index') }}"
              class="nav-link {{ Request::routeIs('superadmin.mutations.*') ? 'active' : '' }}">
              <i class="nav-icon fas fa-exchange-alt"></i>
              <p>Mutasi Siswa</p>
            </a>
          </li>
          <li class="nav-item">
            <a href="{{ route('superadmin.monitoring.infrastructure.index') }}"
              class="nav-link {{ Request::routeIs('superadmin.monitoring.infrastructure.*') ? 'active' : '' }}">
              <i class="nav-icon fas fa-tools"></i>
              <p>Usulan Sarpras</p>
            </a>
          </li>
          <li class="nav-item">
            <a href="{{ route('superadmin.monitoring.learning-activities.index') }}"
              class="nav-link {{ Request::routeIs('superadmin.monitoring.learning-activities.*') ? 'active' : '' }}">
              <i class="nav-icon fas fa-calendar-alt"></i>
              <p>Kegiatan Belajar</p>
            </a>
          </li>
          <li class="nav-item">
            <a href="{{ route('superadmin.monitoring.integrity-pacts.index') }}"
              class="nav-link {{ Request::routeIs('superadmin.monitoring.integrity-pacts.*') ? 'active' : '' }}">
              <i class="nav-icon fas fa-file-signature"></i>
              <p>Fakta Integritas</p>
            </a>
          </li>
          <li class="nav-item">
            <a href="{{ route('superadmin.pip.index') }}"
              class="nav-link {{ Request::routeIs('superadmin.pip.*') ? 'active' : '' }}">
              <i class="nav-icon fas fa-hand-holding-usd text-warning"></i>
              <p>Data PIP</p>
            </a>
          </li>
          <li class="nav-item">
            <a href="{{ route('superadmin.reports.index') }}"
              class="nav-link {{ Request::routeIs('superadmin.reports.*') ? 'active' : '' }}">
              <i class="nav-icon fas fa-chart-pie"></i>
              <p>Laporan Sekolah</p>
            </a>
          </li>

          <li class="nav-header">KONTEN & INFORMASI</li>
          
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

          <li class="nav-header">PENGATURAN WEBSITE</li>
          
          <li class="nav-item">
            <a href="{{ route('superadmin.settings.general') }}"
              class="nav-link {{ Request::routeIs('superadmin.settings.general') ? 'active' : '' }}">
              <i class="nav-icon fas fa-cog"></i>
              <p>Pengaturan Umum</p>
            </a>
          </li>
          <li class="nav-item">
            <a href="{{ route('superadmin.settings.landing') }}"
              class="nav-link {{ Request::routeIs('superadmin.settings.landing') ? 'active' : '' }}">
              <i class="nav-icon fas fa-globe"></i>
              <p>Landing Page</p>
            </a>
          </li>
          <li class="nav-item">
            <a href="{{ route('superadmin.settings.footer') }}"
              class="nav-link {{ Request::routeIs('superadmin.settings.footer') ? 'active' : '' }}">
              <i class="nav-icon fas fa-shoe-prints"></i>
              <p>Footer & Social</p>
            </a>
          </li>
          <li class="nav-item">
            <a href="{{ route('superadmin.settings.smtp') }}"
              class="nav-link {{ Request::routeIs('superadmin.settings.smtp') ? 'active' : '' }}">
              <i class="nav-icon fas fa-envelope"></i>
              <p>Email Server (SMTP)</p>
            </a>
          </li>
          <li class="nav-item">
            <a href="{{ route('superadmin.settings.whatsapp') }}"
              class="nav-link {{ Request::routeIs('superadmin.settings.whatsapp') ? 'active' : '' }}">
              <i class="nav-icon fab fa-whatsapp"></i>
              <p>WhatsApp Gateway</p>
            </a>
          </li>

          <li class="nav-header">SISTEM & KEAMANAN</li>
          
          <li class="nav-item">
            <a href="{{ route('superadmin.users.index') }}"
              class="nav-link {{ request()->routeIs('superadmin.users.*') ? 'active' : '' }}">
              <i class="nav-icon fas fa-users"></i>
              <p>Manajemen User</p>
            </a>
          </li>
          <li class="nav-item">
            <a href="{{ route('superadmin.roles.index') }}"
              class="nav-link {{ request()->routeIs('superadmin.roles.*') ? 'active' : '' }}">
              <i class="nav-icon fas fa-user-shield"></i>
              <p>Roles & Permissions</p>
            </a>
          </li>
          <li class="nav-item">
            <a href="{{ route('superadmin.monitoring.audit_logs') }}"
              class="nav-link {{ Request::routeIs('superadmin.monitoring.audit_logs') ? 'active' : '' }}">
              <i class="nav-icon fas fa-history"></i>
              <p>Audit Logs</p>
            </a>
          </li>
          <li class="nav-item">
            <a href="{{ route('superadmin.backups.index') }}"
              class="nav-link {{ Request::routeIs('superadmin.backups.*') ? 'active' : '' }}">
              <i class="nav-icon fas fa-hdd"></i>
              <p>Backup & Restore</p>
            </a>
          </li>

