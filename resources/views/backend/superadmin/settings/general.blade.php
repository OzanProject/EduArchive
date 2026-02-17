@extends('backend.layouts.app')

@section('title', 'Pengaturan Umum')

@section('content')
  <section class="content-header">
    <div class="container-fluid">
      <div class="row mb-2">
        <div class="col-sm-6">
          <h1>Pengaturan Umum</h1>
        </div>
        <div class="col-sm-6">
          <ol class="breadcrumb float-sm-right">
            <li class="breadcrumb-item"><a href="{{ route('superadmin.dashboard') }}">Dashboard</a></li>
            <li class="breadcrumb-item active">Pengaturan Umum</li>
          </ol>
        </div>
      </div>
    </div>
  </section>

  <section class="content">
    <div class="container-fluid">
      <div class="row">
        <div class="col-md-12">
          <div class="card card-outline card-primary">
            <form action="{{ route('superadmin.settings.update') }}" method="POST" enctype="multipart/form-data">
              @csrf
              <div class="card-body">
                <div class="form-group">
                  <label for="app_name">Nama Aplikasi</label>
                  <input type="text" name="app_name" class="form-control" id="app_name"
                    value="{{ $settings['app_name'] ?? 'EduArchive' }}" placeholder="Nama Aplikasi">
                </div>

                <div class="form-group">
                  <label for="app_version">Versi Aplikasi</label>
                  <input type="text" name="app_version" class="form-control" id="app_version"
                    value="{{ $settings['app_version'] ?? '1.0.0 (Beta)' }}" placeholder="1.0.0 (Beta)">
                </div>

                <div class="form-group">
                  <label for="app_description">Deskripsi Singkat (Meta Description)</label>
                  <textarea name="app_description" class="form-control" rows="3"
                    placeholder="Deskripsi aplikasi...">{{ $settings['app_description'] ?? '' }}</textarea>
                </div>

                <div class="form-group">
                  <label>Zona Waktu Default</label>
                  <select class="form-control" name="app_timezone">
                    <option value="Asia/Jakarta" {{ ($settings['app_timezone'] ?? 'Asia/Jakarta') == 'Asia/Jakarta' ? 'selected' : '' }}>WIB (Asia/Jakarta)</option>
                    <option value="Asia/Makassar" {{ ($settings['app_timezone'] ?? '') == 'Asia/Makassar' ? 'selected' : '' }}>
                      WITA (Asia/Makassar)</option>
                    <option value="Asia/Jayapura" {{ ($settings['app_timezone'] ?? '') == 'Asia/Jayapura' ? 'selected' : '' }}>
                      WIT (Asia/Jayapura)</option>
                  </select>
                </div>

                <div class="form-group">
                  <label for="app_support_phone">Nomor WhatsApp Support</label>
                  <input type="text" name="app_support_phone" class="form-control" id="app_support_phone"
                    value="{{ $settings['app_support_phone'] ?? '' }}" placeholder="6281234567890">
                </div>

                <div class="row">
                  <div class="col-md-6">
                    <div class="form-group">
                      <label for="app_logo">Logo Aplikasi</label>
                      <div class="input-group">
                        <div class="custom-file">
                          <input type="file" class="custom-file-input" id="app_logo" name="app_logo">
                          <label class="custom-file-label" for="app_logo">Choose file</label>
                        </div>
                      </div>
                      <div class="mt-2" id="app_logo_preview_container"
                        style="{{ isset($settings['app_logo']) ? '' : 'display:none' }}">
                        <img id="app_logo_preview"
                          src="{{ isset($settings['app_logo']) ? asset($settings['app_logo']) : '' }}" alt="App Logo"
                          style="max-height: 50px;">
                      </div>
                    </div>
                  </div>
                  <div class="col-md-6">
                    <div class="form-group">
                      <label for="app_favicon">Favicon Browser</label>
                      <div class="input-group">
                        <div class="custom-file">
                          <input type="file" class="custom-file-input" id="app_favicon" name="app_favicon">
                          <label class="custom-file-label" for="app_favicon">Choose file</label>
                        </div>
                      </div>
                      <div class="mt-2" id="app_favicon_preview_container"
                        style="{{ isset($settings['app_favicon']) ? '' : 'display:none' }}">
                        <img id="app_favicon_preview"
                          src="{{ isset($settings['app_favicon']) ? asset($settings['app_favicon']) : '' }}"
                          alt="App Favicon" style="max-height: 32px;">
                      </div>
                    </div>
                  </div>
                </div>

                <div class="form-group">
                  <label for="login_cover_image">Gambar Sampul Login (Split Layout)</label>
                  <div class="input-group">
                    <div class="custom-file">
                      <input type="file" class="custom-file-input" id="login_cover_image" name="login_cover_image">
                      <label class="custom-file-label" for="login_cover_image">Choose file</label>
                    </div>
                  </div>
                  <div class="mt-2" id="login_cover_image_preview_container"
                    style="{{ isset($settings['login_cover_image']) ? '' : 'display:none' }}">
                    <img id="login_cover_image_preview"
                      src="{{ isset($settings['login_cover_image']) ? asset($settings['login_cover_image']) : '' }}"
                      alt="Login Cover" style="max-height: 100px; border-radius: 8px;">
                  </div>
                </div>
              </div>
          </div>
          <div class="card-footer">
            <button type="submit" class="btn btn-primary">Simpan Pengaturan</button>
          </div>
          </form>
        </div>
      </div>
    </div>
    </div>
  </section>

  @push('scripts')
    <script>
      $(document).ready(function () {
        $('.custom-file-input').on('change', function () {
          let inputId = $(this).attr('id');
          let file = this.files[0];
          let reader = new FileReader();
          reader.onload = (e) => {
            let imgId = '#' + inputId + '_preview';
            let containerId = '#' + inputId + '_preview_container';
            $(containerId).show();
            $(imgId).attr('src', e.target.result);
            $(this).next('.custom-file-label').html(file.name);
          }
          if (file) reader.readAsDataURL(file);
        });
      });
    </script>
  @endpush
@endsection