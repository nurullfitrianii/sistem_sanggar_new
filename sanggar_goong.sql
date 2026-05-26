-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: May 12, 2026 at 04:38 AM
-- Server version: 10.4.32-MariaDB
-- PHP Version: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `sanggar_goong`
--

-- --------------------------------------------------------

--
-- Table structure for table `absensi`
--

CREATE TABLE `absensi` (
  `id_absensi` bigint(20) UNSIGNED NOT NULL,
  `id_user` int(11) DEFAULT NULL,
  `id_jadwal` int(11) DEFAULT NULL,
  `waktu_hadir` datetime NOT NULL DEFAULT current_timestamp(),
  `status` varchar(20) NOT NULL DEFAULT 'Hadir',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `absensi`
--

INSERT INTO `absensi` (`id_absensi`, `id_user`, `id_jadwal`, `waktu_hadir`, `status`, `created_at`, `updated_at`) VALUES
(1, 12, 1, '2026-05-09 04:56:11', 'Hadir', '2026-05-08 21:56:11', '2026-05-08 21:56:11'),
(2, 29, 2, '2026-05-11 01:37:10', 'Hadir', NULL, NULL),
(3, 32, 3, '2026-05-11 10:58:18', 'Hadir', NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `galeri`
--

CREATE TABLE `galeri` (
  `id_galeri` int(11) NOT NULL,
  `id_sanggar` int(11) DEFAULT NULL,
  `judul` varchar(100) DEFAULT NULL,
  `foto` varchar(255) DEFAULT NULL,
  `keterangan` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `informasi_berita`
--

CREATE TABLE `informasi_berita` (
  `id_informasi` int(11) NOT NULL,
  `id_user` int(11) DEFAULT NULL,
  `judul` varchar(200) DEFAULT NULL,
  `slug` varchar(200) DEFAULT NULL,
  `isi` text DEFAULT NULL,
  `status` enum('draft','published') NOT NULL DEFAULT 'published',
  `tanggal_publish` date DEFAULT NULL,
  `tanggal_selesai` date DEFAULT NULL,
  `foto` varchar(255) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `jadwal_latihan`
--

CREATE TABLE `jadwal_latihan` (
  `id_jadwal` int(11) NOT NULL,
  `id_pelatih` int(11) DEFAULT NULL,
  `id_program` int(11) DEFAULT NULL,
  `id_sanggar` int(11) DEFAULT NULL,
  `hari` varchar(20) DEFAULT NULL,
  `jam_mulai` time DEFAULT NULL,
  `jam_selesai` time DEFAULT NULL,
  `lokasi` varchar(100) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `jadwal_latihan`
--

INSERT INTO `jadwal_latihan` (`id_jadwal`, `id_pelatih`, `id_program`, `id_sanggar`, `hari`, `jam_mulai`, `jam_selesai`, `lokasi`) VALUES
(1, 8, 1, 1, 'Sabtu', '10:00:00', '13:00:00', 'Sanggar Utama'),
(2, 9, 4, 1, 'Sabtu', '13:00:00', '17:00:00', 'Sanggar Utama'),
(3, 9, 2, 1, 'Sabtu', '10:00:00', '13:00:00', 'Sanggar Utama');

-- --------------------------------------------------------

--
-- Table structure for table `kontak`
--

CREATE TABLE `kontak` (
  `id_kontak` int(11) NOT NULL,
  `nama_pengirim` varchar(100) DEFAULT NULL,
  `email` varchar(100) DEFAULT NULL,
  `pesan` text DEFAULT NULL,
  `tanggal_kirim` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `laporan_keuangan`
--

CREATE TABLE `laporan_keuangan` (
  `id_laporan` int(11) NOT NULL,
  `id_user` int(11) DEFAULT NULL,
  `periode` varchar(50) DEFAULT NULL,
  `total_pemasukan` decimal(15,2) DEFAULT NULL,
  `total_pengeluaran` decimal(15,2) DEFAULT NULL,
  `keterangan` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `laporan_keuangan`
--

INSERT INTO `laporan_keuangan` (`id_laporan`, `id_user`, `periode`, `total_pemasukan`, `total_pengeluaran`, `keterangan`) VALUES
(1, 6, 'April 2026', 175000.00, 0.00, 'Pendaftaran Siswa: nana');

-- --------------------------------------------------------

--
-- Table structure for table `migrations`
--

CREATE TABLE `migrations` (
  `id` int(10) UNSIGNED NOT NULL,
  `migration` varchar(255) NOT NULL,
  `batch` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `migrations`
--

INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES
(1, '2026_04_04_120447_create_sessions_table', 1),
(2, '2026_04_08_023624_create_absensi_table', 1),
(3, '2026_04_08_045151_add_dokumen_to_pendaftaran_table', 2),
(4, '2026_04_09_040903_add_username_to_pendaftaran_table', 3),
(5, '2026_04_09_062823_add_slug_and_foto_to_program_kelas_table', 4),
(6, '2026_04_09_074707_add_bukti_bayar_to_pembayaran_table', 5),
(7, '2026_04_10_013056_add_slug_to_informasi_berita_table', 6),
(8, '2026_04_09_074808_create_transactions_table', 7),
(9, '2026_04_10_025838_add_timestamps_to_informasi_berita_table', 8),
(10, '2024_01_01_000000_create_pendaftaran_table', 9),
(11, '2026_04_28_004158_add_email_and_google_id_to_users_table', 1),
(12, '2026_04_29_100853_create_transaksi_iurans_table', 10),
(13, '2026_05_08_021618_add_status_and_keterangan_to_absensi_table', 11),
(14, '2026_05_10_000001_add_tanggal_bayar_to_transaksi_iurans_table', 12),
(15, '2026_05_11_000001_add_tipe_iuran_metode_to_pembayaran_table', 13),
(16, '2026_05_12_084251_add_tanggal_selesai_to_informasi_berita_table', 14),
(17, '2026_05_12_084633_add_durasi_and_sesi_to_program_kelas_table', 15);

-- --------------------------------------------------------

--
-- Table structure for table `password_reset_tokens`
--

CREATE TABLE `password_reset_tokens` (
  `email` varchar(191) NOT NULL,
  `token` varchar(191) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `password_reset_tokens`
--

INSERT INTO `password_reset_tokens` (`email`, `token`, `created_at`) VALUES
('n.fitriani4444@gmail.com', '$2y$12$TFByRnZ4ppf7ZIWT51peMugj7vp6pyD3VxyTcH1rl.VxJpI6tne2q', '2026-04-29 01:34:28');

-- --------------------------------------------------------

--
-- Table structure for table `pelatih`
--

CREATE TABLE `pelatih` (
  `id_pelatih` int(11) NOT NULL,
  `id_sanggar` int(11) DEFAULT NULL,
  `nama_pelatih` varchar(100) DEFAULT NULL,
  `bidang` varchar(50) DEFAULT NULL,
  `no_hp` varchar(20) DEFAULT NULL,
  `alamat` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `pelatih`
--

INSERT INTO `pelatih` (`id_pelatih`, `id_sanggar`, `nama_pelatih`, `bidang`, `no_hp`, `alamat`) VALUES
(6, 1, 'Dimas Patria', 'Seni Tari', '08123456789', 'Bandung'),
(7, 1, 'Lutfi Jamaludin', 'Karawitan', '08123456789', 'Bandung'),
(8, 1, 'Melsa Afrianti', 'Seni Tari', '08123456789', 'Bandung'),
(9, 1, 'Aulia Rizki Wibowo', 'Karawitan', '08123456789', 'Bandung'),
(10, 1, 'Habiburrahman Daniah', 'Seni Tari', '08123456789', 'Bandung');

-- --------------------------------------------------------

--
-- Table structure for table `pembayaran`
--

CREATE TABLE `pembayaran` (
  `id_pembayaran` int(11) NOT NULL,
  `id_user` int(11) DEFAULT NULL,
  `tanggal_bayar` date DEFAULT NULL,
  `bulan` varchar(20) DEFAULT NULL,
  `jumlah` decimal(10,2) DEFAULT NULL,
  `tipe_iuran` varchar(20) DEFAULT NULL,
  `metode_pembayaran` varchar(20) DEFAULT NULL,
  `status` varchar(20) DEFAULT NULL,
  `bukti_bayar` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `pembayaran`
--

INSERT INTO `pembayaran` (`id_pembayaran`, `id_user`, `tanggal_bayar`, `bulan`, `jumlah`, `tipe_iuran`, `metode_pembayaran`, `status`, `bukti_bayar`) VALUES
(2, 12, '2026-05-01', 'May 2026', 150000.00, NULL, NULL, 'Belum Bayar', 'bukti_transfer/ghMkHy2gjY1EqIX3UCgbtfIueti7PVIXvsDNddLl.jpg'),
(3, 27, '2026-05-12', 'May 2026', 175000.00, 'bulanan', 'transfer', 'Lunas', NULL),
(5, 28, '2026-05-10', 'May 2026', 175000.00, 'bulanan', 'transfer', 'Lunas', NULL),
(6, 29, '2026-05-11', 'May 2026', 175000.00, 'bulanan', 'transfer', 'Lunas', NULL),
(7, 32, NULL, 'May 2026', 150000.00, 'bulanan', 'transfer', 'Menunggu Verifikasi', NULL),
(9, 33, '2026-05-11', 'May 2026', 15000.00, 'mingguan', 'tunai', 'Lunas', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `pendaftaran`
--

CREATE TABLE `pendaftaran` (
  `id_pendaftaran` bigint(20) UNSIGNED NOT NULL,
  `id_program` bigint(20) UNSIGNED DEFAULT NULL,
  `username` varchar(191) DEFAULT NULL,
  `email` varchar(191) DEFAULT NULL,
  `nama_calon` varchar(191) NOT NULL,
  `tanggal_lahir` date DEFAULT NULL,
  `tanggal_daftar` date NOT NULL,
  `no_hp` varchar(20) NOT NULL,
  `alamat` text NOT NULL,
  `dokumen` varchar(191) DEFAULT NULL,
  `status` varchar(20) NOT NULL DEFAULT 'Menunggu',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `snap_token` varchar(255) DEFAULT NULL,
  `status_pembayaran` varchar(20) DEFAULT 'pending'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `pendaftaran`
--

INSERT INTO `pendaftaran` (`id_pendaftaran`, `id_program`, `username`, `email`, `nama_calon`, `tanggal_lahir`, `tanggal_daftar`, `no_hp`, `alamat`, `dokumen`, `status`, `created_at`, `updated_at`, `snap_token`, `status_pembayaran`) VALUES
(1, 1, 'nurulfitriani', 'nurul@gmail.com', 'Nurul Fitriani', '2018-04-11', '2026-05-01', '0987654', 'fghjkl', 'pendaftaran/TfJ0lS9G5ETpGHLnfmTTsjtxGHBoxT2uCDXc5Anh.png', 'Disetujui', NULL, NULL, NULL, 'pending'),
(2, 1, 'anandamarcella', 'ananda@gmail.com', 'Ananda Marcella', '2014-06-10', '2026-05-09', '0987654', 'gfd', NULL, 'Disetujui', NULL, NULL, NULL, 'pending'),
(15, 4, 'naelahayati', 'naela@gmail.com', 'Naela hayati', '2017-04-11', '2026-05-10', '09876543', 'lkjhg', NULL, 'Disetujui', NULL, NULL, NULL, 'pending'),
(16, 6, 'vira', 'vira@gmail.com', 'Vira', '2018-02-12', '2026-05-10', '0987654', '0987654', NULL, 'Ditolak', NULL, NULL, NULL, 'pending'),
(17, 5, 'vira', 'vira@gmail.com', 'Vira', '2016-03-12', '2026-05-10', '0987654', 'lkjhgf', NULL, 'Disetujui', NULL, NULL, NULL, 'pending'),
(18, 4, 'miaafriyanti', 'mmauddyy@gmail.com', 'mia afriyanti', '2017-04-11', '2026-05-11', '09876543', 'jhgf', NULL, 'Disetujui', NULL, NULL, NULL, 'pending'),
(19, 4, 'ningning', 'ningning@gmail.com', 'ningning', '2014-12-04', '2026-05-11', '09876543', 'hg', NULL, 'Ditolak', NULL, NULL, NULL, 'pending'),
(20, 4, 'karinaaespa', 'karina@gmail.com', 'karina aespa', '2018-04-11', '2026-05-11', '09876543', 'oiugf', NULL, 'Ditolak', NULL, NULL, NULL, 'pending'),
(21, 2, 'karina', 'karinaaespa@gmail.com', 'karina', '2018-04-11', '2026-05-11', '0987654', 'oiuhgv', NULL, 'Aktif', NULL, NULL, NULL, 'pending'),
(22, 1, 'dealya', 'dealya@gmail.com', 'dealya', '2017-12-13', '2026-05-11', '0987654', 'hgfd', NULL, 'Aktif', NULL, NULL, NULL, 'pending');

-- --------------------------------------------------------

--
-- Table structure for table `prestasi`
--

CREATE TABLE `prestasi` (
  `id_prestasi` int(11) NOT NULL,
  `id_sanggar` int(11) DEFAULT NULL,
  `nama_event` varchar(200) DEFAULT NULL,
  `tahun` year(4) DEFAULT NULL,
  `tingkat` varchar(50) DEFAULT NULL,
  `keterangan` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `program_kelas`
--

CREATE TABLE `program_kelas` (
  `id_program` int(11) NOT NULL,
  `nama_program` varchar(100) DEFAULT NULL,
  `slug` varchar(255) DEFAULT NULL,
  `kategori` varchar(50) DEFAULT NULL,
  `deskripsi` text DEFAULT NULL,
  `foto` varchar(255) DEFAULT NULL,
  `biaya` decimal(10,2) DEFAULT NULL,
  `durasi` varchar(191) DEFAULT NULL,
  `jumlah_sesi` varchar(191) DEFAULT NULL,
  `status` varchar(20) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `program_kelas`
--

INSERT INTO `program_kelas` (`id_program`, `nama_program`, `slug`, `kategori`, `deskripsi`, `foto`, `biaya`, `durasi`, `jumlah_sesi`, `status`) VALUES
(1, 'Tari Tradisional Jaipong', 'tari-tradisional-jaipong', 'Seni Tari', 'Kelas tari jaipong untuk pemula hingga mahir', 'programs/tari tradisional.JPG', 150000.00, NULL, NULL, 'Aktif'),
(2, 'Tari Klasik Sunda', 'tari-klasik-sunda', 'Seni Tari', 'Kelas tari klasik sunda untuk semua usia', 'programs/seni tari.jpg', 150000.00, NULL, NULL, 'Aktif'),
(3, 'Karawitan - Gamelan & Degung', 'karawitan-gamelan-degung', 'Seni Karawitan', 'Belajar memainkan gamelan dan degung Sunda', 'programs/gamelan.jpg', 200000.00, NULL, NULL, 'Aktif'),
(4, 'Karawitan - Kendang', 'karawitan-kendang', 'Seni Karawitan', 'Kelas kendang untuk semua level', 'programs/kendang.jpg', 175000.00, NULL, NULL, 'Aktif'),
(5, 'Karawitan - Vokal Kawih', 'karawitan-vokal-kawih', 'Seni Karawitan', 'Belajar vokal seni kawih Sunda', 'programs/vokal kawih.JPG', 150000.00, NULL, NULL, 'Aktif'),
(6, 'Karawitan - Kacapi', 'karawitan-kacapi', 'Seni Karawitan', 'Belajar memainkan kacapi tradisional Sunda', 'programs/kecapi.png', 175000.00, NULL, NULL, 'Aktif');

-- --------------------------------------------------------

--
-- Table structure for table `sanggar`
--

CREATE TABLE `sanggar` (
  `id_sanggar` int(11) NOT NULL,
  `nama_sanggar` varchar(100) DEFAULT NULL,
  `alamat` text DEFAULT NULL,
  `email` varchar(100) DEFAULT NULL,
  `no_hp` varchar(20) DEFAULT NULL,
  `visi` text DEFAULT NULL,
  `misi` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `sanggar`
--

INSERT INTO `sanggar` (`id_sanggar`, `nama_sanggar`, `alamat`, `email`, `no_hp`, `visi`, `misi`) VALUES
(1, 'Sanggar Goong Prasasti', 'Bandung', NULL, NULL, NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `sessions`
--

CREATE TABLE `sessions` (
  `id` varchar(255) NOT NULL,
  `user_id` bigint(20) UNSIGNED DEFAULT NULL,
  `ip_address` varchar(45) DEFAULT NULL,
  `user_agent` text DEFAULT NULL,
  `payload` longtext NOT NULL,
  `last_activity` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `transactions`
--

CREATE TABLE `transactions` (
  `id_transaction` bigint(20) UNSIGNED NOT NULL,
  `tanggal` date NOT NULL,
  `jenis` varchar(10) NOT NULL,
  `nominal` decimal(15,2) NOT NULL,
  `keterangan` text DEFAULT NULL,
  `id_user_siswa` bigint(20) UNSIGNED DEFAULT NULL,
  `id_user` bigint(20) UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `transactions`
--

INSERT INTO `transactions` (`id_transaction`, `tanggal`, `jenis`, `nominal`, `keterangan`, `id_user_siswa`, `id_user`, `created_at`, `updated_at`) VALUES
(1, '2026-05-10', 'Masuk', 50000.00, 'Iuran Mingguan: naelahayati', NULL, 7, '2026-05-10 06:09:29', '2026-05-10 06:09:29'),
(2, '2026-05-10', 'Masuk', 175000.00, 'Iuran Bulanan: naelahayati (May 2026)', NULL, 3, '2026-05-10 16:43:52', '2026-05-10 16:43:52'),
(3, '2026-05-10', 'Masuk', 175000.00, 'Pembayaran Bulanan: vira (May 2026)', NULL, 7, '2026-05-10 16:54:03', '2026-05-10 16:54:03'),
(4, '2026-05-11', 'Masuk', 175000.00, 'Iuran Bulanan: miaafriyanti (May 2026)', NULL, 29, '2026-05-10 18:59:23', '2026-05-10 18:59:23'),
(5, '2026-05-11', 'Masuk', 150000.00, 'Pendaftaran Siswa: dealya (Tari Tradisional Jaipong)', NULL, 33, '2026-05-11 11:33:53', '2026-05-11 11:33:53'),
(6, '2026-05-11', 'Masuk', 15000.00, 'Pembayaran Bulanan: dealya (May 2026)', NULL, 33, '2026-05-11 12:58:23', '2026-05-11 12:58:23'),
(7, '2026-05-11', 'Keluar', 100000.00, 'beli baju', NULL, 7, '2026-05-11 13:52:20', '2026-05-11 13:52:20'),
(8, '2026-05-12', 'Masuk', 175000.00, 'Pembayaran Bulanan: naelahayati (May 2026)', NULL, 27, '2026-05-12 01:50:44', '2026-05-12 01:50:44');

-- --------------------------------------------------------

--
-- Table structure for table `transaksi_iurans`
--

CREATE TABLE `transaksi_iurans` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `user_id` int(11) NOT NULL,
  `tipe_iuran` enum('mingguan','bulanan') NOT NULL,
  `metode_pembayaran` enum('tunai','transfer') NOT NULL,
  `jumlah_bayar` int(11) NOT NULL,
  `bukti_bayar` varchar(191) DEFAULT NULL,
  `tanggal_bayar` timestamp NULL DEFAULT NULL,
  `status` enum('pending','valid','ditolak') NOT NULL DEFAULT 'pending',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `snap_token` varchar(255) DEFAULT NULL,
  `status_pembayaran` varchar(20) DEFAULT 'pending'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `transaksi_iurans`
--

INSERT INTO `transaksi_iurans` (`id`, `user_id`, `tipe_iuran`, `metode_pembayaran`, `jumlah_bayar`, `bukti_bayar`, `tanggal_bayar`, `status`, `created_at`, `updated_at`, `snap_token`, `status_pembayaran`) VALUES
(1, 12, 'mingguan', 'transfer', 50000, 'bukti_iuran/Gdna0GLsfHHFojbazIIdSwodyiCjdMXx7Dos6SP3.jpg', '2026-05-01 07:20:48', 'valid', '2026-05-01 07:09:19', '2026-05-01 07:20:48', NULL, 'pending'),
(2, 27, 'mingguan', 'transfer', 50000, NULL, '2026-05-10 06:09:29', 'valid', '2026-05-10 04:29:35', '2026-05-10 06:09:29', NULL, 'pending');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id_user` int(11) NOT NULL,
  `username` varchar(50) NOT NULL,
  `nama_lengkap` varchar(255) DEFAULT NULL,
  `email` varchar(191) DEFAULT NULL,
  `password` varchar(255) NOT NULL,
  `google_id` varchar(191) DEFAULT NULL,
  `role` enum('Ketua','Bendahara','Humas','Siswa') NOT NULL,
  `status` varchar(20) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id_user`, `username`, `nama_lengkap`, `email`, `password`, `google_id`, `role`, `status`) VALUES
(1, 'admin_ketua', NULL, 'n.fitriani4444@gmail.com', '$2y$12$aGfzzhCEyp11GzSj9qs7M.qYxcyzogAatl1pO8IqNqNO8BQyj9zDS', '102369711461631714956', 'Ketua', 'Aktif'),
(2, 'admin_humas', NULL, 'ninirubyjanese@gmail.com', '$2y$12$edrJ8WLrhoXYIC80FFsn1OW3yYsIRt0ovU2x4vCroEl/D.FEy9nfC', '108287896722363002050', 'Humas', 'Aktif'),
(3, 'admin_bendahara', NULL, 'kkddyy214@gmail.com\r\n', '$2y$12$Sjy/myOnWM8O66CYXOCcZOtInPJt4dvi7SYtj6Qzkcj8tKh604znu', NULL, 'Bendahara', 'Aktif'),
(5, 'admin_ketua', NULL, NULL, '$2y$12$79H66H1XzWFi3WqZTxo32u3mg7fI4f.lo4vnRwwaqlOYb3dipWifW', NULL, 'Ketua', 'Aktif'),
(6, 'humas_admin', NULL, NULL, '$2y$12$epgPuk5DBulG0R5fCaZ1Z.Jl/6weNxyHkIz/2lJEUeUvVwNWY9zAq', NULL, 'Humas', 'Aktif'),
(7, 'bendahara_admin', NULL, 'kkddyy214@gmail.com', '$2y$12$Y4M7gUxyxPhj3jmsJ1wUBON1MEfplwXv35pUug4E2zXSRGpnYnxLq', '113429379078288514365', 'Bendahara', 'Aktif'),
(12, 'nurulfitriani', NULL, 'nurul@gmail.com', '$2y$12$qa5YzZP3BtU.zmGuUMMqkeILpyJRBcCDI6UDkj1RZqutzQTlYuE0S', NULL, 'Siswa', 'Aktif'),
(27, 'naelahayati', NULL, 'naela@gmail.com', '$2y$12$FWIyl2Dt.d8u405FD8d7q.WLZUdHOGmQ2437hhEpcn7zzbx.Sh6Q6', NULL, 'Siswa', 'Aktif'),
(28, 'vira', NULL, 'vira@gmail.com', '$2y$12$Au7k9vBoazUE9v0AG9COO.LjV7Da82yixdCbEeuuyrf4jJwYf2/Pe', NULL, 'Siswa', 'Aktif'),
(29, 'miaafriyanti', NULL, 'mmauddyy@gmail.com', '$2y$12$6puItt0Tbp0VYA.ouqWumurycOAgGcWbEMoaTKwHkLo68aHI/gm2.', NULL, 'Siswa', 'Aktif'),
(31, 'karinaaespa', NULL, 'karina@gmail.com', '$2y$12$jrlQFsk.oJaH3l8abbTJN.M2SdKHgMiZEqLo/NPlq/mSu5E22SCPi', NULL, 'Siswa', 'Nonaktif'),
(32, 'karina', NULL, 'karinaaespa@gmail.com', '$2y$12$sp/r5Yk420lwR82WluwsmOiRqJHv9drQL4ME0fRtSxAvDElOTtqtu', NULL, 'Siswa', 'Aktif'),
(33, 'dealya', NULL, 'dealya@gmail.com', '$2y$12$.PxR3afO.PUxjH/wMekObOXwBHSWY1LuoYKsQfiGbVc7pZMZDqKDS', NULL, 'Siswa', 'Aktif');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `absensi`
--
ALTER TABLE `absensi`
  ADD PRIMARY KEY (`id_absensi`);

--
-- Indexes for table `galeri`
--
ALTER TABLE `galeri`
  ADD PRIMARY KEY (`id_galeri`),
  ADD KEY `id_sanggar` (`id_sanggar`);

--
-- Indexes for table `informasi_berita`
--
ALTER TABLE `informasi_berita`
  ADD PRIMARY KEY (`id_informasi`),
  ADD UNIQUE KEY `informasi_berita_slug_unique` (`slug`),
  ADD KEY `id_user` (`id_user`);

--
-- Indexes for table `jadwal_latihan`
--
ALTER TABLE `jadwal_latihan`
  ADD PRIMARY KEY (`id_jadwal`),
  ADD KEY `id_pelatih` (`id_pelatih`),
  ADD KEY `id_program` (`id_program`),
  ADD KEY `id_sanggar` (`id_sanggar`);

--
-- Indexes for table `kontak`
--
ALTER TABLE `kontak`
  ADD PRIMARY KEY (`id_kontak`);

--
-- Indexes for table `laporan_keuangan`
--
ALTER TABLE `laporan_keuangan`
  ADD PRIMARY KEY (`id_laporan`),
  ADD KEY `id_user` (`id_user`);

--
-- Indexes for table `migrations`
--
ALTER TABLE `migrations`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `password_reset_tokens`
--
ALTER TABLE `password_reset_tokens`
  ADD PRIMARY KEY (`email`);

--
-- Indexes for table `pelatih`
--
ALTER TABLE `pelatih`
  ADD PRIMARY KEY (`id_pelatih`),
  ADD KEY `id_sanggar` (`id_sanggar`);

--
-- Indexes for table `pembayaran`
--
ALTER TABLE `pembayaran`
  ADD PRIMARY KEY (`id_pembayaran`),
  ADD KEY `id_user` (`id_user`);

--
-- Indexes for table `pendaftaran`
--
ALTER TABLE `pendaftaran`
  ADD PRIMARY KEY (`id_pendaftaran`);

--
-- Indexes for table `prestasi`
--
ALTER TABLE `prestasi`
  ADD PRIMARY KEY (`id_prestasi`),
  ADD KEY `id_sanggar` (`id_sanggar`);

--
-- Indexes for table `program_kelas`
--
ALTER TABLE `program_kelas`
  ADD PRIMARY KEY (`id_program`),
  ADD UNIQUE KEY `program_kelas_slug_unique` (`slug`);

--
-- Indexes for table `sanggar`
--
ALTER TABLE `sanggar`
  ADD PRIMARY KEY (`id_sanggar`);

--
-- Indexes for table `sessions`
--
ALTER TABLE `sessions`
  ADD PRIMARY KEY (`id`),
  ADD KEY `sessions_user_id_index` (`user_id`),
  ADD KEY `sessions_last_activity_index` (`last_activity`);

--
-- Indexes for table `transactions`
--
ALTER TABLE `transactions`
  ADD PRIMARY KEY (`id_transaction`);

--
-- Indexes for table `transaksi_iurans`
--
ALTER TABLE `transaksi_iurans`
  ADD PRIMARY KEY (`id`),
  ADD KEY `transaksi_iurans_user_id_foreign` (`user_id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id_user`),
  ADD UNIQUE KEY `email` (`email`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `absensi`
--
ALTER TABLE `absensi`
  MODIFY `id_absensi` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `galeri`
--
ALTER TABLE `galeri`
  MODIFY `id_galeri` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `informasi_berita`
--
ALTER TABLE `informasi_berita`
  MODIFY `id_informasi` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `jadwal_latihan`
--
ALTER TABLE `jadwal_latihan`
  MODIFY `id_jadwal` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `kontak`
--
ALTER TABLE `kontak`
  MODIFY `id_kontak` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `laporan_keuangan`
--
ALTER TABLE `laporan_keuangan`
  MODIFY `id_laporan` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `migrations`
--
ALTER TABLE `migrations`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=18;

--
-- AUTO_INCREMENT for table `pelatih`
--
ALTER TABLE `pelatih`
  MODIFY `id_pelatih` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `pembayaran`
--
ALTER TABLE `pembayaran`
  MODIFY `id_pembayaran` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT for table `pendaftaran`
--
ALTER TABLE `pendaftaran`
  MODIFY `id_pendaftaran` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=23;

--
-- AUTO_INCREMENT for table `prestasi`
--
ALTER TABLE `prestasi`
  MODIFY `id_prestasi` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `program_kelas`
--
ALTER TABLE `program_kelas`
  MODIFY `id_program` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `sanggar`
--
ALTER TABLE `sanggar`
  MODIFY `id_sanggar` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `transactions`
--
ALTER TABLE `transactions`
  MODIFY `id_transaction` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `transaksi_iurans`
--
ALTER TABLE `transaksi_iurans`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id_user` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=34;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `galeri`
--
ALTER TABLE `galeri`
  ADD CONSTRAINT `galeri_ibfk_1` FOREIGN KEY (`id_sanggar`) REFERENCES `sanggar` (`id_sanggar`);

--
-- Constraints for table `informasi_berita`
--
ALTER TABLE `informasi_berita`
  ADD CONSTRAINT `informasi_berita_ibfk_1` FOREIGN KEY (`id_user`) REFERENCES `users` (`id_user`);

--
-- Constraints for table `jadwal_latihan`
--
ALTER TABLE `jadwal_latihan`
  ADD CONSTRAINT `jadwal_latihan_ibfk_1` FOREIGN KEY (`id_pelatih`) REFERENCES `pelatih` (`id_pelatih`),
  ADD CONSTRAINT `jadwal_latihan_ibfk_2` FOREIGN KEY (`id_program`) REFERENCES `program_kelas` (`id_program`),
  ADD CONSTRAINT `jadwal_latihan_ibfk_3` FOREIGN KEY (`id_sanggar`) REFERENCES `sanggar` (`id_sanggar`);

--
-- Constraints for table `laporan_keuangan`
--
ALTER TABLE `laporan_keuangan`
  ADD CONSTRAINT `laporan_keuangan_ibfk_1` FOREIGN KEY (`id_user`) REFERENCES `users` (`id_user`);

--
-- Constraints for table `pelatih`
--
ALTER TABLE `pelatih`
  ADD CONSTRAINT `pelatih_ibfk_1` FOREIGN KEY (`id_sanggar`) REFERENCES `sanggar` (`id_sanggar`) ON DELETE CASCADE;

--
-- Constraints for table `pembayaran`
--
ALTER TABLE `pembayaran`
  ADD CONSTRAINT `pembayaran_ibfk_1` FOREIGN KEY (`id_user`) REFERENCES `users` (`id_user`);

--
-- Constraints for table `prestasi`
--
ALTER TABLE `prestasi`
  ADD CONSTRAINT `prestasi_ibfk_1` FOREIGN KEY (`id_sanggar`) REFERENCES `sanggar` (`id_sanggar`);

--
-- Constraints for table `transaksi_iurans`
--
ALTER TABLE `transaksi_iurans`
  ADD CONSTRAINT `transaksi_iurans_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id_user`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
