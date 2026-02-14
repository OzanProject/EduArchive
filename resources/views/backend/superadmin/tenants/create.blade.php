@extends('backend.layouts.app')

@section('title', 'Tambah Sekolah')
@section('page_title', 'Tambah Sekolah Baru')

@section('breadcrumb')
  <li class="breadcrumb-item"><a href="{{ route('superadmin.dashboard') }}">Home</a></li>
  <li class="breadcrumb-item"><a href="{{ route('superadmin.tenants.index') }}">Sekolah</a></li>
  <li class="breadcrumb-item active">Tambah</li>
@endsection

@section('content')
  <div class="row">
    <div class="col-md-6">
      <div class="card card-primary">
        <div class="card-header">
          <h3 class="card-title">Form Data Sekolah</h3>
        </div>
        <!-- /.card-header -->
        <!-- form start -->
        <form action="{{ route('superadmin.tenants.store') }}" method="POST" enctype="multipart/form-data">
          @csrf
          <div class="card-body">
            <div class="form-group">
              <label for="npsn">NPSN</label>
              <input type="text" name="npsn" class="form-control @error('npsn') is-invalid @enderror" id="npsn"
                placeholder="Masukkan NPSN" value="{{ old('npsn') }}">
              @error('npsn') <span class="invalid-feedback">{{ $message }}</span> @enderror
            </div>

            <div class="form-group">
              <label for="nama_sekolah">Nama Sekolah</label>
              <input type="text" name="nama_sekolah" class="form-control @error('nama_sekolah') is-invalid @enderror"
                id="nama_sekolah" placeholder="Contoh: SMP Negeri 1 Jakarta" value="{{ old('nama_sekolah') }}">
              @error('nama_sekolah') <span class="invalid-feedback">{{ $message }}</span> @enderror
            </div>

            <div class="form-group">
              <label for="jenjang">Jenjang</label>
              <select name="jenjang" class="form-control @error('jenjang') is-invalid @enderror">
                <option value="">-- Pilih Jenjang --</option>
                @foreach($schoolLevels as $level)
                  <option value="{{ $level->name }}" {{ old('jenjang') == $level->name ? 'selected' : '' }}>
                    {{ $level->name }} - {{ $level->description }}
                  </option>
                @endforeach
              </select>
              @error('jenjang') <span class="invalid-feedback">{{ $message }}</span> @enderror
            </div>

            <div class="form-group">
              <label for="id">Kode Sekolah (ID)</label>
              <input type="text" name="id" class="form-control @error('id') is-invalid @enderror" id="id"
                placeholder="Contoh: smpn1" value="{{ old('id') }}">
              <small class="form-text text-muted">Digunakan sebagai identitas di URL (contoh:
                localhost:8000/<b>smpn1</b>). Tanpa spasi.</small>
              @error('id') <span class="invalid-feedback">{{ $message }}</span> @enderror
            </div>

            <div class="form-group">
              <label for="alamat">Alamat Lengkap</label>
              <textarea name="alamat" class="form-control" rows="3"
                placeholder="Alamat sekolah...">{{ old('alamat') }}</textarea>
            </div>

            <div class="row">
              <div class="col-md-6">
                <div class="form-group">
                  <label for="subscription_plan">Paket Langganan</label>
                  <select name="subscription_plan" class="form-control @error('subscription_plan') is-invalid @enderror">
                    <option value="Free" {{ old('subscription_plan') == 'Free' ? 'selected' : '' }}>Free</option>
                    <option value="Basic" {{ old('subscription_plan') == 'Basic' ? 'selected' : '' }}>Basic</option>
                    <option value="Premium" {{ old('subscription_plan') == 'Premium' ? 'selected' : '' }}>Premium</option>
                  </select>
                  @error('subscription_plan') <span class="invalid-feedback">{{ $message }}</span> @enderror
                </div>
              </div>
              <div class="col-md-6">
                <div class="form-group">
                  <label for="storage_limit">Batas Penyimpanan (MB)</label>
                  <input type="number" name="storage_limit"
                    class="form-control @error('storage_limit') is-invalid @enderror" id="storage_limit"
                    placeholder="Contoh: 1024" value="{{ old('storage_limit', 1024) }}">
                  <small class="form-text text-muted">0 = Unlimited.</small>
                  @error('storage_limit') <span class="invalid-feedback">{{ $message }}</span> @enderror
                </div>
              </div>
            </div>

            <div class="form-group">
              <div class="custom-control custom-checkbox">
                <input class="custom-control-input" type="checkbox" id="status_aktif" name="status_aktif" value="1"
                  {{ old('status_aktif', true) ? 'checked' : '' }}>
                <label for="status_aktif" class="custom-control-label">Status Sekolah Aktif</label>
              </div>
            </div>

            <div class="form-group">
              <label for="logo">Logo Sekolah (Optional)</label>
              <div class="input-group">
                <div class="custom-file">
                  <input type="file" class="custom-file-input" id="logo" name="logo" accept="image/*">
                  <label class="custom-file-label" for="logo">Choose file</label>
                </div>
              </div>
              <small class="form-text text-muted">Format: JPG, PNG. Maks: 2MB.</small>
              <div id="logo-preview-container" class="mt-2" style="display: none;">
                <img id="logo-preview" src="" alt="Logo Preview" style="max-height: 100px; border-radius: 8px;">
              </div>
              @error('logo') <span class="invalid-feedback d-block">{{ $message }}</span> @enderror
            </div>

            <hr>
            <h5>Informasi Akun Admin Sekolah</h5>
            <div class="form-group">
              <label for="admin_name">Nama Admin</label>
              <input type="text" name="admin_name" class="form-control @error('admin_name') is-invalid @enderror"
                placeholder="Nama Lengkap Admin" value="{{ old('admin_name') }}">
              @error('admin_name') <span class="invalid-feedback">{{ $message }}</span> @enderror
            </div>
            <div class="form-group">
              <label for="admin_email">Email Admin</label>
              <input type="email" name="admin_email" class="form-control @error('admin_email') is-invalid @enderror"
                placeholder="admin@sekolah.com" value="{{ old('admin_email') }}">
              @error('admin_email') <span class="invalid-feedback">{{ $message }}</span> @enderror
            </div>
            <div class="form-group">
              <label for="admin_password">Password</label>
              <input type="password" name="admin_password"
                class="form-control @error('admin_password') is-invalid @enderror" placeholder="Minimal 8 karakter">
              @error('admin_password') <span class="invalid-feedback">{{ $message }}</span> @enderror
            </div>
          </div>
          <!-- /.card-body -->

          <div class="card-footer">
            <button type="submit" class="btn btn-primary">Simpan Sekolah</button>
            <a href="{{ route('superadmin.tenants.index') }}" class="btn btn-default float-right">Batal</a>
          </div>
        </form>
      </div>
    </div>
  </div>
@endsection

@push('scripts')
  <script>
    $(document).ready(function () {
      $('#logo').on('change', function () {
        let reader = new FileReader();
        reader.onload = (e) => {
          $('#logo-preview').attr('src', e.target.result);
          $('#logo-preview-container').show();
        }
        reader.readAsDataURL(this.files[0]);
      });
    });
  </script>
@endpush