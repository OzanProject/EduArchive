@extends('backend.layouts.app')

@section('title', 'Pengaturan Aplikasi')
@section('page_title', 'General Settings')

@section('breadcrumb')
  <li class="breadcrumb-item"><a href="{{ route('superadmin.dashboard') }}">Home</a></li>
  <li class="breadcrumb-item active">Settings</li>
@endsection

@section('content')
  <div class="row">
    <div class="col-md-12">
      <div class="card card-primary card-outline card-tabs">
        <div class="card-header p-0 pt-1 border-bottom-0">
          <ul class="nav nav-tabs" id="settings-tab" role="tablist">
            <li class="nav-item">
              <a class="nav-link active" id="general-tab" data-toggle="pill" href="#general" role="tab"
                aria-controls="general" aria-selected="true">General</a>
            </li>
            <li class="nav-item">
              <a class="nav-link" id="hero-tab" data-toggle="pill" href="#hero" role="tab" aria-controls="hero"
                aria-selected="false">Hero Section</a>
            </li>
            <li class="nav-item">
              <a class="nav-link" id="features-tab" data-toggle="pill" href="#features" role="tab"
                aria-controls="features" aria-selected="false">Features</a>
            </li>
            <li class="nav-item">
              <a class="nav-link" id="architecture-tab" data-toggle="pill" href="#architecture" role="tab"
                aria-controls="architecture" aria-selected="false">Architecture</a>
            </li>
            <li class="nav-item">
              <a class="nav-link" id="partners-tab" data-toggle="pill" href="#partners" role="tab"
                aria-controls="partners" aria-selected="false">Partners & CTA</a>
            </li>
            <li class="nav-item">
              <a class="nav-link" id="smtp-tab" data-toggle="pill" href="#smtp" role="tab" aria-controls="smtp"
                aria-selected="false">SMTP Email</a>
            </li>
            <li class="nav-item">
              <a class="nav-link" id="footer-social-tab" data-toggle="pill" href="#footer-social" role="tab"
                aria-controls="footer-social" aria-selected="false">Footer & Social</a>
            </li>
          </ul>
        </div>
        <form action="{{ route('superadmin.settings.update') }}" method="POST" enctype="multipart/form-data">
          @csrf
          <div class="card-body">
            <div class="tab-content" id="settings-tabContent">
              <!-- General Tab -->
              <div class="tab-pane fade show active" id="general" role="tabpanel" aria-labelledby="general-tab">
                <div class="form-group">
                  <label for="app_name">Nama Aplikasi</label>
                  <input type="text" name="app_name" class="form-control" id="app_name"
                    value="{{ $settings['app_name'] ?? 'EduArchive' }}" placeholder="Nama Aplikasi">
                </div>

                <div class="form-group">
                  <label for="app_description">Deskripsi Singkat (Meta Description)</label>
                  <textarea name="app_description" class="form-control" rows="3"
                    placeholder="Deskripsi aplikasi...">{{ $settings['app_description'] ?? '' }}</textarea>
                </div>

                <div class="form-group">
                  <label for="app_footer">Teks Footer</label>
                  <input type="text" name="app_footer" class="form-control" id="app_footer"
                    value="{{ $settings['app_footer'] ?? 'Copyright © 2026 EduArchive' }}">
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
              </div>

              <!-- Landing Hero Tab -->
              <div class="tab-pane fade" id="hero" role="tabpanel" aria-labelledby="hero-tab">
                <div class="form-group">
                  <label>Tagline (Badge)</label>
                  <input type="text" name="landing_hero_tagline" class="form-control"
                    value="{{ $settings['landing_hero_tagline'] ?? 'Dipercaya oleh 50+ Dinas Pendidikan' }}">
                </div>
                <div class="form-group">
                  <label>Judul Utama (Baris 1)</label>
                  <input type="text" name="landing_hero_title_1" class="form-control"
                    value="{{ $settings['landing_hero_title_1'] ?? 'Solusi Modern' }}">
                </div>
                <div class="form-group">
                  <label>Judul Utama (Highlight Biru)</label>
                  <input type="text" name="landing_hero_title_highlight"
                    class="form-control text-primary font-weight-bold"
                    value="{{ $settings['landing_hero_title_highlight'] ?? 'Arsip Digital' }}">
                </div>
                <div class="form-group">
                  <label>Judul Utama (Baris 2)</label>
                  <input type="text" name="landing_hero_title_2" class="form-control"
                    value="{{ $settings['landing_hero_title_2'] ?? 'Pendidikan' }}">
                </div>
                <div class="form-group">
                  <label>Deskripsi Hero</label>
                  <textarea name="landing_hero_desc" class="form-control summernote-simple"
                    rows="3">{{ $settings['landing_hero_desc'] ?? 'Platform multi-tenant yang aman dan terintegrasi untuk Dinas Pendidikan dan Sekolah. Kelola dokumen ijazah, SK, dan administrasi sekolah dalam satu ekosistem terpusat.' }}</textarea>
                </div>
                <div class="form-group">
                  <label for="landing_hero_image">Hero Dashboard Image</label>
                  <div class="input-group">
                    <div class="custom-file">
                      <input type="file" class="custom-file-input" id="landing_hero_image" name="landing_hero_image">
                      <label class="custom-file-label" for="landing_hero_image">Choose file</label>
                    </div>
                  </div>
                  <div class="mt-2" id="landing_hero_image_preview_container"
                    style="{{ isset($settings['landing_hero_image']) ? '' : 'display:none' }}">
                    <img id="landing_hero_image_preview"
                      src="{{ isset($settings['landing_hero_image']) ? asset($settings['landing_hero_image']) : '' }}"
                      alt="Hero Image" style="max-height: 100px;">
                  </div>
                </div>
              </div>

              <!-- Features Tab -->
              <div class="tab-pane fade" id="features" role="tabpanel" aria-labelledby="features-tab">
                <div class="form-group">
                  <label>Judul Seksi Fitur</label>
                  <input type="text" name="landing_feat_title" class="form-control"
                    value="{{ $settings['landing_feat_title'] ?? 'Satu Platform, Beragam Solusi' }}">
                </div>
                <div class="form-group">
                  <label>Sub-Judul Seksi Fitur</label>
                  <textarea name="landing_feat_subtitle" class="form-control summernote-simple"
                    rows="2">{{ $settings['landing_feat_subtitle'] ?? 'Kami menghadirkan fitur spesifik yang dirancang khusus untuk memenuhi kebutuhan birokrasi pendidikan yang kompleks namun tetap efisien.' }}</textarea>
                </div>
                <hr>
                <h5 class="text-primary">Grup Fitur 1 (Kiri)</h5>
                <div class="form-group">
                  <label>Judul Grup 1</label>
                  <input type="text" name="landing_feat_g1_title" class="form-control"
                    value="{{ $settings['landing_feat_g1_title'] ?? 'Untuk Dinas Pendidikan' }}">
                </div>
                <h5 class="text-success mt-4">Grup Fitur 2 (Kanan)</h5>
                <div class="form-group">
                  <label>Judul Grup 2</label>
                  <input type="text" name="landing_feat_g2_title" class="form-control"
                    value="{{ $settings['landing_feat_g2_title'] ?? 'Untuk Satuan Pendidikan' }}">
                </div>
              </div>

              <!-- Architecture Tab -->
              <div class="tab-pane fade" id="architecture" role="tabpanel" aria-labelledby="architecture-tab">
                <div class="form-group">
                  <label>Judul Seksi Arsitektur</label>
                  <input type="text" name="landing_arch_title" class="form-control"
                    value="{{ $settings['landing_arch_title'] ?? 'Arsitektur Multi-Tenant yang Aman & Terukur' }}">
                </div>
                <div class="form-group">
                  <label>Deskripsi Arsitektur</label>
                  <textarea name="landing_arch_desc" class="form-control summernote-simple"
                    rows="3">{{ $settings['landing_arch_desc'] ?? 'Dirancang dengan teknologi Multi-Tenant tingkat lanjut. Artinya, satu infrastruktur besar melayani ribuan sekolah, namun data antar sekolah tetap terisolasi secara total.' }}</textarea>
                </div>
                <div class="form-group">
                  <label for="landing_arch_image">Gambar Diagram Arsitektur</label>
                  <div class="input-group">
                    <div class="custom-file">
                      <input type="file" class="custom-file-input" id="landing_arch_image" name="landing_arch_image">
                      <label class="custom-file-label" for="landing_arch_image">Choose file</label>
                    </div>
                  </div>
                  <div class="mt-2" id="landing_arch_image_preview_container"
                    style="{{ isset($settings['landing_arch_image']) ? '' : 'display:none' }}">
                    <img id="landing_arch_image_preview"
                      src="{{ isset($settings['landing_arch_image']) ? asset($settings['landing_arch_image']) : '' }}"
                      alt="Architecture Image" style="max-height: 100px;">
                  </div>
                </div>
              </div>

              <!-- Partners & CTA Tab -->
              <div class="tab-pane fade" id="partners" role="tabpanel" aria-labelledby="partners-tab">
                <label>Logo Partner (Instansi Pengguna)</label>
                <div class="row">
                  @for ($i = 1; $i <= 5; $i++)
                    <div class="col-md-2">
                      <div class="form-group text-center">
                        <label class="small">Logo {{ $i }}</label>
                        <div class="custom-file mb-2">
                          <input type="file" class="custom-file-input" id="landing_partner_logo_{{ $i }}"
                            name="landing_partner_logo_{{ $i }}">
                          <label class="custom-file-label" for="landing_partner_logo_{{ $i }}">Pick</label>
                        </div>
                        <div id="landing_partner_logo_{{ $i }}_preview_container"
                          style="{{ isset($settings['landing_partner_logo_' . $i]) ? '' : 'display:none' }}">
                          <img id="landing_partner_logo_{{ $i }}_preview"
                            src="{{ isset($settings['landing_partner_logo_' . $i]) ? asset($settings['landing_partner_logo_' . $i]) : '' }}"
                            class="img-fluid border rounded p-1" style="max-height: 40px;">
                        </div>
                      </div>
                    </div>
                  @endfor
                </div>
                <hr>
                <h5 class="mt-4">Call to Action (Footer)</h5>
                <div class="form-group">
                  <label>Judul CTA</label>
                  <input type="text" name="landing_cta_title" class="form-control"
                    value="{{ $settings['landing_cta_title'] ?? 'Siap Mendigitalkan Arsip Pendidikan Anda?' }}">
                </div>
                <div class="form-group">
                  <label>Deskripsi CTA</label>
                  <textarea name="landing_cta_desc" class="form-control summernote-simple"
                    rows="2">{{ $settings['landing_cta_desc'] ?? 'Bergabunglah dengan puluhan instansi lain yang telah meningkatkan efisiensi administrasi mereka.' }}</textarea>
                </div>
              </div>

              <!-- SMTP Tab -->
              <div class="tab-pane fade" id="smtp" role="tabpanel" aria-labelledby="smtp-tab">
                <div class="callout callout-info">
                  <h5><i class="fas fa-info"></i> Info</h5>
                  Pengaturan ini akan menimpa konfigurasi `.env` default aplikasi. Kosongkan jika ingin menggunakan
                  default `.env`.
                </div>

                <div class="form-group">
                  <label>Mail Driver</label>
                  <select class="form-control" name="mail_driver">
                    <option value="smtp" {{ ($settings['mail_driver'] ?? 'smtp') == 'smtp' ? 'selected' : '' }}>SMTP
                    </option>
                    <option value="log" {{ ($settings['mail_driver'] ?? '') == 'log' ? 'selected' : '' }}>Log (Testing)
                    </option>
                  </select>
                </div>

                <div class="row">
                  <div class="col-md-6">
                    <div class="form-group">
                      <label>Mail Host</label>
                      <input type="text" name="mail_host" class="form-control" value="{{ $settings['mail_host'] ?? '' }}"
                        placeholder="smtp.mailtrap.io">
                    </div>
                  </div>
                  <div class="col-md-6">
                    <div class="form-group">
                      <label>Mail Port</label>
                      <input type="text" name="mail_port" class="form-control"
                        value="{{ $settings['mail_port'] ?? '2525' }}" placeholder="2525">
                    </div>
                  </div>
                </div>

                <div class="row">
                  <div class="col-md-6">
                    <div class="form-group">
                      <label>Mail Username</label>
                      <input type="text" name="mail_username" class="form-control"
                        value="{{ $settings['mail_username'] ?? '' }}">
                    </div>
                  </div>
                  <div class="col-md-6">
                    <div class="form-group">
                      <label>Mail Password</label>
                      <input type="password" name="mail_password" class="form-control"
                        value="{{ $settings['mail_password'] ?? '' }}">
                    </div>
                  </div>
                </div>

                <div class="form-group">
                  <label>Mail Encryption</label>
                  <select class="form-control" name="mail_encryption">
                    <option value="tls" {{ ($settings['mail_encryption'] ?? 'tls') == 'tls' ? 'selected' : '' }}>TLS
                    </option>
                    <option value="ssl" {{ ($settings['mail_encryption'] ?? '') == 'ssl' ? 'selected' : '' }}>SSL</option>
                    <option value="" {{ ($settings['mail_encryption'] ?? '') == '' ? 'selected' : '' }}>None</option>
                  </select>
                </div>

                <div class="row">
                  <div class="col-md-6">
                    <div class="form-group">
                      <label>From Address (Email Pengirim)</label>
                      <input type="email" name="mail_from_address" class="form-control"
                        value="{{ $settings['mail_from_address'] ?? '' }}" placeholder="hello@example.com">
                    </div>
                  </div>
                  <div class="col-md-6">
                    <div class="form-group">
                      <label>From Name (Nama Pengirim)</label>
                      <input type="text" name="mail_from_name" class="form-control"
                        value="{{ $settings['mail_from_name'] ?? 'EduArchive' }}" placeholder="EduArchive System">
                    </div>
                  </div>
                </div>

              </div>
              <!-- /.tab-pane -->

              <!-- Footer & Social Tab -->
              <div class="tab-pane fade" id="footer-social" role="tabpanel" aria-labelledby="footer-social-tab">
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
              <!-- /.tab-pane -->
            </div>
          </div>
          <!-- /.card-body -->

          <div class="card-footer">
            <button type="submit" class="btn btn-primary">Simpan Pengaturan</button>
          </div>
        </form>
      </div>
    </div>
  </div>

  @push('styles')
    <link rel="stylesheet" href="{{ asset('adminlte3/plugins/summernote/summernote-bs4.min.css') }}">
  @endpush

  @push('scripts')
    <script src="{{ asset('adminlte3/plugins/summernote/summernote-bs4.min.js') }}"></script>
    <script>
      $(document).ready(function () {
        // Initialize Summernote
        $('.summernote-simple').summernote({
          height: 150,
          toolbar: [
            ['style', ['bold', 'italic', 'underline', 'clear']],
            ['font', ['strikethrough', 'superscript', 'subscript']],
            ['color', ['color']],
            ['para', ['ul', 'ol', 'paragraph']],
            ['insert', ['link', 'picture', 'video']], // Added media capability
            ['view', ['fullscreen', 'codeview', 'help']]
          ]
        });

        // Generic Image Previewer using ID Convention
        // Input ID: {name}
        // Preview Img ID: {name}_preview
        // Container ID: {name}_preview_container
        $('.custom-file-input').on('change', function () {
          let inputId = $(this).attr('id');
          let file = this.files[0];
          let reader = new FileReader();

          reader.onload = (e) => {
            let imgId = '#' + inputId + '_preview';
            let containerId = '#' + inputId + '_preview_container';

            // Show container if hidden
            $(containerId).show();
            // Set Src
            $(imgId).attr('src', e.target.result);
            // Update label
            $(this).next('.custom-file-label').html(file.name);
          }

          if (file) {
            reader.readAsDataURL(file);
          }
        });
      });
    </script>
  @endpush
@endsection