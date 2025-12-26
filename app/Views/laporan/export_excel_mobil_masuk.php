<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Export Excel Mobil Masuk</title>
    <style>
        body { font-family: Arial, sans-serif; }
        table { width: 100%; border-collapse: collapse; }
        th { background-color: #f2f2f2; border: 1px solid #ddd; padding: 8px; text-align: left; }
        td { border: 1px solid #ddd; padding: 8px; }
        .total { font-weight: bold; background-color: #e8f4f8; }
        .header { text-align: center; margin-bottom: 20px; }
        .summary { margin-bottom: 20px; padding: 10px; background-color: #f9f9f9; border-left: 4px solid #28a745; }
    </style>
</head>
<body>
    <div class="header">
        <h2>LAPORAN MOBIL MASUK</h2>
        <h3>Periode: <?= ucwords(str_replace('_', ' ', $periode)) ?></h3>
        <p>Tanggal Export: <?= date('d/m/Y H:i:s') ?></p>
        <p>Periode: <?= date('d/m/Y', strtotime($date_range['start'])) ?> - <?= date('d/m/Y', strtotime($date_range['end'])) ?></p>
    </div>
    
    <div class="summary">
        <table style="border: none;">
            <tr>
                <td style="border: none;"><strong>Total Mobil Masuk:</strong></td>
                <td style="border: none;"><?= $laporan['total_mobil_masuk'] ?></td>
            </tr>
            <tr>
                <td style="border: none;"><strong>Selesai:</strong></td>
                <td style="border: none;"><?= $laporan['status_count']['selesai'] ?></td>
            </tr>
            <tr>
                <td style="border: none;"><strong>Dalam Proses:</strong></td>
                <td style="border: none;"><?= $laporan['status_count']['proses'] ?></td>
            </tr>
            <tr>
                <td style="border: none;"><strong>Menunggu:</strong></td>
                <td style="border: none;"><?= $laporan['status_count']['menunggu'] ?></td>
            </tr>
        </table>
    </div>
    
    <table>
        <thead>
            <tr>
                <th>No.</th>
                <th>No. Servis</th>
                <th>Tanggal</th>
                <th>Pelanggan</th>
                <th>Kendaraan</th>
                <th>Plat</th>
                <th>Status</th>
                <th>Keluhan</th>
            </tr>
        </thead>
        <tbody>
            <?php $no = 1; ?>
            <?php foreach ($laporan['data'] as $item): ?>
            <tr>
                <td><?= $no++ ?></td>
                <td><?= $item['nomor_servis'] ?></td>
                <td><?= date('d/m/Y', strtotime($item['tanggal_masuk'])) ?></td>
                <td><?= $item['nama_pelanggan'] ?></td>
                <td><?= $item['merk'] ?> <?= $item['tipe'] ?></td>
                <td><?= $item['nomor_plat'] ?></td>
                <td><?= $item['status'] ?></td>
                <td><?= $item['keluhan'] ?></td>
            </tr>
            <?php endforeach; ?>
            <tr class="total">
                <td colspan="7" align="center"><strong>TOTAL MOBIL MASUK</strong></td>
                <td align="center"><strong><?= $laporan['total_mobil_masuk'] ?></strong></td>
            </tr>
        </tbody>
    </table>
</body>
</html>