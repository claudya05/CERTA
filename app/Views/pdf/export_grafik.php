<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Export Grafik Analisis</title>
    <style>
        body { font-family: Helvetica, Arial, sans-serif; margin: 20px; color: #333; }
        .header { text-align: center; border-bottom: 2px solid #0c2340; padding-bottom: 10px; margin-bottom: 20px; }
        .header h2 { margin: 0; color: #0c2340; }
        .header p { margin: 5px 0 0 0; font-size: 13px; color: #666; }
        .info-table { width: 100%; margin-bottom: 20px; font-size: 13px; }
        .info-table td { padding: 4px 0; }
        .chart-box { text-align: center; margin-top: 15px; }
        .chart-box img { width: 100%; max-height: 420px; object-fit: contain; }
    </style>
</head>
<body>
    <div class="header">
        <h2>LAPORAN ANALISIS DATA HISTORIS</h2>
        <p>Cek Rutin Data Center (CERTA)</p>
    </div>

    <table class="info-table">
        <tr>
            <td width="15%"><strong>Nama Aset</strong></td>
            <td width="2%">:</td>
            <td><?= esc($assetName) ?></td>
        </tr>
        <tr>
            <td><strong>Periode</strong></td>
            <td>:</td>
            <td><?= esc($dateFrom) ?> s/d <?= esc($dateTo) ?></td>
        </tr>
    </table>

    <div class="chart-box">
        <img src="<?= $chartImage ?>" alt="Grafik Tren">
    </div>
</body>
</html>