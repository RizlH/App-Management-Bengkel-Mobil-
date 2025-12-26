<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Export Excel Stok Sparepart</title>
    <style>
        body { font-family: Arial, sans-serif; }
        table { width: 100%; border-collapse: collapse; }
        th { background-color: #f2f2f2; border: 1px solid #ddd; padding: 8px; text-align: left; }
        td { border: 1px solid #ddd; padding: 8px; }
        .total { font-weight: bold; background-color: #e8f4f8; }
        .header { text-align: center; margin-bottom: 20px; }
        .summary { margin-bottom: 20px; padding: 10px; background-color: #f9f9f9; border-left: 4px solid #ffc107; }
        .low-stock { background-color: #ffe6e6; }
        .warning { background-color: #fff3cd; }
    </style>
</head>
<body>
    <div class="header">
        <h2>LAPORAN STOK SPAREPART</h2>
        <p>Tanggal Export: <?= date('d/m/Y H:i:s') ?></p>
    </div>
    
    <div class="summary">
        <table style="border: none;">
            <tr>
                <td style="border: none;"><strong>Total Item:</strong></td>
                <td style="border: none;"><?= $laporan['total_item'] ?></td>
            </tr>
            <tr>
                <td style="border: none;"><strong>Stok Rendah (< 5):</strong></td>
                <td style="border: none;"><?= $laporan['stok_rendah'] ?></td>
            </tr>
            <tr>
                <td style="border: none;"><strong>Nilai Total Stok:</strong></td>
                <td style="border: none;">Rp <?= number_format($laporan['total_nilai_stok'], 0, ',', '.') ?></td>
            </tr>
        </table>
    </div>
    
    <table>
        <thead>
            <tr>
                <th>No.</th>
                <th>Nama Barang</th>
                <th>Kategori</th>
                <th>Stok</th>
                <th>Satuan</th>
                <th>Harga Beli (Rp)</th>
                <th>Nilai Stok (Rp)</th>
            </tr>
        </thead>
        <tbody>
            <?php $no = 1; ?>
            <?php foreach ($laporan['data'] as $item): ?>
            <tr <?= $item['stok'] < 5 ? 'class="low-stock"' : ($item['stok'] < 10 ? 'class="warning"' : '') ?>>
                <td><?= $no++ ?></td>
                <td><?= $item['nama_barang'] ?></td>
                <td><?= $item['kategori'] ?></td>
                <td align="center"><?= $item['stok'] ?></td>
                <td><?= $item['satuan'] ?></td>
                <td align="right"><?= number_format($item['harga_beli'], 0, ',', '.') ?></td>
                <td align="right"><?= number_format($item['nilai_stok'], 0, ',', '.') ?></td>
            </tr>
            <?php endforeach; ?>
            <tr class="total">
                <td colspan="6" align="center"><strong>TOTAL NILAI STOK</strong></td>
                <td align="right"><strong>Rp <?= number_format($laporan['total_nilai_stok'], 0, ',', '.') ?></strong></td>
            </tr>
        </tbody>
    </table>
</body>
</html>