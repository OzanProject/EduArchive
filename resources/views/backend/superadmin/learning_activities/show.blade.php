@extends('backend.layouts.app')

@section('title', 'Review Kegiatan Pembelajaran')
@section('page_title', 'Review Kegiatan')

@section('breadcrumb')
  <li class="breadcrumb-item"><a href="{{ route('superadmin.dashboard') }}">Home</a></li>
  <li class="breadcrumb-item"><a href="{{ route('superadmin.monitoring.learning-activities.index') }}">Monitoring
      Kegiatan</a></li>
  <li class="breadcrumb-item active">Review</li>
@endsection

@section('content')
  <div class="row">
    <div class="col-md-7">
      <div class="card card-outline card-info">
        <div class="card-header">
          <h3 class="card-title font-weight-bold">Detail Kegiatan : <strong>{{ $activity->tenant->nama_sekolah }}</strong>
          </h3>
        </div>
        <div class="card-body">
          <div class="row mb-3">
            <div class="col-sm-4 text-muted">Nama Kegiatan</div>
            <div class="col-sm-8 font-weight-bold">{{ $activity->activity_name }}</div>
          </div>
          <div class="row mb-3">
            <div class="col-sm-4 text-muted">Metode</div>
            <div class="col-sm-8">
              <span
                class="badge {{ $activity->method == 'daring' ? 'badge-info' : ($activity->method == 'luring' ? 'badge-success' : 'badge-warning') }}">
                {{ ucfirst($activity->method) }}
              </span>
            </div>
          </div>
          <div class="row mb-3">
            <div class="col-sm-4 text-muted">Waktu Pelaksanaan</div>
            <div class="col-sm-8 font-weight-bold">
              {{ $activity->day }}, {{ substr($activity->time_start, 0, 5) }} - {{ substr($activity->time_end, 0, 5) }}
            </div>
          </div>
          <div class="row mb-3">
            <div class="col-sm-4 text-muted">Deskripsi</div>
            <div class="col-sm-8 text-justify border p-2 bg-light rounded shadow-sm">
              {!! nl2br(e($activity->description ?? 'Tidak ada deskripsi.')) !!}
            </div>
          </div>
          @if($activity->activity_image)
            <div class="row mb-3">
              <div class="col-sm-4 text-muted">Foto Bukti Kegiatan</div>
              <div class="col-sm-8">
                <img
                  src="{{ route('superadmin.tenants.asset', ['tenant' => $activity->tenant_id, 'path' => $activity->activity_image]) }}"
                  alt="Foto Kegiatan" class="img-thumbnail shadow-sm mb-2"
                  style="max-width: 100%; max-height: 400px; cursor: pointer;" onclick="window.open(this.src)">
                <br>
                <span class="badge badge-warning">
                  <i class="fas fa-map-marker-alt mr-1"></i> Periksa Titik Koordinat (Geotag)
                </span>
              </div>
            </div>
          @endif
        </div>
      </div>
    </div>

    <div class="col-md-5">
      <div class="card card-primary">
        <div class="card-header">
          <h3 class="card-title"><i class="fas fa-check-double mr-1"></i> Aksi & Tanggapan Monitoring</h3>
        </div>
        <div class="card-body">
          <form action="{{ route('superadmin.monitoring.learning-activities.status', $activity->id) }}" method="POST">
            @csrf
            @method('PATCH')

            <div class="form-group">
              <label>Status Monitoring :</label>
              @php
                $statusClass = [
                  'pending' => 'badge-secondary',
                  'approved' => 'badge-success',
                  'rejected' => 'badge-danger',
                ][$activity->status] ?? 'badge-light';
              @endphp
              <span class="badge {{ $statusClass }} text-uppercase px-2">{{ $activity->status }}</span>
            </div>

            <div class="form-group">
              <label>Pembaruan Status :</label>
              <select name="status" class="form-control">
                <option value="pending" {{ $activity->status == 'pending' ? 'selected' : '' }}>Pending</option>
                <option value="approved" {{ $activity->status == 'approved' ? 'selected' : '' }}>Approve (Disetujui)
                </option>
                <option value="rejected" {{ $activity->status == 'rejected' ? 'selected' : '' }}>Reject (Ditolak)</option>
              </select>
            </div>

            <div class="form-group">
              <label>Catatan / Feedback :</label>
              <textarea name="status_notes" class="form-control" rows="4"
                placeholder="Berikan alasan atau instruksi jika perlu...">{{ $activity->status_notes }}</textarea>
            </div>

            <button type="submit" class="btn btn-primary btn-block">
              <i class="fas fa-save mr-1"></i> Simpan Tanggapan
            </button>
          </form>
        </div>
      </div>

      <div class="card card-modern card-outline card-info">
        <div class="card-body">
          <h6 class="font-weight-bold text-muted"><i class="fas fa-university mr-1"></i> Profil Lembaga</h6>
          <p class="mb-1"><strong>{{ $activity->tenant->nama_sekolah }}</strong></p>
          <p class="text-sm text-muted mb-0">NPSN: {{ $activity->tenant->npsn }}</p>
          <p class="text-sm text-muted">Jenjang: {{ $activity->tenant->jenjang }}</p>
          <a href="{{ route('superadmin.monitoring.school', $activity->tenant_id) }}" class="btn btn-xs btn-outline-info">
            <i class="fas fa-external-link-alt mr-1"></i> Lihat Data Sekolah
          </a>
        </div>
      </div>
    </div>
  </div>
@endsection