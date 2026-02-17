@extends('backend.layouts.app')

@section('title', 'Detail Siswa')
@section('page_title')
  <span class="text-muted">Monitoring / </span> {{ $student->nama }}
@endsection

@section('content')
  <div class="row">
    <!-- LEFT COLUMN: Profile & Context -->
    <div class="col-md-4">
      <!-- PROFILE CARD -->
      <div class="card card-modern mb-4">
        <div class="card-body text-center pt-5 pb-4">
          <div class="mb-3">
            <!-- Avatar Skeleton/Image -->
            <img src="{{ asset('adminlte3/dist/img/user4-128x128.jpg') }}" class="img-circle elevation-1"
              style="width: 100px; height: 100px; object-fit: cover; border: 4px solid var(--surface-ground);">
          </div>
          <h4 class="font-weight-bold mb-1">{{ $student->nama }}</h4>
          <div class="text-muted mb-3">{{ $student->nisn }}</div>

          <div class="d-flex justify-content-center mb-4">
            @if($student->status_kelulusan == 'lulus')
              <span class="badge badge-success px-3 py-2 rounded-pill">
                <i class="fas fa-user-graduate mr-1"></i> Alumni {{ $student->tahun_lulus }}
              </span>
            @else
              <span class="badge badge-info px-3 py-2 rounded-pill">
                <i class="fas fa-check-circle mr-1"></i> Siswa Aktif
              </span>
            @endif
          </div>

          <!-- MASKED NIK -->
          <div class="bg-light rounded p-3 text-left mb-3">
            <label class="text-xs text-uppercase text-muted font-weight-bold mb-1">NIK (Nomor Induk Kependudukan)</label>
            <div class="d-flex justify-content-between align-items-center">
              <code class="text-dark font-weight-bold" style="font-size: 1.1em;">
                                                     {{ substr($student->nik ?? '3201123456789000', 0, 4) }}********{{ substr($student->nik ?? '3201123456789000', -4) }}
                                                 </code>
              <i class="fas fa-shield-alt text-muted" title="Data secured"></i>
            </div>
          </div>

          <div class="row text-left">
            <div class="col-6 mb-2">
              <small class="text-muted d-block">Tahun Masuk</small>
              <span class="font-weight-bold">{{ $student->year_in ?? '-' }}</span>
            </div>
            <div class="col-6 mb-2">
              <small class="text-muted d-block">Orang Tua</small>
              <span class="font-weight-bold">{{ $student->parent_name ?? '-' }}</span>
            </div>
          </div>
        </div>
      </div>

      <!-- SCHOOL INFO CARD -->
      <div class="card card-modern mb-4">
        <div class="card-body">
          <h6 class="text-uppercase text-muted font-weight-bold text-xs mb-3">Informasi Sekolah</h6>
          <div class="d-flex align-items-center mb-3">
            <div class="bg-primary-light text-white rounded p-2 mr-3">
              <i class="fas fa-school fa-lg"></i>
            </div>
            <div>
              <h6 class="font-weight-bold mb-0">{{ $tenant->nama_sekolah }}</h6>
              <small class="text-muted">NPSN: {{ $tenant->npsn }}</small>
            </div>
          </div>
          <hr class="my-3">
          <a href="{{ route('superadmin.monitoring.school', $tenant->id) }}"
            class="btn btn-outline-primary btn-block btn-sm">
            <i class="fas fa-external-link-alt mr-1"></i> Lihat Sekolah
          </a>
        </div>
      </div>
    </div>

    <!-- RIGHT COLUMN: Documents & Activity -->
    <div class="col-md-8">

      <!-- COMPLETENESS & STATS -->
      <div class="card card-modern mb-4">
        <div class="card-body">
          <div class="row align-items-center">
            <div class="col-md-8">
              <h5 class="font-weight-bold mb-1">Kelengkapan Dokumen</h5>
              <p class="text-muted text-sm mb-2">Progress digitalisasi arsip siswa ini berdasarkan dokumen wajib.</p>
              <div class="progress" style="height: 10px; border-radius: 5px;">
                <div class="progress-bar {{ $completeness == 100 ? 'bg-success' : 'bg-warning' }}" role="progressbar"
                  style="width: {{ $completeness }}%;" aria-valuenow="{{ $completeness }}" aria-valuemin="0"
                  aria-valuemax="100"></div>
              </div>
              @if(!empty($missing_docs))
                <div class="mt-2 text-xs text-danger">
                  <i class="fas fa-exclamation-circle mr-1"></i> Belum diunggah:
                  @foreach($missing_docs as $missing)
                    <span class="badge badge-light border">{{ $missing }}</span>
                  @endforeach
                </div>
              @endif
            </div>
            <div class="col-md-4 text-right">
              <h2 class="font-weight-bold {{ $completeness == 100 ? 'text-success' : 'text-warning' }} mb-0">
                {{ $completeness }}%
              </h2>
              <small class="text-muted">Completed</small>
            </div>
          </div>
        </div>
      </div>

      <!-- DOCUMENT LIST (Modern) -->
      <div class="card card-modern mb-4">
        <div class="card-header d-flex justify-content-between align-items-center border-bottom-0 pb-0">
          <h5 class="font-weight-bold mb-0">Arsip Dokumen</h5>
          <span class="badge badge-secondary">{{ $student->documents->count() }} File</span>
        </div>
        <div class="card-body pt-2">
          @if($student->documents->isEmpty())
            <div class="text-center py-5 text-muted">
              <i class="fas fa-folder-open fa-3x mb-3 text-light"></i>
              <p>Belum ada dokumen yang diunggah.</p>
            </div>
          @else
            <div class="list-group list-group-flush">
              @foreach($student->documents as $doc)
                <div
                  class="list-group-item px-0 py-3 border-bottom d-flex flex-wrap align-items-center justify-content-between">
                  <div class="d-flex align-items-center col-12 col-md-auto p-0 mb-2 mb-md-0">
                    <div class="mr-3 text-center" style="width: 40px;">
                      @if($doc->is_verified)
                        <i class="fas fa-file-pdf text-danger fa-2x"></i>
                      @else
                        <i class="fas fa-file text-muted fa-2x"></i>
                      @endif
                    </div>
                    <div>
                      <h6 class="mb-1 font-weight-bold text-dark">{{ $doc->jenis_dokumen }}</h6>
                      <div class="small">
                        {{-- Validation Status Badge --}}
                        @if($doc->validation_status === 'approved')
                          <span class="badge badge-success"><i class="fas fa-check-circle mr-1"></i> Disetujui</span>
                        @elseif($doc->validation_status === 'rejected')
                          <span class="badge badge-danger"><i class="fas fa-times-circle mr-1"></i> Ditolak</span>
                        @else
                          <span class="badge badge-warning"><i class="fas fa-clock mr-1"></i> Menunggu Validasi</span>
                        @endif
                        <span class="text-muted mx-1">&bull;</span>
                        <span class="text-muted">{{ $doc->created_at->format('d M Y') }}</span>

                        {{-- Show validator info if validated --}}
                        @if($doc->validated_at)
                          <div class="text-xs text-muted mt-1">
                            <i class="fas fa-user-shield"></i>
                            {{ $doc->validator->name ?? 'Super Admin' }} - {{ $doc->validated_at->format('d M Y H:i') }}
                          </div>
                        @endif

                        {{-- Show rejection note --}}
                        @if($doc->validation_status === 'rejected' && $doc->validation_notes)
                          <div class="alert alert-danger alert-sm mt-2 p-2">
                            <small><strong>Alasan Penolakan:</strong> {{ $doc->validation_notes }}</small>
                          </div>
                        @endif
                      </div>
                    </div>
                  </div>
                  <div class="col-12 col-md-auto mt-2 mt-md-0">
                    <div class="btn-group" role="group">
                      {{-- Direct view link for Super Admin - no request needed --}}
                      <a href="{{ route('superadmin.monitoring.view_document', [$tenant->id, $student->id, $doc->id]) }}"
                        target="_blank" class="btn btn-sm btn-outline-primary">
                        <i class="fas fa-eye mr-1"></i> Lihat Dokumen
                      </a>

                      {{-- Validation Buttons --}}
                      @if($doc->validation_status === 'pending')
                        <form
                          action="{{ route('superadmin.monitoring.document.approve', [$tenant->id, $student->id, $doc->id]) }}"
                          method="POST" class="d-inline">
                          @csrf
                          <button type="submit" class="btn btn-sm btn-success" onclick="return confirm('Setujui dokumen ini?')">
                            <i class="fas fa-check"></i> Setujui
                          </button>
                        </form>
                        <button type="button" class="btn btn-sm btn-danger btn-reject-doc" data-doc-id="{{ $doc->id }}"
                          data-doc-name="{{ $doc->jenis_dokumen }}">
                          <i class="fas fa-times"></i> Tolak
                        </button>
                      @elseif($doc->validation_status === 'rejected')
                        <form
                          action="{{ route('superadmin.monitoring.document.approve', [$tenant->id, $student->id, $doc->id]) }}"
                          method="POST" class="d-inline">
                          @csrf
                          <button type="submit" class="btn btn-sm btn-success" onclick="return confirm('Setujui dokumen ini?')">
                            <i class="fas fa-redo"></i> Setujui
                          </button>
                        </form>
                      @endif
                    </div>
                  </div>
                </div>
              @endforeach
            </div>
          @endif
        </div>
      </div>

      <!-- ACTIVITY LOG PANEL -->
      <div class="card card-modern bg-light">
        <div class="card-header border-bottom-0">
          <h6 class="font-weight-bold text-muted mb-0 text-uppercase text-xs">Activity Log</h6>
        </div>
        <div class="card-body pt-0">
          <div class="timeline-simple">
            @forelse($logs as $log)
              <div class="media mb-3">
                <div class="mr-3">
                  <div class="bg-white rounded-circle d-flex align-items-center justify-content-center shadow-sm"
                    style="width: 32px; height: 32px;">
                    <i class="fas fa-eye text-primary text-xs"></i>
                  </div>
                </div>
                <div class="media-body">
                  <p class="mb-0 text-sm">
                    <span class="font-weight-bold">{{ $log->user->name ?? 'System' }}</span>
                    @if(isset($log->action))
                      @if($log->action == 'VIEW')
                        mengakses dokumen
                      @elseif($log->action == 'APPROVE')
                        menyetujui dokumen
                      @elseif($log->action == 'REJECT')
                        <span class="text-danger">menolak</span> dokumen
                      @else
                        melakukan aktivitas
                      @endif
                    @endif
                    <span class="font-weight-bold text-dark">{{ $log->document_name }}</span>
                    @if(isset($log->action) && $log->action == 'REJECT' && isset($log->details['notes']))
                      <div class="text-xs text-muted mt-1 italic">"{{ $log->details['notes'] }}"</div>
                    @endif
                  </p>
                  <small class="text-muted">{{ $log->created_at->diffForHumans() }}</small>
                </div>
              </div>
            @empty
              <p class="text-muted text-sm text-center py-2">Belum ada aktivitas tercatat.</p>
            @endforelse
          </div>
        </div>
      </div>

    </div>
  </div>

  <!-- REQUEST ACCESS MODAL -->
  <div class="modal fade" id="requestAccessModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
      <div class="modal-content border-0 shadow-lg" style="border-radius: 1rem;">
        <div class="modal-header border-bottom-0 pb-0">
          <h5 class="modal-title font-weight-bold">🔐 Request Document Access</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body pt-3">
          <div class="alert alert-light border shadow-sm rounded mb-3">
            <small class="text-muted d-block">Dokumen yang diminta:</small>
            <strong id="modalDocName" class="text-dark">Ijazah</strong>
          </div>
          <p class="text-muted text-sm mb-3">
            Dokumen ini bersifat rahasia. Aktivitas Anda akan dicatat dalam <b>Audit Log</b> negara.
            Silakan masukkan alasan akses untuk melanjutkan.
          </p>
          <form id="requestAccessForm">
            <input type="hidden" name="tenant_id" value="{{ $tenant->id }}">
            <input type="hidden" name="student_id" value="{{ $student->id }}">
            <input type="hidden" name="student_nisn" value="{{ $student->nisn }}">
            <input type="hidden" id="modalDocId" name="document_id">

            <div class="form-group">
              <label class="font-weight-bold text-xs uppercase">Alasan Akses <span class="text-danger">*</span></label>
              <textarea class="form-control bg-light border-0" name="reason" rows="3"
                placeholder="Contoh: Verifikasi keaslian data untuk mutasi siswa..." required></textarea>
            </div>
          </form>
        </div>
        <div class="modal-footer border-top-0 pt-0">
          <button type="button" class="btn btn-link text-muted" data-dismiss="modal">Batal</button>
          <button type="button" class="btn btn-primary px-4 shadow-sm" id="btnSubmitRequest">
            <i class="fas fa-paper-plane mr-1"></i> Kirim & Buka Dokumen
          </button>
        </div>
      </div>
    </div>
  </div>

  {{-- REJECT DOCUMENT MODAL --}}
  <div class="modal fade" id="rejectDocModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
      <div class="modal-content border-0 shadow-lg" style="border-radius: 1rem;">
        <div class="modal-header border-bottom-0 pb-0 bg-danger text-white" style="border-radius: 1rem 1rem 0 0;">
          <h5 class="modal-title font-weight-bold"><i class="fas fa-exclamation-triangle mr-2"></i> Tolak Dokumen</h5>
          <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body pt-3">
          <div class="alert alert-light border shadow-sm rounded mb-3">
            <small class="text-muted d-block">Dokumen:</small>
            <strong id="rejectDocName" class="text-dark">-</strong>
          </div>
          <form id="rejectDocForm" method="POST">
            @csrf
            <div class="form-group">
              <label class="font-weight-bold text-xs uppercase">Alasan Penolakan <span
                  class="text-danger">*</span></label>
              <textarea class="form-control border-danger" name="validation_notes" rows="4"
                placeholder="Jelaskan mengapa dokumen ini ditolak (max 500 karakter)..." required
                maxlength="500"></textarea>
              <small class="text-muted">Alasan ini akan dilihat oleh Admin Sekolah</small>
            </div>
          </form>
        </div>
        <div class="modal-footer border-top-0 pt-0">
          <button type="button" class="btn btn-link text-muted" data-dismiss="modal">Batal</button>
          <button type="button" class="btn btn-danger px-4 shadow-sm" id="btnConfirmReject">
            <i class="fas fa-ban mr-1"></i> Tolak Dokumen
          </button>
        </div>
      </div>
    </div>
  </div>

@endsection

@push('scripts')
  <script>
    $(document).ready(function () {
      // Open Modal
      $('.btn-request-access').on('click', function () {
        let docId = $(this).data('doc-id');
        let docName = $(this).data('doc-name');

        $('#modalDocId').val(docId);
        $('#modalDocName').text(docName);
        $('#requestAccessModal').modal('show');
      });

      // Handle Submit
      $('#btnSubmitRequest').on('click', function () {
        let form = $('#requestAccessForm');
        let btn = $(this);

        // Simple validation
        if (form.find('textarea').val().length < 5) {
          alert('Mohon isi alasan dengan jelas (minimal 5 karakter).');
          return;
        }

        btn.prop('disabled', true).html('<i class="fas fa-spinner fa-spin mr-1"></i> Processing...');

        // AJAX Request using correct route
        $.ajax({
          url: "{{ route('superadmin.monitoring.document_access.request') }}",
          method: "POST",
          data: form.serialize() + "&_token={{ csrf_token() }}",
          success: function (response) {
            $('#requestAccessModal').modal('hide');
            // Open document in new tab
            window.open(response.url, '_blank');
            // Reload page to show log
            location.reload();
          },
          error: function (xhr) {
            btn.prop('disabled', false).html('<i class="fas fa-paper-plane mr-1"></i> Kirim & Buka Dokumen');
            alert('Error: ' + xhr.responseText);
          }
        });
      });
      // Handle Document Rejection
      $('.btn-reject-doc').on('click', function () {
        let docId = $(this).data('doc-id');
        let docName = $(this).data('doc-name');

        $('#rejectDocName').text(docName);
        $('#rejectDocForm').attr('action', '/superadmin/monitoring/{{ $tenant->id }}/student/{{ $student->id }}/document/' + docId + '/reject');
        $('#rejectDocModal').modal('show');
      });

      $('#btnConfirmReject').on('click', function () {
        let form = $('#rejectDocForm');
        let notes = form.find('textarea').val();

        if (notes.trim().length < 10) {
          alert('Alasan penolakan minimal 10 karakter.');
          return;
        }

        form.submit();
      });
    });
  </script>
@endpush