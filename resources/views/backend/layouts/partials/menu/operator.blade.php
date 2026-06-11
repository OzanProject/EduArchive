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

          <li class="nav-item">
            <a href="{{ route('operator.integrity-pacts.index') }}"
              class="nav-link {{ Request::routeIs('operator.integrity-pacts.*') ? 'active' : '' }}">
              <i class="nav-icon fas fa-file-signature"></i>
              <p>Fakta Integritas</p>
            </a>
          </li>

          <li class="nav-item">
            <a href="{{ route('operator.pip.index') }}"
              class="nav-link {{ Request::routeIs('operator.pip.*') ? 'active' : '' }}">
              <i class="nav-icon fas fa-hand-holding-usd text-warning"></i>
              <p>Data PIP</p>
            </a>
          </li>

          {{-- Panduan --}}
          <li class="nav-item">
            <a href="{{ route('operator.guide') }}"
              class="nav-link {{ Request::routeIs('operator.guide') ? 'active' : '' }}">
              <i class="nav-icon fas fa-book-reader"></i>
              <p>Panduan Website</p>
            </a>
          </li>
