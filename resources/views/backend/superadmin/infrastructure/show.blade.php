@extends('backend.layouts.app')

@section('title', 'Review Usulan Sarpras')
@section('page_title', 'Review Usulan')

@section('breadcrumb')
  <li class="breadcrumb-item"><a href="{{ route('superadmin.dashboard') }}">Home</a></li>
  <li class="breadcrumb-item"><a href="{{ route('superadmin.monitoring.infrastructure.index') }}">Monitoring Sarpras</a>
  </li>
  <li class="breadcrumb-item active">Detail</li>
@endsection

@section('content')
  <div class="row">
    <div class="col-md-7">
      <div class="card card-outline {{ $infrastructure->status == 'rejected' ? 'card-danger' : 'card-info' }}">
        <div class="card-header">
          <h3 class="card-title">Detail Usulan Dari : <strong>{{ $infrastructure->tenant->nama_sekolah }}</strong></h3>
        </div>
        <div class="card-body">
          <div class="row mb-3">
            <div class="col-sm-4 text-muted">Tipe Usulan</div>
            <div class="col-sm-8 font-weight-bold">
              {{ $infrastructure->type == 'RKB' ? 'Ruang Kelas Baru' : 'Rehabilitasi' }}
            </div>
          </div>
          <div class="row mb-3">
            <div class="col-sm-4 text-muted">Judul</div>
            <div class="col-sm-8 font-weight-bold">{{ $infrastructure->title }}</div>
          </div>
          <div class="row mb-3">
            <div class="col-sm-4 text-muted">Anggaran Diajukan</div>
            <div class="col-sm-8 font-weight-bold text-success" style="font-size: 1.2rem;">
              Rp {{ number_format($infrastructure->proposed_budget, 0, ',', '.') }}
            </div>
          </div>
          <hr>
          <div class="mt-3">
            <h6><strong>Deskripsi Pihak Sekolah:</strong></h6>
            <p class="text-justify border p-3 bg-light rounded shadow-sm">{!! nl2br(e($infrastructure->description)) !!}
            </p>
          </div>
        </div>
      </div>
    </div>

    <div class="col-md-5">
      <!-- STATUS UPDATE CARD -->
      <div class="card card-primary">
        <div class="card-header">
          <h3 class="card-title"><i class="fas fa-tasks mr-1"></i> Aksi & Pembaruan Status</h3>
        </div>
        <div class="card-body">
          <form action="{{ route('superadmin.monitoring.infrastructure.status', $infrastructure->id) }}" method="POST">
            @csrf
            @method('PATCH')
            <div class="form-group">
              <label>Status Saat Ini :</label>
              @php
                $statusClass = [
                  'pending' => 'badge-secondary',
                  'approved' => 'badge-success',
                  'rejected' => 'badge-danger',
                  'in_progress' => 'badge-primary',
                  'completed' => 'badge-dark',
                ][$infrastructure->status] ?? 'badge-light';
              @endphp
              <span class="badge {{ $statusClass }} text-uppercase px-2">{{ $infrastructure->status }}</span>
            </div>

            <div class="form-group">
              <label>Ubah Status Jadi :</label>
              <select name="status" class="form-control">
                <option value="pending" {{ $infrastructure->status == 'pending' ? 'selected' : '' }}>Pending (Menunggu)
                </option>
                <option value="approved" {{ $infrastructure->status == 'approved' ? 'selected' : '' }}>Setujui (Approved)
                </option>
                <option value="in_progress" {{ $infrastructure->status == 'in_progress' ? 'selected' : '' }}>Sedang
                  Dikerjakan (In Progress)</option>
                <option value="completed" {{ $infrastructure->status == 'completed' ? 'selected' : '' }}>Selesai (Completed)
                </option>
                <option value="rejected" {{ $infrastructure->status == 'rejected' ? 'selected' : '' }}>Tolak (Rejected)
                </option>
              </select>
            </div>

            <button type="submit" class="btn btn-primary btn-block">
              <i class="fas fa-save mr-1"></i> Update Status
            </button>
            <small class="text-muted d-block mt-2 font-italic text-center">
              * Perubahan status akan langsung terlihat oleh pihak sekolah pengusul.
            </small>
          </form>
        </div>
      </div>

      @if($infrastructure->media_path)
        <div class="card card-info card-outline">
          <div class="card-header">
            <h3 class="card-title"><i class="fas fa-camera mr-1"></i> Foto Pendukung</h3>
          </div>
          <div class="card-body text-center p-2">
            <a href="{{ route('superadmin.tenants.asset', [$infrastructure->tenant_id, $infrastructure->media_path]) }}"
              target="_blank">
              <img src="{{ route('superadmin.tenants.asset', [$infrastructure->tenant_id, $infrastructure->media_path]) }}"
                alt="Lampiran" class="img-fluid rounded shadow-sm hover-zoom" style="max-height: 250px;">
            </a>
          </div>
        </div>
      @endif
    </div>
  </div>
@endsection

@push('css')
  <style>
    .hover-zoom:hover {
      transform: scale(1.02);
      transition: 0.3s ease;
      cursor: pointer;
    }
  </style>
@endpush