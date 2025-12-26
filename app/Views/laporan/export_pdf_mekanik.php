<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <style>
        @page { margin: 20px; }
        body { font-family: Arial, sans-serif; font-size: 12px; }
        .header { text-align: center; margin-bottom: 20px; border-bottom: 2px solid #007bff; padding-bottom: 10px; }
        .summary { margin-bottom: 20px; padding: 10px; background-color: #f9f9f9; border: 1px solid #ddd; }
        table { width: 100%; border-collapse: collapse; margin-top: 10px; }
        th { background-color: #f2f2f2; border: 1px solid #ddd; padding: 8px; text-align: left; }
        td { border: 1px solid #ddd; padding: 8px; }
        .total { font-weight: bold; background-color: #e8f4f8; }
        .footer { margin-top: 30px; padding-top: 10px; border-top: 1px solid #ddd; font-size: 10px; text-align: center; }
        .progress-bar { height: 15px; background-color: #e9ecef; border-radius: 3px; overflow: hidden; }
        .progress-fill { height: 100%; background-color: #28a745; }
    </style>
</head>
<body>
    <div class="header">
        <h2 style="color: #007bff; margin: 0;">BENGKEL MOBIL</h2>
        <h3 style="margin: 5px 0;">LAPORAN PENGERJAAN MEKANIK</h3>
        <p style="margin: 5px 0;">Periode: <?= ucwords(str_replace('_', ' ', $periode)) ?></p>
        <p style="margin: 5px 0;"><?= date('d/m/Y', strtotime($date_range['start'])) ?> - <?= date('d/m/Y', strtotime($date_range['end'])) ?></p>
        <p style="margin: 5px 0;">Dicetak: <?= date('d/m/Y H:i:s') ?></p>
    </div>
    
    <div class="summary">
        <table style="border: none; width: 100%;">
            <tr>
                <td style="border: none; width: 40%;"><strong>Total Mekanik Aktif:</strong></td>
                <td style="border: none;"><?= $laporan['total_mekanik'] ?> orang</td>
            </tr>
            <tr>
                <td style="border: none;"><strong>Total Mobil Ditangani:</strong></td>
                <td style="border: none;"><?= $laporan['total_mobil_ditangani'] ?> unit</td>
            </tr>
            <tr>
                <td style="border: none;"><strong>Rata-rata per Mekanik:</strong></td>
                <td style="border: none;"><?= $laporan['total_mekanik'] > 0 ? number_format($laporan['total_mobil_ditangani'] / $laporan['total_mekanik'], 1) : 0 ?> mobil</td>
            </tr>
            <tr>
                <td style="border: none;"><strong>Periode Laporan:</strong></td>
                <td style="border: none;"><?= ucwords(str_replace('_', ' ', $periode)) ?></td>
            </tr>
        </table>
    </div>
    
    <table>
        <thead>
            <tr>
                <th style="width: 5%;">No.</th>
                <th style="width: 25%;">Nama Mekanik</th>
                <th style="width: 20%;">Posisi</th>
                <th style="width: 15%;">Kontak</th>
                <th style="width: 15%;">Jumlah Mobil</th>
                <th style="width: 20%;">Persentase</th>
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
                <td>
                    <div style="display: flex; align-items: center; gap: 8px;">
                        <div style="flex: 1;">
                            <div class="progress-bar">
                                <div class="progress-fill" style="width: <?= $persentase ?>%;"></div>
                            </div>
                        </div>
                        <div style="width: 40px; text-align: right;">
                            <?= number_format($persentase, 1) ?>%
                        </div>
                    </div>
                </td>
            </tr>
            <?php endforeach; ?>
            <tr class="total">
                <td colspan="4" align="center"><strong>TOTAL</strong></td>
                <td align="center"><strong><?= $laporan['total_mobil_ditangani'] ?></strong></td>
                <td align="center"><strong>100%</strong></td>
            </tr>
        </tbody>
    </table>
    
    <div style="margin-top: 20px; padding: 10px; background-color: #f8f9fa; border: 1px solid #dee2e6;">
        <h4 style="margin: 0 0 10px 0; color: #6c757d;">Analisis Pengerjaan:</h4>
        <ul style="margin: 0; padding-left: 20px;">
            <li>Mekanik dengan produktivitas tertinggi: <strong><?= $laporan['data'][0]['nama_mekanik'] ?? '-' ?></strong> (<?= $laporan['data'][0]['total_mobil_ditangani'] ?? 0 ?> mobil)</li>
            <li>Total rata-rata pengerjaan per mekanik: <strong><?= $laporan['total_mekanik'] > 0 ? number_format($laporan['total_mobil_ditangani'] / $laporan['total_mekanik'], 1) : 0 ?> mobil</strong></li>
            <li>Jumlah mekanik dengan pengerjaan di atas rata-rata: 
                <?php
                    $rata_rata = $laporan['total_mekanik'] > 0 ? $laporan['total_mobil_ditangani'] / $laporan['total_mekanik'] : 0;
                    $count_above_avg = 0;
                    foreach ($laporan['data'] as $item) {
                        if ($item['total_mobil_ditangani'] > $rata_rata) {
                            $count_above_avg++;
                        }
                    }
                    echo $count_above_avg;
                ?> orang
            </li>
        </ul>
    </div>
    
    <div class="footer">
        <p>Dicetak oleh: <?= session()->get('full_name') ?></p>
        <p>Halaman 1</p>
    </div>
</body>
</html>