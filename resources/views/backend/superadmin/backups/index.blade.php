@extends('backend.layouts.app')

@section('title', 'Backup & Restore')
@section('page_title', 'Manajemen Backup Sistem')

@section('breadcrumb')
  <li class="breadcrumb-item"><a href="{{ route('superadmin.dashboard') }}">Home</a></li>
  <li class="breadcrumb-item active">Backup & Restore</li>
@endsection

@section('content')
  <div class="row">
    <div class="col-md-12">
      <div class="card card-primary card-outline">
        <div class="card-header">
          <h3 class="card-title"><i class="fas fa-database"></i> Daftar Backup</h3>
          <div class="card-tools">
            <form action="{{ route('superadmin.backups.create') }}" method="POST" class="d-inline" id="backup-form">
              @csrf
              <div class="btn-group">
                <button type="submit" name="type" value="db" class="btn btn-sm btn-primary" onclick="return confirmBackup('db')">
                  <i class="fas fa-database"></i> Backup Database Saja
                </button>
                <button type="submit" name="type" value="full" class="btn btn-sm btn-success" onclick="return confirmBackup('full')">
                  <i class="fas fa-archive"></i> Backup Full (DB + File)
                </button>
              </div>
            </form>
          </div>
        </div>
        <!-- /.card-header -->
        <div class="card-body table-responsive p-0">
          <table class="table table-hover text-nowrap">
            <thead>
              <tr>
                <th>#</th>
                <th>Nama File</th>
                <th>Ukuran</th>
                <th>Tanggal Backup</th>
                <th>Aksi</th>
              </tr>
            </thead>
            <tbody>
              @forelse ($backups as $index => $backup)
                <tr>
                  <td>{{ $index + 1 }}</td>
                  <td><code>{{ $backup['file_name'] }}</code></td>
                  <td><span class="badge badge-info">{{ $backup['file_size'] }}</span></td>
                  <td>{{ $backup['created_at'] }}</td>
                  <td>
                    <a href="{{ route('superadmin.backups.download', $backup['file_name']) }}" class="btn btn-sm btn-success" title="Download">
                      <i class="fas fa-download"></i> Download
                    </a>
                    <form action="{{ route('superadmin.backups.destroy', $backup['file_name']) }}" method="POST" class="d-inline">
                      @csrf
                      @method('DELETE')
                      <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Yakin ingin menghapus file backup ini?');" title="Hapus">
                        <i class="fas fa-trash"></i> Hapus
                      </button>
                    </form>
                  </td>
                </tr>
              @empty
                <tr>
                  <td colspan="5" class="text-center">Belum ada file backup.</td>
                </tr>
              @endforelse
            </tbody>
          </table>
        </div>
        <!-- /.card-body -->
      </div>
      <div class="card card-warning card-outline">
        <div class="card-header">
          <h3 class="card-title"><i class="fas fa-upload"></i> Restore Database</h3>
        </div>
        <div class="card-body">
          <p class="text-danger"><i class="fas fa-exclamation-triangle"></i> <strong>Peringatan:</strong> Melakukan restore database akan <strong>menimpa seluruh data saat ini</strong>. Pastikan Anda memiliki backup terbaru sebelum melanjutkan!</p>
          <form action="{{ route('superadmin.backups.restore') }}" method="POST" enctype="multipart/form-data" onsubmit="return confirm('SANGAT PENTING: Apakah Anda benar-benar yakin ingin me-restore database ini? Data saat ini akan tertimpa permanen!');">
            @csrf
            <div class="form-group">
              <label for="sql_file">Upload File Database (.sql)</label>
              <div class="input-group">
                <div class="custom-file">
                  <input type="file" class="custom-file-input" id="sql_file" name="sql_file" accept=".sql" required>
                  <label class="custom-file-label" for="sql_file">Pilih file .sql</label>
                </div>
                <div class="input-group-append">
                  <button type="submit" class="btn btn-warning"><i class="fas fa-history"></i> Proses Restore</button>
                </div>
              </div>
              <small class="form-text text-muted">Ekstrak file zip backup Anda terlebih dahulu, lalu upload file <code>.sql</code> yang ada di dalam folder <code>db-dumps</code>.</small>
            </div>
          </form>
        </div>
      </div>
    </div>
  </div>
@endsection

@push('scripts')
  <script>
    function confirmBackup(type) {
      let msg = type === 'full' 
        ? 'Membuat full backup (Database + File) mungkin membutuhkan waktu beberapa menit. Lanjutkan?' 
        : 'Membuat backup database. Lanjutkan?';
        
      if (confirm(msg)) {
        // Change button states to loading
        const buttons = document.querySelectorAll('#backup-form button');
        buttons.forEach(btn => {
            btn.disabled = true;
            if (btn.value === type) {
                const originalHtml = btn.innerHTML;
                btn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Memproses...';
            }
        });
        
        // Actually submit the form
        setTimeout(() => {
            const form = document.getElementById('backup-form');
            const hiddenInput = document.createElement('input');
            hiddenInput.type = 'hidden';
            hiddenInput.name = 'type';
            hiddenInput.value = type;
            form.appendChild(hiddenInput);
            form.submit();
        }, 100);
        return false; // Prevent default since we submit manually above
      }
      return false;
    }
  </script>
@endpush
