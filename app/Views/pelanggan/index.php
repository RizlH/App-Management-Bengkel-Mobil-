<?= $this->include('layout/header') ?>

<div class="card">
    <div class="card-header d-flex justify-content-between align-items-center">
        <span><i class="fas fa-users me-2"></i> Data Pelanggan</span>
        <a href="<?= base_url('pelanggan/create') ?>" class="btn btn-light btn-sm">
            <i class="fas fa-plus me-1"></i> Tambah Pelanggan
        </a>
    </div>
    <div class="card-body">
        <!-- Search Form -->
        <form method="get" class="mb-3">
            <div class="row">
                <div class="col-md-10">
                    <input type="text" name="search" class="form-control" placeholder="Cari nama, telepon, atau alamat..." value="<?= $search ?>">
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
                        <th>Nama Pelanggan</th>
                        <th>Alamat</th>
                        <th>No. Telepon</th>
                        <th width="15%" class="text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (empty($pelanggan)): ?>
                        <tr>
                            <td colspan="5" class="text-center text-muted">Tidak ada data pelanggan</td>
                        </tr>
                    <?php else: ?>
                        <?php 
                            $currentPage = $pager->getCurrentPage();
                            $perPage = $pager->getPerPage();
                            $startNumber = (($currentPage - 1) * $perPage) + 1;
                            $no = $startNumber;

                            foreach ($pelanggan as $p): 
                            ?>
                                <td><?= $no++ ?></td>
                                <td><strong><?= $p['nama'] ?></strong></td>
                                <td><?= $p['alamat'] ?></td>
                                <td><i class="fas fa-phone me-1"></i> <?= $p['no_telepon'] ?></td>
                                <td class="text-center">
                                    <a href="<?= base_url('pelanggan/edit/' . $p['id']) ?>" class="btn btn-warning btn-sm">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    <a href="<?= base_url('pelanggan/delete/' . $p['id']) ?>" class="btn btn-danger btn-sm btn-delete">
                                        <i class="fas fa-trash"></i>
                                    </a>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
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
    btn.addEventListener('click', function (e) {
        e.preventDefault();
        const url = this.getAttribute('href');

        Swal.fire({
            title: 'Yakin hapus data?',
            text: 'Data pelanggan akan dihapus permanen!',
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
<?php if (session()->getFlashdata('success')) : ?>
<script>
Swal.fire({
    icon: 'success',
    title: 'Berhasil',
    text: '<?= session()->getFlashdata('success') ?>',
    timer: 2000,
    showConfirmButton: false
});
</script>
<?php endif; ?>


<?= $this->include('layout/footer') ?>
