@extends('backend.layouts.app')

@section('title', 'Mutasi Siswa')
@section('page_title', 'Data Mutasi Siswa')

@section('breadcrumb')
  <li class="breadcrumb-item"><a href="{{ route('superadmin.dashboard') }}">Home</a></li>
  <li class="breadcrumb-item active">Mutasi Siswa</li>
@endsection

@section('content')
  <div class="row">
    <div class="col-12">
      <div class="card">
        <div class="card-header">
          <h3 class="card-title">Riwayat Mutasi Siswa</h3>
        </div>
        <!-- /.card-header -->
        <div class="card-body table-responsive p-0">
          <table class="table table-hover text-nowrap">
            <thead>
              <tr>
                <th>No</th>
                <th>NISN</th>
                <th>Nama Siswa</th>
                <th>Lembaga Asal</th>
                <th>Lembaga Tujuan</th>
                <th>Tgl Mutasi</th>
                <th>Status</th>
                <th>Aksi</th>
              </tr>
            </thead>
            <tbody>
              @forelse($mutations as $mutation)
                <tr>
                  <td>{{ $mutations->firstItem() + $loop->index }}</td>
                  <td>{{ $mutation->student->nisn ?? '-' }}</td>
                  <td>{{ $mutation->student->nama ?? 'Siswa Terhapus' }}</td>
                  <td>{{ $mutation->fromTenant->nama_sekolah ?? 'Tidak Diketahui' }}</td>
                  <td>{{ $mutation->toTenant->nama_sekolah ?? 'Tidak Diketahui' }}</td>
                  <td>{{ $mutation->created_at->format('d M Y H:i') }}</td>
                  <td>
                    @if($mutation->status === 'moved')
                      <span class="badge badge-warning">Pindah</span>
                    @else
                      <span class="badge badge-success">Dikembalikan</span>
                    @endif
                  </td>
                  <td>
                    @if($mutation->status === 'moved' && $mutation->student && $mutation->fromTenant && $mutation->toTenant)
                      <form action="{{ route('superadmin.mutations.return', $mutation->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Yakin ingin mengembalikan siswa ini ke lembaga asalnya?');">
                        @csrf
                        <button type="submit" class="btn btn-sm btn-primary">
                          <i class="fas fa-undo"></i> Kembalikan
                        </button>
                      </form>
                    @endif
                  </td>
                </tr>
              @empty
                <tr>
                  <td colspan="8" class="text-center">Belum ada riwayat mutasi siswa.</td>
                </tr>
              @endforelse
            </tbody>
          </table>
        </div>
        <!-- /.card-body -->
        @if($mutations->hasPages())
        <div class="card-footer clearfix">
          {{ $mutations->links('pagination::bootstrap-4') }}
        </div>
        @endif
      </div>
      <!-- /.card -->
    </div>
  </div>
@endsection
