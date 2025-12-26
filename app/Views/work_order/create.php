<?= $this->include('layout/header') ?>

<div class="card">
    <div class="card-header">
        <i class="fas fa-plus me-2"></i> Buat Work Order Baru
    </div>
    <div class="card-body">
        <form action="<?= base_url('work-order/store') ?>" method="post" id="formWorkOrder">
            <?= csrf_field() ?>
            
            <!-- Info Servis -->
            <div class="row">
                <div class="col-md-6">
                    <div class="mb-3">
                        <label class="form-label">Pilih Penerimaan Servis <span class="text-danger">*</span></label>
                        <select class="form-select" name="penerimaan_servis_id" required>
                            <option value="">-- Pilih Servis --</option>
                            <?php if (!empty($penerimaan_servis)): ?>
                                <?php foreach ($penerimaan_servis as $ps): ?>
                                    <option value="<?= esc($ps['id']) ?>">
                                        <?= esc($ps['nomor_servis']) ?> - <?= esc($ps['nama_pelanggan']) ?> (<?= esc($ps['nomor_plat']) ?>)
                                    </option>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </select>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="mb-3">
                        <label class="form-label">Mekanik <span class="text-danger">*</span></label>
                        <select class="form-select" name="mekanik_id" required>
                            <option value="">-- Pilih Mekanik --</option>
                            <?php if (!empty($mekanik)): ?>
                                <?php foreach ($mekanik as $m): ?>
                                    <option value="<?= esc($m['id']) ?>">
                                        <?= esc($m['nama']) ?> (<?= esc($m['posisi']) ?> - <?= esc($m['level_skill']) ?>)
                                    </option>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </select>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-md-6">
                    <div class="mb-3">
                        <label class="form-label">Tanggal Mulai <span class="text-danger">*</span></label>
                        <input type="datetime-local" class="form-control" name="tanggal_mulai" 
                               value="<?= date('Y-m-d\TH:i') ?>" required>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="mb-3">
                        <label class="form-label">Progres Awal</label>
                        <textarea class="form-control" name="progres" rows="2" 
                                  placeholder="Catatan progres pekerjaan..."></textarea>
                    </div>
                </div>
            </div>

            <!-- Jasa Servis -->
            <hr>
            <h5 class="mb-3"><i class="fas fa-wrench me-2"></i> Jasa Servis</h5>
            <div id="jasaContainer">
                <div class="jasa-item mb-3">
                    <div class="row align-items-end">
                        <div class="col-md-10">
                            <label class="form-label">Pilih Jasa Servis</label>
                            <select class="form-select" name="jasa_servis_id[]">
                                <option value="">-- Pilih Jasa --</option>
                                <?php if (!empty($jasa_servis)): ?>
                                    <?php foreach ($jasa_servis as $js): ?>
                                        <option value="<?= esc($js['id']) ?>">
                                            <?= esc($js['nama_layanan']) ?> - Rp <?= number_format($js['harga_jasa'], 0, ',', '.') ?>
                                        </option>
                                    <?php endforeach; ?>
                                <?php endif; ?>
                            </select>
                        </div>
                        <div class="col-md-2">
                            <button type="button" class="btn btn-danger btn-remove-jasa w-100" disabled>
                                <i class="fas fa-trash"></i>
                            </button>
                        </div>
                    </div>
                </div>
            </div>
            <button type="button" class="btn btn-success btn-sm mb-3" id="btnAddJasa">
                <i class="fas fa-plus me-1"></i> Tambah Jasa
            </button>

            <!-- Sparepart -->
            <hr>
            <h5 class="mb-3"><i class="fas fa-cogs me-2"></i> Sparepart</h5>
            <div id="sparepartContainer">
                <div class="sparepart-item mb-3">
                    <div class="row align-items-end">
                        <div class="col-md-7">
                            <label class="form-label">Pilih Sparepart</label>
                            <select class="form-select" name="sparepart_id[]">
                                <option value="">-- Pilih Sparepart --</option>
                                <?php if (!empty($sparepart)): ?>
                                    <?php foreach ($sparepart as $s): ?>
                                        <option value="<?= esc($s['id']) ?>" 
                                                data-stok="<?= esc($s['stok']) ?>">
                                            <?= esc($s['nama_barang']) ?> - Rp <?= number_format($s['harga_jual'], 0, ',', '.') ?> 
                                            (Stok: <?= esc($s['stok']) ?>)
                                        </option>
                                    <?php endforeach; ?>
                                <?php endif; ?>
                            </select>
                        </div>
                        <div class="col-md-3">
                            <label class="form-label">Jumlah</label>
                            <input type="number" class="form-control jumlah-sparepart" name="jumlah_sparepart[]" 
                                   min="1" placeholder="1">
                        </div>
                        <div class="col-md-2">
                            <button type="button" class="btn btn-danger btn-remove-sparepart w-100" disabled>
                                <i class="fas fa-trash"></i>
                            </button>
                        </div>
                    </div>
                </div>
            </div>
            <button type="button" class="btn btn-success btn-sm mb-3" id="btnAddSparepart">
                <i class="fas fa-plus me-1"></i> Tambah Sparepart
            </button>

            <hr>
            <div class="d-flex gap-2">
                <button type="submit" class="btn btn-danger" id="btnSubmit">
                    <i class="fas fa-save me-1"></i> Simpan Work Order
                </button>
                <a href="<?= base_url('work-order') ?>" class="btn btn-secondary" id="btnCancel">
                    <i class="fas fa-arrow-left me-1"></i> Kembali
                </a>
            </div>
        </form>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    console.log('Work Order Create - DOM Ready');
    
    // Template HTML untuk Jasa Servis
    var jasaTemplate = `
        <div class="jasa-item mb-3">
            <div class="row align-items-end">
                <div class="col-md-10">
                    <label class="form-label">Pilih Jasa Servis</label>
                    <select class="form-select" name="jasa_servis_id[]">
                        <option value="">-- Pilih Jasa --</option>
                        <?php if (!empty($jasa_servis)): ?>
                            <?php foreach ($jasa_servis as $js): ?>
                                <option value="<?= esc($js['id']) ?>">
                                    <?= esc($js['nama_layanan']) ?> - Rp <?= number_format($js['harga_jasa'], 0, ',', '.') ?>
                                </option>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </select>
                </div>
                <div class="col-md-2">
                    <button type="button" class="btn btn-danger btn-remove-jasa w-100">
                        <i class="fas fa-trash"></i>
                    </button>
                </div>
            </div>
        </div>
    `;
    
    // Template HTML untuk Sparepart
    var sparepartTemplate = `
        <div class="sparepart-item mb-3">
            <div class="row align-items-end">
                <div class="col-md-7">
                    <label class="form-label">Pilih Sparepart</label>
                    <select class="form-select" name="sparepart_id[]">
                        <option value="">-- Pilih Sparepart --</option>
                        <?php if (!empty($sparepart)): ?>
                            <?php foreach ($sparepart as $s): ?>
                                <option value="<?= esc($s['id']) ?>" data-stok="<?= esc($s['stok']) ?>">
                                    <?= esc($s['nama_barang']) ?> - Rp <?= number_format($s['harga_jual'], 0, ',', '.') ?> 
                                    (Stok: <?= esc($s['stok']) ?>)
                                </option>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </select>
                </div>
                <div class="col-md-3">
                    <label class="form-label">Jumlah</label>
                    <input type="number" class="form-control jumlah-sparepart" name="jumlah_sparepart[]" 
                           min="1" placeholder="1">
                </div>
                <div class="col-md-2">
                    <button type="button" class="btn btn-danger btn-remove-sparepart w-100">
                        <i class="fas fa-trash"></i>
                    </button>
                </div>
            </div>
        </div>
    `;
    
    // Fungsi update tombol remove
    function updateRemoveButtons() {
        var jasaCount = $('.jasa-item').length;
        var sparepartCount = $('.sparepart-item').length;
        
        $('.btn-remove-jasa').prop('disabled', jasaCount <= 1);
        $('.btn-remove-sparepart').prop('disabled', sparepartCount <= 1);
    }
    
    // Add Jasa Servis
    $('#btnAddJasa').click(function(e) {
        e.preventDefault();
        console.log('Tambah Jasa clicked');
        $('#jasaContainer').append(jasaTemplate);
        updateRemoveButtons();
    });
    
    // Remove Jasa Servis dengan SweetAlert
    $(document).on('click', '.btn-remove-jasa', function(e) {
        e.preventDefault();
        if ($('.jasa-item').length > 1) {
            Swal.fire({
                title: 'Hapus Jasa Servis',
                text: 'Apakah Anda yakin ingin menghapus item jasa servis ini?',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#3085d6',
                confirmButtonText: 'Ya, Hapus!',
                cancelButtonText: 'Batal'
            }).then((result) => {
                if (result.isConfirmed) {
                    $(this).closest('.jasa-item').remove();
                    updateRemoveButtons();
                    Swal.fire(
                        'Terhapus!',
                        'Item jasa servis telah dihapus.',
                        'success'
                    );
                }
            });
        } else {
            Swal.fire({
                title: 'Peringatan',
                text: 'Minimal harus ada 1 item jasa servis',
                icon: 'warning',
                confirmButtonColor: '#3085d6'
            });
        }
    });
    
    // Add Sparepart
    $('#btnAddSparepart').click(function(e) {
        e.preventDefault();
        console.log('Tambah Sparepart clicked');
        $('#sparepartContainer').append(sparepartTemplate);
        updateRemoveButtons();
    });
    
    // Remove Sparepart dengan SweetAlert
    $(document).on('click', '.btn-remove-sparepart', function(e) {
        e.preventDefault();
        if ($('.sparepart-item').length > 1) {
            Swal.fire({
                title: 'Hapus Sparepart',
                text: 'Apakah Anda yakin ingin menghapus item sparepart ini?',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#3085d6',
                confirmButtonText: 'Ya, Hapus!',
                cancelButtonText: 'Batal'
            }).then((result) => {
                if (result.isConfirmed) {
                    $(this).closest('.sparepart-item').remove();
                    updateRemoveButtons();
                    Swal.fire(
                        'Terhapus!',
                        'Item sparepart telah dihapus.',
                        'success'
                    );
                }
            });
        } else {
            Swal.fire({
                title: 'Peringatan',
                text: 'Minimal harus ada 1 item sparepart',
                icon: 'warning',
                confirmButtonColor: '#3085d6'
            });
        }
    });
    
    // Validasi stok sparepart dengan SweetAlert
    $(document).on('change keyup', '.jumlah-sparepart', function() {
        var row = $(this).closest('.sparepart-item');
        var select = row.find('select[name="sparepart_id[]"]');
        var selectedOption = select.find(':selected');
        var stok = parseInt(selectedOption.data('stok')) || 0;
        var jumlah = parseInt($(this).val()) || 0;
        
        if (jumlah > stok && stok > 0) {
            Swal.fire({
                title: 'Stok Tidak Cukup!',
                html: `Jumlah melebihi stok tersedia!<br>Stok yang tersedia: <strong>${stok}</strong>`,
                icon: 'warning',
                confirmButtonColor: '#3085d6'
            }).then(() => {
                $(this).val(stok);
            });
        }
    });
    
    // Validasi form sebelum submit dengan SweetAlert
    $('#formWorkOrder').submit(function(e) {
        e.preventDefault();
        
        var hasJasa = false;
        var hasSparepart = false;
        var errorMsg = '';
        
        // Cek input wajib
        if (!$('select[name="penerimaan_servis_id"]').val()) {
            errorMsg = 'Pilih Penerimaan Servis terlebih dahulu!';
        } else if (!$('select[name="mekanik_id"]').val()) {
            errorMsg = 'Pilih Mekanik terlebih dahulu!';
        }
        
        if (errorMsg) {
            Swal.fire({
                title: 'Data Belum Lengkap',
                text: errorMsg,
                icon: 'warning',
                confirmButtonColor: '#3085d6'
            });
            return false;
        }
        
        // Cek jasa servis
        $('select[name="jasa_servis_id[]"]').each(function() {
            if ($(this).val()) {
                hasJasa = true;
            }
        });
        
        // Cek sparepart
        $('.sparepart-item').each(function() {
            var sparepartId = $(this).find('select[name="sparepart_id[]"]').val();
            var jumlah = parseInt($(this).find('.jumlah-sparepart').val()) || 0;
            
            if (sparepartId && jumlah > 0) {
                hasSparepart = true;
            }
            
            // Validasi: jika sparepart dipilih, jumlah harus diisi
            if (sparepartId && jumlah <= 0) {
                errorMsg = 'Silakan isi jumlah untuk sparepart yang dipilih!';
                return false;
            }
        });
        
        if (!hasJasa && !hasSparepart) {
            Swal.fire({
                title: 'Item Belum Dipilih',
                text: 'Silakan pilih minimal 1 jasa servis atau sparepart!',
                icon: 'warning',
                confirmButtonColor: '#3085d6'
            });
            return false;
        }
        
        if (errorMsg) {
            Swal.fire({
                title: 'Validasi Gagal',
                text: errorMsg,
                icon: 'warning',
                confirmButtonColor: '#3085d6'
            });
            return false;
        }
        
        // Konfirmasi simpan dengan SweetAlert
        Swal.fire({
            title: 'Simpan Work Order?',
            html: 'Apakah Anda yakin ingin menyimpan work order ini?',
            icon: 'question',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            cancelButtonColor: '#3085d6',
            confirmButtonText: 'Ya, Simpan!',
            cancelButtonText: 'Batal',
            reverseButtons: true
        }).then((result) => {
            if (result.isConfirmed) {
                // Submit form
                $(this).off('submit').submit();
            }
        });
    });
    
    // Konfirmasi batal dengan SweetAlert
    $('#btnCancel').click(function(e) {
        e.preventDefault();
        const href = $(this).attr('href');
        
        Swal.fire({
            title: 'Batalkan Pembuatan?',
            html: 'Apakah Anda yakin ingin membatalkan pembuatan work order?<br>Data yang telah diisi akan hilang.',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            cancelButtonColor: '#3085d6',
            confirmButtonText: 'Ya, Batalkan!',
            cancelButtonText: 'Lanjutkan Edit',
            reverseButtons: true
        }).then((result) => {
            if (result.isConfirmed) {
                window.location.href = href;
            }
        });
    });
    
    // Initial setup
    updateRemoveButtons();
});
</script>

<?= $this->include('layout/footer') ?>