@extends('backend.layouts.app')

@section('title', 'Footer & Social Media')

@section('content')
  <section class="content-header">
    <div class="container-fluid">
      <div class="row mb-2">
        <div class="col-sm-6">
          <h1>Footer & Social Media</h1>
        </div>
        <div class="col-sm-6">
          <ol class="breadcrumb float-sm-right">
            <li class="breadcrumb-item"><a href="{{ route('superadmin.dashboard') }}">Dashboard</a></li>
            <li class="breadcrumb-item active">Footer & Social</li>
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
            <form action="{{ route('superadmin.settings.update') }}" method="POST">
              @csrf
              <div class="card-body">
                <div class="form-group">
                  <label>Deskripsi Footer (Tentang Aplikasi)</label>
                  <textarea name="footer_description" class="form-control summernote-simple"
                    rows="3">{{ $settings['footer_description'] ?? ($settings['app_description'] ?? '') }}</textarea>
                  <small class="text-muted">Akan muncul di bagian kiri bawah footer.</small>
                </div>

                <div class="form-group">
                  <label for="app_footer">Teks Copyright Footer</label>
                  <textarea name="app_footer" class="form-control summernote-simple" id="app_footer"
                    rows="2">{{ $settings['app_footer'] ?? 'Copyright © ' . date('Y') . ' EduArchive' }}</textarea>
                </div>

                <hr>
                <h5 class="text-primary"><i class="fas fa-share-alt mr-2"></i> Sosial Media</h5>
                <div class="row">
                  <div class="col-md-6">
                    <div class="form-group">
                      <label><i class="fab fa-facebook text-primary"></i> Facebook URL</label>
                      <input type="text" name="social_facebook" class="form-control"
                        value="{{ $settings['social_facebook'] ?? '' }}" placeholder="https://facebook.com/...">
                    </div>
                  </div>
                  <div class="col-md-6">
                    <div class="form-group">
                      <label><i class="fab fa-twitter text-info"></i> Twitter (X) URL</label>
                      <input type="text" name="social_twitter" class="form-control"
                        value="{{ $settings['social_twitter'] ?? '' }}" placeholder="https://twitter.com/...">
                    </div>
                  </div>
                  <div class="col-md-6">
                    <div class="form-group">
                      <label><i class="fab fa-instagram text-danger"></i> Instagram URL</label>
                      <input type="text" name="social_instagram" class="form-control"
                        value="{{ $settings['social_instagram'] ?? '' }}" placeholder="https://instagram.com/...">
                    </div>
                  </div>
                  <div class="col-md-6">
                    <div class="form-group">
                      <label><i class="fab fa-youtube text-danger"></i> Youtube URL</label>
                      <input type="text" name="social_youtube" class="form-control"
                        value="{{ $settings['social_youtube'] ?? '' }}" placeholder="https://youtube.com/...">
                    </div>
                  </div>
                </div>

                <hr>
                <h5 class="text-primary mb-3"><i class="fas fa-file-contract mr-2"></i> Link Legal / Kebijakan (Global)
                </h5>
                <div class="row">
                  <div class="col-md-6">
                    <div class="form-group">
                      <label>URL Kebijakan Privasi (Privacy Policy)</label>
                      <input type="text" name="link_privacy" class="form-control"
                        value="{{ $settings['link_privacy'] ?? '#' }}" placeholder="https://...">
                    </div>
                  </div>
                  <div class="col-md-6">
                    <div class="form-group">
                      <label>URL Syarat & Ketentuan (Terms of Service)</label>
                      <input type="text" name="link_terms" class="form-control"
                        value="{{ $settings['link_terms'] ?? '#' }}" placeholder="https://...">
                    </div>
                  </div>
                </div>

                <hr>
                <h5 class="text-primary mb-3"><i class="fas fa-link mr-2"></i> Link Footer</h5>

                <div class="row">
                  <!-- Product Links -->
                  <div class="col-md-4">
                    <div class="card card-outline card-secondary">
                      <div class="card-header">
                        <h3 class="card-title">Kolom 1 (Produk)</h3>
                      </div>
                      <div class="card-body p-2">
                        @for($i = 1; $i <= 4; $i++)
                          <div class="form-group mb-2">
                            <label class="small text-muted">Link {{ $i }}</label>
                            <div class="input-group input-group-sm mb-1">
                              <div class="input-group-prepend"><span class="input-group-text">Teks</span></div>
                              <input type="text" name="footer_prod_text_{{ $i }}" class="form-control"
                                value="{{ $settings['footer_prod_text_' . $i] ?? '' }}">
                            </div>
                            <div class="input-group input-group-sm">
                              <div class="input-group-prepend"><span class="input-group-text">URL</span></div>
                              <input type="text" name="footer_prod_url_{{ $i }}" class="form-control"
                                value="{{ $settings['footer_prod_url_' . $i] ?? '' }}" placeholder="/p/judul-halaman">
                            </div>
                          </div>
                        @endfor
                      </div>
                    </div>
                  </div>

                  <!-- Company Links -->
                  <div class="col-md-4">
                    <div class="card card-outline card-secondary">
                      <div class="card-header">
                        <h3 class="card-title">Kolom 2 (Perusahaan)</h3>
                      </div>
                      <div class="card-body p-2">
                        @for($i = 1; $i <= 4; $i++)
                          <div class="form-group mb-2">
                            <label class="small text-muted">Link {{ $i }}</label>
                            <div class="input-group input-group-sm mb-1">
                              <div class="input-group-prepend"><span class="input-group-text">Teks</span></div>
                              <input type="text" name="footer_comp_text_{{ $i }}" class="form-control"
                                value="{{ $settings['footer_comp_text_' . $i] ?? '' }}">
                            </div>
                            <div class="input-group input-group-sm">
                              <div class="input-group-prepend"><span class="input-group-text">URL</span></div>
                              <input type="text" name="footer_comp_url_{{ $i }}" class="form-control"
                                value="{{ $settings['footer_comp_url_' . $i] ?? '' }}" placeholder="/p/judul-halaman">
                            </div>
                          </div>
                        @endfor
                      </div>
                    </div>
                  </div>

                  <!-- Legal Links -->
                  <div class="col-md-4">
                    <div class="card card-outline card-secondary">
                      <div class="card-header">
                        <h3 class="card-title">Kolom 3 (Legal)</h3>
                      </div>
                      <div class="card-body p-2">
                        @for($i = 1; $i <= 3; $i++)
                          <div class="form-group mb-2">
                            <label class="small text-muted">Link {{ $i }}</label>
                            <div class="input-group input-group-sm mb-1">
                              <div class="input-group-prepend"><span class="input-group-text">Teks</span></div>
                              <input type="text" name="footer_legal_text_{{ $i }}" class="form-control"
                                value="{{ $settings['footer_legal_text_' . $i] ?? '' }}">
                            </div>
                            <div class="input-group input-group-sm">
                              <div class="input-group-prepend"><span class="input-group-text">URL</span></div>
                              <input type="text" name="footer_legal_url_{{ $i }}" class="form-control"
                                value="{{ $settings['footer_legal_url_' . $i] ?? '' }}" placeholder="/p/judul-halaman">
                            </div>
                          </div>
                        @endfor
                      </div>
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

  @push('styles')
    <link rel="stylesheet" href="{{ asset('adminlte3/plugins/summernote/summernote-bs4.min.css') }}">
  @endpush

  @push('scripts')
    <script src="{{ asset('adminlte3/plugins/summernote/summernote-bs4.min.js') }}"></script>
    <script>
      $(document).ready(function () {
        // Initialize Summernote
        $('.summernote-simple').summernote({
          height: 120,
          toolbar: [
            ['style', ['bold', 'italic', 'underline', 'clear']],
            ['font', ['strikethrough']],
            ['para', ['ul', 'ol', 'paragraph']],
            ['insert', ['link']],
            ['view', ['codeview']]
          ]
        });
      });
    </script>
  @endpush
@endsection