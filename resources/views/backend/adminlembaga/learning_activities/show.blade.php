@extends('backend.layouts.app')

@section('title', 'Detail Kegiatan Pembelajaran')
@section('page_title', 'Detail Kegiatan')

@section('breadcrumb')
  <li class="breadcrumb-item"><a href="{{ route('adminlembaga.dashboard') }}">Dashboard</a></li>
  <li class="breadcrumb-item"><a href="{{ route('adminlembaga.learning-activities.index') }}">Kegiatan Pembelajaran</a>
  </li>
  <li class="breadcrumb-item active">Detail</li>
@endsection

@section('content')
  <div class="row">
    <div class="col-md-7">
      <div class="card card-outline card-info">
        <div class="card-header">
          <h3 class="card-title font-weight-bold">Informasi Kegiatan</h3>
        </div>
        <div class="card-body">
          <div class="row mb-3">
            <div class="col-sm-4 text-muted">Nama Kegiatan</div>
            <div class="col-sm-8 font-weight-bold">{{ $learningActivity->activity_name }}</div>
          </div>
          <div class="row mb-3">
            <div class="col-sm-4 text-muted">Metode</div>
            <div class="col-sm-8">
              <span
                class="badge {{ $learningActivity->method == 'daring' ? 'badge-info' : ($learningActivity->method == 'luring' ? 'badge-success' : 'badge-warning') }}">
                {{ ucfirst($learningActivity->method) }}
              </span>
            </div>
          </div>
          <div class="row mb-3">
            <div class="col-sm-4 text-muted">Waktu</div>
            <div class="col-sm-8 font-weight-bold">
              {{ $learningActivity->day }}, {{ substr($learningActivity->time_start, 0, 5) }} -
              {{ substr($learningActivity->time_end, 0, 5) }}
            </div>
          </div>
          <div class="row mb-3">
            <div class="col-sm-4 text-muted">Keterangan</div>
            <div class="col-sm-8 text-justify border p-2 bg-light rounded">
              {!! nl2br(e($learningActivity->description ?? 'Tidak ada keterangan tambahan.')) !!}
            </div>
          </div>
          @if($learningActivity->activity_image)
            <div class="row mb-3">
              <div class="col-sm-4 text-muted">Foto Bukti</div>
              <div class="col-sm-8 text-center text-sm-left">
                <img
                  src="{{ route('superadmin.tenants.asset', ['tenant' => tenant('id'), 'path' => $learningActivity->activity_image]) }}"
                  alt="Foto Kegiatan" class="img-thumbnail shadow-sm mb-2"
                  style="max-width: 100%; max-height: 400px; cursor: pointer;" onclick="window.open(this.src)">
                <br>
                <small class="text-muted"><i class="fas fa-search-plus mr-1"></i> Klik untuk memperbesar</small>
                <div class="alert alert-warning py-1 px-2 mt-2 d-inline-block small mb-0">
                  <i class="fas fa-map-marker-alt mr-1"></i> Pastikan foto memiliki titik koordinat
                </div>
              </div>
            </div>
          @endif
        </div>
        <div class="card-footer">
          <a href="{{ route('adminlembaga.learning-activities.index') }}" class="btn btn-default">Kembali</a>
          @if($learningActivity->status == 'pending')
            <a href="{{ route('adminlembaga.learning-activities.edit', $learningActivity->id) }}"
              class="btn btn-warning float-right">
              <i class="fas fa-edit mr-1"></i> Edit Kegiatan
            </a>
          @endif
        </div>
      </div>
    </div>

    <div class="col-md-5">
      <div
        class="card {{ $learningActivity->status == 'approved' ? 'card-success' : ($learningActivity->status == 'rejected' ? 'card-danger' : 'card-secondary') }}">
        <div class="card-header">
          <h3 class="card-title font-weight-bold"><i class="fas fa-satellite mr-1"></i> Status Monitoring</h3>
        </div>
        <div class="card-body">
          <div class="text-center mb-4">
            @php
              $statusColor = [
                'pending' => 'text-secondary',
                'approved' => 'text-success',
                'rejected' => 'text-danger',
              ][$learningActivity->status] ?? 'text-muted';

              $statusIcon = [
                'pending' => 'fa-clock',
                'approved' => 'fa-check-circle',
                'rejected' => 'fa-times-circle',
              ][$learningActivity->status] ?? 'fa-question-circle';
             @endphp
            <i class="fas {{ $statusIcon }} fa-4x {{ $statusColor }} mb-3"></i>
            <h4 class="font-weight-bold text-uppercase {{ $statusColor }}">{{ $learningActivity->status }}</h4>
            <p class="text-muted">Kegiatan ini sedang diawasi oleh Super Admin.</p>
          </div>

          @if($learningActivity->status_notes)
            <div class="alert alert-info shadow-sm">
              <h5><i class="icon fas fa-info"></i> Catatan Super Admin:</h5>
              {{ $learningActivity->status_notes }}
            </div>
          @endif

          <div class="mt-3">
            <small class="text-muted font-italic">
              * Jika status ditolak, silakan baca catatan admin dan lakukan perubahan jika diizinkan.
            </small>
          </div>
        </div>
      </div>
    </div>
  </div>
@endsection