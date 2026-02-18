<!DOCTYPE html>
<html lang="id">

<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Sekolah Tidak Ditemukan - {{ config('app.name') }}</title>
  <link rel="stylesheet"
    href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
  <style>
    body {
      background-color: #f4f6f9;
      font-family: 'Source Sans Pro', sans-serif;
      display: flex;
      align-items: center;
      justify-content: center;
      height: 100vh;
      margin: 0;
    }

    .error-card {
      background: white;
      padding: 40px;
      border-radius: 8px;
      box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
      max-width: 500px;
      width: 90%;
      text-align: center;
    }

    .error-icon {
      font-size: 80px;
      color: #e74c3c;
      margin-bottom: 20px;
    }

    h2 {
      color: #2c3e50;
      margin-bottom: 15px;
    }

    p {
      color: #7f8c8d;
      font-size: 16px;
      line-height: 1.6;
    }

    code {
      background: #ecf0f1;
      padding: 3px 8px;
      border-radius: 3px;
      color: #e74c3c;
      font-family: monospace;
    }

    .btn {
      display: inline-block;
      margin-top: 25px;
      padding: 12px 30px;
      background: #3498db;
      color: white;
      text-decoration: none;
      border-radius: 5px;
      font-weight: 600;
      transition: background 0.3s;
    }

    .btn:hover {
      background: #2980b9;
    }

    ul {
      text-align: left;
      display: inline-block;
      color: #7f8c8d;
      margin-top: 20px;
    }
  </style>
</head>

<body>
  <div class="error-card">
    <div class="error-icon">
      <i class="fas fa-school"></i>
    </div>
    <h2>Sekolah Tidak Ditemukan</h2>
    <p>
      Maaf, sekolah dengan ID <code>{{ $tenant_id }}</code> tidak ditemukan dalam sistem.
    </p>
    <p style="font-size: 14px; margin-bottom: 10px; color: #95a5a6;">
      Kemungkinan penyebab:
    </p>
    <ul>
      <li>ID sekolah salah atau belum terdaftar</li>
      <li>Akun sekolah sudah tidak aktif</li>
      <li>Typo pada URL yang Anda akses</li>
    </ul>
    <div>
      <a href="/" class="btn">
        <i class="fas fa-home"></i> Kembali ke Beranda
      </a>
    </div>
  </div>
</body>

</html>