-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: May 07, 2024 at 06:24 PM
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
-- Database: `suspect_tracker`
--

-- --------------------------------------------------------

--
-- Table structure for table `admin`
--

CREATE TABLE `admin` (
  `user_id` int(15) NOT NULL,
  `admin_id` varchar(20) NOT NULL,
  `password` varchar(10) DEFAULT NULL,
  `address` varchar(50) DEFAULT NULL,
  `phone` varchar(15) DEFAULT NULL,
  `image` varchar(10) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `admin`
--

INSERT INTO `admin` (`user_id`, `admin_id`, `password`, `address`, `phone`, `image`) VALUES
(1, 'manu@gmail.com', '1122', 'Bangalore', '+917022015320', 'manu.png');

-- --------------------------------------------------------

--
-- Table structure for table `suspect_details`
--

CREATE TABLE `suspect_details` (
  `Sus_no` int(15) NOT NULL,
  `suspect_id` varchar(20) NOT NULL,
  `name` varchar(10) DEFAULT NULL,
  `address` varchar(100) DEFAULT NULL,
  `image` varchar(20) DEFAULT NULL,
  `prev_crimes` varchar(100) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `suspect_details`
--

INSERT INTO `suspect_details` (`Sus_no`, `suspect_id`, `name`, `address`, `image`, `prev_crimes`) VALUES
(1, 'Sus_01', 'Raj', '#23 Attibele, Bangalore', 'Raj.png', 'Crime_No 01: Theft in 2018 at Anekal Jurisdiction');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `admin`
--
ALTER TABLE `admin`
  ADD PRIMARY KEY (`admin_id`),
  ADD UNIQUE KEY `user_id` (`user_id`);

--
-- Indexes for table `suspect_details`
--
ALTER TABLE `suspect_details`
  ADD PRIMARY KEY (`suspect_id`),
  ADD UNIQUE KEY `Sus_no` (`Sus_no`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `admin`
--
ALTER TABLE `admin`
  MODIFY `user_id` int(15) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `suspect_details`
--
ALTER TABLE `suspect_details`
  MODIFY `Sus_no` int(15) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
