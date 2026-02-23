@php
  $prefix = request()->routeIs('operator.*') ? 'operator.' : 'adminlembaga.';
@endphp
@extends('backend.layouts.app')

@section('title', 'Edit Dokumen')
@section('page_title', 'Edit Dokumen Siswa')

@section('breadcrumb')
  <li class="breadcrumb-item"><a href="{{ route($prefix . 'dashboard') }}">Dashboard</a></li>
  <li class="breadcrumb-item"><a href="{{ route($prefix . 'documents.index') }}">Dokumen Siswa</a></li>
  <li class="breadcrumb-item active">Edit Dokumen</li>
@endsection

@section('content')
  <div class="row">
    <div class="col-md-12">
      <div class="card card-warning">
        <div class="card-header">
          <h3 class="card-title">Form Edit Dokumen</h3>
        </div>
        <form action="{{ route($prefix . 'documents.update', $document->id) }}" method="POST"
          enctype="multipart/form-data">
          @csrf
          @method('PUT')
          <div class="card-body">
            <div class="row">
              <div class="col-md-6">
                <div class="form-group">
                  <label>Nama Siswa</label>
                  <input type="text" class="form-control"
                    value="{{ $document->student ? $document->student->nama : 'Siswa Dihapus' }}" readonly>
                </div>
              </div>
              <div class="col-md-6">
                <div class="form-group">
                  <label>Jenis Dokumen <span class="text-danger">*</span></label>
                  <select name="document_type" class="form-control @error('document_type') is-invalid @enderror">
                    <option value="">-- Pilih Jenis --</option>
                    @foreach($documentTypes as $type)
                      <option value="{{ $type->name }}" {{ old('document_type', $document->document_type) == $type->name ? 'selected' : '' }}>
                        {{ $type->name }} {{ $type->is_required ? '(Wajib)' : '' }}
                      </option>
                    @endforeach
                  </select>
                  @error('document_type') <span class="invalid-feedback">{{ $message }}</span> @enderror
                </div>
              </div>
            </div>

            <div class="form-group">
              <label>File Dokumen (Kosongkan jika tidak ingin mengubah file)</label>
              <div class="input-group">
                <div class="custom-file">
                  <input type="file" class="custom-file-input" id="file_path" name="file_path">
                  <label class="custom-file-label" for="file_path">Pilih file baru (PDF/JPG/PNG)</label>
                </div>
              </div>
              <small class="text-muted">Format: PDF, JPG, JPEG, PNG. Max: 50MB.</small>
              @error('file_path') <span class="d-block text-danger mt-1">{{ $message }}</span> @enderror
              <div class="mt-2">
                <strong>File saat ini:</strong>
                <a href="{{ route($prefix . 'documents.show', $document->id) }}" target="_blank">
                  <i class="fas fa-file-alt"></i> Lihat File ({{ number_format($document->file_size / 1024, 2) }} KB)
                </a>
              </div>
            </div>

            <div class="form-group">
              <label>Keterangan <small class="text-muted">(Opsional)</small></label>
              <textarea name="keterangan" class="form-control"
                rows="3">{{ old('keterangan', $document->keterangan) }}</textarea>
            </div>

          </div>
          <div class="card-footer">
            <button type="submit" class="btn btn-warning">Simpan Perubahan</button>
            <a href="{{ route($prefix . 'documents.index') }}" class="btn btn-default">Kembali</a>
          </div>
        </form>
      </div>
    </div>
  </div>
@endsection

@push('scripts')
  <script src="{{ asset('adminlte3/plugins/bs-custom-file-input/bs-custom-file-input.min.js') }}"></script>
  <script>
    $(function () {
      bsCustomFileInput.init();
    });
  </script>
@endpush