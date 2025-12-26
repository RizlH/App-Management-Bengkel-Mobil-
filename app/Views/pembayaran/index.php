<?= $this->include('layout/header') ?>

<!-- Tambahkan CSS SweetAlert -->
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">

<div class="card">
    <div class="card-header d-flex justify-content-between align-items-center">
        <span><i class="fas fa-money-bill-wave me-2"></i> Data Pembayaran</span>
        <a href="<?= base_url('pembayaran/create') ?>" class="btn btn-light btn-sm">
            <i class="fas fa-plus me-1"></i> Tambah Pembayaran
        </a>
    </div>
    <div class="card-body">
        <!-- Search Form -->
        <form method="get" class="mb-3" id="searchForm">
            <div class="row">
                <div class="col-md-10">
                    <input type="text" name="search" class="form-control" 
                           placeholder="Cari nomor invoice, pelanggan, atau nomor plat..." value="<?= $search ?>">
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
                        <th>No. Invoice</th>
                        <th>Tanggal</th>
                        <th>Pelanggan</th>
                        <th>Kendaraan</th>
                        <th>Total Semua</th>
                        <th>Metode</th>
                        <th>Status</th>
                        <th width="15%" class="text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (empty($pembayaran)): ?>
                        <tr>
                            <td colspan="8" class="text-center text-muted">Tidak ada data pembayaran</td>
                        </tr>
                    <?php else: ?>
                        <?php foreach ($pembayaran as $p): ?>
                            <tr>
                                <td><strong class="text-danger"><?= $p['nomor_invoice'] ?></strong></td>
                                <td><?= date('d/m/Y H:i', strtotime($p['tanggal_bayar'])) ?></td>
                                <td><?= $p['nama_pelanggan'] ?></td>
                                <td><?= $p['nomor_plat'] ?></td>
                                <td><strong>Rp <?= number_format($p['total_biaya'], 0, ',', '.') ?></strong></td>
                                <td>
                                    <span class="badge bg-info"><?= $p['metode_pembayaran'] ?></span>
                                </td>
                                <td>
                                    <?php if ($p['status_bayar'] == 'Lunas'): ?>
                                        <span class="badge bg-success">Lunas</span>
                                    <?php else: ?>
                                        <span class="badge bg-warning">Belum Lunas</span>
                                    <?php endif; ?>
                                </td>
                                <td class="text-center">
                                    <a href="<?= base_url('pembayaran/show/' . $p['id']) ?>" 
                                       class="btn btn-info btn-sm" title="Detail">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                    <a href="<?= base_url('pembayaran/cetak-invoice/' . $p['id']) ?>" 
                                       class="btn btn-success btn-sm" title="Cetak Invoice" target="_blank">
                                        <i class="fas fa-print"></i>
                                    </a>
                                    <button type="button" 
                                            data-url="<?= base_url('pembayaran/delete/' . $p['id']) ?>" 
                                            data-invoice="<?= $p['nomor_invoice'] ?>"
                                            class="btn btn-danger btn-sm btn-delete" title="Hapus">
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

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
$(document).ready(function() {
    // Validasi untuk delete button
    $('.btn-delete').on('click', function(e) {
        e.preventDefault();
        
        const url = $(this).data('url');
        const invoice = $(this).data('invoice');
        
        Swal.fire({
            title: 'Apakah Anda yakin?',
            html: `<strong>Data pembayaran ${invoice}</strong> akan dihapus secara permanen.<br>Action ini tidak dapat dibatalkan!`,
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            cancelButtonColor: '#6c757d',
            confirmButtonText: 'Ya, Hapus!',
            cancelButtonText: 'Batal',
            reverseButtons: true,
            customClass: {
                confirmButton: 'btn btn-danger',
                cancelButton: 'btn btn-secondary'
            },
            buttonsStyling: false
        }).then((result) => {
            if (result.isConfirmed) {
                // Redirect ke URL delete
                window.location.href = url;
            }
        });
    });
    
    // Validasi untuk form pencarian 
    $('#searchForm').on('submit', function(e) {
        const searchValue = $(this).find('input[name="search"]').val().trim();
        
        if (searchValue.length > 0 && searchValue.length < 3) {
            e.preventDefault();
            Swal.fire({
                icon: 'warning',
                title: 'Peringatan',
                text: 'Masukkan minimal 3 karakter untuk pencarian',
                confirmButtonColor: '#d33',
                confirmButtonText: 'OK'
            });
        }
    });
});
</script>

<?= $this->include('layout/footer') ?>