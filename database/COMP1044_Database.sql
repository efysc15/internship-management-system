-- phpMyAdmin SQL Dump
-- version 5.2.3
-- https://www.phpmyadmin.net/
--
-- Host: localhost:8889
-- Generation Time: Mar 26, 2026 at 08:32 AM
-- Server version: 8.0.44
-- PHP Version: 8.3.30

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `COMP1044_Database`
--

-- --------------------------------------------------------

--
-- Table structure for table `assessments`
--

CREATE TABLE `assessments` (
  `assessment_id` int NOT NULL,
  `internship_id` int NOT NULL,
  `assessor_id` int NOT NULL,
  `task_project` decimal(5,2) NOT NULL,
  `health_safety` decimal(5,2) NOT NULL,
  `theory_application` decimal(5,2) NOT NULL,
  `report_presentation` decimal(5,2) NOT NULL,
  `language_clarity` decimal(5,2) NOT NULL,
  `lifelong_learning` decimal(5,2) NOT NULL,
  `project_management` decimal(5,2) NOT NULL,
  `time_management` decimal(5,2) NOT NULL,
  `total_score` decimal(5,2) NOT NULL,
  `comments` text,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Table structure for table `internships`
--

CREATE TABLE `internships` (
  `internship_id` int NOT NULL,
  `student_id` int NOT NULL,
  `assessor_id` int NOT NULL,
  `company_name` varchar(255) NOT NULL,
  `supervisor_name` varchar(255) DEFAULT NULL,
  `duration` int NOT NULL,
  `start_date` date NOT NULL,
  `end_date` date NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Table structure for table `notifications`
--

CREATE TABLE `notifications` (
  `id` int NOT NULL,
  `assessor_id` int NOT NULL,
  `message` text NOT NULL,
  `is_read` tinyint(1) DEFAULT '0',
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Table structure for table `students`
--

CREATE TABLE `students` (
  `student_id` int NOT NULL,
  `matric_no` varchar(10) NOT NULL,
  `student_name` varchar(255) NOT NULL,
  `programme` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `user_id` int NOT NULL,
  `username` varchar(100) NOT NULL,
  `full_name` varchar(255) NOT NULL,
  `role` enum('admin','assessor') NOT NULL,
  `password` varchar(255) NOT NULL,
  `is_default_password` tinyint(1) DEFAULT '1',
  `created_by` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `last_login` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`user_id`, `username`, `full_name`, `role`, `password`, `is_default_password`, `created_by`, `created_at`, `last_login`) VALUES
(1, 'admin', 'Admin User', 'admin', '$2y$10$do.YjJzI2nvE0tta2z2fDeCr8X2nk5zMaEG4N9S/v.A5F68/cDfMe', 0, 'System', '2026-03-26 08:29:59', '2026-03-26 08:30:07'),
(2, 'assessor1', 'John Tan', 'assessor', '$2y$10$uXjB1.INOwVcQYaw.MHO5u8x56W39HqAIAzgLfyfrlWUYKZxkOQMC', 0, 'Admin User', '2026-03-26 08:30:20', '2026-03-26 08:31:00');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `assessments`
--
ALTER TABLE `assessments`
  ADD PRIMARY KEY (`assessment_id`);

--
-- Indexes for table `internships`
--
ALTER TABLE `internships`
  ADD PRIMARY KEY (`internship_id`);

--
-- Indexes for table `notifications`
--
ALTER TABLE `notifications`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `students`
--
ALTER TABLE `students`
  ADD PRIMARY KEY (`student_id`),
  ADD UNIQUE KEY `matric_no` (`matric_no`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`user_id`),
  ADD UNIQUE KEY `username` (`username`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `assessments`
--
ALTER TABLE `assessments`
  MODIFY `assessment_id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `internships`
--
ALTER TABLE `internships`
  MODIFY `internship_id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `notifications`
--
ALTER TABLE `notifications`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `students`
--
ALTER TABLE `students`
  MODIFY `student_id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `user_id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
