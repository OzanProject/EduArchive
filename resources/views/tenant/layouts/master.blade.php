<!DOCTYPE html>
<html lang="id">

<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>@yield('title') | {{ tenant('nama_sekolah') }}</title>
  <link rel="icon"
    href="{{ !empty($central_branding['app_favicon']) ? asset($central_branding['app_favicon']) : asset('favicon.ico') }}">

  <!-- Google Font: Plus Jakarta Sans (Modern Geometric Sans) -->
  <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&display=swap"
    rel="stylesheet">
  <!-- Font Awesome -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
  <!-- AOS Animation -->
  <link href="https://unpkg.com/aos@2.3.1/dist/aos.css" rel="stylesheet">
  <!-- Bootstrap 5 (Modern) -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

  <style>
    :root {
      --primary: #2563eb;
      --primary-dark: #1d4ed8;
      --secondary: #64748b;
      --surface: #ffffff;
      --background: #f8fafc;
      --text-main: #0f172a;
      --text-muted: #64748b;
    }

    body {
      font-family: 'Plus Jakarta Sans', sans-serif;
      background-color: var(--background);
      color: var(--text-main);
      overflow-x: hidden;
      padding-top: 80px;
      /* Space for fixed navbar */
    }

    /* Navbar Customization */
    .navbar-school {
      background: rgba(255, 255, 255, 0.95);
      backdrop-filter: blur(10px);
      box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.05);
      padding: 1rem 0;
      transition: all 0.3s ease;
    }

    .navbar-brand {
      font-weight: 700;
      color: var(--text-main);
      display: flex;
      align-items: center;
      gap: 0.75rem;
    }

    .navbar-brand img {
      height: 40px;
      width: auto;
    }

    .nav-link {
      font-weight: 600;
      color: var(--text-muted);
      margin: 0 0.5rem;
      transition: color 0.2s;
    }

    .nav-link:hover,
    .nav-link.active {
      color: var(--primary);
    }

    .btn-primary-custom {
      background-color: var(--primary);
      border: none;
      padding: 0.6rem 1.5rem;
      border-radius: 50px;
      font-weight: 600;
      transition: all 0.3s;
      color: white;
    }

    .btn-primary-custom:hover {
      background-color: var(--primary-dark);
      transform: translateY(-2px);
      box-shadow: 0 4px 12px rgba(37, 99, 235, 0.3);
      color: white;
    }

    /* Footer */
    footer {
      background: #0f172a;
      color: #cbd5e1;
      padding: 4rem 0 2rem;
      margin-top: auto;
    }

    footer h5 {
      color: white;
      font-weight: 700;
      margin-bottom: 1.5rem;
    }

    footer a {
      color: #94a3b8;
      text-decoration: none;
      transition: color 0.2s;
    }

    footer a:hover {
      color: white;
    }

    /* Helper Classes */
    .text-primary-custom {
      color: var(--primary);
    }

    .bg-light-custom {
      background-color: #f1f5f9;
    }

    @yield('styles')
  </style>
</head>

<body class="d-flex flex-column min-vh-100">

  @include('tenant.partials.navbar')

  <main>
    @yield('content')
  </main>

  @include('tenant.partials.footer')

  <!-- Scripts -->
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
  <script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>
  <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
  <script>
    AOS.init({
      duration: 800,
      once: true
    });
  </script>
  @stack('scripts')
</body>

</html>