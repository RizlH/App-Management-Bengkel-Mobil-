<?= $this->include('layout/header') ?>

<div class="card">
    <div class="card-header d-flex justify-content-between align-items-center">
        <span><i class="fas fa-cogs me-2"></i> Data Sparepart</span>
        <a href="<?= base_url('sparepart/create') ?>" class="btn btn-light btn-sm">
            <i class="fas fa-plus me-1"></i> Tambah Sparepart
        </a>
    </div>
    <div class="card-body">
        <!-- Search Form -->
        <form method="get" class="mb-3">
            <div class="row">
                <div class="col-md-10">
                    <input type="text" name="search" class="form-control" 
                           placeholder="Cari nama barang, kategori, atau pemasok..." value="<?= $search ?? '' ?>">
                </div>
                <div class="col-md-2">
                    <button type="submit" class="btn btn-danger w-100">
                        <i class="fas fa-search"></i> Cari
                    </button>
                </div>
            </div>
        </form>

        <div class="table-responsive">
            <table class="table table-hover">
                <thead>
                    <tr>
                        <th width="5%">No</th>
                        <th>Nama Barang</th>
                        <th>Kategori</th>
                        <th>Supplier</th>
                        <th>Harga Jual</th>
                        <th>Stok</th>
                        <th width="15%" class="text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (empty($sparepart)): ?>
                        <tr>
                            <td colspan="7" class="text-center text-muted">Tidak ada data sparepart</td>
                        </tr>
                    <?php else: ?>
                        <?php 
                            $currentPage = $pager->getCurrentPage();
                            $perPage = $pager->getPerPage();
                            $startNumber = (($currentPage - 1) * $perPage) + 1;
                            $no = $startNumber;

                            foreach ($sparepart as $s): 
                            ?>
                            <tr>
                                <td><?= $no++ ?></td>
                                <td><strong><?= esc($s['nama_barang']) ?></strong></td>
                                <td><span class="badge bg-secondary"><?= esc($s['kategori']) ?></span></td>
                                <td>
                                    <i class="fas fa-truck me-1 text-danger"></i>
                                    <?= esc($s['pemasok'] ?? 'Belum diisi') ?>
                                </td>
                                <td>Rp <?= number_format($s['harga_jual'], 0, ',', '.') ?></td>
                                <td>
                                    <?php if ($s['stok'] < 5): ?>
                                        <span class="badge bg-danger"><?= $s['stok'] ?></span>
                                    <?php elseif ($s['stok'] < 10): ?>
                                        <span class="badge bg-warning"><?= $s['stok'] ?></span>
                                    <?php else: ?>
                                        <span class="badge bg-success"><?= $s['stok'] ?></span>
                                    <?php endif; ?>
                                    <?= esc($s['satuan']) ?>
                                </td>
                                <td class="text-center">
                                    <a href="<?= base_url('sparepart/edit/' . $s['id']) ?>" 
                                       class="btn btn-warning btn-sm" title="Edit">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    <a href="<?= base_url('sparepart/delete/' . $s['id']) ?>" 
                                       class="btn btn-danger btn-sm btn-delete" title="Hapus">
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
document.querySelectorAll('.btn-delete').forEach(button => {
    button.addEventListener('click', function(e) {
        e.preventDefault();

        const url = this.dataset.url;

        Swal.fire({
            title: 'Yakin ingin menghapus?',
            html: `Data sparepart ini akan dihapus permanen!`,
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            cancelButtonColor: '#6c757d',
            confirmButtonText: 'Ya, hapus!',
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