<?= $this->include('layout/header') ?>

<div class="card">
    <div class="card-header">
        <i class="fas fa-edit me-2"></i> Edit Pelanggan
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

        <!-- 🔴 TAMBAH id FORM -->
        <form action="<?= base_url('pelanggan/update/' . $pelanggan['id']) ?>" 
              method="post"
              id="formEditPelanggan">

            <?= csrf_field() ?>
            
            <div class="mb-3">
                <label class="form-label">Nama Lengkap <span class="text-danger">*</span></label>
                <input type="text" class="form-control" name="nama" 
                       value="<?= old('nama', $pelanggan['nama']) ?>" required>
            </div>

            <div class="mb-3">
                <label class="form-label">Alamat <span class="text-danger">*</span></label>
                <textarea class="form-control" name="alamat" rows="3" required><?= old('alamat', $pelanggan['alamat']) ?></textarea>
            </div>

            <div class="mb-3">
                <label class="form-label">No. Telepon <span class="text-danger">*</span></label>
                <input type="tel" class="form-control" name="no_telepon" 
                       value="<?= old('no_telepon', $pelanggan['no_telepon']) ?>" required>
            </div>

            <div class="d-flex gap-2">
                <button type="submit" class="btn btn-danger">
                    <i class="fas fa-save me-1"></i> Update
                </button>
                <a href="<?= base_url('pelanggan') ?>" class="btn btn-secondary">
                    <i class="fas fa-arrow-left me-1"></i> Kembali
                </a>
            </div>
        </form>
    </div>
</div>

<!-- 🔥 SWEETALERT KONFIRMASI EDIT -->
<script>
document.getElementById('formEditPelanggan').addEventListener('submit', function(e) {
    e.preventDefault();

    Swal.fire({
        title: 'Simpan perubahan?',
        text: 'Data pelanggan akan diperbarui',
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
