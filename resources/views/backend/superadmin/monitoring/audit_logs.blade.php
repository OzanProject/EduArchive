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
      <form id="bulk-delete-form" action="{{ route('superadmin.monitoring.audit_logs.bulk_destroy') }}" method="POST">
        @csrf
        <div class="card">
          <div class="card-header">
            <h3 class="card-title">Riwayat Akses Dokumen Siswa</h3>
            <div class="card-tools">
              <button type="submit" id="btn-bulk-delete" class="btn btn-sm btn-danger d-none"
                onclick="return confirm('Yakin ingin menghapus log yang dipilih?')">
                <i class="fas fa-trash"></i> Hapus Terpilih
              </button>
            </div>
          </div>
          <!-- /.card-header -->
          <div class="card-body table-responsive p-0">
            <table class="table table-hover text-nowrap">
              <thead>
                <tr>
                  <th width="40">
                    <div class="custom-control custom-checkbox">
                      <input class="custom-control-input" type="checkbox" id="check-all">
                      <label for="check-all" class="custom-control-label"></label>
                    </div>
                  </th>
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
                    <td>
                      <div class="custom-control custom-checkbox">
                        <input class="custom-control-input log-checkbox" type="checkbox" name="ids[]"
                          id="check-{{ $log->id }}" value="{{ $log->id }}">
                        <label for="check-{{ $log->id }}" class="custom-control-label"></label>
                      </div>
                    </td>
                    <td>{{ $log->created_at->format('d M Y H:i:s') }}</td>
                    <td>{{ $log->user->name ?? 'System' }}</td>
                    <td>
                      <span class="badge badge-info">{{ $log->tenant->nama_sekolah ?? $log->tenant_id }}</span>
                    </td>
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
                      <button type="button" class="btn btn-sm btn-danger" onclick="deleteSingleLog({{ $log->id }})">
                        <i class="fas fa-trash"></i>
                      </button>
                    </td>
                  </tr>
                @empty
                  <tr>
                    <td colspan="9" class="text-center">Belum ada aktivitas akses dokumen.</td>
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
      </form>
    </div>
  </div>

  <form id="delete-single-form" action="" method="POST" style="display:none;">
    @csrf
    @method('DELETE')
  </form>
@endsection

@push('scripts')
  <script>
    $(function () {
      const checkAll = $('#check-all');
      const logCheckboxes = $('.log-checkbox');
      const btnBulkDelete = $('#btn-bulk-delete');

      checkAll.on('change', function () {
        logCheckboxes.prop('checked', $(this).prop('checked'));
        toggleBulkDeleteBtn();
      });

      logCheckboxes.on('change', function () {
        if (!$(this).prop('checked')) {
          checkAll.prop('checked', false);
        }
        if (logCheckboxes.filter(':checked').length === logCheckboxes.length) {
          checkAll.prop('checked', true);
        }
        toggleBulkDeleteBtn();
      });

      function toggleBulkDeleteBtn() {
        if (logCheckboxes.filter(':checked').length > 0) {
          btnBulkDelete.removeClass('d-none');
        } else {
          btnBulkDelete.addClass('d-none');
        }
      }
    });

    function deleteSingleLog(id) {
      if (confirm('Yakin ingin menghapus log ini?')) {
        const form = $('#delete-single-form');
        form.attr('action', `/superadmin/monitoring/audit-logs/${id}`);
        form.submit();
      }
    }
  </script>
@endpush