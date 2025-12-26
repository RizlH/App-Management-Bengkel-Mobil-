<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <style>
        @page { margin: 20px; }
        body { font-family: Arial, sans-serif; font-size: 12px; }
        .header { text-align: center; margin-bottom: 20px; border-bottom: 2px solid #28a745; padding-bottom: 10px; }
        .summary { margin-bottom: 20px; padding: 10px; background-color: #f9f9f9; border: 1px solid #ddd; }
        table { width: 100%; border-collapse: collapse; margin-top: 10px; }
        th { background-color: #f2f2f2; border: 1px solid #ddd; padding: 8px; text-align: left; }
        td { border: 1px solid #ddd; padding: 8px; }
        .total { font-weight: bold; background-color: #e8f4f8; }
        .footer { margin-top: 30px; padding-top: 10px; border-top: 1px solid #ddd; font-size: 10px; text-align: center; }
        .status-badge { 
            display: inline-block; 
            padding: 3px 8px; 
            border-radius: 3px; 
            font-size: 11px; 
            font-weight: bold;
        }
        .status-menunggu { background-color: #dc3545; color: white; }
        .status-proses { background-color: #ffc107; color: #212529; }
        .status-selesai { background-color: #28a745; color: white; }
        .stat-box { 
            display: inline-block; 
            padding: 5px 10px; 
            margin: 0 5px 5px 0; 
            background-color: #e9ecef; 
            border-radius: 3px;
        }
    </style>
</head>
<body>
    <div class="header">
        <h2 style="color: #28a745; margin: 0;">BENGKEL MOBIL</h2>
        <h3 style="margin: 5px 0;">LAPORAN MOBIL MASUK</h3>
        <p style="margin: 5px 0;">Periode: <?= ucwords(str_replace('_', ' ', $periode)) ?></p>
        <p style="margin: 5px 0;"><?= date('d/m/Y', strtotime($date_range['start'])) ?> - <?= date('d/m/Y', strtotime($date_range['end'])) ?></p>
        <p style="margin: 5px 0;">Dicetak: <?= date('d/m/Y H:i:s') ?></p>
    </div>
    
    <div class="summary">
        <table style="border: none; width: 100%;">
            <tr>
                <td style="border: none; width: 40%;"><strong>Total Mobil Masuk:</strong></td>
                <td style="border: none;"><?= $laporan['total_mobil_masuk'] ?> unit</td>
            </tr>
            <tr>
                <td style="border: none;"><strong>Status Pengerjaan:</strong></td>
                <td style="border: none;">
                    <span class="stat-box">Selesai: <?= $laporan['status_count']['selesai'] ?></span>
                    <span class="stat-box">Proses: <?= $laporan['status_count']['proses'] ?></span>
                    <span class="stat-box">Menunggu: <?= $laporan['status_count']['menunggu'] ?></span>
                </td>
            </tr>
            <tr>
                <td style="border: none;"><strong>Periode Laporan:</strong></td>
                <td style="border: none;"><?= ucwords(str_replace('_', ' ', $periode)) ?></td>
            </tr>
        </table>
    </div>
    
    <div style="margin-bottom: 20px; padding: 10px; background-color: #f8f9fa; border: 1px solid #dee2e6;">
        <h4 style="margin: 0 0 10px 0; color: #6c757d;">Distribusi Merek Mobil:</h4>
        <?php foreach ($laporan['merk_count'] as $merk => $count): ?>
        <div style="display: flex; align-items: center; margin-bottom: 5px;">
            <div style="width: 150px;"><?= $merk ?></div>
            <div style="flex: 1;">
                <div style="height: 15px; background-color: #e9ecef; border-radius: 3px; overflow: hidden;">
                    <?php 
                        $persentase = $laporan['total_mobil_masuk'] > 0 ? ($count / $laporan['total_mobil_masuk']) * 100 : 0;
                    ?>
                    <div style="height: 100%; width: <?= $persentase ?>%; background-color: #17a2b8;"></div>
                </div>
            </div>
            <div style="width: 60px; text-align: right;"><?= $count ?> (<?= number_format($persentase, 1) ?>%)</div>
        </div>
        <?php endforeach; ?>
    </div>
    
    <table>
        <thead>
            <tr>
                <th style="width: 5%;">No.</th>
                <th style="width: 15%;">No. Servis</th>
                <th style="width: 12%;">Tanggal</th>
                <th style="width: 20%;">Pelanggan</th>
                <th style="width: 18%;">Kendaraan</th>
                <th style="width: 10%;">Plat</th>
                <th style="width: 10%;">Status</th>
                <th style="width: 20%;">Keluhan</th>
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
                <td>
                    <?php 
                        $statusClass = strtolower($item['status']);
                        $statusText = ucfirst($statusClass);
                    ?>
                    <span class="status-badge status-<?= $statusClass ?>">
                        <?= $statusText ?>
                    </span>
                </td>
                <td><?= substr($item['keluhan'], 0, 50) ?><?= strlen($item['keluhan']) > 50 ? '...' : '' ?></td>
            </tr>
            <?php endforeach; ?>
            <tr class="total">
                <td colspan="7" align="center"><strong>TOTAL MOBIL MASUK</strong></td>
                <td align="center"><strong><?= $laporan['total_mobil_masuk'] ?> unit</strong></td>
            </tr>
        </tbody>
    </table>
    
    <div style="margin-top: 20px; padding: 10px; background-color: #f8f9fa; border: 1px solid #dee2e6;">
        <h4 style="margin: 0 0 10px 0; color: #6c757d;">Ringkasan Analisis:</h4>
        <ul style="margin: 0; padding-left: 20px;">
            <li>Rata-rata mobil masuk per hari: <strong><?= $laporan['total_mobil_masuk'] > 0 ? number_format($laporan['total_mobil_masuk'] / (date_diff(date_create($date_range['start']), date_create($date_range['end']))->days + 1), 1) : 0 ?> unit/hari</strong></li>
            <li>Presentase penyelesaian: <strong><?= $laporan['total_mobil_masuk'] > 0 ? number_format(($laporan['status_count']['selesai'] / $laporan['total_mobil_masuk']) * 100, 1) : 0 ?>%</strong></li>
            <li>Merek paling banyak: 
                <?php 
                    arsort($laporan['merk_count']);
                    $topMerk = key($laporan['merk_count']);
                    echo $topMerk . ' (' . $laporan['merk_count'][$topMerk] . ' unit)';
                ?>
            </li>
        </ul>
    </div>
    
    <div class="footer">
        <p>Dicetak oleh: <?= session()->get('full_name') ?></p>
        <p>Halaman 1</p>
    </div>
</body>
</html>