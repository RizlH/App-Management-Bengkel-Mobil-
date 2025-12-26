<?= $this->include('layout/header') ?>

<div class="card">
    <div class="card-header">
        <i class="fas fa-edit me-2"></i> Edit Kendaraan
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

        <form action="<?= base_url('kendaraan/update/' . $kendaraan['id']) ?>" 
            method="post" 
            enctype="multipart/form-data"
            id="formEditKendaraan">

            
            <div class="mb-3">
                <label class="form-label">Pemilik Kendaraan <span class="text-danger">*</span></label>
                <select class="form-select" name="pelanggan_id" required>
                    <option value="">-- Pilih Pelanggan --</option>
                    <?php foreach ($pelanggan as $p): ?>
                        <option value="<?= $p['id'] ?>" <?= $kendaraan['pelanggan_id'] == $p['id'] ? 'selected' : '' ?>>
                            <?= $p['nama'] ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>

            <div class="row">
                <div class="col-md-6">
                    <div class="mb-3">
                        <label class="form-label">Nomor Plat <span class="text-danger">*</span></label>
                        <input type="text" class="form-control text-uppercase" name="nomor_plat" 
                               value="<?= old('nomor_plat', $kendaraan['nomor_plat']) ?>" required>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="mb-3">
                        <label class="form-label">Tahun <span class="text-danger">*</span></label>
                        <input type="number" class="form-control" name="tahun" 
                               value="<?= old('tahun', $kendaraan['tahun']) ?>" 
                               min="1900" max="<?= date('Y') + 1 ?>" required>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-md-6">
                    <div class="mb-3">
                        <label class="form-label">Merk <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" name="merk" 
                               value="<?= old('merk', $kendaraan['merk']) ?>" required>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="mb-3">
                        <label class="form-label">Tipe <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" name="tipe" 
                               value="<?= old('tipe', $kendaraan['tipe']) ?>" required>
                    </div>
                </div>
            </div>

            <div class="mb-3">
                <label class="form-label">Foto Kendaraan (Opsional)</label>
                <?php if ($kendaraan['foto']): ?>
                    <div class="mb-2">
                        <img src="<?= base_url('uploads/kendaraan/' . $kendaraan['foto']) ?>" 
                             class="img-thumbnail" style="max-width: 200px;">
                        <p class="small text-muted mt-1">Foto saat ini</p>
                    </div>
                <?php endif; ?>
                <input type="file" class="form-control" name="foto" accept="image/*">
                <small class="text-muted">Kosongkan jika tidak ingin mengganti foto. Format: JPG, PNG. Maksimal 2MB</small>
            </div>

            <div class="d-flex gap-2">
                <button type="submit" class="btn btn-danger">
                    <i class="fas fa-save me-1"></i> Update
                </button>
                <a href="<?= base_url('kendaraan') ?>" class="btn btn-secondary">
                    <i class="fas fa-arrow-left me-1"></i> Kembali
                </a>
            </div>
        </form>
    </div>
</div>
<script>
document.getElementById('formEditKendaraan').addEventListener('submit', function(e) {
    e.preventDefault();

    Swal.fire({
        title: 'Update data kendaraan?',
        text: 'Perubahan akan disimpan',
        icon: 'question',
        showCancelButton: true,
        confirmButtonColor: '#dc3545',
        cancelButtonColor: '#6c757d',
        confirmButtonText: 'Ya, update',
        cancelButtonText: 'Batal'
    }).then((result) => {
        if (result.isConfirmed) {
            this.submit();
        }
    });
});
</script>

<?= $this->include('layout/footer') ?>
