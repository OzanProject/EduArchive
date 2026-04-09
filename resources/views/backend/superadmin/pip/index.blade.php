@extends('backend.layouts.app')

@section('title', 'Data PIP Semua Lembaga')

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('superadmin.dashboard') }}">Dashboard</a></li>
    <li class="breadcrumb-item active">Verifikasi Data PIP</li>
@endsection

@section('content')
<div class="row">
    <div class="col-12 mb-3">
        <!-- Filter Data -->
        <form action="{{ route('superadmin.pip.index') }}" method="GET" class="form-inline">
            <div class="form-group mr-2">
                <select name="tenant_id" class="form-control">
                    <option value="">-- Semua Sekolah --</option>
                    @foreach($tenants as $tenant)
                        <option value="{{ $tenant->id }}" {{ request('tenant_id') == $tenant->id ? 'selected' : '' }}>
                            {{ $tenant->nama_sekolah }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div class="form-group mr-2">
                <select name="status" class="form-control">
                    <option value="">-- Semua Status --</option>
                    <option value="usulan_sekolah" {{ request('status') == 'usulan_sekolah' ? 'selected' : '' }}>Menunggu Tinjauan</option>
                    <option value="diproses_dinas" {{ request('status') == 'diproses_dinas' ? 'selected' : '' }}>Diproses Dinas</option>
                    <option value="disetujui" {{ request('status') == 'disetujui' ? 'selected' : '' }}>Terverifikasi (Valid)</option>
                    <option value="ditolak" {{ request('status') == 'ditolak' ? 'selected' : '' }}>Tidak Valid</option>
                </select>
            </div>
            <button type="submit" class="btn btn-primary"><i class="fas fa-search"></i> Terapkan Filter</button>
            <a href="{{ route('superadmin.pip.index') }}" class="btn btn-default ml-2">Reset</a>
        </form>
    </div>

    <div class="col-12">
        <div class="card card-outline card-primary">
            <div class="card-header">
                <h3 class="card-title">Daftar Penerima PIP dari Sekolah</h3>
            </div>
            <div class="card-body table-responsive p-0">
                <table class="table table-hover text-nowrap table-striped">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Asal Sekolah</th>
                            <th>NISN</th>
                            <th>Nama Siswa</th>
                            <th>Tahap</th>
                            <th>Keterangan Pelaporan</th>
                            <th class="text-center">Status Verifikasi</th>
                            <th class="text-center">Tindakan</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($allPip as $index => $pip)
                            <tr>
                                <td>{{ $allPip->firstItem() + $index }}</td>
                                <td>{{ $pip->tenant->nama_sekolah ?? 'Unknown' }}</td>
                                <td>{{ $pip->nisn ?? '-' }}</td>
                                <td class="font-weight-bold">{{ $pip->nama_siswa }}</td>
                                <td>{{ $pip->tahun_usulan }} / {{ $pip->tahap ?? '-' }}</td>
                                <td>
                                    @if($pip->pesan_lembaga)
                                        <button class="btn btn-xs btn-outline-info" data-toggle="tooltip" title="{{ $pip->pesan_lembaga }}">Lihat Pesan</button>
                                    @else
                                        -
                                    @endif
                                </td>
                                <td class="text-center">
                                    @if($pip->status === 'usulan_sekolah')
                                        <span class="badge badge-secondary">Belum Tinjau</span>
                                    @elseif($pip->status === 'diproses_dinas')
                                        <span class="badge badge-warning">Dalam Cek</span>
                                    @elseif($pip->status === 'disetujui')
                                        <span class="badge badge-success">Valid</span>
                                    @elseif($pip->status === 'ditolak')
                                        <span class="badge badge-danger">Tidak Valid</span>
                                    @endif
                                </td>
                                <td class="text-center text-nowrap">
                                    <button class="btn btn-sm btn-primary" data-toggle="modal" data-target="#replyModal{{ $pip->id }}">
                                        <i class="fas fa-edit"></i> Verifikasi
                                    </button>
                                    <form action="{{ route('superadmin.pip.destroy', $pip->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Peringatan: Anda akan menghapus data ini secara permanen dari sistem lembaga tersebut. Lanjutkan?');">
                                        @csrf
                                        @method('DELETE')
                                        <button class="btn btn-sm btn-danger" data-toggle="tooltip" title="Hapus Permanen">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </form>
                                </td>
                            </tr>

                            <!-- Modal Tindakan -->
                            <div class="modal fade" id="replyModal{{ $pip->id }}" tabindex="-1" role="dialog" aria-hidden="true">
                                <div class="modal-dialog" role="document">
                                    <form action="{{ route('superadmin.pip.updateStatus', $pip->id) }}" method="POST">
                                        @csrf
                                        @method('PATCH')
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h5 class="modal-title">Tinjau Data & Verifikasi - {{ $pip->nama_siswa }}</h5>
                                                <button type="button" class="close" data-dismiss="modal"><span>&times;</span></button>
                                            </div>
                                            <div class="modal-body">
                                                @if($pip->pesan_lembaga)
                                                    <div class="alert alert-info py-2">
                                                        <strong>Keterangan dari Sekolah:</strong><br>
                                                        "{!! nl2br(e($pip->pesan_lembaga)) !!}"
                                                    </div>
                                                @endif
                                                <div class="form-group">
                                                    <label>Ubah Status Lapor</label>
                                                    <select name="status" class="form-control" required>
                                                        <option value="usulan_sekolah" {{ $pip->status == 'usulan_sekolah' ? 'selected' : '' }}>Menunggu Tinjauan Dinas</option>
                                                        <option value="diproses_dinas" {{ $pip->status == 'diproses_dinas' ? 'selected' : '' }}>Sedang Dicek Dinas</option>
                                                        <option value="disetujui" {{ $pip->status == 'disetujui' ? 'selected' : '' }}>Terverifikasi (Valid)</option>
                                                        <option value="ditolak" {{ $pip->status == 'ditolak' ? 'selected' : '' }}>Data Bermasalah / Tidak Valid</option>
                                                    </select>
                                                </div>
                                                <div class="form-group">
                                                    <label>Pesan/Tanggapan Dinas <small class="text-muted">(Opsional, akan dibaca sekolah)</small></label>
                                                    <textarea name="pesan_dinas" class="form-control" rows="4" placeholder="Misal: Data SKTM kurang jelas, harap unggah via link khusus...">{{ $pip->pesan_dinas }}</textarea>
                                                </div>
                                            </div>
                                            <div class="modal-footer">
                                                <button type="button" class="btn btn-secondary" data-dismiss="modal">Tutup</button>
                                                <button type="submit" class="btn btn-primary">Simpan Keputusan</button>
                                            </div>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        @empty
                            <tr>
                                <td colspan="8" class="text-center">Belum ada usulan PIP pada sistem dari sekolah manapun.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            <div class="card-footer">
                {{ $allPip->links() }}
            </div>
        </div>
    </div>
</div>
@endsection
