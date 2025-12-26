<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <style>
        @page { margin: 20px; }
        body { font-family: Arial, sans-serif; font-size: 12px; }
        .header { text-align: center; margin-bottom: 20px; border-bottom: 2px solid #ffc107; padding-bottom: 10px; }
        .summary { margin-bottom: 20px; padding: 10px; background-color: #f9f9f9; border: 1px solid #ddd; }
        table { width: 100%; border-collapse: collapse; margin-top: 10px; }
        th { background-color: #f2f2f2; border: 1px solid #ddd; padding: 8px; text-align: left; }
        td { border: 1px solid #ddd; padding: 8px; }
        .total { font-weight: bold; background-color: #e8f4f8; }
        .footer { margin-top: 30px; padding-top: 10px; border-top: 1px solid #ddd; font-size: 10px; text-align: center; }
        .low-stock { background-color: #ffe6e6 !important; }
        .warning-stock { background-color: #fff3cd !important; }
        .good-stock { background-color: #d4edda !important; }
        .stock-indicator { 
            display: inline-block; 
            width: 10px; 
            height: 10px; 
            border-radius: 50%; 
            margin-right: 5px;
        }
        .stock-critical { background-color: #dc3545; }
        .stock-low { background-color: #ffc107; }
        .stock-good { background-color: #28a745; }
        .category-header { 
            background-color: #6c757d !important; 
            color: white; 
            font-weight: bold; 
            font-size: 14px; 
            text-align: center;
        }
    </style>
</head>
<body>
    <div class="header">
        <h2 style="color: #ffc107; margin: 0;">BENGKEL MOBIL</h2>
        <h3 style="margin: 5px 0;">LAPORAN STOK SPAREPART</h3>
        <p style="margin: 5px 0;">Tanggal: <?= date('d/m/Y H:i:s') ?></p>
        <p style="margin: 5px 0;">Dicetak oleh: <?= session()->get('full_name') ?></p>
    </div>
    
    <div class="summary">
        <table style="border: none; width: 100%;">
            <tr>
                <td style="border: none; width: 40%;"><strong>Total Item Sparepart:</strong></td>
                <td style="border: none;"><?= $laporan['total_item'] ?> jenis</td>
            </tr>
            <tr>
                <td style="border: none;"><strong>Status Stok:</strong></td>
                <td style="border: none;">
                    <span style="display: inline-flex; align-items: center; margin-right: 15px;">
                        <span class="stock-indicator stock-critical"></span> Kritis (< 5): <?= $laporan['stok_rendah'] ?>
                    </span>
                    <span style="display: inline-flex; align-items: center;">
                        <span class="stock-indicator stock-good"></span> Aman (≥ 5): <?= $laporan['total_item'] - $laporan['stok_rendah'] ?>
                    </span>
                </td>
            </tr>
            <tr>
                <td style="border: none;"><strong>Total Nilai Stok:</strong></td>
                <td style="border: none;"><strong>Rp <?= number_format($laporan['total_nilai_stok'], 0, ',', '.') ?></strong></td>
            </tr>
        </table>
    </div>
    
    <?php 
    // Group by kategori
    $groupedData = [];
    foreach ($laporan['data'] as $item) {
        $kategori = $item['kategori'];
        if (!isset($groupedData[$kategori])) {
            $groupedData[$kategori] = [];
        }
        $groupedData[$kategori][] = $item;
    }
    
    $no = 1;
    foreach ($groupedData as $kategori => $items): 
        $kategoriTotal = 0;
        foreach ($items as $item) {
            $kategoriTotal += $item['nilai_stok'];
        }
    ?>
    
    <div style="margin: 20px 0 10px 0;">
        <h4 style="color: #6c757d; margin: 0; border-bottom: 1px solid #dee2e6; padding-bottom: 5px;">
            Kategori: <?= $kategori ?> 
            <span style="float: right; font-size: 12px; color: #28a745;">
                Total Nilai: Rp <?= number_format($kategoriTotal, 0, ',', '.') ?>
            </span>
        </h4>
    </div>
    
    <table>
        <thead>
            <tr>
                <th style="width: 5%;">No.</th>
                <th style="width: 30%;">Nama Barang</th>
                <th style="width: 15%;">Stok</th>
                <th style="width: 10%;">Satuan</th>
                <th style="width: 15%;">Harga Beli</th>
                <th style="width: 15%;">Nilai Stok</th>
                <th style="width: 10%;">Status</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($items as $item): ?>
            <?php 
                $stockClass = '';
                $statusColor = '';
                $statusText = '';
                
                if ($item['stok'] < 5) {
                    $stockClass = 'low-stock';
                    $statusColor = 'stock-critical';
                    $statusText = 'Kritis';
                } elseif ($item['stok'] < 10) {
                    $stockClass = 'warning-stock';
                    $statusColor = 'stock-low';
                    $statusText = 'Rendah';
                } else {
                    $stockClass = 'good-stock';
                    $statusColor = 'stock-good';
                    $statusText = 'Aman';
                }
            ?>
            <tr class="<?= $stockClass ?>">
                <td><?= $no++ ?></td>
                <td><?= $item['nama_barang'] ?></td>
                <td align="center"><?= $item['stok'] ?></td>
                <td align="center"><?= $item['satuan'] ?></td>
                <td align="right">Rp <?= number_format($item['harga_beli'], 0, ',', '.') ?></td>
                <td align="right"><strong>Rp <?= number_format($item['nilai_stok'], 0, ',', '.') ?></strong></td>
                <td align="center">
                    <div style="display: flex; align-items: center; justify-content: center;">
                        <span class="stock-indicator <?= $statusColor ?>"></span>
                        <?= $statusText ?>
                    </div>
                </td>
            </tr>
            <?php endforeach; ?>
            <tr class="total">
                <td colspan="5" align="center"><strong>SUBTOTAL <?= strtoupper($kategori) ?></strong></td>
                <td colspan="2" align="right"><strong>Rp <?= number_format($kategoriTotal, 0, ',', '.') ?></strong></td>
            </tr>
        </tbody>
    </table>
    
    <?php endforeach; ?>
    
    <!-- Ringkasan Akhir -->
    <div style="margin-top: 30px; padding: 15px; background-color: #f8f9fa; border: 2px solid #6c757d;">
        <table style="border: none; width: 100%;">
            <tr>
                <td style="border: none; width: 70%; text-align: right;"><strong>GRAND TOTAL NILAI STOK:</strong></td>
                <td style="border: none; width: 30%; text-align: right; font-size: 16px; color: #dc3545;">
                    <strong>Rp <?= number_format($laporan['total_nilai_stok'], 0, ',', '.') ?></strong>
                </td>
            </tr>
            <tr>
                <td style="border: none; text-align: right;"><strong>Total Item Sparepart:</strong></td>
                <td style="border: none; text-align: right;"><strong><?= $laporan['total_item'] ?> jenis</strong></td>
            </tr>
            <tr>
                <td style="border: none; text-align: right;"><strong>Item dengan Stok Kritis:</strong></td>
                <td style="border: none; text-align: right;">
                    <strong style="color: #dc3545;"><?= $laporan['stok_rendah'] ?> item</strong>
                    (<?= $laporan['total_item'] > 0 ? number_format(($laporan['stok_rendah'] / $laporan['total_item']) * 100, 1) : 0 ?>%)
                </td>
            </tr>
        </table>
    </div>
    
    <div style="margin-top: 20px; padding: 10px; background-color: #fff3cd; border: 1px solid #ffc107;">
        <h4 style="margin: 0 0 10px 0; color: #856404;">Rekomendasi:</h4>
        <?php if ($laporan['stok_rendah'] > 0): ?>
        <ul style="margin: 0; padding-left: 20px;">
            <li><strong>PERHATIAN:</strong> Terdapat <?= $laporan['stok_rendah'] ?> item dengan stok kritis (< 5 unit)</li>
            <li>Disarankan untuk melakukan restock segera pada item-item tersebut</li>
            <li>Prioritaskan restock pada item dengan tingkat penggunaan tinggi</li>
        </ul>
        <?php else: ?>
        <p style="margin: 0; color: #28a745;">
            <strong>BAIK:</strong> Tidak ada item dengan stok kritis. Stok dalam kondisi aman.
        </p>
        <?php endif; ?>
    </div>
    
    <div class="footer">
        <p>Dicetak oleh: <?= session()->get('full_name') ?></p>
        <p>Tanggal: <?= date('d/m/Y H:i:s') ?></p>
        <p>Halaman 1</p>
    </div>
</body>
</html>