@extends('backend.layouts.app')

@section('title', 'Tambah Kegiatan Pembelajaran')
@section('page_title', 'Tambah Kegiatan')

@section('breadcrumb')
  <li class="breadcrumb-item"><a href="{{ route('adminlembaga.dashboard') }}">Dashboard</a></li>
  <li class="breadcrumb-item"><a href="{{ route('adminlembaga.learning-activities.index') }}">Kegiatan Pembelajaran</a>
  </li>
  <li class="breadcrumb-item active">Tambah</li>
  @section('scripts')
    <script>
      function previewImage(input) {
        const container = document.getElementById('image-preview-container');
        const preview = document.getElementById('image-preview');
        const label = input.nextElementSibling;

        if (input.files && input.files[0]) {
          const reader = new FileReader();
          label.innerText = input.files[0].name;

          reader.onload = function (e) {
            preview.src = e.target.result;
            container.style.display = 'block';
          }

          reader.readAsDataURL(input.files[0]);
        } else {
          label.innerText = 'Pilih gambar...';
          container.style.display = 'none';
        }
      }
    </script>
  @endsection
@endsection

@section('content')
  <div class="row">
    <div class="col-md-8">
      <div class="card card-primary">
        <div class="card-header">
          <h3 class="card-title">Form Tambah Kegiatan</h3>
        </div>
        <form action="{{ route('adminlembaga.learning-activities.store') }}" method="POST" enctype="multipart/form-data">
          @csrf
          <div class="card-body">
            <div class="form-group">
              <label for="activity_name">Nama Kegiatan <span class="text-danger">*</span></label>
              <input type="text" name="activity_name" class="form-control @error('activity_name') is-invalid @enderror"
                id="activity_name" value="{{ old('activity_name') }}" placeholder="Contoh: PKBM A, Kelas Menjahit, dll"
                required>
              @error('activity_name') <span class="invalid-feedback">{{ $message }}</span> @enderror
            </div>

            <div class="row">
              <div class="col-md-6">
                <div class="form-group">
                  <label for="method">Metode Pembelajaran <span class="text-danger">*</span></label>
                  <select name="method" class="form-control @error('method') is-invalid @enderror" id="method" required>
                    <option value="">-- Pilih Metode --</option>
                    <option value="daring" {{ old('method') == 'daring' ? 'selected' : '' }}>Daring (Online)</option>
                    <option value="luring" {{ old('method') == 'luring' ? 'selected' : '' }}>Luring (Offline)</option>
                    <option value="hybrid" {{ old('method') == 'hybrid' ? 'selected' : '' }}>Hybrid (Campuran)</option>
                  </select>
                  @error('method') <span class="invalid-feedback">{{ $message }}</span> @enderror
                </div>
              </div>
              <div class="col-md-6">
                <div class="form-group">
                  <label for="day">Hari <span class="text-danger">*</span></label>
                  <select name="day" class="form-control @error('day') is-invalid @enderror" id="day" required>
                    <option value="">-- Pilih Hari --</option>
                    @foreach(['Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu', 'Minggu'] as $h)
                      <option value="{{ $h }}" {{ old('day') == $h ? 'selected' : '' }}>{{ $h }}</option>
                    @endforeach
                  </select>
                  @error('day') <span class="invalid-feedback">{{ $message }}</span> @enderror
                </div>
              </div>
            </div>

            <div class="row">
              <div class="col-md-6">
                <div class="form-group">
                  <label for="time_start">Jam Mulai <span class="text-danger">*</span></label>
                  <input type="time" name="time_start" class="form-control @error('time_start') is-invalid @enderror"
                    id="time_start" value="{{ old('time_start') }}" required>
                  @error('time_start') <span class="invalid-feedback">{{ $message }}</span> @enderror
                </div>
              </div>
              <div class="col-md-6">
                <div class="form-group">
                  <label for="time_end">Jam Selesai <span class="text-danger">*</span></label>
                  <input type="time" name="time_end" class="form-control @error('time_end') is-invalid @enderror"
                    id="time_end" value="{{ old('time_end') }}" required>
                  @error('time_end') <span class="invalid-feedback">{{ $message }}</span> @enderror
                </div>
              </div>
            </div>

            <div class="form-group">
              <label for="description">Keterangan / Deskripsi</label>
              <textarea name="description" class="form-control @error('description') is-invalid @enderror"
                id="description" rows="3" placeholder="Jelaskan detail kegiatan...">{{ old('description') }}</textarea>
              @error('description') <span class="invalid-feedback">{{ $message }}</span> @enderror
            </div>

            <div class="form-group">
              <label for="activity_image">Foto Bukti Kegiatan <span class="text-danger">*</span></label>
              <div class="input-group">
                <div class="custom-file">
                  <input type="file" name="activity_image"
                    class="custom-file-input @error('activity_image') is-invalid @enderror" id="activity_image"
                    accept="image/*" onchange="previewImage(this)">
                  <label class="custom-file-label" for="activity_image">Pilih gambar...</label>
                </div>
              </div>
              @error('activity_image') <span class="text-danger small">{{ $message }}</span> @enderror
              <small class="text-muted d-block mt-1">
                <i class="fas fa-exclamation-triangle text-warning mr-1"></i>
                <strong>PENTING:</strong> Foto <strong>WAJIB</strong> menggunakan titik koordinat (geotag/timestamp
                camera).
              </small>
              <div id="image-preview-container" class="mt-2" style="display: none;">
                <img id="image-preview" src="#" alt="Preview" class="img-thumbnail" style="max-height: 200px;">
              </div>
            </div>
          </div>
          <div class="card-footer text-right">
            <a href="{{ route('adminlembaga.learning-activities.index') }}" class="btn btn-default mr-2">Batal</a>
            <button type="submit" class="btn btn-primary">Simpan Kegiatan</button>
          </div>
        </form>
      </div>
    </div>
    <div class="col-md-4">
      <div class="card card-info card-outline">
        <div class="card-header">
          <h3 class="card-title font-weight-bold"><i class="fas fa-info-circle mr-1"></i> Informasi</h3>
        </div>
        <div class="card-body">
          <ul class="pl-3">
            <li>Kegiatan yang baru ditambahkan akan berstatus <strong>Pending</strong>.</li>
            <li>Status akan berubah setelah ditinjau oleh <strong>Super Admin</strong>.</li>
            <li>Anda hanya dapat mengubah atau menghapus kegiatan yang masih berstatus <strong>Pending</strong>.</li>
          </ul>
        </div>
      </div>
    </div>
  </div>
@endsection