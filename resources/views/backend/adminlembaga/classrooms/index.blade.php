@extends('backend.layouts.app')

@section('title', 'Manajemen Kelas')
@section('page_title', 'Data Kelas (Rombongan Belajar)')

@section('breadcrumb')
  <li class="breadcrumb-item"><a href="{{ route('adminlembaga.dashboard') }}">Dashboard</a></li>
  <li class="breadcrumb-item active">Data Kelas</li>
@endsection

@section('content')
  <div class="row">
    <div class="col-12">
      <div class="card">
        <div class="card-header">
          <h3 class="card-title">Daftar Kelas</h3>
          <div class="card-tools">
            <a href="{{ route('adminlembaga.classrooms.create') }}" class="btn btn-primary btn-sm">
              <i class="fas fa-plus"></i> Tambah Baru
            </a>
          </div>
        </div>
        <!-- /.card-header -->
        <div class="card-body table-responsive p-0">
          <table class="table table-hover text-nowrap">
            <thead>
              <tr>
                <th>No</th>
                <th>Nama Kelas</th>
                <th>Tingkat</th>
                <th>Jurusan</th>
                <th>Wali Kelas</th>
                <th>Tahun Ajaran</th>
                <th>Aksi</th>
              </tr>
            </thead>
            <tbody>
              @forelse($classrooms as $classroom)
                <tr>
                  <td>{{ $loop->iteration + ($classrooms->currentPage() - 1) * $classrooms->perPage() }}</td>
                  <td>{{ $classroom->nama_kelas }}</td>
                  <td>{{ $classroom->tingkat ?? '-' }}</td>
                  <td>{{ $classroom->jurusan ?? '-' }}</td>
                  <td>
                    @if($classroom->teacher)
                      {{ $classroom->teacher->nama_lengkap }}
                    @else
                      <span class="text-muted text-sm">Belum ditentukan</span>
                    @endif
                  </td>
                  <td>{{ $classroom->tahun_ajaran }}</td>
                  <td>
                    <a href="{{ route('adminlembaga.classrooms.edit', $classroom->id) }}" class="btn btn-warning btn-xs">
                      <i class="fas fa-edit"></i>
                    </a>
                    <form action="{{ route('adminlembaga.classrooms.destroy', $classroom->id) }}" method="POST"
                      style="display:inline-block;">
                      @csrf
                      @method('DELETE')
                      <button type="submit" class="btn btn-danger btn-xs"
                        onclick="return confirm('Yakin ingin menghapus kelas ini? Data siswa di dalamnya mungkin akan terpengaruh.')">
                        <i class="fas fa-trash"></i>
                      </button>
                    </form>
                  </td>
                </tr>
              @empty
                <tr>
                  <td colspan="7" class="text-center">Belum ada data kelas.</td>
                </tr>
              @endforelse
            </tbody>
          </table>
        </div>
        <!-- /.card-body -->
        <div class="card-footer clearfix">
          {{ $classrooms->links() }}
        </div>
      </div>
      <!-- /.card -->
    </div>
  </div>
@endsection