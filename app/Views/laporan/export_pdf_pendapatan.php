<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <style>
        @page { margin: 20px; }
        body { font-family: Arial, sans-serif; font-size: 12px; }
        .header { text-align: center; margin-bottom: 20px; border-bottom: 2px solid #dc3545; padding-bottom: 10px; }
        .summary { margin-bottom: 20px; padding: 10px; background-color: #f9f9f9; border: 1px solid #ddd; }
        table { width: 100%; border-collapse: collapse; margin-top: 10px; }
        th { background-color: #f2f2f2; border: 1px solid #ddd; padding: 8px; text-align: left; }
        td { border: 1px solid #ddd; padding: 8px; }
        .total { font-weight: bold; background-color: #e8f4f8; }
        .footer { margin-top: 30px; padding-top: 10px; border-top: 1px solid #ddd; font-size: 10px; text-align: center; }
    </style>
</head>
<body>
    <div class="header">
        <h2 style="color: #dc3545; margin: 0;">BENGKEL MOBIL</h2>
        <h3 style="margin: 5px 0;">LAPORAN PENDAPATAN</h3>
        <p style="margin: 5px 0;">Periode: <?= ucwords(str_replace('_', ' ', $periode)) ?></p>
        <p style="margin: 5px 0;"><?= date('d/m/Y', strtotime($date_range['start'])) ?> - <?= date('d/m/Y', strtotime($date_range['end'])) ?></p>
        <p style="margin: 5px 0;">Dicetak: <?= date('d/m/Y H:i:s') ?></p>
    </div>
    
    <div class="summary">
        <table style="border: none; width: 100%;">
            <tr>
                <td style="border: none; width: 30%;"><strong>Total Pendapatan:</strong></td>
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
                <td style="border: none;"><?= $laporan['jumlah_transaksi'] ?> transaksi</td>
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
                <td><?= $item['jenis'] == 'servis' ? 'Servis' : 'Sparepart' ?></td>
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
    
    <div class="footer">
        <p>Dicetak oleh: <?= session()->get('full_name') ?></p>
        <p>Halaman 1</p>
    </div>
</body>
</html>