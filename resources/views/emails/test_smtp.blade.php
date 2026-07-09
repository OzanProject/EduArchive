<x-mail::message>
# Test Koneksi SMTP Berhasil!

Halo,

Jika Anda membaca email ini, berarti pengaturan **SMTP Server** pada sistem EduArchive Anda telah dikonfigurasi dengan benar dan berjalan dengan lancar.

Pesan ini dikirim menggunakan *template* email resmi EduArchive yang sudah mendukung *responsive design* dan terintegrasi dengan identitas sistem.

<x-mail::button :url="config('app.url')">
Buka Dashboard EduArchive
</x-mail::button>

Terima kasih,<br>
Tim Support {{ config('app.name') }}
</x-mail::message>
