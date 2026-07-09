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
          <img src="https://ui-avatars.com/api/?name={{ urlencode($user->name) }}&background=random" class="img-circle elevation-2" alt="User Image">
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
        {{-- Super Admin Menu --}}
        @if($user->role === 'superadmin')
          @include('backend.layouts.partials.menu.superadmin')
        @endif

        {{-- Admin Lembaga Menu --}}
        @if($user->role === 'admin_sekolah')
          @include('backend.layouts.partials.menu.admin_sekolah')
        @endif

        {{-- Operator Menu --}}
        @if($user->role === 'operator')
          @include('backend.layouts.partials.menu.operator')
        @endif
      </ul>
    </nav>
    <!-- /.sidebar-menu -->
  </div>
  <!-- /.sidebar -->
</aside>
