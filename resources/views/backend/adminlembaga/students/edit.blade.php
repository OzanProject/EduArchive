@php
  $prefix = request()->routeIs('operator.*') ? 'operator.' : 'adminlembaga.';
@endphp
@extends('backend.layouts.app')

@section('title', 'Edit Siswa')
@section('page_title', 'Edit Data Siswa')

@section('breadcrumb')
  <li class="breadcrumb-item"><a href="{{ route($prefix . 'dashboard') }}">Dashboard</a></li>
  <li class="breadcrumb-item"><a href="{{ route($prefix . 'students.index') }}">Data Siswa</a></li>
  <li class="breadcrumb-item active">Edit Data</li>
@endsection

@section('content')
  <form action="{{ route($prefix . 'students.update', $student->id) }}" method="POST" enctype="multipart/form-data">
    @csrf
    @method('PUT')
    <div class="row">
      <!-- Left Column: Personal & Academic Info -->
      <div class="col-md-8">
        <div class="card card-warning card-outline">
          <div class="card-header">
            <h3 class="card-title"><i class="fas fa-user-edit mr-1"></i> Data Pribadi & Akademik</h3>
          </div>
          <div class="card-body">
            <div class="row">
              <div class="col-md-6">
                <div class="form-group">
                  <label>Nama Lengkap <span class="text-danger">*</span></label>
                  <div class="input-group">
                    <div class="input-group-prepend">
                      <span class="input-group-text"><i class="fas fa-user"></i></span>
                    </div>
                    <input type="text" name="nama" class="form-control @error('nama') is-invalid @enderror"
                      value="{{ old('nama', $student->nama) }}" placeholder="Nama Lengkap Siswa" required>
                  </div>
                  @error('nama') <span class="invalid-feedback d-block">{{ $message }}</span> @enderror
                </div>
              </div>
              <div class="col-md-6">
                <div class="form-group">
                  <label>Jenis Kelamin <span class="text-danger">*</span></label>
                  <select name="gender" class="form-control @error('gender') is-invalid @enderror" required>
                    <option value="">-- Pilih Gender --</option>
                    <option value="L" {{ old('gender', $student->gender) == 'L' ? 'selected' : '' }}>Laki-laki</option>
                    <option value="P" {{ old('gender', $student->gender) == 'P' ? 'selected' : '' }}>Perempuan</option>
                  </select>
                  @error('gender') <span class="invalid-feedback d-block">{{ $message }}</span> @enderror
                </div>
              </div>
            </div>

            <div class="row">
              <div class="col-md-6">
                <div class="form-group">
                  <label>NISN</label>
                  <div class="input-group">
                    <div class="input-group-prepend">
                      <span class="input-group-text"><i class="fas fa-id-card"></i></span>
                    </div>
                    <input type="text" name="nisn" class="form-control @error('nisn') is-invalid @enderror"
                      value="{{ old('nisn', $student->nisn) }}" placeholder="Nomor Induk Siswa Nasional">
                  </div>
                  @error('nisn') <span class="invalid-feedback d-block">{{ $message }}</span> @enderror
                </div>
              </div>
              <div class="col-md-6">
                <div class="form-group">
                  <label>NIK</label>
                  <div class="input-group">
                    <div class="input-group-prepend">
                      <span class="input-group-text"><i class="fas fa-id-badge"></i></span>
                    </div>
                    <input type="text" name="nik" class="form-control @error('nik') is-invalid @enderror"
                      value="{{ old('nik', $student->nik) }}" placeholder="Nomor Induk Kependudukan">
                  </div>
                  @error('nik') <span class="invalid-feedback d-block">{{ $message }}</span> @enderror
                </div>
              </div>
            </div>

            <div class="row">
              <div class="col-md-6">
                <div class="form-group">
                  <label>Tempat Lahir</label>
                  <input type="text" name="birth_place" class="form-control"
                    value="{{ old('birth_place', $student->birth_place) }}">
                </div>
              </div>
              <div class="col-md-6">
                <div class="form-group">
                  <label>Tanggal Lahir</label>
                  <div class="input-group">
                    <div class="input-group-prepend">
                      <span class="input-group-text"><i class="fas fa-calendar"></i></span>
                    </div>
                    <input type="date" name="birth_date" class="form-control"
                      value="{{ old('birth_date', $student->birth_date ? $student->birth_date->format('Y-m-d') : '') }}">
                  </div>
                </div>
              </div>
            </div>

            <div class="row">
              <div class="col-md-6">
                <div class="form-group">
                  <label>Kelas</label>
                  <select name="classroom_id" class="form-control select2 @error('classroom_id') is-invalid @enderror"
                    style="width: 100%;">
                    <option value="">-- Pilih Kelas --</option>
                    @foreach($classrooms as $classroom)
                      <option value="{{ $classroom->id }}" {{ old('classroom_id', $student->classroom_id) == $classroom->id ? 'selected' : '' }}>
                        {{ $classroom->nama_kelas }} ({{ $classroom->tahun_ajaran }})
                      </option>
                    @endforeach
                  </select>
                  @error('classroom_id') <span class="invalid-feedback d-block">{{ $message }}</span> @enderror
                </div>
              </div>
              <div class="col-md-6">
                <div class="form-group">
                  <label>Tahun Masuk</label>
                  <input type="number" name="year_in" class="form-control"
                    value="{{ old('year_in', $student->year_in) }}">
                </div>
              </div>
            </div>

            <div class="row">
              <div class="col-md-6">
                <div class="form-group">
                  <label>Nama Orang Tua / Wali</label>
                  <input type="text" name="parent_name" class="form-control"
                    value="{{ old('parent_name', $student->parent_name) }}">
                </div>
              </div>
              <!-- Status Kelulusan (Only in Edit) -->
              <div class="col-md-6">
                <div class="form-group">
                  <label>Status Siswa</label>
                  <div class="input-group">
                    <div class="input-group-prepend">
                      <span class="input-group-text"><i class="fas fa-info-circle"></i></span>
                    </div>
                    <select name="status_kelulusan" class="form-control">
                      <option value="Aktif" {{ old('status_kelulusan', $student->status_kelulusan) == 'Aktif' ? 'selected' : '' }}>Aktif</option>
                      <option value="Lulus" {{ old('status_kelulusan', $student->status_kelulusan) == 'Lulus' ? 'selected' : '' }}>Lulus</option>
                      <option value="Pindah" {{ old('status_kelulusan', $student->status_kelulusan) == 'Pindah' ? 'selected' : '' }}>Pindah</option>
                      <option value="DO" {{ old('status_kelulusan', $student->status_kelulusan) == 'DO' ? 'selected' : '' }}>Putus Sekolah (DO)</option>
                    </select>
                  </div>
                </div>
              </div>
            </div>

            <div class="form-group">
              <label>Alamat Lengkap</label>
              <textarea name="address" class="form-control" rows="3">{{ old('address', $student->address) }}</textarea>
            </div>
          </div>
        </div>
      </div>

      <!-- Right Column: Photo & Actions -->
      <div class="col-md-4">
        <div class="card card-warning card-outline">
          <div class="card-header">
            <h3 class="card-title"><i class="fas fa-camera mr-1"></i> Foto Profil</h3>
          </div>
          <div class="card-body text-center">
            <div class="form-group">
              <div class="image-preview mb-3">
                @if($student->foto_profil)
                  <img src="{{ tenant_asset($student->foto_profil) }}" alt="Foto" class="img-thumbnail rounded-circle"
                    style="width: 150px; height: 150px; object-fit: cover;">
                @else
                  <img src="{{ asset('adminlte3/dist/img/user2-160x160.jpg') }}" alt="Preview"
                    class="img-thumbnail rounded-circle" style="width: 150px; height: 150px; object-fit: cover;">
                @endif
              </div>
              <div class="custom-file text-left">
                <input type="file" class="custom-file-input" id="foto_profil" name="foto_profil" accept="image/*">
                <label class="custom-file-label" for="foto_profil">Ganti Foto...</label>
              </div>
              <small class="text-muted d-block mt-2">Format: JPG, PNG. Max: 2MB.</small>
            </div>
          </div>
        </div>

        <div class="card">
          <div class="card-body">
            <button type="submit" class="btn btn-warning btn-block btn-lg"><i class="fas fa-save mr-1"></i> Update
              Data</button>
            <a href="{{ route($prefix . 'students.index', ['status' => $student->status_kelulusan]) }}"
              class="btn btn-default btn-block mt-2">Kembali</a>
          </div>
        </div>
      </div>
    </div>
  </form>
@endsection