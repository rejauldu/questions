-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Dec 10, 2025 at 09:31 AM
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
-- Database: `ict`
--

-- --------------------------------------------------------

--
-- Table structure for table `cache`
--

CREATE TABLE `cache` (
  `key` varchar(255) NOT NULL,
  `value` mediumtext NOT NULL,
  `expiration` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `cache_locks`
--

CREATE TABLE `cache_locks` (
  `key` varchar(255) NOT NULL,
  `owner` varchar(255) NOT NULL,
  `expiration` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `chat_threads`
--

CREATE TABLE `chat_threads` (
  `id` char(26) NOT NULL,
  `user_id` bigint(20) UNSIGNED NOT NULL,
  `title` varchar(255) DEFAULT NULL,
  `is_pending` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` timestamp NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `chat_threads`
--

INSERT INTO `chat_threads` (`id`, `user_id`, `title`, `is_pending`, `created_at`, `updated_at`) VALUES
('01KATVGMX4T03T3KTWJTDYJY87', 1, 'Hi', 0, '2025-11-24 05:55:47', '2025-11-26 02:36:45'),
('01KATW9NVWK82HYV4SJAAPA522', 1, 'Hi', 0, '2025-11-24 06:09:27', '2025-11-26 01:28:11'),
('01KAZETZW07408YPHK0A5JXVXV', 1, 'Are you there?', 0, '2025-11-26 00:50:27', '2025-11-28 02:52:07'),
('01KB4VATTWNHG8Y4K1Q7TG7SJD', 1, 'New Chat', 1, '2025-11-28 03:05:01', '2025-11-28 03:05:01'),
('01KBAHRXT8EK3FGXNZE7WA083E', 2, 'New Chat', 1, '2025-11-30 08:13:23', '2025-11-30 08:13:23');

-- --------------------------------------------------------

--
-- Table structure for table `failed_jobs`
--

CREATE TABLE `failed_jobs` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `uuid` varchar(255) NOT NULL,
  `connection` text NOT NULL,
  `queue` text NOT NULL,
  `payload` longtext NOT NULL,
  `exception` longtext NOT NULL,
  `failed_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `faqs`
--

CREATE TABLE `faqs` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `question` varchar(255) NOT NULL,
  `answer` varchar(200) NOT NULL,
  `frequency` smallint(6) NOT NULL DEFAULT 1,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `faqs`
--

INSERT INTO `faqs` (`id`, `question`, `answer`, `frequency`, `created_at`, `updated_at`) VALUES
(1, 'hi', 'Hello! I\'m your Exam Date Assistant. Please tell me the course name or subject you need information for.', 33, '2025-11-23 21:55:47', '2025-11-28 02:38:45'),
(2, 'hello', 'Hello! I\'m your Exam Date Assistant. Please tell me the course name or subject you need information for.', 5, '2025-11-23 21:56:24', '2025-11-26 02:36:45'),
(3, 'hi do you know me', 'Hi! I\'m an AI exam date assistant created by Rejaul Karim. I don\'t know you personally, but I\'m here to help with your exam schedules for SSC or HSC. What subject or date do you need info on?', 1, '2025-11-24 05:56:16', '2025-11-24 05:56:16'),
(4, 'how are you', 'I\'m doing great, thanks for asking! 😊 I\'m here to help with SSC or HSC exam schedules. What subject, date, or institution do you need info on? (Default is SSC if unclear.)', 1, '2025-11-24 05:59:00', '2025-11-24 05:59:00'),
(5, 'are you there', 'Yes, I\'m here! How can I help you with exam dates or routines today?', 1, '2025-11-28 02:45:20', '2025-11-28 02:45:20'),
(6, 'not working', 'Sorry to hear that! I\'m here and ready to help with exam routines. What specific institution, subject, or date do you need info on? (Default: SSC)', 1, '2025-11-28 02:47:24', '2025-11-28 02:47:24');

-- --------------------------------------------------------

--
-- Table structure for table `institutions`
--

CREATE TABLE `institutions` (
  `id` smallint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `institutions`
--

INSERT INTO `institutions` (`id`, `name`) VALUES
(4, 'BCS/Bangladesh Civil Service'),
(2, 'HSC/Higher Secondary Certificate/এইচএসসি'),
(3, 'NU/National University'),
(1, 'SSC/Secondary School Certificate/এসএসসি');

-- --------------------------------------------------------

--
-- Table structure for table `jobs`
--

CREATE TABLE `jobs` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `queue` varchar(255) NOT NULL,
  `payload` longtext NOT NULL,
  `attempts` tinyint(3) UNSIGNED NOT NULL,
  `reserved_at` int(10) UNSIGNED DEFAULT NULL,
  `available_at` int(10) UNSIGNED NOT NULL,
  `created_at` int(10) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `job_batches`
--

CREATE TABLE `job_batches` (
  `id` varchar(255) NOT NULL,
  `name` varchar(255) NOT NULL,
  `total_jobs` int(11) NOT NULL,
  `pending_jobs` int(11) NOT NULL,
  `failed_jobs` int(11) NOT NULL,
  `failed_job_ids` longtext NOT NULL,
  `options` mediumtext DEFAULT NULL,
  `cancelled_at` int(11) DEFAULT NULL,
  `created_at` int(11) NOT NULL,
  `finished_at` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `messages`
--

CREATE TABLE `messages` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `thread_id` varchar(26) NOT NULL,
  `content` varchar(200) DEFAULT NULL,
  `sender_type` enum('user','bot') NOT NULL,
  `created_at` timestamp NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `messages`
--

INSERT INTO `messages` (`id`, `thread_id`, `content`, `sender_type`, `created_at`, `updated_at`) VALUES
(1, '01KATVGMX4T03T3KTWJTDYJY87', 'Hi', 'user', '2025-11-24 05:55:54', '2025-11-24 05:55:54'),
(2, '01KATVGMX4T03T3KTWJTDYJY87', 'Hello! I\'m your Exam Date Assistant. Please tell me the course name or subject you need information ', 'bot', '2025-11-24 05:55:54', '2025-11-24 05:55:54'),
(3, '01KATVGMX4T03T3KTWJTDYJY87', 'Do you know me?', 'user', '2025-11-24 05:56:05', '2025-11-24 05:56:05'),
(4, '01KATVGMX4T03T3KTWJTDYJY87', 'Hi! I\'m an AI exam date assistant created by Rejaul Karim. I don\'t know you personally, but I\'m here', 'bot', '2025-11-24 05:56:16', '2025-11-24 05:56:16'),
(5, '01KATVGMX4T03T3KTWJTDYJY87', 'Hello', 'user', '2025-11-24 05:56:55', '2025-11-24 05:56:55'),
(6, '01KATVGMX4T03T3KTWJTDYJY87', 'Hello! I\'m your Exam Date Assistant. Please tell me the course name or subject you need information ', 'bot', '2025-11-24 05:56:55', '2025-11-24 05:56:55'),
(7, '01KATVGMX4T03T3KTWJTDYJY87', 'How are you?', 'user', '2025-11-24 05:58:50', '2025-11-24 05:58:50'),
(8, '01KATVGMX4T03T3KTWJTDYJY87', 'I\'m doing great, thanks for asking! 😊 I\'m here to help with SSC or HSC exam schedules. What subject,', 'bot', '2025-11-24 05:59:00', '2025-11-24 05:59:00'),
(11, '01KATW9NVWK82HYV4SJAAPA522', 'Hi', 'user', '2025-11-25 21:02:55', '2025-11-25 21:02:55'),
(12, '01KATW9NVWK82HYV4SJAAPA522', 'Hello! I\'m your Exam Date Assistant. Please tell me the course name or subject you need information for.', 'bot', '2025-11-25 21:02:55', '2025-11-25 21:02:55'),
(13, '01KATW9NVWK82HYV4SJAAPA522', 'Hi', 'user', '2025-11-26 01:21:06', '2025-11-26 01:21:06'),
(14, '01KATW9NVWK82HYV4SJAAPA522', 'Hello! I\'m your Exam Date Assistant. Please tell me the course name or subject you need information for.', 'bot', '2025-11-26 01:21:06', '2025-11-26 01:21:06'),
(15, '01KATW9NVWK82HYV4SJAAPA522', 'Hi', 'user', '2025-11-26 01:25:50', '2025-11-26 01:25:50'),
(16, '01KATW9NVWK82HYV4SJAAPA522', 'Hello! I\'m your Exam Date Assistant. Please tell me the course name or subject you need information for.', 'bot', '2025-11-26 01:25:50', '2025-11-26 01:25:50'),
(17, '01KATW9NVWK82HYV4SJAAPA522', 'Hi', 'user', '2025-11-26 01:25:55', '2025-11-26 01:25:55'),
(18, '01KATW9NVWK82HYV4SJAAPA522', 'Hello! I\'m your Exam Date Assistant. Please tell me the course name or subject you need information for.', 'bot', '2025-11-26 01:25:55', '2025-11-26 01:25:55'),
(19, '01KATW9NVWK82HYV4SJAAPA522', 'Hi', 'user', '2025-11-26 01:26:07', '2025-11-26 01:26:07'),
(20, '01KATW9NVWK82HYV4SJAAPA522', 'Hello! I\'m your Exam Date Assistant. Please tell me the course name or subject you need information for.', 'bot', '2025-11-26 01:26:07', '2025-11-26 01:26:07'),
(21, '01KATW9NVWK82HYV4SJAAPA522', 'Hi', 'user', '2025-11-26 01:28:11', '2025-11-26 01:28:11'),
(22, '01KATW9NVWK82HYV4SJAAPA522', 'Hello! I\'m your Exam Date Assistant. Please tell me the course name or subject you need information for.', 'bot', '2025-11-26 01:28:11', '2025-11-26 01:28:11'),
(23, '01KATVGMX4T03T3KTWJTDYJY87', 'Hi', 'user', '2025-11-26 01:39:31', '2025-11-26 01:39:31'),
(24, '01KATVGMX4T03T3KTWJTDYJY87', 'Hello! I\'m your Exam Date Assistant. Please tell me the course name or subject you need information for.', 'bot', '2025-11-26 01:39:31', '2025-11-26 01:39:31'),
(25, '01KATVGMX4T03T3KTWJTDYJY87', 'Hi', 'user', '2025-11-26 01:39:44', '2025-11-26 01:39:44'),
(26, '01KATVGMX4T03T3KTWJTDYJY87', 'Hello! I\'m your Exam Date Assistant. Please tell me the course name or subject you need information for.', 'bot', '2025-11-26 01:39:44', '2025-11-26 01:39:44'),
(27, '01KATVGMX4T03T3KTWJTDYJY87', 'Hi', 'user', '2025-11-26 01:40:01', '2025-11-26 01:40:01'),
(28, '01KATVGMX4T03T3KTWJTDYJY87', 'Hello! I\'m your Exam Date Assistant. Please tell me the course name or subject you need information for.', 'bot', '2025-11-26 01:40:01', '2025-11-26 01:40:01'),
(29, '01KATVGMX4T03T3KTWJTDYJY87', 'Hello', 'user', '2025-11-26 01:41:14', '2025-11-26 01:41:14'),
(30, '01KATVGMX4T03T3KTWJTDYJY87', 'Hello! I\'m your Exam Date Assistant. Please tell me the course name or subject you need information for.', 'bot', '2025-11-26 01:41:14', '2025-11-26 01:41:14'),
(31, '01KATVGMX4T03T3KTWJTDYJY87', 'Hi', 'user', '2025-11-26 02:20:43', '2025-11-26 02:20:43'),
(32, '01KATVGMX4T03T3KTWJTDYJY87', 'Hello! I\'m your Exam Date Assistant. Please tell me the course name or subject you need information for.', 'bot', '2025-11-26 02:20:43', '2025-11-26 02:20:43'),
(33, '01KATVGMX4T03T3KTWJTDYJY87', 'Hi', 'user', '2025-11-26 02:20:48', '2025-11-26 02:20:48'),
(34, '01KATVGMX4T03T3KTWJTDYJY87', 'Hello! I\'m your Exam Date Assistant. Please tell me the course name or subject you need information for.', 'bot', '2025-11-26 02:20:48', '2025-11-26 02:20:48'),
(35, '01KATVGMX4T03T3KTWJTDYJY87', 'Hi', 'user', '2025-11-26 02:20:53', '2025-11-26 02:20:53'),
(36, '01KATVGMX4T03T3KTWJTDYJY87', 'Hello! I\'m your Exam Date Assistant. Please tell me the course name or subject you need information for.', 'bot', '2025-11-26 02:20:53', '2025-11-26 02:20:53'),
(37, '01KATVGMX4T03T3KTWJTDYJY87', 'Hi', 'user', '2025-11-26 02:22:45', '2025-11-26 02:22:45'),
(38, '01KATVGMX4T03T3KTWJTDYJY87', 'Hello! I\'m your Exam Date Assistant. Please tell me the course name or subject you need information for.', 'bot', '2025-11-26 02:22:45', '2025-11-26 02:22:45'),
(39, '01KATVGMX4T03T3KTWJTDYJY87', 'Hi', 'user', '2025-11-26 02:25:00', '2025-11-26 02:25:00'),
(40, '01KATVGMX4T03T3KTWJTDYJY87', 'Hello! I\'m your Exam Date Assistant. Please tell me the course name or subject you need information for.', 'bot', '2025-11-26 02:25:00', '2025-11-26 02:25:00'),
(41, '01KATVGMX4T03T3KTWJTDYJY87', 'Hi', 'user', '2025-11-26 02:26:18', '2025-11-26 02:26:18'),
(42, '01KATVGMX4T03T3KTWJTDYJY87', 'Hello! I\'m your Exam Date Assistant. Please tell me the course name or subject you need information for.', 'bot', '2025-11-26 02:26:18', '2025-11-26 02:26:18'),
(43, '01KATVGMX4T03T3KTWJTDYJY87', 'Hi', 'user', '2025-11-26 02:26:25', '2025-11-26 02:26:25'),
(44, '01KATVGMX4T03T3KTWJTDYJY87', 'Hello! I\'m your Exam Date Assistant. Please tell me the course name or subject you need information for.', 'bot', '2025-11-26 02:26:25', '2025-11-26 02:26:25'),
(45, '01KATVGMX4T03T3KTWJTDYJY87', 'Hi', 'user', '2025-11-26 02:27:09', '2025-11-26 02:27:09'),
(46, '01KATVGMX4T03T3KTWJTDYJY87', 'Hello! I\'m your Exam Date Assistant. Please tell me the course name or subject you need information for.', 'bot', '2025-11-26 02:27:09', '2025-11-26 02:27:09'),
(47, '01KATVGMX4T03T3KTWJTDYJY87', 'Hi', 'user', '2025-11-26 02:27:26', '2025-11-26 02:27:26'),
(48, '01KATVGMX4T03T3KTWJTDYJY87', 'Hello! I\'m your Exam Date Assistant. Please tell me the course name or subject you need information for.', 'bot', '2025-11-26 02:27:26', '2025-11-26 02:27:26'),
(49, '01KATVGMX4T03T3KTWJTDYJY87', 'Hi', 'user', '2025-11-26 02:29:11', '2025-11-26 02:29:11'),
(50, '01KATVGMX4T03T3KTWJTDYJY87', 'Hello! I\'m your Exam Date Assistant. Please tell me the course name or subject you need information for.', 'bot', '2025-11-26 02:29:11', '2025-11-26 02:29:11'),
(51, '01KATVGMX4T03T3KTWJTDYJY87', 'Hi', 'user', '2025-11-26 02:29:43', '2025-11-26 02:29:43'),
(52, '01KATVGMX4T03T3KTWJTDYJY87', 'Hello! I\'m your Exam Date Assistant. Please tell me the course name or subject you need information for.', 'bot', '2025-11-26 02:29:43', '2025-11-26 02:29:43'),
(53, '01KATVGMX4T03T3KTWJTDYJY87', 'Hi', 'user', '2025-11-26 02:33:08', '2025-11-26 02:33:08'),
(54, '01KATVGMX4T03T3KTWJTDYJY87', 'Hello! I\'m your Exam Date Assistant. Please tell me the course name or subject you need information for.', 'bot', '2025-11-26 02:33:08', '2025-11-26 02:33:08'),
(55, '01KATVGMX4T03T3KTWJTDYJY87', 'Hi', 'user', '2025-11-26 02:33:43', '2025-11-26 02:33:43'),
(56, '01KATVGMX4T03T3KTWJTDYJY87', 'Hello! I\'m your Exam Date Assistant. Please tell me the course name or subject you need information for.', 'bot', '2025-11-26 02:33:43', '2025-11-26 02:33:43'),
(57, '01KATVGMX4T03T3KTWJTDYJY87', 'Hi', 'user', '2025-11-26 02:34:12', '2025-11-26 02:34:12'),
(58, '01KATVGMX4T03T3KTWJTDYJY87', 'Hello! I\'m your Exam Date Assistant. Please tell me the course name or subject you need information for.', 'bot', '2025-11-26 02:34:12', '2025-11-26 02:34:12'),
(59, '01KATVGMX4T03T3KTWJTDYJY87', 'Hi', 'user', '2025-11-26 02:34:21', '2025-11-26 02:34:21'),
(60, '01KATVGMX4T03T3KTWJTDYJY87', 'Hello! I\'m your Exam Date Assistant. Please tell me the course name or subject you need information for.', 'bot', '2025-11-26 02:34:21', '2025-11-26 02:34:21'),
(61, '01KATVGMX4T03T3KTWJTDYJY87', 'Hi', 'user', '2025-11-26 02:36:34', '2025-11-26 02:36:34'),
(62, '01KATVGMX4T03T3KTWJTDYJY87', 'Hello! I\'m your Exam Date Assistant. Please tell me the course name or subject you need information for.', 'bot', '2025-11-26 02:36:34', '2025-11-26 02:36:34'),
(63, '01KATVGMX4T03T3KTWJTDYJY87', 'Hello', 'user', '2025-11-26 02:36:45', '2025-11-26 02:36:45'),
(64, '01KATVGMX4T03T3KTWJTDYJY87', 'Hello! I\'m your Exam Date Assistant. Please tell me the course name or subject you need information for.', 'bot', '2025-11-26 02:36:45', '2025-11-26 02:36:45'),
(65, '01KAZETZW07408YPHK0A5JXVXV', 'Are you there?', 'user', '2025-11-28 02:38:04', '2025-11-28 02:38:04'),
(66, '01KAZETZW07408YPHK0A5JXVXV', 'API Error: {\"message\":\"User not found.\",\"code\":401}', 'bot', '2025-11-28 02:38:07', '2025-11-28 02:38:07'),
(67, '01KAZETZW07408YPHK0A5JXVXV', 'Hi', 'user', '2025-11-28 02:38:45', '2025-11-28 02:38:45'),
(68, '01KAZETZW07408YPHK0A5JXVXV', 'Hello! I\'m your Exam Date Assistant. Please tell me the course name or subject you need information for.', 'bot', '2025-11-28 02:38:45', '2025-11-28 02:38:45'),
(69, '01KAZETZW07408YPHK0A5JXVXV', 'Are you Ready?\\', 'user', '2025-11-28 02:38:56', '2025-11-28 02:38:56'),
(70, '01KAZETZW07408YPHK0A5JXVXV', 'API Error: {\"message\":\"User not found.\",\"code\":401}', 'bot', '2025-11-28 02:38:57', '2025-11-28 02:38:57'),
(71, '01KAZETZW07408YPHK0A5JXVXV', 'Are you there?', 'user', '2025-11-28 02:45:10', '2025-11-28 02:45:10'),
(72, '01KAZETZW07408YPHK0A5JXVXV', 'Yes, I\'m here! How can I help you with exam dates or routines today?', 'bot', '2025-11-28 02:45:20', '2025-11-28 02:45:20'),
(75, '01KAZETZW07408YPHK0A5JXVXV', 'Not working.', 'user', '2025-11-28 02:47:10', '2025-11-28 02:47:10'),
(76, '01KAZETZW07408YPHK0A5JXVXV', 'Sorry to hear that! I\'m here and ready to help with exam routines. What specific institution, subject, or date do you need info on? (Default: SSC)', 'bot', '2025-11-28 02:47:24', '2025-11-28 02:47:24'),
(79, '01KAZETZW07408YPHK0A5JXVXV', 'Ok.', 'user', '2025-11-28 02:48:48', '2025-11-28 02:48:48'),
(80, '01KAZETZW07408YPHK0A5JXVXV', 'Great! I\'m all set with SSC as default (today: 28 Nov 2025). Ask me about any exam date, subject, or routine!', 'bot', '2025-11-28 02:49:05', '2025-11-28 02:49:05'),
(81, '01KAZETZW07408YPHK0A5JXVXV', 'Ok.', 'user', '2025-11-28 02:49:34', '2025-11-28 02:49:34'),
(82, '01KAZETZW07408YPHK0A5JXVXV', 'Perfect! SSC is set as default (today: 28 Nov 2025). Ready for your exam routine questions – what\'s the subject, date, or institution?', 'bot', '2025-11-28 02:49:42', '2025-11-28 02:49:42'),
(83, '01KAZETZW07408YPHK0A5JXVXV', 'You are not that good.', 'user', '2025-11-28 02:49:55', '2025-11-28 02:49:55'),
(84, '01KAZETZW07408YPHK0A5JXVXV', 'I\'m improving every day to better assist with exam routines! SSC is default (today: 28 Nov 2025). What\'s your question about exams, dates, or subjects?', 'bot', '2025-11-28 02:50:04', '2025-11-28 02:50:04'),
(85, '01KAZETZW07408YPHK0A5JXVXV', 'Why are you telling me your default?', 'user', '2025-11-28 02:50:52', '2025-11-28 02:50:52'),
(86, '01KAZETZW07408YPHK0A5JXVXV', 'I mention the default (SSC, today: 28 Nov 2025) to confirm settings for accurate exam routine answers and avoid confusion. What exam date or subject can I help with now?', 'bot', '2025-11-28 02:51:02', '2025-11-28 02:51:02'),
(87, '01KAZETZW07408YPHK0A5JXVXV', 'BCS', 'user', '2025-11-28 02:51:19', '2025-11-28 02:51:19'),
(88, '01KAZETZW07408YPHK0A5JXVXV', 'Got it! Switching to BCS (Bangladesh Civil Service) as default institution (today: 28 Nov 2025). What exam date, subject, or routine details do you need?', 'bot', '2025-11-28 02:51:34', '2025-11-28 02:51:34'),
(89, '01KAZETZW07408YPHK0A5JXVXV', 'exam kobe?', 'user', '2025-11-28 02:51:54', '2025-11-28 02:51:54'),
(90, '01KAZETZW07408YPHK0A5JXVXV', 'Which specific exam are you asking about? Please mention the subject, date, or institution (BCS or SSC?) for accurate routine details. Today is 28 Nov 2025.', 'bot', '2025-11-28 02:52:07', '2025-11-28 02:52:07');

-- --------------------------------------------------------

--
-- Table structure for table `migrations`
--

CREATE TABLE `migrations` (
  `id` int(10) UNSIGNED NOT NULL,
  `migration` varchar(255) NOT NULL,
  `batch` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `migrations`
--

INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES
(1, '0001_01_01_000000_create_users_table', 1),
(2, '0001_01_01_000001_create_cache_table', 1),
(3, '0001_01_01_000002_create_jobs_table', 1),
(4, '2025_11_20_055157_create_posts_table', 2),
(5, '2025_11_23_105255_create_subjects_table', 4),
(6, '2025_11_23_105714_create_institutions_table', 5),
(7, '2025_11_24_030657_create_faqs_table', 7),
(8, '2025_11_23_061040_create_chat_threads_table', 8),
(9, '2025_11_23_061041_create_messages_table', 9);

-- --------------------------------------------------------

--
-- Table structure for table `password_reset_tokens`
--

CREATE TABLE `password_reset_tokens` (
  `email` varchar(255) NOT NULL,
  `token` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `posts`
--

CREATE TABLE `posts` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `article` text DEFAULT NULL,
  `a` varchar(255) DEFAULT NULL,
  `b` varchar(255) DEFAULT NULL,
  `c` varchar(255) DEFAULT NULL,
  `d` varchar(255) DEFAULT NULL,
  `answer` varchar(255) DEFAULT NULL,
  `subject_id` smallint(6) DEFAULT NULL,
  `topic` varchar(255) DEFAULT NULL,
  `sub_topic` varchar(255) DEFAULT NULL,
  `section` varchar(255) DEFAULT NULL,
  `sub_section` varchar(255) DEFAULT NULL,
  `category` varchar(255) DEFAULT NULL,
  `board` varchar(255) DEFAULT NULL,
  `year` varchar(255) DEFAULT NULL,
  `class` varchar(255) DEFAULT NULL,
  `institution_id` smallint(6) NOT NULL,
  `url` varchar(250) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `posts`
--

INSERT INTO `posts` (`id`, `article`, `a`, `b`, `c`, `d`, `answer`, `subject_id`, `topic`, `sub_topic`, `section`, `sub_section`, `category`, `board`, `year`, `class`, `institution_id`, `url`, `created_at`, `updated_at`) VALUES
(1, '\\(\\frac{x}{y}\\)', 'aa', 'bb', 'cc', 'dd', 'aa', 1, NULL, NULL, NULL, NULL, NULL, NULL, '2025', NULL, 2, NULL, '2025-11-21 09:06:25', '2025-11-21 09:06:25'),
(2, '\\(\\frac{x}{y}\\)', 'aa', 'bb', 'cc', 'dd', 'aa', 2, NULL, NULL, NULL, NULL, NULL, NULL, '2025', NULL, 2, NULL, '2025-11-21 09:10:03', '2025-11-21 09:10:03'),
(3, '\\(\\frac{abcd}{xyz}\\)', 'a', 'a', 'a', 'a', 'a', 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, NULL, '2025-11-21 09:10:43', '2025-11-21 09:10:43'),
(4, 'মি. রফিক একজন বিখ্যাত গবেষক। তিনি এবং তার দল পার্পল কালার (বেগুনি রং) এর উফশী ধান আবিষ্কার করেন। মি. রফিকের বন্ধু শফিক একদিন তার ল্যাবে প্রবেশের জন্য হাত দিতে গেলেই দরজাটি না খুলে এলার্ম বেজে উঠে। কিন্তু মি. রফিক এসে দরজার সামনে দাঁড়াতেই সেই দরজা খুলে যায়।', 'ই-লার্নিং কী?', '\"প্রযুক্তি ব্যবহার করে সরাসরি রোগাক্রান্ত কোষে চিকিৎসা প্রদান সম্ভব\" — ব্যাখ্যা কর।', 'মি. রফিক দরজার সামনে দাঁড়ালে দরজাটি খুলে গেলেও শফিক হাত দিলেও দরজা খুলল না কেন? ব্যাখ্যা কর।', 'উফশী ধান আবিষ্কারের প্রযুক্তিটির কাজ করার পদ্ধতি বর্ণনা কর এবং প্রযুক্তিটি কৃষিক্ষেত্রে কী কী অবদান রাখছে তা উল্লেখ কর।', 'a', 2, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, NULL, '2025-11-21 09:11:43', '2025-11-21 09:11:43'),
(5, 'HSC ICT', NULL, NULL, NULL, NULL, NULL, 1, NULL, NULL, NULL, NULL, NULL, NULL, '2025', '1', 2, 'questions/x0iPYYoMrFSYo8OrXsOQa7LhHGHTsToWI3vhBIK5-1HEjO9.webp', '2025-11-27 05:26:57', '2025-11-27 05:26:57'),
(6, 'HSC Bangla', NULL, NULL, NULL, NULL, NULL, 1, NULL, NULL, NULL, NULL, NULL, NULL, '2025', '1', 2, 'questions/vxvwaaltyL2ocFjwaZnawNgNSwTgsCwejMMcI26N-ncpj6X.webp', '2025-11-27 05:42:36', '2025-11-27 05:42:36');

-- --------------------------------------------------------

--
-- Table structure for table `sessions`
--

CREATE TABLE `sessions` (
  `id` varchar(255) NOT NULL,
  `user_id` bigint(20) UNSIGNED DEFAULT NULL,
  `ip_address` varchar(45) DEFAULT NULL,
  `user_agent` text DEFAULT NULL,
  `payload` longtext NOT NULL,
  `last_activity` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `sessions`
--

INSERT INTO `sessions` (`id`, `user_id`, `ip_address`, `user_agent`, `payload`, `last_activity`) VALUES
('k41eVv5Xqt7CaPkdGXFReHtvo6eDB4PvLwALJkwI', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36 Edg/143.0.0.0', 'YTozOntzOjY6Il90b2tlbiI7czo0MDoid1hlTnRxY0V1VzhOYkhhRTRMRFZ6V1pEOEdxV3RKM1NWU1FldUF1YyI7czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6MTA6Imh0dHA6Ly9pY3QiO3M6NToicm91dGUiO3M6NDoiaG9tZSI7fX0=', 1765355415);

-- --------------------------------------------------------

--
-- Table structure for table `subjects`
--

CREATE TABLE `subjects` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `class` varchar(255) NOT NULL,
  `institution_id` smallint(5) UNSIGNED DEFAULT NULL,
  `year` smallint(5) UNSIGNED NOT NULL,
  `exam_at` datetime DEFAULT NULL COMMENT 'Scheduled date and time of the exam',
  `description` text DEFAULT NULL,
  `status` tinyint(4) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `subjects`
--

INSERT INTO `subjects` (`id`, `name`, `class`, `institution_id`, `year`, `exam_at`, `description`, `status`) VALUES
(1, 'Bangla', '', 2, 2025, '2025-11-26 13:00:00', NULL, 0),
(2, 'English', '', 2, 2025, '2025-11-27 13:30:00', 'Tentative Date: is 27th November 2025', 0),
(3, 'Bangla', '', 1, 2025, '2025-12-25 13:00:00', NULL, 0),
(4, 'English', '', 1, 2025, '2025-12-27 13:00:00', NULL, 0);

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `phone` varchar(15) DEFAULT NULL,
  `email` varchar(255) DEFAULT NULL,
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `password` varchar(255) NOT NULL,
  `remember_token` varchar(100) DEFAULT NULL,
  `institution_id` smallint(6) DEFAULT NULL,
  `role` varchar(10) NOT NULL DEFAULT 'user',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `name`, `phone`, `email`, `email_verified_at`, `password`, `remember_token`, `institution_id`, `role`, `created_at`, `updated_at`) VALUES
(1, 'Admin', '01924974960', 'rejauldu@gmail.com', NULL, '$2y$12$5XA6xNaU1zYFvkfg4ihszuvcx2V7G7u6AFOcFYkthgdawu12w8Bz6', NULL, 1, 'admin', NULL, NULL),
(2, 'Rejaul', '01817338234', NULL, NULL, '$2y$12$z049Yj3ADkBS.i6wy3zjMuEgJ8UyWxOqtYAwGWPZlSjfOU/ewzpEi', NULL, NULL, 'user', '2025-11-30 08:09:49', '2025-11-30 08:09:49');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `cache`
--
ALTER TABLE `cache`
  ADD PRIMARY KEY (`key`);

--
-- Indexes for table `cache_locks`
--
ALTER TABLE `cache_locks`
  ADD PRIMARY KEY (`key`);

--
-- Indexes for table `chat_threads`
--
ALTER TABLE `chat_threads`
  ADD PRIMARY KEY (`id`),
  ADD KEY `chat_threads_user_id_index` (`user_id`);

--
-- Indexes for table `failed_jobs`
--
ALTER TABLE `failed_jobs`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `failed_jobs_uuid_unique` (`uuid`);

--
-- Indexes for table `faqs`
--
ALTER TABLE `faqs`
  ADD PRIMARY KEY (`id`),
  ADD KEY `faqs_question_index` (`question`);

--
-- Indexes for table `institutions`
--
ALTER TABLE `institutions`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `institutions_name_unique` (`name`);

--
-- Indexes for table `jobs`
--
ALTER TABLE `jobs`
  ADD PRIMARY KEY (`id`),
  ADD KEY `jobs_queue_index` (`queue`);

--
-- Indexes for table `job_batches`
--
ALTER TABLE `job_batches`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `messages`
--
ALTER TABLE `messages`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `migrations`
--
ALTER TABLE `migrations`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `password_reset_tokens`
--
ALTER TABLE `password_reset_tokens`
  ADD PRIMARY KEY (`email`);

--
-- Indexes for table `posts`
--
ALTER TABLE `posts`
  ADD PRIMARY KEY (`id`),
  ADD KEY `institution_id` (`institution_id`);

--
-- Indexes for table `sessions`
--
ALTER TABLE `sessions`
  ADD PRIMARY KEY (`id`),
  ADD KEY `sessions_user_id_index` (`user_id`),
  ADD KEY `sessions_last_activity_index` (`last_activity`);

--
-- Indexes for table `subjects`
--
ALTER TABLE `subjects`
  ADD PRIMARY KEY (`id`),
  ADD KEY `subjects_institution_id_index` (`institution_id`),
  ADD KEY `subjects_class_year_index` (`class`,`year`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `users_phone_unique` (`phone`),
  ADD UNIQUE KEY `users_email_unique` (`email`),
  ADD KEY `institution_id` (`institution_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `failed_jobs`
--
ALTER TABLE `failed_jobs`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `faqs`
--
ALTER TABLE `faqs`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `institutions`
--
ALTER TABLE `institutions`
  MODIFY `id` smallint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `jobs`
--
ALTER TABLE `jobs`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `messages`
--
ALTER TABLE `messages`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=91;

--
-- AUTO_INCREMENT for table `migrations`
--
ALTER TABLE `migrations`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT for table `posts`
--
ALTER TABLE `posts`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `subjects`
--
ALTER TABLE `subjects`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
