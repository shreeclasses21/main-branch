-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jun 20, 2025 at 10:21 AM
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
-- Database: `shree_classes`
--

-- --------------------------------------------------------

--
-- Table structure for table `admin_users`
--

CREATE TABLE `admin_users` (
  `id` int(11) NOT NULL,
  `username` varchar(50) NOT NULL,
  `password` varchar(255) NOT NULL,
  `name` varchar(100) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `admin_users`
--

INSERT INTO `admin_users` (`id`, `username`, `password`, `name`, `created_at`) VALUES
(1, 'admin', '$2y$10$OjC0G6jcr4voZlpTlpnFPu6R9L8WDlJNjRy7Qjvoms68nIYV9h6rq', 'Site Admin', '2025-06-16 04:29:45');

-- --------------------------------------------------------

--
-- Table structure for table `attendance_log`
--

CREATE TABLE `attendance_log` (
  `id` int(11) NOT NULL,
  `student_id` varchar(18) DEFAULT NULL,
  `attendance_date` date NOT NULL,
  `type` enum('Present','Absent','On Leave') DEFAULT 'Present',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `attendance_log`
--

INSERT INTO `attendance_log` (`id`, `student_id`, `attendance_date`, `type`, `created_at`) VALUES
(6, '003TESTSTUDENT0001', '2025-06-01', 'Present', '2025-06-16 17:48:08'),
(7, '003TESTSTUDENT0001', '2025-06-02', 'Present', '2025-06-16 17:48:08'),
(8, '003TESTSTUDENT0001', '2025-06-03', 'Present', '2025-06-16 17:48:08'),
(9, '003TESTSTUDENT0001', '2025-06-04', 'Present', '2025-06-16 17:48:08'),
(10, '003TESTSTUDENT0001', '2025-06-05', 'Present', '2025-06-16 17:48:08'),
(11, '003TESTSTUDENT0001', '2025-06-06', 'Present', '2025-06-16 17:48:08'),
(12, '003TESTSTUDENT0001', '2025-06-07', 'On Leave', '2025-06-16 17:48:08'),
(13, '003TESTSTUDENT0001', '2025-06-08', 'Present', '2025-06-16 17:48:08'),
(14, '003TESTSTUDENT0001', '2025-06-09', 'Present', '2025-06-16 17:48:08'),
(15, '003TESTSTUDENT0001', '2025-06-10', 'Present', '2025-06-16 17:48:08'),
(16, '003TESTSTUDENT0001', '2025-06-11', 'Present', '2025-06-16 17:48:08'),
(17, '003TESTSTUDENT0001', '2025-06-12', 'Present', '2025-06-16 17:48:08'),
(18, '003TESTSTUDENT0001', '2025-06-13', 'Present', '2025-06-16 17:48:08'),
(19, '003TESTSTUDENT0001', '2025-06-14', 'Present', '2025-06-16 17:48:08'),
(20, '003TESTSTUDENT0001', '2025-06-15', 'Present', '2025-06-16 17:48:08'),
(21, '003TESTSTUDENT0001', '2025-06-16', 'Present', '2025-06-16 17:48:08'),
(23, '003TESTSTUDENT0001', '2025-06-18', 'Present', '2025-06-16 17:48:08'),
(24, '003TESTSTUDENT0001', '2025-06-19', 'Present', '2025-06-16 17:48:08'),
(25, '003TESTSTUDENT0001', '2025-06-20', 'Absent', '2025-06-16 17:48:08'),
(26, '003TESTSTUDENT0001', '2025-06-21', 'Present', '2025-06-16 17:48:08'),
(27, '003TESTSTUDENT0001', '2025-06-22', 'Present', '2025-06-16 17:48:08'),
(28, '003TESTSTUDENT0001', '2025-06-23', 'Present', '2025-06-16 17:48:08'),
(29, '003TESTSTUDENT0001', '2025-06-24', 'Present', '2025-06-16 17:48:08'),
(30, '003TESTSTUDENT0001', '2025-07-25', 'Present', '2025-06-16 17:48:08'),
(31, '003TESTSTUDENT0001', '2025-06-26', 'Present', '2025-06-16 17:48:08'),
(32, '003TESTSTUDENT0001', '2025-06-27', 'Present', '2025-06-16 17:48:08'),
(33, '003TESTSTUDENT0001', '2025-06-28', 'Present', '2025-06-16 17:48:08'),
(34, '003TESTSTUDENT0001', '2025-06-29', 'On Leave', '2025-06-16 17:48:08'),
(35, '003TESTSTUDENT0001', '2025-06-30', 'Present', '2025-06-16 17:48:08'),
(36, '003TESTSTUDENT0001', '2025-06-17', 'Present', '2025-06-16 18:48:02'),
(37, '5F667C', '2025-06-19', 'Present', '2025-06-19 12:00:14');

-- --------------------------------------------------------

--
-- Table structure for table `attendance_run_log`
--

CREATE TABLE `attendance_run_log` (
  `id` int(11) NOT NULL,
  `run_date` date NOT NULL,
  `run_time` datetime NOT NULL,
  `marked_count` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `auth_codes`
--

CREATE TABLE `auth_codes` (
  `id` int(11) NOT NULL,
  `code` varchar(50) NOT NULL,
  `is_used` tinyint(1) DEFAULT 0,
  `issued_to` varchar(100) DEFAULT NULL,
  `created_at` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `auth_codes`
--

INSERT INTO `auth_codes` (`id`, `code`, `is_used`, `issued_to`, `created_at`) VALUES
(1, 'SCL123', 0, NULL, '2025-06-19 09:23:51'),
(2, 'SCL456', 0, NULL, '2025-06-19 09:23:51'),
(3, 'SCL789', 0, NULL, '2025-06-19 09:23:51'),
(4, 'SHREE2025', 0, NULL, '2025-06-19 09:23:51'),
(5, 'CLASSX', 0, NULL, '2025-06-19 09:23:51'),
(6, 'JOIN2025', 0, NULL, '2025-06-19 09:23:51');

-- --------------------------------------------------------

--
-- Table structure for table `contact_submissions`
--

CREATE TABLE `contact_submissions` (
  `id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `email` varchar(150) NOT NULL,
  `message` text NOT NULL,
  `submitted_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `contact_submissions`
--

INSERT INTO `contact_submissions` (`id`, `name`, `email`, `message`, `submitted_at`) VALUES
(1, 'Oam Shah', 'oamshah2121@gmail.com', 'ggh', '2025-06-20 06:13:26'),
(2, 'Test User Fire', 'shahoam21@gmail.com', 'Hello need to Join Class', '2025-06-20 06:14:51'),
(3, 'Oam Shah', 'oamshah2121@gmail.com', 'Test message', '2025-06-20 06:17:50'),
(4, 'Oam Shah', 'oamshah2121@gmail.com', 'Test', '2025-06-20 06:19:18');

-- --------------------------------------------------------

--
-- Table structure for table `cookie_logs`
--

CREATE TABLE `cookie_logs` (
  `id` int(11) NOT NULL,
  `ip_address` varchar(45) DEFAULT NULL,
  `accepted_at` datetime DEFAULT NULL,
  `browser_info` text DEFAULT NULL,
  `language` varchar(10) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `cookie_logs`
--

INSERT INTO `cookie_logs` (`id`, `ip_address`, `accepted_at`, `browser_info`, `language`) VALUES
(2, '::1', '2025-06-18 21:41:40', NULL, NULL),
(3, '::1', '2025-06-18 19:43:31', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/137.0.0.0 Safari/537.36', 'en-US'),
(4, '192.168.1.2', '2025-06-18 19:47:18', 'Mozilla/5.0 (Linux; Android 10; K) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/137.0.0.0 Mobile Safari/537.36', 'en-US'),
(5, '::1', '2025-06-19 11:52:02', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/137.0.0.0 Safari/537.36', 'en-US'),
(6, '192.168.1.2', '2025-06-19 11:53:44', 'Mozilla/5.0 (Linux; Android 10; K) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/137.0.0.0 Mobile Safari/537.36', 'en-US');

-- --------------------------------------------------------

--
-- Table structure for table `leave_assignments`
--

CREATE TABLE `leave_assignments` (
  `id` int(11) NOT NULL,
  `student_id` varchar(18) DEFAULT NULL,
  `leave_type_id` varchar(18) DEFAULT NULL,
  `year` int(11) DEFAULT NULL,
  `month` varchar(50) DEFAULT NULL,
  `allowed` int(11) DEFAULT NULL,
  `used` int(11) DEFAULT NULL,
  `remaining` int(11) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `leave_assignments`
--

INSERT INTO `leave_assignments` (`id`, `student_id`, `leave_type_id`, `year`, `month`, `allowed`, `used`, `remaining`, `created_at`) VALUES
(1, '003TESTSTUDENT0001', '1', 2025, 'June', 3, 0, 0, '2025-06-15 14:43:59'),
(2, '003TESTSTUDENT0001', '2', 2025, 'June', 4, 1, 0, '2025-06-15 14:43:59'),
(3, '684fa5fa79380', '1', 2025, 'June', 2, 0, NULL, '2025-06-16 08:07:34'),
(4, '684fa5fa79380', '2', 2025, 'June', 4, 0, 0, '2025-06-16 08:07:34'),
(5, '003TESTSTUDENT0001', '1', 2025, 'July', 0, 0, NULL, '2025-06-16 19:00:56');

-- --------------------------------------------------------

--
-- Table structure for table `leave_requests`
--

CREATE TABLE `leave_requests` (
  `id` int(11) NOT NULL,
  `student_id` varchar(18) DEFAULT NULL,
  `leave_type_id` varchar(18) DEFAULT NULL,
  `from_date` date DEFAULT NULL,
  `to_date` date DEFAULT NULL,
  `reason` text DEFAULT NULL,
  `status` varchar(50) DEFAULT NULL,
  `supporting_document` varchar(255) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `leave_requests`
--

INSERT INTO `leave_requests` (`id`, `student_id`, `leave_type_id`, `from_date`, `to_date`, `reason`, `status`, `supporting_document`, `created_at`) VALUES
(3, '003TESTSTUDENT0001', '2', '2025-06-15', '2025-06-15', 'Not felling well', 'Rejected', NULL, '2025-06-15 16:10:05');

-- --------------------------------------------------------

--
-- Table structure for table `leave_types`
--

CREATE TABLE `leave_types` (
  `id` int(11) NOT NULL,
  `name` varchar(80) DEFAULT NULL,
  `description` text DEFAULT NULL,
  `allowed_per_month` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `leave_types`
--

INSERT INTO `leave_types` (`id`, `name`, `description`, `allowed_per_month`) VALUES
(1, 'Vacation', 'Leave taken for vacations or personal time', 2),
(2, 'Sick Leave', 'Leave granted for health-related issues', 3);

-- --------------------------------------------------------

--
-- Table structure for table `payment_invoices`
--

CREATE TABLE `payment_invoices` (
  `id` int(11) NOT NULL,
  `student_id` varchar(18) NOT NULL,
  `student_name` varchar(100) DEFAULT NULL,
  `email` varchar(100) DEFAULT NULL,
  `phone` varchar(20) DEFAULT NULL,
  `month` varchar(20) DEFAULT NULL,
  `year` int(11) DEFAULT NULL,
  `amount` decimal(10,2) DEFAULT NULL,
  `status` enum('Paid','Unpaid','Pending','Partial Paid') DEFAULT 'Pending',
  `type` enum('Online','Cash') DEFAULT 'Online',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `payment_invoices`
--

INSERT INTO `payment_invoices` (`id`, `student_id`, `student_name`, `email`, `phone`, `month`, `year`, `amount`, `status`, `type`, `created_at`) VALUES
(1, 'STU123456', 'John Doe', 'oamshah2121@gmail.com', '9130937571', '06', 2025, 2000.00, 'Paid', 'Online', '2025-06-17 05:02:50'),
(2, 'STU123456', 'John Doe', 'oamshah2121@gmail.com', '9130937571', '06', 2025, 567.00, 'Paid', 'Online', '2025-06-17 08:55:47'),
(3, '684fa5fa79380', 'Oam Shahgh', 'shahoam21@gmail.com', '', '06', 2025, 5678.00, 'Paid', 'Online', '2025-06-17 13:52:50'),
(4, '003TESTSTUDENT0001', 'John Doe', 'oamshah2121@gmail.com', '9130937571', '06', 2025, 6789.00, 'Paid', 'Online', '2025-06-17 13:53:04');

-- --------------------------------------------------------

--
-- Table structure for table `public_holidays`
--

CREATE TABLE `public_holidays` (
  `id` int(11) NOT NULL,
  `holiday_date` date NOT NULL,
  `name` varchar(255) NOT NULL,
  `type` varchar(100) DEFAULT NULL,
  `year` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `public_holidays`
--

INSERT INTO `public_holidays` (`id`, `holiday_date`, `name`, `type`, `year`) VALUES
(2, '2025-08-15', 'Independence Day', 'National', 2025),
(3, '2025-10-02', 'Gandhi Jayanti', 'National', 2025),
(8, '2025-03-08', 'Holi', 'Religious', 2025),
(9, '2025-04-18', 'Good Friday', 'Religious', 2025),
(28, '2025-11-13', 'Diwali', 'Religious', 2025),
(29, '2025-12-25', 'Christmas Day', 'National', 2025),
(30, '2026-01-01', 'New Year\'s Day', 'National', 2026),
(31, '2026-01-26', 'Republic Day', 'National', 2026);

-- --------------------------------------------------------

--
-- Table structure for table `regularization_requests`
--

CREATE TABLE `regularization_requests` (
  `id` int(11) NOT NULL,
  `student_id` varchar(18) DEFAULT NULL,
  `requested_date` date DEFAULT NULL,
  `reason` text DEFAULT NULL,
  `status` enum('Pending','Approved','Rejected') DEFAULT 'Pending',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `regularization_requests`
--

INSERT INTO `regularization_requests` (`id`, `student_id`, `requested_date`, `reason`, `status`, `created_at`) VALUES
(1, '003TESTSTUDENT0001', '2025-06-18', 'Hello', 'Approved', '2025-06-16 17:33:24'),
(2, '003TESTSTUDENT0001', '2025-06-02', 'Forgot to logout', 'Approved', '2025-06-16 17:50:31'),
(3, '003TESTSTUDENT0001', '2025-06-03', 'Forgot to logout', 'Approved', '2025-06-16 17:50:31'),
(4, '003TESTSTUDENT0001', '2025-06-05', 'Forgot to logout', 'Approved', '2025-06-16 17:50:31'),
(5, '003TESTSTUDENT0001', '2025-06-06', 'Forgot to logout', 'Approved', '2025-06-16 17:50:31'),
(6, '003TESTSTUDENT0001', '2025-06-13', 'Forgot to logout', 'Approved', '2025-06-16 17:50:31'),
(7, '003TESTSTUDENT0001', '2025-06-12', 'Forgot to logout', 'Approved', '2025-06-16 17:50:31'),
(8, '003TESTSTUDENT0001', '2025-06-09', 'Forgot to logout', 'Approved', '2025-06-16 17:50:31'),
(9, '003TESTSTUDENT0001', '2025-06-15', 'Forgot to logout', 'Approved', '2025-06-16 17:50:31'),
(10, '003TESTSTUDENT0001', '2025-06-17', 'Forgot to logout', 'Approved', '2025-06-16 17:50:31'),
(11, '003TESTSTUDENT0001', '2025-06-19', 'Forgot to logout', 'Approved', '2025-06-16 17:50:31'),
(12, '003TESTSTUDENT0001', '2025-06-20', 'Forgot to logout', 'Approved', '2025-06-16 17:50:31'),
(13, '003TESTSTUDENT0001', '2025-06-22', 'Forgot to logout', 'Approved', '2025-06-16 17:50:31'),
(14, '003TESTSTUDENT0001', '2025-06-23', 'Forgot to logout', 'Approved', '2025-06-16 17:50:31'),
(15, '003TESTSTUDENT0001', '2025-06-24', 'Forgot to logout', 'Approved', '2025-06-16 17:50:31'),
(16, '003TESTSTUDENT0001', '2025-06-26', 'Forgot to logout', 'Approved', '2025-06-16 17:50:31'),
(17, '003TESTSTUDENT0001', '2025-06-27', 'Forgot to logout', 'Approved', '2025-06-16 17:50:31'),
(18, '003TESTSTUDENT0001', '2025-06-28', 'Forgot to logout', 'Approved', '2025-06-16 17:50:31'),
(19, '003TESTSTUDENT0001', '2025-06-30', 'Forgot to logout', 'Approved', '2025-06-16 17:50:31'),
(20, '003TESTSTUDENT0001', '2025-06-22', 'forgot to logout', 'Approved', '2025-06-16 18:10:01'),
(21, '003TESTSTUDENT0001', '2025-06-19', 'forgot to logout', 'Rejected', '2025-06-16 18:13:03'),
(22, '003TESTSTUDENT0001', '2025-06-19', 'forgot to add leave', 'Rejected', '2025-06-16 18:40:09'),
(23, '003TESTSTUDENT0001', '2025-06-19', 'Forgot to mark', 'Rejected', '2025-06-16 19:01:32'),
(24, '003TESTSTUDENT0001', '2025-06-19', 'Forgot to mark att', 'Approved', '2025-06-18 04:09:19'),
(25, '003TESTSTUDENT0001', '2025-06-20', 'Forgot', 'Pending', '2025-06-18 18:34:13');

-- --------------------------------------------------------

--
-- Table structure for table `students`
--

CREATE TABLE `students` (
  `id` varchar(18) NOT NULL,
  `student_id` varchar(255) DEFAULT NULL,
  `first_name` varchar(40) DEFAULT NULL,
  `last_name` varchar(80) DEFAULT NULL,
  `salutation` varchar(20) DEFAULT NULL,
  `email` varchar(150) DEFAULT NULL,
  `mobile` varchar(20) DEFAULT NULL,
  `phone` varchar(20) DEFAULT NULL,
  `home_phone` varchar(20) DEFAULT NULL,
  `password` varchar(255) DEFAULT NULL,
  `otp_code` varchar(6) DEFAULT NULL,
  `otp_expiry` datetime DEFAULT NULL,
  `birthdate` date DEFAULT NULL,
  `date_of_birth` date DEFAULT NULL,
  `section` varchar(50) DEFAULT NULL,
  `board` varchar(50) DEFAULT NULL,
  `guardian_name` varchar(255) DEFAULT NULL,
  `mailing_street` text DEFAULT NULL,
  `mailing_city` varchar(100) DEFAULT NULL,
  `mailing_state` varchar(100) DEFAULT NULL,
  `mailing_postal` varchar(20) DEFAULT NULL,
  `mailing_country` varchar(100) DEFAULT NULL,
  `status` varchar(50) DEFAULT NULL,
  `profile_photo_url` varchar(255) DEFAULT NULL,
  `first_profile` tinyint(1) DEFAULT NULL,
  `first_time_login` tinyint(1) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `current_grade` float DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `students`
--

INSERT INTO `students` (`id`, `student_id`, `first_name`, `last_name`, `salutation`, `email`, `mobile`, `phone`, `home_phone`, `password`, `otp_code`, `otp_expiry`, `birthdate`, `date_of_birth`, `section`, `board`, `guardian_name`, `mailing_street`, `mailing_city`, `mailing_state`, `mailing_postal`, `mailing_country`, `status`, `profile_photo_url`, `first_profile`, `first_time_login`, `created_at`, `current_grade`, `updated_at`) VALUES
('003TESTSTUDENT0001', 'STU123456', 'John', 'Doe', NULL, 'oamshah2121@gmail.com', '9130937571', '9130937571', NULL, 'Test@123', '468016', '2025-06-20 09:30:59', NULL, '2002-06-21', 'English', 'HSC', 'Mayuri Shah', 'Pune', 'Pune', 'MH', '411041', 'IN', 'Active', '/shreeclasses-api/public/uploads/photo_003TESTSTUDENT0001_1750002004.jpg', 1, 0, '2025-06-15 14:16:04', 7, '2025-06-17 00:20:49'),
('28E805', '28E805', 'Oam', 'Shah', NULL, '', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, NULL, '2025-06-20 06:34:55', NULL, NULL),
('40F215', '40F215', 'Oam', 'Shah', NULL, '', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, NULL, '2025-06-20 06:42:37', NULL, NULL),
('4F4F98', '4F4F98', 'Oam', 'Shah', NULL, '', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, NULL, '2025-06-20 06:46:57', NULL, NULL),
('5F667C', '5F667C', 'Oam', 'Shah', NULL, '', NULL, NULL, NULL, 'Test@123', '114437', '2025-06-19 14:05:45', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, 0, '2025-06-19 09:57:20', NULL, NULL),
('67EBE7', '67EBE7', 'Oam', 'Shah', NULL, '', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, NULL, '2025-06-20 06:31:30', NULL, NULL),
('684fa5fa79380', 'STD4568', 'Oam', 'Shahgh', NULL, '', '', '', '', NULL, NULL, NULL, '0000-00-00', NULL, '', '', '', '', '', '', '', '', 'Active', NULL, NULL, NULL, '2025-06-16 05:04:58', NULL, '2025-06-16 10:35:12'),
('7089A6', '7089A6', 'Oam', 'Shah', NULL, '', NULL, NULL, NULL, 'Test@123', '428562', '2025-06-19 06:37:27', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, '2025-06-19 04:26:46', NULL, NULL),
('80A44C', '80A44C', 'Oam', 'Shah', NULL, '', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, NULL, '2025-06-20 06:38:47', NULL, NULL),
('D84B70', 'D84B70', 'Oam', 'Shah', NULL, '1032202220@mitwpu.edu.in', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, NULL, '2025-06-20 07:51:21', NULL, NULL),
('DAD35D', 'DAD35D', 'Oam', 'Shah', NULL, 'oash@ciklum.com', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, NULL, '2025-06-20 06:44:18', NULL, NULL),
('DAFC02', 'DAFC02', 'Max', 'Son', NULL, 'shahoam21@gmail.com', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-06-19 03:42:15', NULL, NULL),
('E5D1A1', 'E5D1A1', 'Oam', 'Shah', NULL, '', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, NULL, '2025-06-20 06:46:08', NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `student_otp_verification`
--

CREATE TABLE `student_otp_verification` (
  `id` int(11) NOT NULL,
  `student_id` varchar(10) DEFAULT NULL,
  `first_name` varchar(100) DEFAULT NULL,
  `last_name` varchar(100) DEFAULT NULL,
  `email` varchar(150) DEFAULT NULL,
  `otp_code` varchar(6) DEFAULT NULL,
  `otp_expiry` datetime DEFAULT NULL,
  `auth_code` varchar(50) DEFAULT NULL,
  `created_at` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `student_otp_verification`
--

INSERT INTO `student_otp_verification` (`id`, `student_id`, `first_name`, `last_name`, `email`, `otp_code`, `otp_expiry`, `auth_code`, `created_at`) VALUES
(1, '4DC508', 'Oam', 'Shahtuu', 'oash@ciklum.com', '421481', '2025-06-19 06:15:37', NULL, '2025-06-19 09:35:37'),
(2, '4AA8EA', 'Oam', 'Shahyuhffhh', 'oam@ciklum.com', '884584', '2025-06-19 06:18:30', NULL, '2025-06-19 09:38:30'),
(3, 'E18509', 'Oam', 'Shahyuhffhh', 'oash@ciklum.com', '113459', '2025-06-19 06:18:58', NULL, '2025-06-19 09:38:58'),
(4, '6ACE2F', 'Oam', 'Shahyuhffhh', 'oash@ciklum.com', '803397', '2025-06-19 06:20:47', NULL, '2025-06-19 09:40:47'),
(5, '60B5BB', 'Oam', 'Shahtyff', 'oash@ciklum.com', '177862', '2025-06-19 06:21:50', NULL, '2025-06-19 09:41:50'),
(6, 'BFC969', 'Oam', 'Shah', 'oash@ciklum.com', '645051', '2025-06-19 06:24:09', NULL, '2025-06-19 09:44:09'),
(7, '869B63', 'Oam', 'Shah', 'oam@ciklum.com', '177649', '2025-06-19 06:26:21', NULL, '2025-06-19 09:46:21'),
(11, '22D950', 'Oam', 'Shah', 'oash@ciklum.com', '368631', '2025-06-19 07:22:58', NULL, '2025-06-19 10:42:58'),
(12, '39A584', 'Oam', 'Shah', 'oash@ciklum.com', '120443', '2025-06-19 07:23:17', NULL, '2025-06-19 10:43:17'),
(13, '663232', 'Oam', 'Shah', 'oash@ciklum.com', '515817', '2025-06-19 07:25:06', NULL, '2025-06-19 10:45:06'),
(14, 'D49975', 'Oam', 'Shah', 'oash@ciklum.com', '962053', '2025-06-19 07:25:16', NULL, '2025-06-19 10:45:16'),
(15, 'C9C923', 'Oam', 'Shah', 'oash@ciklum.com', '981828', '2025-06-19 07:25:38', NULL, '2025-06-19 10:45:38'),
(16, '47579E', 'Oam', 'Shah', 'oash@ciklum.com', '469305', '2025-06-19 07:25:52', NULL, '2025-06-19 10:45:52'),
(17, 'E8DEDC', 'Oam', 'Shah', 'oash@ciklum.com', '607925', '2025-06-19 07:26:25', NULL, '2025-06-19 10:46:25'),
(18, 'D43CF0', 'Oam', 'Shah', 'oash@ciklum.com', '846933', '2025-06-19 07:27:31', NULL, '2025-06-19 10:47:31'),
(19, 'D45C77', 'Oam', 'Shah', 'oash@ciklum.com', '156150', '2025-06-19 07:28:16', NULL, '2025-06-19 10:48:16'),
(20, '327765', 'Oam', 'Shah', 'oash@ciklum.com', '693252', '2025-06-19 07:28:50', NULL, '2025-06-19 10:48:50'),
(21, 'C8BE6F', 'Oam', 'Shah', 'oash@ciklum.com', '691450', '2025-06-19 12:00:19', NULL, '2025-06-19 15:20:19'),
(22, 'F1C82D', 'Oam', 'Shah', 'oash@ciklum.com', '343209', '2025-06-19 12:02:13', NULL, '2025-06-19 15:22:13'),
(24, '568415', 'Oam', 'Shah', 'oash@ciklum.com', '855569', '2025-06-20 08:35:14', NULL, '2025-06-20 11:55:14'),
(25, '26EE9E', 'Oam', 'Shah', 'oash@ciklum.com', '443707', '2025-06-20 08:36:37', NULL, '2025-06-20 11:56:37'),
(26, 'AFF154', 'Oam', 'Shah', 'oash@ciklum.com', '530388', '2025-06-20 08:36:52', NULL, '2025-06-20 11:56:52'),
(27, '9BA71F', 'Oam', 'Shah', 'oash@ciklum.com', '400274', '2025-06-20 08:38:33', NULL, '2025-06-20 11:58:33'),
(35, 'A72F0C', 'Oam', 'Shah', '1032202220@mitwpu.edu.in', '364076', '2025-06-20 08:58:44', NULL, '2025-06-20 12:18:44'),
(37, '1F51D6', 'Oam', 'Shah', '1032202220@mitwpu.edu.in', '895985', '2025-06-20 09:52:49', NULL, '2025-06-20 13:12:49');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `admin_users`
--
ALTER TABLE `admin_users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `username` (`username`);

--
-- Indexes for table `attendance_log`
--
ALTER TABLE `attendance_log`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `unique_attendance` (`student_id`,`attendance_date`);

--
-- Indexes for table `attendance_run_log`
--
ALTER TABLE `attendance_run_log`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `auth_codes`
--
ALTER TABLE `auth_codes`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `code` (`code`);

--
-- Indexes for table `contact_submissions`
--
ALTER TABLE `contact_submissions`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `cookie_logs`
--
ALTER TABLE `cookie_logs`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `leave_assignments`
--
ALTER TABLE `leave_assignments`
  ADD PRIMARY KEY (`id`),
  ADD KEY `student_id` (`student_id`);

--
-- Indexes for table `leave_requests`
--
ALTER TABLE `leave_requests`
  ADD PRIMARY KEY (`id`),
  ADD KEY `student_id` (`student_id`);

--
-- Indexes for table `leave_types`
--
ALTER TABLE `leave_types`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `payment_invoices`
--
ALTER TABLE `payment_invoices`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `public_holidays`
--
ALTER TABLE `public_holidays`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `regularization_requests`
--
ALTER TABLE `regularization_requests`
  ADD PRIMARY KEY (`id`),
  ADD KEY `student_id` (`student_id`);

--
-- Indexes for table `students`
--
ALTER TABLE `students`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `student_otp_verification`
--
ALTER TABLE `student_otp_verification`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `admin_users`
--
ALTER TABLE `admin_users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `attendance_log`
--
ALTER TABLE `attendance_log`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=38;

--
-- AUTO_INCREMENT for table `attendance_run_log`
--
ALTER TABLE `attendance_run_log`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `auth_codes`
--
ALTER TABLE `auth_codes`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `contact_submissions`
--
ALTER TABLE `contact_submissions`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `cookie_logs`
--
ALTER TABLE `cookie_logs`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `leave_assignments`
--
ALTER TABLE `leave_assignments`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `leave_requests`
--
ALTER TABLE `leave_requests`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `leave_types`
--
ALTER TABLE `leave_types`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `payment_invoices`
--
ALTER TABLE `payment_invoices`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `public_holidays`
--
ALTER TABLE `public_holidays`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=32;

--
-- AUTO_INCREMENT for table `regularization_requests`
--
ALTER TABLE `regularization_requests`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=26;

--
-- AUTO_INCREMENT for table `student_otp_verification`
--
ALTER TABLE `student_otp_verification`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=41;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `attendance_log`
--
ALTER TABLE `attendance_log`
  ADD CONSTRAINT `attendance_log_ibfk_1` FOREIGN KEY (`student_id`) REFERENCES `students` (`id`);

--
-- Constraints for table `leave_assignments`
--
ALTER TABLE `leave_assignments`
  ADD CONSTRAINT `leave_assignments_ibfk_1` FOREIGN KEY (`student_id`) REFERENCES `students` (`id`);

--
-- Constraints for table `leave_requests`
--
ALTER TABLE `leave_requests`
  ADD CONSTRAINT `leave_requests_ibfk_1` FOREIGN KEY (`student_id`) REFERENCES `students` (`id`);

--
-- Constraints for table `regularization_requests`
--
ALTER TABLE `regularization_requests`
  ADD CONSTRAINT `regularization_requests_ibfk_1` FOREIGN KEY (`student_id`) REFERENCES `students` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
