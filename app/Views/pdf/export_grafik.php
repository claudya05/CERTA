<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Export Grafik Tren</title>
    <style>
        body {
            font-family: Helvetica, Arial, sans-serif;
            color: #333;
            margin: 20px;
        }
        .header {
            text-align: center;
            margin-bottom: 25px;
            border-bottom: 2px solid #333;
            padding-bottom: 10px;
        }
        .header h2 {
            margin: 0;
            font-size: 18px;
            text-transform: uppercase;
        }
        .header p {
            margin: 5px 0 0;
            font-size: 12px;
            color: #555;
        }
        .chart-container {
            text-align: center;
            margin-top: 15px;
        }
        .chart-container img {
            max-width: 100%;
            height: auto;
            border: 1px solid #ddd;
            padding: 5px;
        }
    </style>
</head>
<body>

    <div class="header">
        <h2>Laporan Grafik Tren Analisis Data</h2>
        <p>Aset: <strong><?= esc($assetName) ?></strong> | Periode: <?= $dateFrom ?> s/d <?= $dateTo ?></p>
    </div>

    <div class="chart-container">
        <?php if ($chartImage): ?>
            <!-- Menampilkan data gambar Base64 langsung ke elemen <img> -->
            <img src="<?= $chartImage ?>" alt="Grafik Tren">
        <?php else: ?>
            <p style="color: red;">Grafik tidak dapat dimuat.</p>
        <?php endif; ?>
    </div>

</body>
</html> 