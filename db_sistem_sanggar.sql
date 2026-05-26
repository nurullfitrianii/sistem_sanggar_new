DROP DATABASE IF EXISTS db_sistem_sanggar;
CREATE DATABASE db_sistem_sanggar
  CHARACTER SET utf8mb4
  COLLATE utf8mb4_unicode_ci;

USE db_sistem_sanggar;

-- Tabel users
CREATE TABLE users (
  id BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
  name VARCHAR(100) NOT NULL,
  email VARCHAR(100) NOT NULL,
  email_verified_at TIMESTAMP NULL DEFAULT NULL,
  password VARCHAR(255) NOT NULL,
  role ENUM('ketua','humas','bendahara','anggota') NOT NULL,
  status ENUM('aktif','nonaktif') NOT NULL DEFAULT 'aktif',
  remember_token VARCHAR(100) DEFAULT NULL,
  created_at TIMESTAMP NULL DEFAULT NULL,
  updated_at TIMESTAMP NULL DEFAULT NULL,
  PRIMARY KEY (id),
  UNIQUE KEY users_email_unique (email)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Tabel kelas
CREATE TABLE kelas (
  id BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
  nama VARCHAR(100) NOT NULL,
  jenis ENUM('vokal','kecapi','kendang','tari_jaipong','suling') NOT NULL,
  deskripsi TEXT DEFAULT NULL,
  kapasitas INT UNSIGNED DEFAULT NULL,
  created_at TIMESTAMP NULL DEFAULT NULL,
  updated_at TIMESTAMP NULL DEFAULT NULL,
  PRIMARY KEY (id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Tabel jadwal_kelas
CREATE TABLE jadwal_kelas (
  id BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
  kelas_id BIGINT UNSIGNED NOT NULL,
  hari ENUM('senin','selasa','rabu','kamis','jumat','sabtu','minggu') NOT NULL,
  jam_mulai TIME NOT NULL,
  jam_selesai TIME NOT NULL,
  pengajar VARCHAR(100) DEFAULT NULL,
  lokasi VARCHAR(150) DEFAULT NULL,
  created_at TIMESTAMP NULL DEFAULT NULL,
  updated_at TIMESTAMP NULL DEFAULT NULL,
  PRIMARY KEY (id),
  KEY idx_jadwal_kelas_kelas_id (kelas_id),
  CONSTRAINT fk_jadwal_kelas_kelas
    FOREIGN KEY (kelas_id) REFERENCES kelas (id)
    ON DELETE CASCADE
    ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Tabel absensi
CREATE TABLE absensi (
  id BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
  jadwal_id BIGINT UNSIGNED NOT NULL,
  user_id BIGINT UNSIGNED NOT NULL,
  tanggal DATE NOT NULL,
  status ENUM('hadir','izin','sakit','alpha') NOT NULL,
  keterangan TEXT DEFAULT NULL,
  created_at TIMESTAMP NULL DEFAULT NULL,
  updated_at TIMESTAMP NULL DEFAULT NULL,
  PRIMARY KEY (id),
  UNIQUE KEY uniq_absensi (jadwal_id, user_id, tanggal),
  KEY idx_absensi_user_id (user_id),
  CONSTRAINT fk_absensi_jadwal
    FOREIGN KEY (jadwal_id) REFERENCES jadwal_kelas (id)
    ON DELETE CASCADE
    ON UPDATE CASCADE,
  CONSTRAINT fk_absensi_user
    FOREIGN KEY (user_id) REFERENCES users (id)
    ON DELETE CASCADE
    ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Tabel transactions (keuangan)
CREATE TABLE transactions (
  id BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
  tanggal DATE NOT NULL,
  jenis ENUM('pemasukan','pengeluaran') NOT NULL,
  kategori VARCHAR(100) DEFAULT NULL,
  nominal DECIMAL(15,2) NOT NULL,
  keterangan TEXT DEFAULT NULL,
  created_by BIGINT UNSIGNED DEFAULT NULL,
  created_at TIMESTAMP NULL DEFAULT NULL,
  updated_at TIMESTAMP NULL DEFAULT NULL,
  PRIMARY KEY (id),
  KEY idx_transactions_created_by (created_by),
  CONSTRAINT fk_transactions_user
    FOREIGN KEY (created_by) REFERENCES users (id)
    ON DELETE SET NULL
    ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Tabel programs
CREATE TABLE programs (
  id BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
  judul VARCHAR(150) NOT NULL,
  slug VARCHAR(160) NOT NULL,
  deskripsi TEXT NOT NULL,
  gambar VARCHAR(255) DEFAULT NULL,
  is_active TINYINT(1) NOT NULL DEFAULT 1,
  created_at TIMESTAMP NULL DEFAULT NULL,
  updated_at TIMESTAMP NULL DEFAULT NULL,
  PRIMARY KEY (id),
  UNIQUE KEY programs_slug_unique (slug)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Tabel galleries
CREATE TABLE galleries (
  id BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
  judul VARCHAR(150) NOT NULL,
  deskripsi TEXT DEFAULT NULL,
  gambar VARCHAR(255) NOT NULL,
  tanggal DATE DEFAULT NULL,
  created_at TIMESTAMP NULL DEFAULT NULL,
  updated_at TIMESTAMP NULL DEFAULT NULL,
  PRIMARY KEY (id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Tabel posts (berita)
CREATE TABLE posts (
  id BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
  judul VARCHAR(150) NOT NULL,
  slug VARCHAR(160) NOT NULL,
  ringkasan VARCHAR(255) DEFAULT NULL,
  isi TEXT NOT NULL,
  gambar VARCHAR(255) DEFAULT NULL,
  status ENUM('draft','published') NOT NULL DEFAULT 'draft',
  published_at TIMESTAMP NULL DEFAULT NULL,
  created_by BIGINT UNSIGNED DEFAULT NULL,
  created_at TIMESTAMP NULL DEFAULT NULL,
  updated_at TIMESTAMP NULL DEFAULT NULL,
  PRIMARY KEY (id),
  UNIQUE KEY posts_slug_unique (slug),
  KEY idx_posts_created_by (created_by),
  CONSTRAINT fk_posts_user
    FOREIGN KEY (created_by) REFERENCES users (id)
    ON DELETE SET NULL
    ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Tabel contact_messages
CREATE TABLE contact_messages (
  id BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
  nama VARCHAR(100) NOT NULL,
  email VARCHAR(100) NOT NULL,
  subject VARCHAR(150) DEFAULT NULL,
  pesan TEXT NOT NULL,
  is_read TINYINT(1) NOT NULL DEFAULT 0,
  created_at TIMESTAMP NULL DEFAULT NULL,
  updated_at TIMESTAMP NULL DEFAULT NULL,
  PRIMARY KEY (id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO users (name, email, password, role, status, created_at, updated_at) VALUES
('Ketua Sanggar', 'ketua@sanggar.test', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'ketua', 'aktif', NOW(), NOW()),
('Humas Sanggar', 'humas@sanggar.test', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'humas', 'aktif', NOW(), NOW()),
('Bendahara Sanggar', 'bendahara@sanggar.test', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'bendahara', 'aktif', NOW(), NOW()),
('Anggota A', 'anggota1@sanggar.test', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'anggota', 'aktif', NOW(), NOW()),
('Anggota B', 'anggota2@sanggar.test', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'anggota', 'nonaktif', NOW(), NOW());

-- Data dummy kelas
INSERT INTO kelas (nama, jenis, deskripsi, kapasitas, created_at, updated_at) VALUES
('Kelas Vokal Dasar', 'vokal', 'Kelas ilustratif untuk pembinaan teknik vokal tradisional tingkat dasar.', 20, NOW(), NOW()),
('Kelas Tari Jaipong Pemula', 'tari_jaipong', 'Kegiatan latihan gerak dasar Jaipong secara bertahap dan terukur.', 25, NOW(), NOW()),
('Kelas Musik Kecapi', 'kecapi', 'Pembelajaran pengenalan kecapi dan pola irama sederhana.', 15, NOW(), NOW());

-- Data dummy jadwal_kelas
INSERT INTO jadwal_kelas (kelas_id, hari, jam_mulai, jam_selesai, pengajar, lokasi, created_at, updated_at) VALUES
(1, 'senin', '18:00:00', '20:00:00', 'Pelatih Vokal Ilustrasi', 'Studio Vokal 1', NOW(), NOW()),
(2, 'rabu', '17:00:00', '19:00:00', 'Pelatih Tari Ilustrasi', 'Ruang Tari Utama', NOW(), NOW()),
(3, 'sabtu', '10:00:00', '12:00:00', 'Pelatih Musik Ilustrasi', 'Studio Musik 2', NOW(), NOW());

-- Data dummy absensi
INSERT INTO absensi (jadwal_id, user_id, tanggal, status, keterangan, created_at, updated_at) VALUES
(1, 4, CURDATE(), 'hadir', 'Hadir tepat waktu untuk sesi latihan vokal.', NOW(), NOW()),
(2, 4, CURDATE(), 'izin', 'Izin mengikuti kegiatan keluarga.', NOW(), NOW()),
(3, 5, CURDATE(), 'alpha', 'Tidak ada konfirmasi kehadiran.', NOW(), NOW());

-- Data dummy transactions
INSERT INTO transactions (tanggal, jenis, kategori, nominal, keterangan, created_by, created_at, updated_at) VALUES
(CURDATE(), 'pemasukan', 'Iuran Bulanan', 500000.00, 'Iuran bulanan anggota periode berjalan (data ilustratif).', 3, NOW(), NOW()),
(CURDATE(), 'pengeluaran', 'Perawatan Kostum', 250000.00, 'Biaya perawatan kostum tari dan aksesoris (data ilustratif).', 3, NOW(), NOW());

-- Data dummy programs
INSERT INTO programs (judul, slug, deskripsi, gambar, is_active, created_at, updated_at) VALUES
('Program Pembinaan Tari Tradisional', 'program-pembinaan-tari-tradisional',
 'Program berjenjang yang memfasilitasi peserta untuk mempelajari ragam tari tradisional dengan pendekatan pedagogis yang sistematis.',
 'https://images.unsplash.com/photo-1518834633479-74c184cb92b3?auto=format&fit=crop&w=900&q=80',
 1, NOW(), NOW()),
('Program Musik Iringan Nusantara', 'program-musik-iringan-nusantara',
 'Kegiatan pelatihan musik pengiring tari dan upacara adat yang disusun agar mudah diikuti oleh peserta dari berbagai latar belakang.',
 'https://images.unsplash.com/photo-1551488831-00ddcb6c6bd3?auto=format&fit=crop&w=900&q=80',
 1, NOW(), NOW());

-- Data dummy galleries
INSERT INTO galleries (judul, deskripsi, gambar, tanggal, created_at, updated_at) VALUES
('Ilustrasi Pementasan Malam Budaya', 'Dokumentasi pementasan seni tradisional dengan komposisi panggung yang formal dan rapi.',
 'https://images.unsplash.com/photo-1514890547357-a9ee288728e0?auto=format&fit=crop&w=900&q=80',
 CURDATE(), NOW(), NOW()),
('Ilustrasi Sesi Latihan Berkala', 'Kegiatan latihan berkala di studio sanggar dengan suasana kondusif dan terarah.',
 'https://images.unsplash.com/photo-1512428559087-560fa5ceab42?auto=format&fit=crop&w=900&q=80',
 CURDATE(), NOW(), NOW());

-- Data dummy posts (berita)
INSERT INTO posts (judul, slug, ringkasan, isi, gambar, status, published_at, created_by, created_at, updated_at) VALUES
('Pengumuman Kegiatan Ilustratif Sanggar', 'pengumuman-kegiatan-ilustratif-sanggar',
 'Pemberitahuan resmi mengenai contoh kegiatan internal sanggar yang dapat disesuaikan dengan kebutuhan nyata.',
 'Isi berita ini merupakan contoh deskriptif yang menjelaskan pola komunikasi resmi sanggar kepada anggota dan mitra. Seluruh kalimat bersifat fiktif dan dapat diganti sesuai informasi aktual.',
 'https://images.unsplash.com/photo-1518837695005-2083093ee35b?auto=format&fit=crop&w=900&q=80',
'published', NOW(), 2, NOW(), NOW()),
('Rencana Pentas Ilustratif Semester Ini', 'rencana-pentas-ilustratif-semester-ini',
 'Gambaran umum mengenai rencana pementasan yang disusun secara sistematis sebagai contoh perencanaan program.',
 'Konten ini menyajikan pola penjadwalan dan koordinasi pementasan seni tradisional sebagai referensi, tanpa merujuk pada kegiatan aktual tertentu.',
 NULL,
'published', NOW(), 2, NOW(), NOW());

-- Data dummy contact_messages
INSERT INTO contact_messages (nama, email, subject, pesan, is_read, created_at, updated_at) VALUES
('Calon Orang Tua Peserta', 'orangtua.contoh@example.com', 'Permohonan Informasi Program Latihan',
 'Yth. Pengelola Sanggar, melalui pesan ini saya menyampaikan permohonan informasi resmi terkait jadwal, biaya, dan ketentuan pendaftaran peserta baru. Mohon kiranya dapat diberikan penjelasan secara ringkas dan jelas. Terima kasih.',
 0, NOW(), NOW());

