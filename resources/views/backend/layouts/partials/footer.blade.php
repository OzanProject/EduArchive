<footer class="main-footer text-sm">
  <div class="float-right d-none d-sm-block">
    <b>Version</b> {{ $app_settings['app_version'] ?? '1.0.0 (Beta)' }}
  </div>
  <strong>{{ $app_settings['app_footer'] ?? 'Copyright © ' . date('Y') . ' Dinas Pendidikan Kota' }}.</strong> All
  rights reserved.
  Developed by <a href="https://ozanproject.site" class="text-muted">Ozan Project</a>.
</footer>