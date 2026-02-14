@extends('backend.layouts.app')

@section('title', 'Broadcast Notifikasi')
@section('page_title', 'Kelola Broadcast Notifikasi')

@section('breadcrumb')
  <li class="breadcrumb-item"><a href="{{ route('superadmin.dashboard') }}">Home</a></li>
  <li class="breadcrumb-item active">Broadcast</li>
@endsection

@section('content')
  <div class="row">
    <div class="col-12">
      <div class="card">
        <div class="card-header">
          <h3 class="card-title">Daftar Pengumuman</h3>
          <div class="card-tools">
            <a href="{{ route('superadmin.broadcasts.create') }}" class="btn btn-primary btn-sm">
              <i class="fas fa-plus"></i> Buat Pengumuman Baru
            </a>
          </div>
        </div>
        <!-- /.card-header -->
        <div class="card-body table-responsive p-0">
          <table class="table table-hover text-nowrap">
            <thead>
              <tr>
                <th>Judul</th>
                <th>Tipe</th>
                <th>Status</th>
                <th>Dibuat Pada</th>
                <th>Aksi</th>
              </tr>
            </thead>
            <tbody>
              @forelse($broadcasts as $broadcast)
                <tr>
                  <td>{{ $broadcast->title }}</td>
                  <td>
                    @if($broadcast->type == 'info') <span class="badge badge-info">Info</span>
                    @elseif($broadcast->type == 'warning') <span class="badge badge-warning">Peringatan</span>
                    @elseif($broadcast->type == 'danger') <span class="badge badge-danger">Penting</span>
                    @elseif($broadcast->type == 'success') <span class="badge badge-success">Sukses</span>
                    @endif
                  </td>
                  <td>
                    @if($broadcast->is_active)
                      <span class="badge badge-success">Aktif</span>
                    @else
                      <span class="badge badge-secondary">Tidak Aktif</span>
                    @endif
                  </td>
                  <td>{{ $broadcast->created_at->format('d M Y H:i') }}</td>
                  <td>
                    <form action="{{ route('superadmin.broadcasts.destroy', $broadcast->id) }}" method="POST"
                      onsubmit="return confirm('Hapus pengumuman ini?');">
                      @csrf
                      @method('DELETE')
                      <button type="submit" class="btn btn-danger btn-sm"><i class="fas fa-trash"></i> Hapus</button>
                    </form>
                  </td>
                </tr>
              @empty
                <tr>
                  <td colspan="5" class="text-center">Belum ada pengumuman.</td>
                </tr>
              @endforelse
            </tbody>
          </table>
        </div>
        <!-- /.card-body -->
        <div class="card-footer clearfix">
          {{ $broadcasts->links() }}
        </div>
      </div>
      <!-- /.card -->
    </div>
  </div>
@endsection