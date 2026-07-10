<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>
    @if(auth()->check() && auth()->user()->role === 'superadmin')
      {{ $app_settings['app_name'] ?? config('app.name') }}
    @else
      {{ tenant('nama_sekolah') ?? ($app_settings['school_name'] ?? ($app_settings['app_name'] ?? config('app.name'))) }}
    @endif
    | @yield('title')
  </title>
  <link rel="icon"
    href="{{ !empty($central_branding['app_favicon']) ? asset($central_branding['app_favicon']) : asset('favicon.ico') }}">

  <!-- Google Font: Inter -->
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
  <!-- Font Awesome -->
  <link rel="stylesheet" href="{{ asset('adminlte3/plugins/fontawesome-free/css/all.min.css') }}">
  <!-- Theme style -->
  <link rel="stylesheet" href="{{ asset('adminlte3/dist/css/adminlte.min.css') }}">
  <!-- Custom CSS -->
  <link rel="stylesheet" href="{{ asset('css/custom.css') }}">
  <!-- Enterprise Design System -->
  <link rel="stylesheet" href="{{ asset('css/modern-admin.css') }}">
  @stack('styles')
</head>

<body class="hold-transition sidebar-mini">
  <!-- Site wrapper -->
  <div class="wrapper">

    @include('backend.layouts.partials.navbar')

    @include('backend.layouts.partials.sidebar')

    <!-- Content Wrapper. Contains page content -->
    <div class="content-wrapper">
      <!-- Content Header (Page header) -->
      <section class="content-header">
        <div class="container-fluid">
          <div class="row mb-2">
            <div class="col-sm-6">
              <h1>@yield('page_title')</h1>
            </div>
            <div class="col-sm-6">
              <ol class="breadcrumb float-sm-right">
                @yield('breadcrumb')
              </ol>
            </div>
          </div>
        </div><!-- /.container-fluid -->
      </section>

      <!-- Main content -->
      <section class="content">
        @include('backend.layouts.partials.alert')
        @yield('content')
      </section>
      <!-- /.content -->
    </div>
    <!-- /.content-wrapper -->

    @include('backend.layouts.partials.footer')

    <!-- Control Sidebar -->
    <aside class="control-sidebar control-sidebar-dark">
      <!-- Control sidebar content goes here -->
    </aside>
    <!-- /.control-sidebar -->
  </div>
  <!-- ./wrapper -->

  <!-- jQuery -->
  <script src="{{ asset('adminlte3/plugins/jquery/jquery.min.js') }}"></script>
  <!-- Bootstrap 4 -->
  <script src="{{ asset('adminlte3/plugins/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
  <!-- AdminLTE App -->
  <script src="{{ asset('adminlte3/dist/js/adminlte.min.js') }}"></script>
  
  <!-- SweetAlert2 -->
  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
  <script>
    // Global Confirm Interceptor
    $(document).ready(function() {
        // Intercept onclick="return confirm('...')"
        $('[onclick*="return confirm"]').each(function() {
            var $el = $(this);
            var str = $el.attr('onclick');
            var match = str.match(/confirm\(['"](.*?)['"]\)/);
            if (match) {
                var msg = match[1];
                $el.removeAttr('onclick');
                $el.on('click', function(e) {
                    e.preventDefault();
                    Swal.fire({
                        title: 'Konfirmasi',
                        text: msg,
                        icon: 'warning',
                        showCancelButton: true,
                        confirmButtonColor: '#3085d6',
                        cancelButtonColor: '#d33',
                        confirmButtonText: 'Ya, Lanjutkan!',
                        cancelButtonText: 'Batal'
                    }).then((result) => {
                        if (result.isConfirmed) {
                            if ($el.is('button[type="submit"]') || $el.is('input[type="submit"]')) {
                                $el.closest('form').submit();
                            } else if ($el.is('a')) {
                                window.location.href = $el.attr('href');
                            } else if ($el.closest('form').length > 0) {
                                $el.closest('form').submit();
                            }
                        }
                    });
                });
            }
        });

        // Intercept onsubmit="return confirm('...')"
        $('form[onsubmit*="return confirm"]').each(function() {
            var $form = $(this);
            var str = $form.attr('onsubmit');
            var match = str.match(/confirm\(['"](.*?)['"]\)/);
            if (match) {
                var msg = match[1];
                $form.removeAttr('onsubmit');
                $form.on('submit', function(e) {
                    e.preventDefault();
                    Swal.fire({
                        title: 'Konfirmasi',
                        text: msg,
                        icon: 'warning',
                        showCancelButton: true,
                        confirmButtonColor: '#3085d6',
                        cancelButtonColor: '#d33',
                        confirmButtonText: 'Ya, Lanjutkan!',
                        cancelButtonText: 'Batal'
                    }).then((result) => {
                        if (result.isConfirmed) {
                            $form[0].submit();
                        }
                    });
                });
            }
        });
    });
  </script>
  
  @stack('scripts')
</body>

</html>