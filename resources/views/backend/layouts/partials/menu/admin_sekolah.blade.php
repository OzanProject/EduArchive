          <li class="nav-header">ADMINISTRASI SEKOLAH</li>

          <li class="nav-item">
            <a href="{{ route('adminlembaga.dashboard') }}"
              class="nav-link {{ Request::routeIs('adminlembaga.dashboard') ? 'active' : '' }}">
              <i class="nav-icon fas fa-tachometer-alt"></i>
              <p>Dashboard</p>
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
              class="nav-link {{ Request::routeIs('adminlembaga.students.*') && (strtolower(request('status')) != 'lulus' && (!isset($student) || strtolower($student->status_kelulusan) != 'lulus')) ? 'active' : '' }}">
              <i class="nav-icon fas fa-user-graduate"></i>
              <p>Data Siswa Aktif</p>
            </a>
          </li>

          {{-- Data Siswa Lulusan --}}
          <li class="nav-item">
            <a href="{{ route('adminlembaga.students.index', ['status' => 'Lulus']) }}"
              class="nav-link {{ Request::routeIs('adminlembaga.students.*') && (strtolower(request('status')) == 'lulus' || (isset($student) && strtolower($student->status_kelulusan) == 'lulus')) ? 'active' : '' }}">
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

          <li class="nav-header">SARANA PRASARANA</li>
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

          {{-- Data PIP (Program Indonesia Pintar) --}}
          <li class="nav-item">
            <a href="{{ route('adminlembaga.pip.index') }}"
              class="nav-link {{ request()->routeIs('adminlembaga.pip.*') ? 'active' : '' }}">
              <i class="nav-icon fas fa-hand-holding-usd text-warning"></i>
              <p>Data PIP</p>
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
            <a href="{{ route('adminlembaga.settings.index', tenant('id')) }}"
              class="nav-link {{ request()->routeIs('adminlembaga.settings.*') ? 'active' : '' }}">
              <i class="nav-icon fas fa-cogs"></i>
              <p>Pengaturan</p>
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
            <a href="{{ route('adminlembaga.api_tokens.index', tenant('id')) }}"
              class="nav-link {{ request()->routeIs('adminlembaga.api_tokens.*') ? 'active' : '' }}">
              <i class="nav-icon fas fa-project-diagram"></i>
              <p>Web Service API</p>
            </a>
          </li>

          <li class="nav-item">
            <a href="{{ route('adminlembaga.reports.index') }}"
              class="nav-link {{ request()->routeIs('adminlembaga.reports.index') ? 'active' : '' }}">
              <i class="nav-icon fas fa-chart-pie"></i>
              <p>Laporan & Statistik</p>
            </a>
          </li>

          {{-- Panduan --}}
          <li class="nav-item">
            <a href="{{ route('adminlembaga.guide') }}"
              class="nav-link {{ request()->routeIs('adminlembaga.guide') ? 'active' : '' }}">
              <i class="nav-icon fas fa-book-reader"></i>
              <p>Panduan Website</p>
            </a>
          </li>

