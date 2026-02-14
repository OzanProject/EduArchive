@extends('backend.layouts.app')

@section('title', 'Tambah Kelas')
@section('page_title', 'Tambah Data Kelas')

@section('breadcrumb')
  <li class="breadcrumb-item"><a href="{{ route('adminlembaga.dashboard') }}">Dashboard</a></li>
  <li class="breadcrumb-item"><a href="{{ route('adminlembaga.classrooms.index') }}">Data Kelas</a></li>
  <li class="breadcrumb-item active">Tambah Baru</li>
@endsection

@section('content')
  <div class="row">
    <div class="col-md-12">
      <div class="card card-primary">
        <div class="card-header">
          <h3 class="card-title">Form Tambah Kelas</h3>
        </div>
        <form action="{{ route('adminlembaga.classrooms.store') }}" method="POST">
          @csrf
          <div class="card-body">
            <div class="row">
              <div class="col-md-6">
                <div class="form-group">
                  <label>Nama Kelas <span class="text-danger">*</span></label>
                  <input type="text" name="nama_kelas" class="form-control @error('nama_kelas') is-invalid @enderror"
                    value="{{ old('nama_kelas') }}" placeholder="Contoh: VII A, X IPA 1">
                  @error('nama_kelas') <span class="invalid-feedback">{{ $message }}</span> @enderror
                </div>
              </div>
              <div class="col-md-6">
                <div class="form-group">
                  <label>Tahun Ajaran <span class="text-danger">*</span></label>
                  <input type="text" name="tahun_ajaran" class="form-control @error('tahun_ajaran') is-invalid @enderror"
                    value="{{ old('tahun_ajaran', date('Y') . '/' . (date('Y') + 1)) }}" placeholder="Contoh: 2025/2026">
                  @error('tahun_ajaran') <span class="invalid-feedback">{{ $message }}</span> @enderror
                </div>
              </div>
            </div>

            <div class="row">
              <div class="col-md-4">
                <div class="form-group">
                  <label>Tingkat</label>
                  <select name="tingkat" class="form-control">
                    <option value="">-- Pilih --</option>
                    @foreach(['1', '2', '3', '4', '5', '6', '7', '8', '9', '10', '11', '12'] as $level)
                      <option value="{{ $level }}" {{ old('tingkat') == $level ? 'selected' : '' }}>{{ $level }}</option>
                    @endforeach
                  </select>
                </div>
              </div>
              <div class="col-md-4">
                <div class="form-group">
                  <label>Jurusan <small class="text-muted">(Jika ada)</small></label>
                  <input type="text" name="jurusan" class="form-control" value="{{ old('jurusan') }}"
                    placeholder="IPA / IPS / Kejuruan">
                </div>
              </div>
              <div class="col-md-4">
                <div class="form-group">
                  <label>Wali Kelas</label>
                  <select name="wali_kelas_id" class="form-control select2">
                    <option value="">-- Pilih Guru --</option>
                    @foreach($teachers as $teacher)
                      <option value="{{ $teacher->id }}" {{ old('wali_kelas_id') == $teacher->id ? 'selected' : '' }}>
                        {{ $teacher->nama_lengkap }} ({{ $teacher->nip ?? '-' }})
                      </option>
                    @endforeach
                  </select>
                </div>
              </div>
            </div>

          </div>
          <div class="card-footer">
            <button type="submit" class="btn btn-primary">Simpan</button>
            <a href="{{ route('adminlembaga.classrooms.index') }}" class="btn btn-default">Kembali</a>
          </div>
        </form>
      </div>
    </div>
  </div>
@endsection