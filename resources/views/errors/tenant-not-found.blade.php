@extends('errors::minimal')

@section('title', 'Sekolah Tidak Ditemukan')
@section('code', '404')
@section('message')
  <div class="error-content" style="max-width: 600px; margin: 0 auto; text-align: center;">
    <div style="font-size: 80px; color: #e74c3c; margin-bottom: 20px;">
      <i class="fas fa-school"></i>
    </div>
    <h2 style="color: #2c3e50; margin-bottom: 15px;">Sekolah Tidak Ditemukan</h2>
    <p style="color: #7f8c8d; font-size: 16px; margin-bottom: 10px;">
      Maaf, sekolah dengan ID <code
        style="background: #ecf0f1; padding: 3px 8px; border-radius: 3px; color: #e74c3c;">{{ $tenant_id }}</code> tidak
      ditemukan dalam sistem.
    </p>
    <p style="color: #95a5a6; font-size: 14px; margin-bottom: 30px;">
      Kemungkinan penyebab:
    </p>
    <ul style="text-align: left; display: inline-block; color: #7f8c8d;">
      <li>ID sekolah salah atau belum terdaftar</li>
      <li>Akun sekolah sudah tidak aktif</li>
      <li>Typo pada URL yang Anda akses</li>
    </ul>

    <div style="margin-top: 30px;">
      <a href="/" class="btn btn-primary"
        style="display: inline-block; padding: 12px 30px; background: #3498db; color: white; text-decoration: none; border-radius: 5px; font-weight: 600;">
        <i class="fas fa-home"></i> Kembali ke Beranda
      </a>
    </div>

    <p style="margin-top: 30px; color: #bdc3c7; font-size: 13px;">
      Jika Anda yakin URL sudah benar, silakan hubungi administrator sistem.
    </p>
  </div>
@endsection