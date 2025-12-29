<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register - Bengkel Mobil</title>
    
    <!-- Bootstrap & Icon -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <!-- SweetAlert -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    
    <style>
        :root {
            --primary-red: #dc3545;
            --dark-red: #a71d2a;
            --light-red: #ff6b7a;
            --white: #ffffff;
            --black: #000000;
            --gray-light: #f8f9fa;
            --gray-dark: #343a40;
        }
        
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            background: linear-gradient(135deg, var(--primary-red) 0%, var(--dark-red) 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            font-family: 'Segoe UI', system-ui, sans-serif;
            padding: 20px;
        }
        
        .register-wrapper {
            max-width: 1200px;
            width: 100%;
            background: var(--white);
            border-radius: 20px;
            overflow: hidden;
            box-shadow: 0 25px 75px rgba(0, 0, 0, 0.2);
            transition: transform 0.3s ease;
        }
        
        .register-wrapper:hover {
            transform: translateY(-5px);
        }
        
        .register-grid {
            display: grid;
            grid-template-columns: 1fr 1.2fr;
            min-height: 700px;
        }
        
        .register-info {
            background: linear-gradient(135deg, var(--black) 0%, var(--gray-dark) 100%);
            color: var(--white);
            padding: 60px 40px;
            display: flex;
            flex-direction: column;
            justify-content: center;
            position: relative;
            overflow: hidden;
        }
        
        .register-info::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: linear-gradient(45deg, transparent 30%, rgba(255,255,255,0.05) 50%, transparent 70%);
            background-size: 200% 200%;
            animation: shine 3s infinite linear;
        }
        
        @keyframes shine {
            0% { background-position: -200% -200%; }
            100% { background-position: 200% 200%; }
        }
        
        .logo-section {
            text-align: center;
            margin-bottom: 60px;
            position: relative;
            z-index: 1;
        }
        
        .logo-section i {
            font-size: 5rem;
            margin-bottom: 20px;
            color: var(--light-red);
        }
        
        .logo-section h3 {
            font-weight: 800;
            letter-spacing: 2px;
            margin-bottom: 5px;
            color: var(--white);
        }
        
        .benefit-item {
            display: flex;
            align-items: flex-start;
            margin-bottom: 25px;
            padding: 20px;
            background: rgba(255, 255, 255, 0.1);
            border-radius: 12px;
            transition: all 0.3s ease;
            position: relative;
            z-index: 1;
            border: 1px solid rgba(255, 255, 255, 0.1);
        }
        
        .benefit-item:hover {
            background: rgba(255, 255, 255, 0.15);
            transform: translateX(5px);
            border-color: var(--light-red);
        }
        
        .benefit-icon {
            font-size: 1.8rem;
            color: var(--light-red);
            margin-right: 20px;
            min-width: 40px;
            text-align: center;
        }
        
        .register-form {
            padding: 60px 40px;
            overflow-y: auto;
            background: var(--white);
        }
        
        .form-section {
            margin-bottom: 40px;
        }
        
        .form-section-title {
            color: var(--dark-red);
            font-weight: 700;
            margin-bottom: 25px;
            padding-bottom: 15px;
            border-bottom: 2px solid #f0f0f0;
            display: flex;
            align-items: center;
            font-size: 1.1rem;
        }
        
        .form-row {
            margin-bottom: 25px;
        }
        
        .input-with-icon {
            position: relative;
        }
        
        .input-with-icon i {
            position: absolute;
            left: 15px;
            top: 50%;
            transform: translateY(-50%);
            color: #6c757d;
            z-index: 2;
        }
        
        .input-with-icon input {
            padding-left: 45px;
            border-radius: 10px;
            border: 2px solid #e9ecef;
            height: 52px;
            transition: all 0.3s ease;
        }
        
        .input-with-icon input:focus {
            border-color: var(--primary-red);
            box-shadow: 0 0 0 3px rgba(220, 53, 69, 0.2);
        }
        
        .error-message {
            color: var(--primary-red);
            font-size: 0.875rem;
            margin-top: 5px;
            padding-left: 10px;
            border-left: 3px solid var(--primary-red);
            display: none;
        }
        
        .is-invalid {
            border-color: var(--primary-red) !important;
        }
        
        .is-valid {
            border-color: #28a745 !important;
        }
        
        .btn-outline-light {
            border: 2px solid rgba(255, 255, 255, 0.3);
            color: var(--white);
            border-radius: 10px;
            padding: 12px;
            transition: all 0.3s ease;
            position: relative;
            z-index: 1;
        }
        
        .btn-outline-light:hover {
            background: rgba(255, 255, 255, 0.1);
            border-color: var(--light-red);
            color: var(--light-red);
            transform: translateY(-2px);
        }
        
        .btn-danger {
            background: linear-gradient(135deg, var(--primary-red) 0%, var(--dark-red) 100%);
            border: none;
            border-radius: 10px;
            padding: 18px;
            font-weight: 600;
            font-size: 1.1rem;
            transition: all 0.3s ease;
        }
        
        .btn-danger:hover {
            background: linear-gradient(135deg, var(--dark-red) 0%, var(--primary-red) 100%);
            transform: translateY(-3px);
            box-shadow: 0 10px 25px rgba(220, 53, 69, 0.3);
        }
        
        .alert-info {
            background: linear-gradient(135deg, #e8f4fd 0%, #d4e9fa 100%);
            border-left: 4px solid #0d6efd;
            border-radius: 10px;
        }
        
        /* Responsive Design */
        @media (max-width: 992px) {
            .register-grid {
                grid-template-columns: 1fr;
                min-height: auto;
            }
            
            .register-info, .register-form {
                padding: 40px 30px;
            }
            
            .logo-section i {
                font-size: 4rem;
            }
        }
        
        @media (max-width: 768px) {
            .register-info, .register-form {
                padding: 30px 20px;
            }
            
            .benefit-item {
                padding: 15px;
            }
            
            .benefit-icon {
                font-size: 1.5rem;
                margin-right: 15px;
            }
        }
        
        @media (max-width: 576px) {
            .form-row .col-md-6 {
                margin-bottom: 20px;
            }
            
            .btn-danger {
                padding: 15px;
                font-size: 1rem;
            }
            
            .register-wrapper {
                margin: 10px;
            }
        }
    </style>
</head>
<body>
    <div class="register-wrapper">
        <div class="register-grid">
            <!-- Kolom Kiri: Informasi -->
            <div class="register-info">
                <div class="logo-section">
                    <i class="fas fa-car"></i>
                    <h3>BENGKEL MOBIL</h3>
                    <p class="mb-0">Management System</p>
                </div>
                
                <h4 class="mb-4">Keuntungan Bergabung</h4>
                
                <div class="benefit-item">
                    <div class="benefit-icon">
                        <i class="fas fa-clock"></i>
                    </div>
                    <div>
                        <h6 class="mb-1">Servis Cepat</h6>
                        <p class="mb-0 small">Proses servis yang efisien dan tepat waktu</p>
                    </div>
                </div>
                
                <div class="benefit-item">
                    <div class="benefit-icon">
                        <i class="fas fa-chart-line"></i>
                    </div>
                    <div>
                        <h6 class="mb-1">Pelacakan Real-time</h6>
                        <p class="mb-0 small">Pantau status servis kapan saja</p>
                    </div>
                </div>
                
                <div class="benefit-item">
                    <div class="benefit-icon">
                        <i class="fas fa-history"></i>
                    </div>
                    <div>
                        <h6 class="mb-1">Riwayat Lengkap</h6>
                        <p class="mb-0 small">Arsip riwayat servis kendaraan Anda</p>
                    </div>
                </div>
                
                <div class="benefit-item">
                    <div class="benefit-icon">
                        <i class="fas fa-bell"></i>
                    </div>
                    <div>
                        <h6 class="mb-1">Notifikasi</h6>
                        <p class="mb-0 small">Update status servis & reminder</p>
                    </div>
                </div>
                
                <div class="mt-4 pt-4 border-top border-secondary" style="position: relative; z-index: 1;">
                    <p class="small mb-3">Sudah memiliki akun?</p>
                    <a href="<?= base_url('auth/login') ?>" class="btn btn-outline-light w-100">
                        <i class="fas fa-sign-in-alt me-2"></i> Masuk ke Akun
                    </a>
                </div>
            </div>
            
            <!-- Kolom Kanan: Form -->
            <div class="register-form">
                <h4 class="mb-4 text-dark">Buat Akun Baru</h4>
                
                <!-- SweetAlert akan ditampilkan via JavaScript -->
                
                <form action="<?= base_url('auth/register') ?>" method="post" id="registerForm">
                    <?= csrf_field() ?>
                    
                    <!-- Section 1: Informasi Dasar -->
                    <div class="form-section">
                        <h6 class="form-section-title">
                            <i class="fas fa-user-circle me-2"></i>Informasi Dasar
                        </h6>
                        
                        <div class="row form-row">
                            <div class="col-md-6">
                                <div class="input-with-icon mb-3">
                                    <i class="fas fa-user"></i>
                                    <input type="text" class="form-control" name="username" id="username"
                                           placeholder="Masukkan username (min. 3 karakter)"
                                           value="<?= old('username') ?>" required>
                                </div>
                                <div class="error-message" id="usernameError"></div>
                            </div>
                            <div class="col-md-6">
                                <div class="input-with-icon mb-3">
                                    <i class="fas fa-id-card"></i>
                                    <input type="text" class="form-control" name="full_name" id="full_name"
                                           placeholder="Masukkan nama lengkap Anda"
                                           value="<?= old('full_name') ?>" required>
                                </div>
                                <div class="error-message" id="fullnameError"></div>
                            </div>
                        </div>
                        
                        <div class="row form-row">
                            <div class="col-12">
                                <div class="input-with-icon mb-3">
                                    <i class="fas fa-envelope"></i>
                                    <input type="email" class="form-control" name="email" id="email"
                                           placeholder="contoh@email.com"
                                           value="<?= old('email') ?>" required>
                                </div>
                                <div class="error-message" id="emailError"></div>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Section 2: Keamanan -->
                    <div class="form-section">
                        <h6 class="form-section-title">
                            <i class="fas fa-shield-alt me-2"></i>Keamanan Akun
                        </h6>
                        
                        <div class="row form-row">
                            <div class="col-md-6">
                                <div class="input-with-icon mb-3">
                                    <i class="fas fa-lock"></i>
                                    <input type="password" class="form-control" name="password" id="password"
                                           placeholder="Minimal 6 karakter" required >
                                    <button class="btn btn-outline-secondary" type="button" id="togglePassword" 
                                            style="position: absolute; right: 50px; top: 0; height: 100%; border: none; background: transparent;">
                                        <i class="fas fa-eye"></i>
                                    </button>
                                </div>
                                <div class="error-message" id="passwordError"></div>
                            </div>
                            <div class="col-md-6">
                                <div class="input-with-icon mb-3">
                                    <i class="fas fa-check-circle"></i>
                                    <input type="password" class="form-control" name="password_confirm" id="password_confirm"
                                           placeholder="Ketik ulang password" required>
                                </div>
                                <div class="error-message" id="confirmError"></div>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Section 3: Pemulihan Akun -->
                    <div class="form-section">
                        <h6 class="form-section-title">
                            <i class="fas fa-key me-2"></i>Verifikasi Cepat
                        </h6>
                        
                        <div class="alert alert-info">
                            <small>
                                <i class="fas fa-info-circle me-2"></i>
                                Jawaban ini akan digunakan untuk mereset password jika Anda lupa.
                            </small>
                        </div>
                        
                        <div class="row form-row">
                            <div class="col-12">
                                <div class="input-with-icon mb-3">
                                    <i class="fas fa-music"></i>
                                    <input type="text" class="form-control" name="security_answer" id="security_answer"
                                           placeholder="Contoh: Taylor Swift, Tulus, dll"
                                           value="<?= old('security_answer') ?>" required>
                                </div>
                                <div class="error-message" id="securityError"></div>
                                <div class="form-text">Jawaban tidak case-sensitive</div>
                            </div>
                        </div>
                    </div>
                    
                    <button type="submit" class="btn btn-danger w-100 py-3 mt-4" id="submitBtn">
                        <i class="fas fa-user-plus me-2"></i> Daftar Sekarang
                    </button>
                    
                    <div class="text-center mt-3">
                        <small class="text-muted">
                            Dengan mendaftar, Anda menyetujui <a href="#" class="text-decoration-none text-danger">Syarat & Ketentuan</a>
                        </small>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
    document.addEventListener('DOMContentLoaded', function() {
        // Tampilkan SweetAlert jika ada notifikasi dari server
        <?php if (session()->getFlashdata('success')): ?>
            Swal.fire({
                icon: 'success',
                title: 'Registrasi Berhasil!',
                text: '<?= addslashes(session()->getFlashdata('success')) ?>',
                confirmButtonColor: '#28a745',
                timer: 4000,
                timerProgressBar: true
            }).then(() => {
                window.location.href = '<?= base_url('auth/login') ?>';
            });
        <?php endif; ?>
        
        <?php if (session()->getFlashdata('error')): ?>
            Swal.fire({
                icon: 'error',
                title: 'Registrasi Gagal',
                text: '<?= addslashes(session()->getFlashdata('error')) ?>',
                confirmButtonColor: '#dc3545'
            });
        <?php endif; ?>
        
        <?php if (session()->getFlashdata('errors')): ?>
            const errors = <?= json_encode(session()->getFlashdata('errors')) ?>;
            let errorMessages = [];
            
            // Tampilkan error di form
            Object.keys(errors).forEach(field => {
                const errorDiv = document.getElementById(field + 'Error');
                const input = document.getElementById(field);
                
                if (errorDiv) {
                    errorDiv.textContent = errors[field];
                    errorDiv.style.display = 'block';
                }
                
                if (input) {
                    input.classList.add('is-invalid');
                }
                
                errorMessages.push(errors[field]);
            });
            
            // Tampilkan SweetAlert dengan semua error
            if (errorMessages.length > 0) {
                Swal.fire({
                    icon: 'error',
                    title: 'Validasi Gagal',
                    html: '<div class="text-start">' + 
                        errorMessages.map(msg => `<div class="mb-2"><i class="fas fa-times me-2"></i>${msg}</div>`).join('') + 
                        '</div>',
                    confirmButtonColor: '#dc3545'
                });
            }
        <?php endif; ?>

        // Toggle password visibility
        const togglePasswordBtn = document.getElementById('togglePassword');
        if (togglePasswordBtn) {
            togglePasswordBtn.addEventListener('click', function() {
                const password = document.getElementById('password');
                if (password) {
                    const icon = this.querySelector('i');
                    if (password.type === 'password') {
                        password.type = 'text';
                        icon.classList.remove('fa-eye');
                        icon.classList.add('fa-eye-slash');
                    } else {
                        password.type = 'password';
                        icon.classList.remove('fa-eye-slash');
                        icon.classList.add('fa-eye');
                    }
                }
            });
        }

        // Form submit handler
        const form = document.getElementById('registerForm');
        if (form) {
            form.addEventListener('submit', function(e) {
                // Validasi sederhana sebelum submit
                const password = document.getElementById('password');
                const confirm = document.getElementById('password_confirm');
                
                if (password && confirm && password.value !== confirm.value) {
                    e.preventDefault();
                    Swal.fire({
                        icon: 'error',
                        title: 'Password Tidak Cocok',
                        text: 'Password dan konfirmasi password harus sama',
                        confirmButtonColor: '#dc3545'
                    });
                    return false;
                }
                
                // Tampilkan loading state
                const submitBtn = document.getElementById('submitBtn');
                if (submitBtn) {
                    submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i>Memproses...';
                    submitBtn.disabled = true;
                }
                
                // Biarkan form submit normal
                return true;
            });
        }

        // Validasi real-time sederhana
        function setupRealTimeValidation(inputId, errorId, validationFn) {
            const input = document.getElementById(inputId);
            const errorDiv = document.getElementById(errorId);
            
            if (input && errorDiv) {
                input.addEventListener('blur', function() {
                    const result = validationFn(this.value);
                    if (result.isValid) {
                        this.classList.remove('is-invalid');
                        this.classList.add('is-valid');
                        errorDiv.style.display = 'none';
                    } else {
                        this.classList.remove('is-valid');
                        this.classList.add('is-invalid');
                        errorDiv.textContent = result.message;
                        errorDiv.style.display = 'block';
                    }
                });
                
                input.addEventListener('input', function() {
                    this.classList.remove('is-invalid', 'is-valid');
                    errorDiv.style.display = 'none';
                });
            }
        }

        // Setup validasi untuk setiap field
        setupRealTimeValidation('username', 'usernameError', function(value) {
            if (!value.trim()) {
                return { isValid: false, message: 'Username wajib diisi' };
            }
            if (value.trim().length < 3) {
                return { isValid: false, message: 'Username minimal 3 karakter' };
            }
            if (!/^[a-zA-Z0-9_]+$/.test(value)) {
                return { isValid: false, message: 'Hanya huruf, angka, underscore' };
            }
            return { isValid: true, message: '' };
        });

        setupRealTimeValidation('full_name', 'fullnameError', function(value) {
            if (!value.trim()) {
                return { isValid: false, message: 'Nama lengkap wajib diisi' };
            }
            if (value.trim().length < 3) {
                return { isValid: false, message: 'Nama minimal 3 karakter' };
            }
            return { isValid: true, message: '' };
        });

        setupRealTimeValidation('email', 'emailError', function(value) {
            const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
            if (!value.trim()) {
                return { isValid: false, message: 'Email wajib diisi' };
            }
            if (!emailRegex.test(value)) {
                return { isValid: false, message: 'Format email tidak valid' };
            }
            return { isValid: true, message: '' };
        });

        setupRealTimeValidation('password', 'passwordError', function(value) {
            if (!value) {
                return { isValid: false, message: 'Password wajib diisi' };
            }
            if (value.length < 6) {
                return { isValid: false, message: 'Password minimal 6 karakter' };
            }
            return { isValid: true, message: '' };
        });

        setupRealTimeValidation('security_answer', 'securityError', function(value) {
            if (!value.trim()) {
                return { isValid: false, message: 'Jawaban keamanan wajib diisi' };
            }
            if (value.trim().length < 2) {
                return { isValid: false, message: 'Jawaban minimal 2 karakter' };
            }
            return { isValid: true, message: '' };
        });

        // Validasi khusus untuk konfirmasi password
        const confirmInput = document.getElementById('password_confirm');
        const confirmError = document.getElementById('confirmError');
        
        if (confirmInput && confirmError) {
            confirmInput.addEventListener('blur', function() {
                const password = document.getElementById('password');
                if (password && this.value !== password.value) {
                    this.classList.remove('is-valid');
                    this.classList.add('is-invalid');
                    confirmError.textContent = 'Password tidak cocok';
                    confirmError.style.display = 'block';
                } else if (this.value) {
                    this.classList.remove('is-invalid');
                    this.classList.add('is-valid');
                    confirmError.style.display = 'none';
                }
            });
            
            confirmInput.addEventListener('input', function() {
                this.classList.remove('is-invalid', 'is-valid');
                confirmError.style.display = 'none';
            });
        }
    });
    </script>
</body>
</html>