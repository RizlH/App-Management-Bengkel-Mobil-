<?= $this->include('layout/header') ?>

<div class="card">
    <div class="card-header">
        <i class="fas fa-edit me-2"></i> Edit Penerimaan Servis
    </div>
    <div class="card-body">
        <?php if (session()->getFlashdata('errors')): ?>
            <div class="alert alert-danger">
                <ul class="mb-0">
                    <?php foreach (session()->getFlashdata('errors') as $error): ?>
                        <li><?= $error ?></li>
                    <?php endforeach; ?>
                </ul>
            </div>
        <?php endif; ?>
        
        <?php if (session()->getFlashdata('error')): ?>
            <div class="alert alert-danger">
                <i class="fas fa-exclamation-triangle me-2"></i>
                <?= session()->getFlashdata('error') ?>
            </div>
        <?php endif; ?>

        <form id="formEditPenerimaanServis" 
            action="<?= base_url('penerimaan-servis/update/' . $penerimaan_servis['id']) ?>" 
            method="post">

            <?= csrf_field() ?>
            
            <div class="alert alert-info">
                <strong>Nomor Servis:</strong> <?= $penerimaan_servis['nomor_servis'] ?>
                <br>
                <small class="text-danger">
                    <i class="fas fa-info-circle"></i> 
                    Kendaraan yang sedang dalam proses servis (Menunggu/Proses) tidak akan ditampilkan, 
                    kecuali kendaraan yang sedang diedit ini.
                </small>
            </div>

            <div class="row">
                <div class="col-md-6">
                    <div class="mb-3">
                        <label class="form-label">Kendaraan <span class="text-danger">*</span></label>
                        <select class="form-select" name="kendaraan_id" id="kendaraan_id" required>
                            <option value="">-- Pilih Kendaraan --</option>
                            <?php if (!empty($kendaraan)): ?>
                                <?php foreach ($kendaraan as $k): ?>
                                    <option value="<?= $k['id'] ?>" 
                                            data-pelanggan-id="<?= $k['pelanggan_id'] ?>"
                                            data-pelanggan-nama="<?= esc($k['nama_pemilik']) ?>"
                                            data-pelanggan-telepon="<?= esc($k['no_telepon'] ?? 'Belum ada telepon') ?>"
                                            data-pelanggan-alamat="<?= esc($k['alamat'] ?? 'Belum ada alamat') ?>"
                                            <?= $penerimaan_servis['kendaraan_id'] == $k['id'] ? 'selected' : '' ?>
                                            <?= old('kendaraan_id') == $k['id'] ? 'selected' : '' ?>>
                                        <?= esc($k['nomor_plat']) ?> - <?= esc($k['merk']) ?> (<?= esc($k['nama_pemilik']) ?>)
                                        <?= $penerimaan_servis['kendaraan_id'] == $k['id'] ? ' (Sedang diedit)' : '' ?>
                                    </option>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </select>
                        <div class="form-text">
                            <small class="text-danger">
                                <i class="fas fa-exclamation-triangle"></i> 
                                Jika mengganti kendaraan, pastikan kendaraan baru tidak sedang dalam proses servis
                            </small>
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="mb-3">
                        <label class="form-label">Pelanggan <span class="text-danger">*</span></label>
                        <input type="hidden" name="pelanggan_id" id="pelanggan_id" value="<?= $penerimaan_servis['pelanggan_id'] ?>">
                        <div id="info_pelanggan" class="border rounded p-3 bg-success text-white">
                            <p class="mb-1">
                                <i class="fas fa-user me-2"></i>
                                <span id="nama_pelanggan">
                                    <?php 
                                    // Cari nama pelanggan dari kendaraan yang dipilih
                                    foreach ($kendaraan as $k) {
                                        if ($k['id'] == $penerimaan_servis['kendaraan_id']) {
                                            echo $k['nama_pemilik'];
                                            break;
                                        }
                                    }
                                    ?>
                                </span>
                            </p>
                            <p class="mb-1">
                                <i class="fas fa-phone me-2"></i>
                                <small id="telepon_pelanggan">
                                    <?php 
                                    foreach ($kendaraan as $k) {
                                        if ($k['id'] == $penerimaan_servis['kendaraan_id']) {
                                            echo $k['no_telepon'] ?? '-';
                                            break;
                                        }
                                    }
                                    ?>
                                </small>
                            </p>
                            <p class="mb-0">
                                <i class="fas fa-map-marker-alt me-2"></i>
                                <small id="alamat_pelanggan">
                                    <?php 
                                    foreach ($kendaraan as $k) {
                                        if ($k['id'] == $penerimaan_servis['kendaraan_id']) {
                                            echo $k['alamat'] ?? '-';
                                            break;
                                        }
                                    }
                                    ?>
                                </small>
                            </p>
                        </div>
                        <div class="form-text">
                            <small>Pelanggan akan otomatis terdeteksi berdasarkan kendaraan yang dipilih</small>
                        </div>
                    </div>
                </div>
            </div>

            <div class="mb-3">
                <label class="form-label">Keluhan / Masalah Kendaraan <span class="text-danger">*</span></label>
                <textarea class="form-control" name="keluhan" rows="4" required><?= old('keluhan', $penerimaan_servis['keluhan']) ?></textarea>
            </div>

            <div class="row">
                <div class="col-md-4">
                    <div class="mb-3">
                        <label class="form-label">Tanggal & Waktu Masuk <span class="text-danger">*</span></label>
                        <input type="datetime-local" class="form-control" name="tanggal_masuk" 
                               value="<?= old('tanggal_masuk', date('Y-m-d\TH:i', strtotime($penerimaan_servis['tanggal_masuk']))) ?>" required>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="mb-3">
                        <label class="form-label">Estimasi Biaya <span class="text-danger">*</span></label>
                        <input type="number" class="form-control" name="estimasi_biaya" 
                               value="<?= old('estimasi_biaya', $penerimaan_servis['estimasi_biaya']) ?>" min="0" step="1000" required>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="mb-3">
                        <label class="form-label">Status <span class="text-danger">*</span></label>
                        <select class="form-select" name="status" required>
                            <option value="Menunggu" <?= $penerimaan_servis['status'] == 'Menunggu' || old('status') == 'Menunggu' ? 'selected' : '' ?>>Menunggu</option>
                            <option value="Proses" <?= $penerimaan_servis['status'] == 'Proses' || old('status') == 'Proses' ? 'selected' : '' ?>>Proses</option>
                            <option value="Selesai" <?= $penerimaan_servis['status'] == 'Selesai' || old('status') == 'Selesai' ? 'selected' : '' ?>>Selesai</option>
                        </select>
                    </div>
                </div>
            </div>

            <div class="alert alert-warning">
                <i class="fas fa-exclamation-triangle me-2"></i>
                <strong>Perhatian:</strong> Mengubah status menjadi "Selesai" akan mencatat tanggal selesai secara otomatis.
                <?php if ($penerimaan_servis['status'] == 'Selesai'): ?>
                    <br>
                    <strong>Status saat ini: Selesai</strong> (Tanggal selesai: <?= date('d/m/Y H:i', strtotime($penerimaan_servis['tanggal_selesai'] ?? '')) ?>)
                <?php endif; ?>
            </div>

            <div class="d-flex gap-2">
                <button type="submit" class="btn btn-danger">
                    <i class="fas fa-save me-1"></i> Update
                </button>
                <a href="<?= base_url('penerimaan-servis') ?>" class="btn btn-secondary">
                    <i class="fas fa-arrow-left me-1"></i> Kembali
                </a>
                <a href="<?= base_url('penerimaan-servis/show/' . $penerimaan_servis['id']) ?>" class="btn btn-info">
                    <i class="fas fa-eye me-1"></i> Detail
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
document.getElementById('formEditPenerimaanServis').addEventListener('submit', function(e) {
    e.preventDefault();

    const kendaraanId = $('#kendaraan_id').val();
    const pelangganId = $('#pelanggan_id').val();
    const status = $('select[name="status"]').val();
    
    if (!kendaraanId || !pelangganId) {
        Swal.fire({
            title: 'Perhatian!',
            text: 'Silakan pilih kendaraan terlebih dahulu',
            icon: 'warning',
            confirmButtonColor: '#ffc107',
        });
        return;
    }

    // Konfirmasi khusus jika mengganti kendaraan
    const originalKendaraanId = '<?= $penerimaan_servis['kendaraan_id'] ?>';
    let konfirmasiPesan = '';
    
    if (kendaraanId !== originalKendaraanId) {
        konfirmasiPesan = '<br><strong class="text-danger">⚠️ Anda mengganti kendaraan!</strong>';
    }
    
    // Konfirmasi khusus jika mengubah status menjadi Selesai
    if (status === 'Selesai' && '<?= $penerimaan_servis['status'] ?>' !== 'Selesai') {
        konfirmasiPesan += '<br><strong class="text-warning">📅 Tanggal selesai akan dicatat otomatis</strong>';
    }

    Swal.fire({
        title: 'Update Penerimaan Servis?',
        html: `Pelanggan: <strong>${$('#nama_pelanggan').text()}</strong><br>
               Kendaraan: <strong>${$('#kendaraan_id option:selected').text()}</strong><br>
               Status: <span class="badge bg-${status === 'Selesai' ? 'success' : status === 'Proses' ? 'info' : 'warning'}">${status}</span>
               ${konfirmasiPesan}`,
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#ffc107',
        cancelButtonColor: '#6c757d',
        confirmButtonText: 'Ya, Update',
        cancelButtonText: 'Batal',
        showLoaderOnConfirm: true,
        preConfirm: () => {
            return new Promise((resolve) => {
                setTimeout(() => {
                    resolve();
                }, 1000);
            });
        }
    }).then((result) => {
        if (result.isConfirmed) {
            this.submit();
        }
    });
});
</script>

<?= $this->include('layout/footer') ?>