<?= $this->include('layout/header') ?>

<div class="card">
    <div class="card-header">
        <i class="fas fa-plus me-2"></i> Tambah Jasa Servis Baru
    </div>
    <div class="card-body">
        <form id="formCreateJasaServis" action="<?= base_url('jasa-servis/store') ?>" method="post">
            <?= csrf_field() ?>
            
            <div class="mb-3">
                <label class="form-label">Nama Layanan <span class="text-danger">*</span></label>
                <input type="text" class="form-control" name="nama_layanan" 
                       value="<?= old('nama_layanan') ?>" 
                       placeholder="Contoh: Ganti Oli Mesin + Filter" required>
            </div>

            <div class="row">
                <div class="col-md-6">
                    <div class="mb-3">
                        <label class="form-label">Kategori Servis <span class="text-danger">*</span></label>
                        <select class="form-select" name="kategori_servis" required>
                            <option value="">-- Pilih Kategori --</option>
                            <option value="Ringan" <?= old('kategori_servis') == 'Ringan' ? 'selected' : '' ?>>Ringan</option>
                            <option value="Berat" <?= old('kategori_servis') == 'Berat' ? 'selected' : '' ?>>Berat</option>
                            <option value="Tune Up" <?= old('kategori_servis') == 'Tune Up' ? 'selected' : '' ?>>Tune Up</option>
                            <option value="Cuci" <?= old('kategori_servis') == 'Cuci' ? 'selected' : '' ?>>Cuci</option>
                            <option value="Lainnya" <?= old('kategori_servis') == 'Lainnya' ? 'selected' : '' ?>>Lainnya</option>
                        </select>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="mb-3">
                        <label class="form-label">Harga Jasa <span class="text-danger">*</span></label>
                        <input type="number" class="form-control" name="harga_jasa" 
                               value="<?= old('harga_jasa') ?>" min="0" step="0.01" 
                               placeholder="50000" required>
                    </div>
                </div>
            </div>

            <div class="mb-3">
                <label class="form-label">Estimasi Durasi (dalam menit) <span class="text-danger">*</span></label>
                <input type="number" class="form-control" name="estimasi_durasi" 
                       value="<?= old('estimasi_durasi') ?>" min="0" 
                       placeholder="60" required>
                <small class="text-muted">Contoh: 30 menit, 60 menit, 120 menit, dll</small>
            </div>

            <div class="alert alert-info">
                <i class="fas fa-info-circle me-2"></i>
                <strong>Catatan:</strong> Harga jasa akan digunakan sebagai dasar perhitungan 
                total biaya servis dalam work order.
            </div>

            <div class="d-flex gap-2">
                <button type="submit" class="btn btn-danger">
                    <i class="fas fa-save me-1"></i> Simpan
                </button>
                <a href="<?= base_url('jasa-servis') ?>" class="btn btn-secondary">
                    <i class="fas fa-arrow-left me-1"></i> Kembali
                </a>
            </div>
        </form>
    </div>
</div>
<script>
document.getElementById('formCreateJasaServis').addEventListener('submit', function(e) {
    e.preventDefault();

    Swal.fire({
        title: 'Simpan Jasa Servis?',
        text: 'Pastikan data jasa servis sudah benar',
        icon: 'question',
        showCancelButton: true,
        confirmButtonColor: '#dc3545',
        cancelButtonColor: '#6c757d',
        confirmButtonText: 'Ya, Simpan',
        cancelButtonText: 'Batal'
    }).then((result) => {
        if (result.isConfirmed) {
            this.submit();
        }
    });
});
</script>

<?= $this->include('layout/footer') ?>
