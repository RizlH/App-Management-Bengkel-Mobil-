<?= $this->include('layout/header') ?>

<div class="card">
    <div class="card-header">
        <i class="fas fa-edit me-2"></i> Edit Mekanik
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

        <form id="formEditMekanik" action="<?= base_url('mekanik/update/' . $mekanik['id']) ?>" method="post">
            <?= csrf_field() ?>
            
            <div class="mb-3">
                <label class="form-label">Nama Lengkap <span class="text-danger">*</span></label>
                <input type="text" class="form-control" name="nama" 
                       value="<?= old('nama', $mekanik['nama']) ?>" required>
            </div>

            <div class="row">
                <div class="col-md-6">
                    <div class="mb-3">
                        <label class="form-label">Posisi/Spesialisasi <span class="text-danger">*</span></label>
                        <select class="form-select" name="posisi" required>
                            <option value="">-- Pilih Posisi --</option>
                            <option value="Mesin" <?= $mekanik['posisi'] == 'Mesin' ? 'selected' : '' ?>>Mesin</option>
                            <option value="Kelistrikan" <?= $mekanik['posisi'] == 'Kelistrikan' ? 'selected' : '' ?>>Kelistrikan</option>
                            <option value="Ban" <?= $mekanik['posisi'] == 'Ban' ? 'selected' : '' ?>>Ban</option>
                            <option value="Umum" <?= $mekanik['posisi'] == 'Umum' ? 'selected' : '' ?>>Umum</option>
                        </select>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="mb-3">
                        <label class="form-label">Level Skill <span class="text-danger">*</span></label>
                        <select class="form-select" name="level_skill" required>
                            <option value="">-- Pilih Level --</option>
                            <option value="Junior" <?= $mekanik['level_skill'] == 'Junior' ? 'selected' : '' ?>>Junior</option>
                            <option value="Senior" <?= $mekanik['level_skill'] == 'Senior' ? 'selected' : '' ?>>Senior</option>
                            <option value="Expert" <?= $mekanik['level_skill'] == 'Expert' ? 'selected' : '' ?>>Expert</option>
                        </select>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-md-6">
                    <div class="mb-3">
                        <label class="form-label">No. Kontak <span class="text-danger">*</span></label>
                        <input type="tel" class="form-control" name="kontak" 
                               value="<?= old('kontak', $mekanik['kontak']) ?>" required>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="mb-3">
                        <label class="form-label">Status <span class="text-danger">*</span></label>
                        <select class="form-select" name="status_aktif" required>
                            <option value="1" <?= $mekanik['status_aktif'] == '1' ? 'selected' : '' ?>>Aktif</option>
                            <option value="0" <?= $mekanik['status_aktif'] == '0' ? 'selected' : '' ?>>Tidak Aktif</option>
                        </select>
                    </div>
                </div>
            </div>

            <div class="d-flex gap-2">
                <button type="submit" class="btn btn-danger">
                    <i class="fas fa-save me-1"></i> Update
                </button>
                <a href="<?= base_url('mekanik') ?>" class="btn btn-secondary">
                    <i class="fas fa-arrow-left me-1"></i> Kembali
                </a>
            </div>
        </form>
    </div>
</div>
<script>
document.getElementById('formEditMekanik').addEventListener('submit', function(e) {
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
