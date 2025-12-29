-- phpMyAdmin SQL Dump
-- version 5.2.2
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Generation Time: Dec 29, 2025 at 01:09 PM
-- Server version: 10.6.23-MariaDB
-- PHP Version: 8.4.16

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `examdaoc_db`
--

-- --------------------------------------------------------

--
-- Table structure for table `answers`
--

CREATE TABLE `answers` (
  `id` int(11) NOT NULL,
  `text` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- --------------------------------------------------------

--
-- Table structure for table `boards`
--

CREATE TABLE `boards` (
  `id` int(11) NOT NULL,
  `name` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `boards`
--

INSERT INTO `boards` (`id`, `name`) VALUES
(1, 'Dhaka'),
(2, 'Chittagong'),
(3, 'Comilla'),
(4, 'Rajshahi'),
(5, 'Jessore'),
(6, 'Sylhet'),
(7, 'Barisal'),
(8, 'Mymensingh'),
(9, 'Dinajpur');

-- --------------------------------------------------------

--
-- Table structure for table `cache`
--

CREATE TABLE `cache` (
  `key` varchar(255) NOT NULL,
  `value` mediumtext NOT NULL,
  `expiration` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `cache`
--

INSERT INTO `cache` (`key`, `value`, `expiration`) VALUES
('examdao-cache-shareful islam|103.167.15.26', 'i:1;', 1766668223),
('examdao-cache-shareful islam|103.167.15.26:timer', 'i:1766668223;', 1766668223);

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
('01KB4VATTWNHG8Y4K1Q7TG7SJD', 1, 'Hsc exam kobe?', 0, '2025-11-28 03:05:01', '2025-12-17 02:50:31'),
('01KBAHRXT8EK3FGXNZE7WA083E', 2, 'Hi', 0, '2025-11-30 08:13:23', '2025-12-17 03:37:35'),
('01KCNR2K0ATPR3SK3D9GHS8Z6F', 1, 'HSC exam kobe?', 0, '2025-12-17 02:50:52', '2025-12-17 02:51:05'),
('01KCNTSHX9Y3NES8J6D7EMZ4ZF', 2, 'Hi', 0, '2025-12-17 03:38:22', '2025-12-17 09:04:02'),
('01KCPDEDY053JCMFG4CTXGA1WS', 2, 'Any exam?', 0, '2025-12-17 09:04:20', '2025-12-17 20:16:29'),
('01KCQKYG23EVABVFRM0TN1HTM7', 2, 'Who created you?', 0, '2025-12-17 20:17:13', '2025-12-17 20:17:30'),
('01KCQM3T3C1CSM2Y7M97PEZEMJ', 2, 'New Chat', 1, '2025-12-17 20:20:07', '2025-12-17 20:20:07'),
('01KCR01YT6A697MPZKBDV51Q2P', 1, 'New Chat', 1, '2025-12-17 23:48:49', '2025-12-17 23:48:49'),
('01KD80XM2Q79XN2BNK8NA6655E', 3, 'New Chat', 1, '2025-12-24 05:11:46', '2025-12-24 05:11:46');

-- --------------------------------------------------------

--
-- Table structure for table `comments`
--

CREATE TABLE `comments` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `post_id` bigint(20) UNSIGNED NOT NULL,
  `user_id` bigint(20) UNSIGNED DEFAULT NULL,
  `body` text NOT NULL,
  `is_approved` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

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
(1, 'hi', 'Hello! I\'m your Exam Date Assistant. Please tell me the course name or subject you need information for.', 36, '2025-11-23 21:55:47', '2025-12-17 20:16:29'),
(2, 'hello', 'Hello! I\'m your Exam Date Assistant. Please tell me the course name or subject you need information for.', 6, '2025-11-23 21:56:24', '2025-12-17 09:04:02'),
(3, 'hi do you know me', 'Hi! I\'m an AI exam date assistant created by Rejaul Karim. I don\'t know you personally, but I\'m here to help with your exam schedules for SSC or HSC. What subject or date do you need info on?', 1, '2025-11-24 05:56:16', '2025-11-24 05:56:16'),
(4, 'how are you', 'I\'m doing great, thanks for asking! 😊 I\'m here to help with SSC or HSC exam schedules. What subject, date, or institution do you need info on? (Default is SSC if unclear.)', 1, '2025-11-24 05:59:00', '2025-11-24 05:59:00'),
(5, 'are you there', 'Yes, I\'m here! How can I help you with exam dates or routines today?', 1, '2025-11-28 02:45:20', '2025-11-28 02:45:20'),
(6, 'not working', 'Sorry to hear that! I\'m here and ready to help with exam routines. What specific institution, subject, or date do you need info on? (Default: SSC)', 1, '2025-11-28 02:47:24', '2025-11-28 02:47:24'),
(7, 'who created you', 'I was created by Rejaul Karim.', 1, '2025-12-17 20:17:30', '2025-12-17 20:17:30');

-- --------------------------------------------------------

--
-- Table structure for table `institutions`
--

CREATE TABLE `institutions` (
  `id` smallint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `slug` varchar(30) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `institutions`
--

INSERT INTO `institutions` (`id`, `name`, `slug`) VALUES
(1, 'SSC/Secondary School Certificate/এসএসসি', 'ssc'),
(2, 'HSC/Higher Secondary Certificate/এইচএসসি', 'hsc'),
(3, 'NU/National University', 'nu'),
(4, 'BCS/Bangladesh Civil Service', 'bcs');

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
(90, '01KAZETZW07408YPHK0A5JXVXV', 'Which specific exam are you asking about? Please mention the subject, date, or institution (BCS or SSC?) for accurate routine details. Today is 28 Nov 2025.', 'bot', '2025-11-28 02:52:07', '2025-11-28 02:52:07'),
(91, '01KB4VATTWNHG8Y4K1Q7TG7SJD', 'Hsc exam kobe?', 'user', '2025-12-17 02:50:30', '2025-12-17 02:50:30'),
(92, '01KB4VATTWNHG8Y4K1Q7TG7SJD', 'API Error: {\"message\":\"User not found.\",\"code\":401}', 'bot', '2025-12-17 02:50:31', '2025-12-17 02:50:31'),
(93, '01KCNR2K0ATPR3SK3D9GHS8Z6F', 'HSC exam kobe?', 'user', '2025-12-17 02:50:59', '2025-12-17 02:50:59'),
(94, '01KCNR2K0ATPR3SK3D9GHS8Z6F', 'API Error: {\"message\":\"User not found.\",\"code\":401}', 'bot', '2025-12-17 02:50:59', '2025-12-17 02:50:59'),
(95, '01KCNR2K0ATPR3SK3D9GHS8Z6F', 'SSC?', 'user', '2025-12-17 02:51:05', '2025-12-17 02:51:05'),
(96, '01KCNR2K0ATPR3SK3D9GHS8Z6F', 'API Error: {\"message\":\"User not found.\",\"code\":401}', 'bot', '2025-12-17 02:51:05', '2025-12-17 02:51:05'),
(97, '01KBAHRXT8EK3FGXNZE7WA083E', 'Hi', 'user', '2025-12-17 03:37:35', '2025-12-17 03:37:35'),
(98, '01KBAHRXT8EK3FGXNZE7WA083E', 'Hello! I\'m your Exam Date Assistant. Please tell me the course name or subject you need information for.', 'bot', '2025-12-17 03:37:35', '2025-12-17 03:37:35'),
(99, '01KCNTSHX9Y3NES8J6D7EMZ4ZF', 'Hi', 'user', '2025-12-17 09:03:47', '2025-12-17 09:03:47'),
(100, '01KCNTSHX9Y3NES8J6D7EMZ4ZF', 'Hello! I\'m your Exam Date Assistant. Please tell me the course name or subject you need information for.', 'bot', '2025-12-17 09:03:47', '2025-12-17 09:03:47'),
(101, '01KCNTSHX9Y3NES8J6D7EMZ4ZF', 'Hello', 'user', '2025-12-17 09:04:02', '2025-12-17 09:04:02'),
(102, '01KCNTSHX9Y3NES8J6D7EMZ4ZF', 'Hello! I\'m your Exam Date Assistant. Please tell me the course name or subject you need information for.', 'bot', '2025-12-17 09:04:02', '2025-12-17 09:04:02'),
(103, '01KCPDEDY053JCMFG4CTXGA1WS', 'Any exam?', 'user', '2025-12-17 20:15:42', '2025-12-17 20:15:42'),
(104, '01KCPDEDY053JCMFG4CTXGA1WS', 'For BCS/Bangladesh Civil Service, there are no exams scheduled in the available routine data. Could you specify the institution, subject, or date you\'re asking about?', 'bot', '2025-12-17 20:15:51', '2025-12-17 20:15:51'),
(106, '01KCPDEDY053JCMFG4CTXGA1WS', 'Hi', 'user', '2025-12-17 20:16:29', '2025-12-17 20:16:29'),
(107, '01KCPDEDY053JCMFG4CTXGA1WS', 'Hello! I\'m your Exam Date Assistant. Please tell me the course name or subject you need information for.', 'bot', '2025-12-17 20:16:29', '2025-12-17 20:16:29'),
(109, '01KCQKYG23EVABVFRM0TN1HTM7', 'Who created you?', 'user', '2025-12-17 20:17:26', '2025-12-17 20:17:26'),
(110, '01KCQKYG23EVABVFRM0TN1HTM7', 'I was created by Rejaul Karim.', 'bot', '2025-12-17 20:17:30', '2025-12-17 20:17:30');

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
(9, '2025_11_23_061041_create_messages_table', 9),
(10, '2025_12_11_053023_create_comments_table', 10);

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
  `ans` varchar(1) DEFAULT NULL,
  `explanation` varchar(4000) DEFAULT NULL,
  `answer_id` int(11) DEFAULT NULL,
  `subject_id` smallint(6) DEFAULT NULL,
  `chapter` varchar(255) DEFAULT NULL,
  `category` varchar(255) DEFAULT NULL,
  `board_id` tinytext DEFAULT NULL,
  `year` varchar(255) DEFAULT NULL,
  `class` varchar(255) DEFAULT NULL,
  `institution_id` smallint(6) NOT NULL,
  `image1` varchar(250) DEFAULT NULL,
  `image2` varchar(250) DEFAULT NULL,
  `image3` varchar(250) DEFAULT NULL,
  `image4` varchar(250) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `posts`
--

INSERT INTO `posts` (`id`, `article`, `a`, `b`, `c`, `d`, `ans`, `explanation`, `answer_id`, `subject_id`, `chapter`, `category`, `board_id`, `year`, `class`, `institution_id`, `image1`, `image2`, `image3`, `image4`, `created_at`, `updated_at`) VALUES
(1, 'HSC ICT SYLHET BOARD CQ QUESTION-2024 | এইচএসসি আইসিটি সিলেট বোর্ড ২০২৪ সালের সৃজনশীল প্রশ্ন', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 50, NULL, 'CQ', '6', '2024', NULL, 2, 'images/questions/hsc-ict-sylhet-board-2024-1-1.webp', NULL, NULL, NULL, '2025-12-18 09:36:45', '2025-12-18 09:36:45'),
(2, 'HSC ICT SYLHET BOARD MCQ QUESTION-2024 | এইচএসসি আইসিটি সিলেট বোর্ড ২০২৪ সালের এমসিকিউ প্রশ্ন', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 50, NULL, 'CQ', '6', '2024', NULL, 2, 'images/questions/hsc-ict-sylhet-board-2024-1-2.webp', NULL, NULL, NULL, '2025-12-19 10:52:37', '2025-12-19 10:52:37'),
(3, 'মেডিকেল কলেজের শিক্ষক ডা. রিফাত তার শিক্ষার্থীদের ন্যূনতম ধকল সহিষ্ণু, রক্তপাতহীন চিকিৎসা পদ্ধতির অভিজ্ঞতা লাভের উদ্দেশ্যে একটি সিমুলেটেড পরিবেশে অপারেশন প্রক্রিয়া শেখালেন।', 'রোবটিক্স কী?', 'খাদ্যের গুণাগুণ রক্ষার্থে ব্যবহৃত প্রযুক্তি ব্যাখ্যা কর।', 'উদ্দীপকে উল্লিখিত চিকিৎসা পদ্ধতিটি ব্যাখ্যা কর।', 'প্রাত্যহিক জীবনে উদ্দীপকে উল্লিখিত পরিবেশের প্রভাব বিশ্লেষণ কর।', NULL, NULL, NULL, 50, '১ম অধ্যায়', 'CQ', '6', '2024', NULL, 2, NULL, NULL, NULL, NULL, '2025-12-21 04:06:39', '2025-12-21 04:06:39'),
(4, 'সুমন স্যার শ্রেণিকক্ষে ক্যারেক্টার-বাই-ক্যারেক্টার আকারে ডেটা ট্রান্সমিশন মেখড় সম্পর্কে শিক্ষার্থীদের ধারণা দিলেন। তার ক্লাসে অনুপস্থিত শিক্ষার্থীদের অভিভাবক মণ্ডলীকে অবগত করানোর জন্য তিনি ই-মেইল পাঠাতে IEEE 802.16 স্ট্যান্ডার্ড বিশিষ্ট একটি প্রযুক্তি ব্যবহার করলেন।', 'WAN কী?', 'ডেটা আদান-প্রদান একই সাথে সম্ভব—ব্যাখ্যা কর।', 'উদ্দীপকে উল্লিখিত ডেটা ট্রান্সমিশন মেথডটি বর্ণনা কর।', 'উদ্দীপকে উল্লিখিত স্ট্যান্ডার্ডের প্রযুক্তিটি ব্যবহারের যৌক্তিকতা বিশ্লেষণ কর।', NULL, NULL, NULL, 50, 'কমিউনিকেশন সিস্টেমস ও নেটওয়ার্কিং', 'CQ', '6', '2024', NULL, 2, NULL, NULL, NULL, NULL, '2025-12-21 06:47:49', '2025-12-21 06:47:49'),
(5, 'রাশেদ নির্বাচনি পরীক্ষায় বাংলা, ইংরেজি ও আইসিটি বিষয়ে যথাক্রমে (4E)<sub>16</sub>, (1011001)<sub>2</sub>, ও (85)<sub>10</sub> পেল।', 'ভিত্তি কী?', 'বিশ্বের সকল ভাষাকে কোডভুক্ত করা সম্ভব হয়েছে—ব্যাখ্যা কর।', 'রাশেদের বাংলা ও ইংরেজিতে প্রাপ্ত মোট নম্বর অক্টাল পদ্ধতিতে নির্ণয় কর।', 'উদ্দীপকের আলোকে রাশেদের ইংরেজি ও আইসিটি বিষয়ের প্রাপ্ত নম্বরের পার্থক্য যোগের মাধ্যমে নির্ণয় করা সম্ভব কিনা তা বিশ্লেষণপূর্বক মতামত দাও।', NULL, NULL, NULL, 50, 'সংখ্যা পদ্ধতি', 'CQ', '6', '2024', NULL, 2, NULL, NULL, NULL, NULL, '2025-12-21 07:40:35', '2025-12-21 07:40:35'),
(6, 'নামিরা শুধু HTML ব্যবহার করে First, Last, Next, Previous লিংকবিশিষ্ট একটি ওয়েবসাইট তৈরি করলো। ওয়েবসাইটটি ব্যবহারকারীর নিকট থেকে মতামত নিতে এবং আপডেট তথ্য প্রদর্শন করতে পারে না।', 'হাইপার লিংক কী?', 'ওয়েবসাইট পাবলিশিংয়ে হোস্টিং গুরুত্বপূর্ণ কেন? ব্যাখ্যা কর।', 'উদ্দীপকের আলোকে ওয়েবসাইটটির কাঠামো ব্যাখ্যা কর।', 'উদ্দীপকে উল্লিখিত সমস্যা সমাধানের জন্য প্রয়োজনীয় পদক্ষেপ বিশ্লেষণ কর', NULL, NULL, NULL, 50, 'ওয়েব ডিজাইন পরিচিতি এবং HTML', 'CQ', '6', '2024', NULL, 2, NULL, NULL, NULL, NULL, '2025-12-21 07:49:44', '2025-12-21 07:49:44'),
(7, 'পাপন কম্পিউটারে ত্রিভুজের ক্ষেত্রফল নির্ণয়ের প্রোগ্রাম চতুর্থ প্রজন্মের ভাষায় তৈরি করল। পাপনের বন্ধু আপন এর জন্য অন্য আরেকটি ভাষা ব্যবহার করে প্রোগ্রাম রচনা করল, যা খুব দ্রুত নির্বাহ হয় এবং মেমোরিতে খুবই সামান্য স্থান দখল করে। কিন্তু পাপনের কম্পিউটারে আপনের প্রোগ্রামটি কোনো ভাবেই নির্বাহ করা গেল না।', 'প্রোগ্রামের ভাষা কী?', '“C একটি মধ্যমস্তরের ভাষা”- ব্যাখ্যা কর।', 'পাপনের তৈরি করা প্রোগ্রামটির flow chart লেখ।', 'উদ্দীপকের প্রোগ্রামিং ভাষাদ্বয়ের মধ্যে কোনটির ব্যবহার সুবিধাজনক? বিশ্লেষণপূর্বক মতামত দাও।', NULL, NULL, NULL, 50, 'প্রোগ্রামিং ভাষা', 'CQ', '6', '2024', NULL, 2, NULL, NULL, NULL, NULL, '2025-12-21 07:57:23', '2025-12-21 07:57:23'),
(8, '# include&lt;stdio.h&gt;\nmain ()\n{\nint i, s = 0;\nfor ( i = 3 ; i<= 100 ; i + = 4 )\n{\ns=s+i;\n}\nprintf(\"sum = %d\", s ) ;\n}', 'কন্ট্রোল স্টেটমেন্ট কী?', 'i++ এবং ++i - ব্যাখ্যা কর।', 'উদ্দীপকের প্রোগ্রামটির জন্য ফ্লোচার্ট অঙ্কন কর।', 'উদ্দীপকের প্রোগ্রামটি do...... while loop ব্যবহার করেও সমাধান করা সম্ভব – কোডিংসহ ব্যাখ্যা কর।', NULL, NULL, NULL, 50, 'প্রোগ্রামিং ভাষা', 'CQ', '6', '2024', NULL, 2, NULL, NULL, NULL, NULL, '2025-12-21 08:05:23', '2025-12-21 08:05:23'),
(9, 'সুমন ও নীলিমা দুজনেই উচ্চশিক্ষিত। তারা পরস্পরকে খুব ভালোবাসে। তারা বিয়ে করে সংসারী হতে চায়। নীলিমার পরিবার যৌতুক দিতে পারেনি বলে সুমনের পরিবার তাকে মেনে নিতে চায় না। সুমন পরিবারের ইচ্ছায় অন্যত্র বিয়ে করে। কিন্তু নীলিমা এটিকে অপমানজনক মনে করে আর বিয়ে করে না। অতঃপর শিক্ষকতায় নিবেদিত হয়ে নীলিমা তার বাকি জীবন অতিবাহিত করে।', 'কল্যাণীর বাবার পেশা কী?', '“এই তো আমি জায়গা পাইয়াছি”—ব্যাখ্যা করো।', 'উদ্দীপকের সুমন আর ‘অপরিচিতা’ গল্পের অনুপম চরিত্রের সাদৃশ্য তুলে ধরো।', '“উদ্দীপকের নীলিমা ‘অপরিচিতা’ গল্পের কল্যাণীরই প্রতিচ্ছবি”—মন্তব্যটি বিশ্লেষণ করো।', NULL, NULL, NULL, 46, 'অপরিচিতা', 'CQ', '1', '2025', NULL, 2, NULL, NULL, NULL, NULL, '2025-12-22 06:32:38', '2025-12-22 06:32:38'),
(10, '“জাত গেল জাত গেল বলে\r\nএকি আজব কারখানা।\r\nসত্য কাজে কেউ নয় রাজি\r\nসবই দেখি তা না না না।”', '‘বিলাসী’ গল্পের বর্ণনাকারী কে?', '“অন্নপাপ। বাপরে! এর কি আর প্রায়শ্চিত্ত আছে।” — ব্যাখ্যা করো।', 'উদ্দীপকে ‘বিলাসী’ গল্পের কোন দিকটির সাদৃশ্য আছে? বুঝিয়ে লেখো।', '“উদ্দীপকে ‘বিলাসী’ গল্পের আংশিক ভাবের প্রতিফলন ঘটেছে”—মন্তব্যটি বিশ্লেষণ করো।', NULL, NULL, NULL, 46, 'বিলাসী', 'CQ', '1', '2025', NULL, 2, NULL, NULL, NULL, NULL, '2025-12-22 06:36:16', '2025-12-22 06:36:16'),
(11, '‘সিডর’-এ সব হারিয়ে কৃষক কেরামত আলী একেবারে নিঃস্ব হয়ে গেলো। কেরামত নিরুপায় হয়ে বন্ধু হাসমত মিয়ার বাড়ি গিয়ে উঠলো সাহায্যের জন্য। বন্ধু তাকে নিরাশ করেনি। সে তাকে এক জোড়া হালের গরু, লাঙল ও জোয়াল কিনতে টাকা দিলো এবং চাষাবাদ আরম্ভ করতে বললো। কেরামত তার বন্ধুর এ সহায়তা পেয়ে প্রচুর পরিশ্রমের ফলে নিজের অবস্থার পরিবর্তন করতে সক্ষম হলো।', 'একমুষ্ঠি ভিক্ষা দেওয়াকে আমরা কী মনে করো?', '“ওপরের হাত সব সময় নিচের হাত থেকে শ্রেষ্ঠ”—ব্যাখ্যা করো।', 'উদ্দীপকটি ‘মানব-কল্যাণ’ প্রবন্ধের কোন দিকটির সঙ্গে সাদৃশ্যপূর্ণ ব্যাখ্যা করো।', '“উদ্দীপকের মূলভাব এবং ‘মানব-কল্যাণ’ প্রবন্ধের মূলভাব একসূত্রে গাঁথা”—উক্তিটি বিশ্লেষণ করো।', NULL, NULL, NULL, 46, 'মানব-কল্যাণ', 'CQ', '1', '2025', NULL, 2, NULL, NULL, NULL, NULL, '2025-12-22 06:54:33', '2025-12-22 06:54:33'),
(12, 'দার্শনিক সক্রেটিস বলেছেন, “নিজেকে জানো”। নিজেকে জানা মানে আত্মনির্ভরশীল হওয়া। যে ব্যক্তি আত্মনির্ভরশীল, কোনো মিথ্যা ভয় তাকে পরাভূত করতে পারে না। কিন্তু আজকাল সমাজে প্রায়ই পরাবলম্বনের চিত্র চোখে পড়ে। যার ভিতরে ভয় কাজ করে এবং আত্মনির্ভরশীল নয়, সে মানসিক দাসত্বের শিকার। সে কখনো সমাজের কিংবা মানুষের কল্যাণ সাধন করতে পারে না।', '‘সম্মার্জনা’ শব্দের অর্থ কী?', '‘এই পরাবলম্বনই আমাদের নিষ্ক্রিয় করে ফেললে।’—ব্যাখ্যা করো।', 'উদ্দীপকের সাথে ‘আমার পথ’ প্রবন্ধের বক্তব্যগত কী সাদৃশ্য রয়েছে? বুঝিয়ে লেখো।', 'সমাজের উন্নয়নে উদ্দীপক এবং ‘আমার পথ’ প্রবন্ধের মূলবক্তব্য কতটুকু সার্থক বলে তুমি মনে করো?', NULL, NULL, NULL, 46, 'আমার পথ', 'CQ', '1', '2025', NULL, 2, NULL, NULL, NULL, NULL, '2025-12-22 06:55:33', '2025-12-22 06:55:33'),
(13, '“আমায় নহে গো ভালোবাসো শুধু ভালোবাসো মোর গান। বনের পাখিরে কে চিনে রাখে, গান হলে অবসান। চাঁদেরে কে চায়—জোছনা সবাই যাচে। গীত শেষে বীণা পড়ে থাকে ধূলি মাঝে।”', '‘সোনার তরী’ কবিতাটি কোন ছন্দে রচিত?', '‘চারিদিকে বাঁকা জল’—এর মাধ্যমে কবি কী বলতে চেয়েছেন?', '‘সোনার তরী’ কবিতার ধানের সাথে উদ্দীপকের গীতের তুলনা করো।', '“উদ্দীপকের শেষ চরণ দুটি যেন ‘সোনার তরী’ কবিতার জীবনদর্শনের প্রতিচ্ছবি”—‘সোনার তরী’ কবিতার আলোকে মূল্যায়ন করো।', NULL, NULL, NULL, 46, 'সোনার তরী', 'CQ', '1', '2025', NULL, 2, NULL, NULL, NULL, NULL, '2025-12-22 06:56:12', '2025-12-22 06:56:12'),
(14, 'সুকান্ত ভট্টাচার্য একজন তেজস্বী কবি। তিনি ব্রিটিশ ঔপনিবেশিক শাসকদের বিরুদ্ধে তাঁর লেখনী ধারণ করেন। তাঁর কবিতায় আছে মানুষের মুক্তির বার্তা এবং স্বকীয়তা ও স্বাধীনতার স্বাদ। তাঁর দ্রোহচেতনা কবিতার মাধ্যমে ছড়িয়ে পড়েছিল সমাজে।', '‘অগ্নিবীণা’ কাব্য কত সালে প্রকাশিত হয়?', 'কবি নিজেকে ‘বিদ্রোহী রণক্লান্ত’ বলেছেন কেন? ব্যাখ্যা করো।', 'উদ্দীপকে ‘বিদ্রোহী’ কবিতার কোন বক্তব্যটি ফুটে উঠেছে? বুঝিয়ে লেখো।', 'উদ্দীপক এবং ‘বিদ্রোহী’ কবিতার আলোকে বিদ্রোহীসত্তার স্বরূপ উন্মোচন করো।', NULL, NULL, NULL, 46, 'বিদ্রোহী', 'CQ', '1', '2025', NULL, 2, NULL, NULL, NULL, NULL, '2025-12-22 06:57:30', '2025-12-22 06:57:30'),
(15, 'মহানবি হযরত মুহাম্মদ (সা) তায়েফে ইসলাম প্রচার করতে গেলে তায়েফবাসী তাঁর ওপর পাথর নিক্ষেপ করে তাঁকে রক্তাক্ত করে। কিন্তু এত অত্যাচার সহ্য করেও নবিজি তাদের ওপর প্রতিশোধ নেননি। বরং বলেছেন—“এদের জ্ঞান দাও প্রভু, এদের ক্ষমা করো।”', 'কবির বুকে যে কবর বেঁধেছে তার বুক তিনি কী দিয়ে ভরেন?', '“আপন করিতে কাঁদিয়া বেড়াই যে মোরে করেছে পর”—ব্যাখ্যা করো।', 'উদ্দীপকের সাথে ‘প্রতিদান’ কবিতার কোন বক্তব্যটি সাদৃশ্যপূর্ণ? বুঝিয়ে লেখো।', '“একটি সুখী ও সুন্দর সমাজ গঠনে ‘প্রতিদান’ কবিতার ভাবার্থ ও উদ্দীপকের ভাবার্থের সমন্বয়সাধন অপরিহার্য”—আলোচনা করো।', NULL, NULL, NULL, 46, 'প্রতিদান', 'CQ', '1', '2025', NULL, 2, NULL, NULL, NULL, NULL, '2025-12-22 07:45:26', '2025-12-22 07:45:26'),
(16, 'নদীভাঙনে সর্বস্বান্ত নাজিরপুর এলাকার বাসিন্দা হাকিম। জীবন-জীবিকার জন্য সে চলে যায় শহরে। রিকসা চালিয়ে জীবিকা শুরু করলেও অবৈধ ব্যবসার সাথে যুক্ত হয়ে ক্রমেই প্রতিপত্তির মালিক হয়ে অভিজাতদের দলভুক্ত হয়।', '“নাফরমানি করিও না”—উক্তিটি কার?', '“বিশ্বাসের পাথরে যেন খোদাই সে-চোখ”—ব্যাখ্যা করো।', 'উদ্দীপকের সাথে ‘লালসালু’ উপন্যাসের কোন দিকটি সাদৃশ্যপূর্ণ? বুঝিয়ে লেখো।', '“উদ্দীপকের হাকিম এবং ‘লালসালু’ উপন্যাসের মজিদ উভয়ের মধ্যেই অস্তিত্ববাদী চেতনা প্রতিফলিত”—তোমার মতামত দাও।', NULL, NULL, NULL, 46, 'লালসালু', 'CQ', '1', '2025', NULL, 2, NULL, NULL, NULL, NULL, '2025-12-22 07:46:10', '2025-12-22 07:46:10'),
(17, 'আসামের চা বাগানে কুলি জোগাড় করা খুবই কঠিন। দলে দলে লোক কালাজ্বরে মরে। মজুর খাটতে সেখানে কোনো লোক যেতে চায় না। সেই সমস্যা নিরসনে এবং কমিশন পাওয়ার লোভে সুন্দরলাল সাঁওতাল পরগণার অশিক্ষিত, অসহায় ও কুসংস্কারাচ্ছন্ন মানুষদের শিং বোঙ্গার দৈববাণীর ভয় দেখিয়ে কুলির কাজ করতে বাধ্য করায়।', 'সাত ছেলের বাপের নাম কী?', '“দুনিয়াটা বড় বিচিত্র জায়গা।”—উক্তিটি কার? কোন প্রসঙ্গে বলেছে?', 'উদ্দীপকের দৈববাণীর সাথে ‘লালসালু’ উপন্যাসের কীসের সাদৃশ্য রয়েছে? বুঝিয়ে লেখো।', '“উদ্দীপকের সাঁওতাল পরগণার মানুষগুলো যেন মহব্বত নগরের গ্রামবাসীর প্রতিনিধি”—মন্তব্যটি বিশ্লেষণ করো।', NULL, NULL, NULL, 46, 'লালসালু', 'CQ', '1', '2025', NULL, 2, NULL, NULL, NULL, NULL, '2025-12-22 09:31:41', '2025-12-22 09:31:41'),
(18, 'মিলি ও লিলি দুই বোন। মিলি দীর্ঘদিন পরে বিদেশ থেকে দেশে ফিরল এবং জানল যে তাদের বাবার মৃত্যু হয়েছে। মৃত্যুর পূর্বে তাদের বাবা সম্পত্তির কিছু অংশ লিলিকে দান করে যান। এই নিয়ে মিলি ভিষণ গোলযোগ সৃষ্টি করে। সে মনে করে লিলি তাদের বাবাকে ভুলিয়ে সম্পত্তি আত্মসাৎ করেছে। সে প্রতিশোধ নিতে ভয়ংকর হয়ে ওঠে। লিলির একমাত্র ছেলেকে সে মেরে ফেলার ষড়যন্ত্র করে।', '‘সিরাজউদ্দৌলা’ নাটকের শেষ সংলাপটি কার?', '“ঘরের লোক অবিশ্বাসী হলে বাইরের লোকের পক্ষে সবই সম্ভব”—ব্যাখ্যা করো।', 'উদ্দীপকের কোন চরিত্রের সাথে ঘসেটি বেগমের চরিত্রের মিল রয়েছে? বুঝিয়ে লেখো।', '“উদ্দীপকের দ্বন্দ্ব পারিবারিক কিন্তু ‘সিরাজউদ্দৌলা’ নাটকের দ্বন্দ্ব রাজনৈতিক”—বিশ্লেষণ করো।', NULL, NULL, NULL, 46, 'সিরাজউদ্দৌলা', 'CQ', '1', '2025', NULL, 2, NULL, NULL, NULL, NULL, '2025-12-22 09:32:14', '2025-12-22 09:32:14'),
(19, 'দেশমাতৃকার টানে রমিজ কাউকে কিছু না বলে যুদ্ধে গিয়েছিলো। কিন্তু প্রতিপক্ষের লোকজন বিষয়টি বুঝতে পেরেছিলো। এ কারণে তারা রমিজের ভিটেবাড়ি পুড়িয়ে ছাই করে দিয়েছিলো এবং পরিবারের অন্যান্য সদস্যকে নদীর তীরে নিয়ে গুলি করে মেরে ফেলেছিলো।', 'ঘসেটি বেগম কাকে ‘সাপিনী’ বলেছে?', '“তুমিও আমার বিচার করতে বসলে”—কে এবং কেন বলেন?', 'উদ্দীপকের প্রতিপক্ষের লোকজন ‘সিরাজউদ্দৌলা’ নাটকের কোন চরিত্রসমূহের সাথে সাদৃশ্যপূর্ণ? বুঝিয়ে লেখো।', '“স্বার্থপরতা সর্বনাশের কারণ”—উদ্দীপক ও ‘সিরাজউদ্দৌলা’ নাটকের আলোকে মন্তব্যটির যথার্থতা বিচার করো।', NULL, NULL, NULL, 46, 'সিরাজউদ্দৌলা', 'CQ', '1', '2025', NULL, 2, NULL, NULL, NULL, NULL, '2025-12-22 09:32:50', '2025-12-22 09:32:50'),
(20, '“আমি যা বলিব তাই হইবে”—‘অপরিচিতা’ গল্পে উক্তিটি কার?', 'অনুপমের', 'শম্ভুবাবুর', 'কল্যাণীর', 'মামার', 'খ', 'শম্ভুবাবু ছিলেন কর্তৃত্বপরায়ণ ও আত্মঅহংকারী; তাই তিনি নিজের সিদ্ধান্তকেই চূড়ান্ত মনে করতেন।', NULL, 46, 'অপরিচিতা', 'MCQ', '1', '2025', NULL, 2, NULL, NULL, NULL, NULL, '2025-12-22 09:54:11', '2025-12-22 09:54:11'),
(21, '‘কৃতবিদ্য’ শব্দটির অর্থ কী?', 'বীণাপানি', 'বিদ্বান', 'সরস্বতী', 'প্রত্নতাত্ত্বিক', 'খ', 'কৃতবিদ্য শব্দের অর্থ জ্ঞানী বা বিদ্বান ব্যক্তি।', NULL, 46, 'শব্দার্থ', 'MCQ', '1', '2025', NULL, 2, NULL, NULL, NULL, NULL, '2025-12-22 10:01:21', '2025-12-22 10:01:21'),
(22, 'মজিদ জমিলাকে মাজারে বেঁধে রাখে কেন?', 'হত্যার উদ্দেশ্যে', 'কঠিন শাস্তি দিতে', 'দুষ্ট আত্মার প্রভাব ছাড়াতে', 'মোদাচ্ছের পিরের নির্দেশে', 'গ', 'মজিদ ধর্মীয় কুসংস্কার ব্যবহার করে জমিলার ওপর দুষ্ট আত্মার প্রভাব আছে বলে দেখায়।', NULL, 46, 'লালসালু', 'MCQ', '1', '2025', NULL, 2, NULL, NULL, NULL, NULL, '2025-12-22 10:02:14', '2025-12-22 10:02:14'),
(23, '“তুমি অধম তাই বলিয়া আমি উত্তম না হইব কেন?”—কে বলেছেন?', 'বিদ্যাপতি', 'চণ্ডীদাস', 'বঙ্কিমচন্দ্র চট্টোপাধ্যায়', 'রবীন্দ্রনাথ ঠাকুর', 'খ', 'মানবপ্রেম ও আত্মমর্যাদার ভাব প্রকাশ করতে চণ্ডীদাস এই উক্তিটি বলেছেন।', NULL, 46, 'কবিতা ও উক্তি', 'MCQ', '1', '2025', NULL, 2, NULL, NULL, NULL, NULL, '2025-12-22 10:03:15', '2025-12-22 10:03:15'),
(24, '‘বাবুরা, আমাকে একটিবার ছেড়ে দাও’—বিলাসী কেন একথা বলেছিল?', 'প্রহারের ভয়ে', 'মৃত্যুঞ্জয়কে দেখতে', 'রুটিগুলো ঘরে দেয়ার জন্য', 'ন্যাড়াকে দেখতে চেয়ে', 'গ', 'বিলাসী তার পরিবারের কাছে রুটি পৌঁছে দিতে চেয়েছিল বলেই অনুরোধ করেছিল।', NULL, 46, 'বিলাসী', 'MCQ', '1', '2025', NULL, 2, NULL, NULL, NULL, NULL, '2025-12-22 10:04:08', '2025-12-22 10:04:08'),
(25, '‘মানব-কল্যাণ’ প্রবন্ধে আবুল ফজল কী চেয়েছেন?', 'মানবতন্ত্র', 'মুক্তবুদ্ধির বিকাশ', 'ক্ষমতার পালাবদল', 'ধর্ম প্রচার', 'খ', 'আবুল ফজল মানুষের মুক্তচিন্তা ও যুক্তিবাদী মানসিকতার বিকাশ কামনা করেছেন।', NULL, 46, 'মানব-কল্যাণ', 'MCQ', '1', '2025', NULL, 2, NULL, NULL, NULL, NULL, '2025-12-22 10:04:40', '2025-12-22 10:04:40'),
(26, 'ফাঁসির মঞ্চে জীবনের জয়গান গেয়েছেন ক্ষুদিরাম। তিনি দেশের জন্য আত্মবিসর্জন দিয়েছেন তবু বিদেশিদের কাছে মাথা নত করেননি। ‘আমার পথ’ প্রবন্ধ অনুসারে ক্ষুদিরামের মধ্যে কোন বৈশিষ্ট্যটি বিদ্যমান?', 'মহাপুরুষে ভক্তি', 'স্বনির্ভরতা', 'সত্যে বলীয়ান', 'সম্প্রীতি', 'খ', 'ক্ষুদিরাম আত্মনির্ভরশীল ও সাহসী ছিলেন; অন্যায়ের কাছে মাথা নত করেননি।', NULL, 46, 'আমার পথ', 'MCQ', '1', '2025', NULL, 2, NULL, NULL, NULL, NULL, '2025-12-22 10:05:32', '2025-12-22 10:05:32'),
(27, 'ফাঁসির মঞ্চে জীবনের জয়গান গেয়েছেন ক্ষুদিরাম। তিনি দেশের জন্য আত্মবিসর্জন দিয়েছেন তবু বিদেশিদের কাছে মাথা নত করেননি। ‘আমার পথ’ প্রবন্ধ অনুসারে ক্ষুদিরামের উক্ত বৈশিষ্ট্যে বিদ্যমান ভাব প্রকাশ পেয়েছে যে অংশ—\r\ni. অভিশাপ রথের সারথি\r\nii. আমার পথ দেখাবে আমার সত্য\r\niii. সত্যের দম্ভে ক্ষতির শিকার। নিচের কোনটি সঠিক?', 'i ও ii', 'i ও iii', 'ii ও iii', 'i, ii ও iii', 'ঘ', 'সবগুলো অংশে সাহসী ও স্বনির্ভরতার ভাব ফুটে উঠেছে।', NULL, 46, 'আমার পথ', 'MCQ', '1', '2025', NULL, 2, NULL, NULL, NULL, NULL, '2025-12-22 10:09:27', '2025-12-22 10:09:27'),
(28, '‘আমি আপনাদেরই সমগোত্রীয়’—উমিচাঁদ নিজেকে ইংরেজদের সমগোত্রীয় মনে করেন কেন?', 'ধর্মীয় চেতনা', 'উভয়ে বিলেত থেকে আগত', 'যে কোনোভাবে অর্থ উপার্জন', 'নবাবের হাত থেকে আত্মরক্ষা', 'খ', 'উমিচাঁদ নিজেকে ইংরেজদের সমগোত্রীয় মনে করেন কারণ উভয়ই বিলেত থেকে এসেছে এবং একইভাবে বিদেশি প্রভাব গ্রহণ করেছে।', NULL, 46, 'উমিচাঁদ', 'MCQ', '1', '2025', NULL, 2, NULL, NULL, NULL, NULL, '2025-12-22 10:10:47', '2025-12-22 10:10:47'),
(29, '‘মাসি-পিসি’ গল্পে-“রসুই চালায় ঝাঁপ এঁটে মাসি-পিসি বাইরে যায়।” এখানে ‘রসুই চালায়’ কী অর্থে ব্যবহৃত হয়েছে?', 'ড্রয়িং রুম', 'রান্নার ঘর', 'শোয়ার ঘর', 'জিনিসপত্রের ঘর', 'খ', '‘রসুই চালায়’ মানে রান্নাঘর বা রান্নার স্থান।', NULL, 46, 'মাসি-পিসি', 'MCQ', '1', '2025', NULL, 2, NULL, NULL, NULL, NULL, '2025-12-22 10:11:44', '2025-12-22 10:11:44'),
(30, '‘যে মুখে সে কহে নিঠুরিয়া বাণী’—পরের চরণ কোনটি?', 'আমি করে লয়ে তারি মুখখানি', 'আমি লয়ে করে তারি মুখখানি', 'সাজাই তাহার গৃহখানি আনি', 'কত ঠাঁই হতে কত কীযে আনি', 'খ', 'সঠিক ছন্দ এবং প্রায়শই ব্যবহৃত সংস্করণ হলো ‘আমি লয়ে করে তারি মুখখানি’।', NULL, 46, 'কবিতা ও ছন্দ', 'MCQ', '1', '2025', NULL, 2, NULL, NULL, NULL, NULL, '2025-12-22 10:12:26', '2025-12-22 10:12:26'),
(31, '‘বেজেছে কি আগমনী গান?’ উক্তিটিতে কোন বিষয়টি ফুটে উঠেছে?', 'চিত্তচাঞ্চল্য', 'আগ্রহ', 'উদাসীনতা', 'অধ্যবসায়', 'খ', 'উক্তিটি উৎসাহ ও আগ্রহের ভাব প্রকাশ করে।', NULL, 46, 'কবিতা ও ছন্দ', 'MCQ', '1', '2025', NULL, 2, NULL, NULL, NULL, NULL, '2025-12-22 10:12:55', '2025-12-22 10:12:55'),
(32, '‘লালসালু’ উপন্যাসের রহিমা চরিত্রটি\r\ni. ধীরস্থির ও মমতাময়ী\r\nii. একরোখা ও কর্মপরায়ণা\r\niii. শান্তশিষ্ট ও ভীতু।\r\nনিচের কোনটি সঠিক?', 'i ও ii', 'ii ও iii', 'i ও iii', 'i, ii ও iii', 'ক', 'রহিমা চরিত্র ধীরস্থির ও মমতাময়ী এবং কর্মপরায়ণা দুই দিকই প্রকাশ পেয়েছে।', NULL, 46, 'লালসালু', 'MCQ', '1', '2025', NULL, 2, NULL, NULL, NULL, NULL, '2025-12-22 10:13:51', '2025-12-22 10:13:51'),
(33, '\'সিরাজউদ্দৌলা\' নাটকের প্রথম সংলাপটি কার?', 'ওয়ালি খান', 'ক্লেটন', 'উমিচাঁদ', 'হলওয়েল', 'খ', 'প্রথম সংলাপটি ক্লেটনের চরিত্রের মাধ্যমে নাটকে শুরু হয়।', NULL, 46, 'সিরাজউদ্দৌলা', 'MCQ', '1', '2025', NULL, 2, NULL, NULL, NULL, NULL, '2025-12-22 10:58:51', '2025-12-22 10:58:51'),
(34, '\'হে কবি, নীরব কেন ফাগুন যে এসেছে ধরায়\'—এ চরণটিতে \'নীরব কেন\' বলতে কবির কোন অবস্থা প্রকাশ পেয়েছে?', 'শীতে আড়ষ্ট হয়ে থাকা', 'নিদ্রামগ্ন অবস্থা', 'দৈনন্দিন জীবনে নিষ্ক্রিয়তা', 'উদাসীনতা', 'ঘ', 'কবি এখানে উদাসীন ও মনোযোগহীন অবস্থার ভাব প্রকাশ করেছেন।', NULL, 46, 'কবিতা', 'MCQ', '1', '2025', NULL, 2, NULL, NULL, NULL, NULL, '2025-12-22 10:59:25', '2025-12-22 10:59:25'),
(35, 'মাসি-পিসি আহ্লাদিকে প্রতিকূল পরিবেশ থেকে বাঁচাতে সক্ষম হয়েছিল যে শক্তির দ্বারা, তা হচ্ছে—\r\ni. ঐক্য\r\nii. পেশি শক্তি\r\niii. সাহস।\r\nনিচের কোনটি সঠিক?', 'i ও ii', 'ii ও iii', 'i ও iii', 'i, ii ও iii', 'ঘ', 'আহ্লাদি রক্ষা করতে ঐক্য, পেশি শক্তি ও সাহস সবগুলোই গুরুত্বপূর্ণ ছিল।', NULL, 46, 'মাসি-পিসি', 'MCQ', '1', '2025', NULL, 2, NULL, NULL, NULL, NULL, '2025-12-22 11:00:12', '2025-12-22 11:00:12'),
(36, 'মধ্যাহ্নের প্রখর সূর্য/উত্তপ্ত রাজপথ মাড়িয়ে ছাত্র-জনতার মিছিল দুর্বার গতিতে শাহবাগের দিকে যাচ্ছিল। বাসার বারান্দা থেকে তা দেখে ইতিহাসের স্মৃতি রোমন্থন করতে গিয়ে মা তার কিশোর সন্তানকে শোনাচ্ছেন—— একুশের গল্প, গণ-অভ্যুত্থানের গল্প, মুক্তিযুদ্ধের গল্প। রাজপথের ওপর ছোপ ছোপ রক্ত দেখে তার চোখ ছলছল করে।\r\nউদ্দীপকের যে দিকটি ফেব্রুয়ারি ১৯৬৯\' কবিতায় অনুপস্থিত', 'হত্যাযজ্ঞ', 'ইতিহাস চেতনা', 'মায়ের গল্প', 'দুর্বার মিছিল', 'ক', 'উদ্দীপকে হত্যাযজ্ঞের চিত্র নেই; অন্যান্য বিষয় ফুটে উঠেছে।', NULL, 46, 'একুশের গল্প', 'MCQ', '1', '2025', NULL, 2, NULL, NULL, NULL, NULL, '2025-12-22 11:02:25', '2025-12-22 11:02:25'),
(37, 'মধ্যাহ্নের প্রখর সূর্য/উত্তপ্ত রাজপথ মাড়িয়ে ছাত্র-জনতার মিছিল দুর্বার গতিতে শাহবাগের দিকে যাচ্ছিল। বাসার বারান্দা থেকে তা দেখে ইতিহাসের স্মৃতি রোমন্থন করতে গিয়ে মা তার কিশোর সন্তানকে শোনাচ্ছেন—— একুশের গল্প, গণ-অভ্যুত্থানের গল্প, মুক্তিযুদ্ধের গল্প। রাজপথের ওপর ছোপ ছোপ রক্ত দেখে তার চোখ ছলছল করে।\r\nউদ্দীপকের মায়ের শোনানো গল্পে \'ফেব্রুয়ারি ১৯৬৯\' কবিতার যে দিকটি প্রকাশ পেয়েছে—\r\ni. ভাষা আন্দোলন\r\nii. গণ-আন্দোলন\r\niii. স্বাধীনতার যুদ্ধ।\r\nনিচের কোনটি সঠিক?', 'i ও ii', 'ii ও iii', 'i ও iii', 'i, ii ও iii', 'ক', 'মায়ের গল্পে ভাষা আন্দোলন ও গণ-আন্দোলনের দিকটি ফুটে উঠেছে।', NULL, 46, 'একুশের গল্প', 'MCQ', '1', '2025', NULL, 2, NULL, NULL, NULL, NULL, '2025-12-22 11:03:32', '2025-12-22 11:03:32'),
(38, 'কাজী নজরুল ইসলামের ____ সালে প্রকাশিত হয়?', '১৯২০', '১৯২২', '১৯২৪', '১৯২৬', 'গ', '\'অগ্নিবীণা\' কাব্যগ্রন্থটি ১৯২৪ সালে প্রকাশিত হয়।', NULL, 46, 'নজরুল ইসলাম', 'MCQ', '1', '2025', NULL, 2, NULL, NULL, NULL, NULL, '2025-12-22 11:04:22', '2025-12-22 11:04:22'),
(39, '\'চারিদিকে বাঁকাজল করিছে খেলা\'—এখানে \'বাঁকাজল\' বলতে কবি কী বোঝাতে চেয়েছেন?', 'স্রোতের বক্রতা', 'জলের কল্লোল', 'পরিস্থিতির ভয়াবহতা', 'অনন্ত কালস্রোত', 'ক', '\'বাঁকাজল\' দ্বারা নদীর বক্র স্রোত বোঝানো হয়েছে।', NULL, 46, 'কবিতা', 'MCQ', '1', '2025', NULL, 2, NULL, NULL, NULL, NULL, '2025-12-22 11:04:52', '2025-12-22 11:04:52'),
(40, 'আখতারুজ্জামান ইলিয়াস তাঁর \'রেইনকোট\' গল্পে কোন বিষয়টি ফুটিয়ে তুলতে দক্ষতার পরিচয় দিয়েছেন?', 'পাকবাহিনীর নির্যাতন', 'গেরিলা তৎপরতা', 'নুরুল হুদার দেশপ্রেম', 'রাজাকারদের অত্যাচার', 'ঘ', 'গল্পে রাজাকারদের অত্যাচার ও অব্যবস্থার চিত্র ফুটে উঠেছে।', NULL, 46, 'রেইনকোট', 'MCQ', '1', '2025', NULL, 2, NULL, NULL, NULL, NULL, '2025-12-22 11:05:28', '2025-12-22 11:05:28'),
(41, 'মিরন সিরাজউদ্দৌলার বিরুদ্ধে শুনিয়েছিল—\r\ni. দরবারের পদস্থ আমির-উমরাদের মর্যাদাহানি\r\nii. মতিঝিলের জলসা চিরদিনের মতো ভেঙে দেওয়া\r\niii. বাংলার জনসাধারণকে উৎপীড়ন।\r\nনিচের কোনটি সঠিক?', 'i ও i', 'ii ও iii', 'i ও iii', 'i, ii ও iii', 'ঘ', 'সবগুলো অভিযোগই মিরন সিরাজউদ্দৌলার বিরুদ্ধে ছিল।', NULL, 46, 'সিরাজউদ্দৌলা', 'MCQ', '1', '2025', NULL, 2, NULL, NULL, NULL, NULL, '2025-12-22 11:07:37', '2025-12-22 11:07:37'),
(42, '\'ইস্পাতের তরবারি\' কাকে সশস্ত্র করবে?', 'যে কর্ষণ করে', 'যে ভালোবেসে যুদ্ধে যায়', 'যে মৎস্য লালন করে', 'যে লৌহখণ্ডকে প্রজ্বলিত করে', 'খ', '‘ইস্পাতের তরবারি’ সংগ্রাম ও ভালোবাসা থেকে যুদ্ধে যাওয়া ব্যক্তিকে নির্দেশ করে।', NULL, 46, 'ইস্পাতের তরবারি', 'MCQ', '1', '2025', NULL, 2, NULL, NULL, NULL, NULL, '2025-12-22 11:08:26', '2025-12-22 11:08:26'),
(43, '\'মহা প্রলয়ের\' সাথে সম্পর্ক আছে—ইস্রাফিলের সিঙ্গার i. নৃশংসতার ii. নটরাজের iii. নৃশংসতার। নিচের কোনটি সঠিক?', 'i ও ii', 'ii ও iii', 'i ও iii', 'i, ii ও iii', 'ঘ', 'সবগুলো দিকেই মহা প্রলয় ও নৃশংসতার চিত্র ফুটে আছে।', NULL, 46, 'মহা প্রলয়', 'MCQ', '1', '2025', NULL, 2, NULL, NULL, NULL, NULL, '2025-12-22 11:09:04', '2025-12-22 11:09:04'),
(44, '\'এখন যৌবন যার মিছিলে যাবার তার শ্রেষ্ঠ সময়...\' আঠারো বছর বয়সের কোন বৈশিষ্ট্য উদ্দীপকে ফুটে উঠেছে?', 'ত্যাগের মহিমা', 'আত্মপ্রত্যয়', 'সহনশীলতা', 'মহত্ত্ব', 'খ', 'উদ্দীপকে আঠারো বছর বয়স আত্মপ্রত্যয় এবং সাহসিকতার প্রতীক হিসেবে ফুটে উঠেছে।', NULL, 46, 'আঠারো বছর বয়স', 'MCQ', '1', '2025', NULL, 2, NULL, NULL, NULL, NULL, '2025-12-22 11:09:31', '2025-12-22 11:09:31'),
(45, '\'এ বয়স জানে রক্তদানের পুণ্য\'—উক্তি কোন বৈশিষ্ট্য প্রকাশ করে?', 'এ বয়স জানে রক্তদানের পুণ্য-', 'আঠারো বছর বয়স ভয়ংকর', 'এ বয়সে কানে আসে কত মন্ত্রণা', 'এ বয়সে তাই নেই কোনো সংশয়', 'ক', '\'রক্তদানের পুণ্য\' বলছে যে আঠারো বছর ত্যাগ ও আত্মত্যাগের জন্য প্রস্তুত।', NULL, 46, 'আঠারো বছর বয়স', 'MCQ', '1', '2025', NULL, 2, NULL, NULL, NULL, NULL, '2025-12-22 11:09:56', '2025-12-22 11:09:56'),
(46, '\'আমার পথ\' প্রবন্ধ অবলম্বনে মনুষ্যত্ববোধ জাগ্রত হলে—\r\ni. সমাজকে ঐক্যবদ্ধ করা যাবে\r\nii. ধর্মে-ধর্মে বিরোধ মিটে যাবে\r\niii. ধর্মের সত্য উন্মোচিত হবে।\r\nনিচের কোনটি সঠিক?', 'i ও ii', 'ii ও iii', 'i ও iii', 'i, ii ও iii', 'ঘ', 'সবগুলোই প্রবন্ধে মনুষ্যত্ববোধের মাধ্যমে অর্জিত লক্ষ্য।', NULL, 46, 'আমার পথ', 'MCQ', '1', '2025', NULL, 2, NULL, NULL, NULL, NULL, '2025-12-22 11:10:35', '2025-12-22 11:10:35'),
(47, '\'লালসালু\' উপন্যাসের ইংরেজি অনুবাদক এর নাম কী?', 'মুহাম্মদ আতাউল্লাহ', 'কলিমুল্লাহ', 'সৈয়দ ওয়ালীউল্লাহ্', 'অ্যান-মারি-থিবো', 'ঘ', 'অ্যান-মারি-থিবো হলো উপন্যাসের ইংরেজি অনুবাদক।', NULL, 46, 'লালসালু', 'MCQ', '1', '2025', NULL, 2, NULL, NULL, NULL, NULL, '2025-12-22 11:11:39', '2025-12-22 11:11:39'),
(48, 'উদ্দীপকটি \'আমি কিংবদন্তির কথা বলছি\' কবিতার কোন বিষয়টি নির্দেশ করে?', 'বাঙালির ঐতিহ্য', 'কাব্যপ্রীতি', 'কবিতার অমোঘ শক্তির কথা', 'বিচলিত স্নেহের কথা', 'ক', 'কবিতায় বাঙালির ঐতিহ্য, গর্ব ও সংগ্রামের চিত্র ফুটে উঠেছে।', NULL, 46, 'আমি কিংবদন্তির কথা বলছি', 'Writing', '1', '2025', NULL, 2, NULL, NULL, NULL, NULL, '2025-12-22 11:12:21', '2025-12-22 11:12:21'),
(49, 'উদ্দীপকের \'শস্য করেছি চাষ\' বাক্যাংশের সাথে সাদৃশ্যযুক্ত পঙ্ক্তিটি হলো—', 'তাঁর করতলে পলিমাটির সৌরভ ছিল', 'একটি উজ্জ্বল জানালার কথা বলছি', 'প্রবহমান নদী তাকে পুরস্কৃত করবে', 'সে আজন্ম ক্রীতদাস থেকে যাবে', 'ক', '\'শস্য করেছি চাষ\' বাক্যাংশের সাথে মিল রয়েছে \'তাঁর করতলে পলিমাটির সৌরভ ছিল\'।', NULL, 46, 'আমি কিংবদন্তির কথা বলছি', 'Writing', '1', '2025', NULL, 2, NULL, NULL, NULL, NULL, '2025-12-22 11:12:57', '2025-12-22 11:12:57'),
(50, 'উদাহরণসহ ‘অ’ ধ্বনির উচ্চারণের পাঁচটি নিয়ম লেখ।', NULL, NULL, NULL, NULL, NULL, '১) শব্দের শুরুতে অ ধ্বনি স্পষ্ট হয়—যেমন: অমল।\r\n২) শব্দের মাঝে অ ধ্বনি হ্রস্ব হয়—যেমন: কলম।\r\n৩) শব্দের শেষে অ ধ্বনি অনেক সময় উচ্চারিত হয় না—যেমন: জল।\r\n৪) যুক্তাক্ষরে অ ধ্বনি সংক্ষিপ্ত হয়—যেমন: বন্ধ।\r\n৫) অনুস্বরের আগে অ ধ্বনি নাসিক্য হয়—যেমন: অঙ্ক।', NULL, 47, 'ধ্বনি', 'Writing', '1', '2025', NULL, 2, NULL, NULL, NULL, NULL, '2025-12-23 00:38:49', '2025-12-23 00:38:49'),
(51, 'যে কোনো পাঁচটি শব্দের শুদ্ধ উচ্চারণ লেখ: অধ্যক্ষ, ইতোমধ্যে, উদ্যোগ, ঐকতান, কল্যাণ, চর্যাপদ, ধন্যবাদ, পদ্ম।', NULL, NULL, NULL, NULL, NULL, 'অধ্যক্ষ (অধ্খখো), ইতোমধ্যে (ইতো-মধ্যে), উদ্যোগ (উদ্দোগ), ঐকতান (ঐক্কোতান), কল্যাণ (কল্লান)।', NULL, 47, 'উচ্চারণ', 'Writing', '1', '2025', NULL, 2, NULL, NULL, NULL, NULL, '2025-12-23 04:02:37', '2025-12-23 04:02:37'),
(52, 'উদাহরণসহ বাংলা একাডেমি প্রণীত প্রমিত বাংলা বানানের পাঁচটি নিয়ম লেখ।', NULL, NULL, NULL, NULL, NULL, '১) তৎসম শব্দে মূল বানান বজায় থাকে—যেমন: কর্ম। ২) বিদেশি শব্দ প্রমিত উচ্চারণে লেখা হয়—যেমন: অফিস। ৩) যুক্তাক্ষর সরল করা হয়—যেমন: দুঃখ। ৪) অনুস্বরের সঠিক ব্যবহার—যেমন: বাংলা। ৫) দ্বিত্ব ব্যঞ্জনের নিয়ম মানা হয়—যেমন: সম্মান।', NULL, 47, 'বানাননীতি', 'Writing', '1', '2025', NULL, 2, NULL, NULL, NULL, NULL, '2025-12-23 04:03:21', '2025-12-23 04:03:21'),
(53, 'যে কোনো পাঁচটি শব্দের শুদ্ধ বানান লেখ:\r\nঅধ্যায়ন, অপরাহ্ন, আমাবস্যা, গ্রামীন, দন্ডবিদি, প্রতিযোগীতা, বিভিসিকা, রেজিষ্ট্রেশন।', NULL, NULL, NULL, NULL, NULL, 'অধ্যয়ন, অপরাহ্ণ, অমাবস্যা, গ্রামীণ, দণ্ডবিধি।', NULL, 47, 'শুদ্ধ বানান', 'Writing', '1', '2025', NULL, 2, NULL, NULL, NULL, NULL, '2025-12-23 04:06:24', '2025-12-23 04:06:24'),
(54, 'উদাহরণসহ ক্রিয়া পদের শ্রেণিবিভাগ আলোচনা কর।', NULL, NULL, NULL, NULL, NULL, 'ক্রিয়া প্রধানত দুই প্রকার—সকর্মক ও অকর্মক। যেমন: সে বই পড়ে (সকর্মক), সে হাসে (অকর্মক)। এছাড়া মৌলিক ও যৌগিক ক্রিয়াও রয়েছে।', NULL, 47, 'ক্রিয়া', 'Writing', '1', '2025', NULL, 2, NULL, NULL, NULL, NULL, '2025-12-23 04:10:49', '2025-12-23 04:10:49'),
(55, 'নিম্নরেখ যে-কোনো পাঁচটি শব্দের ব্যাকরণিক শব্দশ্রেণি নির্দেশ কর।\nনির্দেশ কর:\n(i) পয়লা বৈশাখ বাঙালির উৎসবের দিন।\n(ii) আজ নয় কাল সে আসবেই।\n(iii) হে বন্ধু, বিদায়।\n(iv) তুমি যে আমার কবিতা।\n(v) মোদের গরব মোদের আশা আমরি বাংলা ভাষা।\n(vi) দুঃখ বিনা সুখ লাভ হয় কি মহীতে?\n(vii) আমিন ও সামিন দুই ভাই।\n(viii) এগিয়ে চলেছে প্রতিবাদী মিছিল।', NULL, NULL, NULL, NULL, NULL, 'পয়লা (বিশেষণ), আজ (ক্রিয়া বিশেষণ), বন্ধু (বিশেষ্য), যে (অব্যয়), বাংলা (বিশেষ্য)।', NULL, 47, 'পদ', 'Writing', '1', '2025', NULL, 2, NULL, NULL, NULL, NULL, '2025-12-23 04:17:06', '2025-12-23 04:17:06'),
(56, 'বাংলা ভাষায় উপসর্গের প্রয়োজনীয়তা ব্যাখ্যা কর।', NULL, NULL, NULL, NULL, NULL, 'উপসর্গ শব্দের আগে বসে অর্থ পরিবর্তন ও সম্প্রসারণ করে। যেমন: গমন → আগমন। এতে ভাষা সমৃদ্ধ হয়।', NULL, 47, 'উপসর্গ', 'Writing', '1', '2025', NULL, 2, NULL, NULL, NULL, NULL, '2025-12-23 04:21:07', '2025-12-23 04:21:07'),
(57, 'যে কোনো পাঁচটি শব্দের ব্যাসবাক্যসহ সমাস নির্ণয় কর।\nকাগজ-কলম, তেপান্তর, নবীনবরণ, মুখচন্দ্র, গণশিক্ষা, বনবাস, সিংহাসন, কাজলকালো।', NULL, NULL, NULL, NULL, NULL, 'মুখচন্দ্র = মুখের ন্যায় চন্দ্র (উপমা সমাস),\r\nবনবাস = বনে বাস (কর্মধারয়),\r\nসিংহাসন = সিংহের আসন (ষষ্ঠী তৎপুরুষ)।', NULL, 47, 'সমাস', 'Writing', '1', '2025', NULL, 2, NULL, NULL, NULL, NULL, '2025-12-23 04:22:34', '2025-12-23 04:22:34'),
(58, 'বাক্যের গঠনগত শ্রেণিবিভাগ আলোচনা কর।', NULL, NULL, NULL, NULL, NULL, 'বাক্য তিন প্রকার—সরল, যৌগিক ও জটিল। যেমন: আমি পড়ি (সরল), আমি পড়ি এবং লেখি (যৌগিক)।', NULL, 47, 'বাক্য', 'Writing', '1', '2025', NULL, 2, NULL, NULL, NULL, NULL, '2025-12-23 04:39:44', '2025-12-23 04:39:44'),
(59, 'নির্দেশ অনুসারে যে-কোনো পাঁচটি বাক্যান্তর কর:\r\n(i) জাদুঘর আমাদের আনন্দ দেয়। (প্রশ্নবোধক)\r\n(ii) সর্বদা তার মনে দুঃখ। (বিস্ময়বোধক)\r\n(iii) তিনি ধনী কিন্তু দাতা নন। (সরল)\r\n(iv) সূর্যোদয়ে পদ্ম ফুটে। (জটিল)\r\n(v) তাদের ভুলটা ভাঙতে দেরি হয় না। (অস্তিবাচক)\r\n(vi) কী ভয়ংকর ঘটনা। (নির্দেশাত্মক)\r\n(vii) মানুষ মরণশীল। (নেতিবাচক)\r\n(viii) জ্ঞানী বলেই তিনি বিনয়ী ছিলেন। (যৌগিক)', NULL, NULL, NULL, NULL, NULL, 'জাদুঘর কি আমাদের আনন্দ দেয়?\r\nকী ভয়ংকর ঘটনা!\r\nমানুষ অমর নয়।\r\nসূর্য উঠলে পদ্ম ফুটে।\r\nতিনি বিনয়ী ছিলেন বলে জ্ঞানী।', NULL, 47, 'বাক্যান্তর', 'Writing', '1', '2025', NULL, 2, NULL, NULL, NULL, NULL, '2025-12-23 04:43:54', '2025-12-23 04:43:54'),
(60, 'নিচের অনুচ্ছেদের অপপ্রয়োগ সংশোধন কর।\r\nনিরব ভাষায় বৃক্ষ আমাদের সার্থকতার গান শোনায়। অনুভুতির কান দ্বারা সে গান শুনিতে হবে। তাহলে বুঝতে পারা যাবে জিবনের মানে বৃদ্ধি, ধম্মের মানে ও তাই।', NULL, NULL, NULL, NULL, NULL, 'নীরব ভাষায় বৃক্ষ আমাদের সার্থকতার গান শোনায়। অনুভূতির কান দিয়ে সে গান শুনতে হবে। তাহলে জীবনের মানে ও ধর্মের মানে বোঝা যাবে।', NULL, 47, 'ভাষা শুদ্ধতা', 'Writing', '1', '2025', NULL, 2, NULL, NULL, NULL, NULL, '2025-12-23 04:45:57', '2025-12-23 04:45:57'),
(61, 'যে কোনো পাঁচটি বাক্য শুদ্ধ করে লেখ:\r\n(i) আমিনা বুদ্ধিমান মেয়ে।\r\n(ii) সব ছাত্ররা উপস্থিত আছে।\r\n(iii) তিনি স্বপরিবারে অনুষ্ঠানে উপস্থিত।\r\n(iv) সে এ মোকদ্দমায় সাক্ষী দিয়েছে।\r\n(v) অশ্রুজলে বুক ভেসে গেল।\r\n(vi) পরবর্তীতে আপনি আবার আসবেন।\r\n(vii) মাদকাশক্তি ভালো নয়।\r\n(viii) সাবধান পূর্বক চলবে।', NULL, NULL, NULL, NULL, NULL, '<table border=\"1\" cellpadding=\"10\" cellspacing=\"0\" width=\"100%\">\n  <thead>\n    <tr>\n      <th>ক্রম</th>\n      <th>অশুদ্ধ বাক্য</th>\n      <th>শুদ্ধ বাক্য</th>\n    </tr>\n  </thead>\n  <tbody>\n    <tr>\n      <td>(i)</td>\n      <td>আমিনা বুদ্ধিমান মেয়ে।</td>\n      <td>আমিনা <b>বুদ্ধিমতী</b> মেয়ে।</td>\n    </tr>\n    <tr>\n      <td>(ii)</td>\n      <td>সব ছাত্ররা উপস্থিত আছে।</td>\n      <td><b>সব ছাত্র</b> উপস্থিত আছে।</td>\n    </tr>\n    <tr>\n      <td>(iii)</td>\n      <td>তিনি স্বপরিবারে অনুষ্ঠানে উপস্থিত।</td>\n      <td>তিনি <b>সপরিবারে</b> অনুষ্ঠানে উপস্থিত।</td>\n    </tr>\n    <tr>\n      <td>(v)</td>\n      <td>অশ্রুজলে বুক ভেসে গেল।</td>\n      <td><b>অশ্রুতে</b> বুক ভেসে গেল।</td>\n    </tr>\n    <tr>\n      <td>(vii)</td>\n      <td>মাদকাশক্তি ভালো নয়।</td>\n      <td><b>মাদকাসক্তি</b> ভালো নয়।</td>\n    </tr>\n  </tbody>\n</table>', NULL, 47, 'বাক্য সংশোধন', 'Writing', '1', '2025', NULL, 2, NULL, NULL, NULL, NULL, '2025-12-23 04:48:10', '2025-12-23 04:48:10'),
(62, 'যে কোনো দশটি শব্দের পারিভাষিক রূপ লেখ:\r\nRepublic, Eye-wash, Ethics, Global, Home Ministry, File, Legend, National Assembly. Organization, Passport, Pension, Range, Symbol. Sanction, Workshop.', NULL, NULL, NULL, NULL, NULL, 'Republic=প্রজাতন্ত্র,\r\nEthics=নীতিশাস্ত্র,\r\nGlobal=বিশ্বব্যাপী,\r\nFile=নথি,\r\nPassport=পাসপোর্ট।', NULL, 47, 'পারিভাষিক শব্দ', 'Writing', '1', '2025', NULL, 2, NULL, NULL, NULL, NULL, '2025-12-23 04:49:27', '2025-12-23 04:49:27'),
(63, 'নিচের অনুচ্ছেদটি বাংলায় অনুবাদ কর।\r\nBangladesh is the land of our birth. The blue sky and the fresh air of this land are very dear to us. It is our duty to build up our dear Bangladesh. It is our sacred duty. If we do our respective duties, then only our country will make progress.', NULL, NULL, NULL, NULL, NULL, 'বাংলাদেশ আমাদের জন্মভূমি। এ দেশের নীল আকাশ ও নির্মল বাতাস আমাদের কাছে খুবই প্রিয়। আমাদের প্রিয় বাংলাদেশকে গড়ে তোলা আমাদের কর্তব্য। এটি আমাদের পবিত্র দায়িত্ব। আমরা যদি প্রত্যেকে নিজ নিজ দায়িত্ব পালন করি, তবেই আমাদের দেশ অগ্রগতি লাভ করবে।', NULL, 47, 'অনুবাদ', 'Writing', '1', '2025', NULL, 2, NULL, NULL, NULL, NULL, '2025-12-23 04:51:21', '2025-12-23 04:51:21'),
(64, 'যে কোনো ঐতিহাসিক স্থান ভ্রমণের ঘটনা নিয়ে দিনলিপি রচনা কর।', NULL, NULL, NULL, NULL, NULL, '**দিনলিপি : সোনারগাঁও ভ্রমণ**\r\n\r\nআজ আমি সোনারগাঁও ভ্রমণে গিয়েছিলাম।\r\nসোনারগাঁও বাংলাদেশের একটি প্রাচীন ও ঐতিহাসিক স্থান।\r\nসকাল বেলা আমরা পরিবারসহ রওনা দিই।\r\nঢাকা থেকে সোনারগাঁও পৌঁছাতে বেশি সময় লাগেনি।\r\nপ্রথমে আমরা লোক ও কারুশিল্প জাদুঘর দেখতে যাই।\r\nজাদুঘরে পুরোনো দিনের নানা নিদর্শন দেখে মুগ্ধ হই।\r\nএরপর পানাম নগরী ঘুরে দেখি।\r\nপানাম নগরীর পুরোনো দালানগুলো ইতিহাসের সাক্ষী হয়ে দাঁড়িয়ে আছে।\r\nভাঙা দেয়াল আর নীরব পরিবেশ আমাকে ভাবিয়ে তোলে।\r\nমনে হলো যেন অতীতে ফিরে গেছি।\r\nসেখানকার প্রশস্ত রাস্তা ও স্থাপত্যশৈলী খুব আকর্ষণীয়।\r\nআমি অনেক ছবি তুললাম।\r\nবন্ধুদের সঙ্গে ঘোরাঘুরি করে বেশ আনন্দ পেলাম।\r\nদুপুরে সবাই মিলে খাবার খাই।\r\nএরপর আশপাশের এলাকা ঘুরে দেখি।\r\nসোনারগাঁওয়ের প্রাকৃতিক পরিবেশ মন ছুঁয়ে যায়।\r\nএই ভ্রমণ থেকে আমরা ইতিহাস সম্পর্কে অনেক কিছু জানতে পারলাম।\r\nভ্রমণটি ছিল শিক্ষণীয় ও আনন্দময়।\r\nসন্ধ্যার দিকে আমরা ঢাকার উদ্দেশ্যে রওনা দিই।\r\nএই স্মরণীয় দিনটি আমার মনে দীর্ঘদিন থাকবে।', NULL, 47, 'দিনলিপি', 'Writing', '1', '2025', NULL, 2, NULL, NULL, NULL, NULL, '2025-12-23 04:52:54', '2025-12-23 04:52:54'),
(65, 'নিত্যপ্রয়োজনীয় দ্রব্যের মূল্যবৃদ্ধি সম্পর্কে একটি প্রতিবেদন রচনা কর।', NULL, NULL, NULL, NULL, NULL, '**প্রতিবেদন**\r\n**বিষয় : নিত্যপ্রয়োজনীয় দ্রব্যের মূল্যবৃদ্ধি**\r\n\r\nবর্তমান সময়ে নিত্যপ্রয়োজনীয় দ্রব্যের মূল্যবৃদ্ধি দেশের সাধারণ মানুষের জীবনে বড় ধরনের প্রভাব ফেলেছে। চাল, ডাল, তেল, সবজি, পেঁয়াজ, আলু, চিনি, মাছ ও মাংসসহ প্রায় সব নিত্যপ্রয়োজনীয় পণ্যের দাম ধারাবাহিকভাবে বেড়ে চলেছে। এতে নিম্ন ও মধ্যবিত্ত শ্রেণির মানুষের জীবনযাত্রা দিন দিন কঠিন হয়ে উঠছে।\r\n\r\nনিত্যপ্রয়োজনীয় দ্রব্যের মূল্যবৃদ্ধির পেছনে একাধিক কারণ রয়েছে। এর মধ্যে উৎপাদন খরচ বৃদ্ধি, জ্বালানি তেলের দাম বৃদ্ধি, পরিবহন ব্যয় বৃদ্ধি, আমদানি নির্ভরতা, বাজারে কৃত্রিম সংকট সৃষ্টি, মজুদদারি ও অসাধু ব্যবসায়ীদের সিন্ডিকেট অন্যতম। এছাড়া প্রাকৃতিক দুর্যোগ ও বৈশ্বিক অর্থনৈতিক সংকটও মূল্যবৃদ্ধিতে ভূমিকা রাখছে।\r\n\r\nমূল্যবৃদ্ধির ফলে সাধারণ মানুষের আয় ও ব্যয়ের মধ্যে বড় ধরনের অসামঞ্জস্য সৃষ্টি হয়েছে। সীমিত আয়ের মানুষ প্রয়োজনীয় খাদ্যসামগ্রী কিনতে হিমশিম খাচ্ছে। অনেক পরিবার বাধ্য হয়ে খাদ্যতালিকা কমিয়ে দিচ্ছে বা পুষ্টিকর খাবার থেকে বঞ্চিত হচ্ছে। এর ফলে স্বাস্থ্যঝুঁকি ও সামাজিক সমস্যাও বাড়ছে।\r\n\r\nএই পরিস্থিতি মোকাবিলায় সরকারকে কঠোর বাজার তদারকি নিশ্চিত করতে হবে। মজুদদারি ও কৃত্রিম সংকট সৃষ্টিকারীদের বিরুদ্ধে আইনানুগ ব্যবস্থা নিতে হবে। একই সঙ্গে কৃষি উৎপাদন বাড়ানো, আমদানি প্রক্রিয়া সহজ করা এবং ন্যায্যমূল্যে পণ্য সরবরাহ নিশ্চিত করা জরুরি। ভোক্তা সচেতনতা বৃদ্ধিও এ ক্ষেত্রে গুরুত্বপূর্ণ ভূমিকা রাখতে পারে।\r\n\r\nপরিশেষে বলা যায়, নিত্যপ্রয়োজনীয় দ্রব্যের মূল্যবৃদ্ধি একটি গুরুতর জাতীয় সমস্যা। সরকার, ব্যবসায়ী ও ভোক্তা—সবার সম্মিলিত উদ্যোগেই এ সমস্যার স্থায়ী সমাধান সম্ভব।', NULL, 47, 'প্রতিবেদন', 'Writing', '1', '2025', NULL, 2, NULL, NULL, NULL, NULL, '2025-12-23 04:54:05', '2025-12-23 04:54:05'),
(66, 'মোবাইল ফোনের অপব্যবহারের ক্ষতিগুলো উল্লেখ করে বন্ধুকে একটি ই-মেইল প্রেরণ কর।', NULL, NULL, NULL, NULL, NULL, 'From: info@examdao.com\r\nTo: friend1@gmail.com\r\nCc: friend2@gmail.com\r\nBcc: teacher@gmail.com\r\n\r\nSubject: মোবাইল ফোনের অপব্যবহারের ক্ষতিকর দিক সম্পর্কে সতর্কতা\r\n\r\nপ্রিয় বন্ধু,\r\n\r\nআশা করি তুমি ভালো আছ। আজ তোমাকে একটি গুরুত্বপূর্ণ বিষয়ে জানাতে এই ই-মেইলটি লিখছি। বর্তমানে মোবাইল ফোন আমাদের দৈনন্দিন জীবনের একটি প্রয়োজনীয় উপকরণ হলেও এর অপব্যবহার আমাদের জন্য নানা ক্ষতির কারণ হয়ে দাঁড়াচ্ছে।\r\n\r\nঅতিরিক্ত মোবাইল ফোন ব্যবহারের ফলে পড়াশোনায় মনোযোগ কমে যায় এবং সময়ের অপচয় ঘটে। সামাজিক যোগাযোগমাধ্যম ও অনলাইন গেমে আসক্তির কারণে অনেকেই বাস্তব জীবন থেকে দূরে সরে যাচ্ছে। এতে পারিবারিক সম্পর্কের অবনতি ঘটে। এছাড়া দীর্ঘ সময় মোবাইল ব্যবহার করলে চোখের সমস্যা, মাথাব্যথা, ঘুমের ব্যাঘাত ও মানসিক চাপ সৃষ্টি হয়।\r\n\r\nতাই আমাদের উচিত প্রয়োজন ছাড়া মোবাইল ফোন ব্যবহার না করা এবং সময়ের সঠিক ব্যবহার করা। পড়াশোনা ও শরীরচর্চায় বেশি মনোযোগ দেওয়া প্রয়োজন।\r\n\r\nআশা করি তুমি বিষয়টি গুরুত্বের সঙ্গে বিবেচনা করবে এবং নিজে সচেতন হবে।\r\n\r\nইতি\r\nতোমার বন্ধু\r\nক', NULL, 47, 'ই-মেইল', 'Writing', '1', '2025', NULL, 2, NULL, NULL, NULL, NULL, '2025-12-23 05:07:18', '2025-12-23 05:07:18'),
(67, 'সহকারী শিক্ষক পদে নিয়োগ লাভের জন্য একটি আবেদনপত্র রচনা কর।', NULL, NULL, NULL, NULL, NULL, 'বরাবর\r\nমাননীয় প্রধান শিক্ষক\r\nপলাশবাড়ী উচ্চ বিদ্যালয়\r\n\r\nবিষয়: সহকারী শিক্ষক পদে নিয়োগের আবেদন।\r\n\r\nমহোদয়,\r\nবিনীত নিবেদন এই যে, আমি পলাশবাড়ী কলেজ থেকে বাংলায় এইচএসসি উত্তীর্ণ। আপনার বিদ্যালয়ে শূন্য ঘোষিত সহকারী শিক্ষক পদে নিয়োগের জন্য আমি আবেদন করছি। শিক্ষাদানে আমার আগ্রহ রয়েছে এবং সুযোগ পেলে সততা ও নিষ্ঠার সঙ্গে দায়িত্ব পালন করব।\r\n\r\nঅতএব, আমার আবেদনটি সদয়ভাবে বিবেচনা করে আমাকে নিয়োগ দিলে চিরকৃতজ্ঞ থাকব।\r\n\r\nবিনীত\r\nনাম : মোঃ রেজাউল ইসলাম\r\nঠিকানা : ঢাকা, বাংলাদেশ', NULL, 47, 'আবেদনপত্র', 'Writing', '1', '2025', NULL, 2, NULL, NULL, NULL, NULL, '2025-12-23 05:16:26', '2025-12-23 05:16:26'),
(68, 'সারাংশ লেখ:\r\nস্বাধীন হওয়ার জন্য যেমন সাধনার প্রয়োজন হয়, তেমনই স্বাধীনতা রক্ষার জন্য প্রয়োজন সত্যনিষ্ঠা ও ন্যায়পরায়ণতার। সত্যের প্রতি শ্রদ্ধাবোধহীন জাতি যতই চেষ্টা করুক, তাদের আবেদন নিবেদনে ফল হয় না। যে জাতির অধিকাংশ ব্যক্তি মিথ্যাচারী, সেখানে দু-চারজন সত্যনিষ্ঠকে বহু বিড়ম্বনা সহ্য করতে হয়, দুর্ভোগ পোহাতে হয়। কিন্তু মানুষ জাতি হিসেবে মাথা তুলে দাঁড়াতে হলে সে কষ্ট সহ্য না করে উপায় নেই।', NULL, NULL, NULL, NULL, NULL, 'স্বাধীন হওয়ার জন্য যেমন প্রচেষ্টা ও সাধনার প্রয়োজন, স্বাধীনতা রক্ষার জন্য তেমনই প্রয়োজন সত্যনিষ্ঠা ও ন্যায়পরায়ণতা। মিথ্যার প্রতি অনীহা না থাকলে জাতি উন্নতি করতে পারে না। যে জাতির অধিকাংশ মানুষ মিথ্যাচারী, সেখানে সত্যনিষ্ঠদের অনেক কষ্ট ও দুর্ভোগ সহ্য করতে হয়। তাই মানুষকে জাতি হিসেবে মাথা তুলে দাঁড়াতে হলে কষ্ট সহ্য করতে বাধ্য হতে হয়।', NULL, 47, 'সারাংশ', 'Writing', '1', '2025', NULL, 2, NULL, NULL, NULL, NULL, '2025-12-23 05:20:49', '2025-12-23 05:20:49'),
(69, 'ভাব-সম্প্রসারণ কর:\r\nতুমি অধম-তাই বলিয়া আমি উত্তম না হইব কেন?', NULL, NULL, NULL, NULL, NULL, 'অন্যের অপমান বা অবমূল্যায়ন আমাদের চরিত্র বা মর্যাদা কমায় না। কেউ যদি আমাদের অধম বলে, তা মানে আমরা সত্যিই অধম, এমন না। একজন মানুষ নিজের আদর্শ, নৈতিকতা এবং সততার মাধ্যমে নিজের মর্যাদা তৈরি করে। অন্যের মন্তব্য বা সমালোচনা আমাদের গুণকে নষ্ট করতে পারে না। সুতরাং, আমাদের উচিত নিজের চরিত্র ও মনোবলকে দৃঢ় রাখা, অন্যের নেতিবাচক কথার প্রতি মনোযোগ না দেওয়া এবং সদাচার, ন্যায়পরায়ণতা ও সত্যনিষ্ঠার মাধ্যমে উত্তম হওয়ার চেষ্টা করা।', NULL, 47, 'ভাব সম্প্রসারণ', 'Writing', '1', '2025', NULL, 2, NULL, NULL, NULL, NULL, '2025-12-23 05:23:52', '2025-12-23 05:23:52'),
(70, 'লাইব্রেরি প্রতিষ্ঠা নিয়ে দুই বন্ধুর মধ্যে সংলাপ রচনা কর।', NULL, NULL, NULL, NULL, NULL, '<p><b>আলিম:</b> হ্যালো রোজি! শুনেছি তুমি স্কুলে লাইব্রেরি প্রতিষ্ঠার উদ্যোগ নিয়েছো।</p>\r\n\r\n<p><b>রোজি:</b> হ্যাঁ আলিম। আমাদের স্কুলে পড়ুয়া অনেক ছাত্রছাত্রী শিক্ষার সুযোগ পায় না, তাই আমি ভাবলাম লাইব্রেরি থাকা উচিত।</p>\r\n\r\n<p><b>আলিম:</b> দারুণ চিন্তা! কিন্তু লাইব্রেরি খোলার জন্য বই আর জায়গার ব্যবস্থা কেমন হবে?</p>\r\n\r\n<p><b>রোজি:</b> আমরা স্কুলে অব্যবহৃত একটি কক্ষে বই রাখার পরিকল্পনা করেছি। শিক্ষক এবং অভিভাবকরা কিছু পুরনো বই দিতে রাজি হয়েছেন।</p>\r\n\r\n<p><b>আলিম:</b> খুব ভালো। লাইব্রেরি থাকলে পড়াশোনার সঙ্গে সঙ্গে ছাত্রছাত্রীদের জ্ঞানও বৃদ্ধি পাবে।</p>\r\n\r\n<p><b>রোজি:</b> ঠিক বলেছো। লাইব্রেরি শুধু পড়াশোনার জন্য নয়, সৃজনশীলতা ও মননশীলতা বৃদ্ধিরও জায়গা হবে।</p>\r\n\r\n<p><b>আলিম:</b> তাহলে শুরু করতে হলে আমরা ছাত্রছাত্রীদেরও উৎসাহিত করতে পারি।</p>\r\n\r\n<p><b>রোজি:</b> হ্যাঁ, আমি ভাবছি লাইব্রেরিতে নিয়মিত গল্প পাঠ ও আলোচনা আয়োজন করা হবে।</p>\r\n\r\n<p><b>আলিম:</b> দারুণ! আশা করি আমাদের স্কুলে সবাই লাইব্রেরি ব্যবহার করবে এবং বইপড়ার অভ্যাস গড়ে উঠবে।</p>\r\n\r\n<p><b>রোজি:</b> আমিও সেই আশা রাখি।</p>', NULL, 47, 'সংলাপ', 'Writing', '1', '2025', NULL, 2, NULL, NULL, NULL, NULL, '2025-12-23 05:27:15', '2025-12-23 05:27:15'),
(71, 'প্রদত্ত উদ্দীপক অনুসরণে একটি ক্ষুদে গল্প রচনা কর:\r\nমাকে সেবা করতে না পারার যন্ত্রণায় দগ্ধ হয় হাসান……..', NULL, NULL, NULL, NULL, NULL, 'শিরোনাম: মায়ের জন্য হাসানের শিক্ষা\r\n\r\nহাসান ছোটোবেলা থেকে মাকে ভালোবাসত, কিন্তু পড়াশোনা ও খেলার জন্য মাকে সাহায্য করতে কমই সময় পেত।  \r\nএকদিন মা অসুস্থ হয়ে পড়লেন। হাসান দ্রুত সাহায্য করতে চাইলেও কাজ ঠিকমতো করতে পারল না।  \r\nরান্নাঘরে গেল এবং হাসান ভীষণ দগ্ধ বোধ করল—মাকে সেবা করতে না পারার যন্ত্রণায় তার মন ব্যথিত হল।  \r\nমা ধৈর্য ধরে বললেন, “সব কাজ একসাথে করা যায় না, ধৈর্য ধরে শেখো।”  \r\nহাসান ঠিক করল, সে মায়ের সাহায্য করবে, ছোট দায়িত্বও যত্নসহকারে পালন করবে।  \r\nপরদিন থেকে সে রান্না, ঘর পরিচ্ছন্নতা এবং মায়ের দৈনন্দিন কাজ শিখল।  \r\nধীরে ধীরে হাসান সব কাজ করতে পারল এবং মায়ের মুখে আনন্দ দেখল।  \r\nসে শিখল, মায়ের সেবা শুধু দায়িত্ব নয়, ভালোবাসার প্রকাশও।  \r\nমায়ের সেবা করতে পারার আনন্দে হাসানের মন শান্তি পেল।  \r\nএই অভিজ্ঞতা তাকে মায়ের প্রতি আরও যত্নশীল এবং দায়িত্বশীল করে তুলল।', NULL, 47, 'ক্ষুদে গল্প', 'Writing', '1', '2025', NULL, 2, NULL, NULL, NULL, NULL, '2025-12-23 05:30:08', '2025-12-23 05:30:08'),
(72, 'যে কোনো একটি বিষয় অবলম্বনে প্রবন্ধ রচনা কর:\r\n(ক) শিষ্টাচারের গুরুত্ব;\r\n(খ) ইন্টারনেট। বিশ্বের সেতুবন্ধন;\r\n(গ) একুশের চেতনা;\r\n(ঘ) বাংলাদেশের পর্যটন শিল্প;\r\n(ঙ) রোহিঙ্গা সমস্যা ও সমাধান।', NULL, NULL, NULL, NULL, NULL, '<p><b>ভূমিকা:</b><br>\nআধুনিক সভ্যতার ইতিহাসে ইন্টারনেট এক বৈপ্লবিক আবিষ্কার। এটি কেবল একটি প্রযুক্তিগত উৎকর্ষ নয়, বরং বিশ্বজুড়ে সামাজিক, শিক্ষা, ব্যবসা ও যোগাযোগের ক্ষেত্রে এক অভূতপূর্ব পরিবর্তন এনেছে। ভৌগোলিক দূরত্বকে জয় করে ইন্টারনেট মুহূর্তেই সমগ্র বিশ্বকে এক সুতোয় গেঁথেছে, যার ফলে তথ্য আদান-প্রদান হয়েছে অবিশ্বাস্য দ্রুত ও সহজসাধ্য।</p><br>\n\n<p><b>শিক্ষাক্ষেত্রে প্রভাব:</b><br>\nশিক্ষার প্রসারে ইন্টারনেট এক অন্তহীন ভাণ্ডার। শিক্ষার্থীরা বিশ্বমানের ই-বুক, গবেষণাপত্র ও টিউটোরিয়ালের মাধ্যমে জ্ঞান অন্বেষণ করতে পারছে। শিক্ষক ও শিক্ষা প্রতিষ্ঠানগুলো ডিজিটাল প্ল্যাটফর্ম ব্যবহার করে পাঠদানকে আরও আকর্ষণীয় ও ইন্টারেক্টিভ করে তুলছে, যা প্রথাগত শিক্ষা ব্যবস্থাকে এক নতুন মাত্রায় নিয়ে গেছে।</p><br>\n\n<p><b>ব্যবসা ও বাণিজ্যে প্রভাব:</b><br>\nই-কমার্স, অনলাইন ব্যাংকিং ও ডিজিটাল মার্কেটিংয়ের বদৌলতে ব্যবসা-বাণিজ্যের ধরন আমূল বদলে গেছে। ক্ষুদ্র ও বৃহৎ উদ্যোক্তারা এখন ঘরে বসেই বিশ্ববাজারে পণ্য ও সেবা পৌঁছে দিচ্ছেন। এটি যেমন অর্থনৈতিক গতিশীলতা বাড়িয়েছে, তেমনি সৃষ্টি করেছে অগণিত নতুন কর্মসংস্থান।</p><br>\n\n<p><b>সামাজিক যোগাযোগ:</b><br>\nসামাজিক মাধ্যমগুলো মানুষের মধ্যে পারস্পরিক মিথস্ক্রিয়াকে সহজতর করেছে। দূর-দূরান্তে থাকা বন্ধু-বান্ধব ও আত্মীয়-স্বজনের সাথে নিবিড় যোগাযোগ বজায় রাখার পাশাপাশি এটি জনমত গঠন ও সামাজিক সচেতনতা বৃদ্ধিতে এক শক্তিশালী মাধ্যম হিসেবে আবির্ভূত হয়েছে।</p><br>\n\n<p><b>স্বাস্থ্য খাতে প্রভাব:</b><br>\nটেলিপ্যাথি ও টেলিমেডিসিন সেবার মাধ্যমে দূরবর্তী অঞ্চলের মানুষও এখন বিশেষজ্ঞ চিকিৎসকের পরামর্শ পাচ্ছে। এছাড়া ইন্টারনেটের মাধ্যমে স্বাস্থ্য সচেতনতা ও রোগ প্রতিরোধের তথ্য দ্রুত ছড়িয়ে দেওয়া সম্ভব হচ্ছে, যা জনস্বাস্থ্যের উন্নয়নে বিশেষ ভূমিকা রাখছে।</p><br>\n\n<p><b>নেতিবাচক প্রভাব:</b><br>\nতথ্যের অবাধ প্রবাহের পাশাপাশি ইন্টারনেটের অপব্যবহারও বাড়ছে। অতিরিক্ত আসক্তি যেমন সময়ের অপচয় ঘটাচ্ছে, তেমনি সাইবার অপরাধ, তথ্য চুরি ও অপপ্রচারের মতো ঝুঁকিও তৈরি করছে। তাই ইন্টারনেটের সুফল পেতে এর পরিমিত ও সচেতন ব্যবহার অত্যন্ত জরুরি।</p><br>\n\n<p><b>বৈশ্বিক সেতুবন্ধন:</b><br>\nইন্টারনেট বিশ্বকে একটি \'গ্লোবাল ভিলেজ\' বা বৈশ্বিক গ্রামে পরিণত করেছে। সীমানা ও দূরত্বের দেয়াল ভেঙে এটি মানুষের মধ্যে সৌহার্দ্য ও সহমর্মিতা ছড়িয়ে দিচ্ছে। বিশ্বের যেকোনো প্রান্তের সংস্কৃতি ও সংকট সম্পর্কে অবগত হওয়ার ফলে আমাদের বৈশ্বিক দৃষ্টিভঙ্গি আরও প্রসারিত হচ্ছে।</p><br>\n\n<p><b>সচেতন ব্যবহার:</b><br>\nইন্টারনেটের আশীর্বাদকে অর্থবহ করতে নৈতিকতা ও নিরাপত্তা নিশ্চিত করা অপরিহার্য। বিশেষ করে নতুন প্রজন্মকে নিরাপদ ইন্টারনেট ব্যবহারের শিক্ষা দিতে হবে, যাতে তারা গুজব ও অনৈতিক বিষয়বস্তু থেকে দূরে থেকে গঠনমূলক কাজে নিজেদের নিয়োজিত করতে পারে।</p><br>\n\n<p><b>উপসংহার:</b><br>\nপরিশেষে বলা যায়, ইন্টারনেট বর্তমান মানবজীবনের এক অবিচ্ছেদ্য অংশ। এটি যেমন সম্ভাবনার অবারিত দ্বার খুলে দিয়েছে, তেমনি এর সঠিক ব্যবহারের ওপরই নির্ভর করছে আমাদের ভবিষ্যৎ সমৃদ্ধি। সুস্থ চিন্তা ও নিয়ন্ত্রিত ব্যবহারের মাধ্যমেই ইন্টারনেট আমাদের সমাজকে আরও উন্নত ও প্রগতিশীল করে তুলবে।</p>', NULL, 47, 'রচনা', 'Writing', '1', '2025', NULL, 2, NULL, NULL, NULL, NULL, '2025-12-23 05:36:49', '2025-12-23 05:36:49'),
(73, 'HSC Bangla 2nd paper MCQ Question Dhaka Board 2025 | ঢাকা বোর্ড ২০২৫ সালের এইচএসসি বাংলা দ্বিতীয় পত্র এমসিকিউ প্রশ্ন', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 47, NULL, 'MCQ', '1', '2025', NULL, 2, 'images/questions/hsc-bangla-2-dhaka-board-2025-1-73.webp', 'images/questions/hsc-bangla-2-dhaka-board-2025-2-73.webp', NULL, NULL, '2025-12-23 11:05:01', '2025-12-23 11:05:01'),
(74, 'HSC 2025 সালের Dhaka Board এর ইংরেজি প্রশ্নের সমাধান', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 48, NULL, 'CQ', '1', '2025', NULL, 2, 'images/questions/hsc-dhaka-board-2025-1-74.webp', 'images/questions/hsc-dhaka-board-2025-2-74.webp', 'images/questions/hsc-dhaka-board-2025-3-74.webp', 'images/questions/hsc-dhaka-board-2025-4-74.webp', '2025-12-25 08:14:08', '2025-12-25 08:14:08');

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
('1QJXDlbWYIqQCxBFCFtRjTtpZZJcYPLNbBinFGuf', NULL, '49.12.205.98', 'SeobilityBot (SEO Tool; https://www.seobility.net/sites/bot.html)', 'YTozOntzOjY6Il90b2tlbiI7czo0MDoiTUZ4alRLQlJtYWZvZjJiMmtUQjhPWEc2NUNHcHZtV1BBMGhtMGVmNiI7czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6MTk6Imh0dHBzOi8vZXhhbWRhby5jb20iO3M6NToicm91dGUiO3M6NDoiaG9tZSI7fXM6NjoiX2ZsYXNoIjthOjI6e3M6Mzoib2xkIjthOjA6e31zOjM6Im5ldyI7YTowOnt9fX0=', 1766984473),
('5EKTRDJf75QasPmJcoYrnfIwk8XmpLazYflv3zm9', NULL, '157.55.39.55', 'Mozilla/5.0 AppleWebKit/537.36 (KHTML, like Gecko; compatible; bingbot/2.0; +http://www.bing.com/bingbot.htm) Chrome/116.0.1938.76 Safari/537.36', 'YTozOntzOjY6Il90b2tlbiI7czo0MDoiaHNkaDA3QjNwZDljb2lBdDN3dFRNMkVueTNzUzVuMUxrQmQwc0NnVCI7czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6MzAxOiJodHRwczovL2V4YW1kYW8uY29tL3F1ZXN0aW9ucy83NC9oc2MtMjAyNS0lRTAlQTYlQjglRTAlQTYlQkUlRTAlQTYlQjIlRTAlQTclODclRTAlQTYlQjAtZGhha2EtYm9hcmQtJUUwJUE2JThGJUUwJUE2JUIwLSVFMCVBNiU4NyVFMCVBNiU4MiVFMCVBNiVCMCVFMCVBNyU4NyVFMCVBNiU5QyVFMCVBNiVCRi0lRTAlQTYlQUElRTAlQTclOEQlRTAlQTYlQjAlRTAlQTYlQjYlRTAlQTclOEQlRTAlQTYlQTglRTAlQTclODclRTAlQTYlQjAtJUUwJUE2JUI4JUUwJUE2JUFFJUUwJUE2JUJFJUUwJUE2JUE3JUUwJUE2JUJFJUUwJUE2JUE4IjtzOjU6InJvdXRlIjtzOjE0OiJxdWVzdGlvbnMuc2hvdyI7fXM6NjoiX2ZsYXNoIjthOjI6e3M6Mzoib2xkIjthOjA6e31zOjM6Im5ldyI7YTowOnt9fX0=', 1766986353),
('c2KGnQ3yjhFFU5n7o3TsWPauqWBk2rkl6RaEMruO', NULL, '40.77.167.26', 'Mozilla/5.0 AppleWebKit/537.36 (KHTML, like Gecko; compatible; bingbot/2.0; +http://www.bing.com/bingbot.htm) Chrome/116.0.1938.76 Safari/537.36', 'YTozOntzOjY6Il90b2tlbiI7czo0MDoiOEVoWlVTWWFWdThzRFlMa1AyZUNZRDRyMWJDWDFBQUJ4YmZFMVdWMCI7czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6ODM2OiJodHRwczovL2V4YW1kYW8uY29tL3F1ZXN0aW9ucy83LyVFMCVBNiVBQSVFMCVBNiVCRSVFMCVBNiVBQSVFMCVBNiVBOC0lRTAlQTYlOTUlRTAlQTYlQUUlRTAlQTclOEQlRTAlQTYlQUElRTAlQTYlQkYlRTAlQTYlODklRTAlQTYlOUYlRTAlQTYlQkUlRTAlQTYlQjAlRTAlQTclODctJUUwJUE2JUE0JUUwJUE3JThEJUUwJUE2JUIwJUUwJUE2JUJGJUUwJUE2JUFEJUUwJUE3JTgxJUUwJUE2JTlDJUUwJUE3JTg3JUUwJUE2JUIwLSVFMCVBNiU5NSVFMCVBNyU4RCVFMCVBNiVCNyVFMCVBNyU4NyVFMCVBNiVBNCVFMCVBNyU4RCVFMCVBNiVCMCVFMCVBNiVBQiVFMCVBNiVCMi0lRTAlQTYlQTglRTAlQTYlQkYlRTAlQTYlQjAlRTAlQTclOEQlRTAlQTYlQTMlRTAlQTYlQUYlRTAlQTYlQkMlRTAlQTclODclRTAlQTYlQjAtJUUwJUE2JUFBJUUwJUE3JThEJUUwJUE2JUIwJUUwJUE3JThCJUUwJUE2JTk3JUUwJUE3JThEJUUwJUE2JUIwJUUwJUE2JUJFJUUwJUE2JUFFLSVFMCVBNiU5QSVFMCVBNiVBNCVFMCVBNyU4MSVFMCVBNiVCMCVFMCVBNyU4RCVFMCVBNiVBNS0lRTAlQTYlQUElRTAlQTclOEQlRTAlQTYlQjAlRTAlQTYlOUMlRTAlQTYlQTglRTAlQTclOEQlRTAlQTYlQUUlRTAlQTclODclRTAlQTYlQjAtJUUwJUE2JUFEJUUwJUE2JUJFJUUwJUE2JUI3JUUwJUE2JUJFJUUwJUE2JUFGJUUwJUE2JUJDLSVFMCVBNiVBNCVFMCVBNyU4OCVFMCVBNiVCMCVFMCVBNiVCRi0lRTAlQTYlOTUlRTAlQTYlQjAlRTAlQTYlQjItJUUwJUE2JUFBJUUwJUE2JUJFJUUwJUE2JUFBJUUwJUE2JUE4JUUwJUE3JTg3JUUwJUE2JUIwLSVFMCVBNiVBQyVFMCVBNiVBOCVFMCVBNyU4RCVFMCVBNiVBNyI7czo1OiJyb3V0ZSI7czoxNDoicXVlc3Rpb25zLnNob3ciO31zOjY6Il9mbGFzaCI7YToyOntzOjM6Im9sZCI7YTowOnt9czozOiJuZXciO2E6MDp7fX19', 1766985966),
('cCYI8W9nm9XUpeafJXzSAwcPy78YEDW0xMZivLlU', NULL, '66.249.70.71', 'Mozilla/5.0 (compatible; Googlebot/2.1; +http://www.google.com/bot.html)', 'YTozOntzOjY6Il90b2tlbiI7czo0MDoibUxSR0NoWGF3VVRGR0EyQnhJUkxPU2YzeVRlb1paRE1MQjZKQTVaNSI7czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6MTk6Imh0dHBzOi8vZXhhbWRhby5jb20iO3M6NToicm91dGUiO3M6NDoiaG9tZSI7fXM6NjoiX2ZsYXNoIjthOjI6e3M6Mzoib2xkIjthOjA6e31zOjM6Im5ldyI7YTowOnt9fX0=', 1766987061),
('cHwRGRV02wjwzIVsculH6Zt0PPFbGehH6VpV8uwq', NULL, '165.22.108.190', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/131.0.0.0 Safari/537.36', 'YTozOntzOjY6Il90b2tlbiI7czo0MDoiRGFUcFRIakdXakVsVTBFckg4Qk9BYWpkaXZ1bDdoa1lpZW9KVTJtMiI7czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6MTk6Imh0dHBzOi8vZXhhbWRhby5jb20iO3M6NToicm91dGUiO3M6NDoiaG9tZSI7fXM6NjoiX2ZsYXNoIjthOjI6e3M6Mzoib2xkIjthOjA6e31zOjM6Im5ldyI7YTowOnt9fX0=', 1766982469),
('CZjGIsOMvZsE8gxZmHRdyhMJfLJ64uojwCMIkE5p', NULL, '66.249.70.70', 'Mozilla/5.0 (Linux; Android 6.0.1; Nexus 5X Build/MMB29P) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.7390.122 Mobile Safari/537.36 (compatible; Googlebot/2.1; +http://www.google.com/bot.html)', 'YTozOntzOjY6Il90b2tlbiI7czo0MDoiNUEzOHpQS0J3cHVFQmVZTXcxWTZQWTB3RkVZWU8zVWlhTHBMSm51ayI7czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6MTk6Imh0dHBzOi8vZXhhbWRhby5jb20iO3M6NToicm91dGUiO3M6NDoiaG9tZSI7fXM6NjoiX2ZsYXNoIjthOjI6e3M6Mzoib2xkIjthOjA6e31zOjM6Im5ldyI7YTowOnt9fX0=', 1766987111),
('d0UoD0bVt9VdIoZKP42RnIHha0nXTHuDQ1ziRSyT', NULL, '157.55.39.60', 'Mozilla/5.0 AppleWebKit/537.36 (KHTML, like Gecko; compatible; bingbot/2.0; +http://www.bing.com/bingbot.htm) Chrome/116.0.1938.76 Safari/537.36', 'YTozOntzOjY6Il90b2tlbiI7czo0MDoiR2JqZWpRQXlZd1VWU3hRQ0FuOGJMaGpDb2F3bjZPcm9NeDg3WmFNeCI7czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6NTc0OiJodHRwczovL2V4YW1kYW8uY29tL3F1ZXN0aW9ucy8zOS8lRTAlQTYlOUElRTAlQTYlQkUlRTAlQTYlQjAlRTAlQTYlQkYlRTAlQTYlQTYlRTAlQTYlQkYlRTAlQTYlOTUlRTAlQTclODctJUUwJUE2JUFDJUUwJUE2JUJFJUUwJUE2JTgxJUUwJUE2JTk1JUUwJUE2JUJFJUUwJUE2JTlDJUUwJUE2JUIyLSVFMCVBNiU5NSVFMCVBNiVCMCVFMCVBNiVCRiVFMCVBNiU5QiVFMCVBNyU4Ny0lRTAlQTYlOTYlRTAlQTclODclRTAlQTYlQjIlRTAlQTYlQkUtJUUwJUE2JThGJUUwJUE2JTk2JUUwJUE2JUJFJUUwJUE2JUE4JUUwJUE3JTg3LSVFMCVBNiVBQyVFMCVBNiVCRSVFMCVBNiU4MSVFMCVBNiU5NSVFMCVBNiVCRSVFMCVBNiU5QyVFMCVBNiVCMi0lRTAlQTYlQUMlRTAlQTYlQjIlRTAlQTYlQTQlRTAlQTclODctJUUwJUE2JTk1JUUwJUE2JUFDJUUwJUE2JUJGLSVFMCVBNiU5NSVFMCVBNyU4MC0lRTAlQTYlQUMlRTAlQTclOEIlRTAlQTYlOUQlRTAlQTYlQkUlRTAlQTYlQTQlRTAlQTclODctJUUwJUE2JTlBJUUwJUE3JTg3JUUwJUE2JUFGJUUwJUE2JUJDJUUwJUE3JTg3JUUwJUE2JTlCJUUwJUE3JTg3JUUwJUE2JUE4IjtzOjU6InJvdXRlIjtzOjE0OiJxdWVzdGlvbnMuc2hvdyI7fXM6NjoiX2ZsYXNoIjthOjI6e3M6Mzoib2xkIjthOjA6e31zOjM6Im5ldyI7YTowOnt9fX0=', 1766978590),
('eEiVjqnRl4J40ZdvEri4lAVT15JLaeMDm8sy4wK9', NULL, '195.158.210.197', 'Mozilla/5.0 (Linux; Android 6.0; Nexus 5 Build/MRA58N) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/46.0.2801.1736 Mobile Safari/537.36', 'YTozOntzOjY6Il90b2tlbiI7czo0MDoibFduTkROS3lvSGtEMEtpa0JhR0tyUG5aRExqUDZvNk5lMFh3azNjTyI7czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6NDM3OiJodHRwczovL2V4YW1kYW8uY29tL3F1ZXN0aW9ucy8zMS8lRTAlQTYlQUMlRTAlQTclODclRTAlQTYlOUMlRTAlQTclODclRTAlQTYlOUIlRTAlQTclODctJUUwJUE2JTk1JUUwJUE2JUJGLSVFMCVBNiU4NiVFMCVBNiU5NyVFMCVBNiVBRSVFMCVBNiVBOCVFMCVBNyU4MC0lRTAlQTYlOTclRTAlQTYlQkUlRTAlQTYlQTgtJUUwJUE2JTg5JUUwJUE2JTk1JUUwJUE3JThEJUUwJUE2JUE0JUUwJUE2JUJGJUUwJUE2JTlGJUUwJUE2JUJGJUUwJUE2JUE0JUUwJUE3JTg3LSVFMCVBNiU5NSVFMCVBNyU4QiVFMCVBNiVBOC0lRTAlQTYlQUMlRTAlQTYlQkYlRTAlQTYlQjclRTAlQTYlQUYlRTAlQTYlQkMlRTAlQTYlOUYlRTAlQTYlQkYtJUUwJUE2JUFCJUUwJUE3JTgxJUUwJUE2JTlGJUUwJUE3JTg3LSVFMCVBNiU4OSVFMCVBNiVBMCVFMCVBNyU4NyVFMCVBNiU5QiVFMCVBNyU4NyI7czo1OiJyb3V0ZSI7czoxNDoicXVlc3Rpb25zLnNob3ciO31zOjY6Il9mbGFzaCI7YToyOntzOjM6Im9sZCI7YTowOnt9czozOiJuZXciO2E6MDp7fX19', 1766981309),
('ew4dW3kOWZGDdhnGJ6JrNtr2wUGFMPZPPGNSYGJ4', NULL, '66.42.51.230', '', 'YToyOntzOjY6Il90b2tlbiI7czo0MDoidUdtcmIyOGg2elQ3WVBEa2hwTWRuaWVjMlVDdExKTXVrMjdKNUlWSCI7czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319fQ==', 1766988437),
('fV4tsXJ0Wuqu0p4dzLSCM5QszqUCiKuEGLqk1pbS', NULL, '66.42.51.230', '', 'YToyOntzOjY6Il90b2tlbiI7czo0MDoiWVd4TVUxdUFWeUJraTVwNGV5VmIyMVI2djlEazJYUjhZckxhNG1qYSI7czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319fQ==', 1766984505),
('Ga63z1GlHMtfSWYqgFqB3tqrF3sTXKWzAERosq0k', NULL, '157.55.39.10', 'Mozilla/5.0 AppleWebKit/537.36 (KHTML, like Gecko; compatible; bingbot/2.0; +http://www.bing.com/bingbot.htm) Chrome/116.0.1938.76 Safari/537.36', 'YTozOntzOjY6Il90b2tlbiI7czo0MDoiQXJRYks1SEtpMVpMdFhtVUtqSEU5dnE2bERCTjZTbkNKNVFUaUR1VSI7czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6Mjk6Imh0dHBzOi8vZXhhbWRhby5jb20vcXVlc3Rpb25zIjtzOjU6InJvdXRlIjtzOjE1OiJxdWVzdGlvbnMuaW5kZXgiO31zOjY6Il9mbGFzaCI7YToyOntzOjM6Im9sZCI7YTowOnt9czozOiJuZXciO2E6MDp7fX19', 1766985430),
('gJuPKuqZsOEWpWwxxI2lGN9cJJUoQDxjRiVYUSdj', NULL, '152.39.187.10', 'Mozilla/5.0 (Linux; Android 6.0; Nexus 5 Build/MRA58N) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/46.0.5196.1845 Mobile Safari/537.36', 'YTozOntzOjY6Il90b2tlbiI7czo0MDoiM2V6dFV0ODE2VVpMT2NDN2xCOFgySnNxM3BZUWZPaE5MVnFHRFVzWCI7czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6Mzg6Imh0dHBzOi8vZXhhbWRhby5jb20vc3ViamVjdC9iYW5nbGEtMXN0IjtzOjU6InJvdXRlIjtzOjEyOiJzdWJqZWN0LnNob3ciO31zOjY6Il9mbGFzaCI7YToyOntzOjM6Im9sZCI7YTowOnt9czozOiJuZXciO2E6MDp7fX19', 1766988709),
('gRyUCbmcFYaCycMKCna9VfqRf5SL5DE52OO5m2Nr', NULL, '49.37.117.46', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/134.0.0.0 Safari/537.36 Edg/134.0.*.*', 'YTozOntzOjY6Il90b2tlbiI7czo0MDoiekhkMTBjTjFLS2dIRkZjYTE5eGUyenp3c3c4ajFxYXZ6dWFkR2drTSI7czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6MTk6Imh0dHBzOi8vZXhhbWRhby5jb20iO3M6NToicm91dGUiO3M6NDoiaG9tZSI7fXM6NjoiX2ZsYXNoIjthOjI6e3M6Mzoib2xkIjthOjA6e31zOjM6Im5ldyI7YTowOnt9fX0=', 1766987141),
('gxPmsH9pTlYy54xpHiDKlSIFZEQGlmqZSnm36wk9', NULL, '66.249.70.64', 'Mozilla/5.0 (Linux; Android 6.0.1; Nexus 5X Build/MMB29P) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.7390.122 Mobile Safari/537.36 (compatible; GoogleOther)', 'YTozOntzOjY6Il90b2tlbiI7czo0MDoiY0RxY1IyNnQzWTRDZTF2dW1HNlJnbnhGZ002TklCOEpubmFRUW4wTiI7czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6ODEzOiJodHRwczovL2V4YW1kYW8uY29tL3F1ZXN0aW9ucy82MC8lRTAlQTYlQTglRTAlQTYlQkYlRTAlQTYlOUElRTAlQTclODclRTAlQTYlQjAtJUUwJUE2JTg1JUUwJUE2JUE4JUUwJUE3JTgxJUUwJUE2JTlBJUUwJUE3JThEJUUwJUE2JTlCJUUwJUE3JTg3JUUwJUE2JUE2JUUwJUE3JTg3JUUwJUE2JUIwLSVFMCVBNiU4NSVFMCVBNiVBQSVFMCVBNiVBQSVFMCVBNyU4RCVFMCVBNiVCMCVFMCVBNyU5RiVFMCVBNyU4QiVFMCVBNiU5Ny0lRTAlQTYlQjglRTAlQTYlODIlRTAlQTYlQjYlRTAlQTclOEIlRTAlQTYlQTclRTAlQTYlQTgtJUUwJUE2JTk1JUUwJUE2JUIwLSVFMCVBNiVBOCVFMCVBNiVCRiVFMCVBNiVCMCVFMCVBNiVBQy0lRTAlQTYlQUQlRTAlQTYlQkUlRTAlQTYlQjclRTAlQTYlQkUlRTAlQTclOUYtJUUwJUE2JUFDJUUwJUE3JTgzJUUwJUE2JTk1JUUwJUE3JThEJUUwJUE2JUI3LSVFMCVBNiU4NiVFMCVBNiVBRSVFMCVBNiVCRSVFMCVBNiVBNiVFMCVBNyU4NyVFMCVBNiVCMC0lRTAlQTYlQjglRTAlQTYlQkUlRTAlQTYlQjAlRTAlQTclOEQlRTAlQTYlQTUlRTAlQTYlOTUlRTAlQTYlQTQlRTAlQTYlQkUlRTAlQTYlQjAtJUUwJUE2JTk3JUUwJUE2JUJFJUUwJUE2JUE4LSVFMCVBNiVCNiVFMCVBNyU4QiVFMCVBNiVBOCVFMCVBNiVCRSVFMCVBNyU5Ri0lRTAlQTYlODUlRTAlQTYlQTglRTAlQTclODElRTAlQTYlQUQlRTAlQTclODElRTAlQTYlQTQlRTAlQTYlQkYlRTAlQTYlQjAtJUUwJUE2JTk1JUUwJUE2JUJFJUUwJUE2JUE4LSVFMCVBNiVBNiVFMCVBNyU4RCVFMCVBNiVBQyVFMCVBNiVCRSVFMCVBNiVCMCVFMCVBNiVCRS0iO3M6NToicm91dGUiO3M6MTQ6InF1ZXN0aW9ucy5zaG93Ijt9czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319fQ==', 1766982779),
('HKK0cr1NSg2M0nk52g8bpfrM2Zb49y2R7vOcB6oO', NULL, '66.249.70.64', 'Mozilla/5.0 (compatible; Googlebot/2.1; +http://www.google.com/bot.html)', 'YTozOntzOjY6Il90b2tlbiI7czo0MDoiclpyNGhESTQwVnZvWEdsMzFUcHBnZGlFRTI4TklGdjh0ZXU3c09hOSI7czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6MjU6Imh0dHBzOi8vZXhhbWRhby5jb20vbG9naW4iO3M6NToicm91dGUiO3M6NToibG9naW4iO31zOjY6Il9mbGFzaCI7YToyOntzOjM6Im9sZCI7YTowOnt9czozOiJuZXciO2E6MDp7fX19', 1766991663),
('Hpw4vZ3s7qyEy06HrmbNMXm2SWxXjP2od7GSl4xm', NULL, '207.46.13.150', 'Mozilla/5.0 AppleWebKit/537.36 (KHTML, like Gecko; compatible; bingbot/2.0; +http://www.bing.com/bingbot.htm) Chrome/116.0.1938.76 Safari/537.36', 'YTozOntzOjY6Il90b2tlbiI7czo0MDoiemgxOFJQWHU5cWc3UGJPcXlvV2hDNGdGeTU2T2hjNHRPSVhoRExlaCI7czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6NDgyOiJodHRwczovL2V4YW1kYW8uY29tL3F1ZXN0aW9ucy8xL2hzYy1pY3Qtc3lsaGV0LWJvYXJkLWNxLXF1ZXN0aW9uLTIwMjQtJUUwJUE2JThGJUUwJUE2JTg3JUUwJUE2JTlBJUUwJUE2JThGJUUwJUE2JUI4JUUwJUE2JUI4JUUwJUE2JUJGLSVFMCVBNiU4NiVFMCVBNiU4NyVFMCVBNiVCOCVFMCVBNiVCRiVFMCVBNiU5RiVFMCVBNiVCRi0lRTAlQTYlQjglRTAlQTYlQkYlRTAlQTYlQjIlRTAlQTclODclRTAlQTYlOUYtJUUwJUE2JUFDJUUwJUE3JThCJUUwJUE2JUIwJUUwJUE3JThEJUUwJUE2JUExLSVFMCVBNyVBOCVFMCVBNyVBNiVFMCVBNyVBOCVFMCVBNyVBQS0lRTAlQTYlQjglRTAlQTYlQkUlRTAlQTYlQjIlRTAlQTclODclRTAlQTYlQjAtJUUwJUE2JUI4JUUwJUE3JTgzJUUwJUE2JTlDJUUwJUE2JUE4JUUwJUE2JUI2JUUwJUE3JTgwJUUwJUE2JUIyLSVFMCVBNiVBQSVFMCVBNyU4RCVFMCVBNiVCMCVFMCVBNiVCNiVFMCVBNyU4RCVFMCVBNiVBOCI7czo1OiJyb3V0ZSI7czoxNDoicXVlc3Rpb25zLnNob3ciO31zOjY6Il9mbGFzaCI7YToyOntzOjM6Im9sZCI7YTowOnt9czozOiJuZXciO2E6MDp7fX19', 1766981295),
('iDxz2MPgiEoFb5BS4Jro8hIwgqHM5prr5yMgiLfZ', NULL, '40.77.167.26', 'Mozilla/5.0 AppleWebKit/537.36 (KHTML, like Gecko; compatible; bingbot/2.0; +http://www.bing.com/bingbot.htm) Chrome/116.0.1938.76 Safari/537.36', 'YTozOntzOjY6Il90b2tlbiI7czo0MDoiaGVSdXh5T1h2djREb3lnOGtOVmluVUNPM3REYXlkSDVXMFkySnpMYSI7czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6Mjg6Imh0dHBzOi8vZXhhbWRhby5jb20vZXhhbS9zc2MiO3M6NToicm91dGUiO3M6MTY6Imluc3RpdHV0aW9uLnNob3ciO31zOjY6Il9mbGFzaCI7YToyOntzOjM6Im9sZCI7YTowOnt9czozOiJuZXciO2E6MDp7fX19', 1766985425),
('j723rdKC0RmXUDRJU1tGKEpe5YTy97Kkjoi9QIvQ', NULL, '157.55.39.55', 'Mozilla/5.0 AppleWebKit/537.36 (KHTML, like Gecko; compatible; bingbot/2.0; +http://www.bing.com/bingbot.htm) Chrome/116.0.1938.76 Safari/537.36', 'YTozOntzOjY6Il90b2tlbiI7czo0MDoiY29teEtIa241SWRrZVVUdVlSMGtCY0hwQ0YyR1lzUENObzh3TTdZViI7czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6NDYxOiJodHRwczovL2V4YW1kYW8uY29tL3F1ZXN0aW9ucy83My9oc2MtYmFuZ2xhLTJuZC1wYXBlci1tY3EtcXVlc3Rpb24tZGhha2EtYm9hcmQtMjAyNS0lRTAlQTYlQTIlRTAlQTYlQkUlRTAlQTYlOTUlRTAlQTYlQkUtJUUwJUE2JUFDJUUwJUE3JThCJUUwJUE2JUIwJUUwJUE3JThEJUUwJUE2JUExLSVFMCVBNyVBOCVFMCVBNyVBNiVFMCVBNyVBOCVFMCVBNyVBQi0lRTAlQTYlQjglRTAlQTYlQkUlRTAlQTYlQjIlRTAlQTclODclRTAlQTYlQjAtJUUwJUE2JThGJUUwJUE2JTg3JUUwJUE2JTlBJUUwJUE2JThGJUUwJUE2JUI4JUUwJUE2JUI4JUUwJUE2JUJGLSVFMCVBNiVBQyVFMCVBNiVCRSVFMCVBNiU4MiVFMCVBNiVCMiVFMCVBNiVCRS0lRTAlQTYlQTYlRTAlQTclOEQlRTAlQTYlQUMlRTAlQTYlQkYlRTAlQTYlQTQlRTAlQTclODAlRTAlQTclOUYtJUUwJUE2JUFBJUUwJUE2JUE0JUUwJUE3JThEJUUwJUE2JUIwLSI7czo1OiJyb3V0ZSI7czoxNDoicXVlc3Rpb25zLnNob3ciO31zOjY6Il9mbGFzaCI7YToyOntzOjM6Im9sZCI7YTowOnt9czozOiJuZXciO2E6MDp7fX19', 1766986353),
('ju9pMgIbz6IvteSg1cHD1B81kKu3c9kQ2US4sjP6', NULL, '103.112.207.179', 'Mozilla/5.0 (iPhone; CPU iPhone OS 18_5 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/18.5 Mobile/15E148 Safari/604.1 Edg/143.0.0.0', 'YTozOntzOjY6Il90b2tlbiI7czo0MDoicVFZRGo5bHlZQ2pkZGFhNWlDMklZeXlVMHhaOGU0aWZnWDVpSUxKWSI7czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6Mjg6Imh0dHBzOi8vZXhhbWRhby5jb20vZXhhbS9oc2MiO3M6NToicm91dGUiO3M6OToiZXhhbS5zaG93Ijt9czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319fQ==', 1766991853),
('knEosyjJ4KP2fTpSksxnBV3OwaPuORAuO3EM9iCl', NULL, '66.249.70.70', 'Mozilla/5.0 (Linux; Android 6.0.1; Nexus 5X Build/MMB29P) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/99.0.4844.84 Mobile Safari/537.36 (compatible; Googlebot/2.1; +http://www.google.com/bot.html)', 'YTozOntzOjY6Il90b2tlbiI7czo0MDoiV1d6OGVCUWFnNDI1ZGw3cTVzSGYyNlZPRTFVVm9WMU4zUTBQVDdHUiI7czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6MTk6Imh0dHBzOi8vZXhhbWRhby5jb20iO3M6NToicm91dGUiO3M6NDoiaG9tZSI7fXM6NjoiX2ZsYXNoIjthOjI6e3M6Mzoib2xkIjthOjA6e31zOjM6Im5ldyI7YTowOnt9fX0=', 1766987128),
('Lyfcipn7xZZjSaMINBPOLbXhZJX9hSEdrlk34BRx', NULL, '66.249.70.70', 'Mozilla/5.0 (Linux; Android 6.0.1; Nexus 5X Build/MMB29P) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/99.0.4844.84 Mobile Safari/537.36 (compatible; Googlebot/2.1; +http://www.google.com/bot.html)', 'YTozOntzOjY6Il90b2tlbiI7czo0MDoic1lscm1VSWpxcldlcGV1dm1oMzc1elhLNDNzdkw2MXV0Q1QxaWNDUSI7czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6MjU6Imh0dHBzOi8vZXhhbWRhby5jb20vbG9naW4iO3M6NToicm91dGUiO3M6NToibG9naW4iO31zOjY6Il9mbGFzaCI7YToyOntzOjM6Im9sZCI7YTowOnt9czozOiJuZXciO2E6MDp7fX19', 1766991664),
('MoE441tL20d9WPsrbXmrsyw6zC8IcabvlVoprbOX', NULL, '49.12.205.98', 'SeobilityBot (SEO Tool; https://www.seobility.net/sites/bot.html)', 'YTozOntzOjY6Il90b2tlbiI7czo0MDoiV3VXOGFzVWZudUtucmpmSFd3SUJOYWhlV1hkSUNRYlhBMXlvdjh4SiI7czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6MTk6Imh0dHBzOi8vZXhhbWRhby5jb20iO3M6NToicm91dGUiO3M6NDoiaG9tZSI7fXM6NjoiX2ZsYXNoIjthOjI6e3M6Mzoib2xkIjthOjA6e31zOjM6Im5ldyI7YTowOnt9fX0=', 1766984473),
('mS8av5WlOOkbWrcp7oJ37xbhcJ0cyMZ2X5SX6cIA', NULL, '217.156.65.7', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/85.0.3987.132 Safari/537.36', 'YTozOntzOjY6Il90b2tlbiI7czo0MDoiM0NneURENWR0dkhkcVpITlNsRHNFNmp5THI2NTRCUGJvYUo1RUFxZSI7czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6MTk6Imh0dHBzOi8vZXhhbWRhby5jb20iO3M6NToicm91dGUiO3M6NDoiaG9tZSI7fXM6NjoiX2ZsYXNoIjthOjI6e3M6Mzoib2xkIjthOjA6e31zOjM6Im5ldyI7YTowOnt9fX0=', 1766983857),
('nhfTIQcRstQ2xzCk9wegxqfxCkYBYVuMrqQBH9v6', NULL, '66.42.51.230', '', 'YToyOntzOjY6Il90b2tlbiI7czo0MDoiUmNGb2ZGOHpqcEV0Vll1YzdZMWh5VWJFcHZOZkFrdmV3TXBOMzFSSSI7czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319fQ==', 1766984509),
('norUNsWEgjW8cAArzYZCllPq4Lc2vnSGoxVrbhVM', NULL, '66.249.70.71', 'Mozilla/5.0 (Linux; Android 6.0.1; Nexus 5X Build/MMB29P) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/99.0.4844.84 Mobile Safari/537.36 (compatible; Googlebot/2.1; +http://www.google.com/bot.html)', 'YTozOntzOjY6Il90b2tlbiI7czo0MDoiOXJRS1VVS05pcDRtMTZBV09jOEwzaDRZSWpWaWw2ZWVyNWlPTmNKOSI7czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6MTk6Imh0dHBzOi8vZXhhbWRhby5jb20iO3M6NToicm91dGUiO3M6NDoiaG9tZSI7fXM6NjoiX2ZsYXNoIjthOjI6e3M6Mzoib2xkIjthOjA6e31zOjM6Im5ldyI7YTowOnt9fX0=', 1766987122),
('ovi7ByBrNMRRgX5PtzwoBIDDoe4wajb6agZu00TI', NULL, '223.181.50.14', 'Mozilla/5.0 (compatible; Googlebot/2.1; +http://www.google.com/bot.html)', 'YTozOntzOjY6Il90b2tlbiI7czo0MDoiNklXcmREVER1VjNMdndmS1dydlQyWGtGeHFvbjlaa0o1UWJsc0pOTSI7czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6MTk6Imh0dHBzOi8vZXhhbWRhby5jb20iO3M6NToicm91dGUiO3M6NDoiaG9tZSI7fXM6NjoiX2ZsYXNoIjthOjI6e3M6Mzoib2xkIjthOjA6e31zOjM6Im5ldyI7YTowOnt9fX0=', 1766991275),
('UaLnPvmXhfjdi5ae8fo67QP5oTXfxLLweNODoaSt', NULL, '49.12.205.98', 'SeobilityBot (SEO Tool; https://www.seobility.net/sites/bot.html)', 'YTozOntzOjY6Il90b2tlbiI7czo0MDoiMkhBQjZEaHZUS1g1S2RKOVBvSEYyb0FtZ2VRc1ZZQ0ppaVZkWURFdSI7czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6MTk6Imh0dHBzOi8vZXhhbWRhby5jb20iO3M6NToicm91dGUiO3M6NDoiaG9tZSI7fXM6NjoiX2ZsYXNoIjthOjI6e3M6Mzoib2xkIjthOjA6e31zOjM6Im5ldyI7YTowOnt9fX0=', 1766984473),
('vSuZ87ltcPvaWDaMhqkgztTGDQ9XSDuR2e8biUt7', NULL, '52.167.144.230', 'Mozilla/5.0 AppleWebKit/537.36 (KHTML, like Gecko; compatible; bingbot/2.0; +http://www.bing.com/bingbot.htm) Chrome/116.0.1938.76 Safari/537.36', 'YTozOntzOjY6Il90b2tlbiI7czo0MDoicTJNRG5CNlNUZVVKSzlWMFNmY29DMzZocnVhcXhuY3ZzR1BNOHNhOCI7czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6MzE3OiJodHRwczovL2V4YW1kYW8uY29tL3F1ZXN0aW9ucy8zOC8lRTAlQTYlOTUlRTAlQTYlQkUlRTAlQTYlOUMlRTAlQTclODAtJUUwJUE2JUE4JUUwJUE2JTlDJUUwJUE2JUIwJUUwJUE3JTgxJUUwJUE2JUIyLSVFMCVBNiU4NyVFMCVBNiVCOCVFMCVBNiVCMiVFMCVBNiVCRSVFMCVBNiVBRSVFMCVBNyU4NyVFMCVBNiVCMC0lRTAlQTYlQjglRTAlQTYlQkUlRTAlQTYlQjIlRTAlQTclODctJUUwJUE2JUFBJUUwJUE3JThEJUUwJUE2JUIwJUUwJUE2JTk1JUUwJUE2JUJFJUUwJUE2JUI2JUUwJUE2JUJGJUUwJUE2JUE0LSVFMCVBNiVCOSVFMCVBNiVBRiVFMCVBNiVCQyI7czo1OiJyb3V0ZSI7czoxNDoicXVlc3Rpb25zLnNob3ciO31zOjY6Il9mbGFzaCI7YToyOntzOjM6Im9sZCI7YTowOnt9czozOiJuZXciO2E6MDp7fX19', 1766981098),
('wJVrExZ0C2OfoFrJ9XyXCQpoeBF2O1KnHyhnFGKX', NULL, '58.145.184.198', 'Mozilla/5.0 (Linux; Android 10; K) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Mobile Safari/537.36', 'YTozOntzOjY6Il90b2tlbiI7czo0MDoiZTI2OTQ0SmpjU2RhekIxd3ZoSXN3b0pmZ3FhVnNGVERaMWZvZm10bSI7czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6MTk6Imh0dHBzOi8vZXhhbWRhby5jb20iO3M6NToicm91dGUiO3M6NDoiaG9tZSI7fXM6NjoiX2ZsYXNoIjthOjI6e3M6Mzoib2xkIjthOjA6e31zOjM6Im5ldyI7YTowOnt9fX0=', 1766979275),
('x21mRCRW97SigylqlhfeXhCcrpuigg3t8874nwdJ', NULL, '128.242.229.172', 'Mozilla/5.0 (Linux; Android 5.0; SM-G900P Build/LRX21T) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/52.0.4434.1202 Mobile Safari/537.36', 'YTozOntzOjY6Il90b2tlbiI7czo0MDoiM25zdjUwTDRZU1cyUUV2d2pHd0FHU0ZlZzZBVG02bWg1cnpqZHRjVyI7czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6Mzg6Imh0dHBzOi8vZXhhbWRhby5jb20vc3ViamVjdC9iYW5nbGEtMXN0IjtzOjU6InJvdXRlIjtzOjEyOiJzdWJqZWN0LnNob3ciO31zOjY6Il9mbGFzaCI7YToyOntzOjM6Im9sZCI7YTowOnt9czozOiJuZXciO2E6MDp7fX19', 1766989035),
('xF3d9V6RZJwozT87sjIsGqpEB3k5rmJtnkp5ZXVe', NULL, '66.249.70.71', 'Mozilla/5.0 (compatible; Googlebot/2.1; +http://www.google.com/bot.html)', 'YTozOntzOjY6Il90b2tlbiI7czo0MDoiY2FOWTB0NWNhN0JlYXBwQ2FOcXZ5dkpvejVtdXJqdGZBeUFUWUszaiI7czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6MTk6Imh0dHBzOi8vZXhhbWRhby5jb20iO3M6NToicm91dGUiO3M6NDoiaG9tZSI7fXM6NjoiX2ZsYXNoIjthOjI6e3M6Mzoib2xkIjthOjA6e31zOjM6Im5ldyI7YTowOnt9fX0=', 1766987122),
('Y7umKtD62h1QBZ0ibduV5hcD3qwtO7IZ1EQ2cR4I', NULL, '40.77.167.25', 'Mozilla/5.0 AppleWebKit/537.36 (KHTML, like Gecko; compatible; bingbot/2.0; +http://www.bing.com/bingbot.htm) Chrome/116.0.1938.76 Safari/537.36', 'YTozOntzOjY6Il90b2tlbiI7czo0MDoidVRwN05jWk5GQnQybkprZzcwZ2h0MkpEbDZKdXdsU3c5VU5Dd3FpMSI7czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6MTk6Imh0dHBzOi8vZXhhbWRhby5jb20iO3M6NToicm91dGUiO3M6NDoiaG9tZSI7fXM6NjoiX2ZsYXNoIjthOjI6e3M6Mzoib2xkIjthOjA6e31zOjM6Im5ldyI7YTowOnt9fX0=', 1766985434);

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
  `exam_at` datetime DEFAULT NULL,
  `description` text DEFAULT NULL,
  `slug` varchar(30) DEFAULT NULL,
  `status` tinyint(4) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `subjects`
--

INSERT INTO `subjects` (`id`, `name`, `class`, `institution_id`, `year`, `exam_at`, `description`, `slug`, `status`) VALUES
(1, 'Bangla 1st', '', 1, 2025, NULL, NULL, 'bangla-1st', 1),
(2, 'Bangla 2nd', '', 1, 2025, NULL, NULL, 'bangla-2nd', 1),
(3, 'English 1st', '', 1, 2025, NULL, NULL, 'english-1st', 1),
(4, 'English 2nd', '', 1, 2025, NULL, NULL, 'english-2nd', 1),
(5, 'ICT', '', 1, 2025, NULL, NULL, 'ict', 1),
(6, 'Math 1st', '', 1, 2025, NULL, NULL, 'math-1st', 1),
(7, 'Math 2nd', '', 1, 2025, NULL, NULL, 'math-2nd', 1),
(8, 'Religion 1st', '', 1, 2025, NULL, NULL, 'religion-1st', 1),
(9, 'Religion 2nd', '', 1, 2025, NULL, NULL, 'religion-2nd', 1),
(10, 'Physics 1st', '', 1, 2025, NULL, NULL, 'physics-1st', 1),
(11, 'Physics 2nd', '', 1, 2025, NULL, NULL, 'physics-2nd', 1),
(12, 'Chemistry 1st', '', 1, 2025, NULL, NULL, 'chemistry-1st', 1),
(13, 'Chemistry 2nd', '', 1, 2025, NULL, NULL, 'chemistry-2nd', 1),
(14, 'Biology 1st', '', 1, 2025, NULL, NULL, 'biology-1st', 1),
(15, 'Biology 2nd', '', 1, 2025, NULL, NULL, 'biology-2nd', 1),
(16, 'Higher Math 1st', '', 1, 2025, NULL, NULL, 'higher-math-1st', 1),
(17, 'Higher Math 2nd', '', 1, 2025, NULL, NULL, 'higher-math-2nd', 1),
(18, 'Accounting 1st', '', 1, 2025, NULL, NULL, 'accounting-1st', 0),
(19, 'Accounting 2nd', '', 1, 2025, NULL, NULL, 'accounting-2nd', 0),
(20, 'Finance 1st', '', 1, 2025, NULL, NULL, 'finance-1st', 0),
(21, 'Finance 2nd', '', 1, 2025, NULL, NULL, 'finance-2nd', 0),
(22, 'Business Entrepreneurship 1st', '', 1, 2025, NULL, NULL, NULL, 0),
(23, 'Business Entrepreneurship 2nd', '', 1, 2025, NULL, NULL, NULL, 0),
(24, 'Agricultural 1st', '', 1, 2025, NULL, NULL, 'agriculture-1st', 1),
(25, 'Agricultural 2nd', '', 1, 2025, NULL, NULL, 'agriculture-2nd', 1),
(26, 'General Science 1st', '', 1, 2025, NULL, NULL, 'general-science-1st', 1),
(27, 'General Science 2nd', '', 1, 2025, NULL, NULL, 'general-science-2nd', 1),
(28, 'Bangladesh and Global Studies 1st', '', 1, 2025, NULL, NULL, NULL, 0),
(29, 'Bangladesh and Global Studies 2nd', '', 1, 2025, NULL, NULL, NULL, 0),
(30, 'Statistics 1st', '', 1, 2025, NULL, NULL, 'statistics-1st', 0),
(31, 'Statistics 2nd', '', 1, 2025, NULL, NULL, 'statistics-2nd', 0),
(32, 'Economics 1st', '', 1, 2025, NULL, NULL, 'economics-1st', 0),
(33, 'Economics 2nd', '', 1, 2025, NULL, NULL, 'economics-2nd', 0),
(34, 'Psychology 1st', '', 1, 2025, NULL, NULL, 'psychology-1st', 0),
(35, 'Psychology 2nd', '', 1, 2025, NULL, NULL, 'psychology-2nd', 0),
(36, 'Logic 1st', '', 1, 2025, NULL, NULL, 'logic-1st', 0),
(37, 'Logic 2nd', '', 1, 2025, NULL, NULL, 'logic-2nd', 0),
(38, 'Sociology 1st', '', 1, 2025, NULL, NULL, 'sociology-1st', 0),
(39, 'Sociology 2nd', '', 1, 2025, NULL, NULL, 'sociology-2nd', 0),
(40, 'Social Work 1st', '', 1, 2025, NULL, NULL, 'social-work-1st', 0),
(41, 'Social Work 2nd', '', 1, 2025, NULL, NULL, 'social-work-2nd', 0),
(42, 'Geography 1st', '', 1, 2025, NULL, NULL, 'geography-1st', 0),
(43, 'Geography 2nd', '', 1, 2025, NULL, NULL, 'geography-2nd', 0),
(44, 'Home Science 1st', '', 1, 2025, NULL, NULL, 'home-science-1st', 0),
(45, 'Home Science 2nd', '', 1, 2025, NULL, NULL, 'home-science-2nd', 0),
(46, 'Bangla 1st', '', 2, 2025, NULL, NULL, 'bangla-1st', 1),
(47, 'Bangla 2nd', '', 2, 2025, NULL, NULL, 'bangla-2nd', 1),
(48, 'English 1st', '', 2, 2025, NULL, NULL, 'english-1st', 1),
(49, 'English 2nd', '', 2, 2025, NULL, NULL, 'english-2nd', 1),
(50, 'ICT', '', 2, 2025, NULL, NULL, 'ict', 1),
(51, 'Math 1st', '', 2, 2025, NULL, NULL, 'math-1st', 1),
(52, 'Math 2nd', '', 2, 2025, NULL, NULL, 'math-2nd', 1),
(53, 'Physics 1st', '', 2, 2025, NULL, NULL, 'physics-1st', 1),
(54, 'Physics 2nd', '', 2, 2025, NULL, NULL, 'physics-2nd', 1),
(55, 'Chemistry 1st', '', 2, 2025, NULL, NULL, 'chemistry-1st', 1),
(56, 'Chemistry 2nd', '', 2, 2025, NULL, NULL, 'chemistry-2nd', 1),
(57, 'Biology 1st', '', 2, 2025, NULL, NULL, 'biology-1st', 1),
(58, 'Biology 2nd', '', 2, 2025, NULL, NULL, 'biology-2nd', 1),
(59, 'Accounting 1st', '', 2, 2025, NULL, NULL, 'accounting-1st', 0),
(60, 'Accounting 2nd', '', 2, 2025, NULL, NULL, 'accounting-2nd', 0),
(61, 'Finance 1st', '', 2, 2025, NULL, NULL, 'finance-1st', 0),
(62, 'Finance 2nd', '', 2, 2025, NULL, NULL, 'finance-2nd', 0),
(63, 'Agricultural 1st', '', 2, 2025, NULL, NULL, 'agriculture-1st', 1),
(64, 'Agricultural 1st', '', 2, 2025, NULL, NULL, 'agriculture-2nd', 1),
(65, 'Statistics 1st', '', 2, 2025, NULL, NULL, 'statistics-1st', 0),
(66, 'Statistics 2nd', '', 2, 2025, NULL, NULL, 'statistics-2nd', 0),
(67, 'Economics 1st', '', 2, 2025, NULL, NULL, 'economics-1st', 0),
(68, 'Economics 2nd', '', 2, 2025, NULL, NULL, 'economics-2nd', 0),
(69, 'Psychology 1st', '', 2, 2025, NULL, NULL, 'psychology-1st', 0),
(70, 'Psychology 2nd', '', 2, 2025, NULL, NULL, 'psychology-2nd', 0),
(71, 'Logic 1st', '', 2, 2025, NULL, NULL, 'logic-1st', 0),
(72, 'Logic 2nd', '', 2, 2025, NULL, NULL, 'logic-2nd', 0),
(73, 'Social Science 1st', '', 2, 2025, NULL, NULL, 'social-science-1st', 0),
(74, 'Social Science 2nd', '', 2, 2025, NULL, NULL, 'social-science-2nd', 0),
(75, 'Social Work 1st', '', 2, 2025, NULL, NULL, 'social-work-1st', 0),
(76, 'Social Work 2nd', '', 2, 2025, NULL, NULL, 'social-work-2nd', 0),
(77, 'Geography 1st', '', 2, 2025, NULL, NULL, 'geography-1st', 0),
(78, 'Geography 2nd', '', 2, 2025, NULL, NULL, 'geography-2nd', 0);

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
(1, 'Admin', '01924974960', 'rejauldu@gmail.com', NULL, '$2y$12$5XA6xNaU1zYFvkfg4ihszuvcx2V7G7u6AFOcFYkthgdawu12w8Bz6', 'Ex7Fj9EsEc90CIiPDJfuqrIQ7HbmGZ1hhxvKT9DnICoNl6oe03JcUi6wO6RQ', 1, 'admin', NULL, NULL),
(2, 'Rejaul', '01817338234', NULL, NULL, '$2y$12$z049Yj3ADkBS.i6wy3zjMuEgJ8UyWxOqtYAwGWPZlSjfOU/ewzpEi', NULL, NULL, 'user', '2025-11-30 08:09:49', '2025-11-30 08:09:49'),
(3, 'Shareful Islam', '01912662075', NULL, NULL, '$2y$12$A/F.OYtQdHS513xoiq31dORiZeZjTQ6jAS14t1bLvYiOF7iFkhD/m', NULL, NULL, 'admin', '2025-12-24 05:11:46', '2025-12-24 05:11:46');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `answers`
--
ALTER TABLE `answers`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `boards`
--
ALTER TABLE `boards`
  ADD PRIMARY KEY (`id`);

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
-- Indexes for table `comments`
--
ALTER TABLE `comments`
  ADD PRIMARY KEY (`id`);

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
-- AUTO_INCREMENT for table `answers`
--
ALTER TABLE `answers`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `boards`
--
ALTER TABLE `boards`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT for table `comments`
--
ALTER TABLE `comments`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `failed_jobs`
--
ALTER TABLE `failed_jobs`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `faqs`
--
ALTER TABLE `faqs`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

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
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=111;

--
-- AUTO_INCREMENT for table `migrations`
--
ALTER TABLE `migrations`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `posts`
--
ALTER TABLE `posts`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=75;

--
-- AUTO_INCREMENT for table `subjects`
--
ALTER TABLE `subjects`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=79;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
