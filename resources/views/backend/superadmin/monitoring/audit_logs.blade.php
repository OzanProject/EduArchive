@extends('backend.layouts.app')

@section('title', 'Audit Logs')
@section('page_title', 'Audit Log Akses Dokumen')

@section('breadcrumb')
  <li class="breadcrumb-item"><a href="{{ route('superadmin.dashboard') }}">Home</a></li>
  <li class="breadcrumb-item active">Audit Logs</li>
@endsection

@section('content')
  <div class="row">
    <div class="col-12">
      <div class="card">
        <div class="card-header">
          <h3 class="card-title">Riwayat Akses Dokumen Siswa</h3>
        </div>
        <!-- /.card-header -->
        <div class="card-body table-responsive p-0">
          <table class="table table-hover text-nowrap">
            <thead>
              <tr>
                <th>Waktu Akses</th>
                <th>Admin User</th>
                <th>Sekolah (Tenant ID)</th>
                <th>NISN Siswa</th>
                <th>Dokumen</th>
                <th>Jenis Aktivitas</th>
                <th>IP Address</th>
                <th>Hapus</th>
              </tr>
            </thead>
            <tbody>
              @forelse($logs as $log)
                @php
                  $details = json_decode($log->details, true);
                @endphp
                <tr>
                  <td>{{ $log->created_at->format('d M Y H:i:s') }}</td>
                  <td>{{ $log->user->name ?? 'System' }}</td>
                  <td><span class="badge badge-info">{{ $log->tenant_id }}</span></td>
                  <td>{{ $details['student_nisn'] ?? '-' }}</td>
                  <td>{{ $details['document_name'] ?? '-' }}</td>
                  <td>
                    @if($log->action == 'VIEW')
                      <span class="badge badge-primary">LIHAT</span>
                    @elseif($log->action == 'APPROVE')
                      <span class="badge badge-success">SETUJU</span>
                    @elseif($log->action == 'REJECT')
                      <span class="badge badge-danger">TOLAK</span>
                    @else
                      <span class="badge badge-secondary">{{ $log->action }}</span>
                    @endif
                  </td>
                  <td>{{ $log->ip_address }}</td>
                  <td>
                    <form action="{{ route('superadmin.monitoring.audit_logs.destroy', $log->id) }}" method="POST"
                      class="d-inline" onsubmit="return confirm('Yakin ingin menghapus log ini?')">
                      @csrf
                      @method('DELETE')
                      <button type="submit" class="btn btn-sm btn-danger">
                        <i class="fas fa-trash"></i>
                      </button>
                    </form>
                  </td>
                </tr>
              @empty
                <tr>
                  <td colspan="7" class="text-center">Belum ada aktivitas akses dokumen.</td>
                </tr>
              @endforelse
            </tbody>
          </table>
        </div>
        <!-- /.card-body -->
        <div class="card-footer clearfix">
          {{ $logs->links() }}
        </div>
      </div>
      <!-- /.card -->
    </div>
  </div>
@endsection