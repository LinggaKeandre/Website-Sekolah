-- phpMyAdmin SQL Dump
-- version 5.2.2
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Generation Time: Mar 02, 2025 at 06:48 PM
-- Server version: 8.0.30
-- PHP Version: 8.1.10

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `absensi_siswa`
--

-- --------------------------------------------------------

--
-- Table structure for table `submitted_tugas`
--

CREATE TABLE `submitted_tugas` (
  `id` int NOT NULL,
  `tugas_id` int NOT NULL,
  `member_id` int NOT NULL,
  `file` varchar(255) NOT NULL,
  `tanggal` datetime NOT NULL,
  `comment` text,
  `member_comment` text,
  `status` enum('submitted','returned') DEFAULT 'submitted',
  `indikator` enum('tepat waktu','terlambat') DEFAULT 'tepat waktu'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `submitted_tugas`
--

INSERT INTO `submitted_tugas` (`id`, `tugas_id`, `member_id`, `file`, `tanggal`, `comment`, `member_comment`, `status`, `indikator`) VALUES
(54, 44, 1, '[\"uploads/tugas/67c4838666ac5.jpg\",\"uploads/tugas/67c4838666e37.jpg\",\"uploads/tugas/67c483866c64c.jpg\"]', '2025-03-02 23:12:54', 'dgsiegoeshig', 'aeofkzioSiogfiasohgisgoih', 'returned', 'terlambat'),
(55, 45, 1, '[\"uploads/tugas/67c48474013e8.jpg\"]', '2025-03-02 23:16:52', 'afjkagkegjle', 'eihieaothiaehtiahipt', 'returned', 'terlambat'),
(57, 44, 1, '[\"uploads/tugas/67c487300382a.png\",\"uploads/tugas/67c4873003ca1.jpeg\",\"uploads/tugas/67c487300400c.jpg\"]', '2025-03-02 23:28:32', NULL, 'iaewnoginworanawionrhhewjuetuhf', 'submitted', 'terlambat'),
(61, 46, 1, '[\"uploads/tugas/67c490ca15810.jpg\",\"uploads/tugas/67c490ca15c2b.jpg\",\"uploads/tugas/67c490ca15fd4.jpg\"]', '2025-03-03 00:09:30', NULL, 'kgjoawjgjonownog;', 'submitted', 'tepat waktu'),
(62, 45, 1, '[\"uploads/tugas/67c493793737b.png\",\"uploads/tugas/67c493793777e.jpeg\",\"uploads/tugas/67c4937937a85.jpg\"]', '2025-03-03 00:20:57', NULL, 'nfkjsjgbjwglwgwsf', 'submitted', 'terlambat'),
(63, 47, 1, '[\"uploads/tugas/67c495368391e.jpg\"]', '2025-03-03 00:28:22', 'gsgsrgsrg', '', 'returned', 'terlambat');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `submitted_tugas`
--
ALTER TABLE `submitted_tugas`
  ADD PRIMARY KEY (`id`),
  ADD KEY `tugas_id` (`tugas_id`),
  ADD KEY `member_id` (`member_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `submitted_tugas`
--
ALTER TABLE `submitted_tugas`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=64;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `submitted_tugas`
--
ALTER TABLE `submitted_tugas`
  ADD CONSTRAINT `submitted_tugas_ibfk_1` FOREIGN KEY (`tugas_id`) REFERENCES `tugas` (`id`),
  ADD CONSTRAINT `submitted_tugas_ibfk_2` FOREIGN KEY (`member_id`) REFERENCES `members` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
