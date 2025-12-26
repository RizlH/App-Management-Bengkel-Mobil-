<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Export Excel Pengerjaan Mekanik</title>
    <style>
        body { font-family: Arial, sans-serif; }
        table { width: 100%; border-collapse: collapse; }
        th { background-color: #f2f2f2; border: 1px solid #ddd; padding: 8px; text-align: left; }
        td { border: 1px solid #ddd; padding: 8px; }
        .total { font-weight: bold; background-color: #e8f4f8; }
        .header { text-align: center; margin-bottom: 20px; }
        .summary { margin-bottom: 20px; padding: 10px; background-color: #f9f9f9; border-left: 4px solid #007bff; }
    </style>
</head>
<body>
    <div class="header">
        <h2>LAPORAN PENGERJAAN MEKANIK</h2>
        <h3>Periode: <?= ucwords(str_replace('_', ' ', $periode)) ?></h3>
        <p>Tanggal Export: <?= date('d/m/Y H:i:s') ?></p>
        <p>Periode: <?= date('d/m/Y', strtotime($date_range['start'])) ?> - <?= date('d/m/Y', strtotime($date_range['end'])) ?></p>
    </div>
    
    <div class="summary">
        <table style="border: none;">
            <tr>
                <td style="border: none;"><strong>Total Mekanik Aktif:</strong></td>
                <td style="border: none;"><?= $laporan['total_mekanik'] ?></td>
            </tr>
            <tr>
                <td style="border: none;"><strong>Total Mobil Ditangani:</strong></td>
                <td style="border: none;"><?= $laporan['total_mobil_ditangani'] ?></td>
            </tr>
            <tr>
                <td style="border: none;"><strong>Rata-rata per Mekanik:</strong></td>
                <td style="border: none;"><?= $laporan['total_mekanik'] > 0 ? number_format($laporan['total_mobil_ditangani'] / $laporan['total_mekanik'], 1) : 0 ?> mobil</td>
            </tr>
        </table>
    </div>
    
    <table>
        <thead>
            <tr>
                <th>No.</th>
                <th>Nama Mekanik</th>
                <th>Posisi</th>
                <th>Kontak</th>
                <th>Jumlah Mobil</th>
                <th>Persentase (%)</th>
            </tr>
        </thead>
        <tbody>
            <?php $no = 1; ?>
            <?php foreach ($laporan['data'] as $item): ?>
            <?php 
                $persentase = $laporan['total_mobil_ditangani'] > 0 ? 
                    ($item['total_mobil_ditangani'] / $laporan['total_mobil_ditangani']) * 100 : 0;
            ?>
            <tr>
                <td><?= $no++ ?></td>
                <td><?= $item['nama_mekanik'] ?></td>
                <td><?= $item['posisi'] ?></td>
                <td><?= $item['kontak'] ?></td>
                <td align="center"><?= $item['total_mobil_ditangani'] ?></td>
                <td align="right"><?= number_format($persentase, 2) ?></td>
            </tr>
            <?php endforeach; ?>
            <tr class="total">
                <td colspan="4" align="center"><strong>TOTAL</strong></td>
                <td align="center"><strong><?= $laporan['total_mobil_ditangani'] ?></strong></td>
                <td align="right"><strong>100.00</strong></td>
            </tr>
        </tbody>
    </table>
</body>
</html>