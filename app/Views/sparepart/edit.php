
<?= $this->include('layout/header') ?>

<div class="card">
    <div class="card-header">
        <i class="fas fa-edit me-2"></i> Edit Sparepart
    </div>
    <div class="card-body">
        <?php if (isset($errors) && !empty($errors)): ?>
            <div class="alert alert-danger">
                <ul class="mb-0">
                    <?php foreach ($errors as $error): ?>
                        <li><?= $error ?></li>
                    <?php endforeach; ?>
                </ul>
            </div>
        <?php endif; ?>

        <form id="formEditSparepart" action="<?= base_url('sparepart/update/' . $sparepart['id']) ?>" method="post">
            <?= csrf_field() ?>
            
            <!-- Informasi Barang -->
            <div class="card bg-light mb-3">
                <div class="card-body">
                    <h6 class="mb-3"><strong><i class="fas fa-box me-2"></i>Informasi Barang</strong></h6>
                    
                    <div class="mb-3">
                        <label class="form-label">Nama Barang <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" name="nama_barang" 
                               value="<?= old('nama_barang', $sparepart['nama_barang']) ?>" required>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label">Kategori <span class="text-danger">*</span></label>
                                <select class="form-select" name="kategori" required>
                                    <option value="">-- Pilih Kategori --</option>
                                    <option value="Oli Mesin" <?= old('kategori') == 'Oli Mesin' ? 'selected' : '' ?>>Oli Mesin</option>
                                    <option value="Oli Transmisi Manual" <?= old('kategori') == 'Oli Transmisi Manual' ? 'selected' : '' ?>>Oli Transmisi Manual</option>
                                    <option value="Oli Transmisi Matic" <?= old('kategori') == 'Oli Transmisi Matic' ? 'selected' : '' ?>>Oli Transmisi Matic</option>
                                    <option value="Filter Oli" <?= old('kategori') == 'Filter Oli' ? 'selected' : '' ?>>Filter Oli</option>
                                    <option value="Cleaner" <?= old('kategori') == 'Cleaner' ? 'selected' : '' ?>>Cleaner</option>
                                    <option value="Minyak Rem" <?= old('kategori') == 'Minyak Rem' ? 'selected' : '' ?>>Minyak Rem</option>
                                    <option value="Ban" <?= old('kategori') == 'Ban' ? 'selected' : '' ?>>Ban</option>
                                    <option value="Sparepart Mesin" <?= old('kategori') == 'Sparepart Mesin' ? 'selected' : '' ?>>Sparepart Mesin</option>
                                    <option value="Sparepart Transmisi Manual" <?= old('kategori') == 'Sparepart Transmisi Manual' ? 'selected' : '' ?>>Sparepart Transmisi Manual</option>
                                    <option value="Sparepart Transmisi Matic" <?= old('kategori') == 'Sparepart Transmisi Matic' ? 'selected' : '' ?>>Sparepart Transmisi Matic</option>
                                    <option value="Sparepart Kaki-kaki" <?= old('kategori') == 'Sparepart Kaki-kaki' ? 'selected' : '' ?>>Sparepart Kaki-kaki</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label">Satuan <span class="text-danger">*</span></label>
                                <select class="form-select" name="satuan" required>
                                    <option value="">-- Pilih Satuan --</option>
                                    <option value="Set" <?= old('satuan') == 'Set' ? 'selected' : '' ?>>Set</option>
                                    <option value="Pcs" <?= old('satuan') == 'Pcs' ? 'selected' : '' ?>>Pcs</option>
                                    <option value="Liter" <?= old('satuan') == 'Liter' ? 'selected' : '' ?>>Liter</option>
                                    <option value="Botol" <?= old('satuan') == 'Botol' ? 'selected' : '' ?>>Botol</option>
                                    <option value="Kaleng" <?= old('satuan') == 'Kaleng' ? 'selected' : '' ?>>Kaleng</option>
                                    <option value="Galon (4/5lt)" <?= old('satuan') == 'Galon (4/5lt)' ? 'selected' : '' ?>>Galon (4/5lt)</option>
                                </select>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Informasi Supplier -->
            <div class="card bg-light mb-3">
                <div class="card-body">
                    <h6 class="mb-3"><strong><i class="fas fa-truck me-2"></i>Informasi Supplier</strong></h6>
                    
                    <div class="mb-3">
                        <label class="form-label">Nama Supplier <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" name="pemasok" 
                               value="<?= old('pemasok', $sparepart['pemasok'] ?? '') ?>" required>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label">Kontak Supplier <span class="text-danger">*</span></label>
                                <input type="tel" class="form-control" name="kontak_pemasok" 
                                       value="<?= old('kontak_pemasok', $sparepart['kontak_pemasok'] ?? '') ?>" required>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label">Alamat Supplier <span class="text-danger">*</span></label>
                                <textarea class="form-control" name="alamat_pemasok" rows="2" required><?= old('alamat_pemasok', $sparepart['alamat_pemasok'] ?? '') ?></textarea>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Informasi Harga & Stok -->
            <div class="card bg-light mb-3">
                <div class="card-body">
                    <h6 class="mb-3"><strong><i class="fas fa-money-bill-wave me-2"></i>Harga & Stok</strong></h6>
                    
                    <div class="row">
                        <div class="col-md-4">
                            <div class="mb-3">
                                <label class="form-label">Harga Beli <span class="text-danger">*</span></label>
                                <input type="number" class="form-control" name="harga_beli" 
                                       value="<?= old('harga_beli', $sparepart['harga_beli']) ?>" 
                                       min="0" step="100" required>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="mb-3">
                                <label class="form-label">Harga Jual <span class="text-danger">*</span></label>
                                <input type="number" class="form-control" name="harga_jual" 
                                       value="<?= old('harga_jual', $sparepart['harga_jual']) ?>" 
                                       min="0" step="100" required>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="mb-3">
                                <label class="form-label">Stok <span class="text-danger">*</span></label>
                                <input type="number" class="form-control" name="stok" 
                                       value="<?= old('stok', $sparepart['stok']) ?>" 
                                       min="0" required>
                            </div>
                        </div>
                    </div>

                    <div class="alert alert-warning mb-0">
                        <i class="fas fa-exclamation-triangle me-2"></i>
                        <strong>Perhatian:</strong> Mengubah stok manual akan menimpa nilai stok saat ini. 
                        Untuk menambah stok, gunakan fitur Pembelian Sparepart.
                    </div>
                </div>
            </div>

            <div class="d-flex gap-2">
                <button type="submit" class="btn btn-danger">
                    <i class="fas fa-save me-1"></i> Update
                </button>
                <a href="<?= base_url('sparepart') ?>" class="btn btn-secondary">
                    <i class="fas fa-arrow-left me-1"></i> Kembali
                </a>
            </div>
        </form>
    </div>
</div>
<script>
document.getElementById('formEditSparepart').addEventListener('submit', function(e) {
    e.preventDefault();

    Swal.fire({
        title: 'Update Data Mekanik?',
        text: 'Perubahan data akan disimpan',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#ffc107',
        cancelButtonColor: '#6c757d',
        confirmButtonText: 'Ya, Update',
        cancelButtonText: 'Batal'
    }).then((result) => {
        if (result.isConfirmed) {
            this.submit();
        }
    });
});
</script>
<?= $this->include('layout/footer') ?>
