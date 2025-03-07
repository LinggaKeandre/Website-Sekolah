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
-- Table structure for table `tugas`
--

CREATE TABLE `tugas` (
  `id` int NOT NULL,
  `judul` varchar(255) NOT NULL,
  `deskripsi` text NOT NULL,
  `deadline` datetime NOT NULL,
  `mapel` varchar(255) NOT NULL, -- Add mapel column
  `files` text CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `tugas`
--

INSERT INTO `tugas` (`id`, `judul`, `deskripsi`, `deadline`, `mapel`, `files`) VALUES
(44, 'Pengantar Desain UI/UX ', 'ihfiahipahipfhiwf', '2025-03-02 22:54:00', 'Pemrograman Web', '[\"uploads/tugas/67c47de06e2e2.png\"]'),
(45, 'Laskar Pelangi', 'njbggsehtuetuseu', '2025-03-02 22:57:00', 'Bahasa Indonesia', '[]'),
(46, 'Negeri Para Bedebah', 'jgajngoakngonro', '2025-03-03 00:14:00', 'Bahasa Inggris', '[\"uploads/tugas/67c49098e9680.jpg\",\"uploads/tugas/67c49098e99aa.jpeg\",\"uploads/tugas/67c49098ea017.png\"]'),
(47, 'aoefojeabfoeaf', 'jajjgkabwjlgb', '2025-03-03 00:28:00', 'Matematika', '[\"uploads/tugas/67c493f1df745.png\",\"uploads/tugas/67c493f1dfc50.jpeg\",\"uploads/tugas/67c493f1e010c.jpg\"]');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `tugas`
--
ALTER TABLE `tugas`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `tugas`
--
ALTER TABLE `tugas`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=48;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
