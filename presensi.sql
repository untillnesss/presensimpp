-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Waktu pembuatan: 05 Agu 2026 pada 01.08
-- Versi server: 8.0.30
-- Versi PHP: 8.1.10

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `presensi`
--

-- --------------------------------------------------------

--
-- Struktur dari tabel `data_presensi`
--

CREATE TABLE `data_presensi` (
  `id_presensi` int NOT NULL,
  `id_user` int NOT NULL,
  `id_instansi` int NOT NULL,
  `tanggal` date NOT NULL,
  `jam_masuk` time NOT NULL,
  `jam_pulang` time NOT NULL,
  `foto_masuk` varchar(250) NOT NULL,
  `foto_pulang` varchar(250) NOT NULL,
  `latitude` decimal(11,8) DEFAULT NULL,
  `longitude` decimal(11,8) DEFAULT NULL,
  `status` enum('izin','cuti','sakit','hadir','tidak hadir','libur','terlambat') CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL,
  `status_pulang` varchar(20) DEFAULT NULL,
  `keterlambatan` int DEFAULT NULL,
  `sumber` enum('gps','manual') DEFAULT 'gps'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data untuk tabel `data_presensi`
--

INSERT INTO `data_presensi` (`id_presensi`, `id_user`, `id_instansi`, `tanggal`, `jam_masuk`, `jam_pulang`, `foto_masuk`, `foto_pulang`, `latitude`, `longitude`, `status`, `status_pulang`, `keterlambatan`, `sumber`) VALUES
(23, 1, 13, '2026-04-20', '00:00:00', '00:00:00', '', '', NULL, NULL, 'izin', NULL, NULL, 'gps'),
(29, 1, 13, '2026-04-21', '17:02:16', '17:20:00', 'masuk_1776765736.png', '', -6.95485300, 112.05484800, 'terlambat', NULL, 22, 'manual'),
(42, 1, 13, '2026-05-05', '13:58:13', '00:00:00', 'masuk_1777964293.png', '', -6.90615413, 112.08027806, 'terlambat', NULL, 9, 'gps'),
(43, 1, 13, '2026-05-06', '14:59:47', '00:00:00', 'masuk_1778054387.png', '', -6.95497300, 112.05514500, 'terlambat', NULL, 420, 'gps'),
(44, 1, 13, '2026-05-09', '00:00:00', '00:00:00', '', '', NULL, NULL, 'izin', NULL, NULL, 'gps'),
(46, 1, 13, '2026-05-16', '00:00:00', '00:00:00', '', '', NULL, NULL, 'izin', NULL, NULL, 'gps');

-- --------------------------------------------------------

--
-- Struktur dari tabel `instansi`
--

CREATE TABLE `instansi` (
  `id_instansi` int NOT NULL,
  `nama_instansi` varchar(100) NOT NULL,
  `status_aktif` tinyint(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data untuk tabel `instansi`
--

INSERT INTO `instansi` (`id_instansi`, `nama_instansi`, `status_aktif`) VALUES
(1, 'KPP PRATAMA TUBAN', 1),
(3, 'BANK JATIM TUBAN', 1),
(4, 'KANTOR POS ', 1),
(5, 'BAPENDA PROV.JATIM', 1),
(6, 'PENGADILAN AGAMA TUBAN', 1),
(7, 'KEJAKSAAN NEGERI TUBAN', 1),
(8, 'DINAS SOSIAL, P3A & PMD KAB. TUBAN', 1),
(9, 'MANDIRI TASPEN TUBAN', 1),
(10, 'DISDUKCAPIL KAB. TUBAN', 1),
(11, 'DINAS KOPUMDAG KAB. TUBAN', 0),
(12, 'BNN TUBAN', 1),
(13, 'DPMPTSP KAB. TUBAN', 1),
(14, 'DISNAKERIN KAB. TUBAN', 1),
(15, 'BPR JATIM CABANG TUBAN', 1),
(16, 'BPKPAD KAB. TUBAN', 1),
(17, 'DINAS PUPR, PRKP KAB. TUBAN', 0),
(18, 'DINKES, P2KB KAB. TUBAN', 1),
(19, 'DLHP KAB. TUBAN', 1),
(20, 'BPJS KESEHATAN TUBAN', 1),
(21, 'BPJS KETENAGAKERJAAN TUBAN', 1),
(22, 'ID EXPRESS', 1);

-- --------------------------------------------------------

--
-- Struktur dari tabel `log_aktivitas`
--

CREATE TABLE `log_aktivitas` (
  `id_log` int NOT NULL,
  `id_user` int DEFAULT NULL,
  `role` enum('admin','pegawai','sekretariat') CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci DEFAULT NULL,
  `aksi` varchar(100) DEFAULT NULL,
  `keterangan` text,
  `created_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data untuk tabel `log_aktivitas`
--

INSERT INTO `log_aktivitas` (`id_log`, `id_user`, `role`, `aksi`, `keterangan`, `created_at`) VALUES
(65, 7, 'admin', 'ACC Pengajuan', 'Menyetujui pengajuan: Cici Silvia Nanda', '2026-04-22 19:27:18'),
(66, 7, 'admin', 'Edit Presensi', 'Edit presensi ID: 34', '2026-04-22 19:35:53'),
(67, 7, 'admin', 'Ubah Setting', 'Admin mengubah jam absensi', '2026-04-22 23:00:41'),
(68, 7, 'admin', 'Ubah Setting', 'Admin mengubah jam absensi', '2026-05-03 20:11:59'),
(69, 7, 'admin', 'Ubah Setting', 'Admin mengubah jam absensi', '2026-05-03 20:15:29'),
(70, 7, 'admin', 'Ubah Setting', 'Admin mengubah jam absensi', '2026-05-03 20:18:22'),
(71, 7, 'admin', 'Tambah Presensi', 'Admin tambah presensi user ID: 1', '2026-05-04 11:32:46'),
(72, 7, 'admin', 'Hapus Presensi', 'Hapus presensi ID: 36', '2026-05-04 11:33:03'),
(73, 7, 'admin', 'Ubah Setting', 'Admin mengubah jam absensi', '2026-05-04 11:35:28'),
(74, 7, 'admin', 'Ubah Setting', 'Admin mengubah jam absensi', '2026-05-04 12:37:10'),
(75, 7, 'admin', 'Ubah Setting', 'Admin mengubah jam absensi', '2026-05-04 18:17:54'),
(76, 7, 'admin', 'Ubah Setting', 'Admin mengubah jam absensi', '2026-05-05 06:32:00'),
(77, 7, 'admin', 'Ubah Setting', 'Admin mengubah jam absensi', '2026-05-05 06:32:02'),
(78, 7, 'admin', 'Tambah Presensi Masuk', 'Absen masuk manual: Adinda Rahma (No.ID: 12345678)', '2026-05-05 06:33:48'),
(79, 7, 'admin', 'Hapus Presensi', 'Hapus presensi ID: 37', '2026-05-05 06:59:52'),
(80, 7, 'admin', 'Ubah Setting', 'Admin mengubah jam absensi', '2026-05-05 07:00:17'),
(81, 7, 'admin', 'Tambah Presensi Masuk', 'Absen masuk manual: Adinda Rahma (No.ID: 12345678)', '2026-05-05 07:06:59'),
(82, 7, 'admin', 'Hapus Presensi', 'Hapus presensi ID: 38', '2026-05-05 07:07:52'),
(83, 7, 'admin', 'Tambah Presensi Masuk', 'Absen masuk manual: Cici Silvia Nanda (No.ID: 1412220009)', '2026-05-05 07:11:38'),
(84, 7, 'admin', 'Hapus Presensi', 'Hapus presensi ID: 39', '2026-05-05 07:40:12'),
(85, 7, 'admin', 'Ubah Setting', 'Admin mengubah jam absensi', '2026-05-05 07:40:36'),
(86, 7, 'admin', 'Tambah Presensi Masuk', 'Absen masuk manual: Cici Silvia Nanda (No.ID: 1412220009)', '2026-05-05 07:44:16'),
(87, 7, 'admin', 'Hapus Presensi', 'Hapus presensi ID: 40', '2026-05-05 07:55:47'),
(88, 7, 'admin', 'ACC Pengajuan', 'Menyetujui pengajuan: Cici Silvia Nanda', '2026-05-05 13:31:04'),
(89, 7, 'admin', 'Hapus Presensi', 'Hapus presensi ID: 41', '2026-05-05 13:33:38'),
(90, 7, 'admin', 'Ubah Setting', 'Admin mengubah jam absensi', '2026-05-05 13:55:14'),
(91, 7, 'admin', 'Ubah Setting', 'Admin mengubah jam absensi', '2026-05-05 13:56:27'),
(92, 7, 'admin', 'Ubah Setting', 'Admin mengubah jam masuk: 08:00', '2026-05-06 10:17:01'),
(93, 7, 'admin', 'Ubah Setting', 'Jam masuk: 08:00 | Koordinat: (-6.954326,112.055769) | Radius: 100m', '2026-05-06 14:59:01'),
(94, 7, 'admin', 'Reset Device', 'Reset device: Cici Silvia Nanda', '2026-05-09 10:55:57'),
(95, 7, 'admin', 'Ubah Setting', 'Jam masuk: 08:00 | Koordinat: (-6.906033,112.080516) | Radius: 100m', '2026-05-09 11:30:14'),
(96, 7, 'admin', 'ACC Pengajuan', 'Menyetujui pengajuan: Cici Silvia Nanda', '2026-05-09 11:31:45'),
(97, 7, 'admin', 'Ubah Setting', 'Jam masuk: 08:00 | Koordinat: (-6.955186,112.054960) | Radius: 100m', '2026-05-10 15:38:19'),
(98, 7, 'admin', 'Ubah Setting', 'Jam masuk: 08:00 | Koordinat: (-6.955186,112.054960) | Radius: 100m', '2026-05-10 15:38:23'),
(99, 7, 'admin', 'Hapus Presensi', 'Hapus presensi ID: 45', '2026-05-10 15:39:34'),
(100, 7, 'admin', 'Reset Device', 'Reset device: Cici Silvia Nanda', '2026-05-16 13:35:50'),
(101, 7, 'admin', 'ACC Pengajuan', 'Menyetujui pengajuan: Cici Silvia Nanda', '2026-05-16 13:52:45'),
(102, 7, 'admin', 'Reset Device', 'Reset device: Cici Silvia Nanda', '2026-05-29 11:37:06'),
(103, 7, 'admin', 'Tambah Presensi Masuk', 'Absen masuk manual: Cici Silvia Nanda (No.ID: 1412220009)', '2026-07-26 11:58:51'),
(104, 7, 'admin', 'Reset Device', 'Reset device: Cici Silvia Nanda', '2026-07-26 12:01:44'),
(105, 7, 'admin', 'Tambah Presensi Masuk', 'Absen masuk manual: Cici Silvia Nanda (No.ID: 1412220009)', '2026-07-26 12:32:45'),
(106, 7, 'admin', 'Tolak Akun', 'Menolak akun: jalang875@gmail.com', '2026-07-29 14:19:33'),
(107, 7, 'admin', 'Reset Device', 'Reset device: Cici Silvia Nanda', '2026-07-29 14:19:45');

-- --------------------------------------------------------

--
-- Struktur dari tabel `otp`
--

CREATE TABLE `otp` (
  `id_otp` int NOT NULL,
  `id_user` int NOT NULL,
  `kode_otp` varchar(6) NOT NULL,
  `expired_at` datetime NOT NULL,
  `is_used` tinyint(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data untuk tabel `otp`
--

INSERT INTO `otp` (`id_otp`, `id_user`, `kode_otp`, `expired_at`, `is_used`) VALUES
(18, 1, '242412', '2026-04-10 12:03:16', 1),
(19, 1, '442671', '2026-04-10 12:27:12', 1),
(22, 1, '772445', '2026-04-21 18:16:37', 0),
(23, 7, '488562', '2026-04-22 18:19:39', 1),
(24, 1, '314884', '2026-04-22 19:34:15', 0),
(25, 1, '221043', '2026-04-22 19:34:48', 0),
(26, 1, '877888', '2026-04-22 19:34:57', 0),
(27, 7, '933739', '2026-06-03 15:56:23', 0),
(28, 7, '292077', '2026-06-03 15:59:36', 1),
(29, 1, '104049', '2026-06-03 16:02:59', 1),
(30, 8, '801192', '2026-06-03 16:04:27', 1),
(31, 7, '262317', '2026-07-26 11:50:27', 1),
(32, 1, '893991', '2026-07-26 12:05:51', 1),
(33, 8, '877154', '2026-07-26 12:53:10', 1),
(35, 14, '346522', '2026-07-29 14:25:45', 0),
(36, 14, '417640', '2026-07-29 14:27:46', 0);

-- --------------------------------------------------------

--
-- Struktur dari tabel `pengajuan`
--

CREATE TABLE `pengajuan` (
  `id_pengajuan` int NOT NULL,
  `id_user` int NOT NULL,
  `id_instansi` int NOT NULL,
  `tanggal_mulai` date NOT NULL,
  `tanggal_selesai` date NOT NULL,
  `jenis` enum('izin') CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL,
  `keterangan` text NOT NULL,
  `file_bukti` varchar(200) DEFAULT NULL,
  `status_pengajuan` enum('disetujui','ditolak','menunggu') CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL,
  `created_at` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data untuk tabel `pengajuan`
--

INSERT INTO `pengajuan` (`id_pengajuan`, `id_user`, `id_instansi`, `tanggal_mulai`, `tanggal_selesai`, `jenis`, `keterangan`, `file_bukti`, `status_pengajuan`, `created_at`) VALUES
(27, 1, 13, '2026-04-22', '2026-04-22', 'izin', 'Seminar di kantor pusat', NULL, 'disetujui', '2026-04-22 19:22:48'),
(30, 1, 13, '2026-05-09', '2026-05-10', 'izin', 'workshop', 'bukti_1778300821_959.jpg', 'disetujui', '2026-05-09 11:27:01'),
(31, 1, 13, '2026-05-16', '2026-05-16', 'izin', 'Workshop di kantor pusat', 'bukti_1778914328_486.jpg', 'disetujui', '2026-05-16 13:52:08');

-- --------------------------------------------------------

--
-- Struktur dari tabel `profil`
--

CREATE TABLE `profil` (
  `id_profil` int NOT NULL,
  `id_user` int NOT NULL,
  `no_id` varchar(30) NOT NULL,
  `nama` varchar(100) NOT NULL,
  `jabatan` varchar(50) NOT NULL,
  `id_instansi` int NOT NULL,
  `foto` varchar(250) NOT NULL,
  `update_at` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data untuk tabel `profil`
--

INSERT INTO `profil` (`id_profil`, `id_user`, `no_id`, `nama`, `jabatan`, `id_instansi`, `foto`, `update_at`) VALUES
(1, 1, '1412220009', 'Cici Silvia Nanda', 'Staff', 13, '1769884757_f26c017bf531ad084133.jpg', '2026-05-05 13:53:06'),
(2, 7, '12345678', 'Riva', 'Admin', 13, '1776665730_ea84fbb94e9843b90eed.jpg', '2026-04-20 13:15:30'),
(4, 8, '111222333', 'Adnan', 'Sekretariat', 13, '1776701197_b790040ca1f5cac1e8dc.jpg', '2026-04-20 23:06:37'),
(6, 12, '1122334455', 'Anis Yuli', 'Staff', 20, '', '2026-05-06 15:10:28'),
(8, 14, '88888888', 'Riva', 'Staff', 1, '', '2026-07-29 14:20:45');

-- --------------------------------------------------------

--
-- Struktur dari tabel `setting_absen`
--

CREATE TABLE `setting_absen` (
  `id_setting` int NOT NULL,
  `jam_masuk_mulai` time NOT NULL,
  `jam_masuk_selesai` time NOT NULL,
  `batas_terlambat` time NOT NULL,
  `jam_pulang_mulai` time NOT NULL,
  `jam_pulang_selesai` time NOT NULL,
  `latitude` decimal(11,8) DEFAULT NULL,
  `longitude` decimal(11,8) DEFAULT NULL,
  `radius` int NOT NULL DEFAULT '100',
  `update_at` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data untuk tabel `setting_absen`
--

INSERT INTO `setting_absen` (`id_setting`, `jam_masuk_mulai`, `jam_masuk_selesai`, `batas_terlambat`, `jam_pulang_mulai`, `jam_pulang_selesai`, `latitude`, `longitude`, `radius`, `update_at`) VALUES
(1, '08:00:00', '08:00:00', '08:20:00', '16:00:00', '17:00:00', -6.95518600, 112.05496000, 100, '2026-05-10 15:38:23');

-- --------------------------------------------------------

--
-- Struktur dari tabel `user`
--

CREATE TABLE `user` (
  `id_user` int NOT NULL,
  `email` varchar(100) NOT NULL,
  `password` varchar(100) NOT NULL,
  `role` enum('admin','pegawai','sekretariat') CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL,
  `is_active` tinyint(1) NOT NULL,
  `email_verified` tinyint(1) NOT NULL DEFAULT '0',
  `device_token` varchar(100) DEFAULT NULL,
  `created_at` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data untuk tabel `user`
--

INSERT INTO `user` (`id_user`, `email`, `password`, `role`, `is_active`, `email_verified`, `device_token`, `created_at`) VALUES
(1, 'silviananda266@gmail.com', '$2y$10$DaPMXgKpzEhtLphcH8Xh2.776U7H8QdbnwaWnhqsLOvyds7zG/MQq', 'pegawai', 1, 1, NULL, '2026-01-28 05:31:39'),
(7, 'cicinanda335@gmail.com', '$2y$10$a7InicedV4DZO1r173OV2.JdyPZLg6UwCKQlLI36NRPIl42R37aMe', 'admin', 1, 1, NULL, '2026-04-02 07:47:48'),
(8, 'presensi.mpp@gmail.com', '$2y$10$lR/F//zPnkHKGfsjR6cPUuhJcs7GPNWZFtnPal6SOPI6BNwmfRam.', 'sekretariat', 1, 1, NULL, '2026-04-20 07:27:16'),
(12, 'anisnurida123.com@gmail.com', '$2y$10$Abk2HDXGyfCT4GIBoJJ3E.DHhQmD1hB0bFIkYYzdH7SR9XpI0RO.m', 'pegawai', 0, 0, NULL, '2026-05-06 15:10:28'),
(14, 'jalang875@gmail.com', '$2y$10$2fmu2kUu5JTy.TSjG6vtyOu0PSt1X83cBSbbnFhO0G4tVNYxicFGy', 'pegawai', 0, 0, NULL, '2026-07-29 14:20:45');

--
-- Indexes for dumped tables
--

--
-- Indeks untuk tabel `data_presensi`
--
ALTER TABLE `data_presensi`
  ADD PRIMARY KEY (`id_presensi`),
  ADD KEY `id_instansi` (`id_instansi`),
  ADD KEY `id_user` (`id_user`);

--
-- Indeks untuk tabel `instansi`
--
ALTER TABLE `instansi`
  ADD PRIMARY KEY (`id_instansi`);

--
-- Indeks untuk tabel `log_aktivitas`
--
ALTER TABLE `log_aktivitas`
  ADD PRIMARY KEY (`id_log`),
  ADD KEY `id_user` (`id_user`);

--
-- Indeks untuk tabel `otp`
--
ALTER TABLE `otp`
  ADD PRIMARY KEY (`id_otp`),
  ADD KEY `id_user` (`id_user`);

--
-- Indeks untuk tabel `pengajuan`
--
ALTER TABLE `pengajuan`
  ADD PRIMARY KEY (`id_pengajuan`),
  ADD KEY `id_instansi` (`id_instansi`),
  ADD KEY `id_user` (`id_user`);

--
-- Indeks untuk tabel `profil`
--
ALTER TABLE `profil`
  ADD PRIMARY KEY (`id_profil`),
  ADD UNIQUE KEY `uq_profil_no_id` (`no_id`),
  ADD KEY `id_instansi` (`id_instansi`),
  ADD KEY `id_user` (`id_user`);

--
-- Indeks untuk tabel `setting_absen`
--
ALTER TABLE `setting_absen`
  ADD PRIMARY KEY (`id_setting`);

--
-- Indeks untuk tabel `user`
--
ALTER TABLE `user`
  ADD PRIMARY KEY (`id_user`);

--
-- AUTO_INCREMENT untuk tabel yang dibuang
--

--
-- AUTO_INCREMENT untuk tabel `data_presensi`
--
ALTER TABLE `data_presensi`
  MODIFY `id_presensi` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=49;

--
-- AUTO_INCREMENT untuk tabel `instansi`
--
ALTER TABLE `instansi`
  MODIFY `id_instansi` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=23;

--
-- AUTO_INCREMENT untuk tabel `log_aktivitas`
--
ALTER TABLE `log_aktivitas`
  MODIFY `id_log` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=108;

--
-- AUTO_INCREMENT untuk tabel `otp`
--
ALTER TABLE `otp`
  MODIFY `id_otp` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=37;

--
-- AUTO_INCREMENT untuk tabel `pengajuan`
--
ALTER TABLE `pengajuan`
  MODIFY `id_pengajuan` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=33;

--
-- AUTO_INCREMENT untuk tabel `profil`
--
ALTER TABLE `profil`
  MODIFY `id_profil` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT untuk tabel `setting_absen`
--
ALTER TABLE `setting_absen`
  MODIFY `id_setting` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT untuk tabel `user`
--
ALTER TABLE `user`
  MODIFY `id_user` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;

--
-- Ketidakleluasaan untuk tabel pelimpahan (Dumped Tables)
--

--
-- Ketidakleluasaan untuk tabel `data_presensi`
--
ALTER TABLE `data_presensi`
  ADD CONSTRAINT `data_presensi_ibfk_2` FOREIGN KEY (`id_instansi`) REFERENCES `instansi` (`id_instansi`) ON DELETE RESTRICT ON UPDATE RESTRICT,
  ADD CONSTRAINT `data_presensi_ibfk_3` FOREIGN KEY (`id_user`) REFERENCES `user` (`id_user`) ON DELETE RESTRICT ON UPDATE RESTRICT;

--
-- Ketidakleluasaan untuk tabel `log_aktivitas`
--
ALTER TABLE `log_aktivitas`
  ADD CONSTRAINT `log_aktivitas_ibfk_1` FOREIGN KEY (`id_user`) REFERENCES `user` (`id_user`) ON DELETE RESTRICT ON UPDATE RESTRICT;

--
-- Ketidakleluasaan untuk tabel `otp`
--
ALTER TABLE `otp`
  ADD CONSTRAINT `otp_ibfk_1` FOREIGN KEY (`id_user`) REFERENCES `user` (`id_user`) ON DELETE RESTRICT ON UPDATE RESTRICT;

--
-- Ketidakleluasaan untuk tabel `pengajuan`
--
ALTER TABLE `pengajuan`
  ADD CONSTRAINT `id_instansi` FOREIGN KEY (`id_instansi`) REFERENCES `instansi` (`id_instansi`) ON DELETE RESTRICT ON UPDATE RESTRICT,
  ADD CONSTRAINT `pengajuan_ibfk_1` FOREIGN KEY (`id_user`) REFERENCES `user` (`id_user`) ON DELETE RESTRICT ON UPDATE RESTRICT;

--
-- Ketidakleluasaan untuk tabel `profil`
--
ALTER TABLE `profil`
  ADD CONSTRAINT `profil_ibfk_2` FOREIGN KEY (`id_instansi`) REFERENCES `instansi` (`id_instansi`) ON DELETE RESTRICT ON UPDATE RESTRICT,
  ADD CONSTRAINT `profil_ibfk_3` FOREIGN KEY (`id_user`) REFERENCES `user` (`id_user`) ON DELETE RESTRICT ON UPDATE RESTRICT;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
