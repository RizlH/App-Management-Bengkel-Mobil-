<?= $this->include('layout/header') ?>

<div class="row">
    <div class="col-md-8">
        <div class="card">
            <div class="card-header">
                <i class="fas fa-info-circle me-2"></i> Detail Penerimaan Servis
            </div>
            <div class="card-body">
                <table class="table table-borderless">
                    <tr>
                        <td width="200"><strong>Nomor Servis</strong></td>
                        <td>: <span class="badge bg-danger"><?= $penerimaan_servis['nomor_servis'] ?></span></td>
                    </tr>
                    <tr>
                        <td><strong>Tanggal Masuk</strong></td>
                        <td>: <?= date('d F Y H:i', strtotime($penerimaan_servis['tanggal_masuk'])) ?></td>
                    </tr>
                    <tr>
                        <td><strong>Status</strong></td>
                        <td>: 
                            <?php
                            $badge_class = [
                                'Menunggu' => 'warning',
                                'Proses' => 'info',
                                'Selesai' => 'success',
                            ];
                            ?>
                            <span class="badge bg-<?= $badge_class[$penerimaan_servis['status']] ?>">
                                <?= $penerimaan_servis['status'] ?>
                            </span>
                        </td>
                    </tr>
                    <tr>
                        <td><strong>Estimasi Biaya</strong></td>
                        <td>: Rp <?= number_format($penerimaan_servis['estimasi_biaya'], 0, ',', '.') ?></td>
                    </tr>
                    <?php if ($penerimaan_servis['tanggal_selesai']): ?>
                    <tr>
                        <td><strong>Tanggal Selesai</strong></td>
                        <td>: <?= date('d F Y H:i', strtotime($penerimaan_servis['tanggal_selesai'])) ?></td>
                    </tr>
                    <?php endif; ?>
                </table>

                <hr>

                <h6 class="mb-3"><strong>Keluhan:</strong></h6>
                <p class="bg-light p-3 rounded"><?= nl2br($penerimaan_servis['keluhan']) ?></p>
            </div>
        </div>

        <!-- Work Orders -->
        <div class="card mt-3">
            <div class="card-header bg-dark text-white">
                <i class="fas fa-tasks me-2"></i> Work Order
            </div>
            <div class="card-body">
                <?php if (empty($work_orders)): ?>
                    <div class="alert alert-info">
                        <i class="fas fa-info-circle me-2"></i>
                        Belum ada work order untuk servis ini.
                        <a href="<?= base_url('work-order/create') ?>" class="alert-link">Buat work order</a>
                    </div>
                <?php else: ?>
                    <?php foreach ($work_orders as $wo): ?>
                        <div class="border rounded p-3 mb-2">
                            <div class="row">
                                <div class="col-md-8">
                                    <p class="mb-1"><strong>Mekanik:</strong> <?= $wo['nama_mekanik'] ?></p>
                                    <p class="mb-1"><strong>Status:</strong> 
                                        <span class="badge bg-<?= $wo['status'] == 'Selesai' ? 'success' : 'info' ?>">
                                            <?= $wo['status'] ?>
                                        </span>
                                    </p>
                                    <p class="mb-0"><small class="text-muted">Mulai: <?= date('d/m/Y H:i', strtotime($wo['tanggal_mulai'])) ?></small></p>
                                </div>
                                <div class="col-md-4 text-end">
                                    <a href="<?= base_url('work-order/show/' . $wo['id']) ?>" class="btn btn-sm btn-info">
                                        <i class="fas fa-eye"></i> Detail
                                    </a>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <div class="col-md-4">
        <!-- Info Pelanggan -->
        <div class="card">
            <div class="card-header bg-danger text-white">
                <i class="fas fa-user me-2"></i> Info Pelanggan
            </div>
            <div class="card-body">
                <p class="mb-2"><strong><?= $penerimaan_servis['nama_pelanggan'] ?></strong></p>
                <p class="mb-1"><i class="fas fa-map-marker-alt me-2"></i><?= $penerimaan_servis['alamat'] ?></p>
                <p class="mb-0"><i class="fas fa-phone me-2"></i><?= $penerimaan_servis['no_telepon'] ?></p>
            </div>
        </div>

        <!-- Info Kendaraan -->
        <div class="card mt-3">
            <div class="card-header bg-dark text-white">
                <i class="fas fa-car me-2"></i> Info Kendaraan
            </div>
            <div class="card-body">
                <?php if ($penerimaan_servis['foto']): ?>
                    <img src="<?= base_url('uploads/kendaraan/' . $penerimaan_servis['foto']) ?>" 
                         class="img-fluid rounded mb-3" alt="Foto Kendaraan">
                <?php endif; ?>
                
                <table class="table table-sm table-borderless">
                    <tr>
                        <td><strong>Nomor Plat</strong></td>
                        <td>: <span class="text-danger"><?= $penerimaan_servis['nomor_plat'] ?></span></td>
                    </tr>
                    <tr>
                        <td><strong>Merk/Tipe</strong></td>
                        <td>: <?= $penerimaan_servis['merk'] ?> <?= $penerimaan_servis['tipe'] ?></td>
                    </tr>
                    <tr>
                        <td><strong>Tahun</strong></td>
                        <td>: <?= $penerimaan_servis['tahun'] ?></td>
                    </tr>
                </table>
            </div>
        </div>

        <div class="mt-3">
            <a href="<?= base_url('penerimaan-servis') ?>" class="btn btn-secondary w-100">
                <i class="fas fa-arrow-left me-1"></i> Kembali
            </a>
        </div>
    </div>
</div>

<?= $this->include('layout/footer') ?>
