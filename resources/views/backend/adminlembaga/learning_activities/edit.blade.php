@extends('backend.layouts.app')

@section('title', 'Edit Kegiatan Pembelajaran')
@section('page_title', 'Edit Kegiatan')

@section('breadcrumb')
  <li class="breadcrumb-item"><a href="{{ route('adminlembaga.dashboard') }}">Dashboard</a></li>
  <li class="breadcrumb-item"><a href="{{ route('adminlembaga.learning-activities.index') }}">Kegiatan Pembelajaran</a>
  </li>
  <li class="breadcrumb-item active">Edit</li>
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
          label.innerText = 'Pilih gambar baru...';
          container.style.display = 'none';
        }
      }
    </script>
  @endsection
@endsection

@section('content')
  <div class="row">
    <div class="col-md-8">
      <div class="card card-warning">
        <div class="card-header">
          <h3 class="card-title">Form Edit Kegiatan</h3>
        </div>
        <form action="{{ route('adminlembaga.learning-activities.update', $learningActivity->id) }}" method="POST"
          enctype="multipart/form-data">
          @csrf
          @method('PATCH')
          <div class="card-body">
            <div class="form-group">
              <label for="activity_name">Nama Kegiatan <span class="text-danger">*</span></label>
              <input type="text" name="activity_name" class="form-control @error('activity_name') is-invalid @enderror"
                id="activity_name" value="{{ old('activity_name', $learningActivity->activity_name) }}" required>
              @error('activity_name') <span class="invalid-feedback">{{ $message }}</span> @enderror
            </div>

            <div class="row">
              <div class="col-md-6">
                <div class="form-group">
                  <label for="method">Metode Pembelajaran <span class="text-danger">*</span></label>
                  <select name="method" class="form-control @error('method') is-invalid @enderror" id="method" required>
                    <option value="daring" {{ old('method', $learningActivity->method) == 'daring' ? 'selected' : '' }}>
                      Daring (Online)</option>
                    <option value="luring" {{ old('method', $learningActivity->method) == 'luring' ? 'selected' : '' }}>
                      Luring (Offline)</option>
                    <option value="hybrid" {{ old('method', $learningActivity->method) == 'hybrid' ? 'selected' : '' }}>
                      Hybrid (Campuran)</option>
                  </select>
                  @error('method') <span class="invalid-feedback">{{ $message }}</span> @enderror
                </div>
              </div>
              <div class="col-md-6">
                <div class="form-group">
                  <label for="day">Hari <span class="text-danger">*</span></label>
                  <select name="day" class="form-control @error('day') is-invalid @enderror" id="day" required>
                    @foreach(['Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu', 'Minggu'] as $h)
                      <option value="{{ $h }}" {{ old('day', $learningActivity->day) == $h ? 'selected' : '' }}>{{ $h }}
                      </option>
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
                    id="time_start" value="{{ old('time_start', substr($learningActivity->time_start, 0, 5)) }}" required>
                  @error('time_start') <span class="invalid-feedback">{{ $message }}</span> @enderror
                </div>
              </div>
              <div class="col-md-6">
                <div class="form-group">
                  <label for="time_end">Jam Selesai <span class="text-danger">*</span></label>
                  <input type="time" name="time_end" class="form-control @error('time_end') is-invalid @enderror"
                    id="time_end" value="{{ old('time_end', substr($learningActivity->time_end, 0, 5)) }}" required>
                  @error('time_end') <span class="invalid-feedback">{{ $message }}</span> @enderror
                </div>
              </div>
            </div>

            <div class="form-group">
              <label for="description">Keterangan / Deskripsi</label>
              <textarea name="description" class="form-control @error('description') is-invalid @enderror"
                id="description" rows="3">{{ old('description', $learningActivity->description) }}</textarea>
              @error('description') <span class="invalid-feedback">{{ $message }}</span> @enderror
            </div>

            <div class="form-group">
              <label for="activity_image">Foto Bukti Kegiatan</label>
              @if($learningActivity->activity_image)
                <div class="mb-2">
                  <img
                    src="{{ route('superadmin.tenants.asset', ['tenant' => tenant('id'), 'path' => $learningActivity->activity_image]) }}"
                    class="img-thumbnail" style="max-height: 150px;">
                  <p class="small text-muted mb-0">Foto saat ini</p>
                </div>
              @endif
              <div class="input-group">
                <div class="custom-file">
                  <input type="file" name="activity_image"
                    class="custom-file-input @error('activity_image') is-invalid @enderror" id="activity_image"
                    accept="image/*" onchange="previewImage(this)">
                  <label class="custom-file-label" for="activity_image">Pilih gambar baru...</label>
                </div>
              </div>
              @error('activity_image') <span class="text-danger small">{{ $message }}</span> @enderror
              <small class="text-muted d-block mt-1">
                <i class="fas fa-exclamation-triangle text-warning mr-1"></i>
                <strong>PENTING:</strong> Foto <strong>WAJIB</strong> menggunakan titik koordinat (geotag/timestamp
                camera). Kosongkan jika tidak ingin mengubah foto.
              </small>
              <div id="image-preview-container" class="mt-2" style="display: none;">
                <img id="image-preview" src="#" alt="Preview" class="img-thumbnail" style="max-height: 200px;">
              </div>
            </div>
          </div>
          <div class="card-footer text-right">
            <a href="{{ route('adminlembaga.learning-activities.index') }}" class="btn btn-default mr-2">Batal</a>
            <button type="submit" class="btn btn-warning">Perbarui Kegiatan</button>
          </div>
        </form>
      </div>
    </div>
  </div>
@endsection