<?= $this->include('layout/header') ?>

<div class="card">
    <div class="card-header">
        <i class="fas fa-plus me-2"></i> Tambah Pelanggan Baru
    </div>
    <div class="card-body">

        <!-- 🔴 FORM TAMBAH -->
        <form action="<?= base_url('pelanggan/store') ?>" 
              method="post"
              id="formTambahPelanggan">

            <?= csrf_field() ?>
            
            <div class="mb-3">
                <label class="form-label">Nama Lengkap <span class="text-danger">*</span></label>
                <input type="text" 
                       class="form-control <?= isset($errors['nama']) ? 'is-invalid' : '' ?>" 
                       name="nama" value="<?= old('nama') ?>" required>
                <?php if (isset($errors['nama'])): ?>
                    <div class="invalid-feedback"><?= $errors['nama'] ?></div>
                <?php endif; ?>
            </div>

            <div class="mb-3">
                <label class="form-label">Alamat <span class="text-danger">*</span></label>
                <textarea class="form-control <?= isset($errors['alamat']) ? 'is-invalid' : '' ?>" 
                          name="alamat" rows="3" required><?= old('alamat') ?></textarea>
                <?php if (isset($errors['alamat'])): ?>
                    <div class="invalid-feedback"><?= $errors['alamat'] ?></div>
                <?php endif; ?>
            </div>

            <div class="mb-3">
                <label class="form-label">No. Telepon <span class="text-danger">*</span></label>
                <input type="tel" 
                       class="form-control <?= isset($errors['no_telepon']) ? 'is-invalid' : '' ?>" 
                       name="no_telepon" value="<?= old('no_telepon') ?>" required>
                <?php if (isset($errors['no_telepon'])): ?>
                    <div class="invalid-feedback"><?= $errors['no_telepon'] ?></div>
                <?php endif; ?>
            </div>

            <div class="d-flex gap-2">
                <button type="submit" class="btn btn-danger">
                    <i class="fas fa-save me-1"></i> Simpan
                </button>
                <a href="<?= base_url('pelanggan') ?>" class="btn btn-secondary">
                    <i class="fas fa-arrow-left me-1"></i> Kembali
                </a>
            </div>
        </form>
    </div>
</div>

<!-- 🔥 SWEETALERT KONFIRMASI TAMBAH -->
<script>
document.getElementById('formTambahPelanggan').addEventListener('submit', function(e) {
    e.preventDefault();

    Swal.fire({
        title: 'Simpan data pelanggan?',
        text: 'Pastikan data sudah benar',
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
