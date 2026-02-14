@extends('backend.layouts.app')

@section('title', 'Pengaturan Landing Page')

@section('content')
  <section class="content-header">
    <div class="container-fluid">
      <div class="row mb-2">
        <div class="col-sm-6">
          <h1>Pengaturan Landing Page</h1>
        </div>
        <div class="col-sm-6">
          <ol class="breadcrumb float-sm-right">
            <li class="breadcrumb-item"><a href="{{ route('superadmin.dashboard') }}">Dashboard</a></li>
            <li class="breadcrumb-item active">Landing Page</li>
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
            <div class="card-header p-2">
              <ul class="nav nav-pills">
                <li class="nav-item"><a class="nav-link active" href="#hero" data-toggle="tab">Hero Section</a></li>
                <li class="nav-item"><a class="nav-link" href="#features" data-toggle="tab">Fitur</a></li>
                <li class="nav-item"><a class="nav-link" href="#architecture" data-toggle="tab">Arsitektur</a></li>
                <li class="nav-item"><a class="nav-link" href="#cta" data-toggle="tab">CTA Section</a></li>
              </ul>
            </div>
            <form action="{{ route('superadmin.settings.update') }}" method="POST" enctype="multipart/form-data">
              @csrf
              <div class="card-body">
                <div class="tab-content">

                  <!-- Hero Tab -->
                  <div class="active tab-pane" id="hero">
                    <div class="form-group">
                      <label>Tagline Kecil (Atas Judul)</label>
                      <input type="text" name="landing_hero_tagline" class="form-control"
                        value="{{ $settings['landing_hero_tagline'] ?? 'Dipercaya oleh 50+ Dinas Pendidikan' }}">
                    </div>
                    <div class="row">
                      <div class="col-md-4">
                        <div class="form-group">
                          <label>Judul Bagian 1</label>
                          <input type="text" name="landing_hero_title_1" class="form-control"
                            value="{{ $settings['landing_hero_title_1'] ?? 'Solusi Modern' }}">
                        </div>
                      </div>
                      <div class="col-md-4">
                        <div class="form-group">
                          <label>Judul Highlight (Warna Biru)</label>
                          <input type="text" name="landing_hero_title_highlight" class="form-control"
                            value="{{ $settings['landing_hero_title_highlight'] ?? 'Arsip Digital' }}">
                        </div>
                      </div>
                      <div class="col-md-4">
                        <div class="form-group">
                          <label>Judul Bagian 2</label>
                          <input type="text" name="landing_hero_title_2" class="form-control"
                            value="{{ $settings['landing_hero_title_2'] ?? 'Pendidikan' }}">
                        </div>
                      </div>
                    </div>
                    <div class="form-group">
                      <label>Deskripsi Hero</label>
                      <textarea name="landing_hero_desc" class="form-control summernote-simple"
                        rows="3">{{ $settings['landing_hero_desc'] ?? 'Platform multi-tenant yang aman dan terintegrasi untuk Dinas Pendidikan dan Sekolah. Kelola dokumen ijazah, SK, dan administrasi sekolah dalam satu ekosistem terpusat.' }}</textarea>
                    </div>
                    <div class="form-group">
                      <label>Gambar Hero (Sebelah Kanan)</label>
                      <div class="input-group">
                        <div class="custom-file">
                          <input type="file" class="custom-file-input" id="landing_hero_image" name="landing_hero_image">
                          <label class="custom-file-label" for="landing_hero_image">Choose file</label>
                        </div>
                      </div>
                      @if(isset($settings['landing_hero_image']))
                        <div class="mt-2" id="landing_hero_image_preview_container">
                          <img id="landing_hero_image_preview" src="{{ asset($settings['landing_hero_image']) }}"
                            alt="Hero Image" style="max-height: 100px;">
                        </div>
                      @else
                        <div class="mt-2" id="landing_hero_image_preview_container" style="display:none">
                          <img id="landing_hero_image_preview" src="" alt="Hero Image" style="max-height: 100px;">
                        </div>
                      @endif
                    </div>
                  </div>

                  <!-- Features Tab -->
                  <div class="tab-pane" id="features">
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
                    <div class="row">
                      <div class="col-md-6">
                        <div class="form-group">
                          <label>Judul Grup 1 (Dinas)</label>
                          <input type="text" name="landing_feat_g1_title" class="form-control"
                            value="{{ $settings['landing_feat_g1_title'] ?? 'Untuk Dinas Pendidikan' }}">
                        </div>
                      </div>
                      <div class="col-md-6">
                        <div class="form-group">
                          <label>Judul Grup 2 (Sekolah)</label>
                          <input type="text" name="landing_feat_g2_title" class="form-control"
                            value="{{ $settings['landing_feat_g2_title'] ?? 'Untuk Satuan Pendidikan' }}">
                        </div>
                      </div>
                    </div>
                  </div>

                  <!-- Architecture Tab -->
                  <div class="tab-pane" id="architecture">
                    <div class="form-group">
                      <label>Judul Arsitektur</label>
                      <input type="text" name="landing_arch_title" class="form-control"
                        value="{{ $settings['landing_arch_title'] ?? 'Arsitektur Multi-Tenant yang Aman & Terukur' }}">
                    </div>
                    <div class="form-group">
                      <label>Deskripsi Arsitektur</label>
                      <textarea name="landing_arch_desc" class="form-control summernote-simple"
                        rows="3">{{ $settings['landing_arch_desc'] ?? 'Dirancang dengan teknologi Multi-Tenant tingkat lanjut. Artinya, satu infrastruktur besar melayani ribuan sekolah, namun data antar sekolah tetap terisolasi secara total.' }}</textarea>
                    </div>
                    <div class="form-group">
                      <label>Gambar Arsitektur (Sebelah Kiri)</label>
                      <div class="input-group">
                        <div class="custom-file">
                          <input type="file" class="custom-file-input" id="landing_arch_image" name="landing_arch_image">
                          <label class="custom-file-label" for="landing_arch_image">Choose file</label>
                        </div>
                      </div>
                      @if(isset($settings['landing_arch_image']))
                        <div class="mt-2" id="landing_arch_image_preview_container">
                          <img id="landing_arch_image_preview" src="{{ asset($settings['landing_arch_image']) }}"
                            alt="Arch Image" style="max-height: 100px;">
                        </div>
                      @else
                        <div class="mt-2" id="landing_arch_image_preview_container" style="display:none">
                          <img id="landing_arch_image_preview" src="" alt="Arch Image" style="max-height: 100px;">
                        </div>
                      @endif
                    </div>
                  </div>

                  <!-- CTA Tab -->
                  <div class="tab-pane" id="cta">
                    <div class="form-group">
                      <label>Judul CTA (Bawah)</label>
                      <input type="text" name="landing_cta_title" class="form-control"
                        value="{{ $settings['landing_cta_title'] ?? 'Siap Mendigitalkan Arsip Pendidikan Anda?' }}">
                    </div>
                    <div class="form-group">
                      <label>Deskripsi CTA</label>
                      <textarea name="landing_cta_desc" class="form-control summernote-simple"
                        rows="2">{{ $settings['landing_cta_desc'] ?? 'Bergabunglah dengan puluhan instansi lain yang telah meningkatkan efisiensi administrasi mereka.' }}</textarea>
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
          height: 150,
          toolbar: [
            ['style', ['bold', 'italic', 'underline', 'clear']],
            ['font', ['strikethrough', 'superscript', 'subscript']],
            ['color', ['color']],
            ['para', ['ul', 'ol', 'paragraph']],
            ['insert', ['link', 'picture', 'video']],
            ['view', ['fullscreen', 'codeview', 'help']]
          ]
        });

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