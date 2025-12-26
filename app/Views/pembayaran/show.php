<?= $this->include('layout/header') ?>

<div class="row">
    <div class="col-md-8">
        <div class="card">
            <div class="card-header bg-success text-white d-flex justify-content-between align-items-center">
                <span><i class="fas fa-file-invoice me-2"></i> Detail Pembayaran</span>
                <span class="badge bg-light text-dark">Status: LUNAS</span>
            </div>
            <div class="card-body">
                <div class="row mb-4">
                    <div class="col-md-6">
                        <h5 class="text-danger mb-3"><?= $pembayaran['nomor_invoice'] ?></h5>
                        <table class="table table-sm table-borderless">
                            <tr>
                                <td width="150"><strong>Nomor Servis</strong></td>
                                <td>: <?= $pembayaran['nomor_servis'] ?></td>
                            </tr>
                            <tr>
                                <td><strong>Tanggal Bayar</strong></td>
                                <td>: <?= date('d F Y H:i', strtotime($pembayaran['tanggal_bayar'])) ?></td>
                            </tr>
                            <tr>
                                <td><strong>Metode</strong></td>
                                <td>: 
                                    <span class="badge bg-info"><?= $pembayaran['metode_pembayaran'] ?></span>
                                </td>
                            </tr>
                        </table>
                    </div>
                    <div class="col-md-6">
                        <h6 class="mb-2"><strong>Info Pelanggan:</strong></h6>
                        <p class="mb-1"><strong><?= $pembayaran['nama_pelanggan'] ?></strong></p>
                        <p class="mb-1 small"><i class="fas fa-map-marker-alt me-1"></i> <?= $pembayaran['alamat'] ?></p>
                        <p class="mb-1 small"><i class="fas fa-phone me-1"></i> <?= $pembayaran['no_telepon'] ?></p>
                        <hr>
                        <p class="mb-0 small">
                            <i class="fas fa-car me-1 text-danger"></i>
                            <strong><?= $pembayaran['nomor_plat'] ?></strong> - 
                            <?= $pembayaran['merk'] ?> <?= $pembayaran['tipe'] ?>
                        </p>
                    </div>
                </div>

                <hr>

                <!-- Jasa Servis -->
                <h6 class="mb-3"><strong><i class="fas fa-wrench me-2 text-danger"></i>Jasa Servis</strong></h6>
                <?php if (empty($jasa_servis)): ?>
                    <p class="text-muted">Tidak ada jasa servis</p>
                <?php else: ?>
                    <div class="table-responsive mb-4">
                        <table class="table table-sm table-hover">
                            <thead class="table-light">
                                <tr>
                                    <th width="50">No</th>
                                    <th>Nama Layanan</th>
                                    <th width="150" class="text-end">Harga</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php $no = 1; foreach ($jasa_servis as $js): ?>
                                    <tr>
                                        <td><?= $no++ ?></td>
                                        <td><?= $js['nama_layanan'] ?></td>
                                        <td class="text-end">Rp <?= number_format($js['harga'], 0, ',', '.') ?></td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                            <tfoot class="table-secondary">
                                <tr>
                                    <td colspan="2" class="text-end"><strong>Subtotal Jasa:</strong></td>
                                    <td class="text-end"><strong>Rp <?= number_format($pembayaran['total_jasa'], 0, ',', '.') ?></strong></td>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                <?php endif; ?>

                <!-- Sparepart -->
                <h6 class="mb-3"><strong><i class="fas fa-cogs me-2 text-danger"></i>Sparepart Digunakan</strong></h6>
                <?php if (empty($sparepart)): ?>
                    <p class="text-muted">Tidak ada sparepart yang digunakan</p>
                <?php else: ?>
                    <div class="table-responsive mb-4">
                        <table class="table table-sm table-hover">
                            <thead class="table-light">
                                <tr>
                                    <th width="50">No</th>
                                    <th>Nama Barang</th>
                                    <th width="100">Jumlah</th>
                                    <th width="130" class="text-end">Harga</th>
                                    <th width="150" class="text-end">Subtotal</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php $no = 1; foreach ($sparepart as $sp): ?>
                                    <tr>
                                        <td><?= $no++ ?></td>
                                        <td><?= $sp['nama_barang'] ?></td>
                                        <td><?= $sp['jumlah'] ?> <?= $sp['satuan'] ?></td>
                                        <td class="text-end">Rp <?= number_format($sp['harga_satuan'], 0, ',', '.') ?></td>
                                        <td class="text-end">Rp <?= number_format($sp['subtotal'], 0, ',', '.') ?></td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                            <tfoot class="table-secondary">
                                <tr>
                                    <td colspan="4" class="text-end"><strong>Subtotal Sparepart:</strong></td>
                                    <td class="text-end"><strong>Rp <?= number_format($pembayaran['total_sparepart'], 0, ',', '.') ?></strong></td>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                <?php endif; ?>

                <!-- Total -->
                <div class="card bg-danger text-white">
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6">
                                <h5 class="mb-0">TOTAL PEMBAYARAN</h5>
                            </div>
                            <div class="col-md-6 text-end">
                                <h3 class="mb-0"><strong>Rp <?= number_format($pembayaran['total_biaya'], 0, ',', '.') ?></strong></h3>
                            </div>
                        </div>
                    </div>
                </div>

                <?php if ($pembayaran['keluhan']): ?>
                    <hr>
                    <h6 class="mb-2"><strong>Keluhan Awal:</strong></h6>
                    <div class="bg-light p-3 rounded">
                        <?= nl2br(esc($pembayaran['keluhan'])) ?>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <div class="col-md-4">
        <!-- Action Buttons -->
        <div class="card">
            <div class="card-body">
                <a href="<?= base_url('pembayaran/cetak-invoice/' . $pembayaran['id']) ?>" 
                   class="btn btn-success w-100 mb-2 btn-lg" target="_blank">
                    <i class="fas fa-print me-1"></i> Cetak Invoice
                </a>
                
                <a href="<?= base_url('pembayaran') ?>" class="btn btn-secondary w-100 mb-2">
                    <i class="fas fa-arrow-left me-1"></i> Kembali
                </a>

                <hr>

                <button type="button" class="btn btn-danger w-100" data-bs-toggle="modal" data-bs-target="#deleteModal">
                    <i class="fas fa-trash me-1"></i> Hapus Pembayaran
                </button>
            </div>
        </div>

        <!-- Info Card -->
        <div class="card mt-3 bg-light">
            <div class="card-body">
                <h6 class="mb-3"><strong><i class="fas fa-info-circle me-2"></i>Informasi Invoice</strong></h6>
                
                <div class="mb-3">
                    <small class="text-muted d-block">Nomor Invoice</small>
                    <strong><?= $pembayaran['nomor_invoice'] ?></strong>
                </div>

                <div class="mb-3">
                    <small class="text-muted d-block">Dibuat Pada</small>
                    <strong><?= date('d/m/Y H:i:s', strtotime($pembayaran['created_at'])) ?></strong>
                </div>

                <?php if ($pembayaran['updated_at'] != $pembayaran['created_at']): ?>
                <div class="mb-3">
                    <small class="text-muted d-block">Terakhir Update</small>
                    <strong><?= date('d/m/Y H:i:s', strtotime($pembayaran['updated_at'])) ?></strong>
                </div>
                <?php endif; ?>

                <div class="mb-0">
                    <small class="text-muted d-block">Status Pembayaran</small>
                    <span class="badge bg-success">
                        <i class="fas fa-check-circle me-1"></i> 
                        <?= $pembayaran['status_bayar'] ?>
                    </span>
                </div>
            </div>
        </div>

        <!-- Payment Method -->
        <div class="card mt-3 border-info">
            <div class="card-body text-center">
                <?php
                $icon_metode = [
                    'Cash' => 'fa-money-bill-wave',
                    'Transfer' => 'fa-university',
                    'QRIS' => 'fa-qrcode'
                ];
                ?>
                <i class="fas <?= $icon_metode[$pembayaran['metode_pembayaran']] ?? 'fa-wallet' ?> fa-3x text-info mb-3"></i>
                <h6><strong>Metode Pembayaran</strong></h6>
                <h5 class="text-info mb-0"><?= $pembayaran['metode_pembayaran'] ?></h5>
            </div>
        </div>

        <!-- Summary -->
        <div class="card mt-3">
            <div class="card-header bg-dark text-white">
                <i class="fas fa-chart-pie me-2"></i> Ringkasan
            </div>
            <div class="card-body">
                <div class="d-flex justify-content-between mb-2">
                    <span>Total Jasa:</span>
                    <strong>Rp <?= number_format($pembayaran['total_jasa'], 0, ',', '.') ?></strong>
                </div>
                <div class="d-flex justify-content-between mb-2">
                    <span>Total Sparepart:</span>
                    <strong>Rp <?= number_format($pembayaran['total_sparepart'], 0, ',', '.') ?></strong>
                </div>
                <hr>
                <div class="d-flex justify-content-between">
                    <strong>TOTAL:</strong>
                    <strong class="text-danger">Rp <?= number_format($pembayaran['total_biaya'], 0, ',', '.') ?></strong>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Delete Confirmation Modal -->
<div class="modal fade" id="deleteModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-danger text-white">
                <h5 class="modal-title">
                    <i class="fas fa-exclamation-triangle me-2"></i> Konfirmasi Hapus
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <p class="mb-3">Apakah Anda yakin ingin menghapus pembayaran ini?</p>
                <div class="alert alert-warning">
                    <i class="fas fa-info-circle me-2"></i>
                    <strong>Perhatian:</strong>
                    <ul class="mb-0 mt-2">
                        <li>Data pembayaran akan dihapus permanent</li>
                        <li>Status servis akan kembali ke "Selesai"</li>
                        <li>Invoice tidak bisa diakses lagi</li>
                    </ul>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                    <i class="fas fa-times me-1"></i> Batal
                </button>
                <a href="<?= base_url('pembayaran/delete/' . $pembayaran['id']) ?>" class="btn btn-danger">
                    <i class="fas fa-trash me-1"></i> Ya, Hapus
                </a>
            </div>
        </div>
    </div>
</div>

<?= $this->include('layout/footer') ?>

