@extends('backend.layouts.app')

@section('title', 'Data Jenjang Sekolah')

@section('content')
  <div class="row">
    <div class="col-12">
      <div class="card">
        <div class="card-header">
          <h3 class="card-title">Daftar Jenjang Sekolah</h3>
          <div class="card-tools">
            <button type="button" class="btn btn-primary btn-sm" data-toggle="modal" data-target="#createModal">
              <i class="fas fa-plus mr-1"></i> Tambah Jenjang
            </button>
          </div>
        </div>
        <div class="card-body table-responsive p-0">
          <table class="table table-hover text-nowrap">
            <thead>
              <tr>
                <th width="10%">Urutan</th>
                <th width="20%">Nama Jenjang</th>
                <th>Deskripsi</th>
                <th width="15%">Aksi</th>
              </tr>
            </thead>
            <tbody>
              @forelse($levels as $level)
                <tr>
                  <td>{{ $level->sequence }}</td>
                  <td><span class="badge badge-info">{{ $level->name }}</span></td>
                  <td>{{ $level->description ?? '-' }}</td>
                  <td>
                    <button type="button" class="btn btn-warning btn-xs" data-toggle="modal"
                      data-target="#editModal{{ $level->id }}">
                      <i class="fas fa-edit"></i>
                    </button>
                    <form action="{{ route('superadmin.school-levels.destroy', $level->id) }}" method="POST"
                      class="d-inline" onsubmit="return confirm('Yakin ingin menghapus jenjang ini?');">
                      @csrf
                      @method('DELETE')
                      <button type="submit" class="btn btn-danger btn-xs">
                        <i class="fas fa-trash"></i>
                      </button>
                    </form>
                  </td>
                </tr>

                <!-- Edit Modal -->
                <div class="modal fade" id="editModal{{ $level->id }}" tabindex="-1" role="dialog" aria-hidden="true">
                  <div class="modal-dialog" role="document">
                    <form action="{{ route('superadmin.school-levels.update', $level->id) }}" method="POST">
                      @csrf
                      @method('PUT')
                      <div class="modal-content">
                        <div class="modal-header">
                          <h5 class="modal-title">Edit Jenjang</h5>
                          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                          </button>
                        </div>
                        <div class="modal-body">
                          <div class="form-group">
                            <label>Nama Jenjang</label>
                            <input type="text" name="name" class="form-control" value="{{ $level->name }}" required>
                          </div>
                          <div class="form-group">
                            <label>Deskripsi</label>
                            <input type="text" name="description" class="form-control" value="{{ $level->description }}">
                          </div>
                          <div class="form-group">
                            <label>Urutan</label>
                            <input type="number" name="sequence" class="form-control" value="{{ $level->sequence }}"
                              required min="0">
                          </div>
                        </div>
                        <div class="modal-footer">
                          <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                          <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
                        </div>
                      </div>
                    </form>
                  </div>
                </div>
              @empty
                <tr>
                  <td colspan="4" class="text-center">Belum ada data jenjang.</td>
                </tr>
              @endforelse
            </tbody>
          </table>
        </div>
      </div>
    </div>
  </div>

  <!-- Create Modal -->
  <div class="modal fade" id="createModal" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog" role="document">
      <form action="{{ route('superadmin.school-levels.store') }}" method="POST">
        @csrf
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title">Tambah Jenjang Baru</h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">&times;</span>
            </button>
          </div>
          <div class="modal-body">
            <div class="form-group">
              <label>Nama Jenjang</label>
              <input type="text" name="name" class="form-control" placeholder="Contoh: SD, SMP" required>
            </div>
            <div class="form-group">
              <label>Deskripsi</label>
              <input type="text" name="description" class="form-control" placeholder="Keterangan singkat (Opsional)">
            </div>
            <div class="form-group">
              <label>Urutan</label>
              <input type="number" name="sequence" class="form-control" value="0" required min="0">
            </div>
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
            <button type="submit" class="btn btn-primary">Simpan</button>
          </div>
        </div>
      </form>
    </div>
  </div>
@endsection