-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Nov 22, 2024 at 11:26 AM
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
-- Database: `vibevintage`
--

-- --------------------------------------------------------

--
-- Table structure for table `product_images`
--

CREATE TABLE `product_images` (
  `id` int(11) NOT NULL,
  `product_id` int(11) NOT NULL,
  `image_path` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `product_images`
--

INSERT INTO `product_images` (`id`, `product_id`, `image_path`) VALUES
(3, 5, 'Men/winterjacket.jpg'),
(4, 6, 'Men/hoodie1.jpg'),
(5, 7, 'Men/officewear1.jpg'),
(6, 9, 'uploads/stylishshirt1.jpg'),
(7, 9, 'uploads/stylishshirt2.jpg'),
(15, 12, 'uploads/tshirt1.jpg'),
(16, 12, 'uploads/tshirt2.jpg'),
(17, 13, 'uploads/tshirt3.jpg'),
(18, 13, 'uploads/tshirt4.jpg'),
(19, 14, 'uploads/mc1.jpg'),
(20, 14, 'uploads/mc2.jpg'),
(21, 15, 'uploads/mc3.jpg'),
(22, 15, 'uploads/mc4.jpg'),
(23, 16, 'uploads/mc5.jpg'),
(24, 16, 'uploads/mc6.jpg'),
(25, 17, 'uploads/menjeans.jpg'),
(26, 17, 'uploads/menjeans2.jpg'),
(27, 18, 'uploads/blackjeans.jpg'),
(28, 18, 'uploads/blackjeans2.jpg'),
(30, 2, 'uploads/sweatshirtboys.jpg'),
(35, 19, 'uploads/blackjeans.jpg'),
(36, 19, 'uploads/blackjeans2.jpg'),
(37, 1, 'Kids/sweatshirtboys.jpg'),
(38, 1, 'Kids/sweatshirtboys2.jpg'),
(39, 2, 'Kids/sweatshirtboys2.jpg');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `product_images`
--
ALTER TABLE `product_images`
  ADD PRIMARY KEY (`id`),
  ADD KEY `product_id` (`product_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `product_images`
--
ALTER TABLE `product_images`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=58;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
