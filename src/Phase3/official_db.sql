-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Nov 28, 2025 at 09:22 AM
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
-- Database: `iotphase3`
--

-- --------------------------------------------------------

--
-- Table structure for table `clients`
--

CREATE TABLE `clients` (
  `client_id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `password` varchar(255) NOT NULL,
  `membership_number` varchar(20) DEFAULT NULL,
  `total_points` int(11) DEFAULT 0,
  `client_type` varchar(20) NOT NULL DEFAULT 'regular'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `clients`
--

INSERT INTO `clients` (`client_id`, `name`, `email`, `password`, `membership_number`, `total_points`, `client_type`) VALUES
(1, 'Malenia', 'malenia@example.com', '12345', '1002', 129, 'admin'),
(2, 'Jill Valentine', 'Jill@example.com', 'abcde', 'M1003', 45, 'regular'),
(4, 'Shaheryar Anwar', 'Shaheryar.a@hotmail.com', '$2y$12$vjuUL5mvmFcE7hRXmHEwNORfBic.0KNQqvlJReNLKrvzPK.BpK2hC', 'M124', 0, 'regular'),
(6, 'Queen Marika', 'shaheryar751@outlook.com', 'Elden1234', 'M126', 0, 'regular');

-- --------------------------------------------------------

--
-- Table structure for table `inventory`
--

CREATE TABLE `inventory` (
  `product_id` int(11) NOT NULL,
  `epc` varchar(24) NOT NULL,
  `quantity` int(11) NOT NULL,
  `update_time` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `inventory`
--

INSERT INTO `inventory` (`product_id`, `epc`, `quantity`, `update_time`) VALUES
(3, '098765432109876543214541', 1, '2025-11-27 01:56:52');

-- --------------------------------------------------------

--
-- Table structure for table `products`
--

CREATE TABLE `products` (
  `product_id` int(12) NOT NULL,
  `name` varchar(200) NOT NULL,
  `category` varchar(200) NOT NULL,
  `price` decimal(10,2) NOT NULL,
  `upc` bigint(13) NOT NULL,
  `epc` varchar(24) NOT NULL,
  `company` varchar(200) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `products`
--

INSERT INTO `products` (`product_id`, `name`, `category`, `price`, `upc`, `epc`, `company`) VALUES
(3, 'oreo', 'food', 6.99, 8992760223015, 'A00000000000000000004929', 'OREOz'),
(4, 'test', 'test', 12.00, 1234567890123, '123456789012345678901234', 'test'),
(7, 'socks', 'clothes', 7.00, 2412792764213, 'A00000000000000000000927', 'Nike'),
(8, 'apple', 'fruit', 1.99, 9817653213121, '123456789012345678900000', 'APPLE'),
(9, 'dildo', 'toys', 20.99, 987654321555, '', 'ToysRus');

-- --------------------------------------------------------

--
-- Table structure for table `receipts`
--

CREATE TABLE `receipts` (
  `receipt_id` int(11) NOT NULL,
  `client_id` int(11) DEFAULT NULL,
  `total_amount` decimal(10,2) NOT NULL,
  `points` int(11) NOT NULL,
  `receipt_date` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `receipts`
--

INSERT INTO `receipts` (`receipt_id`, `client_id`, `total_amount`, `points`, `receipt_date`) VALUES
(7, 1, 0.00, 0, '2025-11-10 21:43:32'),
(8, 1, 0.00, 0, '2025-11-10 21:48:57'),
(9, 1, 0.00, 0, '2025-11-10 21:50:39'),
(10, 1, 0.00, 0, '2025-11-10 21:51:38'),
(11, 1, 0.00, 0, '2025-11-10 21:56:15'),
(12, 1, 0.00, 0, '2025-11-10 22:00:10'),
(13, 1, 0.00, 0, '2025-11-10 22:03:41'),
(14, 1, 0.00, 0, '2025-11-10 22:05:10'),
(15, 1, 0.00, 3, '2025-11-10 22:12:13'),
(16, 1, 0.00, 3, '2025-11-10 22:18:19'),
(17, 1, 0.00, 3, '2025-11-10 22:21:27'),
(18, 1, 0.00, 3, '2025-11-10 22:22:38'),
(19, 1, 0.00, 3, '2025-11-10 22:36:39'),
(20, 1, 0.00, 3, '2025-11-10 22:39:10'),
(21, 1, 40.00, 3, '2025-11-10 22:40:59'),
(22, 1, 24.11, 3, '2025-11-11 11:32:46'),
(23, 1, 16.07, 3, '2025-11-11 11:35:18'),
(24, 1, 32.15, 3, '2025-11-11 11:43:16'),
(25, 1, 8.04, 3, '2025-11-11 11:46:25'),
(26, 1, 8.04, 3, '2025-11-11 11:50:05'),
(27, 1, 16.07, 3, '2025-11-11 11:51:40'),
(28, 1, 16.07, 3, '2025-11-11 11:52:39'),
(29, 1, 8.04, 3, '2025-11-11 11:54:30'),
(30, 1, 8.04, 3, '2025-11-11 12:00:23'),
(31, 1, 16.07, 3, '2025-11-11 12:09:57'),
(32, 1, 16.07, 3, '2025-11-11 12:19:35'),
(33, 1, 8.04, 3, '2025-11-11 12:21:30'),
(34, 1, 8.04, 3, '2025-11-11 12:22:47'),
(35, 1, 8.04, 3, '2025-11-11 12:24:42'),
(36, 1, 8.04, 3, '2025-11-11 12:26:32'),
(37, 1, 8.04, 3, '2025-11-11 12:31:26'),
(38, 1, 24.11, 3, '2025-11-11 13:15:22'),
(39, 1, 8.04, 3, '2025-11-11 13:22:38'),
(40, 1, 16.07, 3, '2025-11-11 13:25:37'),
(41, 1, 16.07, 3, '2025-11-11 13:27:00'),
(42, 1, 24.11, 3, '2025-11-11 14:18:02'),
(43, 1, 8.04, 3, '2025-11-19 22:17:31'),
(44, 1, 32.15, 3, '2025-11-19 22:49:52'),
(45, 1, 48.22, 3, '2025-11-21 10:46:01'),
(46, 1, 24.11, 3, '2025-11-24 14:39:42'),
(47, 1, 16.07, 3, '2025-11-24 14:40:40'),
(48, 1, 16.07, 3, '2025-11-24 14:41:52'),
(49, 1, 16.07, 3, '2025-11-24 14:44:17'),
(50, 1, 16.07, 3, '2025-11-24 14:44:55'),
(51, 1, 16.07, 3, '2025-11-24 14:47:23'),
(52, 1, 16.07, 3, '2025-11-24 14:49:44'),
(53, 1, 16.07, 3, '2025-11-24 14:50:42'),
(54, 1, 8.04, 3, '2025-11-24 14:51:34'),
(55, 1, 8.04, 3, '2025-11-24 14:52:11'),
(56, 1, 8.04, 3, '2025-11-24 14:53:02'),
(57, 1, 8.04, 3, '2025-11-24 14:53:53'),
(58, 1, 16.07, 3, '2025-11-24 15:15:50'),
(59, 1, 8.04, 3, '2025-11-24 15:16:06'),
(60, 1, 8.05, 3, '2025-11-27 18:28:02'),
(61, 1, 8.05, 3, '2025-11-27 18:38:35'),
(62, 1, 8.05, 3, '2025-11-27 18:55:26'),
(63, 1, 8.05, 3, '2025-11-27 19:02:39'),
(64, 1, 24.13, 3, '2025-11-27 19:04:53'),
(65, 1, 24.13, 3, '2025-11-27 19:06:06'),
(66, NULL, 0.00, 0, '2025-11-27 20:28:32'),
(67, NULL, 24.13, 0, '2025-11-27 20:34:04'),
(68, NULL, 24.13, 0, '2025-11-27 20:39:10'),
(69, NULL, 24.13, 0, '2025-11-27 20:44:58');

-- --------------------------------------------------------

--
-- Table structure for table `receipt_items`
--

CREATE TABLE `receipt_items` (
  `receipt_items_id` int(11) NOT NULL,
  `receipt_id` int(11) NOT NULL,
  `product_id` int(11) NOT NULL,
  `quantity` int(11) NOT NULL,
  `unit_price` decimal(10,2) NOT NULL,
  `line_total` decimal(10,2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `receipt_items`
--

INSERT INTO `receipt_items` (`receipt_items_id`, `receipt_id`, `product_id`, `quantity`, `unit_price`, `line_total`) VALUES
(2, 18, 3, 4, 6.99, 27.96),
(3, 19, 3, 17, 6.99, 118.83),
(4, 20, 3, 2, 6.99, 13.98),
(5, 21, 3, 5, 6.99, 34.95),
(6, 23, 3, 2, 6.99, 13.98),
(7, 24, 3, 4, 6.99, 27.96),
(8, 25, 3, 1, 6.99, 6.99),
(9, 26, 3, 1, 6.99, 6.99),
(10, 27, 3, 2, 6.99, 13.98),
(11, 28, 3, 2, 6.99, 13.98),
(12, 29, 3, 1, 6.99, 6.99),
(13, 30, 3, 1, 6.99, 6.99),
(14, 31, 3, 2, 6.99, 13.98),
(15, 32, 3, 2, 6.99, 13.98),
(16, 33, 3, 1, 6.99, 6.99),
(17, 34, 3, 1, 6.99, 6.99),
(18, 35, 3, 1, 6.99, 6.99),
(19, 36, 3, 1, 6.99, 6.99),
(20, 37, 3, 1, 6.99, 6.99),
(21, 38, 3, 3, 6.99, 20.97),
(22, 39, 3, 1, 6.99, 6.99),
(23, 40, 3, 2, 6.99, 13.98),
(24, 41, 3, 2, 6.99, 13.98),
(25, 42, 3, 3, 6.99, 20.97),
(26, 43, 3, 1, 6.99, 6.99),
(27, 44, 3, 4, 6.99, 27.96),
(28, 45, 3, 6, 6.99, 41.94),
(29, 45, 7, 2, 7.00, 14.00),
(30, 46, 3, 3, 6.99, 20.97),
(31, 47, 3, 2, 6.99, 13.98),
(32, 48, 3, 2, 6.99, 13.98),
(33, 49, 3, 2, 6.99, 13.98),
(34, 50, 3, 2, 6.99, 13.98),
(35, 51, 3, 2, 6.99, 13.98),
(36, 52, 3, 2, 6.99, 13.98),
(37, 53, 3, 2, 6.99, 13.98),
(38, 54, 3, 1, 6.99, 6.99),
(39, 55, 3, 1, 6.99, 6.99),
(40, 56, 3, 1, 6.99, 6.99),
(41, 57, 3, 1, 6.99, 6.99),
(42, 58, 3, 2, 6.99, 13.98),
(43, 59, 3, 1, 6.99, 6.99),
(44, 60, 7, 1, 7.00, 7.00),
(45, 61, 7, 1, 7.00, 7.00),
(46, 62, 7, 1, 7.00, 7.00),
(47, 63, 7, 1, 7.00, 7.00),
(48, 64, 9, 1, 20.99, 20.99),
(49, 65, 9, 1, 20.99, 20.99),
(50, 66, 9, 1, 20.99, 20.99),
(51, 67, 9, 1, 20.99, 20.99),
(52, 68, 9, 1, 20.99, 20.99),
(53, 69, 9, 1, 20.99, 20.99);

-- --------------------------------------------------------

--
-- Table structure for table `temperature`
--

CREATE TABLE `temperature` (
  `Temp_id` int(11) NOT NULL,
  `Temp_threshold` decimal(5,2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `clients`
--
ALTER TABLE `clients`
  ADD PRIMARY KEY (`client_id`),
  ADD UNIQUE KEY `email` (`email`),
  ADD UNIQUE KEY `membership_number` (`membership_number`);

--
-- Indexes for table `inventory`
--
ALTER TABLE `inventory`
  ADD PRIMARY KEY (`epc`);

--
-- Indexes for table `products`
--
ALTER TABLE `products`
  ADD PRIMARY KEY (`product_id`);

--
-- Indexes for table `receipts`
--
ALTER TABLE `receipts`
  ADD PRIMARY KEY (`receipt_id`),
  ADD KEY `FK_client_id_receipts` (`client_id`);

--
-- Indexes for table `receipt_items`
--
ALTER TABLE `receipt_items`
  ADD PRIMARY KEY (`receipt_items_id`),
  ADD KEY `FK_receipt_id_receipt_items` (`receipt_id`),
  ADD KEY `FK_product_id_receipt_items` (`product_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `clients`
--
ALTER TABLE `clients`
  MODIFY `client_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `products`
--
ALTER TABLE `products`
  MODIFY `product_id` int(12) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT for table `receipts`
--
ALTER TABLE `receipts`
  MODIFY `receipt_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=70;

--
-- AUTO_INCREMENT for table `receipt_items`
--
ALTER TABLE `receipt_items`
  MODIFY `receipt_items_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=54;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `receipts`
--
ALTER TABLE `receipts`
  ADD CONSTRAINT `FK_client_id_receipts` FOREIGN KEY (`client_id`) REFERENCES `clients` (`client_id`);

--
-- Constraints for table `receipt_items`
--
ALTER TABLE `receipt_items`
  ADD CONSTRAINT `FK_product_id_receipt_items` FOREIGN KEY (`product_id`) REFERENCES `products` (`product_id`),
  ADD CONSTRAINT `FK_receipt_id_receipt_items` FOREIGN KEY (`receipt_id`) REFERENCES `receipts` (`receipt_id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
