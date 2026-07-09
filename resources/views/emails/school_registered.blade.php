<x-mail::message>
# Pendaftaran Berhasil!

Halo {{ $operatorName }},

Terima kasih telah mendaftarkan sekolah Anda di sistem **{{ config('app.name') }}**. 
Berikut adalah rincian data pendaftaran yang telah Anda simpan:

**Data Sekolah:**
- **Nama Sekolah:** {{ $sekolahName }}
- **NPSN:** {{ $npsn }}
- **Kode Sekolah:** {{ $kodeSekolah }}
- **Jenjang:** {{ $jenjang }}

**Data Akses Login (Simpan dengan Aman):**
- **Email:** {{ $email }}
- **Password:** {{ $passwordRaw }}

*Catatan: Password di atas tidak dienkripsi dalam email ini khusus untuk pencatatan Anda. Di dalam sistem, password Anda tetap terenkripsi dan aman.*

> **Status Akun: Menunggu Verifikasi**
> Akun sekolah Anda saat ini sedang dalam status menunggu persetujuan dari pihak **{{ config('app.name') }}**. Anda baru dapat masuk ke sistem setelah akun diaktifkan.

<x-mail::button :url="$loginUrl">
Masuk ke Portal Sekolah
</x-mail::button>

Terima kasih,<br>
Tim Support {{ config('app.name') }}
</x-mail::message>
