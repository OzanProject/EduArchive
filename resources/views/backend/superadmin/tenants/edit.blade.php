@extends('backend.layouts.app')

@section('title', 'Edit Sekolah')
@section('page_title', 'Edit Data Sekolah')

@section('breadcrumb')
  <li class="breadcrumb-item"><a href="{{ route('superadmin.dashboard') }}">Home</a></li>
  <li class="breadcrumb-item"><a href="{{ route('superadmin.tenants.index') }}">Sekolah</a></li>
  <li class="breadcrumb-item active">Edit</li>
@endsection

@section('content')
  <div class="row">
    <div class="col-md-6">
      <div class="card card-primary">
        <div class="card-header">
          <h3 class="card-title">Edit Data Sekolah</h3>
        </div>
        <!-- /.card-header -->
        <!-- form start -->
        <form action="{{ route('superadmin.tenants.update', $tenant->id) }}" method="POST" enctype="multipart/form-data">
          @csrf
          @method('PUT')
          <div class="card-body">
            <div class="form-group">
              <label for="npsn">NPSN</label>
              <input type="text" name="npsn" class="form-control @error('npsn') is-invalid @enderror" id="npsn"
                placeholder="Masukkan NPSN" value="{{ old('npsn', $tenant->npsn) }}">
              @error('npsn') <span class="invalid-feedback">{{ $message }}</span> @enderror
            </div>

            <div class="form-group">
              <label for="nama_sekolah">Nama Sekolah</label>
              <input type="text" name="nama_sekolah" class="form-control @error('nama_sekolah') is-invalid @enderror"
                id="nama_sekolah" placeholder="Contoh: SMP Negeri 1 Jakarta"
                value="{{ old('nama_sekolah', $tenant->nama_sekolah) }}">
              @error('nama_sekolah') <span class="invalid-feedback">{{ $message }}</span> @enderror
            </div>

            <div class="form-group">
              <label for="jenjang">Jenjang</label>
              <select name="jenjang" class="form-control @error('jenjang') is-invalid @enderror">
                <option value="">-- Pilih Jenjang --</option>
                @foreach($schoolLevels as $level)
                  <option value="{{ $level->name }}" {{ old('jenjang', $tenant->jenjang) == $level->name ? 'selected' : '' }}>
                    {{ $level->name }} - {{ $level->description }}
                  </option>
                @endforeach
              </select>
              @error('jenjang') <span class="invalid-feedback">{{ $message }}</span> @enderror
            </div>

            <div class="form-group">
              <label>Domain (Tidak dapat diubah)</label>
              <input type="text" class="form-control" value="{{ $tenant->domains->first()->domain ?? '-' }}" disabled>
            </div>

            <div class="form-group">
              <label for="alamat">Alamat Lengkap</label>
              <textarea name="alamat" class="form-control" rows="3"
                placeholder="Alamat sekolah...">{{ old('alamat', $tenant->alamat) }}</textarea>
            </div>

            <div class="row">
              <div class="col-md-6">
                <div class="form-group">
                  <label for="subscription_plan">Paket Langganan</label>
                  <select name="subscription_plan" class="form-control @error('subscription_plan') is-invalid @enderror">
                    <option value="Free" {{ old('subscription_plan', $tenant->subscription_plan) == 'Free' ? 'selected' : '' }}>Free</option>
                    <option value="Basic" {{ old('subscription_plan', $tenant->subscription_plan) == 'Basic' ? 'selected' : '' }}>Basic</option>
                    <option value="Premium" {{ old('subscription_plan', $tenant->subscription_plan) == 'Premium' ? 'selected' : '' }}>Premium</option>
                  </select>
                  @error('subscription_plan') <span class="invalid-feedback">{{ $message }}</span> @enderror
                </div>
              </div>
              <div class="col-md-6">
                <div class="form-group">
                  <label for="storage_limit">Batas Penyimpanan (MB)</label>
                  <div class="input-group">
                    <input type="number" name="storage_limit" class="form-control @error('storage_limit') is-invalid @enderror"
                      id="storage_limit" placeholder="Contoh: 1024"
                      value="{{ old('storage_limit', $tenant->storage_limit ? round($tenant->storage_limit / 1024 / 1024) : '') }}"
                      {{ is_null($tenant->storage_limit) ? 'disabled' : '' }}>
                    <div class="input-group-append">
                      <div class="input-group-text">
                        <input type="checkbox" id="unlimited_storage" {{ is_null($tenant->storage_limit) ? 'checked' : '' }}>
                        <label class="form-check-label ml-1" for="unlimited_storage" style="cursor: pointer;">Unlimited</label>
                      </div>
                    </div>
                  </div>
                  <small class="form-text text-muted">Jika <strong>Unlimited</strong> dicentang, maka sekolah ini bebas upload tanpa batas.</small>
                  @error('storage_limit') <span class="invalid-feedback d-block">{{ $message }}</span> @enderror
                </div>
              </div>
            </div>

            <div class="form-group">
              <div class="custom-control custom-checkbox">
                <input class="custom-control-input" type="checkbox" id="status_aktif" name="status_aktif" value="1"
                  {{ old('status_aktif', $tenant->status_aktif) ? 'checked' : '' }}>
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

              <div class="mt-2">
                @if($tenant->logo)
                  <img id="logo-preview" src="{{ route('superadmin.tenants.asset', ['tenant' => $tenant->id, 'path' => $tenant->logo]) }}" alt="Logo Sekolah"
                    style="max-height: 100px; border-radius: 8px;">
                @else
                  <div id="logo-preview-container" style="display: none;">
                    <img id="logo-preview" src="" alt="Logo Preview" style="max-height: 100px; border-radius: 8px;">
                  </div>
                @endif
              </div>
              @error('logo') <span class="invalid-feedback d-block">{{ $message }}</span> @enderror
            </div>

            <hr>
            <h5>Informasi Akun Admin Sekolah</h5>
            <div class="alert alert-warning">
              <i class="fas fa-info-circle"></i> Password biarkan kosong jika tidak ingin mengubahnya.
            </div>

            <div class="form-group">
              <label for="admin_name">Nama Admin</label>
              <input type="text" name="admin_name" class="form-control @error('admin_name') is-invalid @enderror"
                placeholder="Nama Lengkap Admin" value="{{ old('admin_name', $admin->name ?? '') }}">
              @error('admin_name') <span class="invalid-feedback">{{ $message }}</span> @enderror
            </div>
            <div class="form-group">
              <label for="admin_email">Email Admin</label>
              <input type="email" name="admin_email" class="form-control @error('admin_email') is-invalid @enderror"
                placeholder="admin@sekolah.com" value="{{ old('admin_email', $admin->email ?? '') }}">
              @error('admin_email') <span class="invalid-feedback">{{ $message }}</span> @enderror
            </div>
            <div class="form-group">
              <label for="admin_password">Password Baru</label>
              <input type="password" name="admin_password"
                class="form-control @error('admin_password') is-invalid @enderror" placeholder="Minimal 8 karakter">
              @error('admin_password') <span class="invalid-feedback">{{ $message }}</span> @enderror
            </div>
          </div>
          <!-- /.card-body -->

          <div class="card-footer">
            <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
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
          // Find image
          let img = $('#logo-preview');
          if (img.length) {
            img.attr('src', e.target.result);
          } else {
            // If no existing logo, show container and set src
            $('#logo-preview-container img').attr('src', e.target.result);
            $('#logo-preview-container').show();
          }
        }
        reader.readAsDataURL(this.files[0]);
      });

      // Storage Limit Toggle
      const storageInput = $('#storage_limit');
      const unlimitedCheckbox = $('#unlimited_storage');

      function toggleStorage() {
        if (unlimitedCheckbox.is(':checked')) {
          storageInput.val('').prop('disabled', true);
        } else {
          storageInput.prop('disabled', false);
        }
      }

      // Initial State
      toggleStorage();

      unlimitedCheckbox.on('change', function () {
        toggleStorage();
      });
    });
  </script>
@endpush