<?= $this->include('layout/header') ?>

<div class="card">
    <div class="card-header d-flex justify-content-between align-items-center">
        <span><i class="fas fa-tasks me-2"></i> Work Order</span>
        <a href="<?= base_url('work-order/create') ?>" class="btn btn-light btn-sm">
            <i class="fas fa-plus me-1"></i> Buat Work Order
        </a>
    </div>
    <div class="card-body">
        <!-- Search Form -->
        <form method="get" class="mb-3">
            <div class="row">
                <div class="col-md-10">
                    <input type="text" name="search" class="form-control" 
                           placeholder="Cari nomor servis, mekanik, atau nomor plat..." value="<?= $search ?>">
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
                        <th>No. Servis</th>
                        <th>Pelanggan</th>
                        <th>Kendaraan</th>
                        <th>Mekanik</th>
                        <th>Status</th>
                        <th>Tanggal Mulai</th>
                        <th width="15%" class="text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (empty($work_orders)): ?>
                        <tr>
                            <td colspan="7" class="text-center text-muted">Tidak ada work order</td>
                        </tr>
                    <?php else: ?>
                        <?php foreach ($work_orders as $wo): ?>
                            <tr>
                                <td><strong class="text-danger"><?= $wo['nomor_servis'] ?></strong></td>
                                <td><?= $wo['nama_pelanggan'] ?></td>
                                <td><?= $wo['nomor_plat'] ?></td>
                                <td>
                                    <i class="fas fa-user-cog me-1"></i> <?= $wo['nama_mekanik'] ?>
                                </td>
                                <td>
                                    <?php
                                    $status_badge = [
                                        'Pending' => 'warning',
                                        'Dikerjakan' => 'info',
                                        'Selesai' => 'success'
                                    ];
                                    ?>
                                    <span class="badge bg-<?= $status_badge[$wo['status']] ?>">
                                        <?= $wo['status'] ?>
                                    </span>
                                </td>
                                <td><?= date('d/m/Y H:i', strtotime($wo['tanggal_mulai'])) ?></td>
                                <td class="text-center">
                                    <a href="<?= base_url('work-order/show/' . $wo['id']) ?>" 
                                       class="btn btn-info btn-sm">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                    <button type="button" class="btn btn-danger btn-sm btn-delete" 
                                            data-id="<?= $wo['id'] ?>"
                                            data-nomor="<?= $wo['nomor_servis'] ?>">
                                        <i class="fas fa-trash"></i>
                                    </button>
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
document.addEventListener('DOMContentLoaded', function() {
    // SweetAlert untuk konfirmasi hapus
    $(document).on('click', '.btn-delete', function(e) {
        e.preventDefault();
        const id = $(this).data('id');
        const nomorServis = $(this).data('nomor');
        
        Swal.fire({
            title: 'Konfirmasi Hapus',
            html: `Apakah Anda yakin ingin menghapus work order<br><strong>${nomorServis}</strong>?`,
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            cancelButtonColor: '#3085d6',
            confirmButtonText: 'Ya, Hapus!',
            cancelButtonText: 'Batal',
            reverseButtons: true
        }).then((result) => {
            if (result.isConfirmed) {
                // Redirect ke URL hapus
                window.location.href = '<?= base_url('work-order/delete/') ?>' + id;
            }
        });
    });
});
</script>

<?= $this->include('layout/footer') ?>