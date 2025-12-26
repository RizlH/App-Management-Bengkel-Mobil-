<?= $this->include('layout/header') ?>

<div class="card">
    <div class="card-header d-flex justify-content-between align-items-center">
        <span><i class="fas fa-wrench me-2"></i> Data Jasa Servis</span>
        <a href="<?= base_url('jasa-servis/create') ?>" class="btn btn-light btn-sm">
            <i class="fas fa-plus me-1"></i> Tambah Jasa Servis
        </a>
    </div>
    <div class="card-body">
        <!-- Search Form -->
        <form method="get" class="mb-3">
            <div class="row">
                <div class="col-md-10">
                    <input type="text" name="search" class="form-control" 
                           placeholder="Cari nama layanan atau kategori..." value="<?= $search ?>">
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
                        <th>Nama Layanan</th>
                        <th>Kategori</th>
                        <th>Harga Jasa</th>
                        <th>Estimasi Durasi</th>
                        <th width="15%" class="text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (empty($jasa_servis)): ?>
                        <tr>
                            <td colspan="6" class="text-center text-muted">Tidak ada data jasa servis</td>
                        </tr>
                    <?php else: ?>
                        <?php 
                            $currentPage = $pager->getCurrentPage();
                            $perPage = $pager->getPerPage();
                            $startNumber = (($currentPage - 1) * $perPage) + 1;
                            $no = $startNumber;

                            foreach ($jasa_servis as $js): 
                            ?>
                                <td><?= $no++ ?></td>
                                <td><strong><?= $js['nama_layanan'] ?></strong></td>
                                <td>
                                    <?php
                                    $kategori_color = [
                                        'Ringan' => 'success',
                                        'Berat' => 'danger',
                                        'Tune Up' => 'warning',
                                        'Cuci' => 'info',
                                        'Lainnya' => 'secondary'
                                    ];
                                    ?>
                                    <span class="badge bg-<?= $kategori_color[$js['kategori_servis']] ?>">
                                        <?= $js['kategori_servis'] ?>
                                    </span>
                                </td>
                                <td><strong class="text-danger">Rp <?= number_format($js['harga_jasa'], 0, ',', '.') ?></strong></td>
                                <td>
                                    <i class="fas fa-clock me-1"></i> 
                                    <?= floor($js['estimasi_durasi'] / 60) ?> jam <?= $js['estimasi_durasi'] % 60 ?> menit
                                </td>
                                <td class="text-center">
                                    <a href="<?= base_url('jasa-servis/edit/' . $js['id']) ?>" class="btn btn-warning btn-sm">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    <a href="<?= base_url('jasa-servis/delete/' . $js['id']) ?>" class="btn btn-danger btn-sm btn-delete" data-nama="<?= esc($js['nama_layanan']) ?>">
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
        const nama = this.dataset.nama;

        Swal.fire({
            title: 'Hapus Jasa Servis?',
            text: `Jasa servis "${nama}" akan dihapus permanen`,
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
