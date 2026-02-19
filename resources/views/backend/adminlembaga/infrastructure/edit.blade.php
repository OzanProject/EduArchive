@extends('backend.layouts.app')

@section('title', 'Edit Usulan Sarpras')
@section('page_title', 'Edit Usulan')

@section('breadcrumb')
  <li class="breadcrumb-item"><a href="{{ route('adminlembaga.dashboard') }}">Dashboard</a></li>
  <li class="breadcrumb-item"><a href="{{ route('adminlembaga.infrastructure.index') }}">Usulan Sarpras</a></li>
  <li class="breadcrumb-item active">Edit</li>
@endsection

@section('content')
  <div class="row">
    <div class="col-md-8">
      <div class="card card-warning card-outline">
        <form action="{{ route('adminlembaga.infrastructure.update', $infrastructure->id) }}" method="POST"
          enctype="multipart/form-data">
          @csrf
          @method('PUT')
          <div class="card-body">
            <div class="form-group">
              <label>Tipe Kebutuhan <span class="text-danger">*</span></label>
              <select name="type" class="form-control @error('type') is-invalid @enderror" required>
                <option value="RKB" {{ old('type', $infrastructure->type) == 'RKB' ? 'selected' : '' }}>Ruang Kelas Baru
                  (RKB)</option>
                <option value="REHAB" {{ old('type', $infrastructure->type) == 'REHAB' ? 'selected' : '' }}>Rehabilitasi
                  (REHAB)</option>
              </select>
              @error('type') <span class="invalid-feedback">{{ $message }}</span> @enderror
            </div>

            <div class="form-group">
              <label>Judul Usulan <span class="text-danger">*</span></label>
              <input type="text" name="title" class="form-control @error('title') is-invalid @enderror"
                value="{{ old('title', $infrastructure->title) }}" placeholder="Contoh: Pembangunan 3 Ruang Kelas Baru"
                required>
              @error('title') <span class="invalid-feedback">{{ $message }}</span> @enderror
            </div>

            <div class="form-group">
              <label>Deskripsi Kebutuhan <span class="text-danger">*</span></label>
              <textarea name="description" class="form-control @error('description') is-invalid @enderror" rows="5"
                placeholder="Jelaskan secara detail alasan kebutuhan..."
                required>{{ old('description', $infrastructure->description) }}</textarea>
              @error('description') <span class="invalid-feedback">{{ $message }}</span> @enderror
            </div>

            <div class="form-group">
              <label>Estimasi Anggaran (Rp) <span class="text-danger">*</span></label>
              <div class="input-group">
                <div class="input-group-prepend">
                  <span class="input-group-text">Rp</span>
                </div>
                <input type="number" name="proposed_budget"
                  class="form-control @error('proposed_budget') is-invalid @enderror"
                  value="{{ old('proposed_budget', $infrastructure->proposed_budget) }}" required>
              </div>
              @error('proposed_budget') <span class="invalid-feedback d-block">{{ $message }}</span> @enderror
            </div>

            <div class="form-group">
              <label>Foto Kondisi / Lokasi (Opsional)</label>
              <div id="image-preview" class="mb-2 {{ $infrastructure->media_path ? '' : 'd-none' }}">
                <img id="preview-img"
                  src="{{ $infrastructure->media_path ? tenant_asset($infrastructure->media_path) : '#' }}"
                  alt="Preview" class="img-thumbnail" style="max-height: 200px;">
              </div>
              <div class="custom-file">
                <input type="file" name="thumbnail" class="custom-file-input @error('thumbnail') is-invalid @enderror"
                  id="thumbnail" onchange="previewImage(this)">
                <label class="custom-file-label" for="thumbnail">Pilih Gambar untuk mengganti...</label>
              </div>
              @error('thumbnail') <span class="invalid-feedback d-block">{{ $message }}</span> @enderror
            </div>
          </div>
          <div class="card-footer">
            <button type="submit" class="btn btn-warning">
              <i class="fas fa-save mr-1"></i> Simpan Perubahan
            </button>
            <a href="{{ route('adminlembaga.infrastructure.index') }}" class="btn btn-default">Batal</a>
          </div>
        </form>
      </div>
    </div>
  </div>
@endsection

@push('js')
  <script>
    $('.custom-file-input').on('change', function () {
      let fileName = $(this).val().split('\\').pop();
      $(this).next('.custom-file-label').addClass("selected").html(fileName);
    });

    function previewImage(input) {
      if (input.files && input.files[0]) {
        var reader = new FileReader();
        reader.onload = function (e) {
          $('#preview-img').attr('src', e.target.result);
          $('#image-preview').removeClass('d-none');
        }
        reader.readAsDataURL(input.files[0]);
      }
    }
  </script>
@endpush