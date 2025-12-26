<?= $this->include('layout/header') ?>

<!-- Tambahkan CSS SweetAlert -->
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">

<div class="row">
    <div class="col-md-8">
        <div class="card">
            <div class="card-header">
                <i class="fas fa-money-bill-wave me-2"></i> Buat Pembayaran Baru
            </div>
            <div class="card-body">
                <?php if (session()->getFlashdata('error')): ?>
                    <div class="alert alert-danger">
                        <?= session()->getFlashdata('error') ?>
                    </div>
                <?php endif; ?>

                <form action="<?= base_url('pembayaran/store') ?>" method="post" id="paymentForm">
                    <?= csrf_field() ?>
                    
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label">Pilih Servis <span class="text-danger">*</span></label>
                                <select class="form-select" name="penerimaan_servis_id" id="selectServis" required>
                                    <option value="">-- Pilih Servis yang Selesai --</option>
                                    <?php foreach ($penerimaan_servis as $ps): ?>
                                        <option value="<?= $ps['id'] ?>" 
                                                data-nomor="<?= $ps['nomor_servis'] ?>"
                                                data-pelanggan="<?= $ps['nama_pelanggan'] ?>"
                                                data-kendaraan="<?= $ps['nomor_plat'] ?>"
                                                <?= (isset($_GET['servis_id']) && $_GET['servis_id'] == $ps['id']) ? 'selected' : '' ?>>
                                            <?= $ps['nomor_servis'] ?> - <?= $ps['nama_pelanggan'] ?> (<?= $ps['nomor_plat'] ?>)
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                                <div class="invalid-feedback" id="servisError"></div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label">Metode Pembayaran <span class="text-danger">*</span></label>
                                <select class="form-select" name="metode_pembayaran" id="metodePembayaran" required>
                                    <option value="">-- Pilih Metode --</option>
                                    <option value="Cash">Cash</option>
                                    <option value="Transfer">Transfer Bank</option>
                                    <option value="QRIS">QRIS</option>
                                </select>
                                <div class="invalid-feedback" id="metodeError"></div>
                            </div>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Tanggal Pembayaran <span class="text-danger">*</span></label>
                        <input type="datetime-local" class="form-control" name="tanggal_bayar" id="tanggalBayar"
                               value="<?= date('Y-m-d\TH:i') ?>" required>
                        <div class="invalid-feedback" id="tanggalError"></div>
                    </div>

                    <div id="infoServis" style="display: none;">
                        <hr>
                        <h6 class="mb-3"><strong>Informasi Servis:</strong></h6>
                        <div class="row">
                            <div class="col-md-4">
                                <p class="mb-1"><strong>Nomor Servis:</strong></p>
                                <p id="infoNomor" class="text-muted">-</p>
                            </div>
                            <div class="col-md-4">
                                <p class="mb-1"><strong>Pelanggan:</strong></p>
                                <p id="infoPelanggan" class="text-muted">-</p>
                            </div>
                            <div class="col-md-4">
                                <p class="mb-1"><strong>Kendaraan:</strong></p>
                                <p id="infoKendaraan" class="text-muted">-</p>
                            </div>
                        </div>
                    </div>

                    <div class="alert alert-info">
                        <i class="fas fa-info-circle me-2"></i>
                        <strong>Catatan:</strong> Total biaya akan dihitung otomatis berdasarkan work order 
                        (jasa servis + sparepart yang digunakan).
                    </div>

                    <hr>
                    <div class="d-flex gap-2">
                        <button type="submit" class="btn btn-danger" id="submitBtn">
                            <i class="fas fa-save me-1"></i> Simpan Pembayaran
                        </button>
                        <a href="<?= base_url('pembayaran') ?>" class="btn btn-secondary">
                            <i class="fas fa-arrow-left me-1"></i> Kembali
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div class="col-md-4">
        <div class="card bg-light">
            <div class="card-body">
                <h6 class="mb-3"><strong><i class="fas fa-lightbulb me-2"></i>Panduan</strong></h6>
                <ol class="small mb-0">
                    <li class="mb-2">Pilih servis yang sudah selesai dari dropdown</li>
                    <li class="mb-2">Pilih metode pembayaran (Cash/Transfer/QRIS)</li>
                    <li class="mb-2">Atur tanggal pembayaran</li>
                    <li class="mb-2">Klik "Simpan Pembayaran"</li>
                    <li class="mb-0">Invoice akan otomatis dibuat dan bisa dicetak</li>
                </ol>
            </div>
        </div>

        <div class="card mt-3 border-danger">
            <div class="card-body text-center">
                <i class="fas fa-file-invoice fa-3x text-danger mb-3"></i>
                <h6><strong>Invoice Otomatis</strong></h6>
                <p class="small text-muted mb-0">
                    Nomor invoice akan di-generate otomatis dengan format: 
                    <br><code>INV-YYYYMMDD-XXXX</code>
                </p>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
$(document).ready(function() {
    // Show info when service selected
    $('#selectServis').on('change', function() {
        var selected = $(this).find(':selected');
        if (selected.val()) {
            $('#infoNomor').text(selected.data('nomor'));
            $('#infoPelanggan').text(selected.data('pelanggan'));
            $('#infoKendaraan').text(selected.data('kendaraan'));
            $('#infoServis').slideDown();
        } else {
            $('#infoServis').slideUp();
        }
    });

    // Trigger if pre-selected
    if ($('#selectServis').val()) {
        $('#selectServis').trigger('change');
    }

    // Validasi form sebelum submit
    $('#paymentForm').on('submit', function(e) {
        e.preventDefault();
        
        // Reset error states
        $('.form-select, .form-control').removeClass('is-invalid');
        $('.invalid-feedback').text('');
        
        let isValid = true;
        let errorMessages = [];
        
        // Validasi Pilihan Servis
        const servisId = $('#selectServis').val();
        if (!servisId) {
            $('#selectServis').addClass('is-invalid');
            $('#servisError').text('Pilih servis yang akan dibayar');
            isValid = false;
            errorMessages.push('• Pilih servis yang akan dibayar');
        }
        
        // Validasi Metode Pembayaran
        const metode = $('#metodePembayaran').val();
        if (!metode) {
            $('#metodePembayaran').addClass('is-invalid');
            $('#metodeError').text('Pilih metode pembayaran');
            isValid = false;
            errorMessages.push('• Pilih metode pembayaran');
        }
        
        // Validasi Tanggal
        const tanggal = $('#tanggalBayar').val();
        if (!tanggal) {
            $('#tanggalBayar').addClass('is-invalid');
            $('#tanggalError').text('Masukkan tanggal pembayaran');
            isValid = false;
            errorMessages.push('• Masukkan tanggal pembayaran');
        }
        
        if (!isValid) {
            // Tampilkan semua error dalam SweetAlert
            Swal.fire({
                icon: 'error',
                title: 'Form Belum Lengkap',
                html: '<strong>Harap lengkapi data berikut:</strong><br>' + errorMessages.join('<br>'),
                confirmButtonColor: '#d33',
                confirmButtonText: 'Mengerti'
            });
            return;
        }
        
        // Tampilkan konfirmasi sebelum submit
        const servisText = $('#selectServis').find(':selected').text();
        const metodeText = $('#metodePembayaran').find(':selected').text();
        
        Swal.fire({
            title: 'Konfirmasi Pembayaran',
            html: `
                <div class="text-start">
                    <p><strong>Apakah data berikut sudah benar?</strong></p>
                    <div class="mb-2">
                        <strong>Servis:</strong><br>
                        <span class="text-muted">${servisText}</span>
                    </div>
                    <div class="mb-2">
                        <strong>Metode Pembayaran:</strong><br>
                        <span class="text-muted">${metodeText}</span>
                    </div>
                    <div>
                        <strong>Tanggal:</strong><br>
                        <span class="text-muted">${tanggal}</span>
                    </div>
                </div>
            `,
            icon: 'question',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            cancelButtonColor: '#6c757d',
            confirmButtonText: 'Ya, Simpan Pembayaran',
            cancelButtonText: 'Periksa Kembali',
            reverseButtons: true,
            customClass: {
                confirmButton: 'btn btn-danger',
                cancelButton: 'btn btn-secondary'
            },
            buttonsStyling: false
        }).then((result) => {
            if (result.isConfirmed) {
                // Show loading state
                const submitBtn = $('#submitBtn');
                const originalText = submitBtn.html();
                submitBtn.prop('disabled', true).html('<i class="fas fa-spinner fa-spin me-1"></i> Menyimpan...');
                
                // Submit form secara programmatic
                const form = $('#paymentForm')[0];
                const formData = new FormData(form);
                
                
                // langsung submit form
                setTimeout(() => {
                    form.submit();
                }, 500);
            }
        });
    });
});
</script>

<?= $this->include('layout/footer') ?>