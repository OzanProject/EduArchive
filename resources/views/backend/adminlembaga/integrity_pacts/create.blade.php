@extends('backend.layouts.app')

@section('title', 'Upload Fakta Integritas')

@section('content')
<div class="row">
    <div class="col-12">
        <div class="card mb-4">
            <div class="card-header pb-0 border-bottom-0">
                <h5 class="mb-0">Upload Fakta Integritas</h5>
            </div>
            <div class="card-body">
                @php
                    $roleRoute = auth()->user()->role === 'operator' ? 'operator' : 'adminlembaga';
                @endphp
                <form action="{{ route($roleRoute . '.integrity-pacts.store', ['tenant' => tenant('id')]) }}" method="POST" enctype="multipart/form-data">
                    @csrf

                    <div class="mb-3">
                        <label for="title" class="form-label">Judul Berkas</label>
                        <input type="text" class="form-control @error('title') is-invalid @enderror" id="title" name="title" value="{{ old('title', 'Fakta Integritas ' . date('Y')) }}" required>
                        @error('title')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="file_path" class="form-label">Berkas PDF</label>
                        <input class="form-control @error('file_path') is-invalid @enderror" type="file" id="file_path" name="file_path" accept=".pdf" required>
                        <small class="text-muted">Maksimal 5MB. Hanya format PDF.</small>
                        @error('file_path')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="d-flex justify-content-end">
                        <a href="{{ route($roleRoute . '.integrity-pacts.index', ['tenant' => tenant('id')]) }}" class="btn btn-secondary me-2">Batal</a>
                        <button type="submit" class="btn btn-primary">Upload Berkas</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
