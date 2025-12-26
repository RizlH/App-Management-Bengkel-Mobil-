<?= $this->include('layout/header') ?>

<div class="row">
    <div class="col-md-8">
        <!-- Detail Work Order -->
        <div class="card">
            <div class="card-header bg-dark text-white d-flex justify-content-between align-items-center">
                <span><i class="fas fa-tasks me-2"></i> Detail Work Order</span>
                <span class="badge bg-light text-dark">ID: #<?= $work_order['id'] ?></span>
            </div>
            <div class="card-body">
                <div class="row mb-3">
                    <div class="col-md-6">
                        <table class="table table-sm table-borderless">
                            <tr>
                                <td width="140"><strong>Nomor Servis</strong></td>
                                <td>: <span class="badge bg-danger"><?= $work_order['nomor_servis'] ?></span></td>
                            </tr>
                            <tr>
                                <td><strong>Pelanggan</strong></td>
                                <td>: <?= $work_order['nama_pelanggan'] ?></td>
                            </tr>
                            <tr>
                                <td><strong>Kendaraan</strong></td>
                                <td>: <?= $work_order['nomor_plat'] ?> - <?= $work_order['merk'] ?></td>
                            </tr>
                        </table>
                    </div>
                    <div class="col-md-6">
                        <table class="table table-sm table-borderless">
                            <tr>
                                <td width="140"><strong>Mekanik</strong></td>
                                <td>: <?= $work_order['nama_mekanik'] ?> 
                                    <small class="text-muted">(<?= $work_order['posisi'] ?>)</small>
                                </td>
                            </tr>
                            <tr>
                                <td><strong>Status</strong></td>
                                <td>: 
                                    <?php
                                    $status_badge = [
                                        'Pending' => 'warning',
                                        'Dikerjakan' => 'info',
                                        'Selesai' => 'success'
                                    ];
                                    ?>
                                    <span class="badge bg-<?= $status_badge[$work_order['status']] ?>">
                                        <?= $work_order['status'] ?>
                                    </span>
                                </td>
                            </tr>
                            <tr>
                                <td><strong>Tanggal Mulai</strong></td>
                                <td>: <?= date('d/m/Y H:i', strtotime($work_order['tanggal_mulai'])) ?></td>
                            </tr>
                            <?php if ($work_order['tanggal_selesai']): ?>
                            <tr>
                                <td><strong>Tanggal Selesai</strong></td>
                                <td>: <?= date('d/m/Y H:i', strtotime($work_order['tanggal_selesai'])) ?></td>
                            </tr>
                            <?php endif; ?>
                        </table>
                    </div>
                </div>

                <?php if ($work_order['progres']): ?>
                    <div class="mb-3">
                        <h6 class="mb-2"><strong><i class="fas fa-clipboard-list me-2"></i>Progres Pekerjaan:</strong></h6>
                        <div class="bg-light p-3 rounded">
                            <?= nl2br(esc($work_order['progres'])) ?>
                        </div>
                    </div>
                <?php endif; ?>

                <div class="mb-3">
                    <h6 class="mb-2"><strong><i class="fas fa-comment-dots me-2"></i>Keluhan Awal:</strong></h6>
                    <div class="bg-light p-3 rounded">
                        <?= nl2br(esc($work_order['keluhan'])) ?>
                    </div>
                </div>

                <!-- Update Status Form -->
                <?php if ($work_order['status'] != 'Selesai'): ?>
                    <hr>
                    <div class="card bg-light">
                        <div class="card-body">
                            <h6 class="mb-3"><i class="fas fa-sync me-2"></i><strong>Update Status & Progres</strong></h6>
                            <form action="<?= base_url('work-order/update-status/' . $work_order['id']) ?>" method="post" id="formUpdateStatus">
                                <?= csrf_field() ?>
                                <div class="row">
                                    <div class="col-md-4">
                                        <label class="form-label">Status Pekerjaan <span class="text-danger">*</span></label>
                                        <select class="form-select" name="status" required>
                                            <option value="Pending" <?= $work_order['status'] == 'Pending' ? 'selected' : '' ?>>Pending</option>
                                            <option value="Dikerjakan" <?= $work_order['status'] == 'Dikerjakan' ? 'selected' : '' ?>>Dikerjakan</option>
                                            <option value="Selesai">Selesai</option>
                                        </select>
                                    </div>
                                    <div class="col-md-8">
                                        <label class="form-label">Update Progres</label>
                                        <textarea class="form-control" name="progres" rows="2" 
                                                  placeholder="Masukkan catatan progres pekerjaan..."><?= $work_order['progres'] ?></textarea>
                                    </div>
                                </div>
                                <div class="mt-3">
                                    <button type="submit" class="btn btn-primary">
                                        <i class="fas fa-save me-1"></i> Update Status
                                    </button>
                                    <small class="text-muted ms-2">
                                        <i class="fas fa-info-circle"></i> Ubah status menjadi "Selesai" untuk melanjutkan ke pembayaran
                                    </small>
                                </div>
                            </form>
                        </div>
                    </div>
                <?php endif; ?>
            </div>
        </div>

        <!-- Jasa Servis -->
        <div class="card mt-3">
            <div class="card-header bg-danger text-white">
                <i class="fas fa-wrench me-2"></i> Jasa Servis
            </div>
            <div class="card-body">
                <?php if (empty($jasa_servis)): ?>
                    <p class="text-muted mb-0">Tidak ada jasa servis yang ditambahkan</p>
                <?php else: ?>
                    <div class="table-responsive">
                        <table class="table table-sm table-hover mb-0">
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
                            <tfoot class="table-dark">
                                <tr>
                                    <td colspan="2" class="text-end"><strong>Subtotal Jasa:</strong></td>
                                    <td class="text-end"><strong>Rp <?= number_format($total_jasa, 0, ',', '.') ?></strong></td>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                <?php endif; ?>
            </div>
        </div>

        <!-- Sparepart -->
        <div class="card mt-3">
            <div class="card-header bg-dark text-white">
                <i class="fas fa-cogs me-2"></i> Sparepart Digunakan
            </div>
            <div class="card-body">
                <?php if (empty($sparepart)): ?>
                    <p class="text-muted mb-0">Tidak ada sparepart yang digunakan</p>
                <?php else: ?>
                    <div class="table-responsive">
                        <table class="table table-sm table-hover mb-0">
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
                            <tfoot class="table-dark">
                                <tr>
                                    <td colspan="4" class="text-end"><strong>Subtotal Sparepart:</strong></td>
                                    <td class="text-end"><strong>Rp <?= number_format($total_sparepart, 0, ',', '.') ?></strong></td>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <div class="col-md-4">
        <!-- Total Biaya -->
        <div class="card border-danger">
            <div class="card-header bg-danger text-white text-center">
                <h6 class="mb-0"><i class="fas fa-calculator me-2"></i>RINGKASAN BIAYA</h6>
            </div>
            <div class="card-body">
                <table class="table table-sm table-borderless">
                    <tr>
                        <td><i class="fas fa-wrench text-danger me-2"></i>Total Jasa</td>
                        <td class="text-end">Rp <?= number_format($total_jasa, 0, ',', '.') ?></td>
                    </tr>
                    <tr>
                        <td><i class="fas fa-cogs text-danger me-2"></i>Total Sparepart</td>
                        <td class="text-end">Rp <?= number_format($total_sparepart, 0, ',', '.') ?></td>
                    </tr>
                </table>
                <hr>
                <div class="text-center">
                    <h5 class="text-muted mb-1">TOTAL BIAYA</h5>
                    <h2 class="text-danger mb-0">
                        <strong>Rp <?= number_format($total_biaya, 0, ',', '.') ?></strong>
                    </h2>
                </div>
            </div>
        </div>

        <!-- Action Buttons -->
        <div class="card mt-3">
            <div class="card-body">
                <a href="<?= base_url('work-order') ?>" class="btn btn-secondary w-100 mb-2">
                    <i class="fas fa-arrow-left me-1"></i> Kembali ke Daftar
                </a>
                
                <?php if ($work_order['status'] == 'Selesai'): ?>
                    <?php
                    // Cek apakah sudah ada pembayaran
                    $pembayaranModel = new \App\Models\PembayaranModel();
                    $sudahBayar = $pembayaranModel->where('penerimaan_servis_id', $work_order['penerimaan_servis_id'])->first();
                    ?>
                    
                    <?php if ($sudahBayar): ?>
                        <div class="alert alert-success mb-2">
                            <i class="fas fa-check-circle me-1"></i>
                            <strong>Sudah Dibayar</strong><br>
                            <small>Invoice: <?= $sudahBayar['nomor_invoice'] ?></small>
                        </div>
                        <a href="<?= base_url('pembayaran/show/' . $sudahBayar['id']) ?>" 
                           class="btn btn-info w-100 mb-2">
                            <i class="fas fa-file-invoice me-1"></i> Lihat Invoice
                        </a>
                        <a href="<?= base_url('pembayaran/cetak-invoice/' . $sudahBayar['id']) ?>" 
                           class="btn btn-success w-100" target="_blank">
                            <i class="fas fa-print me-1"></i> Cetak Invoice
                        </a>
                    <?php else: ?>
                        <a href="<?= base_url('pembayaran/create?servis_id=' . $work_order['penerimaan_servis_id']) ?>" 
                           class="btn btn-danger w-100 btn-lg">
                            <i class="fas fa-money-bill-wave me-1"></i> Buat Pembayaran
                        </a>
                        <small class="text-muted d-block text-center mt-2">
                            <i class="fas fa-info-circle"></i> Klik untuk membuat invoice pembayaran
                        </small>
                    <?php endif; ?>
                <?php else: ?>
                    <div class="alert alert-warning">
                        <i class="fas fa-exclamation-triangle me-1"></i>
                        <strong>Belum Selesai</strong><br>
                        <small>Update status menjadi "Selesai" untuk melanjutkan ke pembayaran</small>
                    </div>
                <?php endif; ?>
            </div>
        </div>

        <!-- Info Tambahan -->
        <div class="card mt-3 bg-light">
            <div class="card-body">
                <h6 class="mb-3"><strong><i class="fas fa-info-circle me-2"></i>Informasi</strong></h6>
                <p class="small mb-2">
                    <i class="fas fa-calendar text-danger me-2"></i>
                    <strong>Dibuat:</strong> <?= date('d/m/Y H:i', strtotime($work_order['created_at'])) ?>
                </p>
                <?php if ($work_order['updated_at'] != $work_order['created_at']): ?>
                <p class="small mb-2">
                    <i class="fas fa-edit text-danger me-2"></i>
                    <strong>Terakhir Update:</strong> <?= date('d/m/Y H:i', strtotime($work_order['updated_at'])) ?>
                </p>
                <?php endif; ?>
                
                <?php if ($work_order['status'] == 'Selesai' && $work_order['tanggal_selesai']): ?>
                    <?php
                    $mulai = new DateTime($work_order['tanggal_mulai']);
                    $selesai = new DateTime($work_order['tanggal_selesai']);
                    $durasi = $mulai->diff($selesai);
                    ?>
                    <p class="small mb-0">
                        <i class="fas fa-clock text-danger me-2"></i>
                        <strong>Durasi Pengerjaan:</strong> 
                        <?php if ($durasi->days > 0): ?>
                            <?= $durasi->days ?> hari 
                        <?php endif; ?>
                        <?= $durasi->h ?> jam <?= $durasi->i ?> menit
                    </p>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // SweetAlert untuk konfirmasi update status
    $('#formUpdateStatus').submit(function(e) {
        e.preventDefault();
        
        const form = this;
        const status = $('select[name="status"]').val();
        const nomorServis = '<?= $work_order["nomor_servis"] ?>';
        
        Swal.fire({
            title: 'Konfirmasi Update Status',
            html: `Anda akan mengubah status Work Order<br><strong>${nomorServis}</strong><br>menjadi <strong>${status}</strong>`,
            icon: 'question',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#6c757d',
            confirmButtonText: 'Ya, Update!',
            cancelButtonText: 'Batal',
            reverseButtons: true
        }).then((result) => {
            if (result.isConfirmed) {
                form.submit();
            }
        });
    });
});
</script>

<?= $this->include('layout/footer') ?>