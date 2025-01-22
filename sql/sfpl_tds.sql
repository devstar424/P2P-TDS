-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jan 22, 2025 at 01:55 PM
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
-- Database: `sfpl_tds`
--

-- --------------------------------------------------------

--
-- Table structure for table `active_admin`
--

CREATE TABLE `active_admin` (
  `id` int(11) NOT NULL,
  `admin_id` int(11) NOT NULL DEFAULT 0,
  `admin_name` varchar(64) NOT NULL DEFAULT '',
  `token` varchar(32) NOT NULL DEFAULT '',
  `expire` timestamp NOT NULL DEFAULT current_timestamp(),
  `useragent` tinytext NOT NULL DEFAULT '',
  `ip` varchar(64) NOT NULL DEFAULT '',
  `postdate` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `admin`
--

CREATE TABLE `admin` (
  `id` int(11) NOT NULL,
  `name` varchar(32) NOT NULL DEFAULT '',
  `username` varchar(32) NOT NULL DEFAULT '',
  `password` varchar(32) NOT NULL DEFAULT ''
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `admin`
--

INSERT INTO `admin` (`id`, `name`, `username`, `password`) VALUES
(1, 'Takir', '5c697613cca1c7b9f353ee7a6322a4af', '7b47c2e1cb8df0411d31a92e34d4999c'),
(2, 'Nizam', '34d778bcfc8c2829edfc70f690a4b46a', '9c32e9186070cb07c44a891a61fc5d7f'),
(3, 'Zafar', '1b0527a886daeda9d30e54a1beb18eb0', 'aa7594e198cfacbdadcf70fdc353bb70'),
(4, 'SFPL', 'b5102fefb80844e2b8e17a5f3b048c54', '037fe0a01bc2c4ac0b004bc457808aea');

-- --------------------------------------------------------

--
-- Table structure for table `deleted_row`
--

CREATE TABLE `deleted_row` (
  `id` int(11) NOT NULL,
  `admin_name` varchar(64) NOT NULL DEFAULT '',
  `tbl` varchar(64) NOT NULL DEFAULT '',
  `row` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin NOT NULL DEFAULT '{}' CHECK (json_valid(`row`)),
  `postdate` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `history`
--

CREATE TABLE `history` (
  `id` int(11) NOT NULL,
  `admin_name` varchar(64) NOT NULL DEFAULT '',
  `pan` varchar(16) NOT NULL DEFAULT '',
  `orderId` varchar(64) NOT NULL DEFAULT '',
  `name` varchar(64) NOT NULL DEFAULT '',
  `amount` float(13,2) NOT NULL DEFAULT 0.00,
  `phone` varchar(32) NOT NULL DEFAULT '',
  `edit` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin NOT NULL DEFAULT '[]' CHECK (json_valid(`edit`)),
  `postdate` timestamp NOT NULL DEFAULT current_timestamp(),
  `tds` float(13,2) GENERATED ALWAYS AS (`amount` * 0.01) VIRTUAL COMMENT '`amount` * 0.01',
  `paid` float(13,2) GENERATED ALWAYS AS (`amount` - `amount` * 0.01) VIRTUAL COMMENT '`amount`-(`amount`*0.01)'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `active_admin`
--
ALTER TABLE `active_admin`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `admin`
--
ALTER TABLE `admin`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `Username` (`username`);

--
-- Indexes for table `deleted_row`
--
ALTER TABLE `deleted_row`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `history`
--
ALTER TABLE `history`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `active_admin`
--
ALTER TABLE `active_admin`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `admin`
--
ALTER TABLE `admin`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `deleted_row`
--
ALTER TABLE `deleted_row`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `history`
--
ALTER TABLE `history`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=18;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
