@extends('backend.layouts.app')

@section('title', 'Data Usulan PIP')

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route(auth()->user()->role === 'operator' ? 'operator.dashboard' : 'adminlembaga.dashboard') }}">Dashboard</a></li>
    <li class="breadcrumb-item active">Data PIP</li>
@endsection

@section('content')
<div class="row">
    <div class="col-12">
        <div class="card card-outline card-warning">
            <div class="card-header">
                <h3 class="card-title">Daftar Siswa Penerima Program Indonesia Pintar (PIP)</h3>
                <div class="card-tools">
                    <button type="button" class="btn btn-sm btn-success" data-toggle="modal" data-target="#importModal">
                        <i class="fas fa-file-excel"></i> Import Excel
                    </button>
                    <button type="button" class="btn btn-sm btn-primary" data-toggle="modal" data-target="#createModal">
                        <i class="fas fa-plus"></i> Tambah Data Penerima
                    </button>
                </div>
            </div>
            <div class="card-body table-responsive p-0">
                <table class="table table-hover text-nowrap table-striped">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>NISN</th>
                            <th>Nama Siswa</th>
                            <th>Tahun/Tahap</th>
                            <th>Keterangan</th>
                            <th class="text-center">Status Lapor</th>
                            <th>Tanggapan Dinas</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($pipData as $index => $pip)
                            <tr>
                                <td>{{ $pipData->firstItem() + $index }}</td>
                                <td>{{ $pip->nisn ?? '-' }}</td>
                                <td>{{ $pip->nama_siswa }}</td>
                                <td>{{ $pip->tahun_usulan }} / {{ $pip->tahap ?? '-' }}</td>
                                <td>
                                    @if($pip->pesan_lembaga)
                                      <button type="button" class="btn btn-xs btn-info" data-toggle="tooltip" title="{{ $pip->pesan_lembaga }}"><i class="fas fa-comment"></i> Psn Sekolah</button>
                                    @else
                                      -
                                    @endif
                                </td>
                                <td class="text-center">
                                    @if($pip->status === 'usulan_sekolah')
                                        <span class="badge badge-secondary">Menunggu Tinjauan</span>
                                    @elseif($pip->status === 'diproses_dinas')
                                        <span class="badge badge-warning">Dicek Dinas</span>
                                    @elseif($pip->status === 'disetujui')
                                        <span class="badge badge-success">Terverifikasi (Valid)</span>
                                    @elseif($pip->status === 'ditolak')
                                        <span class="badge badge-danger">Tidak Valid</span>
                                    @endif
                                </td>
                                <td>
                                    @if($pip->pesan_dinas)
                                      <span class="text-danger small"><i class="fas fa-reply"></i> {{ \Illuminate\Support\Str::limit($pip->pesan_dinas, 30) }}</span>
                                    @else
                                      <span class="text-muted small">Belum ada</span>
                                    @endif
                                </td>
                                <td>
                                    @if($pip->status === 'usulan_sekolah')
                                        <form action="{{ route(auth()->user()->role === 'operator' ? 'operator.pip.destroy' : 'adminlembaga.pip.destroy', $pip->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Yakin hapus data penerima ini?');">
                                            @csrf
                                            @method('DELETE')
                                            <button class="btn btn-xs btn-danger"><i class="fas fa-trash"></i> Hapus</button>
                                        </form>
                                    @else
                                        <button class="btn btn-xs btn-secondary" disabled><i class="fas fa-lock"></i></button>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="8" class="text-center">Belum ada data laporan penerima PIP.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            <div class="card-footer">
                {{ $pipData->links() }}
            </div>
        </div>
    </div>
</div>

<!-- Modal Import Excel -->
<div class="modal fade" id="importModal" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <form action="{{ route(auth()->user()->role === 'operator' ? 'operator.pip.import' : 'adminlembaga.pip.import') }}" method="POST" enctype="multipart/form-data">
            @csrf
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Import Data PIP Excel</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <p class="small text-muted mb-2">Sebaiknya unduh kerangka format Excel kami jika Anda mensubmit laporan massal agar format tabel sesuai:</p>
                    <a href="{{ route(auth()->user()->role === 'operator' ? 'operator.pip.template' : 'adminlembaga.pip.template') }}" class="btn btn-sm btn-outline-success mb-3">
                        <i class="fas fa-download"></i> Unduh Format Template
                    </a>
                    <hr>
                    <div class="form-group">
                        <label for="file">Unggah File Laporan (.xlsx, .csv)</label>
                        <input type="file" name="file" class="form-control-file" required>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Tutup</button>
                    <button type="submit" class="btn btn-success">Import Data</button>
                </div>
            </div>
        </form>
    </div>
</div>

<!-- Modal Tambah Manual -->
<div class="modal fade" id="createModal" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <form action="{{ route(auth()->user()->role === 'operator' ? 'operator.pip.store' : 'adminlembaga.pip.store') }}" method="POST">
            @csrf
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Lapor Data Penerima PIP (Manual)</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <label>NISN <small class="text-muted">(Opsional bila tidak ada)</small></label>
                        <input type="text" name="nisn" class="form-control">
                    </div>
                    <div class="form-group">
                        <label>Nama Siswa <span class="text-danger">*</span></label>
                        <input type="text" name="nama_siswa" class="form-control" required>
                    </div>
                    <div class="row">
                        <div class="col-md-6 form-group">
                            <label>Tahun Terima PIP <span class="text-danger">*</span></label>
                            <input type="number" name="tahun_usulan" class="form-control" value="{{ date('Y') }}" required>
                        </div>
                        <div class="col-md-6 form-group">
                            <label>Tahap (opsional)</label>
                            <input type="text" name="tahap" class="form-control" placeholder="1, 2, dsb">
                        </div>
                    </div>
                    <div class="form-group">
                        <label>Keterangan Penerimaan</label>
                        <textarea name="pesan_lembaga" class="form-control" rows="3" placeholder="Siswa sudah mencairkan dana..."></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary">Simpan Laporan PIP</button>
                </div>
            </div>
        </form>
    </div>
</div>
@endsection
