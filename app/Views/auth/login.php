<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Bengkel Mobil</title>

    <!-- Bootstrap & Icon -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
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
            position: relative;
            z-index: 1;
        }
        
        body::before {
            content: '';
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: linear-gradient(135deg, rgba(0,0,0,0.1) 0%, rgba(0,0,0,0.05) 100%);
            z-index: -1;
        }
        
        .login-container {
            max-width: 450px;
            width: 100%;
            position: relative;
            z-index: 2;
        }
        
        .login-card {
            background: var(--white);
            border-radius: 20px;
            box-shadow: 0 20px 50px rgba(0, 0, 0, 0.2);
            overflow: hidden;
            transition: transform 0.3s ease;
        }
        
        .login-card:hover {
            transform: translateY(-5px);
        }
        
        .login-header {
            background: linear-gradient(135deg, var(--black) 0%, var(--gray-dark) 100%);
            color: var(--white);
            padding: 50px 30px;
            text-align: center;
        }
        
        .login-header i {
            font-size: 4rem;
            margin-bottom: 15px;
            color: var(--light-red);
        }
        
        .login-header h3 {
            font-weight: 800;
            letter-spacing: 1px;
            margin-bottom: 5px;
        }
        
        .login-body {
            padding: 40px;
        }
        
        .form-control {
            border-radius: 10px;
            border: 2px solid #e9ecef;
            padding: 15px;
            font-size: 1rem;
            transition: all 0.3s ease;
        }
        
        .form-control:focus {
            border-color: var(--primary-red);
            box-shadow: 0 0 0 3px rgba(220, 53, 69, 0.2);
        }
        
        .input-group-text {
            background: var(--white);
            border: 2px solid #e9ecef;
            border-right: none;
        }
        
        .input-group .form-control {
            border-left: none;
        }
        
        .input-group .btn-outline-secondary {
            border: 2px solid #e9ecef;
            border-left: none;
        }
        
        .form-label {
            font-weight: 600;
            color: var(--gray-dark);
            margin-bottom: 10px;
        }
        
        .btn-login {
            background: linear-gradient(135deg, var(--primary-red) 0%, var(--dark-red) 100%);
            border: none;
            border-radius: 10px;
            padding: 15px;
            font-weight: 600;
            font-size: 1.1rem;
            color: var(--white);
            transition: all 0.3s ease;
            position: relative;
            overflow: hidden;
        }
        
        .btn-login:hover {
            background: linear-gradient(135deg, var(--dark-red) 0%, var(--primary-red) 100%);
            transform: translateY(-2px);
            box-shadow: 0 8px 20px rgba(220, 53, 69, 0.3);
        }
        
        .btn-outline-secondary {
            color: var(--gray-dark);
            transition: all 0.3s ease;
        }
        
        .btn-outline-secondary:hover {
            background: var(--gray-light);
            color: var(--primary-red);
        }
        
        .error-message {
            font-size: 0.875rem;
            margin-top: 5px;
            padding-left: 10px;
            border-left: 3px solid var(--primary-red);
        }
        
        .is-invalid {
            border-color: var(--primary-red) !important;
        }
        
        .is-valid {
            border-color: #28a745 !important;
        }
        
        .loading {
            position: relative;
            pointer-events: none;
        }
        
        .loading::after {
            content: '';
            position: absolute;
            top: 50%;
            left: 50%;
            width: 20px;
            height: 20px;
            margin: -10px 0 0 -10px;
            border: 2px solid rgba(255, 255, 255, 0.3);
            border-top-color: white;
            border-radius: 50%;
            animation: spin 0.8s linear infinite;
        }
        
        @keyframes spin {
            to { transform: rotate(360deg); }
        }
        
        .login-links {
            margin-top: 25px;
            text-align: center;
        }
        
        .login-links a {
            color: var(--primary-red);
            text-decoration: none;
            font-weight: 600;
            transition: color 0.3s ease;
            padding: 0 10px;
        }
        
        .login-links a:hover {
            color: var(--dark-red);
            text-decoration: underline;
        }
        
        .separator {
            color: var(--gray-dark);
            opacity: 0.5;
        }
        
        .footer-text {
            text-align: center;
            margin-top: 30px;
            color: rgba(255, 255, 255, 0.9);
            font-size: 0.9rem;
        }
        
        /* Fix untuk SweetAlert agar tidak overlap */
        .swal2-container {
            z-index: 999999 !important;
        }
        
        /* Responsive Design */
        @media (max-width: 576px) {
            .login-body {
                padding: 30px 20px;
            }
            
            .login-header {
                padding: 40px 20px;
            }
            
            .login-header i {
                font-size: 3rem;
            }
            
            .login-header h3 {
                font-size: 1.5rem;
            }
            
            .login-links a {
                display: block;
                padding: 5px 0;
            }
            
            .separator {
                display: none;
            }
        }
        
        @media (max-width: 400px) {
            .login-body {
                padding: 25px 15px;
            }
            
            .btn-login {
                padding: 12px;
                font-size: 1rem;
            }
            
            .form-control {
                padding: 12px;
            }
        }
    </style>
</head>
<body>
    <div class="login-container">
        <div class="login-card">
            <div class="login-header">
                <i class="fas fa-car"></i>
                <h3>BENGKEL MOBIL</h3>
                <small>Management System</small>
            </div>

            <div class="login-body">
                <h5 class="text-center mb-4">Login ke Akun Anda</h5>

                <!-- FORM LOGIN -->
                <form action="<?= base_url('auth/login') ?>" method="post" id="loginForm" novalidate>
                    <?= csrf_field() ?>

                    <div class="mb-4">
                        <label class="form-label">Email <span class="text-danger">*</span></label>
                        <div class="input-group">
                            <span class="input-group-text"><i class="fas fa-envelope"></i></span>
                            <input 
                                type="email" 
                                name="email" 
                                id="email"
                                class="form-control" 
                                placeholder="nama@email.com"
                                value="<?= old('email', '') ?>" 
                                required
                            >
                        </div>
                        <div class="error-message text-danger" id="emailError"></div>
                    </div>

                    <div class="mb-5">
                        <label class="form-label">Password <span class="text-danger">*</span></label>
                        <div class="input-group">
                            <span class="input-group-text"><i class="fas fa-lock"></i></span>
                            <input 
                                type="password" 
                                name="password" 
                                id="password"
                                class="form-control" 
                                placeholder="••••••••"
                                required
                            >
                            <button class="btn btn-outline-secondary" type="button" id="togglePassword">
                                <i class="fas fa-eye"></i>
                            </button>
                        </div>
                        <div class="error-message text-danger" id="passwordError"></div>
                    </div>

                    <button type="submit" class="btn btn-login w-100 mb-4" id="submitBtn">
                        <i class="fas fa-sign-in-alt me-2"></i> Login
                    </button>

                    <div class="login-links">
                        <!-- PERBAIKAN: Link yang benar -->
                        <a href="<?= base_url('auth/forgot-password') ?>">Lupa Password?</a>
                        <span class="separator">|</span>
                        <a href="<?= base_url('auth/register') ?>">Daftar Akun Baru</a>
                    </div>
                </form>
            </div>
        </div>

        <p class="footer-text">
            &copy; <?= date('Y') ?> Bengkel Mobil Management System
        </p>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const loginForm = document.getElementById('loginForm');
            const emailInput = document.getElementById('email');
            const passwordInput = document.getElementById('password');
            const submitBtn = document.getElementById('submitBtn');
            
            // Tampilkan SweetAlert jika ada flashdata swal
            <?php if (session()->getFlashdata('swal')): ?>
                const swalData = <?= json_encode(session()->getFlashdata('swal')) ?>;
                Swal.fire({
                    icon: swalData.icon,
                    title: swalData.title,
                    text: swalData.text,
                    confirmButtonColor: '#dc3545',
                    timer: 3000,
                    timerProgressBar: true,
                    showConfirmButton: false
                });
            <?php endif; ?>

            // Tampilkan error validation jika ada
            <?php if (session()->getFlashdata('errors')): ?>
                const errors = <?= json_encode(session()->getFlashdata('errors')) ?>;
                
                if (errors.email) {
                    emailInput.classList.add('is-invalid');
                    document.getElementById('emailError').textContent = errors.email;
                }
                
                if (errors.password) {
                    passwordInput.classList.add('is-invalid');
                    document.getElementById('passwordError').textContent = errors.password;
                }
                
                // Tampilkan alert untuk validasi error
                if (errors.email || errors.password) {
                    Swal.fire({
                        icon: 'error',
                        title: 'Validasi Error',
                        html: '<ul class="text-start">' + 
                              (errors.email ? '<li>' + errors.email + '</li>' : '') +
                              (errors.password ? '<li>' + errors.password + '</li>' : '') +
                              '</ul>',
                        confirmButtonColor: '#dc3545'
                    });
                }
            <?php endif; ?>

            // Toggle password visibility
            const togglePassword = document.getElementById('togglePassword');
            if (togglePassword && passwordInput) {
                togglePassword.addEventListener('click', function() {
                    const type = passwordInput.getAttribute('type') === 'password' ? 'text' : 'password';
                    passwordInput.setAttribute('type', type);
                    this.innerHTML = type === 'password' ? 
                        '<i class="fas fa-eye"></i>' : 
                        '<i class="fas fa-eye-slash"></i>';
                });
            }

            // Validasi real-time untuk email
            emailInput.addEventListener('blur', function() {
                validateEmail();
            });

            emailInput.addEventListener('input', function() {
                if (this.classList.contains('is-invalid')) {
                    this.classList.remove('is-invalid');
                    document.getElementById('emailError').textContent = '';
                }
            });

            // Validasi real-time untuk password
            passwordInput.addEventListener('blur', function() {
                validatePassword();
            });

            passwordInput.addEventListener('input', function() {
                if (this.classList.contains('is-invalid')) {
                    this.classList.remove('is-invalid');
                    document.getElementById('passwordError').textContent = '';
                }
            });

            // Validasi form sebelum submit dengan AJAX
            loginForm.addEventListener('submit', async function(e) {
                e.preventDefault();
                
                if (!validateForm()) {
                    return false;
                }
                
                // Show loading state
                submitBtn.classList.add('loading');
                submitBtn.disabled = true;
                
                // Collect form data
                const formData = new FormData(this);
                
                try {
                    const response = await fetch('<?= base_url('auth/login') ?>', {
                        method: 'POST',
                        body: formData,
                        headers: {
                            'X-Requested-With': 'XMLHttpRequest'
                        }
                    });
                    
                    const data = await response.json();
                    
                    if (response.ok && data.success) {
                        // Login berhasil
                        Swal.fire({
                            icon: 'success',
                            title: 'Login Berhasil!',
                            text: data.message,
                            confirmButtonColor: '#28a745',
                            showConfirmButton: false,
                            timer: 1500
                        }).then(() => {
                            window.location.href = '<?= base_url('dashboard') ?>';
                        });
                    } else {
                        // Login gagal
                        Swal.fire({
                            icon: 'error',
                            title: data.title || 'Login Gagal',
                            text: data.message,
                            confirmButtonColor: '#dc3545'
                        });
                        
                        // Tampilkan error di form
                        if (data.errors) {
                            if (data.errors.email) {
                                emailInput.classList.add('is-invalid');
                                document.getElementById('emailError').textContent = data.errors.email;
                            }
                            if (data.errors.password) {
                                passwordInput.classList.add('is-invalid');
                                document.getElementById('passwordError').textContent = data.errors.password;
                            }
                        }
                    }
                } catch (error) {
                    console.error('AJAX failed:', error);
                    // Traditional form submit fallback
                    this.submit();
                } finally {
                    // Reset loading state
                    submitBtn.classList.remove('loading');
                    submitBtn.disabled = false;
                }
            });

            function validateEmail() {
                const email = emailInput.value.trim();
                const emailError = document.getElementById('emailError');
                
                if (!email) {
                    emailInput.classList.add('is-invalid');
                    emailError.textContent = 'Email wajib diisi';
                    return false;
                }
                
                const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
                if (!emailRegex.test(email)) {
                    emailInput.classList.add('is-invalid');
                    emailError.textContent = 'Format email tidak valid';
                    return false;
                }
                
                emailInput.classList.remove('is-invalid');
                emailInput.classList.add('is-valid');
                emailError.textContent = '';
                return true;
            }

            function validatePassword() {
                const password = passwordInput.value;
                const passwordError = document.getElementById('passwordError');
                
                if (!password) {
                    passwordInput.classList.add('is-invalid');
                    passwordError.textContent = 'Password wajib diisi';
                    return false;
                }
                
                if (password.length < 6) {
                    passwordInput.classList.add('is-invalid');
                    passwordError.textContent = 'Password minimal 6 karakter';
                    return false;
                }
                
                passwordInput.classList.remove('is-invalid');
                passwordInput.classList.add('is-valid');
                passwordError.textContent = '';
                return true;
            }

            function validateForm() {
                const isEmailValid = validateEmail();
                const isPasswordValid = validatePassword();
                
                if (!isEmailValid || !isPasswordValid) {
                    // Tampilkan error summary
                    let errorMessages = [];
                    if (!isEmailValid) errorMessages.push(document.getElementById('emailError').textContent);
                    if (!isPasswordValid) errorMessages.push(document.getElementById('passwordError').textContent);
                    
                    Swal.fire({
                        icon: 'error',
                        title: 'Form Tidak Valid',
                        html: '<ul class="text-start">' + 
                              errorMessages.map(msg => `<li>${msg}</li>`).join('') +
                              '</ul>',
                        confirmButtonColor: '#dc3545'
                    });
                    
                    return false;
                }
                
                return true;
            }

            // Auto-focus ke email field
            emailInput.focus();
        });
    </script>
</body>
</html>