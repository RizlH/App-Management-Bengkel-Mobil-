<?php
// Pastikan file ini tidak ada tag PHP di atas sebelum DOCTYPE
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reset Password - Bengkel Mobil</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
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
        
        .reset-container {
            width: 100%;
            max-width: 500px;
        }
        
        .reset-card {
            background: var(--white);
            border-radius: 20px;
            box-shadow: 0 20px 50px rgba(0, 0, 0, 0.2);
            overflow: hidden;
            transition: transform 0.3s ease;
        }
        
        .reset-card:hover {
            transform: translateY(-5px);
        }
        
        .reset-header {
            background: linear-gradient(135deg, var(--black) 0%, var(--gray-dark) 100%);
            color: var(--white);
            padding: 50px 30px;
            text-align: center;
        }
        
        .reset-header i {
            font-size: 4rem;
            margin-bottom: 25px;
            color: var(--light-red);
        }
        
        .reset-header h4 {
            font-weight: 700;
            margin-bottom: 15px;
            color: var(--white);
        }
        
        .reset-body {
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
        
        .input-group .form-control {
            border-right: none;
        }
        
        .input-group .btn-outline-secondary {
            border: 2px solid #e9ecef;
            border-left: none;
        }
        
        .input-group .input-group-text {
            background: var(--white);
            border: 2px solid #e9ecef;
            border-left: none;
        }
        
        .form-label {
            font-weight: 600;
            color: var(--gray-dark);
            margin-bottom: 12px;
            display: flex;
            align-items: center;
        }
        
        .btn-primary {
            background: linear-gradient(135deg, var(--primary-red) 0%, var(--dark-red) 100%);
            border: none;
            border-radius: 10px;
            padding: 18px;
            font-weight: 600;
            font-size: 1.1rem;
            transition: all 0.3s ease;
        }
        
        .btn-primary:hover {
            background: linear-gradient(135deg, var(--dark-red) 0%, var(--primary-red) 100%);
            transform: translateY(-3px);
            box-shadow: 0 10px 25px rgba(220, 53, 69, 0.3);
        }
        
        .btn-outline-secondary {
            color: var(--gray-dark);
            transition: all 0.3s ease;
        }
        
        .btn-outline-secondary:hover {
            background: var(--gray-light);
            color: var(--primary-red);
        }
        
        .password-strength {
            height: 6px;
            border-radius: 3px;
            margin-top: 8px;
            background: #e9ecef;
            overflow: hidden;
            transition: all 0.3s ease;
        }
        
        .strength-fill {
            height: 100%;
            border-radius: 3px;
            transition: all 0.3s ease;
        }
        
        .strength-weak { 
            width: 33%; 
            background: linear-gradient(90deg, var(--primary-red), #ff6b6b);
        }
        
        .strength-medium { 
            width: 66%; 
            background: linear-gradient(90deg, #ffa502, #ffb142);
        }
        
        .strength-strong { 
            width: 100%; 
            background: linear-gradient(90deg, #20bf6b, #26de81);
        }
        
        .match-icon {
            display: flex;
            align-items: center;
            justify-content: center;
            width: 40px;
            opacity: 0;
            transition: all 0.3s ease;
            transform: scale(0.8);
        }
        
        .match-valid {
            color: #20bf6b;
            opacity: 1;
            transform: scale(1);
        }
        
        .match-invalid {
            color: var(--primary-red);
            opacity: 1;
            transform: scale(1);
        }
        
        .alert {
            border-radius: 10px;
            border: none;
            margin-bottom: 25px;
        }
        
        .alert-success {
            background: linear-gradient(135deg, #d4edda 0%, #c3e6cb 100%);
            border-left: 4px solid #28a745;
        }
        
        .alert-danger {
            background: linear-gradient(135deg, #f8d7da 0%, #f5c6cb 100%);
            border-left: 4px solid var(--primary-red);
        }
        
        .alert-info {
            background: linear-gradient(135deg, #e8f4fd 0%, #d4e9fa 100%);
            border-left: 4px solid #0d6efd;
            border-radius: 10px;
        }
        
        /* Responsive Design */
        @media (max-width: 576px) {
            .reset-body {
                padding: 30px 20px;
            }
            
            .reset-header {
                padding: 40px 20px;
            }
            
            .reset-header i {
                font-size: 3rem;
            }
        }
        
        @media (max-width: 400px) {
            .reset-body {
                padding: 25px 15px;
            }
            
            .btn-primary {
                padding: 15px;
                font-size: 1rem;
            }
        }
    </style>
</head>
<body>
    <div class="reset-container">
        <div class="reset-card">
            <div class="reset-header">
                <i class="fas fa-check-circle"></i>
                <h4>Verifikasi Berhasil!</h4>
                <p class="mb-0 small">Buat password baru untuk akun Anda</p>
            </div>
            <div class="reset-body">
                <?php if (session()->getFlashdata('success')): ?>
                    <div class="alert alert-success alert-dismissible fade show">
                        <i class="fas fa-check-circle me-2"></i>
                        <?= session()->getFlashdata('success') ?>
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                <?php endif; ?>

                <?php if (session()->getFlashdata('errors')): ?>
                    <div class="alert alert-danger">
                        <?php foreach (session()->getFlashdata('errors') as $error): ?>
                            <div><i class="fas fa-exclamation-triangle me-2"></i><?= esc($error) ?></div>
                        <?php endforeach; ?>
                    </div>
                <?php endif; ?>

                <form action="<?= base_url('auth/reset-password') ?>" method="post" id="resetForm">
                    <?= csrf_field() ?>
                    <input type="hidden" name="token" value="<?= $token ?>">
                    
                    <div class="mb-4">
                        <label class="form-label">
                            <i class="fas fa-key me-2"></i>Password Baru
                        </label>
                        <div class="input-group">
                            <input type="password" class="form-control" name="password" 
                                   id="password" placeholder="Minimal 6 karakter" required>
                            <button class="btn btn-outline-secondary" type="button" id="togglePassword">
                                <i class="fas fa-eye"></i>
                            </button>
                        </div>
                        <div class="password-strength" id="strengthBar">
                            <div class="strength-fill" id="strengthFill"></div>
                        </div>
                        <div class="d-flex justify-content-between align-items-center mt-2">
                            <small class="text-muted" id="strengthText">Kekuatan password</small>
                            <small class="text-muted" id="strengthScore">0/4</small>
                        </div>
                    </div>
                    
                    <div class="mb-4">
                        <label class="form-label">
                            <i class="fas fa-check-circle me-2"></i>Konfirmasi Password
                        </label>
                        <div class="input-group">
                            <input type="password" class="form-control" name="password_confirm" 
                                   id="password_confirm" placeholder="Ketik ulang password" required>
                            <span class="input-group-text match-icon" id="matchIcon">
                                <i class="fas fa-check"></i>
                            </span>
                        </div>
                        <small class="text-danger d-none" id="matchError">
                            <i class="fas fa-times-circle me-1"></i>Password tidak cocok
                        </small>
                        <small class="text-success d-none" id="matchSuccess">
                            <i class="fas fa-check-circle me-1"></i>Password cocok
                        </small>
                    </div>

                    <div class="alert alert-info mb-4">
                        <small>
                            <strong><i class="fas fa-shield-alt me-2"></i>Tips password yang kuat:</strong>
                            <ul class="mb-0 mt-2">
                                <li>Minimal 6 karakter</li>
                                <li>Kombinasi huruf besar dan kecil</li>
                                <li>Tambahkan angka dan simbol</li>
                            </ul>
                        </small>
                    </div>
                    
                    <button type="submit" class="btn btn-primary w-100" id="submitBtn">
                        <i class="fas fa-check me-2"></i> Reset Password
                    </button>
                </form>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Toggle password visibility
        document.getElementById('togglePassword').addEventListener('click', function() {
            const password = document.getElementById('password');
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
        });

        // Password strength checker
        document.getElementById('password').addEventListener('keyup', function() {
            const password = this.value;
            const strengthFill = document.getElementById('strengthFill');
            const strengthText = document.getElementById('strengthText');
            const strengthScore = document.getElementById('strengthScore');
            
            let score = 0;
            const checks = [
                password.length >= 6,
                /[a-z]/.test(password) && /[A-Z]/.test(password),
                /[0-9]/.test(password),
                /[^a-zA-Z0-9]/.test(password)
            ];
            
            score = checks.filter(Boolean).length;
            strengthScore.textContent = `${score}/4`;
            
            strengthFill.className = 'strength-fill';
            
            if (password.length === 0) {
                strengthFill.style.width = '0';
                strengthText.textContent = 'Masukkan password';
                strengthText.style.color = '#6c757d';
            } else if (score <= 1) {
                strengthFill.classList.add('strength-weak');
                strengthText.textContent = 'Password lemah';
                strengthText.style.color = '#dc3545';
            } else if (score <= 2) {
                strengthFill.classList.add('strength-medium');
                strengthText.textContent = 'Password sedang';
                strengthText.style.color = '#ffa502';
            } else {
                strengthFill.classList.add('strength-strong');
                strengthText.textContent = 'Password kuat';
                strengthText.style.color = '#20bf6b';
            }
        });

        // Password match checker
        document.getElementById('password_confirm').addEventListener('keyup', function() {
            const password = document.getElementById('password').value;
            const confirm = this.value;
            const matchError = document.getElementById('matchError');
            const matchSuccess = document.getElementById('matchSuccess');
            const matchIcon = document.getElementById('matchIcon');
            const submitBtn = document.getElementById('submitBtn');
            
            if (!confirm) {
                matchError.classList.add('d-none');
                matchSuccess.classList.add('d-none');
                matchIcon.classList.remove('match-valid', 'match-invalid');
                this.classList.remove('is-invalid', 'is-valid');
                submitBtn.disabled = true;
                return;
            }
            
            if (password !== confirm) {
                matchError.classList.remove('d-none');
                matchSuccess.classList.add('d-none');
                matchIcon.className = 'input-group-text match-icon match-invalid';
                matchIcon.innerHTML = '<i class="fas fa-times"></i>';
                this.classList.add('is-invalid');
                this.classList.remove('is-valid');
                submitBtn.disabled = true;
            } else {
                matchError.classList.add('d-none');
                matchSuccess.classList.remove('d-none');
                matchIcon.className = 'input-group-text match-icon match-valid';
                matchIcon.innerHTML = '<i class="fas fa-check"></i>';
                this.classList.remove('is-invalid');
                this.classList.add('is-valid');
                submitBtn.disabled = false;
            }
        });

        // Form validation before submit
        document.getElementById('resetForm').addEventListener('submit', function(e) {
            const password = document.getElementById('password').value;
            const confirm = document.getElementById('password_confirm').value;
            
            if (password !== confirm) {
                e.preventDefault();
                alert('Password dan konfirmasi password tidak cocok!');
                return false;
            }
            
            if (password.length < 6) {
                e.preventDefault();
                alert('Password minimal 6 karakter!');
                return false;
            }
        });
    </script>
</body>
</html>