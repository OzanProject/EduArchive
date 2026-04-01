@extends('backend.layouts.app')

@section('title', 'Review Fakta Integritas')

@section('content')
<div class="row">
    <div class="col-12 col-lg-8 mb-4">
        <div class="card h-100">
            <div class="card-header pb-0 p-3">
                <div class="row">
                    <div class="col-md-8 d-flex align-items-center">
                        <h6 class="mb-0">Dokumen: {{ $pact->title }}</h6>
                    </div>
                </div>
            </div>
            <div class="card-body p-3">
                <div class="border rounded-3" style="height: 600px; overflow:hidden;">
                    <iframe src="{{ route('superadmin.tenants.asset', ['tenant' => $pact->tenant_id, 'path' => $pact->file_path]) }}" width="100%" height="100%" frameborder="0"></iframe>
                </div>
            </div>
        </div>
    </div>
    
    <div class="col-12 col-lg-4 mb-4">
        <div class="card h-100">
            <div class="card-header pb-0 p-3">
                <h6 class="mb-0">Detail & Persetujuan</h6>
            </div>
            <div class="card-body p-3">
                <ul class="list-group mb-4">
                    <li class="list-group-item border-0 d-flex justify-content-between ps-0 mb-2 border-radius-lg">
                        <div class="d-flex flex-column">
                            <span class="text-xs">Nama Lembaga:</span>
                            <span class="text-dark font-weight-bold">{{ $pact->tenant->nama_sekolah ?? 'Unknown' }}</span>
                        </div>
                    </li>
                    <li class="list-group-item border-0 d-flex justify-content-between ps-0 mb-2 border-radius-lg">
                        <div class="d-flex flex-column">
                            <span class="text-xs">Waktu Unggah:</span>
                            <span class="text-dark font-weight-bold">{{ $pact->created_at->format('d M Y, H:i') }}</span>
                        </div>
                    </li>
                    <li class="list-group-item border-0 d-flex justify-content-between ps-0 mb-2 border-radius-lg">
                        <div class="d-flex flex-column">
                            <span class="text-xs">Status Saat Ini:</span>
                            <span class="text-dark font-weight-bold text-sm">
                                @if($pact->status === 'approved')
                                    <span class="badge bg-gradient-success">Approved</span>
                                @elseif($pact->status === 'rejected')
                                    <span class="badge bg-gradient-danger">Rejected</span>
                                @else
                                    <span class="badge bg-gradient-warning">Pending</span>
                                @endif
                            </span>
                        </div>
                    </li>
                </ul>

                <hr class="horizontal dark">
                
                <form action="{{ route('superadmin.monitoring.integrity-pacts.status', $pact->id) }}" method="POST">
                    @csrf
                    @method('PATCH')
                    
                    <div class="mb-3">
                        <label for="status" class="form-label text-sm text-dark font-weight-bold">Update Status</label>
                        <select class="form-select @error('status') is-invalid @enderror" id="status" name="status" required>
                            <option value="">-- Pilih Status --</option>
                            <option value="approved" {{ old('status', $pact->status) === 'approved' ? 'selected' : '' }}>Setujui (Approve)</option>
                            <option value="rejected" {{ old('status', $pact->status) === 'rejected' ? 'selected' : '' }}>Tolak (Reject) minta revisi</option>
                        </select>
                        @error('status')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3" id="notes-container" style="{{ old('status', $pact->status) === 'rejected' ? '' : 'display: none;' }}">
                        <label for="status_notes" class="form-label text-sm text-dark font-weight-bold">Catatan Penolakan (Wajib jika menolak)</label>
                        <textarea class="form-control @error('status_notes') is-invalid @enderror" id="status_notes" name="status_notes" rows="4" placeholder="Jelaskan alasan dokumen ditolak...">{{ old('status_notes', $pact->status_notes) }}</textarea>
                        @error('status_notes')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="d-grid mt-4">
                        <button type="submit" class="btn btn-primary d-flex align-items-center justify-content-center">
                            <i class="fas fa-save me-2"></i> Simpan Status
                        </button>
                    </div>
                </form>
            </div>
            <div class="card-footer px-3 py-2 border-top">
                <a href="{{ route('superadmin.monitoring.integrity-pacts.index') }}" class="btn btn-outline-secondary btn-sm mb-0 w-100">
                    <i class="fas fa-arrow-left me-1"></i> Kembali ke Daftar
                </a>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const statusSelect = document.getElementById('status');
        const notesContainer = document.getElementById('notes-container');
        const notesTextarea = document.getElementById('status_notes');

        statusSelect.addEventListener('change', function() {
            if (this.value === 'rejected') {
                notesContainer.style.display = 'block';
                notesTextarea.setAttribute('required', 'required');
            } else {
                notesContainer.style.display = 'none';
                notesTextarea.removeAttribute('required');
                notesTextarea.value = ''; // clear when not rejected
            }
        });
    });
</script>
@endpush
