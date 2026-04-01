@extends('backend.layouts.app')

@section('title', 'Revisi Fakta Integritas')

@section('content')
<div class="row">
    <div class="col-12">
        <div class="card mb-4">
            <div class="card-header pb-0 border-bottom-0">
                <h5 class="mb-0">Revisi Fakta Integritas</h5>
                @if($integrityPact->status === 'rejected')
                    <div class="alert alert-danger mt-3 text-white" role="alert">
                        <strong>Catatan Penolakan:</strong> {{ $integrityPact->status_notes ?? 'Tidak ada catatan.' }}
                    </div>
                @endif
            </div>
            <div class="card-body">
                @php
                    $roleRoute = auth()->user()->role === 'operator' ? 'operator' : 'adminlembaga';
                @endphp
                <form action="{{ route($roleRoute . '.integrity-pacts.update', ['tenant' => tenant('id'), 'integrity_pact' => $integrityPact->id]) }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')

                    <div class="mb-3">
                        <label for="title" class="form-label">Judul Berkas</label>
                        <input type="text" class="form-control @error('title') is-invalid @enderror" id="title" name="title" value="{{ old('title', $integrityPact->title) }}" required>
                        @error('title')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="file_path" class="form-label">Ganti Berkas PDF (Opsional)</label>
                        <input class="form-control @error('file_path') is-invalid @enderror" type="file" id="file_path" name="file_path" accept=".pdf">
                        <small class="text-muted">Maksimal 5MB. Biarkan kosong jika tidak ingin mengubah berkas asli.</small>
                        <div class="mt-2">
                            <a href="{{ tenant_asset($integrityPact->file_path) }}" target="_blank" class="text-sm">Lihat berkas saat ini</a>
                        </div>
                        @error('file_path')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="d-flex justify-content-end mt-4">
                        <a href="{{ route($roleRoute . '.integrity-pacts.index', ['tenant' => tenant('id')]) }}" class="btn btn-secondary me-2">Batal</a>
                        <button type="submit" class="btn btn-primary">Simpan Revisi</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
