-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Generation Time: Apr 01, 2024 at 05:46 PM
-- Server version: 10.6.16-MariaDB-0ubuntu0.22.04.1
-- PHP Version: 8.2.15

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `admin_rccb`
--

-- --------------------------------------------------------

--
-- Table structure for table `branchmaster`
--

CREATE TABLE `branchmaster` (
  `id` int(11) NOT NULL,
  `branch_details_id` int(11) NOT NULL,
  `branchName` varchar(255) NOT NULL,
  `empId` int(11) NOT NULL,
  `is_manager` int(11) NOT NULL DEFAULT 1,
  `branchHead` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `branchmaster`
--

INSERT INTO `branchmaster` (`id`, `branch_details_id`, `branchName`, `empId`, `is_manager`, `branchHead`, `email`, `created_at`, `updated_at`) VALUES
(2, 10, 'MOHANBATI', 166, 1, 'Sagar Banerjee', 'mohanbatirccbltd@gmail.com', '2022-09-12 12:46:29', '2022-09-12 12:46:29'),
(3, 15, 'UKILPARA', 201, 1, 'SHAHANSHA SAJAHAN KABIR', 'ukilpararccbltd1@gmail.com', '2022-09-12 12:47:19', '2022-09-12 12:47:19'),
(4, 16, 'MAIN BRANCH', 122, 1, 'Kripa Bala', 'rccbraiganjbranch@gmail.com', '2022-09-12 12:48:03', '2022-09-12 12:48:03'),
(5, 9, 'HEMTABAD', 147, 1, 'Dilip Kumar Mandal', 'rccb.hemtabad@gmail.com', '2022-09-12 12:48:48', '2022-09-12 12:48:48'),
(6, 7, 'KUNORE', 135, 1, 'Kuntal Chakraborty', 'kunorerccbltd@gmail.com', '2022-09-12 12:49:27', '2022-09-12 12:49:27'),
(7, 8, 'DALKHOLA', 113, 1, 'Chiranjib jana', 'rccbltd.dalkhola@gmail.com', '2022-09-12 12:50:10', '2022-09-12 12:50:10'),
(8, 11, 'TUNGIDIGHI', 140, 1, 'Biswanath Biswas', 'rccbtungidighi@gmail.com', '2022-09-12 12:50:44', '2022-09-12 12:50:44'),
(9, 6, 'KUSHMUNDI', 139, 1, 'Prasenjit Ghosh', 'kushmandirccbltd@gmail.com', '2022-09-12 12:51:33', '2022-09-12 12:51:33'),
(10, 2, 'BUNIADPUR', 137, 1, 'Brojo Mohan Bag', 'buniadpur.rccbltd@gmail.com', '2022-09-12 12:52:18', '2022-09-12 12:52:18'),
(11, 13, 'HARIRAMPUR', 163, 1, 'Pranab Sengupta', 'rccbltd.harirampur@gmail.com', '2022-09-12 12:52:54', '2022-09-12 12:52:54'),
(12, 5, 'KALIYAGANJ', 136, 1, 'Kartick Chandra Sarkar', 'rccb.kaliyaganj@gmail.com', '2022-09-12 12:53:34', '2022-09-12 12:53:34'),
(13, 12, 'KANKI', 168, 1, 'Bharat Chandra Sarkar', 'kanki.rccbltd@gmail.com', '2022-09-12 12:54:17', '2022-09-12 12:54:17'),
(14, 14, 'PANJIPARA', 143, 1, 'Idris Naster', 'rccbpanjipara16@gmail.com', '2022-09-12 12:54:49', '2022-09-12 12:54:49'),
(15, 4, 'ITAHAR', 198, 1, 'Kartick Baidya', 'rccb.itahar@gmail.com', '2022-09-12 12:55:27', '2022-09-12 12:55:27'),
(16, 1, 'ISLAMPUR', 155, 1, 'Bazlar Rahman', 'rccb03islampur@gmail.com', '2022-09-12 12:55:59', '2022-09-12 12:55:59'),
(17, 3, 'CHOPRA', 197, 1, 'Koushik Roy', 'rccbchopra005@gmail.com', '2022-09-12 12:56:52', '2022-09-12 12:56:52'),
(19, 0, 'CEO', 1001, 2, 'Mingma Bhutia', 'ceorccbltd@gmail.com', '2022-09-15 10:46:28', '2022-09-15 10:46:28'),
(21, 0, 'DGM', 236, 1, 'Tapas Biswas', 'dgmitrccbltd@gmail.com', '2022-09-15 10:47:27', '2022-09-15 10:47:27'),
(22, 0, 'New Establishment Manager', 1002, 2, 'Admin Establishment', 'san2005_karmakar@yahoo.co.in\r\n', '2023-09-21 13:32:03', '2023-09-21 13:32:03');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `branchmaster`
--
ALTER TABLE `branchmaster`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `branchmaster`
--
ALTER TABLE `branchmaster`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=23;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
