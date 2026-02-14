@extends('backend.layouts.app')

@section('title', 'Manajemen Halaman')

@section('content')
  <section class="content-header">
    <div class="container-fluid">
      <div class="row mb-2">
        <div class="col-sm-6">
          <h1>Manajemen Halaman</h1>
        </div>
        <div class="col-sm-6">
          <ol class="breadcrumb float-sm-right">
            <li class="breadcrumb-item"><a href="{{ route('superadmin.dashboard') }}">Dashboard</a></li>
            <li class="breadcrumb-item active">Halaman</li>
          </ol>
        </div>
      </div>
    </div>
  </section>

  <section class="content">
    <div class="container-fluid">
      <div class="row">
        <div class="col-12">
          <div class="card card-outline card-primary">
            <div class="card-header">
              <h3 class="card-title">Daftar Halaman Statis</h3>
              <div class="card-tools">
                <a href="{{ route('superadmin.pages.create') }}" class="btn btn-primary btn-sm">
                  <i class="fas fa-plus"></i> Tambah Halaman
                </a>
              </div>
            </div>
            <div class="card-body">
              <table class="table table-bordered table-hover">
                <thead>
                  <tr>
                    <th style="width: 50px">No</th>
                    <th>Judul</th>
                    <th>Slug / URL</th>
                    <th>Status</th>
                    <th>Terakhir Update</th>
                    <th style="width: 150px">Aksi</th>
                  </tr>
                </thead>
                <tbody>
                  @forelse($pages as $page)
                    <tr>
                      <td>{{ $loop->iteration }}</td>
                      <td>{{ $page->title }}</td>
                      <td>
                        <code>/p/{{ $page->slug }}</code>
                        <a href="{{ url('/p/' . $page->slug) }}" target="_blank" class="ml-1 text-muted"><i
                            class="fas fa-external-link-alt"></i></a>
                      </td>
                      <td>
                        @if ($page->is_published)
                          <span class="badge badge-success">Published</span>
                        @else
                          <span class="badge badge-secondary">Draft</span>
                        @endif
                      </td>
                      <td>{{ $page->updated_at->format('d M Y H:i') }}</td>
                      <td>
                        <a href="{{ route('superadmin.pages.edit', $page->id) }}" class="btn btn-warning btn-sm">
                          <i class="fas fa-edit"></i>
                        </a>
                        <form action="{{ route('superadmin.pages.destroy', $page->id) }}" method="POST" class="d-inline"
                          onsubmit="return confirm('Yakin ingin menghapus halaman ini?')">
                          @csrf
                          @method('DELETE')
                          <button type="submit" class="btn btn-danger btn-sm">
                            <i class="fas fa-trash"></i>
                          </button>
                        </form>
                      </td>
                    </tr>
                  @empty
                    <tr>
                      <td colspan="6" class="text-center">Belum ada halaman yang dibuat.</td>
                    </tr>
                  @endforelse
                </tbody>
              </table>
            </div>
          </div>
        </div>
      </div>
    </div>
  </section>
@endsection