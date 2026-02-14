@extends('backend.layouts.app')

@section('title', 'Edit Jenis Dokumen')
@section('page_title', 'Edit Jenis Dokumen')

@section('breadcrumb')
  <li class="breadcrumb-item"><a href="{{ route('superadmin.dashboard') }}">Dashboard</a></li>
  <li class="breadcrumb-item"><a href="{{ route('superadmin.document-types.index') }}">Jenis Dokumen</a></li>
  <li class="breadcrumb-item active">Edit</li>
@endsection

@section('content')
  <div class="row">
    <div class="col-md-6">
      <div class="card card-primary">
        <div class="card-header">
          <h3 class="card-title">Form Edit Dokumen</h3>
        </div>
        <form action="{{ route('superadmin.document-types.update', $type->id) }}" method="POST">
          @csrf
          @method('PUT')
          <div class="card-body">
            <div class="form-group">
              <label for="name">Nama Dokumen</label>
              <input type="text" name="name" id="name" class="form-control" value="{{ old('name', $type->name) }}"
                required>
              @error('name') <span class="text-danger small">{{ $message }}</span> @enderror
            </div>

            <div class="form-group">
              <label for="code">Kode (Unik)</label>
              <input type="text" name="code" id="code" class="form-control" value="{{ old('code', $type->code) }}"
                required>
              @error('code') <span class="text-danger small">{{ $message }}</span> @enderror
            </div>

            <div class="form-group">
              <label for="description">Deskripsi</label>
              <textarea name="description" id="description" class="form-control"
                rows="3">{{ old('description', $type->description) }}</textarea>
            </div>

            <div class="form-group">
              <div class="custom-control custom-checkbox">
                <input class="custom-control-input" type="checkbox" id="is_required" name="is_required" {{ old('is_required', $type->is_required) ? 'checked' : '' }}>
                <label for="is_required" class="custom-control-label">Wajib Diupload?</label>
                <div class="text-muted small">Centang jika dokumen ini wajib dimiliki oleh setiap siswa.</div>
              </div>
            </div>

            <div class="form-group">
              <div class="custom-control custom-checkbox">
                <input class="custom-control-input" type="checkbox" id="is_active" name="is_active" {{ old('is_active', $type->is_active) ? 'checked' : '' }}>
                <label for="is_active" class="custom-control-label">Aktif?</label>
                <div class="text-muted small">Uncheck untuk menyembunyikan dokumen ini dari pilihan upload.</div>
              </div>
            </div>
          </div>

          <div class="card-footer">
            <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
            <a href="{{ route('superadmin.document-types.index') }}" class="btn btn-default">Batal</a>
          </div>
        </form>
      </div>
    </div>
  </div>
@endsection