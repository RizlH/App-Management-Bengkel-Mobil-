<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Export Excel Pendapatan</title>
    <style>
        body { font-family: Arial, sans-serif; }
        table { width: 100%; border-collapse: collapse; }
        th { background-color: #f2f2f2; border: 1px solid #ddd; padding: 8px; text-align: left; }
        td { border: 1px solid #ddd; padding: 8px; }
        .total { font-weight: bold; background-color: #e8f4f8; }
        .header { text-align: center; margin-bottom: 20px; }
        .summary { margin-bottom: 20px; padding: 10px; background-color: #f9f9f9; border-left: 4px solid #dc3545; }
    </style>
</head>
<body>
    <div class="header">
        <h2>LAPORAN PENDAPATAN</h2>
        <h3>Periode: <?= ucwords(str_replace('_', ' ', $periode)) ?></h3>
        <p>Tanggal Export: <?= date('d/m/Y H:i:s') ?></p>
        <p>Periode: <?= date('d/m/Y', strtotime($date_range['start'])) ?> - <?= date('d/m/Y', strtotime($date_range['end'])) ?></p>
    </div>
    
    <div class="summary">
        <table style="border: none;">
            <tr>
                <td style="border: none;"><strong>Total Pendapatan:</strong></td>
                <td style="border: none;">Rp <?= number_format($laporan['total_pendapatan'], 0, ',', '.') ?></td>
            </tr>
            <tr>
                <td style="border: none;"><strong>Total Jasa Servis:</strong></td>
                <td style="border: none;">Rp <?= number_format($laporan['total_jasa'], 0, ',', '.') ?></td>
            </tr>
            <tr>
                <td style="border: none;"><strong>Total Penjualan Sparepart:</strong></td>
                <td style="border: none;">Rp <?= number_format($laporan['total_sparepart'], 0, ',', '.') ?></td>
            </tr>
            <tr>
                <td style="border: none;"><strong>Jumlah Transaksi:</strong></td>
                <td style="border: none;"><?= $laporan['jumlah_transaksi'] ?> (<?= $laporan['jumlah_servis'] ?> servis + <?= $laporan['jumlah_penjualan_sparepart'] ?> sparepart)</td>
            </tr>
        </table>
    </div>
    
    <table>
        <thead>
            <tr>
                <th>No.</th>
                <th>No. Transaksi</th>
                <th>Tanggal</th>
                <th>Jenis</th>
                <th>Pelanggan/Pembeli</th>
                <th>Jasa (Rp)</th>
                <th>Sparepart (Rp)</th>
                <th>Total (Rp)</th>
            </tr>
        </thead>
        <tbody>
            <?php $no = 1; ?>
            <?php foreach ($laporan['data'] as $item): ?>
            <tr>
                <td><?= $no++ ?></td>
                <td><?= $item['nomor_transaksi'] ?></td>
                <td><?= date('d/m/Y', strtotime($item['tanggal'])) ?></td>
                <td><?= $item['jenis'] == 'servis' ? 'Servis' : 'Penjualan Sparepart' ?></td>
                <td><?= $item['pelanggan'] ?></td>
                <td align="right"><?= number_format($item['total_jasa'], 0, ',', '.') ?></td>
                <td align="right"><?= number_format($item['total_sparepart'], 0, ',', '.') ?></td>
                <td align="right"><strong><?= number_format($item['total_biaya'], 0, ',', '.') ?></strong></td>
            </tr>
            <?php endforeach; ?>
            <tr class="total">
                <td colspan="5" align="center"><strong>TOTAL</strong></td>
                <td align="right"><strong><?= number_format($laporan['total_jasa'], 0, ',', '.') ?></strong></td>
                <td align="right"><strong><?= number_format($laporan['total_sparepart'], 0, ',', '.') ?></strong></td>
                <td align="right"><strong><?= number_format($laporan['total_pendapatan'], 0, ',', '.') ?></strong></td>
            </tr>
        </tbody>
    </table>
</body>
</html>