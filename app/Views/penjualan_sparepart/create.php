<?= $this->include('layout/header') ?>

<div class="card">
    <div class="card-header">
        <i class="fas fa-cash-register me-2"></i> Tambah Penjualan Sparepart
    </div>
    <div class="card-body">
        <form action="<?= base_url('penjualan-sparepart/store') ?>" method="post" id="formPenjualan">
            <?= csrf_field() ?>
            
            <!-- Info Penjualan -->
            <div class="row">
                <div class="col-md-6">
                    <div class="mb-3">
                        <label class="form-label">Nama Pembeli <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" name="nama_pembeli" 
                               placeholder="Contoh: Budi Santoso" required>
                        <small class="text-muted">Nama customer/pembeli sparepart</small>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="mb-3">
                        <label class="form-label">Tanggal Penjualan <span class="text-danger">*</span></label>
                        <input type="date" class="form-control" name="tanggal_penjualan" 
                               value="<?= date('Y-m-d') ?>" required>
                    </div>
                </div>
            </div>

            <!-- Daftar Sparepart -->
            <hr>
            <div class="d-flex justify-content-between align-items-center mb-3">
                <h5 class="mb-0"><i class="fas fa-shopping-cart me-2"></i> Daftar Sparepart</h5>
                <button type="button" class="btn btn-success btn-sm" id="btnAddSparepart">
                    <i class="fas fa-plus me-1"></i> Tambah Item
                </button>
            </div>

            <div class="table-responsive">
                <table class="table table-bordered" id="tableSparepartContainer">
                    <thead class="table-dark">
                        <tr>
                            <th width="35%">Sparepart</th>
                            <th width="12%">Stok</th>
                            <th width="12%">Jumlah</th>
                            <th width="18%">Harga Jual (Rp)</th>
                            <th width="18%">Subtotal (Rp)</th>
                            <th width="5%" class="text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody id="sparepartContainer">
                        <tr class="sparepart-item">
                            <td>
                                <select class="form-select form-select-sm sparepart-select" name="sparepart_id[]" required>
                                    <option value="">-- Pilih Sparepart --</option>
                                    <?php if (!empty($sparepart)): ?>
                                        <?php foreach ($sparepart as $s): ?>
                                            <option value="<?= esc($s['id']) ?>" 
                                                    data-stok="<?= esc($s['stok']) ?>"
                                                    data-harga="<?= esc($s['harga_jual']) ?>">
                                                <?= esc($s['nama_barang']) ?> (<?= esc($s['satuan']) ?>)
                                            </option>
                                        <?php endforeach; ?>
                                    <?php endif; ?>
                                </select>
                            </td>
                            <td>
                                <input type="text" class="form-control form-control-sm stok-display text-center" 
                                       readonly placeholder="0">
                            </td>
                            <td>
                                <input type="number" class="form-control form-control-sm jumlah-input" 
                                       name="jumlah[]" min="1" value="1" required>
                            </td>
                            <td>
                                <input type="number" class="form-control form-control-sm harga-jual-input" 
                                       name="harga_jual[]" min="0" step="100" placeholder="0" required>
                            </td>
                            <td>
                                <input type="text" class="form-control form-control-sm subtotal-display" 
                                       readonly placeholder="Rp 0">
                            </td>
                            <td class="text-center">
                                <button type="button" class="btn btn-danger btn-sm btn-remove-sparepart">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </td>
                        </tr>
                    </tbody>
                    <tfoot class="table-light">
                        <tr>
                            <td colspan="4" class="text-end"><strong>TOTAL PENJUALAN:</strong></td>
                            <td colspan="2">
                                <input type="text" class="form-control form-control-sm fw-bold text-danger" 
                                       id="totalPenjualanDisplay" readonly value="Rp 0">
                                <input type="hidden" name="total_penjualan" id="totalPenjualanHidden" value="0">
                            </td>
                        </tr>
                    </tfoot>
                </table>
            </div>

            <div class="alert alert-info mt-3">
                <i class="fas fa-info-circle me-2"></i>
                <strong>Catatan Penjualan:</strong>
                <ul class="mb-0 mt-2">
                    <li>Stok sparepart akan <strong>otomatis berkurang</strong> setelah penjualan disimpan</li>
                    <li>Pastikan jumlah tidak melebihi stok yang tersedia</li>
                    <li>Harga jual dapat disesuaikan sesuai kesepakatan dengan pembeli</li>
                </ul>
            </div>

            <hr>
            <div class="d-flex gap-2">
                <button type="submit" class="btn btn-danger" id="btnSubmit">
                    <i class="fas fa-save me-1"></i> Simpan Penjualan
                </button>
                <a href="<?= base_url('penjualan-sparepart') ?>" class="btn btn-secondary">
                    <i class="fas fa-arrow-left me-1"></i> Kembali
                </a>
            </div>
        </form>
    </div>
</div>

<!-- Include SweetAlert CSS & JS -->
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Format rupiah
    function formatRupiah(angka) {
        return 'Rp ' + parseInt(angka).toLocaleString('id-ID');
    }
    
    // Hitung subtotal per row
    function calculateSubtotal(row) {
        const jumlah = parseInt(row.querySelector('.jumlah-input').value) || 0;
        const harga = parseInt(row.querySelector('.harga-jual-input').value) || 0;
        const subtotal = jumlah * harga;
        
        row.querySelector('.subtotal-display').value = formatRupiah(subtotal);
        calculateTotal();
    }
    
    // Hitung total penjualan
    function calculateTotal() {
        let total = 0;
        document.querySelectorAll('.sparepart-item').forEach(row => {
            const jumlah = parseInt(row.querySelector('.jumlah-input').value) || 0;
            const harga = parseInt(row.querySelector('.harga-jual-input').value) || 0;
            total += jumlah * harga;
        });
        
        document.getElementById('totalPenjualanDisplay').value = formatRupiah(total);
        document.getElementById('totalPenjualanHidden').value = total;
    }
    
    // Update tombol remove
    function updateRemoveButtons() {
        const count = document.querySelectorAll('.sparepart-item').length;
        document.querySelectorAll('.btn-remove-sparepart').forEach(btn => {
            btn.disabled = count <= 1;
        });
    }
    
    // Event: Pilih sparepart
    document.addEventListener('change', function(e) {
        if (e.target.classList.contains('sparepart-select')) {
            const row = e.target.closest('.sparepart-item');
            const selectedOption = e.target.options[e.target.selectedIndex];
            const stok = parseInt(selectedOption.getAttribute('data-stok')) || 0;
            const harga = parseInt(selectedOption.getAttribute('data-harga')) || 0;
            
            // Update stok display
            row.querySelector('.stok-display').value = stok;
            
            // Auto-fill harga jual
            row.querySelector('.harga-jual-input').value = harga;
            
            // Reset jumlah ke 1
            row.querySelector('.jumlah-input').value = 1;
            
            // Hitung subtotal
            calculateSubtotal(row);
        }
    });
    
    // Event: Input jumlah
    document.addEventListener('input', function(e) {
        if (e.target.classList.contains('jumlah-input')) {
            const row = e.target.closest('.sparepart-item');
            const select = row.querySelector('.sparepart-select');
            const selectedOption = select.options[select.selectedIndex];
            const stok = parseInt(selectedOption.getAttribute('data-stok')) || 0;
            const jumlah = parseInt(e.target.value) || 0;
            
            // Validasi stok dengan SweetAlert
            if (jumlah > stok && stok > 0) {
                Swal.fire({
                    icon: 'warning',
                    title: 'Stok Tidak Cukup!',
                    html: `<p>Jumlah melebihi stok tersedia!</p>
                          <p><strong>Stok tersedia:</strong> ${stok}</p>`,
                    confirmButtonText: 'OK',
                    confirmButtonColor: '#dc3545'
                });
                e.target.value = stok;
            }
            
            calculateSubtotal(row);
        }
        
        if (e.target.classList.contains('harga-jual-input')) {
            const row = e.target.closest('.sparepart-item');
            calculateSubtotal(row);
        }
    });
    
    // Event: Tambah sparepart
    document.getElementById('btnAddSparepart').addEventListener('click', function() {
        const container = document.getElementById('sparepartContainer');
        const newRow = document.createElement('tr');
        newRow.className = 'sparepart-item';
        newRow.innerHTML = `
            <td>
                <select class="form-select form-select-sm sparepart-select" name="sparepart_id[]" required>
                    <option value="">-- Pilih Sparepart --</option>
                    <?php if (!empty($sparepart)): ?>
                        <?php foreach ($sparepart as $s): ?>
                            <option value="<?= esc($s['id']) ?>" 
                                    data-stok="<?= esc($s['stok']) ?>"
                                    data-harga="<?= esc($s['harga_jual']) ?>">
                                <?= esc($s['nama_barang']) ?> (<?= esc($s['satuan']) ?>)
                            </option>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </select>
            </td>
            <td>
                <input type="text" class="form-control form-control-sm stok-display text-center" 
                       readonly placeholder="0">
            </td>
            <td>
                <input type="number" class="form-control form-control-sm jumlah-input" 
                       name="jumlah[]" min="1" value="1" required>
            </td>
            <td>
                <input type="number" class="form-control form-control-sm harga-jual-input" 
                       name="harga_jual[]" min="0" step="100" placeholder="0" required>
            </td>
            <td>
                <input type="text" class="form-control form-control-sm subtotal-display" 
                       readonly placeholder="Rp 0">
            </td>
            <td class="text-center">
                <button type="button" class="btn btn-danger btn-sm btn-remove-sparepart">
                    <i class="fas fa-trash"></i>
                </button>
            </td>
        `;
        
        container.appendChild(newRow);
        updateRemoveButtons();
    });
    
    // Event: Remove sparepart
    document.addEventListener('click', function(e) {
        if (e.target.classList.contains('btn-remove-sparepart') || 
            e.target.closest('.btn-remove-sparepart')) {
            const btn = e.target.classList.contains('btn-remove-sparepart') ? 
                       e.target : e.target.closest('.btn-remove-sparepart');
            const row = btn.closest('.sparepart-item');
            
            if (document.querySelectorAll('.sparepart-item').length > 1) {
                Swal.fire({
                    title: 'Hapus Item?',
                    text: "Apakah Anda yakin ingin menghapus item ini?",
                    icon: 'question',
                    showCancelButton: true,
                    confirmButtonColor: '#dc3545',
                    cancelButtonColor: '#6c757d',
                    confirmButtonText: 'Ya, Hapus!',
                    cancelButtonText: 'Batal'
                }).then((result) => {
                    if (result.isConfirmed) {
                        row.remove();
                        updateRemoveButtons();
                        calculateTotal();
                        
                        Swal.fire({
                            icon: 'success',
                            title: 'Dihapus!',
                            text: 'Item berhasil dihapus',
                            showConfirmButton: false,
                            timer: 1500
                        });
                    }
                });
            } else {
                Swal.fire({
                    icon: 'warning',
                    title: 'Tidak Dapat Menghapus',
                    text: 'Minimal harus ada 1 sparepart dalam penjualan',
                    confirmButtonText: 'OK',
                    confirmButtonColor: '#dc3545'
                });
            }
        }
    });
    
    // Fungsi validasi form dengan SweetAlert
    function validateForm() {
        // Cek nama pembeli
        const namaPembeli = document.querySelector('input[name="nama_pembeli"]').value.trim();
        if (!namaPembeli) {
            Swal.fire({
                icon: 'error',
                title: 'Nama Pembeli Kosong',
                text: 'Silakan isi nama pembeli terlebih dahulu',
                confirmButtonText: 'OK',
                confirmButtonColor: '#dc3545'
            }).then(() => {
                document.querySelector('input[name="nama_pembeli"]').focus();
            });
            return false;
        }

        // Cek minimal ada 1 sparepart yang dipilih
        let hasSparepartSelected = false;
        let sparepartErrors = [];
        
        document.querySelectorAll('.sparepart-item').forEach((row, index) => {
            const sparepartSelect = row.querySelector('.sparepart-select');
            const sparepartId = sparepartSelect.value;
            const jumlah = parseInt(row.querySelector('.jumlah-input').value) || 0;
            const harga = parseInt(row.querySelector('.harga-jual-input').value) || 0;
            const selectedOption = sparepartSelect.options[sparepartSelect.selectedIndex];
            const stok = selectedOption ? parseInt(selectedOption.getAttribute('data-stok')) || 0 : 0;
            const namaSparepart = selectedOption ? selectedOption.textContent.split('(')[0].trim() : '';

            if (sparepartId) {
                hasSparepartSelected = true;

                if (jumlah <= 0) {
                    sparepartErrors.push(`Jumlah untuk ${namaSparepart || 'sparepart'} harus lebih dari 0!`);
                }
                
                if (harga <= 0) {
                    sparepartErrors.push(`Harga jual untuk ${namaSparepart || 'sparepart'} harus lebih dari 0!`);
                }
                
                if (jumlah > stok) {
                    sparepartErrors.push(`Jumlah ${namaSparepart || 'sparepart'} melebihi stok tersedia! Stok: ${stok}`);
                }
            }
        });

        if (!hasSparepartSelected) {
            Swal.fire({
                icon: 'error',
                title: 'Sparepart Belum Dipilih',
                text: 'Silakan pilih minimal 1 sparepart untuk penjualan',
                confirmButtonText: 'OK',
                confirmButtonColor: '#dc3545'
            });
            return false;
        }

        // Tampilkan error jika ada
        if (sparepartErrors.length > 0) {
            Swal.fire({
                icon: 'error',
                title: 'Validasi Gagal',
                html: '<div class="text-start"><p><strong>Terdapat kesalahan:</strong></p>' + 
                      sparepartErrors.map(error => `<p class="mb-1">• ${error}</p>`).join('') + '</div>',
                confirmButtonText: 'OK',
                confirmButtonColor: '#dc3545'
            });
            return false;
        }

        // Cek total penjualan
        const totalPenjualan = parseInt(document.getElementById('totalPenjualanHidden').value) || 0;
        if (totalPenjualan <= 0) {
            Swal.fire({
                icon: 'error',
                title: 'Total Penjualan Tidak Valid',
                text: 'Total penjualan harus lebih dari Rp 0',
                confirmButtonText: 'OK',
                confirmButtonColor: '#dc3545'
            });
            return false;
        }

        return true;
    }

    // Event: Submit form
    document.getElementById('formPenjualan').addEventListener('submit', function(e) {
        e.preventDefault();
        
        // Validasi form
        if (!validateForm()) {
            return false;
        }
        
        // Konfirmasi dengan SweetAlert
        const totalPenjualan = formatRupiah(document.getElementById('totalPenjualanHidden').value);
        const totalItems = document.querySelectorAll('.sparepart-item').length;
        let totalQty = 0;
        document.querySelectorAll('.jumlah-input').forEach(input => {
            totalQty += parseInt(input.value) || 0;
        });
        
        Swal.fire({
            title: 'Simpan Penjualan?',
            html: `<div class="text-start">
                    <p><strong>Detail Penjualan:</strong></p>
                    <p>• Jumlah Item: ${totalItems}</p>
                    <p>• Total Quantity: ${totalQty}</p>
                    <p>• Total Penjualan: <strong class="text-success">${totalPenjualan}</strong></p>
                    <hr>
                    <p class="text-warning"><i class="fas fa-exclamation-triangle"></i> Stok akan otomatis berkurang setelah disimpan!</p>
                   </div>`,
            icon: 'question',
            showCancelButton: true,
            confirmButtonColor: '#28a745',
            cancelButtonColor: '#dc3545',
            confirmButtonText: '<i class="fas fa-save me-1"></i> Simpan',
            cancelButtonText: '<i class="fas fa-times me-1"></i> Batal',
            reverseButtons: true
        }).then((result) => {
            if (result.isConfirmed) {
                // Tampilkan loading
                Swal.fire({
                    title: 'Menyimpan...',
                    text: 'Sedang menyimpan data penjualan',
                    allowOutsideClick: false,
                    didOpen: () => {
                        Swal.showLoading();
                    }
                });
                
                // Submit form
                setTimeout(() => {
                    e.target.submit();
                }, 500);
            }
        });
    });
    
    // Event: Validasi real-time saat input
    document.querySelectorAll('input[name="nama_pembeli"], input[name="tanggal_penjualan"]').forEach(input => {
        input.addEventListener('blur', function() {
            if (this.value.trim() === '') {
                this.classList.add('is-invalid');
            } else {
                this.classList.remove('is-invalid');
            }
        });
    });
    
    // Initial setup
    updateRemoveButtons();
    calculateTotal();
    
    // Tambahkan CSS untuk validasi
    const style = document.createElement('style');
    style.textContent = `
        .is-invalid {
            border-color: #dc3545 !important;
        }
        .swal2-popup {
            font-size: 1rem !important;
        }
    `;
    document.head.appendChild(style);
});
</script>

<?= $this->include('layout/footer') ?>