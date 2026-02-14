@extends('backend.layouts.app')

@section('title', 'Tambah Halaman')

@section('content')
  <section class="content-header">
    <div class="container-fluid">
      <div class="row mb-2">
        <div class="col-sm-6">
          <h1>Tambah Halaman</h1>
        </div>
        <div class="col-sm-6">
          <ol class="breadcrumb float-sm-right">
            <li class="breadcrumb-item"><a href="{{ route('superadmin.dashboard') }}">Dashboard</a></li>
            <li class="breadcrumb-item"><a href="{{ route('superadmin.pages.index') }}">Halaman</a></li>
            <li class="breadcrumb-item active">Tambah</li>
          </ol>
        </div>
      </div>
    </div>
  </section>

  <section class="content">
    <div class="container-fluid">
      <div class="row">
        <div class="col-12">
          <div class="card card-outline card-primary">
            <form action="{{ route('superadmin.pages.store') }}" method="POST">
              @csrf
              <div class="card-body">
                <div class="form-group">
                  <label>Judul Halaman</label>
                  <input type="text" name="title" class="form-control" placeholder="Contoh: Tentang Kami" required>
                </div>
                <div class="form-group">
                  <label>Konten</label>
                  <textarea name="content" class="form-control" id="summernote" rows="10"
                    placeholder="Tulis konten halaman di sini..."></textarea>
                </div>
                <div class="form-group">
                  <div class="custom-control custom-switch">
                    <input type="checkbox" class="custom-control-input" id="is_published" name="is_published" checked>
                    <label class="custom-control-label" for="is_published">Publish Halaman Ini</label>
                  </div>
                </div>
              </div>
              <div class="card-footer">
                <a href="{{ route('superadmin.pages.index') }}" class="btn btn-default">Kembali</a>
                <button type="submit" class="btn btn-primary float-right">Simpan</button>
              </div>
            </form>
          </div>
        </div>
      </div>
    </div>
  </section>
@endsection

@push('styles')
  <link rel="stylesheet" href="{{ asset('adminlte3/plugins/summernote/summernote-bs4.min.css') }}">
@endpush

@push('scripts')
  <script src="{{ asset('adminlte3/plugins/summernote/summernote-bs4.min.js') }}"></script>
  <script>
    $(function () {
      $('#summernote').summernote({
        height: 300
      });
    })
  </script>
@endpush