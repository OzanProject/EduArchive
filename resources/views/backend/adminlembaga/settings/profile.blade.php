@extends('backend.layouts.app')

@section('title', 'Pengaturan Profil Publik')
@section('page_title', 'Profil Publik')

@section('breadcrumb')
  <li class="breadcrumb-item"><a href="{{ route('adminlembaga.dashboard') }}">Home</a></li>
  <li class="breadcrumb-item"><a href="{{ route('adminlembaga.settings.index') }}">Pengaturan</a></li>
  <li class="breadcrumb-item active">Profil Publik</li>
@endsection

@section('content')
  <div class="row">
    <div class="col-md-12">
      <div class="card card-primary card-outline">
        <div class="card-header">
          <h3 class="card-title">Edit Profil Publik Sekolah</h3>
        </div>
        <div class="card-body">
          <form action="{{ route('adminlembaga.settings.update') }}" method="POST" enctype="multipart/form-data">
            @csrf
            <div class="row">
              <!-- Kolom Kiri: Identitas & Utama -->
              <div class="col-md-6">
                <h5 class="text-primary"><i class="fas fa-school mr-2"></i> Identitas Sekolah</h5>
                <hr>
                <div class="form-group">
                  <label>Status Sekolah</label>
                  <select name="school_status" class="form-control">
                    <option value="Negeri" {{ ($settings['school_status'] ?? '') == 'Negeri' ? 'selected' : '' }}>Negeri
                    </option>
                    <option value="Swasta" {{ ($settings['school_status'] ?? '') == 'Swasta' ? 'selected' : '' }}>Swasta
                    </option>
                  </select>
                </div>
                <div class="form-group">
                  <label>Bentuk Pendidikan</label>
                  <input type="text" name="school_education_form" class="form-control"
                    placeholder="Contoh: SD, SMP, SMA, PKBM" value="{{ $settings['school_education_form'] ?? '' }}">
                </div>
                <div class="form-group">
                  <label>Akreditasi</label>
                  <select name="school_accreditation" class="form-control">
                    <option value="A" {{ ($settings['school_accreditation'] ?? '') == 'A' ? 'selected' : '' }}>A</option>
                    <option value="B" {{ ($settings['school_accreditation'] ?? '') == 'B' ? 'selected' : '' }}>B</option>
                    <option value="C" {{ ($settings['school_accreditation'] ?? '') == 'C' ? 'selected' : '' }}>C</option>
                    <option value="Belum Terakreditasi" {{ ($settings['school_accreditation'] ?? '') == 'Belum Terakreditasi' ? 'selected' : '' }}>Belum
                      Terakreditasi</option>
                  </select>
                </div>
                <div class="form-group">
                  <label>Nama Yayasan (Jika Ada)</label>
                  <input type="text" name="school_foundation_name" class="form-control"
                    value="{{ $settings['school_foundation_name'] ?? '' }}">
                </div>
                <div class="form-group">
                  <label>Nama Operator Sekolah</label>
                  <input type="text" name="school_operator_name" class="form-control"
                    value="{{ $settings['school_operator_name'] ?? '' }}">
                </div>

                <h5 class="text-primary mt-4"><i class="fas fa-network-wired mr-2"></i> Utilitas & Infrastruktur</h5>
                <hr>
                <div class="row">
                  <div class="col-6">
                    <div class="form-group">
                      <label>Akses Internet</label>
                      <input type="text" name="school_internet_access" class="form-control"
                        placeholder="Contoh: Telkomsel" value="{{ $settings['school_internet_access'] ?? '' }}">
                    </div>
                  </div>
                  <div class="col-6">
                    <div class="form-group">
                      <label>Sumber Listrik</label>
                      <input type="text" name="school_power_source" class="form-control" placeholder="Contoh: PLN"
                        value="{{ $settings['school_power_source'] ?? '' }}">
                    </div>
                  </div>
                  <div class="col-6">
                    <div class="form-group">
                      <label>Daya Listrik (Var)</label>
                      <input type="number" name="school_power_wattage" class="form-control" placeholder="Contoh: 1400"
                        value="{{ $settings['school_power_wattage'] ?? '' }}">
                    </div>
                  </div>
                  <div class="col-6">
                    <div class="form-group">
                      <label>Luas Tanah (m²)</label>
                      <input type="number" name="school_land_area" class="form-control" placeholder="Contoh: 5000"
                        value="{{ $settings['school_land_area'] ?? '' }}">
                    </div>
                  </div>
                </div>
                <div class="form-group">
                  <label>Kurikulum</label>
                  <input type="text" name="school_curriculum" class="form-control" placeholder="Contoh: Kurikulum Merdeka"
                    value="{{ $settings['school_curriculum'] ?? '' }}">
                </div>

                {{-- ===== LOKASI SEKOLAH ===== --}}
                <h5 class="text-primary mt-4"><i class="fas fa-map-marker-alt mr-2"></i> Lokasi Sekolah</h5>
                <hr>

                {{-- Hidden fields to store name text alongside code --}}
                <input type="hidden" name="school_province_code" id="school_province_code" value="{{ $settings['school_province_code'] ?? '' }}">
                <input type="hidden" name="school_regency_code" id="school_regency_code" value="{{ $settings['school_regency_code'] ?? '' }}">
                <input type="hidden" name="school_district_code" id="school_district_code" value="{{ $settings['school_district_code'] ?? '' }}">
                <input type="hidden" name="school_village_code" id="school_village_code" value="{{ $settings['school_village_code'] ?? '' }}">

                <div class="row">
                  <div class="col-md-6">
                    <div class="form-group">
                      <label>Provinsi</label>
                      <select name="school_province" id="sel_province" class="form-control select2">
                        <option value="">-- Pilih Provinsi --</option>
                      </select>
                    </div>
                  </div>
                  <div class="col-md-6">
                    <div class="form-group">
                      <label>Kabupaten / Kota</label>
                      <select name="school_regency" id="sel_regency" class="form-control select2" disabled>
                        <option value="">-- Pilih Kabupaten/Kota --</option>
                      </select>
                    </div>
                  </div>
                  <div class="col-md-6">
                    <div class="form-group">
                      <label>Kecamatan</label>
                      <select name="school_district" id="sel_district" class="form-control select2" disabled>
                        <option value="">-- Pilih Kecamatan --</option>
                      </select>
                    </div>
                  </div>
                  <div class="col-md-6">
                    <div class="form-group">
                      <label>Desa / Kelurahan</label>
                      <select name="school_village" id="sel_village" class="form-control select2" disabled>
                        <option value="">-- Pilih Desa/Kelurahan --</option>
                      </select>
                    </div>
                  </div>
                </div>

                <div class="form-group">
                  <label>Alamat Lengkap Sekolah</label>
                  <textarea name="school_address" class="form-control" rows="2"
                    placeholder="Contoh: Jl. Merdeka No.1, RT 001/RW 002">{{ $settings['school_address'] ?? '' }}</textarea>
                </div>
                <div class="row">
                  <div class="col-md-6">
                    <div class="form-group">
                      <label>Kode Pos</label>
                      <input type="text" name="school_postal_code" class="form-control" placeholder="Contoh: 44151"
                        value="{{ $settings['school_postal_code'] ?? '' }}">
                    </div>
                  </div>
                  <div class="col-md-6">
                    <div class="form-group">
                      <label>Nomor Telepon Sekolah</label>
                      <input type="text" name="school_phone" class="form-control" placeholder="Contoh: 0262-123456"
                        value="{{ $settings['school_phone'] ?? '' }}">
                    </div>
                  </div>
                </div>

              <!-- Kolom Kanan: Kepala Sekolah & Data Tambahan -->
              </div>{{-- end col-md-6 left --}}
              <div class="col-md-6">
                <h5 class="text-primary"><i class="fas fa-user-tie mr-2"></i> Kepala Sekolah</h5>
                <hr>
                <div class="form-group">
                  <label>Foto Kepala Sekolah</label>
                  <div class="custom-file">
                    <input type="file" class="custom-file-input" id="school_headmaster_photo"
                      name="school_headmaster_photo" accept="image/*">
                    <label class="custom-file-label">Pilih foto...</label>
                  </div>
                  <div id="preview-container-school_headmaster_photo" class="mt-3 text-center">
                    @if(isset($settings['school_headmaster_photo']))
                      <img src="{{ asset($settings['school_headmaster_photo']) }}"
                        class="img-preview border p-2 bg-light rounded" style="max-height:150px;">
                    @endif
                  </div>
                </div>

                <div class="form-group">
                  <label>Foto Sampul / Ilustrasi Sekolah (Halaman Depan)</label>
                  <div class="custom-file">
                    <input type="file" class="custom-file-input" id="school_hero_image" name="school_hero_image"
                      accept="image/*">
                    <label class="custom-file-label">Pilih ilustrasi...</label>
                  </div>
                  <div id="preview-container-school_hero_image" class="mt-3 text-center">
                    @if(isset($settings['school_hero_image']))
                      <img src="{{ asset($settings['school_hero_image']) }}" class="img-preview border p-2 bg-light rounded"
                        style="max-height:150px;">
                    @endif
                  </div>
                </div>
                <div class="form-group">
                  <label>Nama Kepala Sekolah</label>
                  <input type="text" name="school_headmaster_name" class="form-control"
                    value="{{ $settings['school_headmaster_name'] ?? '' }}">
                </div>
                <div class="form-group">
                  <label>NIP Kepala Sekolah</label>
                  <input type="text" name="school_headmaster_nip" class="form-control"
                    value="{{ $settings['school_headmaster_nip'] ?? '' }}">
                </div>

                <h5 class="text-primary mt-4"><i class="fas fa-info-circle mr-2"></i> Informasi Tambahan</h5>
                <hr>
                <div class="form-group">
                  <label>Visi</label>
                  <textarea name="school_vision" class="form-control"
                    rows="2">{{ $settings['school_vision'] ?? '' }}</textarea>
                </div>
                <div class="form-group">
                  <label>Misi</label>
                  <textarea name="school_mission" class="form-control"
                    rows="2">{{ $settings['school_mission'] ?? '' }}</textarea>
                </div>
                <div class="form-group">
                  <label>Sejarah Singkat</label>
                  <textarea name="school_history" class="form-control"
                    rows="2">{{ $settings['school_history'] ?? '' }}</textarea>
                </div>
                <div class="form-group">
                  <label>Link Google Maps (Embed)</label>
                  <input type="text" name="school_map_embed" class="form-control"
                    placeholder="https://www.google.com/maps/embed?..." value="{{ $settings['school_map_embed'] ?? '' }}">
                </div>
              </div>
            </div>

            <div class="row mt-3">
              <div class="col-12">
                <h5 class="text-primary"><i class="fas fa-chart-bar mr-2"></i> Data Sarpras & Statistik (Manual)</h5>
                <hr>
                <p class="text-muted text-sm">Masukan jumlah data sarana yang tidak terdata otomatis oleh sistem.</p>
              </div>
              <div class="col-md-3">
                <div class="form-group">
                  <label>Jumlah Laboratorium</label>
                  <input type="number" name="school_lab_count" class="form-control"
                    value="{{ $settings['school_lab_count'] ?? 0 }}">
                </div>
              </div>
              <div class="col-md-3">
                <div class="form-group">
                  <label>Daya Tampung (Siswa)</label>
                  <input type="number" name="school_capacity" class="form-control"
                    value="{{ $settings['school_capacity'] ?? 0 }}">
                </div>
              </div>
              <div class="col-md-3">
                <div class="form-group">
                  <label>Jumlah Perpustakaan</label>
                  <input type="number" name="school_library_count" class="form-control"
                    value="{{ $settings['school_library_count'] ?? 0 }}">
                </div>
              </div>
              <div class="col-md-3">
                <div class="form-group">
                  <label>Sanitasi Layak (%)</label>
                  <input type="number" step="0.01" name="school_sanitation_percentage" class="form-control"
                    value="{{ $settings['school_sanitation_percentage'] ?? 0 }}">
                </div>
              </div>
              <div class="col-md-6">
                <div class="form-group">
                  <label>Sumber Air Bersih</label>
                  <input type="text" name="school_water_source" class="form-control"
                    placeholder="Contoh: Sumur Terlindung" value="{{ $settings['school_water_source'] ?? '' }}">
                </div>
              </div>
              <div class="col-md-6">
                <div class="form-group">
                  <label>Ketersediaan Jamban</label>
                  <input type="text" name="school_toilet_availability" class="form-control"
                    placeholder="Contoh: Tersedia untuk Siswa Berkebutuhan Khusus"
                    value="{{ $settings['school_toilet_availability'] ?? '' }}">
                </div>
              </div>
            </div>

            <h5 class="text-primary mt-4"><i class="fas fa-share-alt mr-2"></i> Media Sosial</h5>
            <hr>
            <div class="row">
              <div class="col-md-6">
                <div class="form-group">
                  <label>Facebook URL</label>
                  <div class="input-group">
                    <div class="input-group-prepend">
                      <span class="input-group-text"><i class="fab fa-facebook"></i></span>
                    </div>
                    <input type="url" name="school_facebook" class="form-control" placeholder="https://facebook.com/..."
                      value="{{ $settings['school_facebook'] ?? '' }}">
                  </div>
                </div>
              </div>
              <div class="col-md-6">
                <div class="form-group">
                  <label>Instagram URL</label>
                  <div class="input-group">
                    <div class="input-group-prepend">
                      <span class="input-group-text"><i class="fab fa-instagram"></i></span>
                    </div>
                    <input type="url" name="school_instagram" class="form-control" placeholder="https://instagram.com/..."
                      value="{{ $settings['school_instagram'] ?? '' }}">
                  </div>
                </div>
              </div>
              <div class="col-md-6">
                <div class="form-group">
                  <label>Twitter/X URL</label>
                  <div class="input-group">
                    <div class="input-group-prepend">
                      <span class="input-group-text"><i class="fab fa-twitter"></i></span>
                    </div>
                    <input type="url" name="school_twitter" class="form-control" placeholder="https://twitter.com/..."
                      value="{{ $settings['school_twitter'] ?? '' }}">
                  </div>
                </div>
              </div>
              <div class="col-md-6">
                <div class="form-group">
                  <label>YouTube Channel URL</label>
                  <div class="input-group">
                    <div class="input-group-prepend">
                      <span class="input-group-text"><i class="fab fa-youtube"></i></span>
                    </div>
                    <input type="url" name="school_youtube" class="form-control" placeholder="https://youtube.com/..."
                      value="{{ $settings['school_youtube'] ?? '' }}">
                  </div>
                </div>
              </div>
            </div>
            <div class="text-right mt-3">
              <button type="submit" class="btn btn-primary">Simpan Profil Publik</button>
            </div>
          </form>
        </div>
      </div>
    </div>
  </div>
  @push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/bs-custom-file-input/dist/bs-custom-file-input.min.js"></script>
    <script>
      $(function () {
        bsCustomFileInput.init();

        // ---- Image Preview ----
        function previewImage(input, containerId) {
          if (!input.files || !input.files[0]) return;
          const file = input.files[0];
          if (!file.type.startsWith('image/')) {
            alert('File harus berupa gambar!');
            input.value = '';
            return;
          }
          const reader = new FileReader();
          reader.onload = function (e) {
            const html = `<img src="${e.target.result}" class="border p-2 bg-light rounded shadow-sm" style="max-height:150px; object-fit:contain;">`;
            $('#' + containerId).html(html);
          };
          reader.readAsDataURL(file);
        }

        $('#school_headmaster_photo').on('change', function () {
          previewImage(this, 'preview-container-school_headmaster_photo');
        });
        $('#school_hero_image').on('change', function () {
          previewImage(this, 'preview-container-school_hero_image');
        });

        // ---- Cascading Wilayah Indonesia ----
        // Menggunakan proxy backend Laravel (bebas CORS, server-to-server)
        const baseUrl = window.location.origin;

        // Saved values from database
        const savedProvinceCode  = $('#school_province_code').val();
        const savedRegencyCode   = $('#school_regency_code').val();
        const savedDistrictCode  = $('#school_district_code').val();
        const savedVillageCode   = $('#school_village_code').val();
        const savedProvinceName  = '{{ $settings["school_province"] ?? "" }}';
        const savedRegencyName   = '{{ $settings["school_regency"] ?? "" }}';
        const savedDistrictName  = '{{ $settings["school_district"] ?? "" }}';
        const savedVillageName   = '{{ $settings["school_village"] ?? "" }}';

        function populateSelect(selectId, data, selectedCode, selectedName, codeHiddenId, nextSelectId) {
          const $sel = $('#' + selectId);
          $sel.empty().append('<option value="">-- Pilih --</option>');
          data.forEach(item => {
            const selected = (item.id == selectedCode || item.name == selectedName) ? 'selected' : '';
            $sel.append(`<option value="${item.name}" data-code="${item.id}" ${selected}>${item.name}</option>`);
          });
          $sel.prop('disabled', false);

          // If a value was pre-selected, trigger next level
          if (selectedCode || selectedName) {
            const $selected = $sel.find('option:selected');
            if ($selected.val()) {
              const code = $selected.data('code');
              $('#' + codeHiddenId).val(code);
            }
          }
        }

        // Load Provinces
        $.getJSON(`${baseUrl}/api/wilayah/provinces`, function (data) {
          populateSelect('sel_province', data, savedProvinceCode, savedProvinceName, 'school_province_code', 'sel_regency');

          // If province already saved, auto-load regencies
          if (savedProvinceCode || savedProvinceName) {
            const $prov = $('#sel_province option:selected');
            const provCode = $prov.data('code') || savedProvinceCode;
            if (provCode) loadRegencies(provCode, true);
          }
        }).fail(function() { console.error('Gagal memuat data provinsi.'); });

        function loadRegencies(provinceCode, autoLoad) {
          $('#sel_regency').html('<option>Memuat...</option>').prop('disabled', true);
          $('#sel_district').html('<option>-- Pilih Kecamatan --</option>').prop('disabled', true);
          $('#sel_village').html('<option>-- Pilih Desa/Kelurahan --</option>').prop('disabled', true);
          $.getJSON(`${baseUrl}/api/wilayah/regencies/${provinceCode}`, function (data) {
            populateSelect('sel_regency', data, savedRegencyCode, savedRegencyName, 'school_regency_code', 'sel_district');
            if (autoLoad && (savedRegencyCode || savedRegencyName)) {
              const $reg = $('#sel_regency option:selected');
              const regCode = $reg.data('code') || savedRegencyCode;
              if (regCode) loadDistricts(regCode, true);
            }
          }).fail(function() { console.error('Gagal memuat data kabupaten/kota.'); });
        }

        function loadDistricts(regencyCode, autoLoad) {
          $('#sel_district').html('<option>Memuat...</option>').prop('disabled', true);
          $('#sel_village').html('<option>-- Pilih Desa/Kelurahan --</option>').prop('disabled', true);
          $.getJSON(`${baseUrl}/api/wilayah/districts/${regencyCode}`, function (data) {
            populateSelect('sel_district', data, savedDistrictCode, savedDistrictName, 'school_district_code', 'sel_village');
            if (autoLoad && (savedDistrictCode || savedDistrictName)) {
              const $dis = $('#sel_district option:selected');
              const disCode = $dis.data('code') || savedDistrictCode;
              if (disCode) loadVillages(disCode, true);
            }
          }).fail(function() { console.error('Gagal memuat data kecamatan.'); });
        }

        function loadVillages(districtCode, autoLoad) {
          $('#sel_village').html('<option>Memuat...</option>').prop('disabled', true);
          $.getJSON(`${baseUrl}/api/wilayah/villages/${districtCode}`, function (data) {
            populateSelect('sel_village', data, savedVillageCode, savedVillageName, 'school_village_code', null);
          }).fail(function() { console.error('Gagal memuat data desa/kelurahan.'); });
        }

        // Event: Province changed
        $('#sel_province').on('change', function () {
          const code = $(this).find('option:selected').data('code');
          $('#school_province_code').val(code || '');
          $('#school_regency_code').val('');
          $('#school_district_code').val('');
          $('#school_village_code').val('');
          if (code) {
            loadRegencies(code, false);
          } else {
            $('#sel_regency').html('<option>-- Pilih Kabupaten/Kota --</option>').prop('disabled', true);
            $('#sel_district').html('<option>-- Pilih Kecamatan --</option>').prop('disabled', true);
            $('#sel_village').html('<option>-- Pilih Desa/Kelurahan --</option>').prop('disabled', true);
          }
        });

        // Event: Regency changed
        $('#sel_regency').on('change', function () {
          const code = $(this).find('option:selected').data('code');
          $('#school_regency_code').val(code || '');
          $('#school_district_code').val('');
          $('#school_village_code').val('');
          if (code) {
            loadDistricts(code, false);
          } else {
            $('#sel_district').html('<option>-- Pilih Kecamatan --</option>').prop('disabled', true);
            $('#sel_village').html('<option>-- Pilih Desa/Kelurahan --</option>').prop('disabled', true);
          }
        });

        // Event: District changed
        $('#sel_district').on('change', function () {
          const code = $(this).find('option:selected').data('code');
          $('#school_district_code').val(code || '');
          $('#school_village_code').val('');
          if (code) {
            loadVillages(code, false);
          } else {
            $('#sel_village').html('<option>-- Pilih Desa/Kelurahan --</option>').prop('disabled', true);
          }
        });

        // Event: Village changed
        $('#sel_village').on('change', function () {
          const code = $(this).find('option:selected').data('code');
          $('#school_village_code').val(code || '');
        });
      });
    </script>
  @endpush
@endsection