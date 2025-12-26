<?= $this->include('layout/header') ?>

<!-- SweetAlert untuk notifikasi -->
<?php if (session()->getFlashdata('swal')): ?>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const swalData = <?= json_encode(session()->getFlashdata('swal')) ?>;
        Swal.fire({
            icon: swalData.icon,
            title: swalData.title,
            text: swalData.text,
            confirmButtonColor: '#dc3545',
            timer: 3000,
            timerProgressBar: true,
            toast: false,
            position: 'top-end',
            showConfirmButton: true
        });
    });
</script>
<?php endif; ?>

<?php if (session()->getFlashdata('success')): ?>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        Swal.fire({
            icon: 'success',
            title: 'Berhasil!',
            text: '<?= addslashes(session()->getFlashdata('success')) ?>',
            confirmButtonColor: '#28a745',
            timer: 3000,
            timerProgressBar: true,
            toast: false,
            position: 'top-end',
            showConfirmButton: true
        });
    });
</script>
<?php endif; ?>

<?php if (session()->getFlashdata('error')): ?>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        Swal.fire({
            icon: 'error',
            title: 'Gagal!',
            text: '<?= addslashes(session()->getFlashdata('error')) ?>',
            confirmButtonColor: '#dc3545',
            timer: 3000,
            timerProgressBar: true,
            toast: false,
            position: 'top-end',
            showConfirmButton: true
        });
    });
</script>
<?php endif; ?>

<!-- Welcome Card with User Info -->
<div class="row mb-4">
    <div class="col-12">
        <div class="card border-0" style="background: linear-gradient(135deg, #1a1a1a 0%, #343a40 100%);">
            <div class="card-body text-white">
                <div class="row align-items-center">
                    <div class="col-md-8">
                        <h2 class="mb-2">Selamat Datang, <?= session()->get('full_name') ?>! 👋</h2>
                        <p class="mb-0 text-white-50">
                            <i class="fas fa-calendar-alt me-1"></i> 
                            <?= date('l, d F Y') ?> 
                            | <i class="fas fa-clock ms-3 me-1"></i> 
                            <?= date('H:i') ?>
                        </p>
                        <p class="mb-0 mt-2">
                            <small class="text-white-75">
                                <i class="fas fa-envelope me-1"></i> <?= session()->get('email') ?>
                                <?php if (session()->has('last_login')): ?>
                                | <i class="fas fa-sign-in-alt ms-3 me-1"></i> 
                                Login terakhir: <?= date('d/m/Y H:i', strtotime(session()->get('last_login'))) ?>
                                <?php endif; ?>
                            </small>
                        </p>
                    </div>
                    <div class="col-md-4 text-end">
                        <i class="fas fa-user-circle" style="font-size: 5rem; opacity: 0.3;"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Statistics Cards -->
<div class="row">
    <!-- Total Pelanggan -->
    <div class="col-xl-3 col-md-6 mb-4">
        <div class="card stats-card border-0 shadow-hover">
            <div class="card-body">
                <div class="row align-items-center">
                    <div class="col">
                        <div class="text-uppercase text-danger fw-bold text-xs mb-1">Total Pelanggan</div>
                        <div class="h3 mb-0 fw-bold text-gray-800"><?= number_format($total_pelanggan) ?></div>
                        <div class="mt-2">
                            <small class="text-muted">
                                <i class="fas fa-users me-1"></i> Data master
                            </small>
                        </div>
                    </div>
                    <div class="col-auto">
                        <i class="fas fa-users fa-2x text-danger opacity-50"></i>
                    </div>
                </div>
                <div class="mt-3">
                    <a href="<?= base_url('pelanggan') ?>" class="btn btn-sm btn-outline-danger w-100">
                        <i class="fas fa-eye me-1"></i> Lihat Data
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- Total Kendaraan -->
    <div class="col-xl-3 col-md-6 mb-4">
        <div class="card stats-card border-0 shadow-hover">
            <div class="card-body">
                <div class="row align-items-center">
                    <div class="col">
                        <div class="text-uppercase text-danger fw-bold text-xs mb-1">Total Kendaraan</div>
                        <div class="h3 mb-0 fw-bold text-gray-800"><?= number_format($total_kendaraan) ?></div>
                        <div class="mt-2">
                            <small class="text-muted">
                                <i class="fas fa-car me-1"></i> Terdaftar
                            </small>
                        </div>
                    </div>
                    <div class="col-auto">
                        <i class="fas fa-car fa-2x text-danger opacity-50"></i>
                    </div>
                </div>
                <div class="mt-3">
                    <a href="<?= base_url('kendaraan') ?>" class="btn btn-sm btn-outline-danger w-100">
                        <i class="fas fa-eye me-1"></i> Lihat Data
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- Total Mekanik Aktif -->
    <div class="col-xl-3 col-md-6 mb-4">
        <div class="card stats-card border-0 shadow-hover">
            <div class="card-body">
                <div class="row align-items-center">
                    <div class="col">
                        <div class="text-uppercase text-danger fw-bold text-xs mb-1">Mekanik Aktif</div>
                        <div class="h3 mb-0 fw-bold text-gray-800"><?= number_format($total_mekanik) ?></div>
                        <div class="mt-2">
                            <small class="text-muted">
                                <i class="fas fa-user-check me-1"></i> Siap kerja
                            </small>
                        </div>
                    </div>
                    <div class="col-auto">
                        <i class="fas fa-user-cog fa-2x text-danger opacity-50"></i>
                    </div>
                </div>
                <div class="mt-3">
                    <a href="<?= base_url('mekanik') ?>" class="btn btn-sm btn-outline-danger w-100">
                        <i class="fas fa-eye me-1"></i> Lihat Data
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- Servis Aktif -->
    <div class="col-xl-3 col-md-6 mb-4">
        <div class="card stats-card border-0 shadow-hover">
            <div class="card-body">
                <div class="row align-items-center">
                    <div class="col">
                        <div class="text-uppercase text-danger fw-bold text-xs mb-1">Servis Aktif</div>
                        <div class="h3 mb-0 fw-bold text-gray-800"><?= number_format($servis_aktif) ?></div>
                        <div class="mt-2">
                            <small class="text-muted">
                                <i class="fas fa-wrench me-1"></i> Dalam proses
                            </small>
                        </div>
                    </div>
                    <div class="col-auto">
                        <i class="fas fa-wrench fa-2x text-danger opacity-50"></i>
                    </div>
                </div>
                <div class="mt-3">
                    <a href="<?= base_url('penerimaan-servis') ?>" class="btn btn-sm btn-outline-danger w-100">
                        <i class="fas fa-eye me-1"></i> Lihat Data
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Revenue and Performance Cards -->
<div class="row">
    <!-- Pendapatan Bulan Ini (Servis + Sparepart) -->
    <div class="col-xl-6 col-md-6 mb-4">
        <div class="card border-0 h-100" style="background: linear-gradient(135deg, #dc3545 0%, #a71d2a 100%);">
            <div class="card-body text-white">
                <div class="row align-items-center h-100">
                    <div class="col">
                        <h5 class="text-white mb-2">
                            <i class="fas fa-chart-line me-2"></i> Pendapatan Bulan Ini
                        </h5>
                        <h2 class="fw-bold mb-1">Rp <?= number_format($pendapatan_bulan_ini, 0, ',', '.') ?></h2>
                        <p class="mb-0 text-white-75">
                            <small>
                                <i class="fas fa-calendar me-1"></i> Periode: <?= date('F Y') ?>
                            </small>
                        </p>
                        
                        <!-- Breakdown Pendapatan -->
                        <div class="row mt-3">
                            <div class="col-6">
                                <div class="d-flex align-items-center mb-2">
                                    <div class="p-2 me-2">
                                        <i class="fas fa-wrench" style="font-size: 1rem;"></i>
                                    </div>
                                    <div>
                                        <div class="fw-bold">Rp <?= number_format($pendapatan_servis, 0, ',', '.') ?></div>
                                        <small class="text-white-75">Dari Servis</small>
                                    </div>
                                </div>
                            </div>
                            <div class="col-6">
                                <div class="d-flex align-items-center mb-2">
                                    <div class="p-2 me-2">
                                        <i class="fas fa-cog" style="font-size: 1rem;"></i>
                                    </div>
                                    <div>
                                        <div class="fw-bold">Rp <?= number_format($pendapatan_sparepart, 0, ',', '.') ?></div>
                                        <small class="text-white-75">Dari Sparepart</small>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Progress Bar -->
                        <div class="mt-3">
                            <div class="progress bg-white bg-opacity-25" style="height: 8px;">
                                <?php 
                                    $target = 50000000; // Target Rp 50 juta
                                    $percentage = min(100, ($pendapatan_bulan_ini / $target) * 100);
                                ?>
                                <div class="progress-bar bg-white" style="width: <?= $percentage ?>%;"></div>
                            </div>
                            <small class="mt-1 d-block">
                                Target: Rp 50.000.000 (<?= number_format($percentage, 1) ?>%)
                            </small>
                        </div>
                    </div>
                    <div class="col-auto">
                        <i class="fas fa-money-bill-wave fa-3x opacity-25"></i>
                    </div>
                </div>
            </div>
            <div class="card-footer bg-transparent border-0 text-white">
                <a href="<?= base_url('laporan?tipe=pendapatan&periode=bulan_ini') ?>" 
                   class="btn btn-sm btn-light w-100">
                    <i class="fas fa-file-alt me-1"></i> Lihat Detail Laporan
                </a>
            </div>
        </div>
    </div>

    <!-- Penyelesaian Servis (Dari Work Order) -->
    <div class="col-xl-3 col-md-6 mb-4">
        <div class="card border-0 h-100" style="background: linear-gradient(135deg, #28a745 0%, #1e7e34 100%);">
            <div class="card-body text-white">
                <h5 class="text-white mb-3">
                    <i class="fas fa-clipboard-check me-2"></i> Penyelesaian Servis
                </h5>
                
                <!-- Work Order Selesai -->
                <div class="d-flex align-items-center mb-3">
                    <div class="me-3">
                        <i class="fas fa-check-circle fa-2x opacity-50"></i>
                    </div>
                    <div>
                        <h3 class="mb-0"><?= number_format($work_order_selesai) ?></h3>
                        <small class="text-white-75">Work Order Selesai</small>
                    </div>
                </div>
                
                <!-- Work Order Dikerjakan -->
                <div class="d-flex align-items-center mb-3">
                    <div class="me-3">
                        <i class="fas fa-tasks fa-2x opacity-50"></i>
                    </div>
                    <div>
                        <h3 class="mb-0"><?= number_format($work_order_proses) ?></h3>
                        <small class="text-white-75">Work Order Dikerjakan</small>
                    </div>
                </div>
                
                <!-- Work Order Pending -->
                <div class="d-flex align-items-center">
                    <div class="me-3">
                        <i class="fas fa-clock fa-2x opacity-50"></i>
                    </div>
                    <div>
                        <h3 class="mb-0"><?= number_format($work_order_pending) ?></h3>
                        <small class="text-white-75">Work Order Pending</small>
                    </div>
                </div>
                
                <!-- Statistik Komparasi -->
                <div class="mt-3 pt-2 border-top border-white border-opacity-25">
                    <div class="row text-center">
                        <div class="col-6">
                            <?php
                            $total_work_order = $work_order_selesai + $work_order_proses + $work_order_pending;
                            $tingkat_penyelesaian = $total_work_order > 0 ? 
                                ($work_order_selesai / $total_work_order) * 100 : 0;
                            ?>
                            <div class="fw-bold"><?= number_format($tingkat_penyelesaian, 1) ?>%</div>
                            <small class="text-white-75">Tingkat Penyelesaian</small>
                        </div>
                        <div class="col-6">
                            <div class="fw-bold"><?= number_format($total_work_order) ?></div>
                            <small class="text-white-75">Total Work Order</small>
                        </div>
                    </div>
                </div>
            </div>
            <div class="card-footer bg-transparent border-0 text-white">
                <a href="<?= base_url('work-order') ?>" class="btn btn-sm btn-light w-100">
                    <i class="fas fa-clipboard-list me-1"></i> Kelola Work Order
                </a>
            </div>
        </div>
    </div>

    <!-- Stok Sparepart -->
    <div class="col-xl-3 col-md-6 mb-4">
        <div class="card border-0 h-100" style="background: linear-gradient(135deg, #ffc107 0%, #e0a800 100%);">
            <div class="card-body text-white">
                <h5 class="text-white mb-3">
                    <i class="fas fa-boxes me-2"></i> Stok Sparepart
                </h5>
                <div class="d-flex align-items-center mb-3">
                    <div class="me-3">
                        <i class="fas fa-box fa-2x opacity-50"></i>
                    </div>
                    <div>
                        <h3 class="mb-0"><?= number_format($total_sparepart) ?></h3>
                        <small class="text-white-75">Total Item</small>
                    </div>
                </div>
                <div class="d-flex align-items-center">
                    <div class="me-3">
                        <i class="fas fa-exclamation-triangle fa-2x opacity-50"></i>
                    </div>
                    <div>
                        <h3 class="mb-0"><?= number_format($stok_rendah) ?></h3>
                        <small class="text-white-75">Stok Rendah (< 5)</small>
                    </div>
                </div>
                
                <!-- Persentase Stok Rendah -->
                <div class="mt-3 pt-2 border-top border-white border-opacity-25">
                    <div class="d-flex justify-content-between align-items-center">
                        <small class="text-white-75">Status Stok</small>
                        <div>
                            <span class="badge bg-<?= $stok_rendah > 0 ? 'danger' : 'success' ?>">
                                <?php 
                                    $persentase_stok_rendah = $total_sparepart > 0 ? 
                                        ($stok_rendah / $total_sparepart) * 100 : 0;
                                ?>
                                <?= number_format($persentase_stok_rendah, 1) ?>% rendah
                            </span>
                        </div>
                    </div>
                    <div class="progress bg-white bg-opacity-25 mt-2" style="height: 6px;">
                        <div class="progress-bar bg-danger" style="width: <?= $persentase_stok_rendah ?>%"></div>
                    </div>
                </div>
            </div>
            <div class="card-footer bg-transparent border-0 text-white">
                <a href="<?= base_url('sparepart') ?>" class="btn btn-sm btn-light w-100">
                    <i class="fas fa-cogs me-1"></i> Kelola Stok
                </a>
            </div>
        </div>
    </div>
</div>

<!-- Charts and Recent Activities -->
<div class="row">
    <!-- Recent Services -->
    <div class="col-xl-8 col-lg-7 mb-4">
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <div>
                    <i class="fas fa-list me-2"></i> Servis Terbaru
                    <span class="badge bg-danger ms-2"><?= count($servis_terbaru) ?> Transaksi</span>
                </div>
                <div>
                    <a href="<?= base_url('penerimaan-servis') ?>" class="btn btn-sm btn-danger">
                        <i class="fas fa-plus me-1"></i> Tambah Servis
                    </a>
                </div>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover mb-0">
                        <thead class="bg-light">
                            <tr>
                                <th width="5%" class="text-center">#</th>
                                <th width="15%">No. Servis</th>
                                <th width="20%">Pelanggan</th>
                                <th width="15%">Kendaraan</th>
                                <th width="15%">Tanggal</th>
                                <th width="15%">Status</th>
                                <th width="15%" class="text-center">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (empty($servis_terbaru)): ?>
                                <tr>
                                    <td colspan="7" class="text-center text-muted py-4">
                                        <i class="fas fa-inbox fa-2x mb-2 d-block"></i>
                                        Belum ada data servis
                                    </td>
                                </tr>
                            <?php else: ?>
                                <?php foreach ($servis_terbaru as $index => $servis): ?>
                                    <tr>
                                        <td class="text-center"><?= $index + 1 ?></td>
                                        <td>
                                            <strong><?= $servis['nomor_servis'] ?></strong>
                                            
                                        </td>
                                        <td>
                                            <div class="d-flex align-items-center">
                                                <div class="avatar-sm bg-danger text-white rounded-circle d-flex align-items-center justify-content-center me-2" style="width: 30px; height: 30px;">
                                                    <?= substr($servis['nama_pelanggan'], 0, 1) ?>
                                                </div>
                                                <div>
                                                    <div class="fw-bold"><?= $servis['nama_pelanggan'] ?></div>
                                                    <small class="text-muted"><?= $servis['no_telepon_pelanggan'] ?? '-' ?></small>
                                                </div>
                                            </div>
                                        </td>
                                        <td>
                                            <div class="fw-bold"><?= $servis['nomor_plat'] ?></div>
                                            <small class="text-muted"><?= $servis['merk'] ?? '' ?> <?= $servis['tipe'] ?? '' ?></small>
                                        </td>
                                        <td>
                                            <div><?= date('d/m/Y', strtotime($servis['tanggal_masuk'])) ?></div>
                                            <small class="text-muted"><?= date('H:i', strtotime($servis['tanggal_masuk'])) ?></small>
                                        </td>
                                        <td>
                                            <?php
                                            $badge_class = [
                                                'Menunggu' => 'warning',
                                                'Proses' => 'info',
                                                'Selesai' => 'success',
                                                'Diambil' => 'secondary',
                                                'Batal' => 'danger'
                                            ];
                                            ?>
                                            <span class="badge bg-<?= $badge_class[$servis['status']] ?? 'secondary' ?>">
                                                <i class="fas fa-circle me-1" style="font-size: 0.6rem;"></i>
                                                <?= $servis['status'] ?>
                                            </span>
                                        </td>
                                        <td class="text-center">
                                            <div class="btn-group" role="group">
                                                <a href="<?= base_url('penerimaan-servis/show/' . $servis['id']) ?>" 
                                                   class="btn btn-sm btn-outline-danger" title="Detail">
                                                    <i class="fas fa-eye"></i>
                                                </a>
                                                <a href="<?= base_url('work-order/create?servis_id=' . $servis['id']) ?>" 
                                                   class="btn btn-sm btn-outline-primary" title="Buat Work Order">
                                                    <i class="fas fa-clipboard-list"></i>
                                                </a>
                                                <a href="<?= base_url('pembayaran/create?servis_id=' . $servis['id']) ?>" 
                                                   class="btn btn-sm btn-outline-success" title="Buat Pembayaran">
                                                    <i class="fas fa-money-bill"></i>
                                                </a>
                                            </div>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="card-footer text-center">
                <a href="<?= base_url('penerimaan-servis') ?>" class="text-danger text-decoration-none">
                    <i class="fas fa-arrow-right me-1"></i> Lihat Semua Servis
                </a>
            </div>
        </div>
    </div>

    <!-- Quick Stats & Actions -->
    <div class="col-xl-4 col-lg-5 mb-4">
        <!-- Breakdown Pendapatan -->
        <div class="card mb-4">
            <div class="card-header">
                <i class="fas fa-chart-pie me-2"></i> Breakdown Pendapatan
            </div>
            <div class="card-body">
                <div class="mb-3">
                    <div class="d-flex justify-content-between mb-1">
                        <span>Servis (Jasa)</span>
                        <span class="fw-bold">Rp <?= number_format($detail_pendapatan_servis['total_jasa'] ?? 0, 0, ',', '.') ?></span>
                    </div>
                    <div class="progress" style="height: 8px;">
                        <?php 
                            $persen_jasa = $pendapatan_servis > 0 ? 
                                (($detail_pendapatan_servis['total_jasa'] ?? 0) / $pendapatan_servis) * 100 : 0;
                        ?>
                        <div class="progress-bar bg-info" style="width: <?= $persen_jasa ?>%"></div>
                    </div>
                </div>
                
                <div class="mb-3">
                    <div class="d-flex justify-content-between mb-1">
                        <span>Servis (Sparepart)</span>
                        <span class="fw-bold">Rp <?= number_format($detail_pendapatan_servis['total_sparepart_servis'] ?? 0, 0, ',', '.') ?></span>
                    </div>
                    <div class="progress" style="height: 8px;">
                        <?php 
                            $persen_sparepart_servis = $pendapatan_servis > 0 ? 
                                (($detail_pendapatan_servis['total_sparepart_servis'] ?? 0) / $pendapatan_servis) * 100 : 0;
                        ?>
                        <div class="progress-bar bg-success" style="width: <?= $persen_sparepart_servis ?>%"></div>
                    </div>
                </div>
                
                <div class="mb-3">
                    <div class="d-flex justify-content-between mb-1">
                        <span>Penjualan Sparepart</span>
                        <span class="fw-bold">Rp <?= number_format($pendapatan_sparepart, 0, ',', '.') ?></span>
                    </div>
                    <div class="progress" style="height: 8px;">
                        <?php 
                            $persen_penjualan = $pendapatan_bulan_ini > 0 ? 
                                ($pendapatan_sparepart / $pendapatan_bulan_ini) * 100 : 0;
                        ?>
                        <div class="progress-bar bg-warning" style="width: <?= $persen_penjualan ?>%"></div>
                    </div>
                </div>
                
                <hr>
                <div class="text-center">
                    <small class="text-muted">
                        <i class="fas fa-info-circle me-1"></i>
                        Total: Rp <?= number_format($pendapatan_bulan_ini, 0, ',', '.') ?>
                    </small>
                </div>
            </div>
        </div>
        
        <!-- Quick Actions -->
        <div class="card">
            <div class="card-header">
                <i class="fas fa-bolt me-2"></i> Aksi Cepat
            </div>
            <div class="card-body">
                <div class="row g-2">
                    <div class="col-6">
                        <a href="<?= base_url('penerimaan-servis/create') ?>" 
                        class="btn btn-danger w-100 h-100 d-flex flex-column align-items-center justify-content-center p-3">
                            <i class="fas fa-plus-circle fa-2x mb-2"></i>
                            <span>Servis Baru</span>
                        </a>
                    </div>
                    <div class="col-6">
                        <a href="<?= base_url('penjualan-sparepart/create') ?>" 
                        class="btn btn-dark w-100 h-100 d-flex flex-column align-items-center justify-content-center p-3">
                            <i class="fas fa-shopping-cart fa-2x mb-2"></i>
                            <span>Jual Sparepart</span>
                        </a>
                    </div>
                    <div class="col-6">
                        <a href="<?= base_url('work-order/create') ?>" 
                        class="btn btn-outline-primary w-100 h-100 d-flex flex-column align-items-center justify-content-center p-3">
                            <i class="fas fa-clipboard-list fa-2x mb-2"></i>
                            <span>Work Order</span>
                        </a>
                    </div>
                    <div class="col-6">
                        <a href="<?= base_url('laporan') ?>" 
                        class="btn btn-outline-dark w-100 h-100 d-flex flex-column align-items-center justify-content-center p-3">
                            <i class="fas fa-chart-line fa-2x mb-2"></i>
                            <span>Laporan</span>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>


<!-- SweetAlert untuk konfirmasi logout -->
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Chart.js untuk distribusi servis
    if (typeof Chart !== 'undefined') {
        const ctx = document.getElementById('serviceDistributionChart').getContext('2d');
        const serviceDistributionChart = new Chart(ctx, {
            type: 'doughnut',
            data: {
                labels: ['Menunggu', 'Proses', 'Selesai'],
                datasets: [{
                    data: [
                        <?= isset($servis_menunggu) ? $servis_menunggu : 0 ?>,
                        <?= isset($servis_proses) ? $servis_proses : 0 ?>,
                        <?= isset($servis_selesai) ? $servis_selesai : 0 ?>
                    ],
                    backgroundColor: [
                        '#ffc107',
                        '#17a2b8',
                        '#28a745'
                    ],
                    borderWidth: 1,
                    borderColor: '#fff'
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                cutout: '70%',
                plugins: {
                    legend: {
                        display: false
                    },
                    tooltip: {
                        callbacks: {
                            label: function(context) {
                                let label = context.label || '';
                                if (label) {
                                    label += ': ';
                                }
                                label += context.raw;
                                return label;
                            }
                        }
                    }
                }
            }
        });
    }

    // Auto-refresh dashboard setiap 5 menit
    setTimeout(() => {
        Swal.fire({
            icon: 'info',
            title: 'Memperbarui Data',
            text: 'Data dashboard akan diperbarui...',
            timer: 1500,
            showConfirmButton: false,
            toast: true,
            position: 'top-end'
        }).then(() => {
            window.location.reload();
        });
    }, 300000); // 5 menit

    // Update waktu real-time
    function updateTime() {
        const now = new Date();
        const timeElement = document.querySelector('.time-display');
        if (timeElement) {
            timeElement.textContent = now.toLocaleTimeString();
        }
    }
        
    // Update waktu setiap detik
    setInterval(updateTime, 1000);
});
</script>

<?= $this->include('layout/footer') ?>