@extends('backend.layouts.app')

@section('title', 'WhatsApp Gateway')

@section('content')
  <section class="content-header">
    <div class="container-fluid">
      <div class="row mb-2">
        <div class="col-sm-6">
          <h1>WhatsApp Gateway</h1>
        </div>
        <div class="col-sm-6">
          <ol class="breadcrumb float-sm-right">
            <li class="breadcrumb-item"><a href="{{ route('superadmin.dashboard') }}">Dashboard</a></li>
            <li class="breadcrumb-item active">WhatsApp Settings</li>
          </ol>
        </div>
      </div>
    </div>
  </section>

  <section class="content">
    <div class="container-fluid">
      <div class="row">
        <div class="col-md-12">
          <div class="card card-outline card-success">
            <form action="{{ route('superadmin.settings.update') }}" method="POST">
              @csrf
              <div class="card-body">
                <div class="callout callout-success">
                  <h5><i class="fab fa-whatsapp"></i> Info</h5>
                  Konfigurasi ini digunakan untuk mengirim pesan OTP dan notifikasi lainnya melalui WhatsApp.
                </div>

                <div class="form-group">
                  <label>Provider WhatsApp</label>
                  <select class="form-control" name="wa_provider" id="wa_provider">
                    <option value="log" {{ ($settings['wa_provider'] ?? 'log') == 'log' ? 'selected' : '' }}>LOG
                      (Development / Testing Only)</option>
                    <option value="fonnte" {{ ($settings['wa_provider'] ?? '') == 'fonnte' ? 'selected' : '' }}>Fonnte
                      (Recommended)</option>
                    <option value="wablas" {{ ($settings['wa_provider'] ?? '') == 'wablas' ? 'selected' : '' }}>Wablas
                    </option>
                    <option value="twilio" {{ ($settings['wa_provider'] ?? '') == 'twilio' ? 'selected' : '' }}>Twilio
                    </option>
                  </select>
                  <small class="text-muted">Pilih "LOG" jika belum memiliki akun provider. Kode OTP akan muncul di file
                    log aplikasi (`storage/logs/laravel.log`).</small>
                </div>

                <div id="fonnte_settings" class="provider-settings" style="display: none;">
                  <h5 class="text-success mt-4 mb-3 border-bottom pb-2">Konfigurasi Fonnte</h5>
                  <div class="form-group">
                    <label>API Token</label>
                    <input type="text" name="wa_api_key" class="form-control" value="{{ $settings['wa_api_key'] ?? '' }}"
                      placeholder="Masukkan Token Fonnte Anda">
                    <small class="text-muted">Dapatkan token di <a href="https://fonnte.com"
                        target="_blank">fonnte.com</a></small>
                  </div>
                </div>

                <div id="wablas_settings" class="provider-settings" style="display: none;">
                  <h5 class="text-success mt-4 mb-3 border-bottom pb-2">Konfigurasi Wablas</h5>
                  <div class="form-group">
                    <label>API Token</label>
                    <input type="text" name="wa_wablas_token" class="form-control"
                      value="{{ $settings['wa_wablas_token'] ?? '' }}">
                  </div>
                  <div class="form-group">
                    <label>Server Domain</label>
                    <input type="text" name="wa_wablas_domain" class="form-control"
                      value="{{ $settings['wa_wablas_domain'] ?? '' }}" placeholder="https://solo.wablas.com">
                  </div>
                </div>

                <div id="twilio_settings" class="provider-settings" style="display: none;">
                  <h5 class="text-success mt-4 mb-3 border-bottom pb-2">Konfigurasi Twilio</h5>
                  <div class="form-group">
                    <label>Account SID</label>
                    <input type="text" name="wa_twilio_sid" class="form-control"
                      value="{{ $settings['wa_twilio_sid'] ?? '' }}">
                  </div>
                  <div class="form-group">
                    <label>Auth Token</label>
                    <input type="text" name="wa_twilio_token" class="form-control"
                      value="{{ $settings['wa_twilio_token'] ?? '' }}">
                  </div>
                  <div class="form-group">
                    <label>WhatsApp Number (From)</label>
                    <input type="text" name="wa_twilio_from" class="form-control"
                      value="{{ $settings['wa_twilio_from'] ?? '' }}" placeholder="+14155238886">
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
        function toggleSettings() {
          $('.provider-settings').hide();
          const provider = $('#wa_provider').val();
          if (provider === 'fonnte') $('#fonnte_settings').show();
          if (provider === 'wablas') $('#wablas_settings').show();
          if (provider === 'twilio') $('#twilio_settings').show();
        }

        $('#wa_provider').change(toggleSettings);
        toggleSettings(); // Initial call
      });
    </script>
  @endpush
@endsection