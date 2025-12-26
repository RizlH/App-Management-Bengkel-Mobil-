<?= $this->include('layout/header') ?>

<div class="row">
    <div class="col-md-8">
        <!-- Detail Penjualan -->
        <div class="card">
            <div class="card-header bg-danger text-white d-flex justify-content-between align-items-center">
                <span><i class="fas fa-file-invoice me-2"></i> Detail Penjualan Sparepart</span>
                <span class="badge bg-light text-dark">ID: #<?= $penjualan['id'] ?></span>
            </div>
            <div class="card-body">
                <!-- Info Penjualan -->
                <div class="row mb-4">
                    <div class="col-md-6">
                        <table class="table table-sm table-borderless">
                            <tr>
                                <td width="150"><strong>Nomor Penjualan</strong></td>
                                <td>: <span class="badge bg-danger"><?= esc($penjualan['nomor_penjualan']) ?></span></td>
                            </tr>
                            <tr>
                                <td><strong>Nama Pembeli</strong></td>
                                <td>: <?= esc($penjualan['nama_pembeli']) ?></td>
                            </tr>
                        </table>
                    </div>
                    <div class="col-md-6">
                        <table class="table table-sm table-borderless">
                            <tr>
                                <td width="150"><strong>Tanggal Penjualan</strong></td>
                                <td>: <?= date('d F Y', strtotime($penjualan['tanggal_penjualan'])) ?></td>
                            </tr>
                            <tr>
                                <td><strong>Waktu Input</strong></td>
                                <td>: <?= date('d/m/Y H:i:s', strtotime($penjualan['created_at'])) ?></td>
                            </tr>
                        </table>
                    </div>
                </div>

                <hr>

                <!-- Detail Items -->
                <h6 class="mb-3"><strong><i class="fas fa-list me-2"></i>Detail Item Sparepart</strong></h6>
                
                <?php if (empty($detail)): ?>
                    <div class="alert alert-warning">
                        <i class="fas fa-exclamation-triangle me-2"></i>
                        Tidak ada detail sparepart
                    </div>
                <?php else: ?>
                    <div class="table-responsive">
                        <table class="table table-sm table-hover">
                            <thead class="table-dark">
                                <tr>
                                    <th width="50" class="text-center">No</th>
                                    <th>Nama Sparepart</th>
                                    <th width="100" class="text-center">Jumlah</th>
                                    <th width="150" class="text-end">Harga Satuan</th>
                                    <th width="180" class="text-end">Subtotal</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php 
                                $no = 1; 
                                $grandTotal = 0;
                                foreach ($detail as $item): 
                                    $grandTotal += $item['subtotal'];
                                ?>
                                    <tr>
                                        <td class="text-center"><?= $no++ ?></td>
                                        <td>
                                            <strong><?= esc($item['nama_barang']) ?></strong>
                                            <br>
                                            <small class="text-muted">
                                                <i class="fas fa-box me-1"></i>Satuan: <?= esc($item['satuan']) ?>
                                            </small>
                                        </td>
                                        <td class="text-center">
                                            <span class="badge bg-info">
                                                <?= number_format($item['jumlah'], 0, ',', '.') ?> <?= esc($item['satuan']) ?>
                                            </span>
                                        </td>
                                        <td class="text-end">
                                            Rp <?= number_format($item['harga_jual'], 0, ',', '.') ?>
                                        </td>
                                        <td class="text-end">
                                            <strong>Rp <?= number_format($item['subtotal'], 0, ',', '.') ?></strong>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                            <tfoot class="table-danger">
                                <tr>
                                    <td colspan="4" class="text-end">
                                        <strong class="text-white">TOTAL PENJUALAN:</strong>
                                    </td>
                                    <td class="text-end">
                                        <h5 class="mb-0 text-white">
                                            <strong>Rp <?= number_format($penjualan['total_penjualan'], 0, ',', '.') ?></strong>
                                        </h5>
                                    </td>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                <?php endif; ?>

                <!-- Catatan -->
                <div class="alert alert-light border mt-4">
                    <i class="fas fa-info-circle text-danger me-2"></i>
                    <strong>Informasi:</strong>
                    <ul class="mb-0 mt-2">
                        <li>Stok sparepart telah dikurangi otomatis saat transaksi ini disimpan</li>
                        <li>Jika transaksi dihapus, stok akan dikembalikan secara otomatis</li>
                        <li>Data ini masuk dalam laporan penjualan sparepart</li>
                    </ul>
                </div>
            </div>
        </div>
    </div>

    <div class="col-md-4">
        <!-- Summary Card -->
        <div class="card border-danger">
            <div class="card-header bg-danger text-white text-center">
                <h6 class="mb-0"><i class="fas fa-calculator me-2"></i>RINGKASAN</h6>
            </div>
            <div class="card-body">
                <table class="table table-sm table-borderless mb-0">
                    <tr>
                        <td><i class="fas fa-shopping-cart text-danger me-2"></i>Total Item</td>
                        <td class="text-end"><strong><?= count($detail) ?></strong></td>
                    </tr>
                    <tr>
                        <td><i class="fas fa-boxes text-danger me-2"></i>Total Qty</td>
                        <td class="text-end">
                            <strong>
                                <?php 
                                $totalQty = 0;
                                foreach ($detail as $item) {
                                    $totalQty += $item['jumlah'];
                                }
                                echo number_format($totalQty, 0, ',', '.');
                                ?>
                            </strong>
                        </td>
                    </tr>
                </table>
                <hr>
                <div class="text-center">
                    <h5 class="text-muted mb-1">TOTAL PENJUALAN</h5>
                    <h2 class="text-danger mb-0">
                        <strong>Rp <?= number_format($penjualan['total_penjualan'], 0, ',', '.') ?></strong>
                    </h2>
                </div>
            </div>
        </div>

        <!-- Action Buttons -->
        <div class="card mt-3">
            <div class="card-body">
                <a href="<?= base_url('penjualan-sparepart') ?>" class="btn btn-secondary w-100 mb-2">
                    <i class="fas fa-arrow-left me-1"></i> Kembali ke Daftar
                </a>
                
                <button onclick="window.print()" class="btn btn-info w-100 mb-2">
                    <i class="fas fa-print me-1"></i> Cetak Nota
                </button>
                
                <a href="<?= base_url('penjualan-sparepart/delete/' . $penjualan['id']) ?>" 
                   class="btn btn-danger w-100 btn-delete">
                    <i class="fas fa-trash me-1"></i> Hapus Transaksi
                </a>
                
                <small class="text-muted d-block text-center mt-2">
                    <i class="fas fa-exclamation-triangle"></i>
                    Hapus transaksi akan mengembalikan stok
                </small>
            </div>
        </div>

        <!-- Info Timestamp -->
        <div class="card mt-3 bg-light">
            <div class="card-body">
                <h6 class="mb-3"><strong><i class="fas fa-clock me-2"></i>Timeline</strong></h6>
                <p class="small mb-2">
                    <i class="fas fa-calendar-plus text-danger me-2"></i>
                    <strong>Dibuat:</strong><br>
                    <?= date('d F Y, H:i:s', strtotime($penjualan['created_at'])) ?>
                </p>
                <?php if ($penjualan['updated_at'] != $penjualan['created_at']): ?>
                <p class="small mb-0">
                    <i class="fas fa-edit text-danger me-2"></i>
                    <strong>Terakhir Update:</strong><br>
                    <?= date('d F Y, H:i:s', strtotime($penjualan['updated_at'])) ?>
                </p>
                <?php endif; ?>
            </div>
        </div>

        <!-- Buyer Info -->
        <div class="card mt-3 bg-light">
            <div class="card-body">
                <h6 class="mb-3"><strong><i class="fas fa-user me-2"></i>Info Pembeli</strong></h6>
                <p class="mb-2">
                    <i class="fas fa-id-card text-danger me-2"></i>
                    <strong>Nama:</strong><br>
                    <?= esc($penjualan['nama_pembeli']) ?>
                </p>
                <p class="mb-0">
                    <i class="fas fa-calendar text-danger me-2"></i>
                    <strong>Tanggal Transaksi:</strong><br>
                    <?= date('d F Y', strtotime($penjualan['tanggal_penjualan'])) ?>
                </p>
            </div>
        </div>
    </div>
</div>

<!-- Print Styles -->
<style>
@media print {
    .sidebar,
    .topbar,
    .btn,
    .card-footer,
    .alert {
        display: none !important;
    }
    
    .main-content {
        margin-left: 0 !important;
        width: 100% !important;
    }
    
    .col-md-4 {
        display: none;
    }
    
    .col-md-8 {
        width: 100% !important;
    }
    
    .card {
        border: 1px solid #000 !important;
        box-shadow: none !important;
    }
    
    .card-header {
        background: #dc3545 !important;
        color: white !important;
        -webkit-print-color-adjust: exact;
        print-color-adjust: exact;
    }
    
    body {
        background: white !important;
    }
}
</style>

<?= $this->include('layout/footer') ?>