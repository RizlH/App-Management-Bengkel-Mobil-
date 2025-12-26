<?= $this->include('layout/header') ?>

<div class="card">
    <div class="card-header">
        <i class="fas fa-edit me-2"></i> Edit Jasa Servis
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

        <form id="formEditJasaServis" action="<?= base_url('jasa-servis/update/' . $jasa_servis['id']) ?>" method="post">

            <?= csrf_field() ?>
            
            <div class="mb-3">
                <label class="form-label">Nama Layanan <span class="text-danger">*</span></label>
                <input type="text" class="form-control" name="nama_layanan" 
                       value="<?= old('nama_layanan', $jasa_servis['nama_layanan']) ?>" required>
            </div>

            <div class="row">
                <div class="col-md-6">
                    <div class="mb-3">
                        <label class="form-label">Kategori Servis <span class="text-danger">*</span></label>
                        <select class="form-select" name="kategori_servis" required>
                            <option value="">-- Pilih Kategori --</option>
                            <option value="Ringan" <?= $jasa_servis['kategori_servis'] == 'Ringan' ? 'selected' : '' ?>>Ringan</option>
                            <option value="Berat" <?= $jasa_servis['kategori_servis'] == 'Berat' ? 'selected' : '' ?>>Berat</option>
                            <option value="Tune Up" <?= $jasa_servis['kategori_servis'] == 'Tune Up' ? 'selected' : '' ?>>Tune Up</option>
                            <option value="Cuci" <?= $jasa_servis['kategori_servis'] == 'Cuci' ? 'selected' : '' ?>>Cuci</option>
                            <option value="Lainnya" <?= $jasa_servis['kategori_servis'] == 'Lainnya' ? 'selected' : '' ?>>Lainnya</option>
                        </select>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="mb-3">
                        <label class="form-label">Harga Jasa <span class="text-danger">*</span></label>
                        <input type="number" class="form-control" name="harga_jasa" 
                               value="<?= old('harga_jasa', $jasa_servis['harga_jasa']) ?>" 
                               min="0" step="0.01" required>
                    </div>
                </div>
            </div>

            <div class="mb-3">
                <label class="form-label">Estimasi Durasi (dalam menit) <span class="text-danger">*</span></label>
                <input type="number" class="form-control" name="estimasi_durasi" 
                       value="<?= old('estimasi_durasi', $jasa_servis['estimasi_durasi']) ?>" 
                       min="0" required>
                <small class="text-muted">Contoh: 30 menit, 60 menit, 120 menit, dll</small>
            </div>

            <div class="d-flex gap-2">
                <button type="submit" class="btn btn-danger">
                    <i class="fas fa-save me-1"></i> Update
                </button>
                <a href="<?= base_url('jasa-servis') ?>" class="btn btn-secondary">
                    <i class="fas fa-arrow-left me-1"></i> Kembali
                </a>
            </div>
        </form>
    </div>
</div>
<script>
document.getElementById('formEditJasaServis').addEventListener('submit', function(e) {
    e.preventDefault();

    Swal.fire({
        title: 'Update Jasa Servis?',
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
