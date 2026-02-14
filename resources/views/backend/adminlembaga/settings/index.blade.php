@extends('backend.layouts.app')

@section('title', 'Pengaturan Sekolah')
@section('page_title', 'General Settings')

@section('breadcrumb')
  <li class="breadcrumb-item"><a href="{{ route('adminlembaga.dashboard') }}">Home</a></li>
  <li class="breadcrumb-item active">Settings</li>
@endsection

@section('content')
  <div class="row">
    <div class="col-md-12">
      <div class="card card-primary card-outline card-tabs">
        <div class="card-header p-0 pt-1 border-bottom-0">
          <ul class="nav nav-tabs" id="settings-tab" role="tablist">
            <li class="nav-item">
              <a class="nav-link active" id="general-tab" data-toggle="pill" href="#general" role="tab">
                Profil Sekolah
              </a>
            </li>
            <li class="nav-item">
              <a class="nav-link" id="doc-tab" data-toggle="pill" href="#doc" role="tab">
                Dokumen & Kop Surat
              </a>
            </li>
            <li class="nav-item">
              <a class="nav-link" id="account-tab" data-toggle="pill" href="#account" role="tab">
                Info Akun
              </a>
            </li>
          </ul>
        </div>

        <div class="card-body">
          <div class="tab-content">

            {{-- ================= GENERAL TAB ================= --}}
            <div class="tab-pane fade show active" id="general">
              <form action="{{ route('adminlembaga.settings.update') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="form-group">
                  <label>Header Kop Surat (Misal: PEMERINTAH KABUPATEN CIANJUR)</label>
                  <input type="text" name="school_district_header" class="form-control"
                    value="{{ $settings['school_district_header'] ?? '' }}" placeholder="PEMERINTAH KABUPATEN CIANJUR">
                </div>

                <div class="form-group">
                  <label>Nama Sekolah (Alias)</label>
                  <input type="text" name="school_name" class="form-control" value="{{ $settings['school_name'] ?? '' }}">
                </div>

                <div class="form-group">
                  <label>Deskripsi / Visi Misi Singkat</label>
                  <textarea name="school_description" class="form-control"
                    rows="3">{{ $settings['school_description'] ?? '' }}</textarea>
                </div>

                <div class="form-group">
                  <label>Alamat Lengkap</label>
                  <textarea name="school_address" class="form-control"
                    rows="2">{{ $settings['school_address'] ?? '' }}</textarea>
                </div>

                <div class="form-group">
                  <label>No. Telepon</label>
                  <input type="text" name="school_phone" class="form-control"
                    value="{{ $settings['school_phone'] ?? '' }}">
                </div>

                <div class="form-group">
                  <label>Email Sekolah</label>
                  <input type="email" name="school_email" class="form-control"
                    value="{{ $settings['school_email'] ?? '' }}">
                </div>

                <div class="form-group">
                  <label>NPSN</label>
                  <input type="text" name="school_npsn" class="form-control" value="{{ $settings['school_npsn'] ?? '' }}">
                </div>

                <div class="form-group">
                  <label>Nama Kepala Sekolah</label>
                  <input type="text" name="school_headmaster_name" class="form-control"
                    value="{{ $settings['school_headmaster_name'] ?? '' }}">
                </div>

                <div class="form-group">
                  <label>NIP Kepala Sekolah</label>
                  <input type="text" name="school_headmaster_nip" class="form-control"
                    value="{{ $settings['school_headmaster_nip'] ?? '' }}">
                </div>

                <div class="text-right mt-3">
                  <button type="submit" class="btn btn-primary">Simpan Profil</button>
                </div>
              </form>
            </div>

            {{-- ================= DOCUMENT TAB ================= --}}
            <div class="tab-pane fade" id="doc">
              <form action="{{ route('adminlembaga.settings.update') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="callout callout-info">
                  Logo dan tanda tangan akan otomatis digunakan pada cetak dokumen.
                </div>

                <div class="row">
                  {{-- Logo Kabupaten --}}
                  <div class="col-md-6">
                    <div class="form-group">
                      <label>Logo Kabupaten (Kiri)</label>
                      <div class="custom-file">
                        <input type="file" class="custom-file-input" id="logo_kabupaten" name="logo_kabupaten"
                          accept="image/*">
                        <label class="custom-file-label">Pilih file...</label>
                      </div>
                      <div id="preview-container-logo_kabupaten" class="mt-3 text-center">
                        @if(isset($settings['logo_kabupaten']))
                          <img src="{{ asset($settings['logo_kabupaten']) }}"
                            class="img-preview border p-2 bg-light rounded" style="max-height:100px;">
                        @endif
                      </div>
                    </div>
                  </div>

                  {{-- Logo Sekolah --}}
                  <div class="col-md-6">
                    <div class="form-group">
                      <label>Logo Sekolah (Kanan)</label>
                      <div class="custom-file">
                        <input type="file" class="custom-file-input" id="school_logo" name="school_logo" accept="image/*">
                        <label class="custom-file-label">Pilih file...</label>
                      </div>
                      <div id="preview-container-school_logo" class="mt-3 text-center">
                        @if(isset($settings['school_logo']))
                          <img src="{{ asset($settings['school_logo']) }}" class="img-preview border p-2 bg-light rounded"
                            style="max-height:100px;">
                        @endif
                      </div>
                    </div>
                  </div>
                </div>

                <hr>

                <div class="row">
                  {{-- Signature --}}
                  <div class="col-md-6">
                    <div class="form-group">
                      <label>Scan Tanda Tangan</label>
                      <div class="custom-file">
                        <input type="file" class="custom-file-input" id="school_signature" name="school_signature"
                          accept="image/*">
                        <label class="custom-file-label">Pilih file...</label>
                      </div>
                      <div id="preview-container-school_signature" class="mt-3 text-center">
                        @if(isset($settings['school_signature']))
                          <img src="{{ asset($settings['school_signature']) }}"
                            class="img-preview border p-2 bg-light rounded" style="max-height:100px;">
                        @endif
                      </div>
                    </div>
                  </div>

                  {{-- Stamp --}}
                  <div class="col-md-6">
                    <div class="form-group">
                      <label>Scan Stempel</label>
                      <div class="custom-file">
                        <input type="file" class="custom-file-input" id="school_stamp" name="school_stamp"
                          accept="image/*">
                        <label class="custom-file-label">Pilih file...</label>
                      </div>
                      <div id="preview-container-school_stamp" class="mt-3 text-center">
                        @if(isset($settings['school_stamp']))
                          <img src="{{ asset($settings['school_stamp']) }}" class="img-preview border p-2 bg-light rounded"
                            style="max-height:100px;">
                        @endif
                      </div>
                    </div>
                  </div>
                </div>

                <div class="text-right mt-3">
                  <button type="submit" class="btn btn-primary">Simpan Dokumen</button>
                </div>
              </form>
            </div>

            {{-- ================= ACCOUNT INFO TAB ================= --}}
            <div class="tab-pane fade" id="account">
              <div class="row">
                <div class="col-md-8">
                  <div class="callout callout-warning">
                    <i class="fas fa-info-circle"></i> Informasi ini bersifat <strong>read-only</strong>. Hubungi
                    Super Admin untuk perubahan data sensitif.
                  </div>
                  <table class="table table-bordered table-striped">
                    <tbody>
                      <tr>
                        <th style="width: 30%;">ID Sekolah (Tenant ID)</th>
                        <td><code>{{ $tenant->id }}</code></td>
                      </tr>
                      <tr>
                        <th>Domain Akses</th>
                        <td>
                          @foreach($tenant->domains as $domain)
                            <a href="http://{{ $domain->domain }}" target="_blank">{{ $domain->domain }}</a><br>
                          @endforeach
                        </td>
                      </tr>
                      <tr>
                        <th>Status Akun</th>
                        <td>
                          @if($tenant->status_aktif)
                            <span class="badge badge-success">AKTIF</span>
                          @else
                            <span class="badge badge-danger">NON-AKTIF</span>
                          @endif
                        </td>
                      </tr>
                      <tr>
                        <th>Paket Langganan</th>
                        <td>
                          <span class="badge badge-info">{{ $tenant->subscription_plan ?? 'Free / Basic' }}</span>
                        </td>
                      </tr>
                      <tr>
                        <th>Batas Penyimpanan</th>
                        <td>
                          {{ $tenant->storage_limit ? number_format($tenant->storage_limit / 1024, 2) . ' GB' : 'Unlimited' }}
                        </td>
                      </tr>
                      <tr>
                        <th>Tanggal Bergabung</th>
                        <td>{{ $tenant->created_at->translatedFormat('d F Y') }}</td>
                      </tr>
                    </tbody>
                  </table>
                </div>
                <div class="col-md-4">
                  <div class="card card-outline card-warning">
                    <div class="card-header">
                      <h3 class="card-title">Bantuan</h3>
                    </div>
                    <div class="card-body">
                      <p>Jika Anda mengalami kendala teknis atau ingin melakukan upgrade paket layanan, silakan hubungi
                        tim support kami.</p>
                      @if($supportPhone)
                        <a href="https://wa.me/{{ preg_replace('/[^0-9]/', '', $supportPhone) }}" target="_blank"
                          class="btn btn-success btn-block">
                          <i class="fab fa-whatsapp"></i> Hubungi Support
                        </a>
                      @else
                        <div class="alert alert-secondary">
                          <small>Kontak support belum dikonfigurasi oleh Super Admin.</small>
                        </div>
                      @endif
                    </div>
                  </div>
                </div>
              </div>
            </div>

          </div>
        </div>
        <!-- /.card-body -->
      </div>
      <!-- /.card -->
    </div>
  </div>

  {{-- ================= SCRIPT PREVIEW ================= --}}
  @push('scripts')
    <!-- bs-custom-file-input -->
    <script src="https://cdn.jsdelivr.net/npm/bs-custom-file-input/dist/bs-custom-file-input.min.js"></script>

    <script>
      $(function () {

        if (typeof bsCustomFileInput !== 'undefined') {
          bsCustomFileInput.init();
        }

        function previewImage(input, containerId) {

          if (!input.files || !input.files[0]) return;

          const file = input.files[0];

          // Validasi ringan agar hanya gambar
          if (!file.type.startsWith('image/')) {
            alert('File harus berupa gambar!');
            input.value = '';
            return;
          }

          const reader = new FileReader();

          reader.onload = function (e) {

            const html = `
                                    <div class="position-relative d-inline-block">
                                      <img src="${e.target.result}"
                                           class="border p-2 bg-light rounded shadow-sm"
                                           style="max-height:100px; object-fit:contain;">
                                      <small class="d-block text-muted mt-1">${file.name}</small>
                                    </div>
                                  `;

            $('#' + containerId).html(html);
          };

          reader.readAsDataURL(file);
        }

        $('#logo_kabupaten').on('change', function () {
          previewImage(this, 'preview-container-logo_kabupaten');
        });

        $('#school_logo').on('change', function () {
          previewImage(this, 'preview-container-school_logo');
        });

        $('#school_signature').on('change', function () {
          previewImage(this, 'preview-container-school_signature');
        });

        $('#school_stamp').on('change', function () {
          previewImage(this, 'preview-container-school_stamp');
        });

      });
    </script>
  @endpush

@endsection