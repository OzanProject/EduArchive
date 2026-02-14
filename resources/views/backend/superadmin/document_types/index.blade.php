@extends('backend.layouts.app')

@section('title', 'Manajemen Jenis Dokumen')
@section('page_title', 'Manajemen Jenis Dokumen')

@section('breadcrumb')
  <li class="breadcrumb-item"><a href="{{ route('superadmin.dashboard') }}">Dashboard</a></li>
  <li class="breadcrumb-item active">Jenis Dokumen</li>
@endsection

@section('content')
  <div class="row">
    <div class="col-12">
      <div class="card">
        <div class="card-header">
          <h3 class="card-title">Daftar Jenis Dokumen</h3>
          <div class="card-tools">
            <a href="{{ route('superadmin.document-types.create') }}" class="btn btn-primary btn-sm">
              <i class="fas fa-plus"></i> Tambah Baru
            </a>
          </div>
        </div>
        <div class="card-body table-responsive p-0">
          <table class="table table-hover text-nowrap">
            <thead>
              <tr>
                <th>Nama</th>
                <th>Kode</th>
                <th>Wajib?</th>
                <th>Status</th>
                <th>Aksi</th>
              </tr>
            </thead>
            <tbody>
              @forelse($types as $type)
                <tr>
                  <td>
                    <strong>{{ $type->name }}</strong><br>
                    <small class="text-muted">{{ $type->description }}</small>
                  </td>
                  <td><span class="badge badge-light">{{ $type->code }}</span></td>
                  <td>
                    @if($type->is_required)
                      <span class="badge badge-danger">Wajib</span>
                    @else
                      <span class="badge badge-info">Opsional</span>
                    @endif
                  </td>
                  <td>
                    @if($type->is_active)
                      <span class="badge badge-success">Aktif</span>
                    @else
                      <span class="badge badge-secondary">Non-Aktif</span>
                    @endif
                  </td>
                  <td>
                    <a href="{{ route('superadmin.document-types.edit', $type->id) }}" class="btn btn-warning btn-xs">
                      <i class="fas fa-edit"></i> Edit
                    </a>
                    <form action="{{ route('superadmin.document-types.destroy', $type->id) }}" method="POST"
                      class="d-inline" onsubmit="return confirm('Yakin ingin menghapus?')">
                      @csrf
                      @method('DELETE')
                      <button type="submit" class="btn btn-danger btn-xs">
                        <i class="fas fa-trash"></i> Hapus
                      </button>
                    </form>
                  </td>
                </tr>
              @empty
                <tr>
                  <td colspan="5" class="text-center text-muted">Belum ada jenis dokumen.</td>
                </tr>
              @endforelse
            </tbody>
          </table>
        </div>
      </div>
    </div>
  </div>
@endsection