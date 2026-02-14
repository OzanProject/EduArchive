@extends('backend.layouts.app')

@section('title', 'Email Server (SMTP)')

@section('content')
  <section class="content-header">
    <div class="container-fluid">
      <div class="row mb-2">
        <div class="col-sm-6">
          <h1>Email Server (SMTP)</h1>
        </div>
        <div class="col-sm-6">
          <ol class="breadcrumb float-sm-right">
            <li class="breadcrumb-item"><a href="{{ route('superadmin.dashboard') }}">Dashboard</a></li>
            <li class="breadcrumb-item active">SMTP Settings</li>
          </ol>
        </div>
      </div>
    </div>
  </section>

  <section class="content">
    <div class="container-fluid">
      <div class="row">
        <div class="col-md-12">
          <div class="card card-outline card-danger">
            <form action="{{ route('superadmin.settings.update') }}" method="POST">
              @csrf
              <div class="card-body">
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
              <div class="card-footer">
                <button type="submit" class="btn btn-primary">Simpan Pengaturan</button>
              </div>
            </form>
          </div>
        </div>
      </div>
    </div>
  </section>
@endsection