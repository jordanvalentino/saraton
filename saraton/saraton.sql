-- phpMyAdmin SQL Dump
-- version 4.7.0
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: May 20, 2019 at 07:12 PM
-- Server version: 10.1.25-MariaDB
-- PHP Version: 7.1.7

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `saraton`
--

-- --------------------------------------------------------

--
-- Table structure for table `kamus`
--

CREATE TABLE `kamus` (
  `id` int(11) NOT NULL,
  `kalimat` varchar(500) NOT NULL,
  `kategori` varchar(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `kamus`
--

INSERT INTO `kamus` (`id`, `kalimat`, `kategori`) VALUES
(1, 'Ooo, dasar china', 'sara'),
(2, 'Islam teroris', 'sara'),
(3, 'Islam radikal', 'sara'),
(4, 'Ooo, dasar kulit putih', 'sara'),
(5, 'Hey kamu penganut agama teroris', ''),
(6, 'Hey kamu pemakan babi', 'sara'),
(7, 'Hey kamu kulit hitam', 'sara'),
(8, 'Dasar kafir', 'sara'),
(9, 'Hey kamu wana', 'sara'),
(10, 'Eh ada si mata sipit', ''),
(11, 'Ajaran agamamu itu sesat', 'sara'),
(12, 'Tidak ada agama di dunia ini yang lebih benar daripada agamaku', 'sara'),
(13, 'dasar cina matre', 'sara'),
(14, 'dasar orang pribumi goblok', 'sara'),
(15, 'Dasar wana kamu anjing', 'sara'),
(16, 'Islam asu bangsat', 'sara'),
(17, 'gua dibilang cina, naik derajat dong', 'sara'),
(18, 'biksu botak bangsat', ''),
(19, 'wana asu bajingan', ''),
(20, 'Jancok paaike mata dong kalau lihat-lihat dasar sipit', '');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `kamus`
--
ALTER TABLE `kamus`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `kamus`
--
ALTER TABLE `kamus`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=21;COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
