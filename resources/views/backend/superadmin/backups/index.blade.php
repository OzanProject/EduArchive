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
      <!-- /.card -->
      
      <div class="callout callout-warning">
        <h5><i class="fas fa-exclamation-triangle"></i> Penting: Tentang Proses Restore</h5>
        <p>Proses pemulihan data (Restore) sangat berisiko jika dilakukan melalui web browser karena dapat terputus di tengah jalan. Praktik terbaik untuk keamanan data Anda adalah:</p>
        <ol>
            <li>Klik tombol <strong>Download</strong> pada file backup yang diinginkan.</li>
            <li>Ekstrak file `.zip` tersebut di komputer Anda. Di dalamnya terdapat folder `db-dumps` (berisi file .sql) dan file lain.</li>
            <li>Import file `.sql` tersebut langsung melalui phpMyAdmin, HeidiSQL, atau Terminal MySQL di server untuk merestore database.</li>
            <li>Jika Anda memilih Backup Full, salin folder `storage` hasil ekstrak ke server Anda jika ingin merestore file/dokumen.</li>
        </ol>
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
