@php
  $prefix = request()->routeIs('operator.*') ? 'operator.' : 'adminlembaga.';
@endphp
@extends('backend.layouts.app')

@section('title', $pageTitle ?? 'Manajemen Siswa')
@section('page_title', $pageTitle ?? 'Data Siswa')

@section('breadcrumb')
  <li class="breadcrumb-item"><a href="{{ route($prefix . 'dashboard') }}">Dashboard</a></li>
  <li class="breadcrumb-item active">{{ $pageTitle ?? 'Data Siswa' }}</li>
@endsection

@section('content')
  <div class="row">
    <div class="col-12">
      <div class="card">
        <div class="card-header">
          <h3 class="card-title">{{ $pageTitle ?? 'Daftar Siswa' }}</h3>
          <div class="card-tools d-flex flex-wrap justify-content-end" style="gap: 5px; row-gap: 5px;">
            <button class="btn btn-danger btn-sm d-none" id="btn-bulk-delete" onclick="bulkDelete()">
              <i class="fas fa-trash"></i> Hapus Masal
            </button>
            <button class="btn btn-info btn-sm d-none" id="btn-bulk-print" onclick="bulkPrint()">
              <i class="fas fa-print"></i> Cetak Masal
            </button>
            @if($status == 'Aktif')
            <button class="btn btn-warning btn-sm d-none" id="btn-bulk-promote" data-toggle="modal"
              data-target="#promoteModal">
              <i class="fas fa-level-up-alt"></i> Naik Kelas (Terpilih)
            </button>
            <button class="btn btn-warning btn-sm" data-toggle="modal"
              data-target="#promoteRombelModal">
              <i class="fas fa-layer-group"></i> Naik Kelas (Rombel)
            </button>
            <button class="btn btn-secondary btn-sm d-none" id="btn-bulk-graduate" data-toggle="modal"
              data-target="#graduateModal">
              <i class="fas fa-user-graduate"></i> Luluskan (Terpilih)
            </button>
            <button class="btn btn-secondary btn-sm" data-toggle="modal"
              data-target="#graduateRombelModal">
              <i class="fas fa-graduation-cap"></i> Luluskan (Rombel)
            </button>
            @elseif($status == 'Lulus')
            <button class="btn btn-warning btn-sm d-none" id="btn-bulk-cancel-graduate" onclick="bulkCancelGraduate()">
              <i class="fas fa-undo"></i> Batalkan Lulus (Terpilih)
            </button>
            @endif
            <a href="{{ route($prefix . 'students.create') }}" class="btn btn-primary btn-sm">
              <i class="fas fa-plus"></i> Tambah Baru
            </a>
            <button type="button" class="btn btn-success btn-sm" data-toggle="modal" data-target="#importModal">
              <i class="fas fa-file-excel"></i> Import Excel
            </button>
          </div>
        </div>
        <!-- /.card-header -->
        <div class="card-body table-responsive p-0">
          {{-- Search Filter --}}
          <div class="p-3 pb-0">
            <form action="{{ route($prefix . 'students.index') }}" method="GET" class="mb-3">
              <input type="hidden" name="status" value="{{ $status }}">
              <div class="row">
                <div class="col-md-4">
                  <div class="input-group input-group-sm">
                    <input type="text" name="search" class="form-control" placeholder="Cari NISN atau Nama Siswa..."
                      value="{{ request('search') }}">
                    <div class="input-group-append">
                      <button type="submit" class="btn btn-primary">
                        <i class="fas fa-search"></i> Cari
                      </button>
                    </div>
                  </div>
                </div>
                @if($status == 'Lulus')
                  <div class="col-md-3">
                    <div class="input-group input-group-sm">
                      <div class="input-group-prepend">
                        <span class="input-group-text"><i class="fas fa-calendar-alt"></i></span>
                      </div>
                      <select name="tahun_lulus" class="form-control" onchange="this.form.submit()">
                        <option value="">-- Semua Tahun Lulus --</option>
                        {{-- $years is now passed from the controller --}}
                        @foreach($years as $year)
                          <option value="{{ $year }}" {{ request('tahun_lulus') == $year ? 'selected' : '' }}>{{ $year }}
                          </option>
                        @endforeach
                      </select>
                    </div>
                  </div>
                @endif
                <div class="col-md-2">
                  @if(request('search') || request('tahun_lulus'))
                    <a href="{{ route($prefix . 'students.index', ['status' => $status]) }}" class="btn btn-default btn-sm">
                      <i class="fas fa-times"></i> Reset Filter
                    </a>
                  @endif
                </div>
              </div>
            </form>
          </div>
          <table class="table table-hover text-nowrap">
            <thead>
              <tr>
                <th style="width: 10px">
                  <input type="checkbox" id="checkAll">
                </th>
                <th>No</th>
                <th>Foto</th>
                <th>Nama Lengkap</th>
                <th>L/P</th>
                @if($status == 'Lulus')
                  <th>Tahun Lulus</th>
                  <th>No Ijazah</th>
                @else
                  <th>Kelas</th>
                @endif
                <th>NISN</th>
                <th>NIK</th>
                <th>No. HP</th>
                <th>Status</th>
                <th>Aksi</th>
              </tr>
            </thead>
            <tbody>
              <form id="bulk-action-form" action="" method="POST" style="display: none;">
                @csrf
              </form>
              @forelse($students as $student)
                <tr>
                  <td>
                    <input type="checkbox" class="checkItem" name="ids[]" value="{{ $student->id }}">
                  </td>
                  <td>{{ $loop->iteration + ($students->currentPage() - 1) * $students->perPage() }}</td>
                  <td>
                    @if($student->foto_profil)
                      <img src="{{ tenant_asset($student->foto_profil) }}" alt="Foto" class="img-circle"
                        style="width: 30px; height: 30px; object-fit: cover;">
                    @else
                      <img src="{{ asset('adminlte3/dist/img/user2-160x160.jpg') }}" alt="Default" class="img-circle"
                        style="width: 30px; height: 30px; object-fit: cover;">
                    @endif
                  </td>
                  <td>{{ $student->nama }}</td>
                  <td>{{ $student->gender ?? '-' }}</td>
                  @if($status == 'Lulus')
                    <td>{{ $student->tahun_lulus ?? '-' }}</td>
                    <td><span class="badge badge-info">{{ $student->no_seri_ijazah ?? '-' }}</span></td>
                  @else
                    <td>{{ $student->classroom ? $student->classroom->nama_kelas : ($student->kelas ?? '-') }}</td>
                  @endif
                  <td>{{ $student->nisn ?? '-' }}</td>
                  <td>{{ $student->nik ?? '-' }}</td>
                  <td>{{ $student->no_hp ?? '-' }}</td>
                  <td>
                    <span class="badge badge-{{ $student->status_kelulusan == 'Aktif' ? 'success' : 'secondary' }}">
                      {{ $student->status_kelulusan }}
                    </span>
                  </td>
                  <td>
                    <div class="btn-group">
                      <button type="button" class="btn btn-primary btn-xs dropdown-toggle" data-toggle="dropdown">
                        <i class="fas fa-file-upload"></i> Dokumen
                      </button>
                      <div class="dropdown-menu">
                        <a class="dropdown-item"
                          href="{{ route($prefix . 'documents.create', ['student_id' => $student->id, 'type' => 'Kartu Keluarga']) }}">Upload
                          KK</a>
                        <a class="dropdown-item"
                          href="{{ route($prefix . 'documents.create', ['student_id' => $student->id, 'type' => 'KTP']) }}">Upload
                          KTP</a>
                        <a class="dropdown-item"
                          href="{{ route($prefix . 'documents.create', ['student_id' => $student->id, 'type' => 'Ijazah']) }}">Upload
                          Ijazah</a>
                        <div class="dropdown-divider"></div>
                        <a class="dropdown-item"
                          href="{{ route($prefix . 'documents.index', ['student_id' => $student->id]) }}">Lihat Semua</a>
                      </div>
                    </div>
                    <a href="{{ route($prefix . 'students.print', $student->id) }}" class="btn btn-info btn-sm"
                      target="_blank" title="Cetak Biodata">
                      <i class="fas fa-print"></i>
                    </a>
                    <a href="{{ route($prefix . 'students.edit', $student->id) }}" class="btn btn-warning btn-sm"
                      title="Edit">
                      <i class="fas fa-edit"></i>
                    </a>
                    @if($status == 'Lulus')
                    <form action="{{ route($prefix . 'students.cancelGraduate', $student->id) }}" method="POST"
                      style="display:inline-block;">
                      @csrf
                      <button type="submit" class="btn btn-secondary btn-sm" title="Batalkan Kelulusan"
                        onclick="return confirm('Yakin ingin membatalkan kelulusan siswa ini dan mengembalikannya menjadi siswa aktif?')">
                        <i class="fas fa-undo"></i>
                      </button>
                    </form>
                    @endif
                    <form action="{{ route($prefix . 'students.destroy', $student->id) }}" method="POST"
                      style="display:inline-block;">
                      @csrf
                      @method('DELETE')
                      <button type="submit" class="btn btn-danger btn-xs"
                        onclick="return confirm('Yakin ingin menghapus siswa ini?')">
                        <i class="fas fa-trash"></i>
                      </button>
                    </form>
                  </td>
                </tr>
              @empty
                <tr>
                  <td colspan="10" class="text-center">Belum ada data siswa.</td>
                </tr>
              @endforelse
            </tbody>
          </table>
        </div>
        <!-- /.card-body -->
        <div class="card-footer clearfix">
          {{ $students->links() }}
        </div>
      </div>
      <!-- /.card -->
    </div>
  </div>

  @include('backend.adminlembaga.students.import_modal')

  <!-- Modal Promote -->
  <div class="modal fade" id="promoteModal" tabindex="-1" role="dialog" aria-labelledby="promoteModalLabel"
    aria-hidden="true">
    <div class="modal-dialog" role="document">
      <div class="modal-content">
        <form id="promote-form" action="{{ route($prefix . 'students.bulkPromote') }}" method="POST">
          @csrf
          <div class="modal-header">
            <h5 class="modal-title" id="promoteModalLabel">Naik Kelas Masal</h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">&times;</span>
            </button>
          </div>
          <div class="modal-body">
            <p>Pilih kelas tujuan untuk siswa yang dipilih:</p>
            <div class="form-group">
              <label>Kelas Tujuan</label>
              <select name="target_classroom_id" class="form-control select2" style="width: 100%;" required>
                <option value="">-- Pilih Kelas --</option>
                @foreach($classroomsList as $classroom)
                  <option value="{{ $classroom->id }}">{{ $classroom->nama_kelas }} ({{ $classroom->tahun_ajaran }})
                  </option>
                @endforeach
              </select>
            </div>
            <div id="promote-ids"></div>
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
            <button type="button" class="btn btn-primary" onclick="submitPromote()">Simpan</button>
          </div>
        </form>
      </div>
    </div>
  </div>

  <!-- Modal Promote Rombel -->
  <div class="modal fade" id="promoteRombelModal" tabindex="-1" role="dialog" aria-labelledby="promoteRombelModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
      <div class="modal-content">
        <form action="{{ route($prefix . 'students.bulkPromoteRombel') }}" method="POST">
          @csrf
          <div class="modal-header">
            <h5 class="modal-title" id="promoteRombelModalLabel">Naik Kelas Masal (Per Rombel)</h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">&times;</span>
            </button>
          </div>
          <div class="modal-body">
            <p>Pindahkan <strong>semua siswa aktif</strong> dari satu kelas ke kelas lainnya secara langsung:</p>
            <div class="form-group">
              <label>Kelas Asal</label>
              <select name="source_classroom_id" class="form-control select2" style="width: 100%;" required>
                <option value="">-- Pilih Kelas Asal --</option>
                @foreach($classroomsList as $classroom)
                  <option value="{{ $classroom->id }}">{{ $classroom->nama_kelas }} ({{ $classroom->tahun_ajaran }})</option>
                @endforeach
              </select>
            </div>
            <div class="form-group">
              <label>Kelas Tujuan</label>
              <select name="target_classroom_id" class="form-control select2" style="width: 100%;" required>
                <option value="">-- Pilih Kelas Tujuan --</option>
                @foreach($classroomsList as $classroom)
                  <option value="{{ $classroom->id }}">{{ $classroom->nama_kelas }} ({{ $classroom->tahun_ajaran }})</option>
                @endforeach
              </select>
            </div>
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
            <button type="submit" class="btn btn-primary" onclick="return confirm('Apakah Anda yakin ingin memindahkan seluruh siswa di kelas asal ke kelas tujuan?')">Simpan</button>
          </div>
        </form>
      </div>
    </div>
  </div>

  <!-- Modal Graduate -->
  <div class="modal fade" id="graduateModal" tabindex="-1" role="dialog" aria-labelledby="graduateModalLabel"
    aria-hidden="true">
    <div class="modal-dialog" role="document">
      <div class="modal-content">
        <form id="graduate-form" action="{{ route($prefix . 'students.bulkGraduate') }}" method="POST">
          @csrf
          <div class="modal-header">
            <h5 class="modal-title" id="graduateModalLabel">Luluskan Siswa Masal</h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">&times;</span>
            </button>
          </div>
          <div class="modal-body">
            <p>Masukkan tahun lulus untuk siswa yang dipilih:</p>
            <div class="form-group">
              <label>Tahun Lulus</label>
              <input type="number" name="graduation_year" class="form-control" value="{{ date('Y') }}" required>
            </div>
            <div id="graduate-ids"></div>
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
            <button type="button" class="btn btn-primary" onclick="submitGraduate()">Simpan</button>
          </div>
        </form>
      </div>
    </div>
  </div>

  <!-- Modal Graduate Rombel -->
  <div class="modal fade" id="graduateRombelModal" tabindex="-1" role="dialog" aria-labelledby="graduateRombelModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
      <div class="modal-content">
        <form action="{{ route($prefix . 'students.bulkGraduateRombel') }}" method="POST">
          @csrf
          <div class="modal-header">
            <h5 class="modal-title" id="graduateRombelModalLabel">Luluskan Siswa Masal (Per Rombel)</h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">&times;</span>
            </button>
          </div>
          <div class="modal-body">
            <p>Luluskan <strong>semua siswa aktif</strong> dalam satu kelas sekaligus:</p>
            <div class="form-group">
              <label>Kelas yang Diluluskan</label>
              <select name="source_classroom_id" class="form-control select2" style="width: 100%;" required>
                <option value="">-- Pilih Kelas --</option>
                @foreach($classroomsList as $classroom)
                  <option value="{{ $classroom->id }}">{{ $classroom->nama_kelas }} ({{ $classroom->tahun_ajaran }})</option>
                @endforeach
              </select>
            </div>
            <div class="form-group">
              <label>Tahun Lulus</label>
              <input type="number" name="graduation_year" class="form-control" value="{{ date('Y') }}" required>
            </div>
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
            <button type="submit" class="btn btn-primary" onclick="return confirm('Apakah Anda yakin ingin meluluskan seluruh siswa di kelas ini?')">Simpan</button>
          </div>
        </form>
      </div>
    </div>
  </div>

  @push('scripts')
    <script>
      $('#checkAll').click(function () {
        $('.checkItem').prop('checked', this.checked);
        toggleBulkButtons();
      });

      $('.checkItem').change(function () {
        if ($('.checkItem:checked').length == $('.checkItem').length) {
          $('#checkAll').prop('checked', true);
        } else {
          $('#checkAll').prop('checked', false);
        }
        toggleBulkButtons();
      });

      function toggleBulkButtons() {
        if ($('.checkItem:checked').length > 0) {
          $('#btn-bulk-delete').removeClass('d-none');
          $('#btn-bulk-print').removeClass('d-none');
          @if($status == 'Aktif')
          $('#btn-bulk-promote').removeClass('d-none');
          $('#btn-bulk-graduate').removeClass('d-none');
          @elseif($status == 'Lulus')
          $('#btn-bulk-cancel-graduate').removeClass('d-none');
          @endif
        } else {
          $('#btn-bulk-delete').addClass('d-none');
          $('#btn-bulk-print').addClass('d-none');
          @if($status == 'Aktif')
          $('#btn-bulk-promote').addClass('d-none');
          $('#btn-bulk-graduate').addClass('d-none');
          @elseif($status == 'Lulus')
          $('#btn-bulk-cancel-graduate').addClass('d-none');
          @endif
        }
      }

      function bulkDelete() {
        if (!confirm('Yakin ingin menghapus data yang dipilih?')) return;

        var ids = [];
        $('.checkItem:checked').each(function () {
          ids.push($(this).val());
        });

        // Use a strict form submission for DELETE
        var form = $('#bulk-action-form');
        form.attr('action', '{{ route($prefix . "students.bulkDestroy") }}');
        form.empty(); // clear previous inputs
        form.append('@csrf'); // append CSRF again

        $.each(ids, function (index, value) {
          form.append('<input type="hidden" name="ids[]" value="' + value + '">');
        });

        form.submit();
      }

      function bulkPrint() {
        var ids = [];
        $('.checkItem:checked').each(function () {
          ids.push($(this).val());
        });

        if (ids.length === 0) return;

        var url = '{{ route($prefix . "students.bulkPrint") }}' + '?ids=' + ids.join(',');
        window.open(url, '_blank');
      }

      function submitPromote() {
        var ids = [];
        $('.checkItem:checked').each(function () {
          ids.push($(this).val());
        });

        if (ids.length === 0) return;

        var container = $('#promote-ids');
        container.empty();
        $.each(ids, function (index, value) {
          container.append('<input type="hidden" name="ids[]" value="' + value + '">');
        });

        // AJAX submission to handle JSON response
        $.ajax({
          url: $('#promote-form').attr('action'),
          method: 'POST',
          data: $('#promote-form').serialize(),
          success: function (response) {
            if (response.success) {
              alert(response.message);
              location.reload();
            } else {
              alert('Terjadi kesalahan.');
            }
          },
          error: function (xhr) {
            alert('Error: ' + xhr.responseText);
          }
        });
      }

      function submitGraduate() {
        var ids = [];
        $('.checkItem:checked').each(function () {
          ids.push($(this).val());
        });

        if (ids.length === 0) return;

        var container = $('#graduate-ids');
        container.empty();
        $.each(ids, function (index, value) {
          container.append('<input type="hidden" name="ids[]" value="' + value + '">');
        });

        // AJAX submission
        $.ajax({
          url: $('#graduate-form').attr('action'),
          method: 'POST',
          data: $('#graduate-form').serialize(),
          success: function (response) {
            if (response.success) {
              alert(response.message);
              location.reload();
            } else {
              alert('Terjadi kesalahan.');
            }
          },
          error: function (xhr) {
            alert('Error: ' + xhr.responseText);
          }
        });
      }

      function bulkCancelGraduate() {
        if (!confirm('Yakin ingin membatalkan kelulusan data yang dipilih? Mereka akan dikembalikan menjadi Siswa Aktif.')) return;

        var ids = [];
        $('.checkItem:checked').each(function () {
          ids.push($(this).val());
        });

        if (ids.length === 0) return;

        // Use a strict form submission for Bulk Cancel Graduate
        var form = $('#bulk-action-form');
        form.attr('action', '{{ route($prefix . "students.bulkCancelGraduate") }}');
        form.empty(); // clear previous inputs
        form.append('@csrf'); // append CSRF again

        $.each(ids, function (index, value) {
          form.append('<input type="hidden" name="ids[]" value="' + value + '">');
        });

        form.submit();
      }
    </script>
  @endpush
@endsection