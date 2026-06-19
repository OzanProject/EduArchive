<!DOCTYPE html>
<html lang="id">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>@yield('title', 'Cetak Data Siswa')</title>
  <style>
    body {
      font-family: 'Times New Roman', Times, serif;
      font-size: 12pt;
      line-height: 1.3;
      color: #000;
    }

    .container {
      width: 100%;
      max-width: 800px;
      margin: 0 auto;
      padding: 10px 20px;
    }

    /* Kop Surat */
    .kop-surat {
      width: 100%;
      border-bottom: 4px double #000;
      padding-bottom: 5px;
      margin-bottom: 15px;
    }

    .kop-table {
      width: 100%;
      border: none;
      margin-bottom: 0px;
    }

    .kop-table td {
      vertical-align: middle;
      text-align: center;
    }

    .logo-cell {
      width: 15%;
    }

    .logo-img {
      width: 75px;
      height: auto;
    }

    .kop-text {
      width: 70%;
      padding: 0 5px;
    }

    .kop-text h2 {
      margin: 0;
      font-size: 14pt;
      font-weight: bold;
      text-transform: uppercase;
      line-height: 1.2;
    }

    .kop-text h1 {
      margin: 2px 0;
      font-size: 18pt;
      font-weight: bold;
      text-transform: uppercase;
      line-height: 1.2;
    }

    .kop-text p {
      margin: 0;
      font-size: 10pt;
      font-style: italic;
      line-height: 1.2;
    }

    .header-title {
      text-align: center;
      margin: 10px 0;
      text-decoration: underline;
      font-weight: bold;
      font-size: 14pt;
    }

    .photo-section {
      text-align: center;
      margin-bottom: 10px;
    }

    .photo-section img {
      width: 110px;
      height: 147px;
      object-fit: cover;
      border: 1px solid #777;
      padding: 3px;
    }

    .data-table {
      width: 100%;
      border-collapse: collapse;
      margin-top: 5px;
    }

    .data-table td {
      padding: 4px 5px;
      vertical-align: top;
      font-size: 12pt;
    }

    .data-table td:first-child {
      width: 200px;
    }

    .data-table td:nth-child(2) {
      width: 20px;
      text-align: center;
    }

    .footer {
      margin-top: 30px;
      width: 100%;
      font-size: 12pt;
    }

    .ttd-kanan {
      width: 40%;
      float: right;
      position: relative;
      text-align: center;
    }

    .signature-container {
      position: relative;
      height: 90px;
      width: 220px;
      margin: 5px auto;
    }

    .signature-img {
      height: 80px;
      position: absolute;
      bottom: 0;
      left: 50%;
      transform: translateX(-50%);
      z-index: 2;
    }

    .stamp-img {
      height: 75px;
      position: absolute;
      bottom: 5px;
      left: 0;
      z-index: 1;
      opacity: 0.8;
      transform: rotate(-5deg);
    }

    @media print {
      @page {
        margin: 1cm;
        size: A4 portrait;
      }

      body {
        -webkit-print-color-adjust: exact;
      }

      .page-break {
        page-break-after: always;
      }
      
      .no-print {
        display: none;
      }
    }
  </style>
</head>

<body onload="window.print()">
  @yield('content')
</body>

</html>
