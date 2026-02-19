@extends('backend.layouts.app')

@section('title', 'Tambah Usulan Sarpras')
@section('page_title', 'Buat Usulan Baru')

@section('breadcrumb')
  <li class="breadcrumb-item"><a href="{{ route('adminlembaga.dashboard') }}">Dashboard</a></li>
  <li class="breadcrumb-item"><a href="{{ route('adminlembaga.infrastructure.index') }}">Usulan Sarpras</a></li>
  <li class="breadcrumb-item active">Tambah</li>
@endsection

@section('content')
  <div class="row">
    <div class="col-md-8">
      <div class="card card-primary card-outline">
        <form action="{{ route('adminlembaga.infrastructure.store') }}" method="POST" enctype="multipart/form-data">
          @csrf
          <div class="card-body">
            <div class="form-group">
              <label>Tipe Kebutuhan <span class="text-danger">*</span></label>
              <select name="type" class="form-control @error('type') is-invalid @enderror" required>
                <option value="">-- Pilih Tipe --</option>
                <option value="RKB" {{ old('type') == 'RKB' ? 'selected' : '' }}>Ruang Kelas Baru (RKB)</option>
                <option value="REHAB" {{ old('type') == 'REHAB' ? 'selected' : '' }}>Rehabilitasi (REHAB)</option>
              </select>
              @error('type') <span class="invalid-feedback">{{ $message }}</span> @enderror
            </div>

            <div class="form-group">
              <label>Judul Usulan <span class="text-danger">*</span></label>
              <input type="text" name="title" class="form-control @error('title') is-invalid @enderror"
                value="{{ old('title') }}" placeholder="Contoh: Pembangunan 3 Ruang Kelas Baru" required>
              @error('title') <span class="invalid-feedback">{{ $message }}</span> @enderror
            </div>

            <div class="form-group">
              <label>Deskripsi Kebutuhan <span class="text-danger">*</span></label>
              <textarea name="description" class="form-control @error('description') is-invalid @enderror" rows="5"
                placeholder="Jelaskan secara detail alasan kebutuhan dan spesifikasi yang diminta..."
                required>{{ old('description') }}</textarea>
              @error('description') <span class="invalid-feedback">{{ $message }}</span> @enderror
            </div>

            <div class="form-group">
              <label>Estimasi Anggaran (Rp) <span class="text-danger">*</span></label>
              <div class="input-group">
                <div class="input-group-prepend">
                  <span class="input-group-text">Rp</span>
                </div>
                <input type="number" name="proposed_budget"
                  class="form-control @error('proposed_budget') is-invalid @enderror" value="{{ old('proposed_budget') }}"
                  placeholder="Contoh: 150000000" required>
              </div>
              @error('proposed_budget') <span class="invalid-feedback d-block">{{ $message }}</span> @enderror
            </div>

            <div class="form-group">
              <label>Foto Kondisi / Lokasi (Opsional)</label>
              <div id="image-preview" class="mb-2 d-none">
                <img id="preview-img" src="#" alt="Preview" class="img-thumbnail" style="max-height: 200px;">
              </div>
              <div class="custom-file">
                <input type="file" name="thumbnail" class="custom-file-input @error('thumbnail') is-invalid @enderror"
                  id="thumbnail" onchange="previewImage(this)">
                <label class="custom-file-label" for="thumbnail">Pilih Gambar...</label>
              </div>
              <small class="text-muted">Format: JPG, PNG, JPEG. Max: 2MB</small>
              @error('thumbnail') <span class="invalid-feedback d-block">{{ $message }}</span> @enderror
            </div>
          </div>
          <div class="card-footer">
            <button type="submit" class="btn btn-primary">
              <i class="fas fa-paper-plane mr-1"></i> Kirim Usulan
            </button>
            <a href="{{ route('adminlembaga.infrastructure.index') }}" class="btn btn-default">Batal</a>
          </div>
        </form>
      </div>
    </div>
    <div class="col-md-4">
      <div class="card card-info card-outline">
        <div class="card-header">
          <h3 class="card-title"><i class="fas fa-info-circle mr-1"></i> Petunjuk</h3>
        </div>
        <div class="card-body">
          <ul class="pl-3">
            <li>Pilih <strong>RKB</strong> jika sekolah membutuhkan pembangunan ruang baru.</li>
            <li>Pilih <strong>REHAB</strong> jika sekolah memiliki fasilitas yang rusak dan perlu diperbaiki.</li>
            <li>Sertakan estimasi anggaran yang logis berdasarkan kebutuhan.</li>
            <li>Unggah foto pendukung untuk memperkuat usulan Anda.</li>
          </ul>
        </div>
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