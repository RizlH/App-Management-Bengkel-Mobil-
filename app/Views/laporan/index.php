<?= $this->include('layout/header') ?>

<div class="card">
    <div class="card-header">
        <i class="fas fa-chart-line me-2"></i> Laporan
    </div>
    <div class="card-body">
        <!-- Filter Form -->
        <form method="get" class="mb-4">
            <div class="row">
                <div class="col-md-4 mb-3">
                    <label class="form-label">Jenis Laporan</label>
                    <select name="tipe" class="form-select" id="tipeLaporan">
                        <option value="pendapatan" <?= $tipe_laporan == 'pendapatan' ? 'selected' : '' ?>>Laporan Pendapatan</option>
                        <option value="mekanik" <?= $tipe_laporan == 'mekanik' ? 'selected' : '' ?>>Laporan Pengerjaan Mekanik</option>
                        <option value="mobil_masuk" <?= $tipe_laporan == 'mobil_masuk' ? 'selected' : '' ?>>Laporan Mobil Masuk</option>
                        <option value="stok" <?= $tipe_laporan == 'stok' ? 'selected' : '' ?>>Laporan Stok Sparepart</option>
                    </select>
                </div>
                <div class="col-md-4 mb-3">
                    <label class="form-label">Periode</label>
                    <select name="periode" class="form-select">
                        <option value="hari_ini" <?= $periode == 'hari_ini' ? 'selected' : '' ?>>Hari Ini</option>
                        <option value="minggu_ini" <?= $periode == 'minggu_ini' ? 'selected' : '' ?>>Minggu Ini</option>
                        <option value="bulan_ini" <?= $periode == 'bulan_ini' ? 'selected' : '' ?>>Bulan Ini</option>
                        <option value="tahun_ini" <?= $periode == 'tahun_ini' ? 'selected' : '' ?>>Tahun Ini</option>
                    </select>
                </div>
                <div class="col-md-4 d-flex align-items-end mb-3">
                    <button type="submit" class="btn btn-danger w-100">
                        <i class="fas fa-filter me-1"></i> Tampilkan Laporan
                    </button>
                </div>
            </div>
        </form>

        <!-- Export Buttons -->
        <div class="mb-3">
            <a href="<?= base_url('laporan/exportExcel') ?>?tipe=<?= $tipe_laporan ?>&periode=<?= $periode ?>" 
            class="btn btn-success" target="_blank">
                <i class="fas fa-file-excel me-1"></i> Export Excel
            </a>
            <a href="<?= base_url('laporan/exportPdf') ?>?tipe=<?= $tipe_laporan ?>&periode=<?= $periode ?>" 
            class="btn btn-danger" target="_blank">
                <i class="fas fa-file-pdf me-1"></i> Export PDF
            </a>
        </div>

        <hr>

        <!-- Display Report Based on Type -->
        <?php if ($tipe_laporan == 'pendapatan'): ?>
            <!-- LAPORAN PENDAPATAN -->
            <h5 class="mb-3">Laporan Pendapatan - <?= ucwords(str_replace('_', ' ', $periode)) ?></h5>
            
            <div class="row mb-4">
                <div class="col-md-3 mb-3">
                    <div class="card border-danger">
                        <div class="card-body">
                            <h6 class="text-muted">Total Pendapatan</h6>
                            <h3 class="text-danger">Rp <?= number_format($laporan['total_pendapatan'], 0, ',', '.') ?></h3>
                        </div>
                    </div>
                </div>
                <div class="col-md-3 mb-3">
                    <div class="card border-info">
                        <div class="card-body">
                            <h6 class="text-muted">Dari Jasa Servis</h6>
                            <h3 class="text-info">Rp <?= number_format($laporan['total_jasa'], 0, ',', '.') ?></h3>
                        </div>
                    </div>
                </div>
                <div class="col-md-3 mb-3">
                    <div class="card border-success">
                        <div class="card-body">
                            <h6 class="text-muted">Dari Sparepart</h6>
                            <h3 class="text-success">Rp <?= number_format($laporan['total_sparepart'], 0, ',', '.') ?></h3>
                        </div>
                    </div>
                </div>
                <div class="col-md-3 mb-3">
                    <div class="card border-warning">
                        <div class="card-body">
                            <h6 class="text-muted">Total Transaksi</h6>
                            <h3 class="text-warning"><?= $laporan['jumlah_transaksi'] ?></h3>
                        </div>
                    </div>
                </div>
            </div>

            <div class="alert alert-info">
                <i class="fas fa-info-circle me-2"></i>
                <strong>Rincian:</strong> <?= $laporan['jumlah_servis'] ?> transaksi servis dan 
                <?= $laporan['jumlah_penjualan_sparepart'] ?> transaksi penjualan sparepart
            </div>

            <div class="table-responsive">
                <table class="table table-hover table-striped">
                    <thead>
                        <tr>
                            <th>No. Transaksi</th>
                            <th>Tanggal</th>
                            <th>Jenis</th>
                            <th>Pelanggan/Pembeli</th>
                            <th>Jasa</th>
                            <th>Sparepart</th>
                            <th>Total</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($laporan['data'] as $item): ?>
                        <tr>
                            <td>
                                <?= $item['nomor_transaksi'] ?>
                                <?php if ($item['jenis'] == 'sparepart'): ?>
                                    <span class="badge bg-secondary ms-1">SP</span>
                                <?php else: ?>
                                    <span class="badge bg-primary ms-1">SV</span>
                                <?php endif; ?>
                            </td>
                            <td><?= date('d/m/Y', strtotime($item['tanggal'])) ?></td>
                            <td>
                                <?php if ($item['jenis'] == 'sparepart'): ?>
                                    <span class="badge bg-secondary">Penjualan Sparepart</span>
                                <?php else: ?>
                                    <span class="badge bg-primary">Servis</span>
                                <?php endif; ?>
                            </td>
                            <td><?= $item['pelanggan'] ?></td>
                            <td>Rp <?= number_format($item['total_jasa'], 0, ',', '.') ?></td>
                            <td>Rp <?= number_format($item['total_sparepart'], 0, ',', '.') ?></td>
                            <td><strong>Rp <?= number_format($item['total_biaya'], 0, ',', '.') ?></strong></td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>

        <?php elseif ($tipe_laporan == 'mekanik'): ?>
            <!-- LAPORAN PENGERJAAN MEKANIK -->
            <h5 class="mb-3">Laporan Pengerjaan Mekanik - <?= ucwords(str_replace('_', ' ', $periode)) ?></h5>
            
            <div class="row mb-4">
                <div class="col-md-4 mb-3">
                    <div class="card border-primary">
                        <div class="card-body">
                            <h6 class="text-muted">Total Mekanik Aktif</h6>
                            <h3 class="text-primary"><?= $laporan['total_mekanik'] ?></h3>
                        </div>
                    </div>
                </div>
                <div class="col-md-4 mb-3">
                    <div class="card border-success">
                        <div class="card-body">
                            <h6 class="text-muted">Total Mobil Ditangani</h6>
                            <h3 class="text-success"><?= $laporan['total_mobil_ditangani'] ?></h3>
                        </div>
                    </div>
                </div>
                <div class="col-md-4 mb-3">
                    <div class="card border-warning">
                        <div class="card-body">
                            <h6 class="text-muted">Rata-rata per Mekanik</h6>
                            <h3 class="text-warning">
                                <?= $laporan['total_mekanik'] > 0 ? 
                                    number_format($laporan['total_mobil_ditangani'] / $laporan['total_mekanik'], 1) : 0 ?> mobil
                            </h3>
                        </div>
                    </div>
                </div>
            </div>

            <div class="table-responsive">
                <table class="table table-hover table-striped">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Nama Mekanik</th>
                            <th>Posisi</th>
                            <th>Kontak</th>
                            <th>Jumlah Mobil Ditangani</th>
                            <th>Persentase</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($laporan['data'] as $index => $item): ?>
                        <?php 
                            $persentase = $laporan['total_mobil_ditangani'] > 0 ? 
                                ($item['total_mobil_ditangani'] / $laporan['total_mobil_ditangani']) * 100 : 0;
                        ?>
                        <tr>
                            <td><?= $index + 1 ?></td>
                            <td><?= $item['nama_mekanik'] ?></td>
                            <td><span class="badge bg-info"><?= $item['posisi'] ?></span></td>
                            <td><?= $item['kontak'] ?></td>
                            <td>
                                <div class="d-flex align-items-center">
                                    <span class="me-2"><?= $item['total_mobil_ditangani'] ?></span>
                                    <?php if ($item['total_mobil_ditangani'] > 0): ?>
                                        <i class="fas fa-car text-success"></i>
                                    <?php else: ?>
                                        <i class="fas fa-car text-muted"></i>
                                    <?php endif; ?>
                                </div>
                            </td>
                            <td>
                                <div class="progress" style="height: 20px;">
                                    <div class="progress-bar bg-success" 
                                         role="progressbar" 
                                         style="width: <?= $persentase ?>%"
                                         aria-valuenow="<?= $persentase ?>" 
                                         aria-valuemin="0" 
                                         aria-valuemax="100">
                                        <?= number_format($persentase, 1) ?>%
                                    </div>
                                </div>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>

        <?php elseif ($tipe_laporan == 'mobil_masuk'): ?>
            <!-- LAPORAN MOBIL MASUK -->
            <h5 class="mb-3">Laporan Mobil Masuk - <?= ucwords(str_replace('_', ' ', $periode)) ?></h5>
            
            <div class="row mb-4">
                <div class="col-md-3 mb-3">
                    <div class="card border-primary">
                        <div class="card-body">
                            <h6 class="text-muted">Total Mobil Masuk</h6>
                            <h3 class="text-primary"><?= $laporan['total_mobil_masuk'] ?></h3>
                        </div>
                    </div>
                </div>
                <div class="col-md-3 mb-3">
                    <div class="card border-success">
                        <div class="card-body">
                            <h6 class="text-muted">Selesai</h6>
                            <h3 class="text-success"><?= $laporan['status_count']['selesai'] ?></h3>
                        </div>
                    </div>
                </div>
                <div class="col-md-3 mb-3">
                    <div class="card border-warning">
                        <div class="card-body">
                            <h6 class="text-muted">Dalam Proses</h6>
                            <h3 class="text-warning"><?= $laporan['status_count']['proses'] ?></h3>
                        </div>
                    </div>
                </div>
                <div class="col-md-3 mb-3">
                    <div class="card border-danger">
                        <div class="card-body">
                            <h6 class="text-muted">Menunggu</h6>
                            <h3 class="text-danger"><?= $laporan['status_count']['menunggu'] ?></h3>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Chart for Merk Distribution -->
            <div class="row mb-4">
                <div class="col-md-6">
                    <div class="card">
                        <div class="card-header">
                            <i class="fas fa-chart-pie me-2"></i> Distribusi Merek Mobil
                        </div>
                        <div class="card-body">
                            <ul class="list-group">
                                <?php foreach ($laporan['merk_count'] as $merk => $count): ?>
                                <li class="list-group-item d-flex justify-content-between align-items-center">
                                    <?= $merk ?>
                                    <span class="badge bg-primary rounded-pill"><?= $count ?></span>
                                </li>
                                <?php endforeach; ?>
                            </ul>
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="card">
                        <div class="card-header">
                            <i class="fas fa-chart-bar me-2"></i> Status Pengerjaan
                        </div>
                        <div class="card-body">
                            <ul class="list-group">
                                <?php foreach ($laporan['status_count'] as $status => $count): ?>
                                <?php 
                                    $badgeClass = [
                                        'menunggu' => 'danger',
                                        'proses' => 'warning',
                                        'selesai' => 'success',
                                    ];
                                ?>
                                <li class="list-group-item d-flex justify-content-between align-items-center">
                                    <?= ucfirst($status) ?>
                                    <span class="badge bg-<?= $badgeClass[$status] ?> rounded-pill"><?= $count ?></span>
                                </li>
                                <?php endforeach; ?>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>

            <div class="table-responsive">
                <table class="table table-hover table-striped">
                    <thead>
                        <tr>
                            <th>No. Servis</th>
                            <th>Tanggal Masuk</th>
                            <th>Pelanggan</th>
                            <th>Kendaraan</th>
                            <th>Status</th>
                            <th>Keluhan</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($laporan['data'] as $item): ?>
                        <tr>
                            <td><?= $item['nomor_servis'] ?></td>
                            <td><?= date('d/m/Y', strtotime($item['tanggal_masuk'])) ?></td>
                            <td><?= $item['nama_pelanggan'] ?></td>
                            <td>
                                <strong><?= $item['merk'] ?> <?= $item['tipe'] ?></strong><br>
                                <small class="text-muted"><?= $item['nomor_plat'] ?></small>
                            </td>
                            <td>
                                <?php 
                                    $statusBadge = [
                                        'Menunggu' => 'danger',
                                        'Proses' => 'warning',
                                        'Selesai' => 'success',
                                    ];
                                ?>
                                <span class="badge bg-<?= $statusBadge[$item['status']] ?? 'secondary' ?>">
                                    <?= $item['status'] ?>
                                </span>
                            </td>
                            <td><?= $item['keluhan'] ?></td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>

        <?php elseif ($tipe_laporan == 'stok'): ?>
            <!-- LAPORAN STOK SPAREPART -->
            <h5 class="mb-3">Laporan Stok Sparepart</h5>
            
            <div class="row mb-4">
                <div class="col-md-3 mb-3">
                    <div class="card border-primary">
                        <div class="card-body">
                            <h6 class="text-muted">Total Item</h6>
                            <h3 class="text-primary"><?= $laporan['total_item'] ?></h3>
                        </div>
                    </div>
                </div>
                <div class="col-md-3 mb-3">
                    <div class="card border-warning">
                        <div class="card-body">
                            <h6 class="text-muted">Stok Rendah (< 5)</h6>
                            <h3 class="text-warning"><?= $laporan['stok_rendah'] ?></h3>
                        </div>
                    </div>
                </div>
                <div class="col-md-3 mb-3">
                    <div class="card border-success">
                        <div class="card-body">
                            <h6 class="text-muted">Stok Aman</h6>
                            <h3 class="text-success"><?= $laporan['total_item'] - $laporan['stok_rendah'] ?></h3>
                        </div>
                    </div>
                </div>
                <div class="col-md-3 mb-3">
                    <div class="card border-info">
                        <div class="card-body">
                            <h6 class="text-muted">Nilai Total Stok</h6>
                            <h3 class="text-info">Rp <?= number_format($laporan['total_nilai_stok'], 0, ',', '.') ?></h3>
                        </div>
                    </div>
                </div>
            </div>

            <?php if ($laporan['stok_rendah'] > 0): ?>
            <div class="alert alert-warning">
                <i class="fas fa-exclamation-triangle me-2"></i>
                <strong>Perhatian:</strong> Terdapat <?= $laporan['stok_rendah'] ?> item dengan stok rendah (< 5)
            </div>
            <?php endif; ?>

            <div class="table-responsive">
                <table class="table table-hover table-striped">
                    <thead>
                        <tr>
                            <th>Nama Barang</th>
                            <th>Kategori</th>
                            <th>Stok</th>
                            <th>Satuan</th>
                            <th>Harga Beli</th>
                            <th>Nilai Stok</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($laporan['data'] as $item): ?>
                        <tr>
                            <td><?= $item['nama_barang'] ?></td>
                            <td><span class="badge bg-secondary"><?= $item['kategori'] ?></span></td>
                            <td>
                                <?php if ($item['stok'] < 5): ?>
                                    <span class="badge bg-danger"><?= $item['stok'] ?></span>
                                <?php elseif ($item['stok'] < 10): ?>
                                    <span class="badge bg-warning"><?= $item['stok'] ?></span>
                                <?php else: ?>
                                    <span class="badge bg-success"><?= $item['stok'] ?></span>
                                <?php endif; ?>
                            </td>
                            <td><?= $item['satuan'] ?></td>
                            <td>Rp <?= number_format($item['harga_beli'], 0, ',', '.') ?></td>
                            <td><strong>Rp <?= number_format($item['nilai_stok'], 0, ',', '.') ?></strong></td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        <?php endif; ?>
    </div>
</div>

<?= $this->include('layout/footer') ?>