@extends('backend.layouts.app')

@section('title', 'Tambah Guru & Tendik')
@section('page_title', 'Tambah Data Guru & Tendik')

@section('breadcrumb')
  <li class="breadcrumb-item"><a href="{{ route('adminlembaga.dashboard') }}">Dashboard</a></li>
  <li class="breadcrumb-item"><a href="{{ route('adminlembaga.teachers.index') }}">Guru & Tendik</a></li>
  <li class="breadcrumb-item active">Tambah Baru</li>
@endsection

@section('content')
  <div class="row">
    <div class="col-md-12">
      <div class="card card-primary">
        <div class="card-header">
          <h3 class="card-title">Form Tambah Data</h3>
        </div>
        <form action="{{ route('adminlembaga.teachers.store') }}" method="POST" enctype="multipart/form-data">
          @csrf
          <div class="card-body">
            <div class="row">
              <div class="col-md-6">
                <div class="form-group">
                  <label>Nama Lengkap <span class="text-danger">*</span></label>
                  <input type="text" name="nama_lengkap" class="form-control @error('nama_lengkap') is-invalid @enderror"
                    value="{{ old('nama_lengkap') }}" placeholder="Tanpa gelar">
                  @error('nama_lengkap') <span class="invalid-feedback">{{ $message }}</span> @enderror
                </div>
              </div>
              <div class="col-md-3">
                <div class="form-group">
                  <label>Gelar Depan</label>
                  <input type="text" name="gelar_depan" class="form-control" value="{{ old('gelar_depan') }}"
                    placeholder="Sdr. / Dr.">
                </div>
              </div>
              <div class="col-md-3">
                <div class="form-group">
                  <label>Gelar Belakang</label>
                  <input type="text" name="gelar_belakang" class="form-control" value="{{ old('gelar_belakang') }}"
                    placeholder="S.Pd., M.Si.">
                </div>
              </div>
            </div>

            <div class="row">
              <div class="col-md-6">
                <div class="form-group">
                  <label>NIP</label>
                  <input type="text" name="nip" class="form-control @error('nip') is-invalid @enderror"
                    value="{{ old('nip') }}" placeholder="Nomor Induk Pegawai">
                  @error('nip') <span class="invalid-feedback">{{ $message }}</span> @enderror
                </div>
              </div>
              <div class="col-md-6">
                <div class="form-group">
                  <label>NUPTK</label>
                  <input type="text" name="nuptk" class="form-control @error('nuptk') is-invalid @enderror"
                    value="{{ old('nuptk') }}" placeholder="Nomor Unik Pendidik dan Tenaga Kependidikan">
                  @error('nuptk') <span class="invalid-feedback">{{ $message }}</span> @enderror
                </div>
              </div>
            </div>

            <div class="row">
              <div class="col-md-6">
                <div class="form-group">
                  <label>Tempat Lahir</label>
                  <input type="text" name="tempat_lahir" class="form-control" value="{{ old('tempat_lahir') }}">
                </div>
              </div>
              <div class="col-md-6">
                <div class="form-group">
                  <label>Tanggal Lahir</label>
                  <input type="date" name="tanggal_lahir" class="form-control" value="{{ old('tanggal_lahir') }}">
                </div>
              </div>
            </div>

            <div class="row">
              <div class="col-md-6">
                <div class="form-group">
                  <label>Jenis Kelamin <span class="text-danger">*</span></label>
                  <select name="jenis_kelamin" class="form-control @error('jenis_kelamin') is-invalid @enderror">
                    <option value="">-- Pilih --</option>
                    <option value="L" {{ old('jenis_kelamin') == 'L' ? 'selected' : '' }}>Laki-laki</option>
                    <option value="P" {{ old('jenis_kelamin') == 'P' ? 'selected' : '' }}>Perempuan</option>
                  </select>
                  @error('jenis_kelamin') <span class="invalid-feedback">{{ $message }}</span> @enderror
                </div>
              </div>
              <div class="col-md-6">
                <div class="form-group">
                  <label>Status Kepegawaian <span class="text-danger">*</span></label>
                  <select name="status_kepegawaian"
                    class="form-control @error('status_kepegawaian') is-invalid @enderror">
                    <option value="Lainnya" {{ old('status_kepegawaian') == 'Lainnya' ? 'selected' : '' }}>Lainnya</option>
                    <option value="PNS" {{ old('status_kepegawaian') == 'PNS' ? 'selected' : '' }}>PNS</option>
                    <option value="PPPK" {{ old('status_kepegawaian') == 'PPPK' ? 'selected' : '' }}>PPPK</option>
                    <option value="GTY" {{ old('status_kepegawaian') == 'GTY' ? 'selected' : '' }}>GTY (Guru Tetap Yayasan)
                    </option>
                    <option value="GTT" {{ old('status_kepegawaian') == 'GTT' ? 'selected' : '' }}>GTT (Guru Tidak Tetap)
                    </option>
                    <option value="Honor Daerah" {{ old('status_kepegawaian') == 'Honor Daerah' ? 'selected' : '' }}>Honor
                      Daerah</option>
                  </select>
                  @error('status_kepegawaian') <span class="invalid-feedback">{{ $message }}</span> @enderror
                </div>
              </div>
            </div>

            <div class="row">
              <div class="col-md-6">
                <div class="form-group">
                  <label>Email</label>
                  <input type="email" name="email" class="form-control @error('email') is-invalid @enderror"
                    value="{{ old('email') }}">
                  @error('email') <span class="invalid-feedback">{{ $message }}</span> @enderror
                </div>
              </div>
              <div class="col-md-6">
                <div class="form-group">
                  <label>No HP / WA</label>
                  <input type="text" name="no_hp" class="form-control" value="{{ old('no_hp') }}">
                </div>
              </div>
            </div>

            <div class="form-group">
              <label>Alamat Lengkap</label>
              <textarea name="alamat" class="form-control" rows="3">{{ old('alamat') }}</textarea>
            </div>

            <div class="form-group">
              <label>Foto Profil</label>
              <div class="input-group">
                <div class="custom-file">
                  <input type="file" class="custom-file-input" id="foto" name="foto">
                  <label class="custom-file-label" for="foto">Pilih file</label>
                </div>
              </div>
              <small class="text-muted">Format: JPG, JPEG, PNG. Max: 2MB.</small>
            </div>

          </div>
          <div class="card-footer">
            <button type="submit" class="btn btn-primary">Simpan</button>
            <a href="{{ route('adminlembaga.teachers.index') }}" class="btn btn-default">Kembali</a>
          </div>
        </form>
      </div>
    </div>
  </div>
@endsection