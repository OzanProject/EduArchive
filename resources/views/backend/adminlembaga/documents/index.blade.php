@php
  $prefix = request()->routeIs('operator.*') ? 'operator.' : 'adminlembaga.';
@endphp
@extends('backend.layouts.app')

@section('title', 'Manajemen Dokumen')
@section('page_title', 'Data Dokumen Siswa')

@section('breadcrumb')
  <li class="breadcrumb-item"><a href="{{ route($prefix . 'dashboard') }}">Dashboard</a></li>
  <li class="breadcrumb-item active">Dokumen Siswa</li>
@endsection

@section('content')
  <div class="row">
    <div class="col-12">
      <div class="card">
        <div class="card-header">
          <h3 class="card-title">Daftar Dokumen</h3>
          <div class="card-tools">
            <a href="{{ route($prefix . 'documents.create', ['student_id' => request('student_id')]) }}"
              class="btn btn-primary btn-sm">
              <i class="fas fa-upload"></i> Upload Dokumen
            </a>
          </div>
        </div>
        <div class="card-body">
          <ul class="nav nav-tabs mb-3">
            <li class="nav-item">
              <a class="nav-link {{ $status == 'Aktif' ? 'active' : '' }}"
                href="{{ route($prefix . 'documents.index', ['status' => 'Aktif']) }}">
                <i class="fas fa-user-graduate"></i> Siswa Aktif
              </a>
            </li>
            <li class="nav-item">
              <a class="nav-link {{ $status == 'Lulus' ? 'active' : '' }}"
                href="{{ route($prefix . 'documents.index', ['status' => 'Lulus']) }}">
                <i class="fas fa-graduation-cap"></i> Siswa Lulusan
              </a>
            </li>
          </ul>
          <table class="table table-hover text-nowrap">
            <thead>
              <tr>
                <th>No</th>
                <th>Nama Siswa</th>
                <th>Jenis Dokumen</th>
                <th>Nama File</th>
                <th>Ukuran</th>
                <th>Status Validasi</th>
                <th>Diupload Oleh</th>
                <th>Aksi</th>
              </tr>
            </thead>
            <tbody>
              @forelse($documents as $document)
                <tr>
                  <td>{{ $loop->iteration + ($documents->currentPage() - 1) * $documents->perPage() }}</td>
                  <td>
                    @if($document->student)
                      <a href="{{ route($prefix . 'students.edit', $document->student_id) }}">
                        {{ $document->student->nama }}
                      </a>
                    @else
                      <span class="text-danger">Siswa dihapus</span>
                    @endif
                  </td>
                  <td>{{ $document->document_type }}</td>
                  <td>
                    <a href="{{ route($prefix . 'documents.show', $document->id) }}" target="_blank">
                      <i class="fas fa-file-alt"></i> Lihat File
                    </a>
                  </td>
                  <td>{{ number_format($document->file_size / 1024, 2) }} KB</td>
                  <td>
                    {{-- Validation Status Badge --}}
                    @if($document->validation_status === 'approved')
                      <span class="badge badge-success">
                        <i class="fas fa-check-circle"></i> Disetujui
                      </span>
                      @if($document->validated_at)
                        <br><small class="text-muted">{{ $document->validated_at->format('d/m/Y H:i') }}</small>
                      @endif
                    @elseif($document->validation_status === 'rejected')
                      <span class="badge badge-danger">
                        <i class="fas fa-times-circle"></i> Ditolak
                      </span>
                      @if($document->validation_notes)
                        <br><small class="text-danger"><strong>Alasan:</strong>
                          {{ Str::limit($document->validation_notes, 50) }}</small>
                      @endif
                      @if($document->validated_at)
                        <br><small class="text-muted">{{ $document->validated_at->format('d/m/Y H:i') }}</small>
                      @endif
                    @else
                      <span class="badge badge-warning">
                        <i class="fas fa-clock"></i> Menunggu Validasi
                      </span>
                      <br><small class="text-muted">Belum divalidasi Super Admin</small>
                    @endif
                  </td>
                  <td>{{ $document->uploader ? $document->uploader->name : '-' }}</td>
                  <td>
                    <form action="{{ route($prefix . 'documents.destroy', $document->id) }}" method="POST"
                      style="display:inline-block;">
                      @csrf
                      @method('DELETE')
                      <button type="submit" class="btn btn-danger btn-xs"
                        onclick="return confirm('Yakin ingin menghapus dokumen ini?')">
                        <i class="fas fa-trash"></i>
                      </button>
                    </form>
                  </td>
                </tr>
              @empty
                <tr>
                  <td colspan="8" class="text-center">Belum ada dokumen diupload.</td>
                </tr>
              @endforelse
            </tbody>
          </table>
        </div>
        <!-- /.card-body -->
        <div class="card-footer clearfix">
          {{ $documents->links() }}
        </div>
      </div>
      <!-- /.card -->
    </div>
  </div>
@endsection