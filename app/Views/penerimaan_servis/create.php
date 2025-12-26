<?= $this->include('layout/header') ?>

<div class="card">
    <div class="card-header">
        <i class="fas fa-clipboard-check me-2"></i> Check-in Kendaraan untuk Servis
    </div>
    <div class="card-body">
        <?php if (session()->getFlashdata('error')): ?>
            <div class="alert alert-danger">
                <i class="fas fa-exclamation-triangle me-2"></i>
                <?= session()->getFlashdata('error') ?>
            </div>
        <?php endif; ?>
        
        <?php if (session()->getFlashdata('errors')): ?>
            <div class="alert alert-danger">
                <ul class="mb-0">
                    <?php foreach (session()->getFlashdata('errors') as $error): ?>
                        <li><?= $error ?></li>
                    <?php endforeach; ?>
                </ul>
            </div>
        <?php endif; ?>
        
        <?php if (empty($kendaraan)): ?>
            <div class="alert alert-warning">
                <i class="fas fa-exclamation-circle me-2"></i>
                <strong>Perhatian!</strong> Tidak ada kendaraan yang tersedia untuk servis baru.
                <p class="mb-0 mt-2">
                    Semua kendaraan saat ini sedang dalam proses servis (status "Menunggu" atau "Proses").
                    <br>
                    <a href="<?= base_url('kendaraan') ?>" class="alert-link">Kelola kendaraan</a> atau 
                    <a href="<?= base_url('penerimaan-servis') ?>" class="alert-link">lihat daftar servis aktif</a>.
                </p>
            </div>
        <?php endif; ?>

        <form id="formCreatePenerimaanServis" action="<?= base_url('penerimaan-servis/store') ?>" method="post" <?= empty($kendaraan) ? 'onsubmit="return false;"' : '' ?>>
            <?= csrf_field() ?>
            
            <div class="row">
                <div class="col-md-6">
                    <div class="mb-3">
                        <label class="form-label">Kendaraan <span class="text-danger">*</span></label>
                        <select class="form-select" name="kendaraan_id" id="kendaraan_id" required <?= empty($kendaraan) ? 'disabled' : '' ?>>
                            <option value="">-- Pilih Kendaraan --</option>
                            <?php if (!empty($kendaraan)): ?>
                                <?php foreach ($kendaraan as $k): ?>
                                    <option value="<?= $k['id'] ?>" 
                                            data-pelanggan-id="<?= $k['pelanggan_id'] ?>"
                                            data-pelanggan-nama="<?= esc($k['nama_pemilik']) ?>"
                                            data-pelanggan-telepon="<?= esc($k['no_telepon'] ?? 'Belum ada telepon') ?>"
                                            data-pelanggan-alamat="<?= esc($k['alamat'] ?? 'Belum ada alamat') ?>"
                                            <?= old('kendaraan_id') == $k['id'] ? 'selected' : '' ?>>
                                        <?= esc($k['nomor_plat']) ?> - <?= esc($k['merk']) ?> (<?= esc($k['nama_pemilik']) ?>)
                                    </option>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </select>
                        <?php if (empty($kendaraan)): ?>
                            <div class="form-text text-danger">
                                <i class="fas fa-info-circle"></i> Tidak ada kendaraan yang tersedia
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="mb-3">
                        <label class="form-label">Pelanggan <span class="text-danger">*</span></label>
                        <input type="hidden" name="pelanggan_id" id="pelanggan_id" value="">
                        <div id="info_pelanggan" class="border rounded p-3 bg-light">
                            <p class="mb-1">
                                <i class="fas fa-user me-2"></i>
                                <span id="nama_pelanggan" class="text-muted">
                                    <?= empty($kendaraan) ? 'Tidak ada kendaraan tersedia' : 'Pilih kendaraan terlebih dahulu' ?>
                                </span>
                            </p>
                            <p class="mb-1">
                                <i class="fas fa-phone me-2"></i>
                                <small id="telepon_pelanggan">-</small>
                            </p>
                            <p class="mb-0">
                                <i class="fas fa-map-marker-alt me-2"></i>
                                <small id="alamat_pelanggan">-</small>
                            </p>
                        </div>
                        <div class="form-text">
                            <small>Pelanggan akan otomatis terdeteksi berdasarkan kendaraan yang dipilih</small>
                            <br>
                            <small class="text-danger">
                                <i class="fas fa-info-circle"></i> 
                                Hanya menampilkan kendaraan yang tidak memiliki servis aktif (Menunggu/Proses)
                            </small>
                        </div>
                    </div>
                </div>
            </div>

            <div class="mb-3">
                <label class="form-label">Keluhan / Masalah Kendaraan <span class="text-danger">*</span></label>
                <textarea class="form-control" name="keluhan" rows="4" required 
                          placeholder="Jelaskan masalah atau keluhan kendaraan secara detail..." 
                          <?= empty($kendaraan) ? 'disabled' : '' ?>><?= old('keluhan') ?></textarea>
            </div>

            <div class="row">
                <div class="col-md-6">
                    <div class="mb-3">
                        <label class="form-label">Tanggal & Waktu Masuk <span class="text-danger">*</span></label>
                        <input type="datetime-local" class="form-control" name="tanggal_masuk" 
                               value="<?= old('tanggal_masuk') ?? date('Y-m-d\TH:i') ?>" required 
                               <?= empty($kendaraan) ? 'disabled' : '' ?>>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="mb-3">
                        <label class="form-label">Estimasi Biaya <span class="text-danger">*</span></label>
                        <input type="number" class="form-control" name="estimasi_biaya" 
                               value="<?= old('estimasi_biaya') ?>" min="0" step="1000" 
                               placeholder="500000" required <?= empty($kendaraan) ? 'disabled' : '' ?>>
                        <div class="form-text">
                            <small>Dalam Rupiah (Rp)</small>
                        </div>
                    </div>
                </div>
            </div>

            <div class="alert alert-info">
                <i class="fas fa-info-circle me-2"></i>
                <strong>Catatan:</strong> Setelah penerimaan servis disimpan, Anda dapat membuat work order 
                untuk menentukan mekanik, jasa servis, dan sparepart yang dibutuhkan.
                <?php if (empty($kendaraan)): ?>
                    <br>
                    <strong class="text-danger">Untuk membuat servis baru, pastikan ada kendaraan yang tersedia.</strong>
                <?php endif; ?>
            </div>

            <div class="d-flex gap-2">
                <?php if (!empty($kendaraan)): ?>
                    <button type="submit" class="btn btn-danger">
                        <i class="fas fa-save me-1"></i> Simpan Check-in
                    </button>
                <?php else: ?>
                    <button type="button" class="btn btn-danger" disabled>
                        <i class="fas fa-save me-1"></i> Simpan Check-in
                    </button>
                <?php endif; ?>
                <a href="<?= base_url('penerimaan-servis') ?>" class="btn btn-secondary">
                    <i class="fas fa-arrow-left me-1"></i> Kembali
                </a>
            </div>
        </form>
    </div>
</div>

<script>
$(document).ready(function() {
    // Function untuk update tampilan pelanggan
    function updatePelangganInfo() {
        const selectedOption = $('#kendaraan_id option:selected');
        
        if (selectedOption.val()) {
            const pelangganId = selectedOption.data('pelanggan-id');
            const pelangganNama = selectedOption.data('pelanggan-nama');
            const pelangganTelepon = selectedOption.data('pelanggan-telepon') || '-';
            const pelangganAlamat = selectedOption.data('pelanggan-alamat') || '-';
            
            // Update hidden field
            $('#pelanggan_id').val(pelangganId);
            
            // Update display
            $('#nama_pelanggan').html(`<strong>${pelangganNama}</strong>`);
            $('#telepon_pelanggan').text(pelangganTelepon);
            $('#alamat_pelanggan').text(pelangganAlamat);
            
            // Update styling
            $('#info_pelanggan')
                .removeClass('bg-light text-dark')
                .addClass('bg-success text-white');
        } else {
            // Reset jika tidak ada yang dipilih
            $('#pelanggan_id').val('');
            $('#nama_pelanggan').html('<span class="text-muted">Pilih kendaraan terlebih dahulu</span>');
            $('#telepon_pelanggan').text('-');
            $('#alamat_pelanggan').text('-');
            $('#info_pelanggan')
                .removeClass('bg-success text-white')
                .addClass('bg-light text-dark');
        }
    }
    
    // Saat kendaraan dipilih
    $('#kendaraan_id').on('change', updatePelangganInfo);
    
    // Saat halaman pertama kali load
    updatePelangganInfo();
});

// Konfirmasi sebelum submit
document.getElementById('formCreatePenerimaanServis')?.addEventListener('submit', function(e) {
    e.preventDefault();

    const kendaraanId = $('#kendaraan_id').val();
    const pelangganId = $('#pelanggan_id').val();
    
    if (!kendaraanId || !pelangganId) {
        Swal.fire({
            title: 'Perhatian!',
            text: 'Silakan pilih kendaraan terlebih dahulu',
            icon: 'warning',
            confirmButtonColor: '#dc3545',
        });
        return;
    }

    Swal.fire({
        title: 'Simpan Penerimaan Servis?',
        html: `Pelanggan: <strong>${$('#nama_pelanggan').text()}</strong><br>
               Kendaraan: <strong>${$('#kendaraan_id option:selected').text()}</strong>`,
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