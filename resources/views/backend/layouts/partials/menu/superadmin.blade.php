          {{-- CONTROL --}}
          <li class="nav-header">CONTROL</li>
          <li class="nav-item">
            <a href="{{ route('superadmin.dashboard') }}"
              class="nav-link {{ Request::routeIs('superadmin.dashboard') ? 'active' : '' }}">
              <i class="nav-icon fas fa-chart-line"></i>
              <p>Dashboard</p>
            </a>
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

          <li
            class="nav-item has-treeview {{ (Request::routeIs('superadmin.monitoring.*') && !Request::routeIs('superadmin.monitoring.audit_logs')) || Request::routeIs('superadmin.pip.*') ? 'menu-open' : '' }}">
            <a href="#"
              class="nav-link {{ (Request::routeIs('superadmin.monitoring.*') && !Request::routeIs('superadmin.monitoring.audit_logs')) || Request::routeIs('superadmin.pip.*') ? 'active' : '' }}">
              <i class="nav-icon fas fa-desktop"></i>
              <p>
                Monitoring
                <i class="right fas fa-angle-left"></i>
              </p>
            </a>
            <ul class="nav nav-treeview">
              <li class="nav-item has-treeview {{ (request('category', 'students') == 'students' && !Request::routeIs('superadmin.monitoring.infrastructure.*') && !Request::routeIs('superadmin.monitoring.learning-activities.*') && !Request::routeIs('superadmin.monitoring.integrity-pacts.*')) ? 'menu-open' : '' }}">
                <a href="#" class="nav-link {{ (request('category', 'students') == 'students' && !Request::routeIs('superadmin.monitoring.infrastructure.*') && !Request::routeIs('superadmin.monitoring.learning-activities.*') && !Request::routeIs('superadmin.monitoring.integrity-pacts.*')) ? 'active' : '' }}">
                  <i class="far fa-circle nav-icon"></i>
                  <p>
                    Siswa Aktif
                    <i class="right fas fa-angle-left"></i>
                  </p>
                </a>
                <ul class="nav nav-treeview">
                  <li class="nav-item">
                    <a href="{{ route('superadmin.monitoring.index', ['category' => 'students']) }}"
                      class="nav-link {{ ((Request::routeIs('superadmin.monitoring.index') && request('category') == 'students' && !request('age_filter')) || (Request::routeIs('superadmin.monitoring.school') && request('status') == 'aktif' && !request('age_filter'))) ? 'active' : '' }}">
                      <i class="far fa-dot-circle nav-icon"></i>
                      <p>Semua Siswa</p>
                    </a>
                  </li>
                  <li class="nav-item">
                    <a href="{{ route('superadmin.monitoring.index', ['category' => 'students', 'age_filter' => 'under_25']) }}"
                      class="nav-link {{ (request('category') == 'students' && request('age_filter') == 'under_25') ? 'active' : '' }}">
                      <i class="far fa-dot-circle nav-icon"></i>
                      <p>Usia < 25 Tahun</p>
                    </a>
                  </li>
                  <li class="nav-item">
                    <a href="{{ route('superadmin.monitoring.index', ['category' => 'students', 'age_filter' => 'over_25']) }}"
                      class="nav-link {{ (request('category') == 'students' && request('age_filter') == 'over_25') ? 'active' : '' }}">
                      <i class="far fa-dot-circle nav-icon"></i>
                      <p>Usia ≥ 25 Tahun</p>
                    </a>
                  </li>
                </ul>
              </li>
              <li class="nav-item has-treeview {{ (request('category') == 'graduates' || (Request::routeIs('superadmin.monitoring.school') && request('status') == 'lulus')) ? 'menu-open' : '' }}">
                <a href="#" class="nav-link {{ (request('category') == 'graduates' || (Request::routeIs('superadmin.monitoring.school') && request('status') == 'lulus')) ? 'active' : '' }}">
                  <i class="far fa-circle nav-icon"></i>
                  <p>
                    Data Lulusan
                    <i class="right fas fa-angle-left"></i>
                  </p>
                </a>
                <ul class="nav nav-treeview">
                  <li class="nav-item">
                    <a href="{{ route('superadmin.monitoring.index', ['category' => 'graduates']) }}"
                      class="nav-link {{ ((Request::routeIs('superadmin.monitoring.index') && request('category') == 'graduates' && !request('age_filter')) || (Request::routeIs('superadmin.monitoring.school') && request('status') == 'lulus' && !request('age_filter'))) ? 'active' : '' }}">
                      <i class="far fa-dot-circle nav-icon"></i>
                      <p>Semua Lulusan</p>
                    </a>
                  </li>
                  <li class="nav-item">
                    <a href="{{ route('superadmin.monitoring.index', ['category' => 'graduates', 'age_filter' => 'under_25']) }}"
                      class="nav-link {{ (request('category') == 'graduates' && request('age_filter') == 'under_25') ? 'active' : '' }}">
                      <i class="far fa-dot-circle nav-icon"></i>
                      <p>Usia < 25 Tahun</p>
                    </a>
                  </li>
                  <li class="nav-item">
                    <a href="{{ route('superadmin.monitoring.index', ['category' => 'graduates', 'age_filter' => 'over_25']) }}"
                      class="nav-link {{ (request('category') == 'graduates' && request('age_filter') == 'over_25') ? 'active' : '' }}">
                      <i class="far fa-dot-circle nav-icon"></i>
                      <p>Usia ≥ 25 Tahun</p>
                    </a>
                  </li>
                </ul>
              </li>
              <li class="nav-item">
                <a href="{{ route('superadmin.monitoring.infrastructure.index') }}"
                  class="nav-link {{ Request::routeIs('superadmin.monitoring.infrastructure.*') ? 'active' : '' }}">
                  <i class="far fa-circle nav-icon"></i>
                  <p>Usulan Sarpras</p>
                </a>
              </li>
              <li class="nav-item">
                <a href="{{ route('superadmin.monitoring.learning-activities.index') }}"
                  class="nav-link {{ Request::routeIs('superadmin.monitoring.learning-activities.*') ? 'active' : '' }}">
                  <i class="far fa-circle nav-icon"></i>
                  <p>Monitoring Kegiatan</p>
                </a>
              </li>
              <li class="nav-item">
                <a href="{{ route('superadmin.monitoring.integrity-pacts.index') }}"
                  class="nav-link {{ Request::routeIs('superadmin.monitoring.integrity-pacts.*') ? 'active' : '' }}">
                  <i class="far fa-circle nav-icon"></i>
                  <p>Fakta Integritas</p>
                </a>
              </li>
              <li class="nav-item">
                <a href="{{ route('superadmin.pip.index') }}"
                  class="nav-link {{ Request::routeIs('superadmin.pip.*') ? 'active' : '' }}">
                  <i class="far fa-circle nav-icon text-warning"></i>
                  <p>Data PIP</p>
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
            <a href="{{ route('superadmin.reports.index') }}"
              class="nav-link {{ Request::routeIs('superadmin.reports.*') ? 'active' : '' }}">
              <i class="nav-icon fas fa-chart-pie"></i>
              <p>Laporan Sekolah</p>
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

          <li class="nav-item">
            <a href="{{ route('superadmin.backups.index') }}"
              class="nav-link {{ Request::routeIs('superadmin.backups.*') ? 'active' : '' }}">
              <i class="nav-icon fas fa-archive"></i>
              <p>Backup & Restore</p>
            </a>
          </li>

