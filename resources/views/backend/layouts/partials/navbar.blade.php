<!-- Navbar -->
@php
  /** @var \App\Models\User $user */
  $user = Auth::user();
@endphp
<nav class="main-header navbar navbar-expand navbar-white navbar-light border-bottom-0 shadow-sm" style="height: 64px;">
  <!-- Left navbar links -->
  <ul class="navbar-nav align-items-center">
    <li class="nav-item">
      <a class="nav-link" data-widget="pushmenu" href="#" role="button"><i class="fas fa-bars text-secondary"></i></a>
    </li>

    <!-- Live Clock Widget (Compact) -->
    <li class="nav-item d-none d-sm-inline-block ml-2">
      <div class="d-flex flex-column" style="line-height: 1.1;">
        <span class="text-xs text-muted font-weight-bold text-uppercase" id="live-day-date">...</span>
        <span class="font-weight-bold text-dark text-sm" id="live-time">...</span>
      </div>
    </li>
  </ul>

  <!-- GLOBAL SEARCH (Center) -->
  <div class="mx-auto d-none d-md-flex align-items-center justify-content-center flex-grow-1" style="max-width: 500px;">
    <div class="w-100 position-relative">
      @if(auth()->user()->role !== 'superadmin')
        <form action="{{ route('adminlembaga.students.index') }}" method="GET" class="w-100">
      @endif

        <div class="input-group shadow-sm border"
          style="border-radius: 0.5rem; overflow: hidden; background-color: #f4f6f9;">
          <div class="input-group-prepend">
            <span class="input-group-text border-0 pl-3" style="background-color: transparent;"><i
                class="fas fa-search text-muted"></i></span>
          </div>
          <input class="form-control border-0 py-2" style="background-color: transparent;" type="search"
            name="{{ auth()->user()->role !== 'superadmin' ? 'search' : '' }}"
            placeholder="{{ auth()->user()->role === 'superadmin' ? 'Cari Siswa, Sekolah, atau NPSN...' : 'Cari Siswa...' }}"
            aria-label="Search">

          @if(auth()->user()->role !== 'superadmin')
            <input type="hidden" name="status" value="Aktif">
          @endif
        </div>

        @if(auth()->user()->role !== 'superadmin')
          </form>
        @endif
    </div>
  </div>

  <!-- Right navbar links -->
  <ul class="navbar-nav ml-auto align-items-center">

    @if(auth()->user()->role === 'superadmin')
      <!-- Quick Stats (Super Admin) -->
      <li class="nav-item d-none d-lg-block mr-4">
        <div class="text-xs text-muted">
          <span class="mr-2"><i class="fas fa-school text-primary mr-1"></i> <b>{{ \App\Models\Tenant::count() }}</b>
            Sekolah</span>
          <span><i class="fas fa-user-graduate text-success mr-1"></i> <b>{{ $global_student_count }}</b> Siswa</span>
        </div>
      </li>
    @elseif(auth()->user()->role === 'admin_sekolah' || auth()->user()->role === 'operator')
      <!-- Quick Stats (Tenant) -->
      <li class="nav-item d-none d-lg-block mr-3">
        <div class="d-flex flex-column text-right" style="line-height: 1.1;">
          <span class="text-xs text-muted">
            <i class="fas fa-user-graduate text-success mr-1"></i>
            <b>{{ $tenant_student_count ?? 0 }}</b> Siswa Aktif
          </span>
          <span class="text-xs text-muted">
            <i class="fas fa-calendar-alt text-info mr-1"></i>
            T.A {{ $app_settings['current_academic_year'] ?? date('Y') }}
          </span>
        </div>
      </li>
    @endif

    <!-- Fullscreen Toggle -->
    <li class="nav-item">
      <a class="nav-link" data-widget="fullscreen" href="#" role="button">
        <i class="fas fa-expand-arrows-alt text-secondary"></i>
      </a>
    </li>

    <!-- Notifications Dropdown -->
    <li class="nav-item dropdown">
      <a class="nav-link" data-toggle="dropdown" href="#">
        <i class="far fa-bell text-secondary"></i>
        @if($user->unreadNotifications->count() > 0)
          <span class="badge badge-danger navbar-badge">{{ $user->unreadNotifications->count() }}</span>
        @endif
      </a>
      <div class="dropdown-menu dropdown-menu-lg dropdown-menu-right border-0 shadow-lg rounded-lg mt-2">
        <span class="dropdown-item dropdown-header font-weight-bold">{{ $user->unreadNotifications->count() }}
          Notifications</span>

        @forelse($user->unreadNotifications->take(5) as $notification)
          <div class="dropdown-divider"></div>
          <a href="#" class="dropdown-item">
            <i class="fas fa-envelope mr-2 text-primary"></i>
            {{ \Illuminate\Support\Str::limit($notification->data['message'] ?? 'New Notification', 30) }}
            <span class="float-right text-muted text-sm">{{ $notification->created_at->diffForHumans() }}</span>
          </a>
        @empty
          <div class="dropdown-divider"></div>
          <a href="#" class="dropdown-item text-center text-muted">No new notifications</a>
        @endforelse

        <div class="dropdown-divider"></div>
        <a href="#" class="dropdown-item dropdown-footer text-primary font-weight-bold">See All Notifications</a>
      </div>
    </li>

    <!-- User Profile Dropdown -->
    <li class="nav-item dropdown user-menu ml-2">
      <a href="#" class="nav-link dropdown-toggle d-flex align-items-center" data-toggle="dropdown">
        @if($user->avatar)
          @php
            $avatarUrl = tenant() ? tenant_asset($user->avatar) : asset('storage/' . $user->avatar);
          @endphp
          <img src="{{ $avatarUrl }}" class="user-image img-circle elevation-1" alt="User Image"
            style="width: 32px; height: 32px; object-fit: cover;">
        @else
          <img
            src="{{ !empty($app_settings['app_logo']) ? $app_settings['app_logo'] : asset('adminlte/dist/img/user2-160x160.jpg') }}"
            class="user-image img-circle elevation-1" alt="User Image" style="width: 32px; height: 32px;">
        @endif
        <span class="d-none d-md-inline font-weight-bold ml-2 text-dark">{{ $user->name }}</span>
        <i class="fas fa-chevron-down text-xs text-muted ml-2"></i>
      </a>
      <ul class="dropdown-menu dropdown-menu-lg dropdown-menu-right border-0 shadow-lg rounded-lg mt-2">
        <!-- User Header -->
        <li class="user-header bg-white text-left pl-3 pt-3 pb-3 border-bottom">
          <div class="d-flex align-items-center">
            @if($user->avatar)
              @php
                $avatarUrl = tenant() ? tenant_asset($user->avatar) : asset('storage/' . $user->avatar);
              @endphp
              <img src="{{ $avatarUrl }}" class="img-circle elevation-1 mr-3" alt="User Image"
                style="width: 48px; height: 48px; object-fit: cover;">
            @else
              <img
                src="{{ !empty($app_settings['app_logo']) ? $app_settings['app_logo'] : asset('adminlte/dist/img/user2-160x160.jpg') }}"
                class="img-circle elevation-1 mr-3" alt="User Image" style="width: 48px; height: 48px;">
            @endif
            <div>
              <h6 class="font-weight-bold text-dark mb-0 text-wrap">{{ $user->name }}</h6>
              <small
                class="text-muted">{{ $user->role === 'admin_sekolah' ? 'Admin Sekolah' : ($user->role === 'operator' ? 'Operator' : 'Super Administrator') }}</small>
            </div>
          </div>
        </li>
        <!-- Menu Footer-->
        <li class="user-footer bg-light border-0">
          @if(auth()->user()->role === 'superadmin')
            <a href="{{ route('profile.edit') }}" class="btn btn-default btn-flat btn-sm rounded shadow-sm">Profile</a>
          @else
            <a href="{{ route('tenant.profile.edit', ['tenant' => tenant('id')]) }}"
              class="btn btn-default btn-flat btn-sm rounded shadow-sm">Profile</a>
          @endif
          <form method="POST" action="{{ route('logout') }}" class="d-inline-block float-right">
            @csrf
            <button type="submit" class="btn btn-danger btn-flat btn-sm rounded shadow-sm">Logout</button>
          </form>
        </li>
      </ul>
    </li>
  </ul>
</nav>

<!-- Live Clock Script -->
<script>
  function updateLiveClock() {
    const now = new Date();
    const optionsDate = { weekday: 'long', year: 'numeric', month: 'long', day: 'numeric' };
    // Gunakan timezone dari server jika memungkinkan, tapi untuk client-side visual, browser time cukup ok
    // atau kita bisa inject timezone dari BE ke variable JS

    document.getElementById('live-day-date').innerText = now.toLocaleDateString('id-ID', optionsDate);
    document.getElementById('live-time').innerText = now.toLocaleTimeString('id-ID', { hour: '2-digit', minute: '2-digit', second: '2-digit' }).replace(/\./g, ':');
  }

  setInterval(updateLiveClock, 1000);
  updateLiveClock(); // initial call
</script>
<!-- /.navbar -->