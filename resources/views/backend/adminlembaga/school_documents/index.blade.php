@extends('backend.layouts.app')

@section('title', 'Arsip Dokumen Lembaga')
@section('page_title', 'Arsip Dokumen Lembaga')

@section('breadcrumb')
  <li class="breadcrumb-item"><a href="{{ route('adminlembaga.dashboard') }}">Dashboard</a></li>
  <li class="breadcrumb-item active">Arsip Dokumen</li>
@endsection

@section('content')
  <div class="row">
    <div class="col-12">
      <div class="alert alert-info">
        <h5><i class="icon fas fa-info"></i> Info</h5>
        Fitur Arsip Dokumen Lembaga ini digunakan untuk menyimpan dokumen-dokumen penting sekolah seperti SK Pendirian,
        Akreditasi, Tata Tertib, dan dokumen internal lainnya.
      </div>

      <div class="card">
        <div class="card-header">
          <h3 class="card-title">Daftar Arsip</h3>
          <div class="card-tools">
            <button class="btn btn-primary btn-sm" data-toggle="modal" data-target="#uploadModal">
              <i class="fas fa-plus"></i> Upload Dokumen Baru
            </button>
          </div>
        </div>
        <!-- /.card-header -->
        <div class="card-body table-responsive p-0">
          <table class="table table-hover text-nowrap">
            <thead>
              <tr>
                <th>No</th>
                <th>Nama Dokumen</th>
                <th>Kategori</th>
                <th>Tanggal Upload</th>
                <th>Petugas</th>
                <th>Aksi</th>
              </tr>
            </thead>
            <tbody>
              @forelse($documents as $doc)
                <tr>
                  <td>{{ $loop->iteration }}</td>
                  <td>{{ $doc->title }}</td>
                  <td><span class="badge badge-info">{{ $doc->category }}</span></td>
                  <td>{{ $doc->created_at->format('d M Y') }}</td>
                  <td>{{ $doc->uploader ? $doc->uploader->name : '-' }}</td>
                  <td>
                    <a href="{{ asset('storage/' . $doc->file_path) }}" target="_blank" class="btn btn-info btn-xs">
                      <i class="fas fa-download"></i>
                    </a>
                    <form action="{{ route('adminlembaga.school-documents.destroy', $doc->id) }}" method="POST"
                      style="display:inline-block;">
                      @csrf
                      @method('DELETE')
                      <button type="submit" class="btn btn-danger btn-xs"
                        onclick="return confirm('Yakin ingin menghapus arsip ini?')">
                        <i class="fas fa-trash"></i>
                      </button>
                    </form>
                  </td>
                </tr>
              @empty
                <tr>
                  <td colspan="6" class="text-center">Belum ada arsip dokumen.</td>
                </tr>
              @endforelse
            </tbody>
          </table>
        </div>
        <!-- /.card-body -->
      </div>
      <!-- /.card -->
    </div>
  </div>

  <!-- Modal Upload -->
  <div class="modal fade" id="uploadModal" tabindex="-1" role="dialog" aria-labelledby="uploadModalLabel"
    aria-hidden="true">
    <div class="modal-dialog" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="uploadModalLabel">Upload Arsip Dokumen</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <form action="{{ route('adminlembaga.school-documents.store') }}" method="POST" enctype="multipart/form-data">
          @csrf
          <div class="modal-body">
            <div class="form-group">
              <label>Nama Dokumen</label>
              <input type="text" name="title" class="form-control" required placeholder="Contoh: SK Pendirian Sekolah">
            </div>
            <div class="form-group">
              <label>Kategori</label>
              <select name="category" class="form-control" required>
                <option value="SK">Surat Keputusan (SK)</option>
                <option value="Surat Masuk">Surat Masuk</option>
                <option value="Surat Keluar">Surat Keluar</option>
                <option value="Akreditasi">Dokumen Akreditasi</option>
                <option value="Lainnya">Lainnya</option>
              </select>
            </div>
            <div class="form-group">
              <label>File Dokumen</label>
              <input type="file" name="file_path" class="form-control-file" required>
              <small class="text-muted">PDF/Word/Image, Max 10MB.</small>
            </div>
            <div class="form-group">
              <label>Keterangan</label>
              <textarea name="description" class="form-control" rows="2"></textarea>
            </div>
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
            <button type="submit" class="btn btn-primary">Simpan</button>
          </div>
        </form>
      </div>
    </div>
  </div>
@endsection