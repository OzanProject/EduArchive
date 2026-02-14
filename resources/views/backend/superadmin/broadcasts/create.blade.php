@extends('backend.layouts.app')

@section('title', 'Buat Broadcast')
@section('page_title', 'Buat Pengumuman Baru')

@section('breadcrumb')
  <li class="breadcrumb-item"><a href="{{ route('superadmin.dashboard') }}">Home</a></li>
  <li class="breadcrumb-item"><a href="{{ route('superadmin.broadcasts.index') }}">Broadcast</a></li>
  <li class="breadcrumb-item active">Buat Baru</li>
@endsection

@section('content')
  <div class="row">
    <div class="col-md-12">
      <div class="card card-primary">
        <div class="card-header">
          <h3 class="card-title">Form Pengumuman</h3>
        </div>
        <form action="{{ route('superadmin.broadcasts.store') }}" method="POST">
          @csrf
          <div class="card-body">
            <div class="form-group">
              <label for="title">Judul Pengumuman</label>
              <input type="text" name="title" class="form-control @error('title') is-invalid @enderror" id="title"
                placeholder="Contoh: Maintenance Server Hari Minggu" value="{{ old('title') }}" required>
              @error('title')
                <span class="invalid-feedback">{{ $message }}</span>
              @enderror
            </div>

            <div class="form-group">
              <label for="type">Tipe Notifikasi</label>
              <select name="type" class="form-control" id="type">
                <option value="info">Info (Biru)</option>
                <option value="warning">Peringatan (Kuning)</option>
                <option value="danger">Penting / Bahaya (Merah)</option>
                <option value="success">Sukses (Hijau)</option>
              </select>
            </div>

            <div class="form-group">
              <label for="content">Isi Pengumuman</label>
              <textarea name="content" class="form-control @error('content') is-invalid @enderror" id="content" rows="5"
                placeholder="Tulis isi pengumuman di sini..." required>{{ old('content') }}</textarea>
              @error('content')
                <span class="invalid-feedback">{{ $message }}</span>
              @enderror
            </div>

            <div class="form-check">
              <input type="checkbox" class="form-check-input" id="is_active" name="is_active" checked>
              <label class="form-check-label" for="is_active">Langsung Aktifkan?</label>
            </div>
          </div>

          <div class="card-footer">
            <button type="submit" class="btn btn-primary">Kirim Broadcast</button>
            <a href="{{ route('superadmin.broadcasts.index') }}" class="btn btn-default">Batal</a>
          </div>
        </form>
      </div>
    </div>
  </div>
@endsection