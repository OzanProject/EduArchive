@extends('backend.layouts.app')

@section('title', 'Fakta Integritas')

@section('content')
<div class="row">
    <div class="col-12">
        <div class="card mb-4">
            <div class="card-header pb-0 d-flex justify-content-between align-items-center">
                <h6>Daftar Fakta Integritas</h6>
                @php
                    $roleRoute = auth()->user()->role === 'operator' ? 'operator' : 'adminlembaga';
                @endphp
                <a href="{{ route($roleRoute . '.integrity-pacts.create', ['tenant' => tenant('id')]) }}" class="btn btn-primary btn-sm mb-0">
                    <i class="fas fa-plus"></i> Upload Fakta Integritas
                </a>
            </div>
            <div class="card-body px-0 pt-0 pb-2">
                <div class="table-responsive p-0">
                    <table class="table align-items-center mb-0 text-sm">
                        <thead>
                            <tr>
                                <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">No</th>
                                <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Waktu Upload</th>
                                <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Judul Berkas</th>
                                <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Status</th>
                                <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Catatan Super Admin</th>
                                <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($pacts as $index => $pact)
                                <tr>
                                    <td class="align-middle px-4">
                                        <p class="text-xs font-weight-bold mb-0">{{ $pacts->firstItem() + $index }}</p>
                                    </td>
                                    <td class="align-middle px-4">
                                        <p class="text-xs mb-0">{{ $pact->created_at->format('d M Y H:i') }}</p>
                                    </td>
                                    <td class="align-middle px-4">
                                        <p class="text-xs font-weight-bold mb-0">{{ $pact->title }}</p>
                                    </td>
                                    <td class="align-middle px-4">
                                        @if($pact->status === 'approved')
                                            <span class="badge badge-sm bg-gradient-success">Approved</span>
                                        @elseif($pact->status === 'rejected')
                                            <span class="badge badge-sm bg-gradient-danger">Rejected</span>
                                        @else
                                            <span class="badge badge-sm bg-gradient-warning">Pending</span>
                                        @endif
                                    </td>
                                    <td class="align-middle px-4">
                                        <p class="text-xs text-secondary mb-0 text-wrap" style="max-width: 250px;">
                                            {{ $pact->status_notes ?? '-' }}
                                        </p>
                                    </td>
                                    <td class="align-middle text-center">
                                        <a href="{{ tenant_asset($pact->file_path) }}" target="_blank" class="text-secondary font-weight-bold text-xs me-3" data-bs-toggle="tooltip" data-bs-title="Lihat Berkas">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                        @if($pact->status !== 'approved')
                                            <a href="{{ route($roleRoute . '.integrity-pacts.edit', ['tenant' => tenant('id'), 'integrity_pact' => $pact->id]) }}" class="text-secondary font-weight-bold text-xs me-3" data-bs-toggle="tooltip" data-bs-title="Edit/Revisi">
                                                <i class="fas fa-edit"></i>
                                            </a>
                                            <form action="{{ route($roleRoute . '.integrity-pacts.destroy', ['tenant' => tenant('id'), 'integrity_pact' => $pact->id]) }}" method="POST" style="display: inline-block;">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="border-0 bg-transparent text-secondary font-weight-bold text-xs p-0" onclick="return confirm('Apakah Anda yakin ingin menghapus beras ini?')" data-bs-toggle="tooltip" data-bs-title="Hapus">
                                                    <i class="fas fa-trash text-danger"></i>
                                                </button>
                                            </form>
                                        @endif
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="text-center py-4">
                                        <p class="text-sm font-weight-bold mb-0">Belum ada dokumen Fakta Integritas yang diunggah.</p>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                <!-- Pagination -->
                <div class="px-4 py-3">
                    {{ $pacts->links() }}
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
