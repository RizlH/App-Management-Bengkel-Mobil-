<?= $this->include('layout/header') ?>

<div class="card">
    <div class="card-header d-flex justify-content-between align-items-center">
        <span><i class="fas fa-cash-register me-2"></i> Penjualan Sparepart</span>
        <a href="<?= base_url('penjualan-sparepart/create') ?>" class="btn btn-light btn-sm">
            <i class="fas fa-plus me-1"></i> Tambah Penjualan
        </a>
    </div>
    <div class="card-body">
        
        <!-- Table -->
        <div class="table-responsive">
            <table class="table table-hover">
                <thead>
                    <tr>
                        <th width="15%">Nomor Penjualan</th>
                        <th width="20%">Nama Pembeli</th>
                        <th width="12%">Tanggal</th>
                        <th width="18%" class="text-end">Total Penjualan</th>
                        <th width="15%">Dibuat</th>
                        <th width="15%" class="text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (empty($penjualan)): ?>
                        <tr>
                            <td colspan="6" class="text-center text-muted py-4">
                                <i class="fas fa-inbox fa-3x mb-3 d-block" style="opacity: 0.3;"></i>
                                Tidak ada data penjualan
                            </td>
                        </tr>
                    <?php else: ?>
                        <?php 
                        $currentPage = $pager->getCurrentPage();
                        $perPage = $pager->getPerPage();
                        $startNumber = (($currentPage - 1) * $perPage) + 1;
                        $no = $startNumber;
                        foreach ($penjualan as $item): 
                        ?>
                        <tr>
                            <td>
                                <strong class="text-danger"><?= esc($item['nomor_penjualan']) ?></strong>
                            </td>
                            <td>
                                <i class="fas fa-user me-1 text-muted"></i>
                                <?= esc($item['nama_pembeli']) ?>
                            </td>
                            <td>
                                <?= date('d/m/Y', strtotime($item['tanggal_penjualan'])) ?>
                            </td>
                            <td class="text-end">
                                <strong class="text-success">
                                    Rp <?= number_format($item['total_penjualan'], 0, ',', '.') ?>
                                </strong>
                            </td>
                            <td>
                                <small class="text-muted">
                                    <?= date('d/m/Y H:i', strtotime($item['created_at'])) ?>
                                </small>
                            </td>
                            <td class="text-center">
                                <a href="<?= base_url('penjualan-sparepart/show/' . $item['id']) ?>" 
                                   class="btn btn-info btn-sm" title="Detail">
                                    <i class="fas fa-eye"></i>
                                </a>
                                <a href="<?= base_url('penjualan-sparepart/delete/' . $item['id']) ?>" 
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

        <!-- Pagination -->
        <?php if (!empty($pager) && $pager->getPageCount() > 1): ?>
        <div class="d-flex justify-content-between align-items-center mt-3">
            <div class="text-muted">
                <small>
                    Menampilkan <?= (($currentPage - 1) * $perPage) + 1 ?> - 
                    <?= min($currentPage * $perPage, $pager->getTotal()) ?> 
                    dari <?= $pager->getTotal() ?> data
                </small>
            </div>
            <nav>
                <?= $pager->links() ?>
            </nav>
        </div>
        <?php endif; ?>

        <!-- Info -->
        <div class="alert alert-light border mt-3">
            <i class="fas fa-info-circle text-danger me-2"></i>
            <small>
                <strong>Catatan:</strong> 
                Data penjualan yang dihapus akan mengembalikan stok sparepart secara otomatis.
            </small>
        </div>
    </div>
</div>

<?= $this->include('layout/footer') ?>