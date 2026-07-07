          <li class="nav-item">
            <a href="{{ route('adminlembaga.dashboard') }}"
              class="nav-link {{ Request::routeIs('adminlembaga.dashboard') ? 'active' : '' }}">
              <i class="nav-icon fas fa-tachometer-alt"></i>
              <p>Dashboard</p>
            </a>
          </li>
          
          <li class="nav-item">
            <a href="{{ route('adminlembaga.dapodik.index', tenant('id')) }}"
              class="nav-link {{ request()->routeIs('adminlembaga.dapodik.*') ? 'active' : '' }}">
              <i class="nav-icon fas fa-cloud-download-alt text-success"></i>
              <p>Integrasi Dapodik</p>
            </a>
          </li>

          <li class="nav-item">
            <a href="{{ route('adminlembaga.reports.index') }}"
              class="nav-link {{ request()->routeIs('adminlembaga.reports.index') ? 'active' : '' }}">
              <i class="nav-icon fas fa-chart-pie"></i>
              <p>Laporan & Statistik</p>
            </a>
          </li>

          <li class="nav-header">DATA MASTER</li>

          <li class="nav-item">
            <a href="{{ route('adminlembaga.classrooms.index') }}"
              class="nav-link {{ Request::routeIs('adminlembaga.classrooms.*') ? 'active' : '' }}">
              <i class="nav-icon fas fa-chalkboard-teacher"></i>
              <p>Data Kelas</p>
            </a>
          </li>
          <li class="nav-item">
            <a href="{{ route('adminlembaga.teachers.index') }}"
              class="nav-link {{ Request::routeIs('adminlembaga.teachers.*') ? 'active' : '' }}">
              <i class="nav-icon fas fa-user-tie"></i>
              <p>Guru & Tendik</p>
            </a>
          </li>
          <li class="nav-item">
            <a href="{{ route('adminlembaga.users.index') }}"
              class="nav-link {{ Request::routeIs('adminlembaga.users.*') ? 'active' : '' }}">
              <i class="nav-icon fas fa-users-cog"></i>
              <p>Operator Sekolah</p>
            </a>
          </li>

          <li class="nav-header">KESISWAAN</li>

          @php
            $isStudentActive = Request::routeIs('adminlembaga.students.*') && (strtolower(request('status')) != 'lulus' && (!isset($student) || strtolower($student->status_kelulusan) != 'lulus'));
            $isStudentGraduate = Request::routeIs('adminlembaga.students.*') && (strtolower(request('status')) == 'lulus' || (isset($student) && strtolower($student->status_kelulusan) == 'lulus'));
          @endphp
          
          <li class="nav-item">
            <a href="{{ route('adminlembaga.students.index', ['status' => 'Aktif']) }}"
              class="nav-link {{ $isStudentActive ? 'active' : '' }}">
              <i class="nav-icon fas fa-user-graduate"></i>
              <p>Data Siswa Aktif</p>
            </a>
          </li>
          <li class="nav-item">
            <a href="{{ route('adminlembaga.students.index', ['status' => 'Lulus']) }}"
              class="nav-link {{ $isStudentGraduate ? 'active' : '' }}">
              <i class="nav-icon fas fa-user-check"></i>
              <p>Data Siswa Lulusan</p>
            </a>
          </li>
          <li class="nav-item">
            <a href="{{ route('adminlembaga.documents.index') }}"
              class="nav-link {{ request()->routeIs('adminlembaga.documents.*') ? 'active' : '' }}">
              <i class="nav-icon fas fa-folder-open"></i>
              <p>Dokumen Siswa</p>
            </a>
          </li>
          <li class="nav-item">
            <a href="{{ route('adminlembaga.pip.index') }}"
              class="nav-link {{ request()->routeIs('adminlembaga.pip.*') ? 'active' : '' }}">
              <i class="nav-icon fas fa-hand-holding-usd text-warning"></i>
              <p>Data PIP</p>
            </a>
          </li>

          <li class="nav-header">SARANA & ADMINISTRASI</li>

          <li class="nav-item">
            <a href="{{ route('adminlembaga.school-documents.index') }}"
              class="nav-link {{ request()->routeIs('adminlembaga.school-documents.*') ? 'active' : '' }}">
              <i class="nav-icon fas fa-file-archive"></i>
              <p>Arsip Lembaga</p>
            </a>
          </li>
          <li class="nav-item">
            <a href="{{ route('adminlembaga.infrastructure.index') }}"
              class="nav-link {{ request()->routeIs('adminlembaga.infrastructure.*') ? 'active' : '' }}">
              <i class="nav-icon fas fa-tools"></i>
              <p>RKB & REHAB</p>
            </a>
          </li>
          <li class="nav-item">
            <a href="{{ route('adminlembaga.learning-activities.index') }}"
              class="nav-link {{ request()->routeIs('adminlembaga.learning-activities.*') ? 'active' : '' }}">
              <i class="nav-icon fas fa-calendar-alt"></i>
              <p>Kegiatan Belajar</p>
            </a>
          </li>
          <li class="nav-item">
            <a href="{{ route('adminlembaga.integrity-pacts.index') }}"
              class="nav-link {{ request()->routeIs('adminlembaga.integrity-pacts.*') ? 'active' : '' }}">
              <i class="nav-icon fas fa-file-signature"></i>
              <p>Fakta Integritas</p>
            </a>
          </li>

          <li class="nav-header">PENGATURAN SISTEM</li>

          <li class="nav-item">
            <a href="{{ route('adminlembaga.settings.index', tenant('id')) }}"
              class="nav-link {{ request()->routeIs('adminlembaga.settings.*') && !request()->routeIs('adminlembaga.settings.profile') ? 'active' : '' }}">
              <i class="nav-icon fas fa-cogs"></i>
              <p>Pengaturan Sekolah</p>
            </a>
          </li>
          <li class="nav-item">
            <a href="{{ route('adminlembaga.settings.profile') }}"
              class="nav-link {{ request()->routeIs('adminlembaga.settings.profile') ? 'active' : '' }}">
              <i class="nav-icon fas fa-id-badge text-info"></i>
              <p>Profil Publik</p>
            </a>
          </li>
          <li class="nav-item">
            <a href="{{ route('adminlembaga.api_tokens.index', tenant('id')) }}"
              class="nav-link {{ request()->routeIs('adminlembaga.api_tokens.*') ? 'active' : '' }}">
              <i class="nav-icon fas fa-project-diagram"></i>
              <p>Web Service API</p>
            </a>
          </li>

          <li class="nav-header">BANTUAN</li>

          <li class="nav-item">
            <a href="{{ route('adminlembaga.guide') }}"
              class="nav-link {{ request()->routeIs('adminlembaga.guide') ? 'active' : '' }}">
              <i class="nav-icon fas fa-book-reader"></i>
              <p>Panduan Website</p>
            </a>
          </li>

