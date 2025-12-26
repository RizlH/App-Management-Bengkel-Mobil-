<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $title ?? 'Bengkel Mobil' ?> - Management System</title>
    
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <!-- DataTables CSS -->
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/dataTables.bootstrap5.min.css">
    
    <!-- Custom CSS -->
    <style>
        :root {
            --primary-red: #dc3545;
            --dark-red: #a71d2a;
            --light-red: #f8d7da;
            --black: #212529;
            --white: #ffffff;
            --gray: #6c757d;
            --light-gray: #f8f9fa;
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        html, body {
            height: 100%;
            overflow-x: hidden;
        }

        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: var(--light-gray);
        }

        /* Sidebar Styles */
        .sidebar {
            height: 100vh;
            background: linear-gradient(180deg, var(--black) 0%, #1a1a1a 100%);
            color: var(--white);
            position: fixed;
            top: 0;
            left: 0;
            width: 250px;
            z-index: 1040;
            transition: transform 0.3s ease-in-out;
            overflow-y: auto;
            overflow-x: hidden;
        }

        .sidebar::-webkit-scrollbar {
            width: 6px;
        }

        .sidebar::-webkit-scrollbar-track {
            background: rgba(255, 255, 255, 0.1);
        }

        .sidebar::-webkit-scrollbar-thumb {
            background: var(--primary-red);
            border-radius: 3px;
        }

        .sidebar .brand {
            padding: 20px;
            background-color: var(--primary-red);
            text-align: center;
            border-bottom: 3px solid var(--dark-red);
            position: sticky;
            top: 0;
            z-index: 10;
        }

        .sidebar .brand h4 {
            margin: 0;
            font-weight: bold;
            color: var(--white);
        }

        .sidebar .nav-link {
            color: rgba(255, 255, 255, 0.8);
            padding: 12px 20px;
            margin: 5px 15px;
            border-radius: 8px;
            transition: all 0.3s;
            display: flex;
            align-items: center;
        }

        .sidebar .nav-link:hover,
        .sidebar .nav-link.active {
            background-color: var(--primary-red);
            color: var(--white);
            transform: translateX(5px);
        }

        .sidebar .nav-link i {
            margin-right: 10px;
            width: 20px;
            text-align: center;
        }

        /* Main Content */
        .main-content {
            margin-left: 250px;
            padding: 20px;
            min-height: 100vh;
            width: calc(100% - 250px);
            transition: margin-left 0.3s ease-in-out, width 0.3s ease-in-out;
        }

        /* Topbar */
        .topbar {
            background-color: var(--white);
            padding: 15px 30px;
            margin: -20px -20px 20px -20px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
            border-bottom: 3px solid var(--primary-red);
            position: sticky;
            top: 0;
            z-index: 999;
            display: flex;
            align-items: center;
            gap: 15px;
        }

        .topbar .toggle-wrapper {
            display: none;
        }

        .topbar h5 {
            margin: 0;
            flex: 1;
        }

        /* User Dropdown */
        .user-dropdown {
            position: relative;
        }

        .user-dropdown .dropdown-toggle {
            background: none;
            border: none;
            color: var(--black);
            cursor: pointer;
            padding: 8px 12px;
            border-radius: 8px;
            transition: all 0.3s;
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .user-dropdown .dropdown-toggle:hover {
            background-color: var(--light-gray);
        }

        .user-dropdown .dropdown-toggle::after {
            content: '\f078';
            font-family: 'Font Awesome 6 Free';
            font-weight: 900;
            border: none;
            margin-left: 5px;
            font-size: 0.75rem;
        }

        .user-dropdown .dropdown-menu {
            position: absolute;
            top: 100%;
            right: 0;
            background: var(--white);
            box-shadow: 0 5px 20px rgba(0,0,0,0.15);
            border-radius: 8px;
            padding: 8px 0;
            min-width: 200px;
            margin-top: 8px;
            display: none;
            z-index: 1000;
            border: 1px solid #ddd;
        }

        .user-dropdown .dropdown-menu.show {
            display: block;
        }

        .user-dropdown .dropdown-item {
            padding: 10px 20px;
            color: var(--black);
            text-decoration: none;
            display: flex;
            align-items: center;
            gap: 10px;
            transition: all 0.3s;
        }

        .user-dropdown .dropdown-item:hover {
            background-color: var(--light-gray);
        }

        .user-dropdown .dropdown-item i {
            width: 20px;
            text-align: center;
        }

        .user-dropdown .dropdown-divider {
            height: 1px;
            background-color: #e0e0e0;
            margin: 5px 0;
        }

        /* Cards */
        .card {
            border: none;
            border-radius: 10px;
            box-shadow: 0 0 20px rgba(0,0,0,0.08);
            margin-bottom: 20px;
        }

        .card-header {
            background: linear-gradient(135deg, var(--primary-red) 0%, var(--dark-red) 100%);
            color: var(--white);
            border-radius: 10px 10px 0 0 !important;
            padding: 15px 20px;
            font-weight: bold;
        }

        /* Buttons */
        .btn-primary {
            background-color: var(--primary-red);
            border-color: var(--primary-red);
        }

        .btn-primary:hover {
            background-color: var(--dark-red);
            border-color: var(--dark-red);
        }

        .btn-danger {
            background-color: var(--black);
            border-color: var(--black);
        }

        .btn-danger:hover {
            background-color: #000;
            border-color: #000;
        }

        /* Tables */
        .table thead th {
            background-color: var(--black);
            color: var(--white);
            border: none;
        }

        /* Stats Cards */
        .stats-card {
            border-left: 4px solid var(--primary-red);
            transition: transform 0.3s;
        }

        .stats-card:hover {
            transform: translateY(-5px);
        }

        .stats-icon {
            font-size: 3rem;
            opacity: 0.3;
        }

        /* Alerts */
        .alert {
            border-left: 4px solid var(--primary-red);
        }

        /* Mobile Toggle Button - Hidden by default */
        .mobile-toggle {
            display: none;
            background: transparent;
            color: var(--primary-red);
            border: none;
            padding: 8px;
            cursor: pointer;
            transition: all 0.3s;
            font-size: 1.3rem;
        }

        .mobile-toggle:hover {
            color: var(--dark-red);
        }

        .mobile-toggle:active {
            transform: scale(0.9);
        }

        /* Sidebar Overlay */
        .sidebar-overlay {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0, 0, 0, 0.5);
            z-index: 1030;
            opacity: 0;
            transition: opacity 0.3s ease-in-out;
        }

        .sidebar-overlay.active {
            display: block;
            opacity: 1;
        }

        /* Content Wrapper */
        .content-wrapper {
            padding-bottom: 50px;
        }

        /* ===== RESPONSIVE DESIGN ===== */
        @media (max-width: 768px) {
            /* Hide sidebar by default on mobile */
            .sidebar {
                transform: translateX(-100%);
            }

            /* Show sidebar when active */
            .sidebar.active {
                transform: translateX(0);
                box-shadow: 5px 0 20px rgba(0, 0, 0, 0.3);
            }

            /* Show mobile toggle button in topbar */
            .topbar .toggle-wrapper {
                display: block;
            }

            .mobile-toggle {
                display: block;
            }

            /* Adjust main content for mobile */
            .main-content {
                margin-left: 0;
                width: 100%;
                padding: 15px;
            }

            /* Adjust topbar for mobile */
            .topbar {
                margin: -15px -15px 15px -15px;
                padding: 12px 15px;
                gap: 10px;
            }

            .topbar h5 {
                font-size: 1rem;
            }

            /* User dropdown mobile */
            .user-dropdown .dropdown-toggle {
                padding: 6px 10px;
                font-size: 0.9rem;
            }

            .user-dropdown .dropdown-toggle .user-name {
                display: none;
            }

            .user-dropdown .dropdown-menu {
                min-width: 180px;
                right: -10px;
            }

            /* Card adjustments */
            .card {
                margin-bottom: 15px;
            }

            .card-header {
                padding: 12px 15px;
                font-size: 0.95rem;
            }

            /* Stats cards */
            .stats-card {
                margin-bottom: 15px;
            }

            .stats-icon {
                font-size: 2rem;
            }

            /* Table responsive */
            .table-responsive {
                overflow-x: auto;
                -webkit-overflow-scrolling: touch;
            }

            /* Adjust sidebar nav items */
            .sidebar nav {
                display: flex;
                flex-direction: column;
                gap: 5px; /* Mengganti margin individual */
            }
            
            .sidebar .nav-link {
                color: rgba(255, 255, 255, 0.8);
                padding: 12px 20px;
                margin: 0 15px; /* Hanya margin kiri-kanan */
                border-radius: 8px;
                transition: all 0.3s;
                display: flex;
                align-items: center;
                /* Isolasi hover effect */
                position: relative;
                will-change: transform, background-color;
                backface-visibility: hidden;
            }

            .sidebar .nav-link i {
                margin-right: 8px;
                font-size: 0.9rem;
            }

            .sidebar .nav-link:hover {
                background-color: var(--primary-red);
                color: var(--white);
                transform: translateX(5px);
                /* Mencegah efek spillover */
                z-index: 10;
            }

            .sidebar .nav-link.active {
                background-color: var(--primary-red);
                color: var(--white);
                transform: translateX(5px);
            }

            /* Pertegas pemisah kategori */
            .sidebar .px-3.mt-3.mb-2 {
                margin-top: 20px !important;
                margin-bottom: 10px !important;
                padding: 10px 20px 5px;
                position: relative;
                z-index: 5;
            }

            .sidebar .px-3.mt-3.mb-2:not(:first-child) {
                border-top: 1px solid rgba(255, 255, 255, 0.15);

            .sidebar .brand {
                padding: 15px;
            }

            .sidebar .brand h4 {
                font-size: 1.2rem;
            }

            .sidebar .brand small {
                font-size: 0.75rem;
            }
        }

        @media (max-width: 576px) {
            /* Extra small devices */
            .topbar {
                padding: 10px 12px;
            }

            .topbar h5 {
                font-size: 0.9rem;
            }

            .mobile-toggle {
                font-size: 1.2rem;
                padding: 6px;
            }

            .user-dropdown .dropdown-toggle {
                padding: 5px 8px;
            }

            .user-dropdown .dropdown-toggle i {
                font-size: 1.2rem;
            }

            .card-header {
                font-size: 0.9rem;
                padding: 10px 12px;
            }

            .btn {
                font-size: 0.85rem;
                padding: 6px 12px;
            }
        }

        /* Landscape orientation on mobile */
        @media (max-width: 768px) and (orientation: landscape) {
            .sidebar {
                width: 200px;
            }

            .sidebar .brand {
                padding: 10px;
            }

            .sidebar .brand h4 {
                font-size: 1rem;
            }

            .sidebar .nav-link {
                padding: 8px 12px;
                font-size: 0.85rem;
            }
        }

        /* Print styles */
        @media print {
            .sidebar,
            .mobile-toggle,
            .topbar,
            .sidebar-overlay {
                display: none !important;
            }

            .main-content {
                margin-left: 0 !important;
                width: 100% !important;
            }
        }
    </style>
</head>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>


<body>


<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<!-- Sidebar Overlay -->
<div class="sidebar-overlay" id="sidebarOverlay"></div>

<!-- Sidebar -->
<div class="sidebar" id="sidebar">
    <div class="brand">
        <i class="fas fa-car fa-2x mb-2"></i>
        <h4>BENGKEL MOBIL</h4>
        <small>Management System</small>
    </div>
    
    <nav class="nav flex-column mt-4">
        <a class="nav-link <?= uri_string() == 'dashboard' ? 'active' : '' ?>" href="<?= base_url('dashboard') ?>">
            <i class="fas fa-home"></i> <span>Dashboard</span>
        </a>
        
        <div class="px-3 mt-3 mb-2" style="color: var(--gray); font-size: 0.85rem;">
            <strong>MASTER DATA</strong>
        </div>
        
        <a class="nav-link <?= strpos(uri_string(), 'pelanggan') !== false ? 'active' : '' ?>" href="<?= base_url('pelanggan') ?>">
            <i class="fas fa-users"></i> <span>Pelanggan</span>
        </a>
        
        <a class="nav-link <?= strpos(uri_string(), 'kendaraan') !== false ? 'active' : '' ?>" href="<?= base_url('kendaraan') ?>">
            <i class="fas fa-car"></i> <span>Kendaraan</span>
        </a>
        
        <a class="nav-link <?= strpos(uri_string(), 'mekanik') !== false ? 'active' : '' ?>" href="<?= base_url('mekanik') ?>">
            <i class="fas fa-user-cog"></i> <span>Mekanik</span>
        </a>
        
        <a class="nav-link master-nav <?= strpos(uri_string(), 'sparepart') !== false && strpos(uri_string(), 'penjualan-sparepart') === false ? 'active' : '' ?>" href="<?= base_url('sparepart') ?>">
            <i class="fas fa-cogs"></i> <span>Sparepart</span>
        </a>
        
        <a class="nav-link <?= strpos(uri_string(), 'jasa-servis') !== false ? 'active' : '' ?>" href="<?= base_url('jasa-servis') ?>">
            <i class="fas fa-wrench"></i> <span>Jasa Servis</span>
        </a>
        
        <div class="px-3 mt-3 mb-2" style="color: var(--gray); font-size: 0.85rem;">
            <strong>TRANSAKSI</strong>
        </div>
        
        <a class="nav-link <?= strpos(uri_string(), 'penerimaan-servis') !== false ? 'active' : '' ?>" href="<?= base_url('penerimaan-servis') ?>">
            <i class="fas fa-clipboard-check"></i> <span>Penerimaan Servis</span>
        </a>
        
        <a class="nav-link <?= strpos(uri_string(), 'work-order') !== false ? 'active' : '' ?>" href="<?= base_url('work-order') ?>">
            <i class="fas fa-tasks"></i> <span>Work Order</span>
        </a>
        
        <a class="nav-link <?= strpos(uri_string(), 'pembayaran') !== false ? 'active' : '' ?>" href="<?= base_url('pembayaran') ?>">
            <i class="fas fa-money-bill-wave"></i> <span>Pembayaran</span>
        </a>
        
        <a class="nav-link transaksi-nav <?= strpos(uri_string(), 'penjualan-sparepart') !== false ? 'active' : '' ?>" href="<?= base_url('penjualan-sparepart') ?>">
            <i class="fas fa-shopping-cart"></i> <span>Penjualan Sparepart</span>
        </a>

        <div class="px-3 mt-3 mb-2" style="color: var(--gray); font-size: 0.85rem;">
            <strong>LAPORAN</strong>
        </div>
        
        <a class="nav-link <?= strpos(uri_string(), 'laporan') !== false ? 'active' : '' ?>" href="<?= base_url('laporan') ?>">
            <i class="fas fa-chart-line"></i> <span>Laporan</span>
        </a>
        
        <a class="nav-link" href="<?= base_url('auth/logout') ?>">
            <i class="fas fa-sign-out-alt"></i> <span>Logout</span>
        </a>
        
        <div style="height: 50px;"></div>
    </nav>
</div>

<!-- Main Content -->
<div class="main-content">
    <!-- Topbar -->
    <div class="topbar">
        <!-- Toggle Button untuk Mobile -->
        <div class="toggle-wrapper">
            <button class="mobile-toggle" id="sidebarToggle" aria-label="Toggle Sidebar">
                <i class="fas fa-bars"></i>
            </button>
        </div>
        
        <!-- Page Title -->
        <h5><?= $title ?? 'Dashboard' ?></h5>
        
        <!-- User Dropdown -->
        <div class="user-dropdown dropdown">
            <button class="dropdown-toggle" id="userDropdown" type="button" 
                    data-bs-toggle="dropdown" aria-expanded="false">
                <i class="fas fa-user-circle fa-lg text-danger"></i>
                <strong class="user-name"><?= session()->get('full_name') ?></strong>
            </button>
            <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="userDropdown">
                <li>
                    <button class="dropdown-item text-danger" type="button" onclick="confirmLogout()">
                        <i class="fas fa-sign-out-alt me-2"></i>
                        <span>Logout</span>
                    </button>
                </li>
            </ul>
        </div>

        <script>
        function confirmLogout() {
            // Tutup dropdown Bootstrap
            const dropdown = bootstrap.Dropdown.getInstance(document.getElementById('userDropdown'));
            if (dropdown) {
                dropdown.hide();
            }
            
            // Tampilkan konfirmasi
            Swal.fire({
                title: 'Logout',
                text: 'Yakin ingin logout?',
                icon: 'question',
                showCancelButton: true,
                confirmButtonColor: '#dc3545',
                cancelButtonColor: '#6c757d',
                confirmButtonText: 'Ya',
                cancelButtonText: 'Tidak'
            }).then((result) => {
                if (result.isConfirmed) {
                    window.location.href = '<?= base_url('auth/logout') ?>';
                }
            });
        }
        </script>
    </div>

    <!-- Content Wrapper -->

    <div class="content-wrapper">
        <?php if (session()->getFlashdata('success')): ?>
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                <i class="fas fa-check-circle me-2"></i>
                <?= session()->getFlashdata('success') ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        <?php endif; ?>

        <?php if (session()->getFlashdata('error')): ?>
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <i class="fas fa-exclamation-circle me-2"></i>
                <?= session()->getFlashdata('error') ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        <?php endif; ?>
