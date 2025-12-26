<?= $this->include('layout/header') ?>

<div class="card">
    <div class="card-header d-flex justify-content-between align-items-center">
        <span><i class="fas fa-user-cog me-2"></i> Data Mekanik</span>
        <a href="<?= base_url('mekanik/create') ?>" class="btn btn-light btn-sm">
            <i class="fas fa-plus me-1"></i> Tambah Mekanik
        </a>
    </div>
    <div class="card-body">
        <!-- Search Form -->
        <form method="get" class="mb-3">
            <div class="row">
                <div class="col-md-10">
                    <input type="text" name="search" class="form-control" 
                           placeholder="Cari nama atau posisi mekanik..." value="<?= $search ?>">
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
                        <th>Nama Mekanik</th>
                        <th>Posisi</th>
                        <th>Level Skill</th>
                        <th>Kontak</th>
                        <th>Status</th>
                        <th width="15%" class="text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (empty($mekanik)): ?>
                        <tr>
                            <td colspan="7" class="text-center text-muted">Tidak ada data mekanik</td>
                        </tr>
                    <?php else: ?>
                        <?php 
                            $currentPage = $pager->getCurrentPage();
                            $perPage = $pager->getPerPage();
                            $startNumber = (($currentPage - 1) * $perPage) + 1;
                            $no = $startNumber;

                            foreach ($mekanik as $m): 
                            ?>
                                <td><?= $no++ ?></td>
                                <td><strong><?= $m['nama'] ?></strong></td>
                                <td>
                                    <span class="badge bg-secondary">
                                        <i class="fas fa-wrench me-1"></i> <?= $m['posisi'] ?>
                                    </span>
                                </td>
                                <td>
                                    <?php
                                    $level_color = [
                                        'Junior' => 'info',
                                        'Senior' => 'warning',
                                        'Expert' => 'success'
                                    ];
                                    ?>
                                    <span class="badge bg-<?= $level_color[$m['level_skill']] ?>">
                                        <?= $m['level_skill'] ?>
                                    </span>
                                </td>
                                <td><i class="fas fa-phone me-1"></i> <?= $m['kontak'] ?></td>
                                <td>
                                    <?php if ($m['status_aktif']): ?>
                                        <span class="badge bg-success">Aktif</span>
                                    <?php else: ?>
                                        <span class="badge bg-danger">Tidak Aktif</span>
                                    <?php endif; ?>
                                </td>
                                <td class="text-center">
                                    <a href="<?= base_url('mekanik/edit/' . $m['id']) ?>" class="btn btn-warning btn-sm">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    <a href="#" 
                                        class="btn btn-danger btn-sm btn-delete"
                                        data-url="<?= base_url('mekanik/delete/' . $m['id']) ?>"
                                        data-nama="<?= $m['nama'] ?>">
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
        const nama = this.dataset.nama;

        Swal.fire({
            title: 'Yakin ingin menghapus?',
            html: `Data mekanik <b>${nama}</b> akan dihapus permanen!`,
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
