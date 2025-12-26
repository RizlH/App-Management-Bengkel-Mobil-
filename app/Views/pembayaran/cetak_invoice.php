<!DOCTYPE html>
<html>
<head>
    <title>Invoice #<?= $pembayaran['nomor_invoice'] ?></title>
    <style>
        body { font-family: Arial, sans-serif; margin: 30px; }
        .header { text-align: center; border-bottom: 3px solid #dc3545; padding-bottom: 20px; margin-bottom: 30px; }
        .header h1 { color: #dc3545; margin: 0; }
        .info-box { margin-bottom: 30px; }
        .info-box table { width: 100%; }
        .info-box td { padding: 5px; }
        .table-invoice { width: 100%; border-collapse: collapse; margin-bottom: 20px; }
        .table-invoice th, .table-invoice td { border: 1px solid #ddd; padding: 10px; }
        .table-invoice th { background-color: #212529; color: white; }
        .total-box { text-align: right; font-size: 18px; font-weight: bold; }
        .footer { text-align: center; margin-top: 50px; padding-top: 20px; border-top: 1px solid #ddd; }
    </style>
</head>
<body>
    <div class="header">
        <h1>🚗 BENGKEL MOBIL</h1>
        <p>Jl. Aria Santika, Kota Tangerang | Telp: 0857-8377-0890</p>
    </div>

    <h2 style="color: #dc3545;">INVOICE PEMBAYARAN</h2>
    
    <div class="info-box">
        <table>
            <tr>
                <td width="120"><strong>No. Invoice</strong></td>
                <td>: <?= $pembayaran['nomor_invoice'] ?></td>
                <td width="120"><strong>Tanggal</strong></td>
                <td>: <?= date('d F Y', strtotime($pembayaran['tanggal_bayar'])) ?></td>
            </tr>
            <tr>
                <td><strong>No. Servis</strong></td>
                <td>: <?= $pembayaran['nomor_servis'] ?></td>
                <td><strong>Metode Bayar</strong></td>
                <td>: <?= $pembayaran['metode_pembayaran'] ?></td>
            </tr>
        </table>
    </div>

    <div class="info-box">
        <h4>Informasi Pelanggan:</h4>
        <table>
            <tr>
                <td width="120"><strong>Nama</strong></td>
                <td>: <?= $pembayaran['nama_pelanggan'] ?></td>
            </tr>
            <tr>
                <td><strong>Alamat</strong></td>
                <td>: <?= $pembayaran['alamat'] ?></td>
            </tr>
            <tr>
                <td><strong>Telepon</strong></td>
                <td>: <?= $pembayaran['no_telepon'] ?></td>
            </tr>
            <tr>
                <td><strong>Kendaraan</strong></td>
                <td>: <?= $pembayaran['nomor_plat'] ?> - <?= $pembayaran['merk'] ?> <?= $pembayaran['tipe'] ?></td>
            </tr>
        </table>
    </div>

    <h4>Detail Pekerjaan:</h4>

    <?php if (!empty($jasa_servis)): ?>
    <h5>Jasa Servis:</h5>
    <table class="table-invoice">
        <thead>
            <tr>
                <th width="5%">No</th>
                <th>Nama Layanan</th>
                <th width="20%">Harga</th>
            </tr>
        </thead>
        <tbody>
            <?php $no = 1; foreach ($jasa_servis as $js): ?>
            <tr>
                <td><?= $no++ ?></td>
                <td><?= $js['nama_layanan'] ?></td>
                <td>Rp <?= number_format($js['harga'], 0, ',', '.') ?></td>
            </tr>
            <?php endforeach; ?>
            <tr>
                <td colspan="2" align="right"><strong>Subtotal Jasa:</strong></td>
                <td><strong>Rp <?= number_format($pembayaran['total_jasa'], 0, ',', '.') ?></strong></td>
            </tr>
        </tbody>
    </table>
    <?php endif; ?>

    <?php if (!empty($sparepart)): ?>
    <h5>Sparepart:</h5>
    <table class="table-invoice">
        <thead>
            <tr>
                <th width="5%">No</th>
                <th>Nama Barang</th>
                <th width="10%">Jumlah</th>
                <th width="15%">Harga Satuan</th>
                <th width="15%">Subtotal</th>
            </tr>
        </thead>
        <tbody>
            <?php $no = 1; foreach ($sparepart as $sp): ?>
            <tr>
                <td><?= $no++ ?></td>
                <td><?= $sp['nama_barang'] ?></td>
                <td><?= $sp['jumlah'] ?> <?= $sp['satuan'] ?></td>
                <td>Rp <?= number_format($sp['harga_satuan'], 0, ',', '.') ?></td>
                <td>Rp <?= number_format($sp['subtotal'], 0, ',', '.') ?></td>
            </tr>
            <?php endforeach; ?>
            <tr>
                <td colspan="4" align="right"><strong>Subtotal Sparepart:</strong></td>
                <td><strong>Rp <?= number_format($pembayaran['total_sparepart'], 0, ',', '.') ?></strong></td>
            </tr>
        </tbody>
    </table>
    <?php endif; ?>

    <div class="total-box">
        <p style="font-size: 24px; color: #dc3545;">
            TOTAL PEMBAYARAN: Rp <?= number_format($pembayaran['total_biaya'], 0, ',', '.') ?>
        </p>
        <p>Status: <span style="color: green;">LUNAS</span></p>
    </div>

    <div class="footer">
        <p>Terima kasih atas kepercayaan Anda!</p>
        <p><small>Invoice ini dicetak pada <?= date('d F Y H:i:s') ?></small></p>
    </div>

    <script>
        window.print();
    </script>
</body>
</html>
