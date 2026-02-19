@extends('backend.layouts.app')

@section('title', 'Monitoring Sarpras')
@section('page_title', 'Monitoring Usulan RKB & REHAB')

@section('breadcrumb')
  <li class="breadcrumb-item"><a href="{{ route('superadmin.dashboard') }}">Home</a></li>
  <li class="breadcrumb-item active">Monitoring Sarpras</li>
@endsection

@section('content')
  <div class="row">
    <div class="col-12">
      <div class="card card-modern">
        <div class="card-header">
          <h3 class="card-title">Filter Usulan</h3>
        </div>
        <div class="card-body bg-light">
          <form action="{{ route('superadmin.monitoring.infrastructure.index') }}" method="GET" class="row">
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
              <select name="type" class="form-control form-control-sm">
                <option value="">Semua Tipe</option>
                <option value="RKB" {{ request('type') == 'RKB' ? 'selected' : '' }}>RKB</option>
                <option value="REHAB" {{ request('type') == 'REHAB' ? 'selected' : '' }}>REHAB</option>
              </select>
            </div>
            <div class="col-md-2">
              <select name="status" class="form-control form-control-sm">
                <option value="">Semua Status</option>
                <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Pending</option>
                <option value="approved" {{ request('status') == 'approved' ? 'selected' : '' }}>Approved</option>
                <option value="in_progress" {{ request('status') == 'in_progress' ? 'selected' : '' }}>In Progress</option>
                <option value="completed" {{ request('status') == 'completed' ? 'selected' : '' }}>Completed</option>
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
                <th>Tipe</th>
                <th>Judul Usulan</th>
                <th>Anggaran</th>
                <th>Status</th>
                <th>Aksi</th>
              </tr>
            </thead>
            <tbody>
              @forelse($requests as $req)
                <tr>
                  <td>{{ $loop->iteration + ($requests->currentPage() - 1) * $requests->perPage() }}</td>
                  <td>
                    <span class="font-weight-bold">{{ $req->tenant->nama_sekolah }}</span><br>
                    <small class="text-muted">NPSN: {{ $req->tenant->npsn }}</small>
                  </td>
                  <td>
                    <span class="badge {{ $req->type == 'RKB' ? 'badge-info' : 'badge-warning' }}">
                      {{ $req->type }}
                    </span>
                  </td>
                  <td>{{ Str::limit($req->title, 35) }}</td>
                  <td>Rp {{ number_format($req->proposed_budget, 0, ',', '.') }}</td>
                  <td>
                    @php
                      $statusClass = [
                        'pending' => 'badge-secondary',
                        'approved' => 'badge-success',
                        'rejected' => 'badge-danger',
                        'in_progress' => 'badge-primary',
                        'completed' => 'badge-dark',
                      ][$req->status] ?? 'badge-light';
                    @endphp
                    <span class="badge {{ $statusClass }} text-uppercase">
                      {{ str_replace('_', ' ', $req->status) }}
                    </span>
                  </td>
                  <td>
                    <a href="{{ route('superadmin.monitoring.infrastructure.show', $req->id) }}"
                      class="btn btn-info btn-xs">
                      <i class="fas fa-search mr-1"></i> Review
                    </a>
                  </td>
                </tr>
              @empty
                <tr>
                  <td colspan="7" class="text-center py-4 text-muted">Tidak ada usulan sarana prasarana yang masuk.</td>
                </tr>
              @endforelse
            </tbody>
          </table>
        </div>
        <div class="card-footer">
          {{ $requests->appends(request()->query())->links() }}
        </div>
      </div>
    </div>
  </div>
@endsection