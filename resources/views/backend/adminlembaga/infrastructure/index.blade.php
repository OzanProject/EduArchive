@extends('backend.layouts.app')

@section('title', 'Usulan Sarpras')
@section('page_title', 'RKB & REHAB')

@section('breadcrumb')
  <li class="breadcrumb-item"><a href="{{ route('adminlembaga.dashboard') }}">Dashboard</a></li>
  <li class="breadcrumb-item active">Usulan Sarpras</li>
@endsection

@section('content')
  <div class="row">
    <div class="col-12">
      <div class="card card-primary card-outline">
        <div class="card-header">
          <h3 class="card-title">Daftar Usulan Sarana Prasarana</h3>
          <div class="card-tools">
            <a href="{{ route('adminlembaga.infrastructure.create') }}" class="btn btn-primary btn-sm">
              <i class="fas fa-plus mr-1"></i> Tambah Usulan
            </a>
          </div>
        </div>
        <div class="card-body table-responsive p-0">
          <table class="table table-hover text-nowrap">
            <thead>
              <tr>
                <th>No</th>
                <th>Tipe</th>
                <th>Judul Usulan</th>
                <th>Anggaran (Rp)</th>
                <th>Status</th>
                <th>Tanggal</th>
                <th>Aksi</th>
              </tr>
            </thead>
            <tbody>
              @forelse($requests as $req)
                <tr>
                  <td>{{ $loop->iteration + ($requests->currentPage() - 1) * $requests->perPage() }}</td>
                  <td>
                    <span class="badge {{ $req->type == 'RKB' ? 'badge-info' : 'badge-warning' }}">
                      {{ $req->type }}
                    </span>
                  </td>
                  <td>{{ Str::limit($req->title, 40) }}</td>
                  <td>{{ number_format($req->proposed_budget, 0, ',', '.') }}</td>
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
                  <td>{{ $req->created_at->format('d/m/Y') }}</td>
                  <td>
                    <div class="btn-group">
                      <a href="{{ route('adminlembaga.infrastructure.show', $req->id) }}" class="btn btn-info btn-xs">
                        <i class="fas fa-eye"></i>
                      </a>
                      @if($req->status == 'pending')
                        <a href="{{ route('adminlembaga.infrastructure.edit', $req->id) }}" class="btn btn-warning btn-xs">
                          <i class="fas fa-edit"></i>
                        </a>
                        <form action="{{ route('adminlembaga.infrastructure.destroy', $req->id) }}" method="POST"
                          class="d-inline" onsubmit="return confirm('Hapus usulan ini?')">
                          @csrf
                          @method('DELETE')
                          <button class="btn btn-danger btn-xs">
                            <i class="fas fa-trash"></i>
                          </button>
                        </form>
                      @endif
                    </div>
                  </td>
                </tr>
              @empty
                <tr>
                  <td colspan="7" class="text-center py-4 text-muted">Belum ada usulan sarana prasarana.</td>
                </tr>
              @endforelse
            </tbody>
          </table>
        </div>
        <div class="card-footer clearfix">
          {{ $requests->links() }}
        </div>
      </div>
    </div>
  </div>
@endsection