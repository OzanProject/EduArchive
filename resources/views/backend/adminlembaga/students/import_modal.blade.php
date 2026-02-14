<!-- Import Modal -->
<div class="modal fade" id="importModal" tabindex="-1" role="dialog" aria-labelledby="importModalLabel"
  aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="importModalLabel">Import Data Siswa ({{ $status }})</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <form action="{{ route('adminlembaga.students.import') }}" method="POST" enctype="multipart/form-data">
        @csrf
        <div class="modal-body">
          <div class="form-group">
            <label>Import Sebagai Status</label>
            <select name="status" id="importStatus" class="form-control">
              <option value="Aktif" {{ $status == 'Aktif' ? 'selected' : '' }}>Siswa Aktif</option>
              <option value="Lulus" {{ $status == 'Lulus' ? 'selected' : '' }}>Siswa Lulus</option>
            </select>
          </div>
          <div class="form-group">
            <label>Download Template</label>
            <br>
            <a href="{{ route('adminlembaga.students.template', ['status' => $status]) }}" id="downloadTemplateBtn"
              class="btn btn-success btn-sm">
              <i class="fas fa-file-excel"></i> Download Format Excel
            </a>
          </div>
          <div class="form-group">
            <label for="file">Pilih File Excel (.xlsx, .xls)</label>
            <input type="file" class="form-control-file" id="file" name="file" required accept=".xlsx, .xls">
          </div>
          <div class="alert alert-info">
            <small>Pastikan format file sesuai template. Kolom: Nama, NIK, NISN, Kelas (Nama Kelas), Tgl Lahir
              (YYYY-MM-DD).</small>
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
<script>
  document.addEventListener('DOMContentLoaded', function () {
    const statusSelect = document.getElementById('importStatus');
    const downloadBtn = document.getElementById('downloadTemplateBtn');
    const baseUrl = "{{ route('adminlembaga.students.template') }}";

    if (statusSelect && downloadBtn) {
      statusSelect.addEventListener('change', function () {
        downloadBtn.href = baseUrl + '?status=' + this.value;
      });
    }
  });
</script>