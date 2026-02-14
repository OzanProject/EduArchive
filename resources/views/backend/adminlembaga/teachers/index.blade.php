@extends('backend.layouts.app')

@section('title', 'Manajemen Guru & Tendik')
@section('page_title', 'Data Guru & Tenaga Kependidikan')

@section('breadcrumb')
  <li class="breadcrumb-item"><a href="{{ route('adminlembaga.dashboard') }}">Dashboard</a></li>
  <li class="breadcrumb-item active">Guru & Tendik</li>
@endsection

@section('content')
  <div class="row">
    <div class="col-12">
      <div class="card">
        <div class="card-header">
          <h3 class="card-title">Daftar Guru & Tendik</h3>
          <div class="card-tools">
            <a href="{{ route('adminlembaga.teachers.create') }}" class="btn btn-primary btn-sm">
              <i class="fas fa-plus"></i> Tambah Guru
            </a>
            <button type="button" class="btn btn-success btn-sm ml-2" data-toggle="modal" data-target="#importModal">
              <i class="fas fa-file-excel"></i> Import Excel
            </button>
          </div>
        </div>
        <!-- /.card-header -->
        <div class="card-body table-responsive p-0">
          <table class="table table-hover text-nowrap">
            <thead>
              <tr>
                <th>No</th>
                <th>Foto</th>
                <th>Nama Lengkap</th>
                <th>NIP / NUPTK</th>
                <th>Jenis Kelamin</th>
                <th>Status</th>
                <th>Aksi</th>
              </tr>
            </thead>
            <tbody>
              @forelse($teachers as $teacher)
                <tr>
                  <td>{{ $loop->iteration + ($teachers->currentPage() - 1) * $teachers->perPage() }}</td>
                  <td>
                    @if($teacher->foto)
                      <img src="{{ asset('storage/' . $teacher->foto) }}" alt="Foto" class="img-circle"
                        style="width: 30px; height: 30px; object-fit: cover;">
                    @else
                      <img src="{{ asset('adminlte3/dist/img/user2-160x160.jpg') }}" alt="Default" class="img-circle"
                        style="width: 30px; height: 30px; object-fit: cover;">
                    @endif
                  </td>
                  <td>
                    {{ $teacher->gelar_depan }} {{ $teacher->nama_lengkap }} {{ $teacher->gelar_belakang }}
                    <br>
                    <small class="text-muted">{{ $teacher->email }}</small>
                  </td>
                  <td>
                    {{ $teacher->nip ?? '-' }} <br>
                    <small class="text-muted">NUPTK: {{ $teacher->nuptk ?? '-' }}</small>
                  </td>
                  <td>{{ $teacher->jenis_kelamin == 'L' ? 'Laki-laki' : 'Perempuan' }}</td>
                  <td>
                    <span
                      class="badge badge-{{ $teacher->status_kepegawaian == 'PNS' || $teacher->status_kepegawaian == 'PPPK' ? 'success' : 'info' }}">
                      {{ $teacher->status_kepegawaian }}
                    </span>
                  </td>
                  <td>
                    <a href="{{ route('adminlembaga.teachers.edit', $teacher->id) }}" class="btn btn-warning btn-xs">
                      <i class="fas fa-edit"></i>
                    </a>
                    <form action="{{ route('adminlembaga.teachers.destroy', $teacher->id) }}" method="POST"
                      style="display:inline-block;">
                      @csrf
                      @method('DELETE')
                      <button type="submit" class="btn btn-danger btn-xs"
                        onclick="return confirm('Yakin ingin menghapus data ini?')">
                        <i class="fas fa-trash"></i>
                      </button>
                    </form>
                  </td>
                </tr>
              @empty
                <tr>
                  <td colspan="7" class="text-center">Belum ada data guru/tendik.</td>
                </tr>
              @endforelse
            </tbody>
          </table>
        </div>
        <!-- /.card-body -->
        <div class="card-footer clearfix">
          {{ $teachers->links() }}
        </div>
      </div>
      <!-- /.card -->
    </div>
  </div>

  @include('backend.adminlembaga.teachers.import_modal')
@endsection