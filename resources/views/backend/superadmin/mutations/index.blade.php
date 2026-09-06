@extends('backend.layouts.app')

@section('title', 'Mutasi Siswa')
@section('page_title', 'Data Mutasi Siswa')

@section('breadcrumb')
  <li class="breadcrumb-item"><a href="{{ route('superadmin.dashboard') }}">Home</a></li>
  <li class="breadcrumb-item active">Mutasi Siswa</li>
@endsection

@section('content')
  @push('styles')
    <!-- DataTables -->
    <link rel="stylesheet" href="{{ asset('adminlte3/plugins/datatables-bs4/css/dataTables.bootstrap4.min.css') }}">
    <link rel="stylesheet" href="{{ asset('adminlte3/plugins/datatables-responsive/css/responsive.bootstrap4.min.css') }}">
  @endpush

  <div class="row">
    <div class="col-12">
      <div class="card">
        <div class="card-header">
          <h3 class="card-title">Riwayat Mutasi Siswa</h3>
        </div>
        <!-- /.card-header -->
        <div class="card-body">
          <table id="mutations-table" class="table table-bordered table-striped">
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
              @foreach($mutations as $index => $mutation)
                <tr>
                  <td>{{ $index + 1 }}</td>
                  <td>{{ $mutation->student->nisn ?? '-' }}</td>
                  <td>{{ $mutation->student->nama ?? 'Siswa Terhapus' }}</td>
                  <td>{{ $mutation->fromTenant->nama_sekolah ?? 'Tidak Diketahui' }}</td>
                  <td>
                    @if($mutation->status === 'dropped_out')
                      <span class="text-muted"><i>Keluar/Inaktif</i></span>
                    @else
                      {{ $mutation->toTenant->nama_sekolah ?? 'Tidak Diketahui' }}
                    @endif
                  </td>
                  <td>{{ $mutation->created_at->format('d M Y H:i') }}</td>
                  <td>
                    @if($mutation->status === 'moved')
                      <span class="badge badge-warning">Pindah</span>
                    @elseif($mutation->status === 'dropped_out')
                      <span class="badge badge-danger">Inaktif/Drop Out</span>
                    @else
                      <span class="badge badge-success">Dikembalikan</span>
                    @endif
                  </td>
                  <td>
                    @if(($mutation->status === 'moved' && $mutation->student && $mutation->fromTenant && $mutation->toTenant) || ($mutation->status === 'dropped_out' && $mutation->student && $mutation->fromTenant))
                      <form action="{{ route('superadmin.mutations.return', $mutation->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Yakin ingin {{ $mutation->status === 'dropped_out' ? 'mengaktifkan kembali' : 'mengembalikan' }} siswa ini?');">
                        @csrf
                        <button type="submit" class="btn btn-sm btn-primary">
                          <i class="fas fa-undo"></i> {{ $mutation->status === 'dropped_out' ? 'Aktifkan Kembali' : 'Kembalikan' }}
                        </button>
                      </form>
                    @endif
                    <form action="{{ route('superadmin.mutations.destroy', $mutation->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Yakin ingin menghapus riwayat mutasi ini? Data yang dihapus tidak dapat dikembalikan.');">
                      @csrf
                      @method('DELETE')
                      <button type="submit" class="btn btn-sm btn-danger">
                        <i class="fas fa-trash"></i> Hapus
                      </button>
                    </form>
                  </td>
                </tr>
              @endforeach
            </tbody>
          </table>
        </div>
        <!-- /.card-body -->
      </div>
      <!-- /.card -->
    </div>
  </div>

  @push('scripts')
    <!-- DataTables  & Plugins -->
    <script src="{{ asset('adminlte3/plugins/datatables/jquery.dataTables.min.js') }}"></script>
    <script src="{{ asset('adminlte3/plugins/datatables-bs4/js/dataTables.bootstrap4.min.js') }}"></script>
    <script src="{{ asset('adminlte3/plugins/datatables-responsive/js/dataTables.responsive.min.js') }}"></script>
    <script src="{{ asset('adminlte3/plugins/datatables-responsive/js/responsive.bootstrap4.min.js') }}"></script>
    
    <script>
      $(function () {
        $('#mutations-table').DataTable({
          "paging": true,
          "lengthChange": true,
          "searching": true,
          "ordering": true,
          "info": true,
          "autoWidth": false,
          "responsive": true,
          "language": {
            "url": "//cdn.datatables.net/plug-ins/1.10.24/i18n/Indonesian.json"
          }
        });
      });
    </script>
  @endpush
@endsection
