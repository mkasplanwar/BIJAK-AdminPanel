-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jan 14, 2025 at 02:34 PM
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
-- Database: `db_bijak`
--

-- --------------------------------------------------------

--
-- Table structure for table `admin`
--

CREATE TABLE `admin` (
  `id` int(11) NOT NULL,
  `username` varchar(50) NOT NULL,
  `password` varchar(255) NOT NULL,
  `nama_lengkap` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `admin`
--

INSERT INTO `admin` (`id`, `username`, `password`, `nama_lengkap`, `email`) VALUES
(1, 'admin', 'admin123', 'Administrator', 'admin@bijak.com');

-- --------------------------------------------------------

--
-- Table structure for table `clients`
--

CREATE TABLE `clients` (
  `id` int(11) NOT NULL,
  `nama_client` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `no_telp` varchar(15) NOT NULL,
  `alamat` text NOT NULL,
  `jenis_bisnis` varchar(50) NOT NULL,
  `tanggal_bergabung` date NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `clients`
--

INSERT INTO `clients` (`id`, `nama_client`, `email`, `no_telp`, `alamat`, `jenis_bisnis`, `tanggal_bergabung`) VALUES
(1, 'M. Kaspul Anwar', 'mkasplanwar@gmail.com', '089649000413', 'Jl. Kelayan Raya, Block B, Nusantara', 'Starbucks', '2025-01-12'),
(2, 'Arsyilla Putri Anwar Elzena', 'arsyilla@gmail.com', '081234567891', 'Jl. Kelayan Raya, Block B, Nusantara', 'McDonald\'s', '2025-01-14'),
(3, 'Sophia Putri Anwar Aliza', 'sophia@gmail.com', '081212129898', 'Jl. Kelayan Raya, Block B, Nusantara', 'Richeese', '2025-01-14'),
(4, 'Fareilla Putri Anwar Elzahra', 'fareilla@gmail.com', '087612344321', 'Jl. Kelayan Raya, Block B, Nusantara', 'KFC', '2025-01-14'),
(5, 'Alicia Putri Anwar Elyana', 'alicia@gmail.com', '081234567654', 'Jl. Kelayan Raya, Block B, Nusantara', 'Alfamart', '2025-01-14'),
(6, 'Muhammad Anwar Al-Banjary', 'anwar@gmail.com', '087645677654', 'Jl. Kelayan Raya, Block B, Nusantara', 'Burger King', '2025-01-14');

-- --------------------------------------------------------

--
-- Table structure for table `consultants`
--

CREATE TABLE `consultants` (
  `id` int(11) NOT NULL,
  `nama_lengkap` varchar(100) NOT NULL,
  `spesialisasi` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `no_telp` varchar(15) NOT NULL,
  `tanggal_bergabung` date NOT NULL,
  `alamat` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `consultants`
--

INSERT INTO `consultants` (`id`, `nama_lengkap`, `spesialisasi`, `email`, `no_telp`, `tanggal_bergabung`, `alamat`) VALUES
(1, 'Dewi Santika', 'Keuangan', 'dewi.santika@bijak.com', '081234567890', '2025-01-01', 'Jl. Kelayan Raya, Block B, Nusantara'),
(2, 'Andi Pratama', 'Pajak', 'andi.pratama@bijak.com', '081298765432', '2025-01-05', 'Jl. Kelayan Raya, Block B, Nusantara'),
(3, 'Siti Aisyah', 'Marketing', 'siti.aisyah@bijak.com', '081312345678', '2025-01-10', 'Jl. Kelayan Raya, Block B, Nusantara'),
(4, 'Budi Hartono', 'Strategi Bisnis', 'budi.hartono@bijak.com', '081334567890', '2025-01-15', 'Jl. Kelayan Raya, Block B, Nusantara'),
(5, 'Nurul Huda', 'Legalitas', 'nurul.huda@bijak.com', '081356789012', '2025-01-20', 'Jl. Kelayan Raya, Block B, Nusantara'),
(6, 'Rizky Fadillah', 'Digitalisasi', 'rizky.fadillah@bijak.com', '081378901234', '2025-01-25', 'Jl. Kelayan Raya, Block B, Nusantara');

-- --------------------------------------------------------

--
-- Table structure for table `schedules`
--

CREATE TABLE `schedules` (
  `id` int(11) NOT NULL,
  `clients_id` int(11) DEFAULT NULL,
  `consultant_id` int(11) NOT NULL,
  `tanggal_konsultasi` datetime NOT NULL,
  `status` enum('dijadwalkan','selesai','dibatalkan') NOT NULL,
  `catatan` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `schedules`
--

INSERT INTO `schedules` (`id`, `clients_id`, `consultant_id`, `tanggal_konsultasi`, `status`, `catatan`) VALUES
(1, 1, 1, '2025-01-30 10:00:00', 'dijadwalkan', 'Diskusi tentang manajemen keuangan bisnis Starbucks'),
(2, 2, 2, '2025-02-01 14:00:00', 'dibatalkan', 'Konsultasi pajak untuk usaha McDonald\'s'),
(3, 3, 3, '2025-02-03 09:30:00', 'dijadwalkan', 'Identifikasi risiko bisnis Richeese'),
(4, 4, 4, '2025-02-05 11:00:00', 'selesai', 'Strategi pengembangan bisnis untuk KFC'),
(5, 5, 5, '2025-02-07 13:00:00', 'selesai', 'Pembuatan dokumen legal untuk Alfamart'),
(6, 6, 6, '2025-02-10 15:00:00', 'dibatalkan', 'Implementasi teknologi digital untuk Burger King');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `admin`
--
ALTER TABLE `admin`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `clients`
--
ALTER TABLE `clients`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `consultants`
--
ALTER TABLE `consultants`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `schedules`
--
ALTER TABLE `schedules`
  ADD PRIMARY KEY (`id`),
  ADD KEY `umkm_id` (`clients_id`),
  ADD KEY `consultant_id` (`consultant_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `admin`
--
ALTER TABLE `admin`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `clients`
--
ALTER TABLE `clients`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT for table `consultants`
--
ALTER TABLE `consultants`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `schedules`
--
ALTER TABLE `schedules`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `schedules`
--
ALTER TABLE `schedules`
  ADD CONSTRAINT `schedules_ibfk_1` FOREIGN KEY (`clients_id`) REFERENCES `clients` (`id`),
  ADD CONSTRAINT `schedules_ibfk_2` FOREIGN KEY (`consultant_id`) REFERENCES `consultants` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
