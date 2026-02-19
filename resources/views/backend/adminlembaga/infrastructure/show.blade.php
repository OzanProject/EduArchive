@extends('backend.layouts.app')

@section('title', 'Detail Usulan Sarpras')
@section('page_title', 'Detail Usulan')

@section('breadcrumb')
  <li class="breadcrumb-item"><a href="{{ route('adminlembaga.dashboard') }}">Dashboard</a></li>
  <li class="breadcrumb-item"><a href="{{ route('adminlembaga.infrastructure.index') }}">Usulan Sarpras</a></li>
  <li class="breadcrumb-item active">Detail</li>
@endsection

@section('content')
  <div class="row">
    <div class="col-md-8">
      <div class="card card-outline {{ $infrastructure->status == 'rejected' ? 'card-danger' : 'card-info' }}">
        <div class="card-header">
          <h3 class="card-title">Informasi Usulan #{{ $infrastructure->id }}</h3>
          <div class="card-tools">
            <span class="badge {{ [
    'pending' => 'badge-secondary',
    'approved' => 'badge-success',
    'rejected' => 'badge-danger',
    'in_progress' => 'badge-primary',
    'completed' => 'badge-dark',
  ][$infrastructure->status] ?? 'badge-light' }} text-uppercase px-2 py-1">
              {{ str_replace('_', ' ', $infrastructure->status) }}
            </span>
          </div>
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
            <div class="col-sm-8 font-weight-bold text-success">Rp
              {{ number_format($infrastructure->proposed_budget, 0, ',', '.') }}
            </div>
          </div>
          <hr>
          <div class="mt-3">
            <h6><strong>Deskripsi:</strong></h6>
            <p class="text-justify">{!! nl2br(e($infrastructure->description)) !!}</p>
          </div>
        </div>
        <div class="card-footer text-right">
          @if($infrastructure->status == 'pending')
            <a href="{{ route('adminlembaga.infrastructure.edit', $infrastructure->id) }}" class="btn btn-warning btn-sm">
              <i class="fas fa-edit mr-1"></i> Edit Usulan
            </a>
          @endif
          <a href="{{ route('adminlembaga.infrastructure.index') }}" class="btn btn-default btn-sm">Kembali</a>
        </div>
      </div>
    </div>
    <div class="col-md-4">
      @if($infrastructure->media_path)
        <div class="card card-info">
          <div class="card-header">
            <h3 class="card-title"><i class="fas fa-image mr-1"></i> Lampiran Foto</h3>
          </div>
          <div class="card-body text-center p-2">
            <img src="{{ tenant_asset($infrastructure->media_path) }}" alt="Lampiran" class="img-fluid rounded shadow-sm">
          </div>
        </div>
      @endif

      <div class="card">
        <div class="card-header bg-light">
          <h3 class="card-title"><i class="fas fa-history mr-1"></i> Timeline</h3>
        </div>
        <div class="card-body p-0">
          <div class="p-3 border-bottom">
            <small class="text-muted d-block">{{ $infrastructure->created_at->format('d M Y, H:i') }}</small>
            <span class="text-sm">Usulan pertama kali dibuat dan dikirim ke Super Admin.</span>
          </div>
          @if($infrastructure->updated_at != $infrastructure->created_at)
            <div class="p-3">
              <small class="text-muted d-block">{{ $infrastructure->updated_at->format('d M Y, H:i') }}</small>
              <span class="text-sm">Status diperbarui menjadi
                <strong>{{ strtoupper(str_replace('_', ' ', $infrastructure->status)) }}</strong>.</span>
            </div>
          @endif
        </div>
      </div>
    </div>
  </div>
@endsection