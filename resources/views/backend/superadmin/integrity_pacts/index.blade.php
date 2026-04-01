@extends('backend.layouts.app')

@section('title', 'Monitoring Fakta Integritas')

@section('content')
<div class="row">
    <div class="col-12">
        <div class="card mb-4">
            <div class="card-header pb-0 border-bottom-0">
                <h6 class="mb-0">Daftar Fakta Integritas Lembaga</h6>
                <p class="text-sm mb-0">Kelola dan tinjau berkas fakta integritas yang diunggah oleh setiap lembaga/sekolah.</p>
            </div>
            <div class="card-body px-0 pt-0 pb-2">
                <div class="table-responsive p-0">
                    <table class="table align-items-center mb-0 text-sm">
                        <thead>
                            <tr>
                                <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">No</th>
                                <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Lembaga</th>
                                <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Judul Berkas</th>
                                <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Waktu Upload</th>
                                <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Status</th>
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
                                        <p class="text-xs font-weight-bold mb-0">{{ $pact->tenant->nama_sekolah ?? 'Unknown Tenant' }}</p>
                                        <p class="text-xs text-secondary mb-0">{{ $pact->tenant->admin_email ?? '-' }}</p>
                                    </td>
                                    <td class="align-middle px-4">
                                        <p class="text-xs font-weight-bold mb-0">{{ $pact->title }}</p>
                                    </td>
                                    <td class="align-middle px-4">
                                        <p class="text-xs mb-0">{{ $pact->created_at->format('d M Y H:i') }}</p>
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
                                    <td class="align-middle text-center">
                                        <a href="{{ route('superadmin.monitoring.integrity-pacts.show', $pact->id) }}" class="btn btn-sm btn-info mb-0" data-bs-toggle="tooltip" data-bs-title="Review / Tinjau">
                                            <i class="fas fa-search me-1"></i> Tinjau
                                        </a>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="text-center py-4">
                                        <p class="text-sm font-weight-bold mb-0">Belum ada fakta integritas yang diunggah.</p>
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
