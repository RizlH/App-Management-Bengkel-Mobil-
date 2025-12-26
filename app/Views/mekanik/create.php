<?= $this->include('layout/header') ?>

<div class="card">
    <div class="card-header">
        <i class="fas fa-plus me-2"></i> Tambah Mekanik Baru
    </div>
    <div class="card-body">
        <form id="formCreateMekanik" action="<?= base_url('mekanik/store') ?>" method="post">
            <?= csrf_field() ?>
            
            <div class="mb-3">
                <label class="form-label">Nama Lengkap <span class="text-danger">*</span></label>
                <input type="text" class="form-control" name="nama" value="<?= old('nama') ?>" required>
            </div>

            <div class="row">
                <div class="col-md-6">
                    <div class="mb-3">
                        <label class="form-label">Posisi/Spesialisasi <span class="text-danger">*</span></label>
                        <select class="form-select" name="posisi" required>
                            <option value="">-- Pilih Posisi --</option>
                            <option value="Mesin" <?= old('posisi') == 'Mesin' ? 'selected' : '' ?>>Mesin</option>
                            <option value="Kelistrikan" <?= old('posisi') == 'Kelistrikan' ? 'selected' : '' ?>>Kelistrikan</option>
                            <option value="Ban" <?= old('posisi') == 'Ban' ? 'selected' : '' ?>>Ban</option>
                            <option value="Umum" <?= old('posisi') == 'Umum' ? 'selected' : '' ?>>Umum</option>
                        </select>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="mb-3">
                        <label class="form-label">Level Skill <span class="text-danger">*</span></label>
                        <select class="form-select" name="level_skill" required>
                            <option value="">-- Pilih Level --</option>
                            <option value="Junior" <?= old('level_skill') == 'Junior' ? 'selected' : '' ?>>Junior</option>
                            <option value="Senior" <?= old('level_skill') == 'Senior' ? 'selected' : '' ?>>Senior</option>
                            <option value="Expert" <?= old('level_skill') == 'Expert' ? 'selected' : '' ?>>Expert</option>
                        </select>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-md-6">
                    <div class="mb-3">
                        <label class="form-label">No. Kontak <span class="text-danger">*</span></label>
                        <input type="tel" class="form-control" name="kontak" value="<?= old('kontak') ?>" required>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="mb-3">
                        <label class="form-label">Status <span class="text-danger">*</span></label>
                        <select class="form-select" name="status_aktif" required>
                            <option value="1" <?= old('status_aktif') == '1' ? 'selected' : '' ?>>Aktif</option>
                            <option value="0" <?= old('status_aktif') == '0' ? 'selected' : '' ?>>Tidak Aktif</option>
                        </select>
                    </div>
                </div>
            </div>

            <div class="d-flex gap-2">
                <button type="submit" class="btn btn-danger">
                    <i class="fas fa-save me-1"></i> Simpan
                </button>
                <a href="<?= base_url('mekanik') ?>" class="btn btn-secondary">
                    <i class="fas fa-arrow-left me-1"></i> Kembali
                </a>
            </div>
        </form>
    </div>
</div>
<script>
document.getElementById('formCreateMekanik').addEventListener('submit', function(e) {
    e.preventDefault();

    Swal.fire({
        title: 'Simpan Data Mekanik?',
        text: 'Pastikan semua data mekanik sudah benar',
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
