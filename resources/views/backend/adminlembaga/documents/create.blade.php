@php
  $prefix = request()->routeIs('operator.*') ? 'operator.' : 'adminlembaga.';
@endphp
@extends('backend.layouts.app')

@section('title', 'Upload Dokumen')
@section('page_title', 'Upload Dokumen Siswa')

@section('breadcrumb')
  <li class="breadcrumb-item"><a href="{{ route($prefix . 'dashboard') }}">Dashboard</a></li>
  <li class="breadcrumb-item"><a href="{{ route($prefix . 'documents.index') }}">Dokumen Siswa</a></li>
  <li class="breadcrumb-item active">Upload Baru</li>
@endsection

@section('content')
  <div class="row">
    <div class="col-md-12">
      <div class="card card-primary">
        <div class="card-header">
          <h3 class="card-title">Form Upload Dokumen</h3>
        </div>
        <form action="{{ route($prefix . 'documents.store') }}" method="POST" enctype="multipart/form-data">
          @csrf
          <div class="card-body">
            <div class="row">
              <div class="col-md-6">
                <div class="form-group">
                  <label>Pilih Siswa <span class="text-danger">*</span></label>
                  <select name="student_id" class="form-control select2 @error('student_id') is-invalid @enderror">
                    <option value="">-- Pilih Siswa --</option>
                    @foreach($students as $student)
                      <option value="{{ $student->id }}" {{ old('student_id', $selectedStudentId) == $student->id ? 'selected' : '' }}>
                        {{ $student->nama }} ({{ $student->status_kelulusan }} -
                        {{ $student->classroom ? $student->classroom->nama_kelas : 'Tanpa Kelas' }})
                      </option>
                    @endforeach
                  </select>
                  @error('student_id') <span class="invalid-feedback">{{ $message }}</span> @enderror
                </div>
              </div>
              <div class="col-md-6">
                <div class="form-group">
                  <label>Jenis Dokumen <span class="text-danger">*</span></label>
                  <select name="document_type" class="form-control @error('document_type') is-invalid @enderror">
                    <option value="">-- Pilih Jenis --</option>
                    @foreach($documentTypes as $type)
                        <option value="{{ $type->name }}" {{ old('document_type', $selectedType) == $type->name ? 'selected' : '' }}>
                            {{ $type->name }} {{ $type->is_required ? '(Wajib)' : '' }}
                        </option>
                    @endforeach
                  </select>
                  @error('document_type') <span class="invalid-feedback">{{ $message }}</span> @enderror
                </div>
              </div>
            </div>

            <div class="form-group">
              <label>File Dokumen <span class="text-danger">*</span></label>
              <div class="input-group">
                <div class="custom-file">
                  <input type="file" class="custom-file-input" id="file_path" name="file_path">
                  <label class="custom-file-label" for="file_path">Pilih file (PDF/JPG/PNG)</label>
                </div>
              </div>
              <small class="text-muted">Format: PDF, JPG, JPEG, PNG. Max: 50MB.</small>
              @error('file_path') <span class="d-block text-danger mt-1">{{ $message }}</span> @enderror
            </div>

            <div class="form-group">
              <label>Keterangan <small class="text-muted">(Opsional)</small></label>
              <textarea name="keterangan" class="form-control" rows="3">{{ old('keterangan') }}</textarea>
            </div>

          </div>
          <div class="card-footer">
            <button type="submit" class="btn btn-primary">Upload</button>
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