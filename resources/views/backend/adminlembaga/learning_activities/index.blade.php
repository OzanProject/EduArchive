@extends('backend.layouts.app')

@section('title', 'Kegiatan Pembelajaran')
@section('page_title', 'Kegiatan Pembelajaran')

@section('breadcrumb')
  <li class="breadcrumb-item"><a href="{{ route('adminlembaga.dashboard') }}">Dashboard</a></li>
  <li class="breadcrumb-item active">Kegiatan Pembelajaran</li>
@endsection

@section('content')
  <div class="row">
    <div class="col-12">
      <div class="card card-primary card-outline">
        <div class="card-header">
          <h3 class="card-title">Daftar Kegiatan Pembelajaran</h3>
          <div class="card-tools">
            <a href="{{ route('adminlembaga.learning-activities.create') }}" class="btn btn-primary btn-sm">
              <i class="fas fa-plus mr-1"></i> Tambah Kegiatan
            </a>
          </div>
        </div>
        <div class="card-body table-responsive p-0">
          <table class="table table-hover text-nowrap">
            <thead>
              <tr>
                <th>No</th>
                <th>Kegiatan</th>
                <th>Metode</th>
                <th>Hari</th>
                <th>Jam</th>
                <th>Status</th>
                <th>Aksi</th>
              </tr>
            </thead>
            <tbody>
              @forelse($activities as $act)
                <tr>
                  <td>{{ $loop->iteration + ($activities->currentPage() - 1) * $activities->perPage() }}</td>
                  <td>{{ Str::limit($act->activity_name, 30) }}</td>
                  <td>
                    <span
                      class="badge {{ $act->method == 'daring' ? 'badge-info' : ($act->method == 'luring' ? 'badge-success' : 'badge-warning') }}">
                      {{ ucfirst($act->method) }}
                    </span>
                  </td>
                  <td>{{ $act->day }}</td>
                  <td>{{ substr($act->time_start, 0, 5) }} - {{ substr($act->time_end, 0, 5) }}</td>
                  <td>
                    @php
                      $statusClass = [
                        'pending' => 'badge-secondary',
                        'approved' => 'badge-success',
                        'rejected' => 'badge-danger',
                      ][$act->status] ?? 'badge-light';
                    @endphp
                    <span class="badge {{ $statusClass }} text-uppercase">
                      {{ $act->status }}
                    </span>
                  </td>
                  <td>
                    <div class="btn-group">
                      <a href="{{ route('adminlembaga.learning-activities.show', $act->id) }}" class="btn btn-info btn-xs">
                        <i class="fas fa-eye"></i>
                      </a>
                      @if(in_array($act->status, ['pending', 'rejected']))
                        <a href="{{ route('adminlembaga.learning-activities.edit', $act->id) }}"
                          class="btn btn-warning btn-xs" title="Edit">
                          <i class="fas fa-edit"></i>
                        </a>
                        <form action="{{ route('adminlembaga.learning-activities.destroy', $act->id) }}" method="POST"
                          class="d-inline" onsubmit="return confirm('Hapus kegiatan ini?')">
                          @csrf
                          @method('DELETE')
                          <button class="btn btn-danger btn-xs" title="Hapus">
                            <i class="fas fa-trash"></i>
                          </button>
                        </form>
                      @endif
                    </div>
                  </td>
                </tr>
              @empty
                <tr>
                  <td colspan="7" class="text-center py-4 text-muted">Belum ada kegiatan pembelajaran.</td>
                </tr>
              @endforelse
            </tbody>
          </table>
        </div>
        <div class="card-footer clearfix">
          {{ $activities->links() }}
        </div>
      </div>
    </div>
  </div>
@endsection