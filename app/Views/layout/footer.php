<!-- SweetAlert JS -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<!-- jQuery -->
<script src="https://code.jquery.com/jquery-3.7.0.min.js"></script>

<!-- Bootstrap 5 JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

<!-- DataTables JS -->
<script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.13.6/js/dataTables.bootstrap5.min.js"></script>

<!-- Chart.js -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<!-- Custom Scripts -->
<script>
    $(document).ready(function() {
        // Initialize DataTable
        if ($.fn.DataTable) {
            $('.datatable').DataTable({
                "language": {
                    "url": "//cdn.datatables.net/plug-ins/1.13.6/i18n/id.json"
                },
                "pageLength": 10,
                "ordering": true,
                "searching": true,
                "lengthChange": true,
                "info": true,
                "autoWidth": false,
                "responsive": true
            });
        }

        // Auto hide alerts after 5 seconds
        setTimeout(function() {
            $('.alert').fadeOut('slow');
        }, 5000);

        // Confirm delete dengan SweetAlert
        $('.btn-delete').on('click', function(e) {
            e.preventDefault();
            const deleteUrl = $(this).attr('href');
            const itemName = $(this).data('name') || 'data ini';
            
            Swal.fire({
                title: 'Konfirmasi Hapus',
                text: `Apakah Anda yakin ingin menghapus ${itemName}?`,
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#dc3545',
                cancelButtonColor: '#6c757d',
                confirmButtonText: 'Ya, Hapus!',
                cancelButtonText: 'Batal'
            }).then((result) => {
                if (result.isConfirmed) {
                    window.location.href = deleteUrl;
                }
            });
        });

        // Mobile sidebar toggle with overlay
        $('#sidebarToggle').on('click', function(e) {
            e.stopPropagation();
            $('#sidebar').toggleClass('active');
            $('#sidebarOverlay').toggleClass('active');
            $(this).find('i').toggleClass('fa-bars fa-times');
        });

        // Close sidebar when clicking overlay
        $('#sidebarOverlay').on('click', function() {
            $('#sidebar').removeClass('active');
            $(this).removeClass('active');
            $('#sidebarToggle').find('i').removeClass('fa-times').addClass('fa-bars');
        });

        // Close sidebar when clicking outside on mobile
        $(document).on('click', function(e) {
            if ($(window).width() <= 768) {
                if (!$(e.target).closest('#sidebar, #sidebarToggle').length) {
                    $('#sidebar').removeClass('active');
                    $('#sidebarOverlay').removeClass('active');
                    $('#sidebarToggle').find('i').removeClass('fa-times').addClass('fa-bars');
                }
            }
        });

        // Prevent sidebar close when clicking inside sidebar
        $('#sidebar').on('click', function(e) {
            e.stopPropagation();
        });

        // Format currency inputs
        $('.currency-input').on('keyup', function() {
            let value = $(this).val().replace(/[^0-9]/g, '');
            $(this).val(value);
        });

        // Format number with thousand separator
        function formatNumber(num) {
            return num.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ".");
        }

        // Preview image before upload
        $('.image-input').on('change', function() {
            const file = this.files[0];
            if (file) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    $('.image-preview').attr('src', e.target.result).show();
                }
                reader.readAsDataURL(file);
            }
        });

        // Pelanggan change event untuk load kendaraan
        if ($('#pelanggan_id').length > 0) {
            $('#pelanggan_id').on('change', function() {
                const pelangganId = $(this).val();
                if (pelangganId) {
                    $.ajax({
                        url: '<?= base_url("penerimaan-servis/getKendaraanByPelanggan/") ?>' + pelangganId,
                        type: 'GET',
                        dataType: 'json',
                        success: function(data) {
                            let options = '<option value="">-- Pilih Kendaraan --</option>';
                            $.each(data, function(index, kendaraan) {
                                options += '<option value="' + kendaraan.id + '">' + 
                                        kendaraan.nomor_plat + ' - ' + 
                                        kendaraan.merk + ' ' + 
                                        kendaraan.tipe + '</option>';
                            });
                            $('#kendaraan_id').html(options);
                        },
                        error: function(jqXHR, textStatus, errorThrown) {
                            console.error('Error:', textStatus, errorThrown);
                            $('#kendaraan_id').html('<option value="">-- Error memuat data --</option>');
                        }
                    });
                } else {
                    $('#kendaraan_id').html('<option value="">-- Pilih Kendaraan --</option>');
                }
            });
        }

        // Dynamic form for adding items (sparepart/jasa)
        let itemIndex = 0;
        
        $('.add-item').on('click', function() {
            itemIndex++;
            const itemType = $(this).data('type'); // 'sparepart' or 'jasa'
            const containerClass = itemType === 'sparepart' ? '.sparepart-container' : '.jasa-container';
            
            let html = '<div class="row mb-2 item-row">';
            html += '<div class="col-md-5 col-12 mb-2 mb-md-0">';
            html += '<select class="form-select form-select-sm item-select" name="' + itemType + '_id[]" required>';
            html += '<option value="">-- Pilih ' + (itemType === 'sparepart' ? 'Sparepart' : 'Jasa') + ' --</option>';
            
            if (itemType === 'sparepart') {
                <?php if (isset($sparepart) && is_array($sparepart)): ?>
                <?php foreach ($sparepart as $sp): ?>
                html += '<option value="<?= esc($sp['id'] ?? '') ?>" data-harga="<?= esc($sp['harga_jual'] ?? 0) ?>"><?= esc($sp['nama_barang'] ?? '') ?> - Stok: <?= esc($sp['stok'] ?? 0) ?></option>';
                <?php endforeach; ?>
                <?php endif; ?>
            } else {
                <?php if (isset($jasa_servis) && is_array($jasa_servis)): ?>
                <?php foreach ($jasa_servis as $js): ?>
                html += '<option value="<?= esc($js['id'] ?? '') ?>" data-harga="<?= esc($js['harga_jasa'] ?? 0) ?>"><?= esc($js['nama_layanan'] ?? '') ?> - Rp <?= number_format($js['harga_jasa'] ?? 0, 0, ',', '.') ?></option>';
                <?php endforeach; ?>
                <?php endif; ?>
            }
            
            html += '</select>';
            html += '</div>';
            
            if (itemType === 'sparepart') {
                html += '<div class="col-md-2 col-6 mb-2 mb-md-0">';
                html += '<input type="number" class="form-control form-control-sm jumlah-input" name="jumlah_' + itemType + '[]" placeholder="Jumlah" min="1" required>';
                html += '</div>';
                html += '<div class="col-md-3 col-6 mb-2 mb-md-0">';
                html += '<input type="text" class="form-control form-control-sm harga-display" placeholder="Harga" readonly>';
                html += '</div>';
            } else {
                html += '<div class="col-md-5 col-6 mb-2 mb-md-0">';
                html += '<input type="text" class="form-control form-control-sm harga-display" placeholder="Harga" readonly>';
                html += '</div>';
            }
            
            html += '<div class="col-md-2 col-12">';
            html += '<button type="button" class="btn btn-danger btn-sm w-100 remove-item"><i class="fas fa-trash"></i> Hapus</button>';
            html += '</div>';
            html += '</div>';
            
            $(containerClass).append(html);
        });

        // Remove item
        $(document).on('click', '.remove-item', function() {
            $(this).closest('.item-row').remove();
            calculateTotal();
        });

        // Update harga display when item selected
        $(document).on('change', '.item-select', function() {
            const harga = $(this).find(':selected').data('harga');
            const row = $(this).closest('.item-row');
            row.find('.harga-display').val(harga ? 'Rp ' + formatNumber(harga) : '');
            calculateTotal();
        });

        // Calculate total when jumlah changed
        $(document).on('keyup', '.jumlah-input', function() {
            calculateTotal();
        });

        // Calculate total biaya
        function calculateTotal() {
            let total = 0;
            
            // Calculate sparepart total
            $('.sparepart-container .item-row').each(function() {
                const harga = $(this).find('.item-select option:selected').data('harga') || 0;
                const jumlah = $(this).find('.jumlah-input').val() || 0;
                total += (harga * jumlah);
            });
            
            // Calculate jasa total
            $('.jasa-container .item-row').each(function() {
                const harga = $(this).find('.item-select option:selected').data('harga') || 0;
                total += parseFloat(harga);
            });
            
            $('#total_biaya').val('Rp ' + formatNumber(total));
            $('#total_biaya_hidden').val(total);
        }

        // Print invoice
        $('.btn-print').on('click', function() {
            window.print();
        });

        // Smooth scroll to top
        $('.scroll-top').on('click', function() {
            $('html, body').animate({scrollTop: 0}, 'slow');
        });
        <?php if (session()->getFlashdata('success')): ?>
            Swal.fire({
                icon: 'success',
                title: 'Berhasil!',
                text: '<?= addslashes(session()->getFlashdata('success')) ?>',
                confirmButtonColor: '#28a745',
                timer: 3000,
                timerProgressBar: true
            });
        <?php endif; ?>
        
        <?php if (session()->getFlashdata('error')): ?>
            Swal.fire({
                icon: 'error',
                title: 'Gagal!',
                text: '<?= addslashes(session()->getFlashdata('error')) ?>',
                confirmButtonColor: '#dc3545',
                timer: 3000,
                timerProgressBar: true
            });
        <?php endif; ?>
    });


    // Format Rupiah
    function formatRupiah(angka) {
        const number_string = angka.toString().replace(/[^,\d]/g, '');
        const split = number_string.split(',');
        const sisa = split[0].length % 3;
        let rupiah = split[0].substr(0, sisa);
        const ribuan = split[0].substr(sisa).match(/\d{3}/gi);

        if (ribuan) {
            const separator = sisa ? '.' : '';
            rupiah += separator + ribuan.join('.');
        }

        rupiah = split[1] != undefined ? rupiah + ',' + split[1] : rupiah;
        return 'Rp ' + rupiah;
    }
    
</script>
<?php if (session()->getFlashdata('success')) : ?>
<script>
Swal.fire({
    icon: 'success',
    title: 'Berhasil',
    text: '<?= session()->getFlashdata('success') ?>',
    timer: 2000,
    showConfirmButton: false
});
</script>
<?php endif; ?>

<?php if (session()->getFlashdata('errors')) : ?>
<script>
Swal.fire({
    icon: 'error',
    title: 'Validasi Gagal',
    html: `
        <ul style="text-align:left">
            <?php foreach (session()->getFlashdata('errors') as $error): ?>
                <li><?= esc($error) ?></li>
            <?php endforeach; ?>
        </ul>
    `
});
</script>
<?php endif; ?>

</body>
</html>