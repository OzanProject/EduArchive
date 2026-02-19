@extends('backend.layouts.app')

@section('title', 'Monitoring Kegiatan Pembelajaran')
@section('page_title', 'Monitoring Kegiatan Pembelajaran')

@section('breadcrumb')
  <li class="breadcrumb-item"><a href="{{ route('superadmin.dashboard') }}">Home</a></li>
  <li class="breadcrumb-item active">Monitoring Kegiatan</li>
@endsection

@section('content')
  <div class="row">
    <div class="col-12">
      <div class="card card-modern">
        <div class="card-header">
          <h3 class="card-title">Filter Kegiatan</h3>
        </div>
        <div class="card-body bg-light">
          <form action="{{ route('superadmin.monitoring.learning-activities.index') }}" method="GET" class="row">
            <div class="col-md-3">
              <select name="tenant_id" class="form-control form-control-sm">
                <option value="">Semua Sekolah</option>
                @foreach($tenants as $t)
                  <option value="{{ $t->id }}" {{ request('tenant_id') == $t->id ? 'selected' : '' }}>{{ $t->nama_sekolah }}
                  </option>
                @endforeach
              </select>
            </div>
            <div class="col-md-2">
              <select name="method" class="form-control form-control-sm">
                <option value="">Semua Metode</option>
                <option value="daring" {{ request('method') == 'daring' ? 'selected' : '' }}>Daring</option>
                <option value="luring" {{ request('method') == 'luring' ? 'selected' : '' }}>Luring</option>
                <option value="hybrid" {{ request('method') == 'hybrid' ? 'selected' : '' }}>Hybrid</option>
              </select>
            </div>
            <div class="col-md-2">
              <select name="status" class="form-control form-control-sm">
                <option value="">Semua Status</option>
                <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Pending</option>
                <option value="approved" {{ request('status') == 'approved' ? 'selected' : '' }}>Approved</option>
                <option value="rejected" {{ request('status') == 'rejected' ? 'selected' : '' }}>Rejected</option>
              </select>
            </div>
            <div class="col-md-2">
              <button type="submit" class="btn btn-primary btn-sm btn-block">
                <i class="fas fa-filter mr-1"></i> Filter
              </button>
            </div>
          </form>
        </div>
        <div class="card-body table-responsive p-0">
          <table class="table table-hover text-nowrap">
            <thead>
              <tr>
                <th>No</th>
                <th>Sekolah</th>
                <th>Kegiatan</th>
                <th>Metode</th>
                <th>Hari & Jam</th>
                <th>Status</th>
                <th>Aksi</th>
              </tr>
            </thead>
            <tbody>
              @forelse($activities as $act)
                <tr>
                  <td>{{ $loop->iteration + ($activities->currentPage() - 1) * $activities->perPage() }}</td>
                  <td>
                    <span class="font-weight-bold">{{ $act->tenant->nama_sekolah }}</span><br>
                    <small class="text-muted">NPSN: {{ $act->tenant->npsn }}</small>
                  </td>
                  <td>{{ Str::limit($act->activity_name, 25) }}</td>
                  <td>
                    <span
                      class="badge {{ $act->method == 'daring' ? 'badge-info' : ($act->method == 'luring' ? 'badge-success' : 'badge-warning') }}">
                      {{ ucfirst($act->method) }}
                    </span>
                  </td>
                  <td>
                    {{ $act->day }}<br>
                    <small>{{ substr($act->time_start, 0, 5) }} - {{ substr($act->time_end, 0, 5) }}</small>
                  </td>
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
                    <a href="{{ route('superadmin.monitoring.learning-activities.show', $act->id) }}"
                      class="btn btn-info btn-xs">
                      <i class="fas fa-search mr-1"></i> Review
                    </a>
                  </td>
                </tr>
              @empty
                <tr>
                  <td colspan="7" class="text-center py-4 text-muted">Tidak ada kegiatan yang ditemukan.</td>
                </tr>
              @endforelse
            </tbody>
          </table>
        </div>
        <div class="card-footer">
          {{ $activities->appends(request()->query())->links() }}
        </div>
      </div>
    </div>
  </div>
@endsection