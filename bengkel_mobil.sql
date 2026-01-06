-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Waktu pembuatan: 03 Jan 2026 pada 13.23
-- Versi server: 10.4.32-MariaDB
-- Versi PHP: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `bengkel_mobil`
--

-- --------------------------------------------------------

--
-- Struktur dari tabel `detail_penjualan_sparepart`
--

CREATE TABLE `detail_penjualan_sparepart` (
  `id` int(11) NOT NULL,
  `penjualan_sparepart_id` int(11) NOT NULL,
  `sparepart_id` int(11) NOT NULL,
  `jumlah` int(11) NOT NULL,
  `harga_jual` decimal(12,2) NOT NULL,
  `subtotal` decimal(12,2) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `detail_penjualan_sparepart`
--

INSERT INTO `detail_penjualan_sparepart` (`id`, `penjualan_sparepart_id`, `sparepart_id`, `jumlah`, `harga_jual`, `subtotal`, `created_at`) VALUES
(4, 3, 5, 1, 400000.00, 400000.00, '2025-12-22 16:59:11'),
(5, 3, 3, 1, 70000.00, 70000.00, '2025-12-22 16:59:11'),
(6, 4, 7, 1, 2500000.00, 2500000.00, '2025-12-23 05:26:53'),
(7, 4, 2, 1, 350000.00, 350000.00, '2025-12-23 05:26:53'),
(8, 5, 7, 3, 2500000.00, 7500000.00, '2026-01-03 08:59:29'),
(9, 5, 5, 2, 400000.00, 800000.00, '2026-01-03 08:59:29');

-- --------------------------------------------------------

--
-- Struktur dari tabel `jasa_servis`
--

CREATE TABLE `jasa_servis` (
  `id` int(11) NOT NULL,
  `nama_layanan` varchar(100) NOT NULL,
  `harga_jasa` decimal(12,2) NOT NULL,
  `kategori_servis` enum('Ringan','Berat','Tune Up','Cuci','Lainnya') NOT NULL,
  `estimasi_durasi` int(11) NOT NULL COMMENT 'dalam menit',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `jasa_servis`
--

INSERT INTO `jasa_servis` (`id`, `nama_layanan`, `harga_jasa`, `kategori_servis`, `estimasi_durasi`, `created_at`, `updated_at`) VALUES
(1, 'Ganti Oli Mesin', 30000.00, 'Ringan', 30, '2025-12-05 12:44:40', '2025-12-05 19:52:11'),
(2, 'Tune Up', 300000.00, 'Tune Up', 60, '2025-12-07 09:30:35', '2025-12-07 09:30:35'),
(4, 'Turun Mesin', 3000000.00, 'Berat', 9600, '2025-12-23 05:04:55', '2025-12-23 05:05:27'),
(5, 'Cuci Busa', 50000.00, 'Cuci', 30, '2025-12-23 05:05:57', '2025-12-23 05:05:57'),
(6, 'Poles Full Body', 500000.00, 'Lainnya', 1440, '2025-12-23 05:06:42', '2025-12-23 05:06:42'),
(7, 'Ganti Oli Mesin + Filter', 50000.00, 'Ringan', 90, '2025-12-23 05:12:37', '2025-12-23 05:12:37'),
(8, 'Ganti Shock Breaker Depan', 300000.00, 'Ringan', 72, '2025-12-23 05:14:27', '2025-12-23 05:14:27');

-- --------------------------------------------------------

--
-- Struktur dari tabel `kendaraan`
--

CREATE TABLE `kendaraan` (
  `id` int(11) NOT NULL,
  `pelanggan_id` int(11) NOT NULL,
  `nomor_plat` varchar(20) NOT NULL,
  `merk` varchar(50) NOT NULL,
  `tipe` varchar(50) NOT NULL,
  `tahun` year(4) NOT NULL,
  `foto` varchar(255) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `kendaraan`
--

INSERT INTO `kendaraan` (`id`, `pelanggan_id`, `nomor_plat`, `merk`, `tipe`, `tahun`, `foto`, `created_at`, `updated_at`) VALUES
(1, 2, 'B 1244 WYN', 'Nissan', 'Livina', '2012', '1765733316_322d4e11a4d7cde79a9b.jpg', '2025-12-05 12:42:06', '2025-12-14 10:28:36'),
(2, 3, 'B 1244 TAS', 'Honda', 'Civic', '2000', '1765124908_6dd9696f4d33edfc5f94.jpg', '2025-12-07 09:28:28', '2025-12-07 09:28:28'),
(3, 4, 'B 1244 TES', 'Toyota', 'Avanza', '2013', '1766466123_260ea136435024b05755.jpg', '2025-12-07 09:48:39', '2025-12-23 05:02:03'),
(6, 2, 'B 4321 WYN', 'Nissan', 'Silvia S15', '2020', '1766466215_116b515125f27a560a6c.jpg', '2025-12-23 05:03:35', '2025-12-23 05:03:35'),
(7, 28, 'B 1020 TES', 'Mitsubishi', 'Lancer EVO III', '1996', '1766469102_1f35bed60d711c468d93.webp', '2025-12-23 05:51:42', '2025-12-23 05:51:42'),
(8, 4, 'B 1234 WYA', 'Nissan', 'Silvia S15', '2020', '1767430218_bc454cf1de75ec4d8933.jpg', '2026-01-03 08:50:18', '2026-01-03 08:50:18');

-- --------------------------------------------------------

--
-- Struktur dari tabel `mekanik`
--

CREATE TABLE `mekanik` (
  `id` int(11) NOT NULL,
  `nama` varchar(100) NOT NULL,
  `posisi` enum('Mesin','Kelistrikan','Ban','Umum') NOT NULL,
  `kontak` varchar(20) NOT NULL,
  `level_skill` enum('Junior','Senior','Expert') NOT NULL,
  `status_aktif` tinyint(1) DEFAULT 1,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `mekanik`
--

INSERT INTO `mekanik` (`id`, `nama`, `posisi`, `kontak`, `level_skill`, `status_aktif`, `created_at`, `updated_at`) VALUES
(1, 'Nur Wahid', 'Mesin', '089786865757', 'Senior', 1, '2025-12-05 12:42:37', '2025-12-05 19:51:33'),
(2, 'Bantet', 'Umum', '081234567812', 'Junior', 1, '2025-12-07 09:28:56', '2025-12-23 05:47:56'),
(3, 'Cibe', 'Umum', '081234567813', 'Junior', 1, '2025-12-07 09:29:17', '2025-12-07 09:29:17'),
(18, 'Abah', 'Mesin', '081234560004', 'Expert', 1, '2025-12-16 04:19:35', '2025-12-23 04:38:15'),
(19, 'Evan Setiawan', 'Mesin', '081234560005', 'Junior', 0, '2025-12-16 04:19:35', '2025-12-23 05:23:39'),
(21, 'Galih Saputra', 'Mesin', '081234560007', 'Junior', 0, '2025-12-16 04:19:35', '2025-12-23 05:23:49'),
(22, 'Hendra Wijaya', 'Mesin', '081234560008', 'Junior', 0, '2025-12-16 04:19:35', '2025-12-23 05:24:03'),
(23, 'Iqbal Maulana', 'Mesin', '081234560009', 'Junior', 0, '2025-12-16 04:19:35', '2025-12-23 05:24:13'),
(24, 'Joko Prabowo', 'Mesin', '081234560010', 'Junior', 0, '2025-12-16 04:19:35', '2025-12-23 05:24:25'),
(25, 'Junaidi', 'Ban', '089786865822', 'Senior', 0, '2025-12-23 04:37:31', '2025-12-23 05:24:34'),
(26, 'Bahasa Inggris II', 'Kelistrikan', '089786865712', 'Junior', 0, '2025-12-23 04:37:53', '2025-12-23 06:00:17');

-- --------------------------------------------------------

--
-- Struktur dari tabel `pelanggan`
--

CREATE TABLE `pelanggan` (
  `id` int(11) NOT NULL,
  `nama` varchar(100) NOT NULL,
  `alamat` text NOT NULL,
  `no_telepon` varchar(20) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `pelanggan`
--

INSERT INTO `pelanggan` (`id`, `nama`, `alamat`, `no_telepon`, `created_at`, `updated_at`) VALUES
(2, 'Dias Mayri', 'Lebak Wangi', '081212122323', '2025-12-05 12:41:06', '2025-12-15 20:11:25'),
(3, 'Bambang', 'Kota Tangerang', '083211231233', '2025-12-07 09:27:08', '2025-12-08 09:15:11'),
(4, 'Jamal', 'Kab. Tangerang', '081208120812', '2025-12-07 09:27:40', '2025-12-07 09:27:40'),
(8, 'Andi Saputra', 'Jakarta', '081234567890', '2025-12-16 04:15:30', '2025-12-16 04:15:30'),
(9, 'Budi Santoso', 'Bandung', '081298765432', '2025-12-16 04:15:30', '2025-12-16 04:15:30'),
(10, 'Citra Lestari', 'Surabaya', '082112345678', '2025-12-16 04:15:30', '2025-12-16 04:15:30'),
(12, 'Eko Prasetyo', 'Semarang', '081345678901', '2025-12-16 04:15:30', '2025-12-16 04:15:30'),
(14, 'Gita Permata', 'Denpasar', '081998877665', '2025-12-16 04:15:30', '2025-12-16 04:15:30'),
(16, 'Intan Maharani', 'Palembang', '082188776655', '2025-12-16 04:15:30', '2025-12-16 04:15:30'),
(18, 'Kurniawan', 'Makassar', '085311223344', '2025-12-16 04:15:30', '2025-12-16 04:15:30'),
(19, 'Lia Amelia', 'Balikpapan', '081255566677', '2025-12-16 04:15:30', '2025-12-16 04:15:30'),
(20, 'Muhammad Rizki', 'Pekanbaru', '082299887766', '2025-12-16 04:15:30', '2025-12-16 04:15:30'),
(21, 'Nina Oktaviani', 'Bogor', '081366778899', '2025-12-16 04:15:30', '2025-12-16 04:15:30'),
(22, 'Putra Ramadhan', 'Depok', '085288990011', '2025-12-16 04:15:30', '2025-12-16 04:15:30'),
(23, 'Qori Aisyah', 'Bekasi', '082133344455', '2025-12-16 04:15:30', '2025-12-16 04:15:30'),
(24, 'Rudi Hartono', 'Tangerang', '081277889900', '2025-12-16 04:15:30', '2025-12-16 04:15:30'),
(25, 'Siti Khadijah', 'Cirebon', '085712223344', '2025-12-16 04:15:30', '2025-12-16 04:15:30'),
(26, 'Taufik Hidayat', 'Purwokerto', '082144556677', '2025-12-16 04:15:30', '2025-12-16 04:15:30'),
(27, 'Yuni Kartika', 'Pontianak', '081388990022', '2025-12-16 04:15:30', '2025-12-16 04:15:30'),
(28, 'Rizal', 'Tangerang', '08908908907', '2025-12-23 05:50:54', '2025-12-23 05:50:54'),
(29, 'Naniiii', 'Tangerang Kotaaa', '089087678766', '2026-01-03 08:48:52', '2026-01-03 08:49:21');

-- --------------------------------------------------------

--
-- Struktur dari tabel `pembayaran`
--

CREATE TABLE `pembayaran` (
  `id` int(11) NOT NULL,
  `nomor_invoice` varchar(50) NOT NULL,
  `penerimaan_servis_id` int(11) NOT NULL,
  `total_jasa` decimal(12,2) NOT NULL DEFAULT 0.00,
  `total_sparepart` decimal(12,2) NOT NULL DEFAULT 0.00,
  `total_biaya` decimal(12,2) NOT NULL,
  `metode_pembayaran` enum('Cash','Transfer','QRIS') NOT NULL,
  `status_bayar` enum('Belum Lunas','Lunas') DEFAULT 'Belum Lunas',
  `tanggal_bayar` datetime NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `pembayaran`
--

INSERT INTO `pembayaran` (`id`, `nomor_invoice`, `penerimaan_servis_id`, `total_jasa`, `total_sparepart`, `total_biaya`, `metode_pembayaran`, `status_bayar`, `tanggal_bayar`, `created_at`, `updated_at`) VALUES
(2, 'INV-20251207-351B', 2, 30000.00, 300000.00, 330000.00, 'Transfer', 'Lunas', '2025-12-07 16:39:00', '2025-12-07 09:39:36', '2025-12-07 09:39:36'),
(3, 'INV-20251208-5BA9', 4, 30000.00, 300000.00, 330000.00, 'QRIS', 'Lunas', '2025-12-08 13:38:00', '2025-12-08 06:38:50', '2025-12-08 06:38:50'),
(4, 'INV-20251208-CC6E', 5, 330000.00, 650000.00, 980000.00, 'Cash', 'Lunas', '2025-12-08 15:06:00', '2025-12-08 08:06:24', '2025-12-08 08:06:24'),
(5, 'INV-20251216-44F2', 10, 330000.00, 300000.00, 630000.00, 'Cash', 'Lunas', '2025-12-16 05:26:00', '2025-12-15 22:27:07', '2025-12-15 22:27:07'),
(6, 'INV-20251221-29E3', 11, 330000.00, 420000.00, 750000.00, 'Cash', 'Lunas', '2025-12-21 17:31:00', '2025-12-21 10:31:13', '2025-12-21 10:31:13'),
(7, 'INV-20251221-2509', 12, 330000.00, 420000.00, 750000.00, 'QRIS', 'Lunas', '2025-12-21 17:57:00', '2025-12-21 10:57:16', '2025-12-21 10:57:16'),
(8, 'INV-20251222-278D', 13, 300000.00, 70000.00, 370000.00, 'Cash', 'Lunas', '2025-12-22 01:04:00', '2025-12-21 18:04:10', '2025-12-21 18:04:10'),
(9, 'INV-20251223-FD9F', 14, 300000.00, 400000.00, 700000.00, 'Cash', 'Lunas', '2025-12-23 02:02:00', '2025-12-22 19:02:26', '2025-12-22 19:02:26'),
(10, 'INV-20251223-B939', 16, 30000.00, 350000.00, 380000.00, 'Cash', 'Lunas', '2025-12-23 02:07:00', '2025-12-22 19:08:00', '2025-12-22 19:08:00'),
(11, 'INV-20251223-144B', 15, 30000.00, 70000.00, 100000.00, 'Cash', 'Lunas', '2025-12-23 02:16:00', '2025-12-22 19:16:47', '2025-12-22 19:16:47'),
(12, 'INV-20251223-83E9', 19, 30000.00, 400000.00, 430000.00, 'Cash', 'Lunas', '2025-12-23 11:59:00', '2025-12-23 04:59:16', '2025-12-23 04:59:16'),
(13, 'INV-20251223-01C3', 20, 3900000.00, 6455000.00, 10355000.00, 'Cash', 'Lunas', '2025-12-23 12:20:00', '2025-12-23 05:20:20', '2025-12-23 05:20:20'),
(14, 'INV-20260103-557E', 22, 330000.00, 420000.00, 750000.00, 'Transfer', 'Lunas', '2026-01-03 15:55:00', '2026-01-03 08:56:11', '2026-01-03 08:56:11');

-- --------------------------------------------------------

--
-- Struktur dari tabel `penerimaan_servis`
--

CREATE TABLE `penerimaan_servis` (
  `id` int(11) NOT NULL,
  `nomor_servis` varchar(50) NOT NULL,
  `pelanggan_id` int(11) NOT NULL,
  `kendaraan_id` int(11) NOT NULL,
  `keluhan` text NOT NULL,
  `estimasi_biaya` decimal(12,2) NOT NULL,
  `status` enum('Menunggu','Proses','Selesai','Diambil') DEFAULT 'Menunggu',
  `tanggal_masuk` datetime NOT NULL,
  `tanggal_selesai` datetime DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `penerimaan_servis`
--

INSERT INTO `penerimaan_servis` (`id`, `nomor_servis`, `pelanggan_id`, `kendaraan_id`, `keluhan`, `estimasi_biaya`, `status`, `tanggal_masuk`, `tanggal_selesai`, `created_at`, `updated_at`) VALUES
(2, 'SRV-20251207-C9FA', 2, 1, 'Ganti oli dan Tune Up', 500000.00, 'Selesai', '2025-12-07 16:12:00', '2025-12-07 16:38:57', '2025-12-07 09:13:06', '2025-12-22 18:51:01'),
(4, 'SRV-20251207-2FA2', 4, 3, 'Ganti oli + tune up', 500000.00, 'Selesai', '2025-12-07 16:48:00', '2025-12-08 13:38:33', '2025-12-07 09:49:21', '2025-12-22 18:51:01'),
(5, 'SRV-20251208-EE68', 2, 1, 'Ganti Oli apakekkkkkkkkk', 330000.00, 'Selesai', '2025-12-08 13:46:00', '2025-12-08 15:06:14', '2025-12-08 06:47:00', '2025-12-22 18:51:01'),
(10, 'SRV-20251216-E9AD', 2, 1, 'Ganti Oli + Tune Up', 5000000.00, 'Selesai', '2025-12-16 05:24:00', '2025-12-16 05:26:27', '2025-12-15 22:25:00', '2025-12-22 18:51:01'),
(11, 'SRV-20251221-4D8C', 3, 2, 'FSGDHGFDFFGsdsd', 1200000.00, 'Selesai', '2025-12-21 16:13:00', '2025-12-21 17:30:48', '2025-12-21 09:15:14', '2025-12-22 18:51:01'),
(12, 'SRV-20251221-D470', 4, 3, 'REGTHYKL;LKJHGHJ', 500000.00, 'Selesai', '2025-12-21 17:55:00', '2025-12-21 17:56:58', '2025-12-21 10:55:55', '2025-12-22 18:51:01'),
(13, 'SRV-20251222-9028', 3, 2, 'sezrdctfvgbhnjbhg', 700000.00, 'Selesai', '2025-12-22 01:02:00', '2025-12-22 01:03:55', '2025-12-21 18:03:06', '2025-12-22 18:51:01'),
(14, 'SRV-20251223-7951', 3, 2, 'sxdrctfvgybuhnjm', 100000.00, 'Selesai', '2025-12-23 01:44:00', '2025-12-23 02:03:17', '2025-12-22 18:44:44', '2025-12-22 19:03:17'),
(15, 'SRV-20251223-886A', 3, 2, 'xc vbnm,fcvghbjkojhg', 100000.00, 'Selesai', '2025-12-23 01:44:00', '2025-12-23 02:16:23', '2025-12-22 18:45:06', '2025-12-22 19:16:23'),
(16, 'SRV-20251223-8744', 2, 1, 'sxdctfvygbuhnjbhg', 100000.00, 'Selesai', '2025-12-23 01:45:00', '2025-12-23 02:07:49', '2025-12-22 18:45:48', '2025-12-22 19:07:49'),
(18, 'SRV-20251223-12A1', 2, 1, 'sxrdcfyvguhbijnklbedchb', 100000.00, 'Menunggu', '2025-12-23 11:56:00', NULL, '2025-12-23 04:56:53', '2025-12-23 04:56:53'),
(19, 'SRV-20251223-37A7', 4, 3, 'ezsrdtfyghjnkm', 100000.00, 'Selesai', '2025-12-23 11:57:00', '2025-12-23 11:59:05', '2025-12-23 04:57:40', '2025-12-23 04:59:05'),
(20, 'SRV-20251223-6327', 2, 6, 'Project Besar', 10000000.00, 'Selesai', '2025-12-23 12:17:00', '2025-12-23 12:20:04', '2025-12-23 05:18:01', '2025-12-23 05:20:04'),
(21, 'SRV-20251223-709D', 28, 7, 'Ganti oli aje', 600000.00, 'Proses', '2025-12-23 12:52:00', NULL, '2025-12-23 05:52:31', '2025-12-23 05:53:28'),
(22, 'SRV-20260103-247A', 4, 8, 'Ganti oli + tune up', 700000.00, 'Selesai', '2026-01-03 15:51:00', '2026-01-03 15:55:22', '2026-01-03 08:52:06', '2026-01-03 08:55:22');

-- --------------------------------------------------------

--
-- Struktur dari tabel `penjualan_sparepart`
--

CREATE TABLE `penjualan_sparepart` (
  `id` int(11) NOT NULL,
  `nomor_penjualan` varchar(50) NOT NULL,
  `nama_pembeli` varchar(100) NOT NULL,
  `tanggal_penjualan` date NOT NULL,
  `total_penjualan` decimal(12,2) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `penjualan_sparepart`
--

INSERT INTO `penjualan_sparepart` (`id`, `nomor_penjualan`, `nama_pembeli`, `tanggal_penjualan`, `total_penjualan`, `created_at`, `updated_at`) VALUES
(3, 'PJ-20251222-826C', 'Budi', '2025-12-22', 470000.00, '2025-12-22 16:59:11', '2025-12-22 16:59:11'),
(4, 'PJ-20251223-F91F', 'Jajang', '2025-12-23', 2850000.00, '2025-12-23 05:26:53', '2025-12-23 05:26:53'),
(5, 'PJ-20260103-F64B', 'Hanni', '2026-01-03', 8300000.00, '2026-01-03 08:59:29', '2026-01-03 08:59:29');

-- --------------------------------------------------------

--
-- Struktur dari tabel `sparepart`
--

CREATE TABLE `sparepart` (
  `id` int(11) NOT NULL,
  `nama_barang` varchar(100) NOT NULL,
  `kategori` varchar(50) NOT NULL,
  `pemasok` varchar(100) DEFAULT NULL,
  `kontak_pemasok` varchar(20) DEFAULT NULL,
  `alamat_pemasok` text DEFAULT NULL,
  `harga_beli` decimal(12,2) NOT NULL,
  `harga_jual` decimal(12,2) NOT NULL,
  `stok` int(11) NOT NULL DEFAULT 0,
  `satuan` varchar(20) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `sparepart`
--

INSERT INTO `sparepart` (`id`, `nama_barang`, `kategori`, `pemasok`, `kontak_pemasok`, `alamat_pemasok`, `harga_beli`, `harga_jual`, `stok`, `satuan`, `created_at`, `updated_at`) VALUES
(1, 'Shell Hx 6', 'Oli Mesin', 'PT. Shell Indonesia', '089087870987', 'Tangerang', 280000.00, 300000.00, 5, 'Galon (4/5lt)', '2025-12-05 12:43:53', '2025-12-21 10:26:50'),
(2, 'Shell Hx 7', 'Oli Mesin', 'PT. Shell Indonesia', '089087870987', 'Tangerang', 300000.00, 350000.00, 4, 'Galon (4/5lt)', '2025-12-07 09:30:05', '2026-01-03 08:54:16'),
(3, 'Cleaner Foam', 'Cleaner', 'PT. Wurth Indonesia', '089087871234', 'Jakarta Barat', 50000.00, 70000.00, 11, 'Kaleng', '2025-12-21 06:57:12', '2026-01-03 08:54:16'),
(4, 'Carbu Cleaner', 'Cleaner', 'PT. Wurth Indonesia', '089087871234', 'Jakarta Barat', 500000.00, 70000.00, 6, 'Pcs', '2025-12-21 06:59:03', '2025-12-23 05:44:42'),
(5, 'Shell Hx Ultra', 'Oli Mesin', 'PT. Shell Indonesia', '089087870987', '\r\nTangerang', 380000.00, 400000.00, 3, 'Galon (4/5lt)', '2025-12-21 07:01:09', '2026-01-03 08:59:29'),
(6, 'Sensor CKP Avanza 2010', 'Sparepart Mesin', 'PT. Astra Indonesia', '098765456781', 'Cilegon', 300000.00, 320000.00, 30, 'Pcs', '2025-12-21 07:04:12', '2025-12-23 05:13:19'),
(7, 'Packing Head Silvia 2020', 'Sparepart Mesin', 'PT. Nissan Indonesia', '089087871212', 'Tangerang', 2000000.00, 2500000.00, 7, 'Pcs', '2025-12-23 05:08:32', '2026-01-03 08:59:29'),
(8, 'Compound Body', 'Cleaner', 'TB. Maju Jaya', '089087870912', 'Bugel', 50000.00, 70000.00, 9, 'Pcs', '2025-12-23 05:09:50', '2025-12-23 05:09:50'),
(9, 'Shampoo Cuci Mobil', 'Cleaner', 'TB. Maju Jaya', '089087871234', 'Bugel', 50000.00, 75000.00, 10, 'Botol', '2025-12-23 05:10:50', '2025-12-23 05:19:39'),
(10, 'Filter Oli Nissan Universal Ori', 'Filter Oli', 'PT. Nissan Indonesia', '089087871234', 'Tangerang', 50000.00, 70000.00, 10, 'Pcs', '2025-12-23 05:12:09', '2025-12-23 05:53:28'),
(11, 'Shock Breaker Depan Nissan Silvia S 15', 'Sparepart Kaki-kaki', 'PT. Nissan Indonesia', '089087871234', 'Tangerang', 3000000.00, 3200000.00, 7, 'Set', '2025-12-23 05:15:44', '2025-12-23 05:19:39');

-- --------------------------------------------------------

--
-- Struktur dari tabel `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `username` varchar(50) NOT NULL,
  `email` varchar(100) NOT NULL,
  `password` varchar(255) NOT NULL,
  `security_answer` varchar(255) DEFAULT NULL,
  `full_name` varchar(100) NOT NULL,
  `reset_token` varchar(100) DEFAULT NULL,
  `reset_token_expire` datetime DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `users`
--

INSERT INTO `users` (`id`, `username`, `email`, `password`, `security_answer`, `full_name`, `reset_token`, `reset_token_expire`, `created_at`, `updated_at`) VALUES
(5, 'dosen', 'rzlhrmwn00@gmail.com', '$2y$10$E0csB9Nhmh1vITbXlNltwOnVCFPSrTD4rTKn.9gydPG0ZvqL00GF2', '$2y$10$nuub1wZdMSWKi70AVQObBO/jLYuLPJIN34xs.6w6diA6x5aSqxjhW', 'Sofjan', NULL, NULL, '2025-12-15 10:16:46', '2025-12-15 10:16:46'),
(6, 'jajang', 'admin@bengkel.com', '$2y$10$W.m6h9I1hQ1pveVhRaRnRumR/Nvije/OzpYnvs9JwyS9B.XPvlyMG', '$2y$10$d4d.G6R6zwhaPOY4.6lJQ.u/vMOeLB8wMl2.GAUieK8XN73mIBsma', 'Jajang', NULL, NULL, '2025-12-15 10:33:57', '2025-12-15 10:33:57'),
(7, '1124160208', 'ergev@wef.com', '$2y$10$U8X0eHj/ldcKr8fdOcLKUOUOC1xNTIJz3CqJuIMfQ3QVOxba5Qq72', '$2y$10$eqA1inM46nL7IhSv.O7ayOKi3nq9KjNetEtUmvBmNcRGEvvsPLFAW', 'Rizal Hermawan', NULL, NULL, '2025-12-15 10:35:46', '2025-12-15 10:35:46'),
(9, 'ijal', 'rizal@gmail.com', '$2y$10$0aYL3UzzB/6NAWgrMXN0DuvoRwTZQih5PAbQna5Ar0HPbQrcbzWIe', '$2y$10$LFjwFtl4zufmvLmqepKXLOSO8qTtgkWn.OMkMVFVqkuIcruLbkwRy', 'Rizal Hermawan', NULL, NULL, '2025-12-15 22:22:38', '2025-12-22 18:10:06'),
(10, '2024', 'lana@gmail.com', '$2y$10$PYt/ayIxcalUrrKVcX6SY.aeyOLjF4wK.vrkm2hpo0doUtKiyHKIO', '$2y$10$GZFi7aIJU.IOXwiNEq/LDuvckqOrt/7Yi6Jrh0iLhI1qOtgv/I8qe', 'Lana del rey', NULL, NULL, '2025-12-22 18:11:15', '2025-12-22 18:31:19'),
(11, 'admin123', 'admin1@gmail.com', '$2y$10$t.cfgwgPIHiZoLds2uy/UuHavTD3n.n/o7Ts1zWtIE/y2oMvWsoyS', '$2y$10$TeSlMO1qR8j2JRxTrTp3deuzzBt5mKf0Jg3OiZUTI8c/3MX4InBVS', 'Admin1', NULL, NULL, '2026-01-03 08:47:47', '2026-01-03 08:47:47');

-- --------------------------------------------------------

--
-- Struktur dari tabel `work_order`
--

CREATE TABLE `work_order` (
  `id` int(11) NOT NULL,
  `penerimaan_servis_id` int(11) NOT NULL,
  `mekanik_id` int(11) NOT NULL,
  `progres` text DEFAULT NULL,
  `status` enum('Pending','Dikerjakan','Selesai') DEFAULT 'Pending',
  `tanggal_mulai` datetime NOT NULL,
  `tanggal_selesai` datetime DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `work_order`
--

INSERT INTO `work_order` (`id`, `penerimaan_servis_id`, `mekanik_id`, `progres`, `status`, `tanggal_mulai`, `tanggal_selesai`, `created_at`, `updated_at`) VALUES
(3, 2, 1, 'Ta gituuuuuuuuuuuuuu', 'Selesai', '2025-12-07 16:13:00', '2025-12-07 16:38:57', '2025-12-07 09:13:47', '2025-12-07 09:38:57'),
(5, 4, 1, 'awwwwwwwwwwwwwwwwwwwwwwwww', 'Selesai', '2025-12-08 13:37:00', '2025-12-08 13:38:33', '2025-12-08 06:38:11', '2025-12-08 06:38:33'),
(6, 5, 1, 'hbehbcsdhcbhsbdhc', 'Selesai', '2025-12-08 15:05:00', '2025-12-08 15:06:14', '2025-12-08 08:06:05', '2025-12-08 08:06:14'),
(7, 10, 1, 'Buru burrrruuuuuu', 'Selesai', '2025-12-16 05:25:00', '2025-12-16 05:26:27', '2025-12-15 22:26:05', '2025-12-15 22:26:27'),
(8, 11, 3, 'ya gituuuuuuuuuuuu', 'Selesai', '2025-12-21 17:29:00', '2025-12-21 17:30:48', '2025-12-21 10:30:31', '2025-12-21 10:30:48'),
(9, 12, 3, 'EFDGFHHJGFD', 'Selesai', '2025-12-21 17:56:00', '2025-12-21 17:56:58', '2025-12-21 10:56:43', '2025-12-21 10:56:58'),
(10, 13, 2, 'dfghbnjmk', 'Selesai', '2025-12-22 01:03:00', '2025-12-22 01:03:55', '2025-12-21 18:03:40', '2025-12-21 18:03:55'),
(11, 14, 1, 'sxdcfgvhbjnhbgvf', 'Selesai', '2025-12-23 02:01:00', '2025-12-23 02:02:14', '2025-12-22 19:02:04', '2025-12-22 19:02:14'),
(12, 15, 1, 'aszdxfcgdxsf', 'Selesai', '2025-12-23 02:05:00', '2025-12-23 02:16:23', '2025-12-22 19:05:38', '2025-12-22 19:16:23'),
(13, 16, 1, 'fcgvbhvcgvhb', 'Selesai', '2025-12-23 02:07:00', '2025-12-23 02:07:49', '2025-12-22 19:07:33', '2025-12-22 19:07:49'),
(15, 19, 18, 'Ya gituuuuu', 'Selesai', '2025-12-23 11:57:00', '2025-12-23 11:59:05', '2025-12-23 04:58:28', '2025-12-23 04:59:05'),
(16, 20, 18, 'Sudah Seperti Baru', 'Selesai', '2025-12-23 12:18:00', '2025-12-23 12:20:04', '2025-12-23 05:19:39', '2025-12-23 05:20:04'),
(17, 21, 18, 'Filternya juga ganti', 'Pending', '2025-12-23 12:52:00', NULL, '2025-12-23 05:53:28', '2025-12-23 05:53:28'),
(18, 22, 18, 'mobil sudah datang. olinya pake hx7', 'Selesai', '2026-01-03 15:52:00', '2026-01-03 15:55:22', '2026-01-03 08:54:16', '2026-01-03 08:55:22');

-- --------------------------------------------------------

--
-- Struktur dari tabel `work_order_jasa`
--

CREATE TABLE `work_order_jasa` (
  `id` int(11) NOT NULL,
  `work_order_id` int(11) NOT NULL,
  `jasa_servis_id` int(11) NOT NULL,
  `harga` decimal(12,2) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `work_order_jasa`
--

INSERT INTO `work_order_jasa` (`id`, `work_order_id`, `jasa_servis_id`, `harga`, `created_at`) VALUES
(3, 3, 1, 30000.00, '2025-12-07 16:13:47'),
(5, 5, 1, 30000.00, '2025-12-08 13:38:11'),
(6, 6, 2, 300000.00, '2025-12-08 15:06:05'),
(7, 6, 1, 30000.00, '2025-12-08 15:06:05'),
(8, 7, 1, 30000.00, '2025-12-16 05:26:05'),
(9, 7, 2, 300000.00, '2025-12-16 05:26:05'),
(10, 8, 2, 300000.00, '2025-12-21 17:30:31'),
(11, 8, 1, 30000.00, '2025-12-21 17:30:31'),
(12, 9, 1, 30000.00, '2025-12-21 17:56:43'),
(13, 9, 2, 300000.00, '2025-12-21 17:56:43'),
(14, 10, 2, 300000.00, '2025-12-21 18:03:40'),
(15, 11, 2, 300000.00, '2025-12-22 19:02:04'),
(16, 12, 1, 30000.00, '2025-12-22 19:05:38'),
(17, 13, 1, 30000.00, '2025-12-22 19:07:33'),
(19, 15, 1, 30000.00, '2025-12-23 04:58:28'),
(20, 16, 4, 3000000.00, '2025-12-23 05:19:39'),
(21, 16, 7, 50000.00, '2025-12-23 05:19:39'),
(22, 16, 6, 500000.00, '2025-12-23 05:19:39'),
(23, 16, 5, 50000.00, '2025-12-23 05:19:39'),
(24, 16, 8, 300000.00, '2025-12-23 05:19:39'),
(25, 17, 7, 50000.00, '2025-12-23 05:53:28'),
(26, 18, 1, 30000.00, '2026-01-03 08:54:16'),
(27, 18, 2, 300000.00, '2026-01-03 08:54:16');

-- --------------------------------------------------------

--
-- Struktur dari tabel `work_order_sparepart`
--

CREATE TABLE `work_order_sparepart` (
  `id` int(11) NOT NULL,
  `work_order_id` int(11) NOT NULL,
  `sparepart_id` int(11) NOT NULL,
  `jumlah` int(11) NOT NULL,
  `harga_satuan` decimal(12,2) NOT NULL,
  `subtotal` decimal(12,2) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `work_order_sparepart`
--

INSERT INTO `work_order_sparepart` (`id`, `work_order_id`, `sparepart_id`, `jumlah`, `harga_satuan`, `subtotal`, `created_at`) VALUES
(2, 3, 1, 1, 300000.00, 300000.00, '2025-12-07 16:13:47'),
(4, 5, 1, 1, 300000.00, 300000.00, '2025-12-08 13:38:11'),
(5, 6, 1, 1, 300000.00, 300000.00, '2025-12-08 15:06:05'),
(6, 6, 2, 1, 350000.00, 350000.00, '2025-12-08 15:06:05'),
(7, 7, 1, 1, 300000.00, 300000.00, '2025-12-16 05:26:05'),
(8, 8, 2, 1, 350000.00, 350000.00, '2025-12-21 17:30:31'),
(9, 8, 3, 1, 70000.00, 70000.00, '2025-12-21 17:30:31'),
(10, 9, 2, 1, 350000.00, 350000.00, '2025-12-21 17:56:43'),
(11, 9, 3, 1, 70000.00, 70000.00, '2025-12-21 17:56:43'),
(12, 10, 3, 1, 70000.00, 70000.00, '2025-12-21 18:03:40'),
(13, 11, 5, 1, 400000.00, 400000.00, '2025-12-22 19:02:04'),
(14, 12, 3, 1, 70000.00, 70000.00, '2025-12-22 19:05:38'),
(15, 13, 2, 1, 350000.00, 350000.00, '2025-12-22 19:07:33'),
(17, 15, 5, 1, 400000.00, 400000.00, '2025-12-23 04:58:28'),
(18, 16, 5, 1, 400000.00, 400000.00, '2025-12-23 05:19:39'),
(19, 16, 3, 2, 70000.00, 140000.00, '2025-12-23 05:19:39'),
(20, 16, 4, 2, 70000.00, 140000.00, '2025-12-23 05:19:39'),
(21, 16, 11, 1, 3200000.00, 3200000.00, '2025-12-23 05:19:39'),
(22, 16, 9, 1, 75000.00, 75000.00, '2025-12-23 05:19:39'),
(23, 16, 7, 1, 2500000.00, 2500000.00, '2025-12-23 05:19:39'),
(24, 17, 5, 1, 400000.00, 400000.00, '2025-12-23 05:53:28'),
(25, 17, 10, 1, 70000.00, 70000.00, '2025-12-23 05:53:28'),
(26, 18, 2, 1, 350000.00, 350000.00, '2026-01-03 08:54:16'),
(27, 18, 3, 1, 70000.00, 70000.00, '2026-01-03 08:54:16');

--
-- Indexes for dumped tables
--

--
-- Indeks untuk tabel `detail_penjualan_sparepart`
--
ALTER TABLE `detail_penjualan_sparepart`
  ADD PRIMARY KEY (`id`),
  ADD KEY `penjualan_sparepart_id` (`penjualan_sparepart_id`),
  ADD KEY `sparepart_id` (`sparepart_id`);

--
-- Indeks untuk tabel `jasa_servis`
--
ALTER TABLE `jasa_servis`
  ADD PRIMARY KEY (`id`);

--
-- Indeks untuk tabel `kendaraan`
--
ALTER TABLE `kendaraan`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `nomor_plat` (`nomor_plat`),
  ADD KEY `pelanggan_id` (`pelanggan_id`);

--
-- Indeks untuk tabel `mekanik`
--
ALTER TABLE `mekanik`
  ADD PRIMARY KEY (`id`);

--
-- Indeks untuk tabel `pelanggan`
--
ALTER TABLE `pelanggan`
  ADD PRIMARY KEY (`id`);

--
-- Indeks untuk tabel `pembayaran`
--
ALTER TABLE `pembayaran`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `nomor_invoice` (`nomor_invoice`),
  ADD KEY `penerimaan_servis_id` (`penerimaan_servis_id`);

--
-- Indeks untuk tabel `penerimaan_servis`
--
ALTER TABLE `penerimaan_servis`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `nomor_servis` (`nomor_servis`),
  ADD KEY `pelanggan_id` (`pelanggan_id`),
  ADD KEY `kendaraan_id` (`kendaraan_id`);

--
-- Indeks untuk tabel `penjualan_sparepart`
--
ALTER TABLE `penjualan_sparepart`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `nomor_penjualan` (`nomor_penjualan`);

--
-- Indeks untuk tabel `sparepart`
--
ALTER TABLE `sparepart`
  ADD PRIMARY KEY (`id`);

--
-- Indeks untuk tabel `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `username` (`username`),
  ADD UNIQUE KEY `email` (`email`);

--
-- Indeks untuk tabel `work_order`
--
ALTER TABLE `work_order`
  ADD PRIMARY KEY (`id`),
  ADD KEY `penerimaan_servis_id` (`penerimaan_servis_id`),
  ADD KEY `mekanik_id` (`mekanik_id`);

--
-- Indeks untuk tabel `work_order_jasa`
--
ALTER TABLE `work_order_jasa`
  ADD PRIMARY KEY (`id`),
  ADD KEY `work_order_id` (`work_order_id`),
  ADD KEY `jasa_servis_id` (`jasa_servis_id`);

--
-- Indeks untuk tabel `work_order_sparepart`
--
ALTER TABLE `work_order_sparepart`
  ADD PRIMARY KEY (`id`),
  ADD KEY `work_order_id` (`work_order_id`),
  ADD KEY `sparepart_id` (`sparepart_id`);

--
-- AUTO_INCREMENT untuk tabel yang dibuang
--

--
-- AUTO_INCREMENT untuk tabel `detail_penjualan_sparepart`
--
ALTER TABLE `detail_penjualan_sparepart`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT untuk tabel `jasa_servis`
--
ALTER TABLE `jasa_servis`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT untuk tabel `kendaraan`
--
ALTER TABLE `kendaraan`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT untuk tabel `mekanik`
--
ALTER TABLE `mekanik`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=27;

--
-- AUTO_INCREMENT untuk tabel `pelanggan`
--
ALTER TABLE `pelanggan`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=30;

--
-- AUTO_INCREMENT untuk tabel `pembayaran`
--
ALTER TABLE `pembayaran`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;

--
-- AUTO_INCREMENT untuk tabel `penerimaan_servis`
--
ALTER TABLE `penerimaan_servis`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=23;

--
-- AUTO_INCREMENT untuk tabel `penjualan_sparepart`
--
ALTER TABLE `penjualan_sparepart`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT untuk tabel `sparepart`
--
ALTER TABLE `sparepart`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT untuk tabel `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT untuk tabel `work_order`
--
ALTER TABLE `work_order`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=19;

--
-- AUTO_INCREMENT untuk tabel `work_order_jasa`
--
ALTER TABLE `work_order_jasa`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=28;

--
-- AUTO_INCREMENT untuk tabel `work_order_sparepart`
--
ALTER TABLE `work_order_sparepart`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=28;

--
-- Ketidakleluasaan untuk tabel pelimpahan (Dumped Tables)
--

--
-- Ketidakleluasaan untuk tabel `detail_penjualan_sparepart`
--
ALTER TABLE `detail_penjualan_sparepart`
  ADD CONSTRAINT `detail_penjualan_sparepart_ibfk_1` FOREIGN KEY (`penjualan_sparepart_id`) REFERENCES `penjualan_sparepart` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `detail_penjualan_sparepart_ibfk_2` FOREIGN KEY (`sparepart_id`) REFERENCES `sparepart` (`id`);

--
-- Ketidakleluasaan untuk tabel `kendaraan`
--
ALTER TABLE `kendaraan`
  ADD CONSTRAINT `kendaraan_ibfk_1` FOREIGN KEY (`pelanggan_id`) REFERENCES `pelanggan` (`id`) ON DELETE CASCADE;

--
-- Ketidakleluasaan untuk tabel `pembayaran`
--
ALTER TABLE `pembayaran`
  ADD CONSTRAINT `pembayaran_ibfk_1` FOREIGN KEY (`penerimaan_servis_id`) REFERENCES `penerimaan_servis` (`id`) ON DELETE CASCADE;

--
-- Ketidakleluasaan untuk tabel `penerimaan_servis`
--
ALTER TABLE `penerimaan_servis`
  ADD CONSTRAINT `penerimaan_servis_ibfk_1` FOREIGN KEY (`pelanggan_id`) REFERENCES `pelanggan` (`id`),
  ADD CONSTRAINT `penerimaan_servis_ibfk_2` FOREIGN KEY (`kendaraan_id`) REFERENCES `kendaraan` (`id`);

--
-- Ketidakleluasaan untuk tabel `work_order`
--
ALTER TABLE `work_order`
  ADD CONSTRAINT `work_order_ibfk_1` FOREIGN KEY (`penerimaan_servis_id`) REFERENCES `penerimaan_servis` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `work_order_ibfk_2` FOREIGN KEY (`mekanik_id`) REFERENCES `mekanik` (`id`);

--
-- Ketidakleluasaan untuk tabel `work_order_jasa`
--
ALTER TABLE `work_order_jasa`
  ADD CONSTRAINT `work_order_jasa_ibfk_1` FOREIGN KEY (`work_order_id`) REFERENCES `work_order` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `work_order_jasa_ibfk_2` FOREIGN KEY (`jasa_servis_id`) REFERENCES `jasa_servis` (`id`);

--
-- Ketidakleluasaan untuk tabel `work_order_sparepart`
--
ALTER TABLE `work_order_sparepart`
  ADD CONSTRAINT `work_order_sparepart_ibfk_1` FOREIGN KEY (`work_order_id`) REFERENCES `work_order` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `work_order_sparepart_ibfk_2` FOREIGN KEY (`sparepart_id`) REFERENCES `sparepart` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
