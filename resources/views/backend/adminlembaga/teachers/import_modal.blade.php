<!-- Import Modal -->
<div class="modal fade" id="importModal" tabindex="-1" role="dialog" aria-labelledby="importModalLabel"
  aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="importModalLabel">Import Data Guru</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <form action="{{ route('adminlembaga.teachers.import') }}" method="POST" enctype="multipart/form-data">
        @csrf
        <div class="modal-body">
          <div class="form-group">
            <label>Download Template</label>
            <br>
            <a href="{{ route('adminlembaga.teachers.template') }}" class="btn btn-success btn-sm">
              <i class="fas fa-file-excel"></i> Download Format Excel
            </a>
          </div>
          <div class="form-group">
            <label for="file">Pilih File Excel (.xlsx, .xls)</label>
            <input type="file" class="form-control-file" id="file" name="file" required accept=".xlsx, .xls">
          </div>
          <div class="alert alert-info">
            <small>Pastikan format file sesuai template. Kolom: <strong>NIP, Nama Lengkap, Email, No HP, Alamat,
                Jabatan</strong>.</small>
          </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
          <button type="submit" class="btn btn-primary">Import</button>
        </div>
      </form>
    </div>
  </div>
</div>