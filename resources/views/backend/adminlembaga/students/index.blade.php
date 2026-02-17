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
          <div class="card-tools">
            <button class="btn btn-danger btn-sm d-none" id="btn-bulk-delete" onclick="bulkDelete()">
              <i class="fas fa-trash"></i> Hapus Masal
            </button>
            <button class="btn btn-info btn-sm d-none ml-1" id="btn-bulk-print" onclick="bulkPrint()">
              <i class="fas fa-print"></i> Cetak Masal
            </button>
            <button class="btn btn-warning btn-sm d-none ml-1" id="btn-bulk-promote" data-toggle="modal"
              data-target="#promoteModal">
              <i class="fas fa-level-up-alt"></i> Naik Kelas
            </button>
            <button class="btn btn-secondary btn-sm d-none ml-1" id="btn-bulk-graduate" data-toggle="modal"
              data-target="#graduateModal">
              <i class="fas fa-user-graduate"></i> Luluskan
            </button>
            <a href="{{ route($prefix . 'students.create') }}" class="btn btn-primary btn-sm ml-2">
              <i class="fas fa-plus"></i> Tambah Baru
            </a>
            <button type="button" class="btn btn-success btn-sm ml-2" data-toggle="modal" data-target="#importModal">
              <i class="fas fa-file-excel"></i> Import Excel
            </button>
          </div>
        </div>
        <!-- /.card-header -->
        <div class="card-body table-responsive p-0">
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
          {{ $students->appends(['status' => $status])->links() }}
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
                @foreach(\App\Models\Classroom::where('is_active', true)->orderBy('nama_kelas')->get() as $classroom)
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
          $('#btn-bulk-promote').removeClass('d-none');
          $('#btn-bulk-graduate').removeClass('d-none');
        } else {
          $('#btn-bulk-delete').addClass('d-none');
          $('#btn-bulk-print').addClass('d-none');
          $('#btn-bulk-promote').addClass('d-none');
          $('#btn-bulk-graduate').addClass('d-none');
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
        form.attr('action', '{{ route("adminlembaga.students.bulkDestroy") }}');
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

        var url = '{{ route("adminlembaga.students.bulkPrint") }}' + '?ids=' + ids.join(',');
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
    </script>
  @endpush
@endsection