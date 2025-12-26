<?php
// Pastikan file ini tidak ada tag PHP di atas sebelum DOCTYPE
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Lupa Password - Bengkel Mobil</title>
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
        
        .forgot-container {
            width: 100%;
            max-width: 480px;
        }
        
        .forgot-card {
            background: var(--white);
            border-radius: 20px;
            box-shadow: 0 15px 40px rgba(0, 0, 0, 0.2);
            overflow: hidden;
            transition: transform 0.3s ease;
        }
        
        .forgot-card:hover {
            transform: translateY(-5px);
        }
        
        .forgot-header {
            background: linear-gradient(135deg, var(--black) 0%, var(--gray-dark) 100%);
            color: var(--white);
            padding: 40px 30px;
            text-align: center;
        }
        
        .forgot-header i {
            font-size: 3.5rem;
            margin-bottom: 20px;
            color: var(--light-red);
        }
        
        .forgot-header h4 {
            font-weight: 700;
            margin-bottom: 10px;
            color: var(--white);
        }
        
        .forgot-body {
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
        
        .form-label {
            font-weight: 600;
            color: var(--gray-dark);
            margin-bottom: 10px;
            display: flex;
            align-items: center;
        }
        
        .btn-primary {
            background: linear-gradient(135deg, var(--primary-red) 0%, var(--dark-red) 100%);
            border: none;
            border-radius: 10px;
            padding: 15px;
            font-weight: 600;
            font-size: 1.1rem;
            transition: all 0.3s ease;
        }
        
        .btn-primary:hover {
            background: linear-gradient(135deg, var(--dark-red) 0%, var(--primary-red) 100%);
            transform: translateY(-2px);
            box-shadow: 0 8px 20px rgba(220, 53, 69, 0.3);
        }
        
        .info-box {
            background: linear-gradient(135deg, #ffeaea 0%, #ffd6d6 100%);
            border-left: 4px solid var(--primary-red);
            padding: 20px;
            border-radius: 10px;
            margin-bottom: 30px;
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
        
        .link-back {
            color: var(--primary-red);
            text-decoration: none;
            font-weight: 600;
            transition: color 0.3s ease;
            display: inline-flex;
            align-items: center;
        }
        
        .link-back:hover {
            color: var(--dark-red);
            text-decoration: underline;
        }
        
        hr {
            border-color: #e9ecef;
            opacity: 0.5;
        }
        
        /* Responsive Design */
        @media (max-width: 576px) {
            .forgot-body {
                padding: 30px 20px;
            }
            
            .forgot-header {
                padding: 30px 20px;
            }
            
            .forgot-header i {
                font-size: 2.5rem;
            }
        }
        
        @media (max-width: 400px) {
            .forgot-body {
                padding: 25px 15px;
            }
            
            .btn-primary {
                padding: 12px;
                font-size: 1rem;
            }
        }
    </style>
</head>
<body>
    <div class="forgot-container">
        <div class="forgot-card">
            <div class="forgot-header">
                <i class="fas fa-key"></i>
                <h4>Lupa Password?</h4>
                <p class="mb-0 small">Jawab pertanyaan keamanan untuk mereset password</p>
            </div>
            
            <div class="forgot-body">
                <!-- Flash Messages -->
                <?php if (session()->getFlashdata('success')): ?>
                    <div class="alert alert-success alert-dismissible fade show">
                        <i class="fas fa-check-circle me-2"></i>
                        <?= session()->getFlashdata('success') ?>
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                <?php endif; ?>

                <?php if (session()->getFlashdata('error')): ?>
                    <div class="alert alert-danger alert-dismissible fade show">
                        <i class="fas fa-exclamation-circle me-2"></i>
                        <?= session()->getFlashdata('error') ?>
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

                <div class="info-box">
                    <small>
                        <i class="fas fa-info-circle me-2"></i>
                        Masukkan email dan jawab pertanyaan keamanan yang Anda buat saat registrasi.
                    </small>
                </div>
                
                <!-- Form -->
                <form action="<?= base_url('auth/forgot-password') ?>" method="post">
                    <?= csrf_field() ?>
                    
                    <div class="mb-4">
                        <label class="form-label">
                            <i class="fas fa-envelope me-2"></i>Email
                        </label>
                        <input type="email" class="form-control" name="email" 
                               placeholder="contoh@email.com" 
                               value="<?= old('email') ?>" required>
                    </div>

                    <div class="mb-4">
                        <label class="form-label">
                            <i class="fas fa-music me-2"></i>Siapa penyanyi favorit kamu?
                        </label>
                        <input type="text" class="form-control" name="security_answer" 
                               placeholder="Jawaban pertanyaan keamanan" 
                               value="<?= old('security_answer') ?>" required>
                        <small class="text-muted mt-2 d-block">
                            <i class="fas fa-lightbulb me-1"></i>
                            Ketik jawaban yang sama seperti saat registrasi
                        </small>
                    </div>
                    
                    <button type="submit" class="btn btn-primary w-100 mb-4">
                        <i class="fas fa-check-circle me-2"></i> Verifikasi & Reset Password
                    </button>

                    <div class="text-center mb-4">
                        <a href="<?= base_url('auth/login') ?>" class="link-back">
                            <i class="fas fa-arrow-left me-2"></i>Kembali ke Login
                        </a>
                    </div>

                    <hr class="my-4">

                    <div class="text-center">
                        <small class="text-muted">
                            <i class="fas fa-question-circle me-2"></i>
                            Lupa jawaban keamanan? Hubungi administrator
                        </small>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>