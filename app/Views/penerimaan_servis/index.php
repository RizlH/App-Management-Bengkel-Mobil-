<?= $this->include('layout/header') ?>

<div class="card">
    <div class="card-header d-flex justify-content-between align-items-center">
        <span><i class="fas fa-clipboard-check me-2"></i> Penerimaan Servis</span>
        <a href="<?= base_url('penerimaan-servis/create') ?>" class="btn btn-light btn-sm">
            <i class="fas fa-plus me-1"></i> Check-in Kendaraan
        </a>
    </div>
    <div class="card-body">
        <!-- Search & Filter -->
        <form method="get" class="mb-3">
            <div class="row">
                <div class="col-md-7">
                    <input type="text" name="search" class="form-control" 
                           placeholder="Cari nomor servis, pelanggan, atau nomor plat..." value="<?= $search ?>">
                </div>
                <div class="col-md-3">
                    <select name="status" class="form-select">
                        <option value="">Semua Status</option>
                        <option value="Menunggu" <?= $status == 'Menunggu' ? 'selected' : '' ?>>Menunggu</option>
                        <option value="Proses" <?= $status == 'Proses' ? 'selected' : '' ?>>Proses</option>
                        <option value="Selesai" <?= $status == 'Selesai' ? 'selected' : '' ?>>Selesai</option>
                    </select>
                </div>
                <div class="col-md-2">
                    <button type="submit" class="btn btn-danger w-100">
                        <i class="fas fa-filter"></i> Filter
                    </button>
                </div>
            </div>
        </form>

        <div class="table-responsive">
            <table class="table table-hover">
                <thead>
                    <tr>
                        <th>No. Servis</th>
                        <th>Tanggal</th>
                        <th>Pelanggan</th>
                        <th>Kendaraan</th>
                        <th>Status</th>
                        <th>Est. Biaya</th>
                        <th width="15%" class="text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (empty($penerimaan_servis)): ?>
                        <tr>
                            <td colspan="7" class="text-center text-muted">Tidak ada data penerimaan servis</td>
                        </tr>
                    <?php else: ?>
                        <?php foreach ($penerimaan_servis as $ps): ?>
                            <tr>
                                <td><strong class="text-danger"><?= $ps['nomor_servis'] ?></strong></td>
                                <td><?= date('d/m/Y', strtotime($ps['tanggal_masuk'])) ?></td>
                                <td><?= $ps['nama_pelanggan'] ?></td>
                                <td><?= $ps['nomor_plat'] ?> <small class="text-muted">(<?= $ps['merk'] ?>)</small></td>
                                <td>
                                    <?php
                                    $badge_class = [
                                        'Menunggu' => 'warning',
                                        'Proses' => 'info',
                                        'Selesai' => 'success',
                                        '-' => 'secondary'
                                    ];
                                    ?>
                                    <span class="badge bg-<?= $badge_class[$ps['status']] ?>">
                                        <?= $ps['status'] ?>
                                    </span>
                                </td>
                                <td>Rp <?= number_format($ps['estimasi_biaya'], 0, ',', '.') ?></td>
                                <td class="text-center">
                                    <a href="<?= base_url('penerimaan-servis/show/' . $ps['id']) ?>" 
                                       class="btn btn-info btn-sm" title="Detail">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                    <a href="<?= base_url('penerimaan-servis/edit/' . $ps['id']) ?>" 
                                       class="btn btn-warning btn-sm" title="Edit">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    <a href="<?= base_url('penerimaan-servis/delete/' . $ps['id']) ?>" 
                                       class="btn btn-danger btn-sm btn-delete" title="Hapus" data-nomor="<?= $ps['nomor_servis'] ?>">
                                        <i class="fas fa-trash"></i>
                                    </a>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>

        <?php if (!empty($pager) && $pager->getPageCount() > 1): ?>
        <?php
            $currentPage = $pager->getCurrentPage();
            $perPage = $pager->getPerPage();
            $total = $pager->getTotal();
            
            // Hitung dengan benar
            $start = (($currentPage - 1) * $perPage) + 1;
            $end = min($currentPage * $perPage, $total);
        ?>
        <div class="mt-4">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <small class="text-muted">
                        <i class="fas fa-list me-1"></i>
                        Menampilkan <?= $start ?> - <?= $end ?> dari <?= number_format($total, 0, ',', '.') ?> data
                    </small>
                </div>
                
                <nav>
                    <ul class="pagination pagination-sm mb-0">
                        <!-- Previous -->
                        <?php if ($currentPage > 1): ?>
                            <li class="page-item">
                                <a class="page-link" href="?page=<?= $currentPage - 1 ?>">
                                    <i class="fas fa-chevron-left"></i>
                                </a>
                            </li>
                        <?php else: ?>
                            <li class="page-item disabled">
                                <span class="page-link">
                                    <i class="fas fa-chevron-left"></i>
                                </span>
                            </li>
                        <?php endif; ?>
                        
                        <!-- Page Numbers -->
                        <?php for ($i = 1; $i <= $pager->getPageCount(); $i++): ?>
                            <?php if ($i >= $currentPage - 2 && $i <= $currentPage + 2): ?>
                                <li class="page-item <?= $i == $currentPage ? 'active' : '' ?>">
                                    <a class="page-link" href="?page=<?= $i ?>"><?= $i ?></a>
                                </li>
                            <?php endif; ?>
                        <?php endfor; ?>
                        
                        <!-- Next -->
                        <?php if ($currentPage < $pager->getPageCount()): ?>
                            <li class="page-item">
                                <a class="page-link" href="?page=<?= $currentPage + 1 ?>">
                                    <i class="fas fa-chevron-right"></i>
                                </a>
                            </li>
                        <?php else: ?>
                            <li class="page-item disabled">
                                <span class="page-link">
                                    <i class="fas fa-chevron-right"></i>
                                </span>
                            </li>
                        <?php endif; ?>
                    </ul>
                </nav>
            </div>
        </div>
        <?php endif; ?>
    </div>
</div>

<script>
document.querySelectorAll('.btn-delete').forEach(btn => {
    btn.addEventListener('click', function(e) {
        e.preventDefault();

        const url = this.getAttribute('href');
        const nomor = this.dataset.nomor;

        Swal.fire({
            title: 'Hapus Penerimaan Servis?',
            text: `Nomor servis ${nomor} akan dihapus permanen`,
            icon: 'error',
            showCancelButton: true,
            confirmButtonColor: '#dc3545',
            cancelButtonColor: '#6c757d',
            confirmButtonText: 'Ya, Hapus',
            cancelButtonText: 'Batal'
        }).then((result) => {
            if (result.isConfirmed) {
                window.location.href = url;
            }
        });
    });
});
</script>

<?= $this->include('layout/footer') ?>
