<?= $this->include('layout/header') ?>

<div class="card">
    <div class="card-header">
        <i class="fas fa-plus me-2"></i> Tambah Kendaraan Baru
    </div>
    <div class="card-body">
        <form action="<?= base_url('kendaraan/store') ?>" 
            method="post" 
            enctype="multipart/form-data"
            id="formTambahKendaraan">  
            <div class="mb-3">
                <label class="form-label">Pemilik Kendaraan <span class="text-danger">*</span></label>
                <select class="form-select" name="pelanggan_id" required>
                    <option value="">-- Pilih Pelanggan --</option>
                    <?php foreach ($pelanggan as $p): ?>
                        <option value="<?= $p['id'] ?>" <?= old('pelanggan_id') == $p['id'] ? 'selected' : '' ?>>
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
                               value="<?= old('nomor_plat') ?>" placeholder="B 1234 XYZ" required>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="mb-3">
                        <label class="form-label">Tahun <span class="text-danger">*</span></label>
                        <input type="number" class="form-control" name="tahun" 
                               value="<?= old('tahun') ?>" min="1900" max="<?= date('Y') + 1 ?>" required>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-md-6">
                    <div class="mb-3">
                        <label class="form-label">Merk <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" name="merk" 
                               value="<?= old('merk') ?>" placeholder="Toyota, Honda, dll" required>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="mb-3">
                        <label class="form-label">Tipe <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" name="tipe" 
                               value="<?= old('tipe') ?>" placeholder="Avanza, Jazz, dll" required>
                    </div>
                </div>
            </div>

            <div class="mb-3">
                <label class="form-label">Foto Kendaraan (Tampak Depan) <span class="text-danger">*</span></label>
                <input type="file" class="form-control" name="foto" accept="image/*" required>
                <small class="text-muted">Format: JPG, PNG. Maksimal 2MB</small>
            </div>

            <div class="d-flex gap-2">
                <button type="submit" class="btn btn-danger">
                    <i class="fas fa-save me-1"></i> Simpan
                </button>
                <a href="<?= base_url('kendaraan') ?>" class="btn btn-secondary">
                    <i class="fas fa-arrow-left me-1"></i> Kembali
                </a>
            </div>
        </form>
    </div>
</div>
<script>
document.getElementById('formTambahKendaraan').addEventListener('submit', function(e) {
    e.preventDefault();

    Swal.fire({
        title: 'Simpan data kendaraan?',
        text: 'Pastikan data dan foto kendaraan sudah benar',
        icon: 'question',
        showCancelButton: true,
        confirmButtonColor: '#dc3545',
        cancelButtonColor: '#6c757d',
        confirmButtonText: 'Ya, simpan',
        cancelButtonText: 'Batal'
    }).then((result) => {
        if (result.isConfirmed) {
            this.submit();
        }
    });
});
</script>

<?= $this->include('layout/footer') ?>