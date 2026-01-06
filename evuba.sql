-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jan 06, 2026 at 12:10 PM
-- Server version: 10.4.32-MariaDB
-- PHP Version: 8.0.30

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `evuba`
--

-- --------------------------------------------------------

--
-- Table structure for table `announcements`
--

CREATE TABLE `announcements` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `title` varchar(255) NOT NULL,
  `message` text DEFAULT NULL,
  `target_role` varchar(255) DEFAULT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `user_id` bigint(20) UNSIGNED DEFAULT NULL,
  `status` enum('draft','published','archived') NOT NULL DEFAULT 'draft',
  `priority` enum('low','medium','high') NOT NULL DEFAULT 'medium',
  `published_at` datetime DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `announcements`
--

INSERT INTO `announcements` (`id`, `title`, `message`, `target_role`, `is_active`, `user_id`, `status`, `priority`, `published_at`, `created_at`, `updated_at`) VALUES
(1, 'Global Announcement 1', 'Qui neque enim nobis unde. Eum et sit magni quisquam aut ut ducimus. Magnam mollitia explicabo quibusdam ex est officiis. Illum possimus et sint praesentium.', 'customer', 1, NULL, 'draft', 'medium', NULL, '2025-12-23 17:26:00', '2025-12-23 17:26:00'),
(2, 'Global Announcement 2', 'Animi molestias qui qui ut ducimus repudiandae quibusdam. At ratione eum ut molestias earum cum aperiam. Expedita in vel perspiciatis quae non.', 'customer', 1, NULL, 'draft', 'medium', NULL, '2025-12-23 17:26:00', '2025-12-23 17:26:00'),
(3, 'Global Announcement 3', 'Reprehenderit recusandae ipsum placeat nulla deleniti. Eveniet beatae minus dolore aspernatur dolores sit. Quis modi sit ut. Quis harum beatae consequatur.', 'customer', 1, NULL, 'draft', 'medium', NULL, '2025-12-23 17:26:00', '2025-12-23 17:26:00'),
(4, 'Global Announcement 4', 'Sint nihil totam iste praesentium. Necessitatibus nemo ducimus et quos sed id aliquam. Est reprehenderit accusantium quod vero qui sapiente ea. Nulla harum aspernatur expedita.', 'customer', 1, NULL, 'draft', 'medium', NULL, '2025-12-23 17:26:00', '2025-12-23 17:26:00'),
(5, 'Global Announcement 5', 'Facere sit eum deleniti nesciunt qui. Dolores qui non voluptas ipsam. Eum illo voluptas atque quas eum aperiam.', 'customer', 1, NULL, 'draft', 'medium', NULL, '2025-12-23 17:26:00', '2025-12-23 17:26:00'),
(6, 'Global Announcement 1', 'Necessitatibus voluptatibus quis corrupti iste illo magni in. Omnis facere omnis minus vitae aut consequatur. Aut ex quos fugit iusto dicta consequatur.', 'customer', 1, NULL, 'draft', 'medium', NULL, '2025-12-23 17:27:28', '2025-12-23 17:27:28'),
(7, 'Global Announcement 2', 'Omnis ea occaecati illum officiis dolorum recusandae. Veniam possimus fuga repellat molestiae et necessitatibus tempore. Placeat hic perspiciatis fugit qui culpa et ullam. Ea soluta debitis ut molestiae.', 'customer', 1, NULL, 'draft', 'medium', NULL, '2025-12-23 17:27:28', '2025-12-23 17:27:28'),
(8, 'Global Announcement 3', 'Optio error neque corrupti dolore et deserunt. Dolorum et commodi nihil nemo quo. Consequatur illo consequatur libero eum recusandae fuga. Accusamus inventore sit dolor et.', 'customer', 1, NULL, 'draft', 'medium', NULL, '2025-12-23 17:27:28', '2025-12-23 17:27:28'),
(9, 'Global Announcement 4', 'Est quod harum fugit voluptatem voluptatibus. Corporis dolorem qui sed et modi temporibus. Debitis libero dignissimos possimus deleniti beatae exercitationem et. Molestias est atque est blanditiis. Eos voluptatem sed laudantium eum.', 'customer', 1, NULL, 'draft', 'medium', NULL, '2025-12-23 17:27:28', '2025-12-23 17:27:28'),
(10, 'Global Announcement 5', 'Quis qui laborum quod ducimus. Aspernatur quam saepe non est doloribus iusto nostrum exercitationem. Et non dolorem repellat ut quia eos autem dolore.', 'customer', 1, NULL, 'draft', 'medium', NULL, '2025-12-23 17:27:28', '2025-12-23 17:27:28');

-- --------------------------------------------------------

--
-- Table structure for table `appointments`
--

CREATE TABLE `appointments` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `user_id` bigint(20) UNSIGNED DEFAULT NULL,
  `employee_id` bigint(20) UNSIGNED DEFAULT NULL,
  `manager_id` bigint(20) UNSIGNED DEFAULT NULL,
  `title` varchar(255) NOT NULL,
  `description` text DEFAULT NULL,
  `status` enum('pending','confirmed','completed','cancelled') NOT NULL DEFAULT 'pending',
  `priority` enum('low','medium','high','urgent') NOT NULL DEFAULT 'medium',
  `source_type` varchar(255) DEFAULT NULL,
  `source_id` bigint(20) UNSIGNED DEFAULT NULL,
  `auto_assigned` tinyint(1) NOT NULL DEFAULT 0,
  `assigned_at` timestamp NULL DEFAULT NULL,
  `assignment_notes` text DEFAULT NULL,
  `scheduled_at` datetime NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `appointments`
--

INSERT INTO `appointments` (`id`, `user_id`, `employee_id`, `manager_id`, `title`, `description`, `status`, `priority`, `source_type`, `source_id`, `auto_assigned`, `assigned_at`, `assignment_notes`, `scheduled_at`, `created_at`, `updated_at`) VALUES
(1, 29, 3, NULL, 'Support Ticket: Issue with Order #1000', 'I haven\'t received my order yet. Please help.', 'pending', 'urgent', 'ticket', 1, 1, '2025-12-22 18:29:59', 'Auto-assigned based on support ticket #1', '2025-12-22 12:29:59', '2025-12-22 18:29:59', '2025-12-22 18:29:59'),
(2, 31, 26, NULL, 'Support Ticket: Issue with Order #1002', 'I haven\'t received my order yet. Please help.', 'pending', 'medium', 'ticket', 2, 1, '2025-12-22 18:30:10', 'Auto-assigned based on support ticket #2', '2025-12-22 12:30:10', '2025-12-22 18:30:10', '2025-12-22 18:30:10'),
(3, 33, 27, NULL, 'Support Ticket: Issue with Order #1004', 'I haven\'t received my order yet. Please help.', 'pending', 'medium', 'ticket', 3, 1, '2025-12-22 18:30:13', 'Auto-assigned based on support ticket #3', '2025-12-22 12:30:13', '2025-12-22 18:30:13', '2025-12-22 18:30:13'),
(4, 29, 28, NULL, 'Support Ticket: Issue with Order #1000', 'I haven\'t received my order yet. Please help.', 'pending', 'urgent', 'ticket', 4, 1, '2025-12-22 18:30:47', 'Auto-assigned based on support ticket #4', '2025-12-22 12:30:47', '2025-12-22 18:30:47', '2025-12-22 18:30:47'),
(5, 31, 3, NULL, 'Support Ticket: Issue with Order #1002', 'I haven\'t received my order yet. Please help.', 'pending', 'medium', 'ticket', 5, 1, '2025-12-22 18:30:54', 'Auto-assigned based on support ticket #5', '2025-12-22 12:30:54', '2025-12-22 18:30:54', '2025-12-22 18:30:54'),
(6, 33, 26, NULL, 'Support Ticket: Issue with Order #1004', 'I haven\'t received my order yet. Please help.', 'pending', 'medium', 'ticket', 6, 1, '2025-12-22 18:30:58', 'Auto-assigned based on support ticket #6', '2025-12-22 12:30:58', '2025-12-22 18:30:58', '2025-12-22 18:30:58'),
(7, 29, 27, NULL, 'Support Ticket: Issue with Order #1000', 'I haven\'t received my order yet. Please help.', 'pending', 'urgent', 'ticket', 7, 1, '2025-12-22 18:31:38', 'Auto-assigned based on support ticket #7', '2025-12-22 12:31:38', '2025-12-22 18:31:38', '2025-12-22 18:31:38'),
(8, 31, 28, NULL, 'Support Ticket: Issue with Order #1002', 'I haven\'t received my order yet. Please help.', 'pending', 'medium', 'ticket', 8, 1, '2025-12-22 18:31:45', 'Auto-assigned based on support ticket #8', '2025-12-22 12:31:45', '2025-12-22 18:31:45', '2025-12-22 18:31:45'),
(9, 33, 3, NULL, 'Support Ticket: Issue with Order #1004', 'I haven\'t received my order yet. Please help.', 'pending', 'medium', 'ticket', 9, 1, '2025-12-22 18:31:49', 'Auto-assigned based on support ticket #9', '2025-12-22 12:31:49', '2025-12-22 18:31:49', '2025-12-22 18:31:49'),
(10, 30, 26, NULL, 'Service Booking: Networking', 'Customer booking for Networking', 'confirmed', 'high', 'booking', 22, 1, '2025-12-22 18:31:53', 'Auto-assigned from booking #22', '2025-12-24 10:31:53', '2025-12-22 18:31:53', '2025-12-22 18:31:53'),
(11, 32, 27, NULL, 'Service Booking: Networking', 'Customer booking for Networking', 'confirmed', 'medium', 'booking', 23, 1, '2025-12-22 18:31:56', 'Auto-assigned from booking #23', '2025-12-26 10:31:56', '2025-12-22 18:31:56', '2025-12-22 18:31:56'),
(12, 29, 28, NULL, 'Support Ticket: Issue with Order #1000', 'I haven\'t received my order yet. Please help.', 'pending', 'urgent', 'ticket', 10, 1, '2025-12-22 18:33:00', 'Auto-assigned based on support ticket #10', '2025-12-22 12:33:00', '2025-12-22 18:33:00', '2025-12-22 18:33:00'),
(13, 31, 3, NULL, 'Support Ticket: Issue with Order #1002', 'I haven\'t received my order yet. Please help.', 'pending', 'medium', 'ticket', 11, 1, '2025-12-22 18:33:08', 'Auto-assigned based on support ticket #11', '2025-12-22 12:33:08', '2025-12-22 18:33:08', '2025-12-22 18:33:08'),
(14, 33, 26, NULL, 'Support Ticket: Issue with Order #1004', 'I haven\'t received my order yet. Please help.', 'pending', 'medium', 'ticket', 12, 1, '2025-12-22 18:33:12', 'Auto-assigned based on support ticket #12', '2025-12-22 12:33:12', '2025-12-22 18:33:12', '2025-12-22 18:33:12'),
(15, 30, 27, NULL, 'Service Booking: Networking', 'Customer booking for Networking', 'confirmed', 'high', 'booking', 24, 1, '2025-12-22 18:33:15', 'Auto-assigned from booking #24', '2025-12-24 10:33:15', '2025-12-22 18:33:15', '2025-12-22 18:33:15'),
(16, 32, 28, NULL, 'Service Booking: Networking', 'Customer booking for Networking', 'confirmed', 'medium', 'booking', 25, 1, '2025-12-22 18:33:19', 'Auto-assigned from booking #25', '2025-12-26 10:33:19', '2025-12-22 18:33:19', '2025-12-22 18:33:19'),
(17, 29, 3, NULL, 'Order Processing: Order #1', 'Process and fulfill customer order', 'confirmed', 'high', 'order', 1, 1, '2025-12-22 18:33:23', 'Auto-assigned from order #1', '2025-12-22 11:33:23', '2025-12-22 18:33:23', '2025-12-22 18:33:23'),
(18, 29, 26, NULL, 'Support Ticket: Issue with Order #1000', 'I haven\'t received my order yet. Please help.', 'pending', 'urgent', 'ticket', 13, 1, '2025-12-22 18:36:47', 'Auto-assigned based on support ticket #13', '2025-12-22 12:36:47', '2025-12-22 18:36:47', '2025-12-22 18:36:47'),
(19, 31, 27, NULL, 'Support Ticket: Issue with Order #1002', 'I haven\'t received my order yet. Please help.', 'pending', 'medium', 'ticket', 14, 1, '2025-12-22 18:36:54', 'Auto-assigned based on support ticket #14', '2025-12-22 12:36:54', '2025-12-22 18:36:54', '2025-12-22 18:36:54'),
(20, 33, 28, NULL, 'Support Ticket: Issue with Order #1004', 'I haven\'t received my order yet. Please help.', 'pending', 'medium', 'ticket', 15, 1, '2025-12-22 18:36:57', 'Auto-assigned based on support ticket #15', '2025-12-22 12:36:57', '2025-12-22 18:36:57', '2025-12-22 18:36:57'),
(21, 30, 3, NULL, 'Service Booking: Networking', 'Customer booking for Networking', 'confirmed', 'high', 'booking', 26, 1, '2025-12-22 18:37:01', 'Auto-assigned from booking #26', '2025-12-24 10:37:01', '2025-12-22 18:37:01', '2025-12-22 18:37:01'),
(23, 29, 27, NULL, 'Order Processing: Order #2', 'Process and fulfill customer order', 'confirmed', 'high', 'order', 2, 1, '2025-12-22 18:37:09', 'Auto-assigned from order #2', '2025-12-22 11:37:09', '2025-12-22 18:37:09', '2025-12-22 18:37:09'),
(24, 29, 28, NULL, 'Support Ticket: Issue with Order #1000', 'I haven\'t received my order yet. Please help.', 'pending', 'urgent', 'ticket', 16, 1, '2025-12-22 18:38:01', 'Auto-assigned based on support ticket #16', '2025-12-22 12:38:01', '2025-12-22 18:38:01', '2025-12-22 18:38:01'),
(25, 31, 3, NULL, 'Support Ticket: Issue with Order #1002', 'I haven\'t received my order yet. Please help.', 'pending', 'medium', 'ticket', 17, 1, '2025-12-22 18:38:08', 'Auto-assigned based on support ticket #17', '2025-12-22 12:38:08', '2025-12-22 18:38:08', '2025-12-22 18:38:08'),
(26, 33, 26, NULL, 'Support Ticket: Issue with Order #1004', 'I haven\'t received my order yet. Please help.', 'pending', 'medium', 'ticket', 18, 1, '2025-12-22 18:38:11', 'Auto-assigned based on support ticket #18', '2025-12-22 12:38:11', '2025-12-22 18:38:11', '2025-12-22 18:38:11'),
(27, 30, 27, NULL, 'Service Booking: Networking', 'Customer booking for Networking', 'confirmed', 'high', 'booking', 28, 1, '2025-12-22 18:38:15', 'Auto-assigned from booking #28', '2025-12-24 10:38:15', '2025-12-22 18:38:15', '2025-12-22 18:38:15'),
(28, 32, 3, NULL, 'Service Booking: Networking', 'Customer booking for Networking', 'confirmed', 'medium', 'booking', 29, 1, '2025-12-22 18:38:20', 'Auto-assigned from booking #29', '2025-12-26 10:38:20', '2025-12-22 18:38:20', '2025-12-23 13:38:51'),
(29, 29, 3, NULL, 'Order Processing: Order #3', 'Process and fulfill customer order', 'confirmed', 'high', 'order', 3, 1, '2025-12-22 18:38:23', 'Auto-assigned from order #3', '2025-12-22 11:38:23', '2025-12-22 18:38:23', '2025-12-22 18:38:23'),
(30, 29, 26, NULL, 'Support Ticket: Issue with Order #1000', 'I haven\'t received my order yet. Please help.', 'pending', 'urgent', 'ticket', 19, 1, '2025-12-22 18:39:29', 'Auto-assigned based on support ticket #19', '2025-12-22 12:39:29', '2025-12-22 18:39:29', '2025-12-22 18:39:29'),
(31, 31, 27, NULL, 'Support Ticket: Issue with Order #1002', 'I haven\'t received my order yet. Please help.', 'pending', 'medium', 'ticket', 20, 1, '2025-12-22 18:39:36', 'Auto-assigned based on support ticket #20', '2025-12-22 12:39:36', '2025-12-22 18:39:36', '2025-12-22 18:39:36'),
(32, 33, 28, NULL, 'Support Ticket: Issue with Order #1004', 'I haven\'t received my order yet. Please help.', 'pending', 'medium', 'ticket', 21, 1, '2025-12-22 18:39:39', 'Auto-assigned based on support ticket #21', '2025-12-22 12:39:39', '2025-12-22 18:39:39', '2025-12-22 18:39:39'),
(33, 30, 3, NULL, 'Service Booking: Networking', 'Customer booking for Networking', 'confirmed', 'high', 'booking', 30, 1, '2025-12-22 18:39:43', 'Auto-assigned from booking #30', '2025-12-24 10:39:43', '2025-12-22 18:39:43', '2025-12-22 18:39:43'),
(34, 32, 26, NULL, 'Service Booking: Networking', 'Customer booking for Networking', 'confirmed', 'medium', 'booking', 31, 1, '2025-12-22 18:39:46', 'Auto-assigned from booking #31', '2025-12-26 10:39:46', '2025-12-22 18:39:46', '2025-12-22 18:39:46'),
(35, 29, 27, NULL, 'Order Processing: Order #4', 'Process and fulfill customer order', 'confirmed', 'high', 'order', 4, 1, '2025-12-22 18:39:50', 'Auto-assigned from order #4', '2025-12-22 11:39:50', '2025-12-22 18:39:50', '2025-12-22 18:39:50'),
(36, NULL, 28, NULL, 'Customer Inquiry: Do you offer bulk discounts fo...', 'Do you offer bulk discounts for corporate orders?', 'pending', 'medium', 'message', 7, 1, '2025-12-22 18:39:53', 'Auto-assigned from customer message #7', '2025-12-22 14:39:53', '2025-12-22 18:39:53', '2025-12-22 18:39:53'),
(37, 4, 1, 2, 'Quarterly Performance Review', 'Reviewing the last quarters KPIs and setting new targets.', 'completed', 'medium', NULL, NULL, 0, NULL, NULL, '2025-12-24 15:00:36', '2025-12-23 15:36:36', '2025-12-23 15:41:57'),
(38, 4, 1, 2, 'Customer Onboarding Session', 'Walking through the system features with our new premium client.', 'completed', 'medium', NULL, NULL, 0, NULL, NULL, '2025-12-25 12:00:36', '2025-12-23 15:36:36', '2025-12-23 15:36:36'),
(39, 4, 1, 2, 'Technical Support Consultation', 'Resolving complex integration issues for the enterprise dashboard.', 'completed', 'medium', NULL, NULL, 0, NULL, NULL, '2025-12-26 17:00:36', '2025-12-23 15:36:36', '2025-12-23 16:10:04'),
(40, 4, 1, 2, 'Product Demo: Smart Hub v2', 'Showcasing the latest automation features to the regional team.', 'completed', 'medium', NULL, NULL, 0, NULL, NULL, '2025-12-27 12:00:36', '2025-12-23 15:36:36', '2025-12-23 16:23:18'),
(41, 4, 1, 2, 'Strategic Planning Meeting', 'Aligning the workflow for the upcoming system maintenance.', 'completed', 'medium', NULL, NULL, 0, NULL, NULL, '2025-12-28 17:00:36', '2025-12-23 15:36:36', '2025-12-23 15:36:36'),
(42, 34, NULL, NULL, 'Review', 'Eos fugiat est officia reprehenderit.', 'cancelled', 'medium', NULL, NULL, 0, NULL, NULL, '2025-11-24 19:31:57', '2025-12-23 17:22:41', '2025-12-23 17:22:41'),
(43, 34, NULL, NULL, 'Audit', 'Non illo quo corrupti quia eos accusantium et.', 'completed', 'medium', NULL, NULL, 0, NULL, NULL, '2025-11-27 02:57:20', '2025-12-23 17:22:41', '2025-12-23 17:22:41'),
(44, 34, NULL, NULL, 'System Setup', 'Provident dolor non quia.', 'cancelled', 'medium', NULL, NULL, 0, NULL, NULL, '2025-12-18 11:34:35', '2025-12-23 17:22:41', '2025-12-23 17:22:41'),
(45, 34, NULL, NULL, 'Maintenance', 'Iure accusantium veniam voluptate iste rerum sapiente eligendi.', 'cancelled', 'medium', NULL, NULL, 0, NULL, NULL, '2025-11-29 13:51:59', '2025-12-23 17:22:41', '2025-12-23 17:22:41'),
(46, NULL, 26, NULL, 'System Setup', 'Nobis aut non natus dolorum exercitationem debitis.', 'completed', 'medium', NULL, NULL, 0, NULL, NULL, '2025-12-30 11:29:00', '2025-12-23 17:22:41', '2025-12-24 00:25:44'),
(47, 34, NULL, NULL, 'Consultation', 'Maxime ducimus et at nobis quo eaque sint.', 'completed', 'medium', NULL, NULL, 0, NULL, NULL, '2025-12-09 18:37:44', '2025-12-23 17:26:00', '2025-12-23 17:26:00'),
(48, 34, NULL, NULL, 'Review', 'Consequatur fuga alias ipsum repellendus atque.', 'cancelled', 'medium', NULL, NULL, 0, NULL, NULL, '2026-01-02 23:12:37', '2025-12-23 17:26:00', '2025-12-23 17:26:00'),
(50, 34, NULL, NULL, 'Maintenance', 'Laboriosam voluptatem voluptatem non sed deserunt.', 'confirmed', 'medium', NULL, NULL, 0, NULL, NULL, '2025-12-06 00:14:18', '2025-12-23 17:26:00', '2025-12-23 17:26:00'),
(51, 34, NULL, NULL, 'Review', 'Nisi temporibus aut incidunt omnis autem.', 'completed', 'medium', NULL, NULL, 0, NULL, NULL, '2026-01-15 19:02:04', '2025-12-23 17:26:00', '2025-12-23 17:26:00'),
(52, 34, NULL, NULL, 'Audit', 'Autem quos mollitia aliquam quisquam enim.', 'pending', 'medium', NULL, NULL, 0, NULL, NULL, '2025-12-22 23:05:31', '2025-12-23 17:27:28', '2025-12-23 17:27:28'),
(53, 34, NULL, NULL, 'Consultation', 'Eum tempore id rerum error et molestiae voluptatem.', 'completed', 'medium', NULL, NULL, 0, NULL, NULL, '2026-01-23 06:08:43', '2025-12-23 17:27:28', '2025-12-23 17:27:28'),
(55, 34, NULL, NULL, 'Consultation', 'Similique repudiandae dolores nostrum cumque et.', 'pending', 'medium', NULL, NULL, 0, NULL, NULL, '2025-12-17 18:03:17', '2025-12-23 17:27:28', '2025-12-23 17:27:28'),
(56, 34, NULL, NULL, 'Audit', 'Doloremque temporibus nobis eius quidem minima adipisci consequuntur.', 'pending', 'medium', NULL, NULL, 0, NULL, NULL, '2025-12-07 11:01:04', '2025-12-23 17:27:28', '2025-12-23 17:27:28'),
(57, 1, 28, NULL, 'Cooking', 'ngmnbb', 'pending', 'medium', NULL, NULL, 0, NULL, NULL, '2025-12-25 01:48:00', '2025-12-23 17:48:18', '2025-12-23 17:48:51');

-- --------------------------------------------------------

--
-- Table structure for table `bookings`
--

CREATE TABLE `bookings` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `user_id` bigint(20) UNSIGNED NOT NULL,
  `employee_id` bigint(20) UNSIGNED DEFAULT NULL,
  `service_id` bigint(20) UNSIGNED DEFAULT NULL,
  `title` varchar(255) NOT NULL,
  `description` text DEFAULT NULL,
  `booking_date` datetime NOT NULL,
  `status` varchar(255) NOT NULL DEFAULT 'pending',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `bookings`
--

INSERT INTO `bookings` (`id`, `user_id`, `employee_id`, `service_id`, `title`, `description`, `booking_date`, `status`, `created_at`, `updated_at`) VALUES
(1, 6, 5, 2, 'Nam ab inventore sit.', 'Voluptatum nemo qui aspernatur cupiditate. Aperiam sit unde voluptas eaque. Fugit nemo dolores nesciunt delectus adipisci.', '2025-12-25 00:00:00', 'rejected', '2025-12-22 15:42:43', '2025-12-23 13:08:05'),
(2, 7, 2, 2, 'Voluptas doloremque blanditiis.', 'Sapiente laudantium maiores enim aut ut repellendus debitis ratione. Dolores delectus sit sint vel. Nesciunt illum magnam eos aut voluptatem. Ut ipsam ut laudantium. Porro iusto consequatur et voluptatem omnis.', '2026-01-12 20:26:37', 'cancelled', '2025-12-22 15:42:43', '2025-12-23 13:07:45'),
(3, 8, 2, 2, 'Tenetur eum minima sapiente.', 'Distinctio quis et aut eos et aperiam. Sed pariatur doloremque quia saepe necessitatibus. Magnam asperiores reprehenderit tenetur optio corrupti.', '2025-12-13 03:34:12', 'rejected', '2025-12-22 15:42:43', '2025-12-22 15:42:43'),
(4, 9, 5, 2, 'Nam assumenda delectus.', 'Cumque quo delectus enim et autem est deserunt. Ea corrupti necessitatibus ea tempore voluptas excepturi. Suscipit officiis laborum sed doloribus. Qui facilis vero suscipit eaque itaque voluptatem eos. Nihil possimus excepturi tempora hic cum amet.', '2026-01-06 21:11:04', 'pending', '2025-12-22 15:42:43', '2025-12-22 15:42:43'),
(5, 10, 5, 2, 'Laudantium et ducimus consequuntur.', 'Ut voluptatem eveniet itaque dolores nesciunt. Iure inventore similique mollitia eos laborum omnis sint minima. Doloribus vel similique ut quis. Totam quia dolorem id. Sunt ullam vitae quae eveniet fuga.', '2025-12-12 22:07:37', 'pending', '2025-12-22 15:42:43', '2025-12-22 15:42:43'),
(6, 11, 2, 2, 'Sequi dolor est similique.', 'Facere ipsam sunt impedit explicabo dignissimos eum. Labore sit odit recusandae laborum voluptatem dolore enim ea. Consequuntur vel consectetur aut dolor aliquid dicta ea.', '2025-12-20 05:33:08', 'cancelled', '2025-12-22 15:42:43', '2025-12-23 13:06:38'),
(7, 12, 2, 2, 'Quia ut nulla.', 'Magnam laudantium voluptate et quod. Eum et saepe architecto est voluptatem. Vel id deleniti ut sapiente. Occaecati dolor quasi dicta ratione sed aperiam molestiae.', '2025-12-04 13:20:00', 'cancelled', '2025-12-22 15:42:43', '2025-12-22 15:42:43'),
(8, 13, 2, 2, 'Impedit sint rerum.', 'Aut unde labore sit sint libero quis. Voluptatem quo illo ut. Soluta consectetur assumenda placeat eveniet ut repudiandae quis. Dolorem consequatur laborum deserunt consequuntur consequatur.', '2025-12-21 04:48:00', 'cancelled', '2025-12-22 15:42:43', '2025-12-22 15:42:43'),
(9, 14, 5, 2, 'Dolore aliquid quis quia.', 'Quo sed dolorem et excepturi. Alias expedita voluptatem repellat ad deserunt delectus est. Nemo odit sit nostrum non magnam.', '2025-12-13 10:50:19', 'approved', '2025-12-22 15:42:43', '2025-12-22 15:42:43'),
(10, 15, 5, 2, 'Saepe sint et ex.', 'Aliquid magnam vel aut quas dolor illum itaque. Est est quisquam voluptatem ut ab ex ab. Libero esse qui sint.', '2025-11-22 16:15:45', 'cancelled', '2025-12-22 15:42:43', '2025-12-22 15:42:43'),
(11, 16, 2, 2, 'Excepturi quidem dolore dignissimos.', 'Nihil dicta deleniti id minima ut. Officiis quidem quasi rerum. Repellendus deserunt veritatis sed explicabo.', '2025-11-29 17:51:52', 'pending', '2025-12-22 15:42:43', '2025-12-22 15:42:43'),
(12, 17, 5, 2, 'Eligendi animi eum.', 'Magnam molestiae qui asperiores nulla neque. Sint repellat nisi ut sunt ex aliquam quibusdam. Et aliquid assumenda nihil quia ipsa voluptatem. Molestiae ducimus eveniet cum doloremque magni accusantium possimus.', '2025-12-26 01:48:59', 'rejected', '2025-12-22 15:42:43', '2025-12-22 15:42:43'),
(13, 18, 2, 2, 'Quia molestiae rerum sequi.', 'Provident nobis et dolores blanditiis tempore cupiditate sapiente. Vero totam dolorem repudiandae provident. Qui et officia qui est quibusdam doloribus exercitationem. Vero autem magni ipsum enim eum et modi.', '2026-01-13 02:30:52', 'cancelled', '2025-12-22 15:42:43', '2025-12-22 15:42:43'),
(14, 19, 2, 2, 'Aperiam autem veniam voluptates.', 'Eos ducimus explicabo distinctio illo omnis quas. Suscipit laborum quam officia impedit eum neque.', '2026-01-17 04:25:14', 'rejected', '2025-12-22 15:42:43', '2025-12-22 15:42:43'),
(15, 20, 2, 2, 'Aliquid soluta non.', 'Vel natus qui voluptatem sed. Iure quis et maiores fugit perferendis. Modi voluptates repellat porro omnis aut laudantium omnis velit. Voluptatem porro mollitia accusantium vitae ea occaecati id.', '2025-11-24 08:42:14', 'cancelled', '2025-12-22 15:42:43', '2025-12-22 15:42:43'),
(16, 21, 5, 2, 'Magnam aperiam nihil ea.', 'Ut quod ullam nihil doloribus. Iure quod nam suscipit fugiat et est eos. Explicabo fugit recusandae alias quasi. Neque odit atque ducimus dolor est aut repellat.', '2026-01-06 07:38:20', 'pending', '2025-12-22 15:42:43', '2025-12-22 15:42:43'),
(17, 22, 5, 2, 'Eum et voluptas.', 'Sint sequi temporibus vero id. Velit officiis eos quasi esse nihil sed quam. Voluptatibus labore expedita nihil labore ut. Vel eaque sint ea ipsam minima.', '2025-12-03 13:33:28', 'approved', '2025-12-22 15:42:43', '2025-12-22 15:42:43'),
(18, 23, 2, 2, 'Et ea adipisci.', 'Ipsa voluptatem ut eos unde dicta. At qui veniam delectus omnis. Dolorem qui in fugit dolore.', '2026-01-03 22:02:00', 'completed', '2025-12-22 15:42:44', '2025-12-22 15:53:46'),
(19, 24, 5, 2, 'Ut facere quis voluptate.', 'Consectetur dignissimos est illo magni veritatis amet. Quia consequuntur illo dolores corporis tempore error. Quaerat alias et tempora non beatae nemo ratione voluptatem. Qui quae porro aut veniam. Sunt ipsum atque omnis aut.', '2026-01-21 03:51:00', 'rejected', '2025-12-22 15:42:44', '2025-12-22 15:56:34'),
(20, 25, 2, 2, 'Explicabo blanditiis in error repudiandae.', 'Praesentium id itaque provident doloribus. Illo alias dolor sed odio et. Deserunt est repellendus non laboriosam fuga voluptas.', '2025-12-27 10:46:40', 'approved', '2025-12-22 15:42:44', '2025-12-22 15:42:44'),
(22, 30, NULL, 2, 'Service Booking Request', NULL, '2025-12-24 10:31:53', 'pending', '2025-12-22 18:31:53', '2025-12-22 18:31:53'),
(23, 32, NULL, 2, 'Service Booking Request', NULL, '2025-12-26 10:31:56', 'pending', '2025-12-22 18:31:56', '2025-12-22 18:31:56'),
(24, 30, NULL, 2, 'Service Booking Request', NULL, '2025-12-24 10:33:15', 'pending', '2025-12-22 18:33:15', '2025-12-22 18:33:15'),
(25, 32, NULL, 2, 'Service Booking Request', NULL, '2025-12-26 10:33:19', 'pending', '2025-12-22 18:33:19', '2025-12-22 18:33:19'),
(26, 30, NULL, 2, 'Service Booking Request', NULL, '2025-12-24 10:37:01', 'pending', '2025-12-22 18:37:01', '2025-12-22 18:37:01'),
(27, 32, NULL, 2, 'Service Booking Request', NULL, '2025-12-26 10:37:05', 'pending', '2025-12-22 18:37:05', '2025-12-22 18:37:05'),
(28, 30, NULL, 2, 'Service Booking Request', NULL, '2025-12-24 10:38:15', 'pending', '2025-12-22 18:38:15', '2025-12-22 18:38:15'),
(29, 32, NULL, 2, 'Service Booking Request', NULL, '2025-12-26 10:38:20', 'pending', '2025-12-22 18:38:20', '2025-12-22 18:38:20'),
(30, 30, NULL, 2, 'Service Booking Request', NULL, '2025-12-24 10:39:43', 'pending', '2025-12-22 18:39:43', '2025-12-22 18:39:43'),
(31, 32, NULL, 2, 'Service Booking Request', NULL, '2025-12-25 00:00:00', 'rejected', '2025-12-22 18:39:46', '2025-12-23 12:59:33'),
(32, 4, 1, 2, 'Service Request 1', NULL, '2025-12-24 07:44:15', 'confirmed', '2025-12-23 15:44:15', '2025-12-23 15:44:15'),
(33, 4, 1, 2, 'Service Request 2', NULL, '2025-12-25 07:44:15', 'confirmed', '2025-12-23 15:44:15', '2025-12-23 15:44:15'),
(34, 4, 1, 2, 'Service Request 3', NULL, '2025-12-26 07:44:15', 'confirmed', '2025-12-23 15:44:15', '2025-12-23 15:44:15'),
(35, 4, 1, 2, 'Service Request 4', NULL, '2025-12-27 07:44:15', 'completed', '2025-12-23 15:44:15', '2025-12-23 15:44:15'),
(36, 4, 1, 2, 'Service Request 5', NULL, '2025-12-28 07:44:15', 'completed', '2025-12-23 15:44:15', '2025-12-23 16:12:00'),
(38, 4, 1, 2, 'Service Request 2', NULL, '2025-12-25 15:44:00', 'confirmed', '2025-12-23 15:44:55', '2025-12-23 20:01:53'),
(40, 4, 1, 2, 'Service Request 4', NULL, '2025-12-27 07:44:55', 'confirmed', '2025-12-23 15:44:55', '2025-12-23 15:44:55'),
(41, 4, 1, 2, 'Service Request 5', NULL, '2025-12-28 07:44:55', 'completed', '2025-12-23 15:44:55', '2025-12-23 15:44:55'),
(43, 1, 26, NULL, 'Photography', 'Gufotora', '2025-12-25 00:00:00', 'pending', '2025-12-23 16:38:32', '2025-12-23 16:38:32'),
(44, 34, NULL, NULL, 'Service Booking 1', 'Organic scalable attitude', '2026-01-25 23:22:33', 'completed', '2025-12-23 17:27:28', '2025-12-23 17:27:28'),
(45, 34, NULL, 3, 'Service Booking 2', 'Focused client-driven help-desk', '2026-01-29 12:16:15', 'completed', '2025-12-23 17:27:28', '2025-12-23 17:27:28'),
(46, 34, NULL, NULL, 'Service Booking 3', 'Synergized bi-directional alliance', '2026-02-06 19:24:33', 'pending', '2025-12-23 17:27:28', '2025-12-23 17:27:28'),
(47, 34, NULL, NULL, 'Service Booking 4', 'Persevering multimedia securedline', '2026-01-17 20:29:45', 'cancelled', '2025-12-23 17:27:28', '2025-12-23 17:27:28'),
(48, 34, NULL, NULL, 'Service Booking 5', 'Down-sized object-oriented model', '2026-01-05 22:50:05', 'cancelled', '2025-12-23 17:27:28', '2025-12-23 17:27:28'),
(50, 1, NULL, NULL, 'Rail Yard Engineer Service', 'unleash interactive experiences', '2025-12-26 00:00:00', 'pending', '2025-12-23 17:42:33', '2025-12-23 17:42:33'),
(51, 1, 26, NULL, 'Photography', 'Gufotora', '2025-12-26 00:00:00', 'pending', '2025-12-23 20:13:17', '2025-12-23 20:13:17');

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
('laravel-cache-settings.currency', 's:3:\"FRW\";', 2081847813),
('laravel-cache-spatie.permission.cache', 'a:3:{s:5:\"alias\";a:4:{s:1:\"a\";s:2:\"id\";s:1:\"b\";s:4:\"name\";s:1:\"c\";s:10:\"guard_name\";s:1:\"r\";s:5:\"roles\";}s:11:\"permissions\";a:40:{i:0;a:4:{s:1:\"a\";i:1;s:1:\"b\";s:10:\"view users\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:2:{i:0;i:1;i:1;i:2;}}i:1;a:4:{s:1:\"a\";i:2;s:1:\"b\";s:12:\"create users\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:2:{i:0;i:1;i:1;i:2;}}i:2;a:4:{s:1:\"a\";i:3;s:1:\"b\";s:10:\"edit users\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:2:{i:0;i:1;i:1;i:2;}}i:3;a:4:{s:1:\"a\";i:4;s:1:\"b\";s:12:\"delete users\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:1:{i:0;i:1;}}i:4;a:4:{s:1:\"a\";i:5;s:1:\"b\";s:14:\"view customers\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:2:{i:0;i:1;i:1;i:2;}}i:5;a:4:{s:1:\"a\";i:6;s:1:\"b\";s:16:\"create customers\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:2:{i:0;i:1;i:1;i:2;}}i:6;a:4:{s:1:\"a\";i:7;s:1:\"b\";s:14:\"edit customers\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:2:{i:0;i:1;i:1;i:2;}}i:7;a:4:{s:1:\"a\";i:8;s:1:\"b\";s:16:\"delete customers\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:1:{i:0;i:1;}}i:8;a:4:{s:1:\"a\";i:9;s:1:\"b\";s:14:\"view employees\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:2:{i:0;i:1;i:1;i:2;}}i:9;a:4:{s:1:\"a\";i:10;s:1:\"b\";s:16:\"create employees\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:1:{i:0;i:1;}}i:10;a:4:{s:1:\"a\";i:11;s:1:\"b\";s:14:\"edit employees\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:2:{i:0;i:1;i:1;i:2;}}i:11;a:4:{s:1:\"a\";i:12;s:1:\"b\";s:16:\"delete employees\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:1:{i:0;i:1;}}i:12;a:4:{s:1:\"a\";i:13;s:1:\"b\";s:13:\"view products\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:4:{i:0;i:1;i:1;i:2;i:2;i:3;i:3;i:4;}}i:13;a:4:{s:1:\"a\";i:14;s:1:\"b\";s:15:\"create products\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:2:{i:0;i:1;i:1;i:2;}}i:14;a:4:{s:1:\"a\";i:15;s:1:\"b\";s:13:\"edit products\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:2:{i:0;i:1;i:1;i:2;}}i:15;a:4:{s:1:\"a\";i:16;s:1:\"b\";s:15:\"delete products\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:1:{i:0;i:1;}}i:16;a:4:{s:1:\"a\";i:17;s:1:\"b\";s:11:\"view orders\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:4:{i:0;i:1;i:1;i:2;i:2;i:3;i:3;i:4;}}i:17;a:4:{s:1:\"a\";i:18;s:1:\"b\";s:13:\"create orders\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:2:{i:0;i:1;i:1;i:4;}}i:18;a:4:{s:1:\"a\";i:19;s:1:\"b\";s:11:\"edit orders\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:2:{i:0;i:1;i:1;i:2;}}i:19;a:4:{s:1:\"a\";i:20;s:1:\"b\";s:13:\"delete orders\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:1:{i:0;i:1;}}i:20;a:4:{s:1:\"a\";i:21;s:1:\"b\";s:17:\"view appointments\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:4:{i:0;i:1;i:1;i:2;i:2;i:3;i:3;i:4;}}i:21;a:4:{s:1:\"a\";i:22;s:1:\"b\";s:19:\"create appointments\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:3:{i:0;i:1;i:1;i:2;i:2;i:4;}}i:22;a:4:{s:1:\"a\";i:23;s:1:\"b\";s:17:\"edit appointments\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:2:{i:0;i:1;i:1;i:2;}}i:23;a:4:{s:1:\"a\";i:24;s:1:\"b\";s:19:\"delete appointments\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:1:{i:0;i:1;}}i:24;a:4:{s:1:\"a\";i:25;s:1:\"b\";s:13:\"view bookings\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:4:{i:0;i:1;i:1;i:2;i:2;i:3;i:3;i:4;}}i:25;a:4:{s:1:\"a\";i:26;s:1:\"b\";s:15:\"create bookings\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:3:{i:0;i:1;i:1;i:2;i:2;i:4;}}i:26;a:4:{s:1:\"a\";i:27;s:1:\"b\";s:13:\"edit bookings\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:2:{i:0;i:1;i:1;i:2;}}i:27;a:4:{s:1:\"a\";i:28;s:1:\"b\";s:15:\"delete bookings\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:1:{i:0;i:1;}}i:28;a:4:{s:1:\"a\";i:29;s:1:\"b\";s:10:\"view stock\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:2:{i:0;i:1;i:1;i:2;}}i:29;a:4:{s:1:\"a\";i:30;s:1:\"b\";s:12:\"create stock\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:2:{i:0;i:1;i:1;i:2;}}i:30;a:4:{s:1:\"a\";i:31;s:1:\"b\";s:10:\"edit stock\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:2:{i:0;i:1;i:1;i:2;}}i:31;a:4:{s:1:\"a\";i:32;s:1:\"b\";s:12:\"delete stock\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:1:{i:0;i:1;}}i:32;a:4:{s:1:\"a\";i:33;s:1:\"b\";s:12:\"view tickets\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:4:{i:0;i:1;i:1;i:2;i:2;i:3;i:3;i:4;}}i:33;a:4:{s:1:\"a\";i:34;s:1:\"b\";s:14:\"create tickets\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:3:{i:0;i:1;i:1;i:3;i:2;i:4;}}i:34;a:4:{s:1:\"a\";i:35;s:1:\"b\";s:12:\"edit tickets\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:3:{i:0;i:1;i:1;i:2;i:2;i:3;}}i:35;a:4:{s:1:\"a\";i:36;s:1:\"b\";s:14:\"delete tickets\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:1:{i:0;i:1;}}i:36;a:4:{s:1:\"a\";i:37;s:1:\"b\";s:12:\"view reports\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:2:{i:0;i:1;i:1;i:2;}}i:37;a:4:{s:1:\"a\";i:38;s:1:\"b\";s:16:\"generate reports\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:2:{i:0;i:1;i:1;i:2;}}i:38;a:4:{s:1:\"a\";i:39;s:1:\"b\";s:15:\"manage settings\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:1:{i:0;i:1;}}i:39;a:4:{s:1:\"a\";i:40;s:1:\"b\";s:12:\"manage roles\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:1:{i:0;i:1;}}}s:5:\"roles\";a:4:{i:0;a:3:{s:1:\"a\";i:1;s:1:\"b\";s:5:\"admin\";s:1:\"c\";s:3:\"web\";}i:1;a:3:{s:1:\"a\";i:2;s:1:\"b\";s:7:\"manager\";s:1:\"c\";s:3:\"web\";}i:2;a:3:{s:1:\"a\";i:3;s:1:\"b\";s:8:\"employee\";s:1:\"c\";s:3:\"web\";}i:3;a:3:{s:1:\"a\";i:4;s:1:\"b\";s:8:\"customer\";s:1:\"c\";s:3:\"web\";}}}', 1767640097);

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
-- Table structure for table `categories`
--

CREATE TABLE `categories` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `description` text DEFAULT NULL,
  `parent_id` bigint(20) UNSIGNED DEFAULT NULL,
  `slug` varchar(255) DEFAULT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `categories`
--

INSERT INTO `categories` (`id`, `name`, `description`, `parent_id`, `slug`, `is_active`, `created_at`, `updated_at`) VALUES
(1, 'Maintenance', 'New', NULL, NULL, 1, '2025-12-22 03:04:41', '2025-12-22 03:04:41'),
(3, 'Transport', 'Cars', NULL, NULL, 1, '2025-12-22 16:02:49', '2025-12-22 16:02:49'),
(4, 'Electronics', 'Electronic devices and accessories', NULL, NULL, 1, '2025-12-22 17:10:04', '2025-12-22 17:10:04'),
(5, 'Clothing', 'Apparel and fashion items', NULL, NULL, 1, '2025-12-22 17:10:04', '2025-12-22 17:10:04'),
(6, 'Food & Beverages', 'Food and drink products', NULL, NULL, 1, '2025-12-22 17:10:04', '2025-12-22 17:10:04'),
(7, 'Home & Garden', 'Home improvement and garden supplies', NULL, NULL, 1, '2025-12-22 17:10:04', '2025-12-22 17:10:04'),
(8, 'Office Supplies', 'Office and stationery items', NULL, NULL, 1, '2025-12-22 17:10:04', '2025-12-22 17:10:04'),
(9, 'Home & Office', 'Home & Office items', NULL, NULL, 1, '2025-12-23 17:20:31', '2025-12-23 17:20:31'),
(10, 'Services', 'Services items', NULL, NULL, 1, '2025-12-23 17:20:31', '2025-12-23 17:20:31'),
(11, 'Software', 'Software items', NULL, NULL, 1, '2025-12-23 17:20:31', '2025-12-23 17:20:31');

-- --------------------------------------------------------

--
-- Table structure for table `customers`
--

CREATE TABLE `customers` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `email` varchar(255) DEFAULT NULL,
  `phone` varchar(255) DEFAULT NULL,
  `address` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `customers`
--

INSERT INTO `customers` (`id`, `name`, `email`, `phone`, `address`, `created_at`, `updated_at`) VALUES
(6, 'Ernesto Ondricka Jr.', 'reichert.mario@example.net', NULL, NULL, '2025-12-22 15:42:43', '2025-12-22 15:42:43'),
(7, 'Don Kuhlman', 'kelli.daniel@example.net', NULL, NULL, '2025-12-22 15:42:43', '2025-12-22 15:42:43'),
(8, 'Justyn Balistreri IV', 'lamont.denesik@example.org', NULL, NULL, '2025-12-22 15:42:43', '2025-12-22 15:42:43'),
(9, 'Glenda Marvin', 'akemmer@example.com', NULL, NULL, '2025-12-22 15:42:43', '2025-12-22 15:42:43'),
(10, 'Dr. Jaqueline Gerhold', 'dion18@example.org', NULL, NULL, '2025-12-22 15:42:43', '2025-12-22 15:42:43'),
(11, 'Camylle Nolan III', 'urban.thiel@example.net', NULL, NULL, '2025-12-22 15:42:43', '2025-12-22 15:42:43'),
(12, 'Sydney Keebler', 'ykoss@example.org', NULL, NULL, '2025-12-22 15:42:43', '2025-12-22 15:42:43'),
(14, 'Jaquan Halvorson', 'frieda06@example.org', NULL, NULL, '2025-12-22 15:42:43', '2025-12-22 15:42:43'),
(15, 'Christiana Gulgowski DDS', 'cade80@example.org', NULL, NULL, '2025-12-22 15:42:43', '2025-12-22 15:42:43'),
(16, 'Mr. Rhiannon Balistreri DDS', 'verona97@example.net', NULL, NULL, '2025-12-22 15:42:43', '2025-12-22 15:42:43'),
(17, 'Mikayla Hoeger', 'sadye.zieme@example.com', NULL, NULL, '2025-12-22 15:42:43', '2025-12-22 15:42:43'),
(18, 'Mr. Brannon Kutch', 'cboyle@example.org', NULL, NULL, '2025-12-22 15:42:43', '2025-12-22 15:42:43'),
(19, 'Therese Hayes', 'madisen.wilderman@example.org', NULL, NULL, '2025-12-22 15:42:43', '2025-12-22 15:42:43'),
(20, 'Lorenza Wilderman', 'nwisozk@example.com', NULL, NULL, '2025-12-22 15:42:43', '2025-12-22 15:42:43'),
(21, 'Orlo Rau Jr.', 'strosin.leola@example.org', NULL, NULL, '2025-12-22 15:42:43', '2025-12-22 15:42:43'),
(22, 'Ms. Erika O\'Kon PhD', 'qgerlach@example.com', NULL, NULL, '2025-12-22 15:42:43', '2025-12-22 15:42:43'),
(23, 'Dr. Tess Stark DDS', 'halle67@example.com', NULL, NULL, '2025-12-22 15:42:43', '2025-12-22 15:42:43'),
(24, 'Flavie Kautzer', 'jammie.reichel@example.com', NULL, NULL, '2025-12-22 15:42:43', '2025-12-22 15:42:43'),
(25, 'Adrian DuBuque', 'dbartoletti@example.net', NULL, NULL, '2025-12-22 15:42:43', '2025-12-22 15:42:43'),
(26, 'John Doe', 'john@example.com', '+250788111222', 'Kigali', '2025-12-22 17:10:38', '2025-12-22 17:10:38'),
(27, 'Jane Smith', 'jane@example.com', '+250788222333', 'Kigali', '2025-12-22 17:10:38', '2025-12-22 17:10:38'),
(28, 'Bob Johnson', 'bob@example.com', '+250788333444', 'Kigali', '2025-12-22 17:10:38', '2025-12-22 17:10:38'),
(30, 'Customer 2', 'customer2@test.com', NULL, NULL, '2025-12-22 18:27:44', '2025-12-22 18:27:44'),
(32, 'Customer 4', 'customer4@test.com', NULL, NULL, '2025-12-22 18:27:44', '2025-12-22 18:27:44'),
(33, 'Customer 5', 'customer5@test.com', NULL, NULL, '2025-12-22 18:27:45', '2025-12-22 18:27:45'),
(34, 'Demo Customer', 'customer@evuba.com', NULL, NULL, '2025-12-23 17:20:31', '2025-12-23 17:20:31'),
(35, 'Dusabe Yvonne', 'dusabe@gmail.com', NULL, NULL, '2025-12-23 16:39:16', '2026-01-05 02:22:42');

-- --------------------------------------------------------

--
-- Table structure for table `employees`
--

CREATE TABLE `employees` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `phone` varchar(255) DEFAULT NULL,
  `position` varchar(255) DEFAULT NULL,
  `specialization` varchar(255) DEFAULT NULL,
  `department` varchar(255) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `employees`
--

INSERT INTO `employees` (`id`, `name`, `email`, `phone`, `position`, `specialization`, `department`, `created_at`, `updated_at`) VALUES
(1, 'Faustino', 'admin@gmail.com', NULL, 'Admin', NULL, NULL, '2025-12-23 18:04:55', '2025-12-23 18:58:25'),
(2, 'Manager', 'manager@gmail.com', NULL, 'Manager', NULL, NULL, '2025-12-22 02:56:44', '2025-12-22 02:56:51'),
(5, 'Keza Kelia', 'kellia@gmail.com', NULL, 'Manager', NULL, NULL, '2025-12-22 15:16:33', '2025-12-22 15:16:33'),
(13, 'Alison Kris', 'cooper.rogahn@example.net', NULL, 'Manager', NULL, NULL, '2025-12-22 21:34:19', '2025-12-22 21:34:19'),
(26, 'John Tech', 'john.tech@evuba.com', NULL, 'Employee', NULL, NULL, '2025-12-22 18:27:43', '2025-12-22 18:27:43'),
(27, 'Sarah Support', 'sarah.support@evuba.com', '0787832490', 'Employee', 'Maintenance', 'ICT', '2025-12-22 18:27:43', '2025-12-23 12:50:11'),
(28, 'Mike Sales', 'mike.sales@evuba.com', NULL, 'Employee', NULL, NULL, '2025-12-22 18:27:43', '2025-12-22 18:27:43'),
(29, 'Customer 1', 'customer1@test.com', NULL, 'Employee', NULL, NULL, '2025-12-23 05:35:12', '2025-12-23 05:35:12');

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
-- Table structure for table `inventories`
--

CREATE TABLE `inventories` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `quantity` int(11) NOT NULL DEFAULT 0,
  `price` decimal(10,2) NOT NULL DEFAULT 0.00,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `invoices`
--

CREATE TABLE `invoices` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `invoice_number` varchar(255) NOT NULL,
  `invoice_date` date NOT NULL,
  `subtotal` decimal(10,2) DEFAULT NULL,
  `tax_amount` decimal(10,2) DEFAULT NULL,
  `customer_id` bigint(20) UNSIGNED DEFAULT NULL,
  `customer_name` varchar(255) NOT NULL,
  `description` text DEFAULT NULL,
  `grand_total` decimal(10,2) NOT NULL,
  `payment_method` varchar(255) DEFAULT NULL,
  `status` varchar(255) NOT NULL DEFAULT 'pending',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `invoices`
--

INSERT INTO `invoices` (`id`, `invoice_number`, `invoice_date`, `subtotal`, `tax_amount`, `customer_id`, `customer_name`, `description`, `grand_total`, `payment_method`, `status`, `created_at`, `updated_at`) VALUES
(1, 'INV-00001', '2025-12-22', 5875000.00, 1057500.00, 9, 'Emmanuel Rukundo', NULL, 6932500.00, 'momo', 'paid', '2025-12-22 12:38:49', '2025-12-22 12:38:49'),
(2, 'INV-00002', '2025-12-22', 2445000.00, 440100.00, NULL, 'Umwiza Umwali', NULL, 2885100.00, 'momo', 'paid', '2025-12-22 12:40:22', '2025-12-22 12:40:22'),
(3, 'INV-00003', '2025-12-22', 2100000.00, 378000.00, NULL, 'Customer', NULL, 2478000.00, 'airtel', 'paid', '2025-12-22 12:44:32', '2025-12-22 12:44:32'),
(4, 'INV-00004', '2025-12-22', 2245000.00, 404100.00, 7, 'Jean-Luc Habimana', NULL, 2649100.00, 'momo', 'paid', '2025-12-22 12:46:33', '2025-12-22 12:46:33'),
(5, 'INV-20251222-005', '2025-12-22', 234791.00, 42262.38, 7, 'Don Kuhlman', 'yes', 277053.38, 'mtn_momo', 'paid', '2025-12-22 18:10:12', '2025-12-22 18:10:12'),
(6, 'INV-20251222-006', '2025-12-22', 201926.00, 36346.68, 34, 'Hoziane', 'Keep in dry clean space', 238272.68, 'mtn_momo', 'paid', '2025-12-23 05:30:18', '2025-12-23 05:30:18'),
(7, 'INV-20251223-001', '2025-12-23', 2312643.98, 416275.92, 27, 'Jane Smith', 'Transport given to our customers', 2728919.90, 'mtn_momo', 'paid', '2025-12-23 19:11:51', '2025-12-23 19:11:51');

-- --------------------------------------------------------

--
-- Table structure for table `invoice_items`
--

CREATE TABLE `invoice_items` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `invoice_id` bigint(20) UNSIGNED NOT NULL,
  `product_id` bigint(20) UNSIGNED DEFAULT NULL,
  `description` varchar(255) DEFAULT NULL,
  `quantity` int(11) NOT NULL,
  `unit_price` decimal(10,2) NOT NULL,
  `total` decimal(10,2) NOT NULL,
  `buy_price` decimal(10,2) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `invoice_items`
--

INSERT INTO `invoice_items` (`id`, `invoice_id`, `product_id`, `description`, `quantity`, `unit_price`, `total`, `buy_price`, `created_at`, `updated_at`) VALUES
(1, 1, 2, 'Dell Latitude 5420', 1, 850000.00, 850000.00, NULL, '2025-12-22 12:38:49', '2025-12-22 12:38:49'),
(2, 1, 3, 'MacBook Air M2', 4, 1250000.00, 5000000.00, NULL, '2025-12-22 12:38:49', '2025-12-22 12:38:49'),
(3, 1, 4, 'Logitech Wireless Mouse', 1, 25000.00, 25000.00, NULL, '2025-12-22 12:38:49', '2025-12-22 12:38:49'),
(4, 2, NULL, 'Computer & Laptop Services', 1, 0.00, 0.00, NULL, '2025-12-22 12:40:22', '2025-12-22 12:40:22'),
(5, 2, 2, 'Dell Latitude 5420', 1, 850000.00, 850000.00, NULL, '2025-12-22 12:40:22', '2025-12-22 12:40:22'),
(6, 2, 3, 'MacBook Air M2', 1, 1250000.00, 1250000.00, NULL, '2025-12-22 12:40:22', '2025-12-22 12:40:22'),
(7, 2, 4, 'Logitech Wireless Mouse', 1, 25000.00, 25000.00, NULL, '2025-12-22 12:40:22', '2025-12-22 12:40:22'),
(8, 2, 5, 'External SSD 1TB', 1, 120000.00, 120000.00, NULL, '2025-12-22 12:40:22', '2025-12-22 12:40:22'),
(9, 2, NULL, 'Software Installation & Config', 1, 50000.00, 50000.00, NULL, '2025-12-22 12:40:22', '2025-12-22 12:40:22'),
(10, 2, 7, 'Network Maintenance (Monthly)', 1, 150000.00, 150000.00, NULL, '2025-12-22 12:40:22', '2025-12-22 12:40:22'),
(11, 3, 2, 'Dell Latitude 5420', 1, 850000.00, 850000.00, NULL, '2025-12-22 12:44:32', '2025-12-22 12:44:32'),
(12, 3, 3, 'MacBook Air M2', 1, 1250000.00, 1250000.00, NULL, '2025-12-22 12:44:32', '2025-12-22 12:44:32'),
(13, 4, NULL, 'Computer & Laptop Services', 1, 0.00, 0.00, NULL, '2025-12-22 12:46:33', '2025-12-22 12:46:33'),
(14, 4, 2, 'Dell Latitude 5420', 1, 850000.00, 850000.00, NULL, '2025-12-22 12:46:33', '2025-12-22 12:46:33'),
(15, 4, 3, 'MacBook Air M2', 1, 1250000.00, 1250000.00, NULL, '2025-12-22 12:46:33', '2025-12-22 12:46:33'),
(16, 4, 4, 'Logitech Wireless Mouse', 1, 25000.00, 25000.00, NULL, '2025-12-22 12:46:33', '2025-12-22 12:46:33'),
(17, 4, 5, 'External SSD 1TB', 1, 120000.00, 120000.00, NULL, '2025-12-22 12:46:33', '2025-12-22 12:46:33'),
(18, 5, NULL, 'Software Installation & Config', 1, 234791.00, 234791.00, NULL, '2025-12-22 18:10:12', '2025-12-22 18:10:12'),
(19, 6, 2, 'Dell Latitude 5420', 2, 21341.00, 42682.00, NULL, '2025-12-23 05:30:18', '2025-12-23 05:30:18'),
(20, 6, 5, 'External SSD 1TB', 1, 89814.00, 89814.00, NULL, '2025-12-23 05:30:18', '2025-12-23 05:30:18'),
(21, 6, 7, 'Network Maintenance (Monthly)', 1, 69430.00, 69430.00, NULL, '2025-12-23 05:30:18', '2025-12-23 05:30:18'),
(22, 7, 3, 'MacBook Air M2', 3, 462311.00, 1386933.00, NULL, '2025-12-23 19:11:51', '2025-12-23 19:11:51'),
(23, 7, 7, 'Network Maintenance (Monthly)', 3, 69430.00, 208290.00, NULL, '2025-12-23 19:11:51', '2025-12-23 19:11:51'),
(24, 7, 5, 'External SSD 1TB', 3, 85036.66, 255109.98, NULL, '2025-12-23 19:11:51', '2025-12-23 19:11:51'),
(25, 7, 3, 'MacBook Air M2', 1, 462311.00, 462311.00, NULL, '2025-12-23 19:11:51', '2025-12-23 19:11:51');

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

--
-- Dumping data for table `jobs`
--

INSERT INTO `jobs` (`id`, `queue`, `payload`, `attempts`, `reserved_at`, `available_at`, `created_at`) VALUES
(1, 'default', '{\"uuid\":\"83abf489-b438-4645-a6cd-16aae4aec0ea\",\"displayName\":\"App\\\\Mail\\\\CompanyUpdateMail\",\"job\":\"Illuminate\\\\Queue\\\\CallQueuedHandler@call\",\"maxTries\":null,\"maxExceptions\":null,\"failOnTimeout\":false,\"backoff\":null,\"timeout\":null,\"retryUntil\":null,\"data\":{\"commandName\":\"Illuminate\\\\Mail\\\\SendQueuedMailable\",\"command\":\"O:34:\\\"Illuminate\\\\Mail\\\\SendQueuedMailable\\\":17:{s:8:\\\"mailable\\\";O:26:\\\"App\\\\Mail\\\\CompanyUpdateMail\\\":3:{s:14:\\\"messageContent\\\";s:3:\\\"yes\\\";s:2:\\\"to\\\";a:1:{i:0;a:2:{s:4:\\\"name\\\";N;s:7:\\\"address\\\";s:24:\\\"innocentntakir@gmail.com\\\";}}s:6:\\\"mailer\\\";s:4:\\\"smtp\\\";}s:5:\\\"tries\\\";N;s:7:\\\"timeout\\\";N;s:13:\\\"maxExceptions\\\";N;s:17:\\\"shouldBeEncrypted\\\";b:0;s:10:\\\"connection\\\";N;s:5:\\\"queue\\\";N;s:12:\\\"messageGroup\\\";N;s:12:\\\"deduplicator\\\";N;s:5:\\\"delay\\\";N;s:11:\\\"afterCommit\\\";N;s:10:\\\"middleware\\\";a:0:{}s:7:\\\"chained\\\";a:0:{}s:15:\\\"chainConnection\\\";N;s:10:\\\"chainQueue\\\";N;s:19:\\\"chainCatchCallbacks\\\";N;s:3:\\\"job\\\";N;}\"},\"createdAt\":1766384270,\"delay\":null}', 0, NULL, 1766384270, 1766384270),
(2, 'default', '{\"uuid\":\"c0a0a8a8-90ee-4bdc-9f39-30bab65dc3a0\",\"displayName\":\"App\\\\Mail\\\\CompanyUpdateMail\",\"job\":\"Illuminate\\\\Queue\\\\CallQueuedHandler@call\",\"maxTries\":null,\"maxExceptions\":null,\"failOnTimeout\":false,\"backoff\":null,\"timeout\":null,\"retryUntil\":null,\"data\":{\"commandName\":\"Illuminate\\\\Mail\\\\SendQueuedMailable\",\"command\":\"O:34:\\\"Illuminate\\\\Mail\\\\SendQueuedMailable\\\":17:{s:8:\\\"mailable\\\";O:26:\\\"App\\\\Mail\\\\CompanyUpdateMail\\\":3:{s:14:\\\"messageContent\\\";s:4:\\\"Helo\\\";s:2:\\\"to\\\";a:1:{i:0;a:2:{s:4:\\\"name\\\";N;s:7:\\\"address\\\";s:24:\\\"innocentntakir@gmail.com\\\";}}s:6:\\\"mailer\\\";s:4:\\\"smtp\\\";}s:5:\\\"tries\\\";N;s:7:\\\"timeout\\\";N;s:13:\\\"maxExceptions\\\";N;s:17:\\\"shouldBeEncrypted\\\";b:0;s:10:\\\"connection\\\";N;s:5:\\\"queue\\\";N;s:12:\\\"messageGroup\\\";N;s:12:\\\"deduplicator\\\";N;s:5:\\\"delay\\\";N;s:11:\\\"afterCommit\\\";N;s:10:\\\"middleware\\\";a:0:{}s:7:\\\"chained\\\";a:0:{}s:15:\\\"chainConnection\\\";N;s:10:\\\"chainQueue\\\";N;s:19:\\\"chainCatchCallbacks\\\";N;s:3:\\\"job\\\";N;}\"},\"createdAt\":1766403619,\"delay\":null}', 0, NULL, 1766403619, 1766403619),
(3, 'default', '{\"uuid\":\"11cdf47a-fb2e-4d08-9773-c5cc27ad72ce\",\"displayName\":\"App\\\\Mail\\\\CompanyUpdateMail\",\"job\":\"Illuminate\\\\Queue\\\\CallQueuedHandler@call\",\"maxTries\":null,\"maxExceptions\":null,\"failOnTimeout\":false,\"backoff\":null,\"timeout\":null,\"retryUntil\":null,\"data\":{\"commandName\":\"Illuminate\\\\Mail\\\\SendQueuedMailable\",\"command\":\"O:34:\\\"Illuminate\\\\Mail\\\\SendQueuedMailable\\\":17:{s:8:\\\"mailable\\\";O:26:\\\"App\\\\Mail\\\\CompanyUpdateMail\\\":3:{s:14:\\\"messageContent\\\";s:9:\\\"Thank you\\\";s:2:\\\"to\\\";a:1:{i:0;a:2:{s:4:\\\"name\\\";N;s:7:\\\"address\\\";s:24:\\\"innocentntakir@gmail.com\\\";}}s:6:\\\"mailer\\\";s:4:\\\"smtp\\\";}s:5:\\\"tries\\\";N;s:7:\\\"timeout\\\";N;s:13:\\\"maxExceptions\\\";N;s:17:\\\"shouldBeEncrypted\\\";b:0;s:10:\\\"connection\\\";N;s:5:\\\"queue\\\";N;s:12:\\\"messageGroup\\\";N;s:12:\\\"deduplicator\\\";N;s:5:\\\"delay\\\";N;s:11:\\\"afterCommit\\\";N;s:10:\\\"middleware\\\";a:0:{}s:7:\\\"chained\\\";a:0:{}s:15:\\\"chainConnection\\\";N;s:10:\\\"chainQueue\\\";N;s:19:\\\"chainCatchCallbacks\\\";N;s:3:\\\"job\\\";N;}\"},\"createdAt\":1766490084,\"delay\":null}', 0, NULL, 1766490084, 1766490084),
(4, 'default', '{\"uuid\":\"73659ba5-114d-4e0a-b7d2-7c4c19b516ef\",\"displayName\":\"App\\\\Mail\\\\CompanyUpdateMail\",\"job\":\"Illuminate\\\\Queue\\\\CallQueuedHandler@call\",\"maxTries\":null,\"maxExceptions\":null,\"failOnTimeout\":false,\"backoff\":null,\"timeout\":null,\"retryUntil\":null,\"data\":{\"commandName\":\"Illuminate\\\\Mail\\\\SendQueuedMailable\",\"command\":\"O:34:\\\"Illuminate\\\\Mail\\\\SendQueuedMailable\\\":17:{s:8:\\\"mailable\\\";O:26:\\\"App\\\\Mail\\\\CompanyUpdateMail\\\":3:{s:14:\\\"messageContent\\\";s:9:\\\"Thank you\\\";s:2:\\\"to\\\";a:1:{i:0;a:2:{s:4:\\\"name\\\";N;s:7:\\\"address\\\";s:22:\\\"inonecdreams@gmail.com\\\";}}s:6:\\\"mailer\\\";s:4:\\\"smtp\\\";}s:5:\\\"tries\\\";N;s:7:\\\"timeout\\\";N;s:13:\\\"maxExceptions\\\";N;s:17:\\\"shouldBeEncrypted\\\";b:0;s:10:\\\"connection\\\";N;s:5:\\\"queue\\\";N;s:12:\\\"messageGroup\\\";N;s:12:\\\"deduplicator\\\";N;s:5:\\\"delay\\\";N;s:11:\\\"afterCommit\\\";N;s:10:\\\"middleware\\\";a:0:{}s:7:\\\"chained\\\";a:0:{}s:15:\\\"chainConnection\\\";N;s:10:\\\"chainQueue\\\";N;s:19:\\\"chainCatchCallbacks\\\";N;s:3:\\\"job\\\";N;}\"},\"createdAt\":1766490084,\"delay\":null}', 0, NULL, 1766490084, 1766490084);

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
  `sender_id` bigint(20) UNSIGNED NOT NULL,
  `recipient_id` bigint(20) UNSIGNED NOT NULL,
  `subject` varchar(255) DEFAULT NULL,
  `body` text NOT NULL,
  `is_read` tinyint(1) NOT NULL DEFAULT 0,
  `read_at` datetime DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `message_replies`
--

CREATE TABLE `message_replies` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `message_us_id` bigint(20) UNSIGNED NOT NULL,
  `user_id` bigint(20) UNSIGNED NOT NULL,
  `reply_content` text NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `message_replies`
--

INSERT INTO `message_replies` (`id`, `message_us_id`, `user_id`, `reply_content`, `created_at`, `updated_at`) VALUES
(2, 7, 1, 'Hello', '2025-12-23 05:38:14', '2025-12-23 05:38:14'),
(3, 7, 1, 'How are you', '2025-12-23 05:38:40', '2025-12-23 05:38:40'),
(4, 2, 1, 'Hi', '2025-12-23 05:53:23', '2025-12-23 05:53:23'),
(5, 14, 1, 'Hi how are you?', '2025-12-23 18:48:50', '2025-12-23 18:48:50'),
(6, 5, 1, 'yes', '2025-12-23 19:42:40', '2025-12-23 19:42:40'),
(7, 14, 1, 'Yes bro', '2025-12-24 00:45:57', '2025-12-24 00:45:57');

-- --------------------------------------------------------

--
-- Table structure for table `message_us`
--

CREATE TABLE `message_us` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `first_name` varchar(255) NOT NULL,
  `last_name` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `message` text NOT NULL,
  `read` tinyint(1) NOT NULL DEFAULT 0,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `message_us`
--

INSERT INTO `message_us` (`id`, `first_name`, `last_name`, `email`, `message`, `read`, `created_at`, `updated_at`) VALUES
(1, 'Sarah', 'Johnson', 'sarah.j@example.com', 'I am interested in your premium consulting services. Could you please send me a brochure or price list? Thank you!', 1, '2025-12-22 14:33:40', '2025-12-23 05:38:57'),
(2, 'Michael', 'Chen', 'm.chen@techsolutions.com', 'URGENT: Having trouble logging into my employee portal since this morning. It keeps throwing a 403 error. Please assist.', 1, '2025-12-22 14:33:40', '2025-12-23 05:39:07'),
(4, 'Emma', 'Wilson', 'emma.wilson@creative-co.uk', 'Just wanted to say that your support team was incredibly helpful with my last inquiry. Great job!', 1, '2025-12-22 14:33:40', '2025-12-22 14:33:40'),
(5, 'Guest', 'Visitor', 'guest@visitor.com', 'Do you offer bulk discounts for corporate orders?', 1, '2025-12-22 18:37:13', '2025-12-23 05:38:55'),
(6, 'Guest', 'Visitor', 'guest@visitor.com', 'Do you offer bulk discounts for corporate orders?', 1, '2025-12-22 18:38:26', '2025-12-23 05:38:53'),
(7, 'Guest', 'Visitor', 'guest@visitor.com', 'Do you offer bulk discounts for corporate orders?', 1, '2025-12-22 18:39:53', '2025-12-23 05:38:00'),
(8, 'Gianni', 'Lavie', 'gianni@gmail.com', 'Hi', 1, '2025-12-23 05:56:40', '2025-12-23 18:48:30'),
(9, 'Gianni', 'Lavie', 'gianni@gmail.com', 'Hi', 0, '2025-12-23 05:56:45', '2025-12-23 05:56:45'),
(10, 'Gianni', 'Lavie', 'gianni@gmail.com', 'Hi', 1, '2025-12-23 05:56:59', '2025-12-23 18:48:27'),
(11, 'Gianni', 'Lavie', 'gianni@gmail.com', 'Hi', 0, '2025-12-23 05:57:22', '2025-12-24 00:53:49'),
(12, 'Gianni', 'Lavie', 'gianni@gmail.com', 'Hi', 1, '2025-12-23 05:57:22', '2025-12-23 05:58:04'),
(13, 'Gianni', 'Lavie', 'gianni@gmail.com', 'Hi', 0, '2025-12-23 05:57:23', '2025-12-24 01:17:37'),
(14, 'Gianni', 'Lavie', 'gianni@gmail.com', 'Hi', 0, '2025-12-23 05:57:24', '2025-12-24 01:17:21');

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
(4, '2025_11_10_032104_create_permission_tables', 1),
(5, '2025_11_10_134944_create_appointments_table', 1),
(6, '2025_11_10_135207_create_orders_table', 1),
(7, '2025_11_10_135429_create_support_tickets_table', 1),
(8, '2025_11_10_142257_create_tasks_table', 1),
(9, '2025_11_10_144341_create_inventories_table', 1),
(10, '2025_11_10_150252_create_employees_table', 1),
(11, '2025_11_19_000001_create_categories_table', 1),
(12, '2025_11_19_000002_create_support_categories_table', 1),
(13, '2025_11_20_000001_create_products_table', 1),
(14, '2025_11_20_000002_create_suppliers_table', 1),
(15, '2025_11_20_000003_create_customers_table', 1),
(16, '2025_11_20_000004_create_services_table', 1),
(17, '2025_11_20_000005_create_teams_table', 1),
(18, '2025_11_20_000006_create_pages_table', 1),
(19, '2025_11_24_105516_create_sales_table', 1),
(20, '2025_11_27_000001_create_bookings_table', 1),
(21, '2025_11_27_000002_create_stock_table', 1),
(22, '2025_11_27_000003_create_stock_in_table', 1),
(23, '2025_11_27_000004_create_stock_out_table', 1),
(24, '2025_11_27_000005_create_invoices_table', 1),
(25, '2025_11_27_000006_create_invoice_items_table', 1),
(26, '2025_11_27_000007_create_team_members_table', 1),
(27, '2025_11_27_000008_create_schedules_table', 1),
(28, '2025_11_27_000009_create_role_page_permission_table', 1),
(29, '2025_11_27_000010_create_tickets_table', 1),
(30, '2025_11_27_000011_create_ticket_replies_table', 1),
(31, '2025_11_27_000012_create_ticket_logs_table', 1),
(32, '2025_12_15_061607_create_announcements_table', 1),
(33, '2025_12_18_124710_create_subscribers_table', 1),
(34, '2025_12_19_073609_create_messages_table', 1),
(35, '2025_12_21_100001_add_soft_deletes_to_users_table', 2),
(36, '2025_12_21_181450_create_notifications_table', 3),
(37, '2025_12_21_100002_rename_receiver_to_recipient_in_messages', 4),
(38, '2025_12_21_185126_add_specialization_and_department_to_employees_table', 5),
(39, '2025_12_21_190146_add_customer_and_payment_to_invoices_table', 6),
(40, '2025_12_21_190729_add_description_and_total_to_invoice_items_table', 7),
(41, '2025_12_21_202632_add_subtotal_and_tax_to_invoices_table', 8),
(43, '2025_12_22_042221_add_unit_price_to_products_table', 9),
(44, '2025_12_22_051536_add_employee_id_and_manager_id_to_appointments_table', 10),
(45, '2025_12_22_062047_create_message_us_table', 11),
(46, '2025_12_22_062940_create_message_replies_table', 12),
(48, '2025_12_22_065846_create_settings_table', 13),
(49, '2025_12_22_071213_add_is_active_to_users_table', 14),
(50, '2025_12_22_074029_change_booking_status_to_string', 15),
(51, '2025_12_22_102358_add_priority_and_source_to_appointments_table', 16),
(52, '2025_12_22_103855_make_user_id_nullable_in_appointments_table', 17),
(54, '2025_12_22_120206_fix_announcements_and_create_user_announcements_table', 18),
(55, '2025_12_22_125611_add_profile_fields_to_users_table', 19),
(56, '2025_12_22_130407_add_theme_to_users_table', 20),
(57, '2025_12_23_070955_create_reports_table', 21),
(58, '2025_12_23_093000_add_product_id_to_orders_table', 22);

-- --------------------------------------------------------

--
-- Table structure for table `model_has_permissions`
--

CREATE TABLE `model_has_permissions` (
  `permission_id` bigint(20) UNSIGNED NOT NULL,
  `model_type` varchar(255) NOT NULL,
  `model_id` bigint(20) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `model_has_roles`
--

CREATE TABLE `model_has_roles` (
  `role_id` bigint(20) UNSIGNED NOT NULL,
  `model_type` varchar(255) NOT NULL,
  `model_id` bigint(20) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `model_has_roles`
--

INSERT INTO `model_has_roles` (`role_id`, `model_type`, `model_id`) VALUES
(2, 'App\\Models\\User', 1),
(2, 'App\\Models\\User', 13),
(3, 'App\\Models\\User', 5),
(3, 'App\\Models\\User', 29),
(4, 'App\\Models\\User', 2),
(4, 'App\\Models\\User', 3),
(4, 'App\\Models\\User', 4),
(4, 'App\\Models\\User', 35);

-- --------------------------------------------------------

--
-- Table structure for table `notifications`
--

CREATE TABLE `notifications` (
  `id` char(36) NOT NULL,
  `type` varchar(255) NOT NULL,
  `notifiable_type` varchar(255) NOT NULL,
  `notifiable_id` bigint(20) UNSIGNED NOT NULL,
  `data` text NOT NULL,
  `read_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `notifications`
--

INSERT INTO `notifications` (`id`, `type`, `notifiable_type`, `notifiable_id`, `data`, `read_at`, `created_at`, `updated_at`) VALUES
('019a27e8-774c-4982-8b22-4a8818241db8', 'App\\Notifications\\SystemAlert', 'App\\Models\\User', 14, '{\"title\":\"Welcome to eVuba\",\"message\":\"Thank you for joining our professional platform.\",\"icon\":\"fa-door-open\",\"action_url\":\"#\"}', NULL, '2025-12-20 20:49:28', '2025-12-20 20:49:28'),
('02f621c3-cd10-4bfa-861e-d42d2cc13b3f', 'App\\Notifications\\TaskAssigned', 'App\\Models\\User', 7, '{\"title\":\"New Task Assigned\",\"message\":\"You have been assigned a new high-priority project task.\",\"icon\":\"fa-tasks\",\"action_url\":\"\\/tasks\"}', '2025-12-22 20:49:28', '2025-12-21 20:49:28', '2025-12-21 20:49:28'),
('042fcf6d-f4d3-40f7-bc07-5ac4172f4e2b', 'App\\Notifications\\TaskAssigned', 'App\\Models\\User', 13, '{\"title\":\"New Task Assigned\",\"message\":\"You have been assigned a new high-priority project task.\",\"icon\":\"fa-tasks\",\"action_url\":\"\\/tasks\"}', '2025-12-22 20:49:28', '2025-12-21 20:49:28', '2025-12-21 20:49:28'),
('06883062-109c-4c41-970d-88817ec7d8a9', 'App\\Notifications\\SystemAlert', 'App\\Models\\User', 16, '{\"title\":\"Welcome to eVuba\",\"message\":\"Thank you for joining our professional platform.\",\"icon\":\"fa-door-open\",\"action_url\":\"#\"}', NULL, '2025-12-20 20:49:28', '2025-12-20 20:49:28'),
('0b5acb6a-221a-4598-aa47-111892e3e5d3', 'App\\Notifications\\SystemAlert', 'App\\Models\\User', 27, '{\"title\":\"Welcome to eVuba\",\"message\":\"Thank you for joining our professional platform.\",\"icon\":\"fa-door-open\",\"action_url\":\"#\"}', NULL, '2025-12-20 20:49:28', '2025-12-20 20:49:28'),
('0c0a8ed8-e77e-4842-9f5d-f82eacf258ea', 'App\\Notifications\\SecurityAlert', 'App\\Models\\User', 26, '{\"title\":\"Security Update\",\"message\":\"Your profile security settings were successfully updated.\",\"icon\":\"fa-shield-alt\",\"action_url\":\"\\/profile\"}', NULL, '2025-12-22 20:19:28', '2025-12-22 20:19:28'),
('0c8708c2-23f6-4fa8-9522-c3721e1c186d', 'App\\Notifications\\SecurityAlert', 'App\\Models\\User', 24, '{\"title\":\"Security Update\",\"message\":\"Your profile security settings were successfully updated.\",\"icon\":\"fa-shield-alt\",\"action_url\":\"\\/profile\"}', NULL, '2025-12-22 20:19:28', '2025-12-22 20:19:28'),
('0db64f45-4f46-4d50-875a-761de0d31154', 'App\\Notifications\\SecurityAlert', 'App\\Models\\User', 27, '{\"title\":\"Security Update\",\"message\":\"Your profile security settings were successfully updated.\",\"icon\":\"fa-shield-alt\",\"action_url\":\"\\/profile\"}', NULL, '2025-12-22 20:19:28', '2025-12-22 20:19:28'),
('0dd78021-71df-4f38-a17b-6fb0a6e86488', 'App\\Notifications\\TaskAssigned', 'App\\Models\\User', 4, '{\"title\":\"New Task Assigned\",\"message\":\"You have been assigned a new high-priority project task.\",\"icon\":\"fa-tasks\",\"action_url\":\"\\/tasks\"}', '2025-12-22 20:49:28', '2025-12-21 20:49:28', '2025-12-21 20:49:28'),
('0e4ceb2b-2800-4e84-9d3a-01c118757e39', 'App\\Notifications\\TaskAssigned', 'App\\Models\\User', 28, '{\"title\":\"New Task Assigned\",\"message\":\"You have been assigned a new high-priority project task.\",\"icon\":\"fa-tasks\",\"action_url\":\"\\/tasks\"}', '2025-12-22 20:49:28', '2025-12-21 20:49:28', '2025-12-21 20:49:28'),
('0f8571e4-9e21-4a1b-a9ba-b707ff8a852b', 'App\\Notifications\\TaskAssigned', 'App\\Models\\User', 6, '{\"title\":\"New Task Assigned\",\"message\":\"You have been assigned a new high-priority project task.\",\"icon\":\"fa-tasks\",\"action_url\":\"\\/tasks\"}', '2025-12-22 20:49:28', '2025-12-21 20:49:28', '2025-12-21 20:49:28'),
('1037a54c-a66a-43f8-9ede-12b75ee5fc0a', 'App\\Notifications\\SecurityAlert', 'App\\Models\\User', 8, '{\"title\":\"Security Update\",\"message\":\"Your profile security settings were successfully updated.\",\"icon\":\"fa-shield-alt\",\"action_url\":\"\\/profile\"}', NULL, '2025-12-22 20:19:28', '2025-12-22 20:19:28'),
('125ba77a-2aae-430e-92ae-5287d6684f43', 'App\\Notifications\\SystemAlert', 'App\\Models\\User', 10, '{\"title\":\"Welcome to eVuba\",\"message\":\"Thank you for joining our professional platform.\",\"icon\":\"fa-door-open\",\"action_url\":\"#\"}', NULL, '2025-12-20 20:49:28', '2025-12-20 20:49:28'),
('182fe750-fb54-49fc-8b0a-c8dedbf0e809', 'App\\Notifications\\TaskAssigned', 'App\\Models\\User', 29, '{\"title\":\"New Task Assigned\",\"message\":\"You have been assigned a new high-priority project task.\",\"icon\":\"fa-tasks\",\"action_url\":\"\\/tasks\"}', '2025-12-22 20:49:28', '2025-12-21 20:49:28', '2025-12-21 20:49:28'),
('1b23264a-3ead-442d-abae-f8e17fef6c6b', 'App\\Notifications\\TaskAssigned', 'App\\Models\\User', 21, '{\"title\":\"New Task Assigned\",\"message\":\"You have been assigned a new high-priority project task.\",\"icon\":\"fa-tasks\",\"action_url\":\"\\/tasks\"}', '2025-12-22 20:49:28', '2025-12-21 20:49:28', '2025-12-21 20:49:28'),
('1c9f309f-98d1-433c-86d3-e9a36caaf078', 'App\\Notifications\\SystemAlert', 'App\\Models\\User', 6, '{\"title\":\"Welcome to eVuba\",\"message\":\"Thank you for joining our professional platform.\",\"icon\":\"fa-door-open\",\"action_url\":\"#\"}', NULL, '2025-12-20 20:49:28', '2025-12-20 20:49:28'),
('247e4d47-511d-46f3-8fc8-e91c9a96f9e3', 'App\\Notifications\\TaskAssigned', 'App\\Models\\User', 1, '{\"title\":\"New Task Assigned\",\"message\":\"You have been assigned a new high-priority project task.\",\"icon\":\"fa-tasks\",\"action_url\":\"\\/tasks\"}', '2025-12-22 20:49:28', '2025-12-21 20:49:28', '2025-12-21 20:49:28'),
('283caafa-668b-49f3-b8a6-8ceaf1ce0848', 'App\\Notifications\\SecurityAlert', 'App\\Models\\User', 23, '{\"title\":\"Security Update\",\"message\":\"Your profile security settings were successfully updated.\",\"icon\":\"fa-shield-alt\",\"action_url\":\"\\/profile\"}', NULL, '2025-12-22 20:19:28', '2025-12-22 20:19:28'),
('2a56f7b9-d3fb-4b5e-a34a-bc18e0497cdf', 'App\\Notifications\\TaskAssigned', 'App\\Models\\User', 18, '{\"title\":\"New Task Assigned\",\"message\":\"You have been assigned a new high-priority project task.\",\"icon\":\"fa-tasks\",\"action_url\":\"\\/tasks\"}', '2025-12-22 20:49:28', '2025-12-21 20:49:28', '2025-12-21 20:49:28'),
('332df1b0-0917-4455-97a6-2df09f72f2cf', 'App\\Notifications\\SystemAlert', 'App\\Models\\User', 30, '{\"title\":\"Welcome to eVuba\",\"message\":\"Thank you for joining our professional platform.\",\"icon\":\"fa-door-open\",\"action_url\":\"#\"}', NULL, '2025-12-20 20:49:28', '2025-12-20 20:49:28'),
('34ed83a8-54ab-46f5-ada7-5dd3726f44db', 'App\\Notifications\\SystemAlert', 'App\\Models\\User', 28, '{\"title\":\"Welcome to eVuba\",\"message\":\"Thank you for joining our professional platform.\",\"icon\":\"fa-door-open\",\"action_url\":\"#\"}', NULL, '2025-12-20 20:49:28', '2025-12-20 20:49:28'),
('36316fef-2f48-46e6-a7fc-cf92cf7d1708', 'App\\Notifications\\SecurityAlert', 'App\\Models\\User', 14, '{\"title\":\"Security Update\",\"message\":\"Your profile security settings were successfully updated.\",\"icon\":\"fa-shield-alt\",\"action_url\":\"\\/profile\"}', NULL, '2025-12-22 20:19:28', '2025-12-22 20:19:28'),
('3668372d-08e3-3c53-9329-b641376031dc', 'App\\Notifications\\SystemAlert', 'App\\Models\\User', 34, '{\"title\":\"System Update 4\",\"message\":\"Odit est est eum et nam vel.\",\"icon\":\"fa-info-circle\",\"action_url\":\"#\"}', NULL, '2025-12-22 02:45:59', '2025-12-23 17:27:28'),
('36bc0ca6-8bec-4af3-9908-ff8c5ad2d59c', 'App\\Notifications\\TaskAssigned', 'App\\Models\\User', 20, '{\"title\":\"New Task Assigned\",\"message\":\"You have been assigned a new high-priority project task.\",\"icon\":\"fa-tasks\",\"action_url\":\"\\/tasks\"}', '2025-12-22 20:49:28', '2025-12-21 20:49:28', '2025-12-21 20:49:28'),
('37d79dea-3206-30f8-8243-27e208deab85', 'App\\Notifications\\SystemAlert', 'App\\Models\\User', 34, '{\"title\":\"System Update 2\",\"message\":\"Animi ut quaerat hic eos recusandae.\",\"icon\":\"fa-info-circle\",\"action_url\":\"#\"}', NULL, '2025-12-21 20:32:06', '2025-12-23 17:26:00'),
('3848327d-e479-422d-a461-753ff5d37ea3', 'App\\Notifications\\SecurityAlert', 'App\\Models\\User', 31, '{\"title\":\"Security Update\",\"message\":\"Your profile security settings were successfully updated.\",\"icon\":\"fa-shield-alt\",\"action_url\":\"\\/profile\"}', NULL, '2025-12-22 20:19:28', '2025-12-22 20:19:28'),
('399b9038-a887-4624-82da-5ebbba15d654', 'App\\Notifications\\TaskAssigned', 'App\\Models\\User', 10, '{\"title\":\"New Task Assigned\",\"message\":\"You have been assigned a new high-priority project task.\",\"icon\":\"fa-tasks\",\"action_url\":\"\\/tasks\"}', '2025-12-22 20:49:28', '2025-12-21 20:49:28', '2025-12-21 20:49:28'),
('3a1b2dc2-189b-42e3-b7f1-70131a8d1e76', 'App\\Notifications\\SecurityAlert', 'App\\Models\\User', 11, '{\"title\":\"Security Update\",\"message\":\"Your profile security settings were successfully updated.\",\"icon\":\"fa-shield-alt\",\"action_url\":\"\\/profile\"}', NULL, '2025-12-22 20:19:28', '2025-12-22 20:19:28'),
('3ab5e328-5976-4476-8700-df9f3fa68052', 'App\\Notifications\\SecurityAlert', 'App\\Models\\User', 4, '{\"title\":\"Security Update\",\"message\":\"Your profile security settings were successfully updated.\",\"icon\":\"fa-shield-alt\",\"action_url\":\"\\/profile\"}', NULL, '2025-12-22 20:19:28', '2025-12-22 20:19:28'),
('3f338b43-f9d5-3c3c-9b28-80891bb40c80', 'App\\Notifications\\SystemAlert', 'App\\Models\\User', 34, '{\"title\":\"System Update 1\",\"message\":\"Animi cumque dolor ducimus neque exercitationem harum non a.\",\"icon\":\"fa-info-circle\",\"action_url\":\"#\"}', '2025-12-23 17:27:28', '2025-12-20 11:24:28', '2025-12-23 17:27:28'),
('4109a31e-2595-4393-ab0d-7ffbe49e6482', 'App\\Notifications\\SystemAlert', 'App\\Models\\User', 20, '{\"title\":\"Welcome to eVuba\",\"message\":\"Thank you for joining our professional platform.\",\"icon\":\"fa-door-open\",\"action_url\":\"#\"}', NULL, '2025-12-20 20:49:28', '2025-12-20 20:49:28'),
('487c1551-acab-4719-86eb-995dabf6a147', 'App\\Notifications\\TaskAssigned', 'App\\Models\\User', 12, '{\"title\":\"New Task Assigned\",\"message\":\"You have been assigned a new high-priority project task.\",\"icon\":\"fa-tasks\",\"action_url\":\"\\/tasks\"}', '2025-12-22 20:49:28', '2025-12-21 20:49:28', '2025-12-21 20:49:28'),
('4c0dce47-a2f4-4ed3-8971-84afdf4a0f0a', 'App\\Notifications\\TaskAssigned', 'App\\Models\\User', 30, '{\"title\":\"New Task Assigned\",\"message\":\"You have been assigned a new high-priority project task.\",\"icon\":\"fa-tasks\",\"action_url\":\"\\/tasks\"}', '2025-12-22 20:49:28', '2025-12-21 20:49:28', '2025-12-21 20:49:28'),
('4e433fe4-7b1f-4a24-9f0b-a6b3767becdf', 'App\\Notifications\\SecurityAlert', 'App\\Models\\User', 12, '{\"title\":\"Security Update\",\"message\":\"Your profile security settings were successfully updated.\",\"icon\":\"fa-shield-alt\",\"action_url\":\"\\/profile\"}', NULL, '2025-12-22 20:19:28', '2025-12-22 20:19:28'),
('4fcee0a5-841f-4bc5-8381-412a6209bc56', 'App\\Notifications\\TaskAssigned', 'App\\Models\\User', 23, '{\"title\":\"New Task Assigned\",\"message\":\"You have been assigned a new high-priority project task.\",\"icon\":\"fa-tasks\",\"action_url\":\"\\/tasks\"}', '2025-12-22 20:49:28', '2025-12-21 20:49:28', '2025-12-21 20:49:28'),
('50a9ea43-548c-4a48-b9fa-5c93dbafeb01', 'App\\Notifications\\SecurityAlert', 'App\\Models\\User', 5, '{\"title\":\"Security Update\",\"message\":\"Your profile security settings were successfully updated.\",\"icon\":\"fa-shield-alt\",\"action_url\":\"\\/profile\"}', NULL, '2025-12-22 20:19:28', '2025-12-22 20:19:28'),
('52a97557-2b82-4dfc-b1e1-3e214af454bb', 'App\\Notifications\\SystemAlert', 'App\\Models\\User', 25, '{\"title\":\"Welcome to eVuba\",\"message\":\"Thank you for joining our professional platform.\",\"icon\":\"fa-door-open\",\"action_url\":\"#\"}', NULL, '2025-12-20 20:49:28', '2025-12-20 20:49:28'),
('55455347-f7bd-4b81-a3ee-40a638a899c3', 'App\\Notifications\\TaskAssigned', 'App\\Models\\User', 25, '{\"title\":\"New Task Assigned\",\"message\":\"You have been assigned a new high-priority project task.\",\"icon\":\"fa-tasks\",\"action_url\":\"\\/tasks\"}', '2025-12-22 20:49:28', '2025-12-21 20:49:28', '2025-12-21 20:49:28'),
('55c02456-8640-4327-be3c-834b438b986d', 'App\\Notifications\\TaskAssigned', 'App\\Models\\User', 9, '{\"title\":\"New Task Assigned\",\"message\":\"You have been assigned a new high-priority project task.\",\"icon\":\"fa-tasks\",\"action_url\":\"\\/tasks\"}', '2025-12-22 20:49:28', '2025-12-21 20:49:28', '2025-12-21 20:49:28'),
('56d7ed44-f46c-4934-ade9-0081de48a705', 'App\\Notifications\\SecurityAlert', 'App\\Models\\User', 6, '{\"title\":\"Security Update\",\"message\":\"Your profile security settings were successfully updated.\",\"icon\":\"fa-shield-alt\",\"action_url\":\"\\/profile\"}', NULL, '2025-12-22 20:19:28', '2025-12-22 20:19:28'),
('57e99666-7025-4983-8953-5117e6b7e0d7', 'App\\Notifications\\SystemAlert', 'App\\Models\\User', 15, '{\"title\":\"Welcome to eVuba\",\"message\":\"Thank you for joining our professional platform.\",\"icon\":\"fa-door-open\",\"action_url\":\"#\"}', NULL, '2025-12-20 20:49:28', '2025-12-20 20:49:28'),
('5be48113-6466-46ba-8b4c-7d92da311db1', 'App\\Notifications\\SecurityAlert', 'App\\Models\\User', 16, '{\"title\":\"Security Update\",\"message\":\"Your profile security settings were successfully updated.\",\"icon\":\"fa-shield-alt\",\"action_url\":\"\\/profile\"}', NULL, '2025-12-22 20:19:28', '2025-12-22 20:19:28'),
('5dde21d7-64a1-4330-834f-09a7abf274b1', 'App\\Notifications\\SystemAlert', 'App\\Models\\User', 18, '{\"title\":\"Welcome to eVuba\",\"message\":\"Thank you for joining our professional platform.\",\"icon\":\"fa-door-open\",\"action_url\":\"#\"}', NULL, '2025-12-20 20:49:28', '2025-12-20 20:49:28'),
('5e7888b0-42a1-418d-80fd-e801d23b533c', 'App\\Notifications\\TaskAssigned', 'App\\Models\\User', 11, '{\"title\":\"New Task Assigned\",\"message\":\"You have been assigned a new high-priority project task.\",\"icon\":\"fa-tasks\",\"action_url\":\"\\/tasks\"}', '2025-12-22 20:49:28', '2025-12-21 20:49:28', '2025-12-21 20:49:28'),
('66b9d433-681e-4c6d-9326-6bbd88327f94', 'App\\Notifications\\SystemAlert', 'App\\Models\\User', 1, '{\"title\":\"Photo Updated\",\"message\":\"Your profile picture has been changed.\",\"icon\":\"fa-camera\",\"action_url\":\"http:\\/\\/127.0.0.1:8000\\/profile\",\"type\":\"system\"}', '2025-12-23 17:34:17', '2025-12-22 21:01:39', '2025-12-23 17:34:17'),
('689bb709-f16e-4fe9-8b21-8a2d4229c68d', 'App\\Notifications\\SystemAlert', 'App\\Models\\User', 4, '{\"title\":\"Welcome to eVuba\",\"message\":\"Thank you for joining our professional platform.\",\"icon\":\"fa-door-open\",\"action_url\":\"#\"}', NULL, '2025-12-20 20:49:28', '2025-12-20 20:49:28'),
('68ce8a02-a24a-465b-9d3a-bd224f3e7958', 'App\\Notifications\\SecurityAlert', 'App\\Models\\User', 30, '{\"title\":\"Security Update\",\"message\":\"Your profile security settings were successfully updated.\",\"icon\":\"fa-shield-alt\",\"action_url\":\"\\/profile\"}', NULL, '2025-12-22 20:19:28', '2025-12-22 20:19:28'),
('68e4dc81-2ea0-4d93-9ad3-ae4266dfefc2', 'App\\Notifications\\TaskAssigned', 'App\\Models\\User', 24, '{\"title\":\"New Task Assigned\",\"message\":\"You have been assigned a new high-priority project task.\",\"icon\":\"fa-tasks\",\"action_url\":\"\\/tasks\"}', '2025-12-22 20:49:28', '2025-12-21 20:49:28', '2025-12-21 20:49:28'),
('6910930e-6fdd-4296-833f-940ed3915f52', 'App\\Notifications\\SecurityAlert', 'App\\Models\\User', 15, '{\"title\":\"Security Update\",\"message\":\"Your profile security settings were successfully updated.\",\"icon\":\"fa-shield-alt\",\"action_url\":\"\\/profile\"}', NULL, '2025-12-22 20:19:28', '2025-12-22 20:19:28'),
('71aea338-685d-46c9-80e5-410314a88ab1', 'App\\Notifications\\TaskAssigned', 'App\\Models\\User', 17, '{\"title\":\"New Task Assigned\",\"message\":\"You have been assigned a new high-priority project task.\",\"icon\":\"fa-tasks\",\"action_url\":\"\\/tasks\"}', '2025-12-22 20:49:28', '2025-12-21 20:49:28', '2025-12-21 20:49:28'),
('725b8af7-b167-46d4-8746-5097ea50387e', 'App\\Notifications\\SecurityAlert', 'App\\Models\\User', 21, '{\"title\":\"Security Update\",\"message\":\"Your profile security settings were successfully updated.\",\"icon\":\"fa-shield-alt\",\"action_url\":\"\\/profile\"}', NULL, '2025-12-22 20:19:28', '2025-12-22 20:19:28'),
('72e68b93-4259-45b5-8d51-bde1b9d179fd', 'App\\Notifications\\SecurityAlert', 'App\\Models\\User', 32, '{\"title\":\"Security Update\",\"message\":\"Your profile security settings were successfully updated.\",\"icon\":\"fa-shield-alt\",\"action_url\":\"\\/profile\"}', NULL, '2025-12-22 20:19:28', '2025-12-22 20:19:28'),
('7cf2c335-a40e-48d1-9d4a-302ef160365b', 'App\\Notifications\\SecurityAlert', 'App\\Models\\User', 9, '{\"title\":\"Security Update\",\"message\":\"Your profile security settings were successfully updated.\",\"icon\":\"fa-shield-alt\",\"action_url\":\"\\/profile\"}', NULL, '2025-12-22 20:19:28', '2025-12-22 20:19:28'),
('84259b11-d9b0-4cf1-85a5-f9e4eb9a5fcd', 'App\\Notifications\\TaskAssigned', 'App\\Models\\User', 22, '{\"title\":\"New Task Assigned\",\"message\":\"You have been assigned a new high-priority project task.\",\"icon\":\"fa-tasks\",\"action_url\":\"\\/tasks\"}', '2025-12-22 20:49:28', '2025-12-21 20:49:28', '2025-12-21 20:49:28'),
('8d3bbe92-1e4d-408a-ba06-83555f9cb37c', 'App\\Notifications\\SecurityAlert', 'App\\Models\\User', 29, '{\"title\":\"Security Update\",\"message\":\"Your profile security settings were successfully updated.\",\"icon\":\"fa-shield-alt\",\"action_url\":\"\\/profile\"}', NULL, '2025-12-22 20:19:28', '2025-12-22 20:19:28'),
('9013fe6d-6b93-3ede-b768-f9aa29b257dc', 'App\\Notifications\\SystemAlert', 'App\\Models\\User', 34, '{\"title\":\"System Update 3\",\"message\":\"Amet ad nulla nobis blanditiis consequuntur quis accusantium.\",\"icon\":\"fa-info-circle\",\"action_url\":\"#\"}', '2025-12-23 17:27:28', '2025-12-17 13:20:34', '2025-12-23 17:27:28'),
('90a9c63b-c726-3149-917f-9f9244d4ced2', 'App\\Notifications\\SystemAlert', 'App\\Models\\User', 34, '{\"title\":\"System Update 3\",\"message\":\"Consequuntur error ut dolores hic.\",\"icon\":\"fa-info-circle\",\"action_url\":\"#\"}', '2025-12-23 17:26:00', '2025-12-22 07:36:52', '2025-12-23 17:26:00'),
('919aabb3-c60e-4ebc-9b6e-1d468f3b8ee1', 'App\\Notifications\\SystemAlert', 'App\\Models\\User', 34, '{\"title\":\"Order Status Updated\",\"message\":\"Your order # is now Cancelled.\",\"icon\":\"fa-shopping-bag\",\"action_url\":\"http:\\/\\/127.0.0.1:8000\\/customer\\/orders\",\"type\":\"system\"}', NULL, '2025-12-23 19:28:46', '2025-12-23 19:28:46'),
('94124a33-6dad-4103-a4a2-df7519bb43ee', 'App\\Notifications\\SystemAlert', 'App\\Models\\User', 19, '{\"title\":\"Welcome to eVuba\",\"message\":\"Thank you for joining our professional platform.\",\"icon\":\"fa-door-open\",\"action_url\":\"#\"}', NULL, '2025-12-20 20:49:28', '2025-12-20 20:49:28'),
('965eb82f-bd93-41b8-84b3-caecb4f7085c', 'App\\Notifications\\SecurityAlert', 'App\\Models\\User', 7, '{\"title\":\"Security Update\",\"message\":\"Your profile security settings were successfully updated.\",\"icon\":\"fa-shield-alt\",\"action_url\":\"\\/profile\"}', NULL, '2025-12-22 20:19:28', '2025-12-22 20:19:28'),
('99c25dde-2acb-4aee-8fb6-6ff343a1d8eb', 'App\\Notifications\\SystemAlert', 'App\\Models\\User', 29, '{\"title\":\"Welcome to eVuba\",\"message\":\"Thank you for joining our professional platform.\",\"icon\":\"fa-door-open\",\"action_url\":\"#\"}', NULL, '2025-12-20 20:49:28', '2025-12-20 20:49:28'),
('9b0c6da1-083b-4af3-b094-75b4e287f78e', 'App\\Notifications\\SecurityAlert', 'App\\Models\\User', 2, '{\"title\":\"Security Update\",\"message\":\"Your profile security settings were successfully updated.\",\"icon\":\"fa-shield-alt\",\"action_url\":\"\\/profile\"}', NULL, '2025-12-22 20:19:28', '2025-12-22 20:19:28'),
('9c7b6305-df6a-462f-816c-e5c098df5ab4', 'App\\Notifications\\SystemAlert', 'App\\Models\\User', 1, '{\"title\":\"Photo Updated\",\"message\":\"Your profile picture has been changed.\",\"icon\":\"fa-camera\",\"action_url\":\"http:\\/\\/127.0.0.1:8000\\/profile\",\"type\":\"system\"}', '2025-12-23 17:40:45', '2025-12-22 20:57:10', '2025-12-23 17:40:45'),
('9f2c212e-1d01-4ec9-8117-239b4c44fa89', 'App\\Notifications\\SystemAlert', 'App\\Models\\User', 33, '{\"title\":\"Welcome to eVuba\",\"message\":\"Thank you for joining our professional platform.\",\"icon\":\"fa-door-open\",\"action_url\":\"#\"}', NULL, '2025-12-20 20:49:28', '2025-12-20 20:49:28'),
('9f67a5d1-186e-46e7-8b30-437888875769', 'App\\Notifications\\TaskAssigned', 'App\\Models\\User', 8, '{\"title\":\"New Task Assigned\",\"message\":\"You have been assigned a new high-priority project task.\",\"icon\":\"fa-tasks\",\"action_url\":\"\\/tasks\"}', '2025-12-22 20:49:28', '2025-12-21 20:49:28', '2025-12-21 20:49:28'),
('9f7ae546-d1dc-4dbc-9f0b-bfda20490f0a', 'App\\Notifications\\SystemAlert', 'App\\Models\\User', 5, '{\"title\":\"Welcome to eVuba\",\"message\":\"Thank you for joining our professional platform.\",\"icon\":\"fa-door-open\",\"action_url\":\"#\"}', NULL, '2025-12-20 20:49:28', '2025-12-20 20:49:28'),
('9f99fb64-23a2-472f-bc01-8ae85ee1d56a', 'App\\Notifications\\SecurityAlert', 'App\\Models\\User', 19, '{\"title\":\"Security Update\",\"message\":\"Your profile security settings were successfully updated.\",\"icon\":\"fa-shield-alt\",\"action_url\":\"\\/profile\"}', NULL, '2025-12-22 20:19:28', '2025-12-22 20:19:28'),
('a01936f2-ed1d-427a-828c-bfceb1380076', 'App\\Notifications\\SystemAlert', 'App\\Models\\User', 2, '{\"title\":\"Welcome to eVuba\",\"message\":\"Thank you for joining our professional platform.\",\"icon\":\"fa-door-open\",\"action_url\":\"#\"}', NULL, '2025-12-20 20:49:28', '2025-12-20 20:49:28'),
('a6593b93-67af-4288-aeb1-6c0b4286e293', 'App\\Notifications\\SystemAlert', 'App\\Models\\User', 21, '{\"title\":\"Welcome to eVuba\",\"message\":\"Thank you for joining our professional platform.\",\"icon\":\"fa-door-open\",\"action_url\":\"#\"}', NULL, '2025-12-20 20:49:28', '2025-12-20 20:49:28'),
('a6726814-fe64-4354-92fe-316880dd0b64', 'App\\Notifications\\SystemAlert', 'App\\Models\\User', 26, '{\"title\":\"Welcome to eVuba\",\"message\":\"Thank you for joining our professional platform.\",\"icon\":\"fa-door-open\",\"action_url\":\"#\"}', NULL, '2025-12-20 20:49:28', '2025-12-20 20:49:28'),
('a7e7d268-a060-3e7d-b929-d0ab65077e91', 'App\\Notifications\\SystemAlert', 'App\\Models\\User', 34, '{\"title\":\"System Update 5\",\"message\":\"Aperiam nemo et quia aliquid magni est amet.\",\"icon\":\"fa-info-circle\",\"action_url\":\"#\"}', '2025-12-23 17:26:00', '2025-12-21 09:26:58', '2025-12-23 17:26:00'),
('a7e7f480-b2c6-4023-8dac-c85eec8ab70d', 'App\\Notifications\\SecurityAlert', 'App\\Models\\User', 13, '{\"title\":\"Security Update\",\"message\":\"Your profile security settings were successfully updated.\",\"icon\":\"fa-shield-alt\",\"action_url\":\"\\/profile\"}', NULL, '2025-12-22 20:19:28', '2025-12-22 20:19:28'),
('a8b86cfc-5a59-482d-ba4c-b86fc84d0fb6', 'App\\Notifications\\TaskAssigned', 'App\\Models\\User', 27, '{\"title\":\"New Task Assigned\",\"message\":\"You have been assigned a new high-priority project task.\",\"icon\":\"fa-tasks\",\"action_url\":\"\\/tasks\"}', '2025-12-22 20:49:28', '2025-12-21 20:49:28', '2025-12-21 20:49:28'),
('ad36148c-fb6a-47f1-9142-cdcb26f63dad', 'App\\Notifications\\TaskAssigned', 'App\\Models\\User', 3, '{\"title\":\"New Task Assigned\",\"message\":\"You have been assigned a new high-priority project task.\",\"icon\":\"fa-tasks\",\"action_url\":\"\\/tasks\"}', '2025-12-22 20:49:28', '2025-12-21 20:49:28', '2025-12-21 20:49:28'),
('af03a062-e310-35f9-bc2c-54645e748d54', 'App\\Notifications\\SystemAlert', 'App\\Models\\User', 34, '{\"title\":\"System Update 5\",\"message\":\"Et sed cum non qui tempore voluptas.\",\"icon\":\"fa-info-circle\",\"action_url\":\"#\"}', '2025-12-23 17:27:28', '2025-12-22 19:04:01', '2025-12-23 17:27:28'),
('af22c73d-56be-346e-b661-fbdb677db946', 'App\\Notifications\\SystemAlert', 'App\\Models\\User', 34, '{\"title\":\"System Update 4\",\"message\":\"Rerum id sunt illum corrupti et mollitia.\",\"icon\":\"fa-info-circle\",\"action_url\":\"#\"}', NULL, '2025-12-21 19:59:13', '2025-12-23 17:26:00'),
('b576ecec-ab7f-49fb-8b27-ab5f7186c174', 'App\\Notifications\\SystemAlert', 'App\\Models\\User', 23, '{\"title\":\"Welcome to eVuba\",\"message\":\"Thank you for joining our professional platform.\",\"icon\":\"fa-door-open\",\"action_url\":\"#\"}', NULL, '2025-12-20 20:49:28', '2025-12-20 20:49:28'),
('b8314bda-6bd1-45d0-a12a-0ce4b64ae942', 'App\\Notifications\\SecurityAlert', 'App\\Models\\User', 3, '{\"title\":\"Security Update\",\"message\":\"Your profile security settings were successfully updated.\",\"icon\":\"fa-shield-alt\",\"action_url\":\"\\/profile\"}', NULL, '2025-12-22 20:19:28', '2025-12-22 20:19:28'),
('b917f801-888b-4e8f-9e80-0e78841bd2cb', 'App\\Notifications\\SecurityAlert', 'App\\Models\\User', 22, '{\"title\":\"Security Update\",\"message\":\"Your profile security settings were successfully updated.\",\"icon\":\"fa-shield-alt\",\"action_url\":\"\\/profile\"}', NULL, '2025-12-22 20:19:28', '2025-12-22 20:19:28'),
('ba38fcdc-5eda-4a7f-be0f-36085d8eb2c4', 'App\\Notifications\\SecurityAlert', 'App\\Models\\User', 25, '{\"title\":\"Security Update\",\"message\":\"Your profile security settings were successfully updated.\",\"icon\":\"fa-shield-alt\",\"action_url\":\"\\/profile\"}', NULL, '2025-12-22 20:19:28', '2025-12-22 20:19:28'),
('bd3c305c-bfb4-4a60-94f2-a1ffcff86344', 'App\\Notifications\\SystemAlert', 'App\\Models\\User', 32, '{\"title\":\"Welcome to eVuba\",\"message\":\"Thank you for joining our professional platform.\",\"icon\":\"fa-door-open\",\"action_url\":\"#\"}', NULL, '2025-12-20 20:49:28', '2025-12-20 20:49:28'),
('c11d871a-dd61-3d9b-8f0e-afdf87627223', 'App\\Notifications\\SystemAlert', 'App\\Models\\User', 34, '{\"title\":\"System Update 1\",\"message\":\"Magnam dicta sed qui.\",\"icon\":\"fa-info-circle\",\"action_url\":\"#\"}', '2025-12-23 17:26:00', '2025-12-18 23:38:42', '2025-12-23 17:26:00'),
('c2512b20-48a8-41d8-90ec-5f5d94d532eb', 'App\\Notifications\\SystemAlert', 'App\\Models\\User', 24, '{\"title\":\"Welcome to eVuba\",\"message\":\"Thank you for joining our professional platform.\",\"icon\":\"fa-door-open\",\"action_url\":\"#\"}', NULL, '2025-12-20 20:49:28', '2025-12-20 20:49:28'),
('c3ec5e14-222b-4eb6-9eef-b289f89043ae', 'App\\Notifications\\BookingRescheduledNotification', 'App\\Models\\User', 24, '{\"title\":\"Appointment Rescheduled\",\"message\":\"Your appointment for Networking has been moved to Jan 21, 03:51 AM\",\"booking_id\":19,\"type\":\"reschedule\"}', NULL, '2025-12-22 15:56:40', '2025-12-22 15:56:40'),
('c9889f10-8405-447c-b184-c633299a53a5', 'App\\Notifications\\SystemAlert', 'App\\Models\\User', 12, '{\"title\":\"Welcome to eVuba\",\"message\":\"Thank you for joining our professional platform.\",\"icon\":\"fa-door-open\",\"action_url\":\"#\"}', NULL, '2025-12-20 20:49:28', '2025-12-20 20:49:28'),
('cb1271d4-c0db-4d5b-a85a-b23eb7139f38', 'App\\Notifications\\TaskAssigned', 'App\\Models\\User', 26, '{\"title\":\"New Task Assigned\",\"message\":\"You have been assigned a new high-priority project task.\",\"icon\":\"fa-tasks\",\"action_url\":\"\\/tasks\"}', '2025-12-22 20:49:28', '2025-12-21 20:49:28', '2025-12-21 20:49:28'),
('cf96e9f8-c9b6-45b3-9c29-fc8f5d5eb87d', 'App\\Notifications\\SystemAlert', 'App\\Models\\User', 3, '{\"title\":\"Welcome to eVuba\",\"message\":\"Thank you for joining our professional platform.\",\"icon\":\"fa-door-open\",\"action_url\":\"#\"}', NULL, '2025-12-20 20:49:28', '2025-12-20 20:49:28'),
('d333b347-d39c-4335-b80c-267228ca9804', 'App\\Notifications\\TaskAssigned', 'App\\Models\\User', 31, '{\"title\":\"New Task Assigned\",\"message\":\"You have been assigned a new high-priority project task.\",\"icon\":\"fa-tasks\",\"action_url\":\"\\/tasks\"}', '2025-12-22 20:49:28', '2025-12-21 20:49:28', '2025-12-21 20:49:28'),
('d35fd39f-5b01-470f-9522-94b684ff99fe', 'App\\Notifications\\SecurityAlert', 'App\\Models\\User', 10, '{\"title\":\"Security Update\",\"message\":\"Your profile security settings were successfully updated.\",\"icon\":\"fa-shield-alt\",\"action_url\":\"\\/profile\"}', NULL, '2025-12-22 20:19:28', '2025-12-22 20:19:28'),
('d4e0f10e-8ce9-4466-abde-8abdfc82f8de', 'App\\Notifications\\SecurityAlert', 'App\\Models\\User', 33, '{\"title\":\"Security Update\",\"message\":\"Your profile security settings were successfully updated.\",\"icon\":\"fa-shield-alt\",\"action_url\":\"\\/profile\"}', NULL, '2025-12-22 20:19:28', '2025-12-22 20:19:28'),
('d7a53f73-87f7-4c34-82bd-b821ef116a8d', 'App\\Notifications\\TaskAssigned', 'App\\Models\\User', 15, '{\"title\":\"New Task Assigned\",\"message\":\"You have been assigned a new high-priority project task.\",\"icon\":\"fa-tasks\",\"action_url\":\"\\/tasks\"}', '2025-12-22 20:49:28', '2025-12-21 20:49:28', '2025-12-21 20:49:28'),
('d8a34711-bdf5-4f07-9257-f22e23d3f674', 'App\\Notifications\\TaskAssigned', 'App\\Models\\User', 2, '{\"title\":\"New Task Assigned\",\"message\":\"You have been assigned a new high-priority project task.\",\"icon\":\"fa-tasks\",\"action_url\":\"\\/tasks\"}', '2025-12-22 20:49:28', '2025-12-21 20:49:28', '2025-12-21 20:49:28'),
('daedeae4-e994-4137-8de0-3efe54467f99', 'App\\Notifications\\SecurityAlert', 'App\\Models\\User', 28, '{\"title\":\"Security Update\",\"message\":\"Your profile security settings were successfully updated.\",\"icon\":\"fa-shield-alt\",\"action_url\":\"\\/profile\"}', NULL, '2025-12-22 20:19:28', '2025-12-22 20:19:28'),
('db6385f1-777a-4d64-ba15-40637b2764ac', 'App\\Notifications\\TaskAssigned', 'App\\Models\\User', 5, '{\"title\":\"New Task Assigned\",\"message\":\"You have been assigned a new high-priority project task.\",\"icon\":\"fa-tasks\",\"action_url\":\"\\/tasks\"}', '2025-12-22 20:49:28', '2025-12-21 20:49:28', '2025-12-21 20:49:28'),
('ddebd7d0-3b22-3b27-a556-d9878de93c09', 'App\\Notifications\\SystemAlert', 'App\\Models\\User', 34, '{\"title\":\"System Update 2\",\"message\":\"Est illo eaque qui fuga.\",\"icon\":\"fa-info-circle\",\"action_url\":\"#\"}', NULL, '2025-12-18 13:37:56', '2025-12-23 17:27:28'),
('df86f9c2-43ca-423d-8771-5bd8c30c8118', 'App\\Notifications\\SystemAlert', 'App\\Models\\User', 26, '{\"title\":\"New Task Assigned\",\"message\":\"You have been assigned to: System Setup.\",\"icon\":\"fa-tasks\",\"action_url\":\"http:\\/\\/127.0.0.1:8000\\/employee\\/appointments\",\"type\":\"system\"}', NULL, '2025-12-24 00:25:36', '2025-12-24 00:25:36'),
('e4b4916d-a506-4039-8d80-7931722294e7', 'App\\Notifications\\SecurityAlert', 'App\\Models\\User', 20, '{\"title\":\"Security Update\",\"message\":\"Your profile security settings were successfully updated.\",\"icon\":\"fa-shield-alt\",\"action_url\":\"\\/profile\"}', NULL, '2025-12-22 20:19:28', '2025-12-22 20:19:28'),
('e567848c-1ee0-4769-aa6f-69c02771eecb', 'App\\Notifications\\SystemAlert', 'App\\Models\\User', 22, '{\"title\":\"Welcome to eVuba\",\"message\":\"Thank you for joining our professional platform.\",\"icon\":\"fa-door-open\",\"action_url\":\"#\"}', NULL, '2025-12-20 20:49:28', '2025-12-20 20:49:28'),
('e5908f64-1ddb-4644-9c68-6071b85deae9', 'App\\Notifications\\SystemAlert', 'App\\Models\\User', 13, '{\"title\":\"Welcome to eVuba\",\"message\":\"Thank you for joining our professional platform.\",\"icon\":\"fa-door-open\",\"action_url\":\"#\"}', NULL, '2025-12-20 20:49:28', '2025-12-20 20:49:28'),
('e5b6bf32-6a30-473b-90f2-74215a03597c', 'App\\Notifications\\TaskAssigned', 'App\\Models\\User', 19, '{\"title\":\"New Task Assigned\",\"message\":\"You have been assigned a new high-priority project task.\",\"icon\":\"fa-tasks\",\"action_url\":\"\\/tasks\"}', '2025-12-22 20:49:28', '2025-12-21 20:49:28', '2025-12-21 20:49:28'),
('e81a81d3-bb3c-40eb-8b5e-4cc71e4af99b', 'App\\Notifications\\SystemAlert', 'App\\Models\\User', 9, '{\"title\":\"Welcome to eVuba\",\"message\":\"Thank you for joining our professional platform.\",\"icon\":\"fa-door-open\",\"action_url\":\"#\"}', NULL, '2025-12-20 20:49:28', '2025-12-20 20:49:28'),
('eb4afc45-dfed-435a-a262-b6df9b26d9b9', 'App\\Notifications\\TaskAssigned', 'App\\Models\\User', 33, '{\"title\":\"New Task Assigned\",\"message\":\"You have been assigned a new high-priority project task.\",\"icon\":\"fa-tasks\",\"action_url\":\"\\/tasks\"}', '2025-12-22 20:49:28', '2025-12-21 20:49:28', '2025-12-21 20:49:28'),
('ecf23f44-2269-46c2-b834-8810a7cd02fd', 'App\\Notifications\\SecurityAlert', 'App\\Models\\User', 17, '{\"title\":\"Security Update\",\"message\":\"Your profile security settings were successfully updated.\",\"icon\":\"fa-shield-alt\",\"action_url\":\"\\/profile\"}', NULL, '2025-12-22 20:19:28', '2025-12-22 20:19:28'),
('ed67d8cc-17f9-41bd-a24d-7f13040d8b4b', 'App\\Notifications\\SystemAlert', 'App\\Models\\User', 8, '{\"title\":\"Welcome to eVuba\",\"message\":\"Thank you for joining our professional platform.\",\"icon\":\"fa-door-open\",\"action_url\":\"#\"}', NULL, '2025-12-20 20:49:28', '2025-12-20 20:49:28'),
('ef02ffad-eb44-41fa-a380-2aac90f9f256', 'App\\Notifications\\SystemAlert', 'App\\Models\\User', 1, '{\"title\":\"Photo Updated\",\"message\":\"Your profile picture has been changed.\",\"icon\":\"fa-camera\",\"action_url\":\"http:\\/\\/127.0.0.1:8000\\/profile\",\"type\":\"system\"}', NULL, '2025-12-23 18:52:55', '2025-12-23 18:52:55'),
('efcade5f-3509-4673-8c49-71f23ccf8a79', 'App\\Notifications\\SystemAlert', 'App\\Models\\User', 11, '{\"title\":\"Welcome to eVuba\",\"message\":\"Thank you for joining our professional platform.\",\"icon\":\"fa-door-open\",\"action_url\":\"#\"}', NULL, '2025-12-20 20:49:28', '2025-12-20 20:49:28'),
('f38fc649-c026-46e9-8ffd-87c4c6733f21', 'App\\Notifications\\SystemAlert', 'App\\Models\\User', 17, '{\"title\":\"Welcome to eVuba\",\"message\":\"Thank you for joining our professional platform.\",\"icon\":\"fa-door-open\",\"action_url\":\"#\"}', NULL, '2025-12-20 20:49:28', '2025-12-20 20:49:28'),
('f4f04e37-f2fb-41f4-9323-c314c56ae82d', 'App\\Notifications\\SystemAlert', 'App\\Models\\User', 31, '{\"title\":\"Welcome to eVuba\",\"message\":\"Thank you for joining our professional platform.\",\"icon\":\"fa-door-open\",\"action_url\":\"#\"}', NULL, '2025-12-20 20:49:28', '2025-12-20 20:49:28'),
('f772ac37-4af0-4be3-b150-9d0c668cbf5c', 'App\\Notifications\\TaskAssigned', 'App\\Models\\User', 32, '{\"title\":\"New Task Assigned\",\"message\":\"You have been assigned a new high-priority project task.\",\"icon\":\"fa-tasks\",\"action_url\":\"\\/tasks\"}', '2025-12-22 20:49:28', '2025-12-21 20:49:28', '2025-12-21 20:49:28'),
('f7d543f7-2046-4048-b609-38c5fc69ac7b', 'App\\Notifications\\TaskAssigned', 'App\\Models\\User', 16, '{\"title\":\"New Task Assigned\",\"message\":\"You have been assigned a new high-priority project task.\",\"icon\":\"fa-tasks\",\"action_url\":\"\\/tasks\"}', '2025-12-22 20:49:28', '2025-12-21 20:49:28', '2025-12-21 20:49:28'),
('f92e5995-88f4-4c27-b663-1c75d7436d01', 'App\\Notifications\\SystemAlert', 'App\\Models\\User', 7, '{\"title\":\"Welcome to eVuba\",\"message\":\"Thank you for joining our professional platform.\",\"icon\":\"fa-door-open\",\"action_url\":\"#\"}', NULL, '2025-12-20 20:49:28', '2025-12-20 20:49:28'),
('fdd6e699-588f-4629-a432-b28db5dcc732', 'App\\Notifications\\TaskAssigned', 'App\\Models\\User', 14, '{\"title\":\"New Task Assigned\",\"message\":\"You have been assigned a new high-priority project task.\",\"icon\":\"fa-tasks\",\"action_url\":\"\\/tasks\"}', '2025-12-22 20:49:28', '2025-12-21 20:49:28', '2025-12-21 20:49:28'),
('fde4a918-dd7b-41c0-9004-17d0651cb6f2', 'App\\Notifications\\SecurityAlert', 'App\\Models\\User', 1, '{\"title\":\"Security Update\",\"message\":\"Your profile security settings were successfully updated.\",\"icon\":\"fa-shield-alt\",\"action_url\":\"\\/profile\"}', '2025-12-22 20:50:26', '2025-12-22 20:19:28', '2025-12-22 20:50:26'),
('ff29b682-1c09-482d-9ed3-77956e341389', 'App\\Notifications\\SystemAlert', 'App\\Models\\User', 1, '{\"title\":\"Welcome to eVuba\",\"message\":\"Thank you for joining our professional platform.\",\"icon\":\"fa-door-open\",\"action_url\":\"#\"}', '2025-12-22 20:50:26', '2025-12-20 20:49:28', '2025-12-22 20:50:26'),
('ff571244-9c28-4663-9f4e-adfcf58c2b3d', 'App\\Notifications\\SecurityAlert', 'App\\Models\\User', 18, '{\"title\":\"Security Update\",\"message\":\"Your profile security settings were successfully updated.\",\"icon\":\"fa-shield-alt\",\"action_url\":\"\\/profile\"}', NULL, '2025-12-22 20:19:28', '2025-12-22 20:19:28');

-- --------------------------------------------------------

--
-- Table structure for table `orders`
--

CREATE TABLE `orders` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `user_id` bigint(20) UNSIGNED NOT NULL,
  `product_id` bigint(20) UNSIGNED DEFAULT NULL,
  `product_name` varchar(255) NOT NULL,
  `quantity` int(11) NOT NULL,
  `price` decimal(10,2) NOT NULL,
  `status` enum('pending','processing','completed','cancelled') NOT NULL DEFAULT 'pending',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `orders`
--

INSERT INTO `orders` (`id`, `user_id`, `product_id`, `product_name`, `quantity`, `price`, `status`, `created_at`, `updated_at`) VALUES
(1, 29, NULL, 'Premium Service Package', 1, 5000.00, 'pending', '2025-12-22 18:33:23', '2025-12-22 18:33:23'),
(2, 29, NULL, 'Premium Service Package', 1, 5000.00, 'completed', '2025-12-22 18:37:09', '2025-12-23 13:37:19'),
(3, 29, NULL, 'Premium Service Package', 1, 5000.00, 'completed', '2025-12-22 18:38:23', '2025-12-22 19:26:08'),
(5, 34, 4, 'Logitech Wireless Mouse', 3, 25000.00, 'cancelled', '2025-12-23 11:50:54', '2025-12-23 19:28:46'),
(6, 34, 4, 'Logitech Wireless Mouse', 4, 25000.00, 'cancelled', '2025-09-24 13:35:17', '2025-12-23 17:22:41'),
(7, 34, 7, 'Network Maintenance (Monthly)', 4, 150000.00, 'completed', '2025-11-24 02:50:18', '2025-12-23 17:22:41'),
(8, 34, 7, 'Network Maintenance (Monthly)', 4, 150000.00, 'cancelled', '2025-09-29 05:20:05', '2025-12-23 17:22:41'),
(9, 34, 5, 'External SSD 1TB', 5, 120000.00, 'processing', '2025-10-31 22:36:08', '2025-12-23 17:22:41'),
(10, 34, 8, 'Computer', 4, 0.00, 'processing', '2025-10-20 07:43:11', '2025-12-23 17:26:00'),
(11, 34, 5, 'External SSD 1TB', 1, 120000.00, 'pending', '2025-09-27 13:45:14', '2025-12-23 17:26:00'),
(12, 34, 4, 'Logitech Wireless Mouse', 5, 25000.00, 'completed', '2025-10-23 13:15:57', '2025-12-23 17:26:00'),
(13, 34, 4, 'Logitech Wireless Mouse', 1, 25000.00, 'pending', '2025-12-17 18:08:16', '2025-12-23 17:26:00'),
(14, 34, 4, 'Logitech Wireless Mouse', 2, 25000.00, 'pending', '2025-10-10 19:43:27', '2025-12-23 17:26:00'),
(15, 34, 7, 'Network Maintenance (Monthly)', 5, 150000.00, 'completed', '2025-09-24 19:34:27', '2025-12-23 17:27:28'),
(16, 34, 8, 'Computer', 2, 0.00, 'processing', '2025-12-01 15:49:07', '2025-12-23 17:27:28'),
(17, 34, 3, 'MacBook Air M2', 3, 1250000.00, 'cancelled', '2025-11-10 15:54:45', '2025-12-23 17:27:28'),
(18, 34, 8, 'Computer', 5, 0.00, 'cancelled', '2025-10-14 02:27:13', '2025-12-23 17:27:28'),
(19, 34, 2, 'Dell Latitude 5420', 2, 850000.00, 'pending', '2025-10-09 16:42:35', '2025-12-23 17:27:28'),
(22, 1, 3, 'MacBook Air M2', 1, 0.00, 'processing', '2025-12-23 20:15:02', '2026-01-05 02:17:10');

-- --------------------------------------------------------

--
-- Table structure for table `pages`
--

CREATE TABLE `pages` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `slug` varchar(255) DEFAULT NULL,
  `description` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

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
-- Table structure for table `permissions`
--

CREATE TABLE `permissions` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `guard_name` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `permissions`
--

INSERT INTO `permissions` (`id`, `name`, `guard_name`, `created_at`, `updated_at`) VALUES
(1, 'view users', 'web', '2025-12-22 02:11:19', '2025-12-22 02:11:19'),
(2, 'create users', 'web', '2025-12-22 02:11:19', '2025-12-22 02:11:19'),
(3, 'edit users', 'web', '2025-12-22 02:11:19', '2025-12-22 02:11:19'),
(4, 'delete users', 'web', '2025-12-22 02:11:19', '2025-12-22 02:11:19'),
(5, 'view customers', 'web', '2025-12-22 02:11:19', '2025-12-22 02:11:19'),
(6, 'create customers', 'web', '2025-12-22 02:11:19', '2025-12-22 02:11:19'),
(7, 'edit customers', 'web', '2025-12-22 02:11:19', '2025-12-22 02:11:19'),
(8, 'delete customers', 'web', '2025-12-22 02:11:19', '2025-12-22 02:11:19'),
(9, 'view employees', 'web', '2025-12-22 02:11:19', '2025-12-22 02:11:19'),
(10, 'create employees', 'web', '2025-12-22 02:11:19', '2025-12-22 02:11:19'),
(11, 'edit employees', 'web', '2025-12-22 02:11:19', '2025-12-22 02:11:19'),
(12, 'delete employees', 'web', '2025-12-22 02:11:19', '2025-12-22 02:11:19'),
(13, 'view products', 'web', '2025-12-22 02:11:19', '2025-12-22 02:11:19'),
(14, 'create products', 'web', '2025-12-22 02:11:19', '2025-12-22 02:11:19'),
(15, 'edit products', 'web', '2025-12-22 02:11:19', '2025-12-22 02:11:19'),
(16, 'delete products', 'web', '2025-12-22 02:11:19', '2025-12-22 02:11:19'),
(17, 'view orders', 'web', '2025-12-22 02:11:19', '2025-12-22 02:11:19'),
(18, 'create orders', 'web', '2025-12-22 02:11:19', '2025-12-22 02:11:19'),
(19, 'edit orders', 'web', '2025-12-22 02:11:19', '2025-12-22 02:11:19'),
(20, 'delete orders', 'web', '2025-12-22 02:11:19', '2025-12-22 02:11:19'),
(21, 'view appointments', 'web', '2025-12-22 02:11:19', '2025-12-22 02:11:19'),
(22, 'create appointments', 'web', '2025-12-22 02:11:19', '2025-12-22 02:11:19'),
(23, 'edit appointments', 'web', '2025-12-22 02:11:19', '2025-12-22 02:11:19'),
(24, 'delete appointments', 'web', '2025-12-22 02:11:19', '2025-12-22 02:11:19'),
(25, 'view bookings', 'web', '2025-12-22 02:11:19', '2025-12-22 02:11:19'),
(26, 'create bookings', 'web', '2025-12-22 02:11:19', '2025-12-22 02:11:19'),
(27, 'edit bookings', 'web', '2025-12-22 02:11:19', '2025-12-22 02:11:19'),
(28, 'delete bookings', 'web', '2025-12-22 02:11:19', '2025-12-22 02:11:19'),
(29, 'view stock', 'web', '2025-12-22 02:11:19', '2025-12-22 02:11:19'),
(30, 'create stock', 'web', '2025-12-22 02:11:19', '2025-12-22 02:11:19'),
(31, 'edit stock', 'web', '2025-12-22 02:11:19', '2025-12-22 02:11:19'),
(32, 'delete stock', 'web', '2025-12-22 02:11:19', '2025-12-22 02:11:19'),
(33, 'view tickets', 'web', '2025-12-22 02:11:19', '2025-12-22 02:11:19'),
(34, 'create tickets', 'web', '2025-12-22 02:11:19', '2025-12-22 02:11:19'),
(35, 'edit tickets', 'web', '2025-12-22 02:11:19', '2025-12-22 02:11:19'),
(36, 'delete tickets', 'web', '2025-12-22 02:11:19', '2025-12-22 02:11:19'),
(37, 'view reports', 'web', '2025-12-22 02:11:19', '2025-12-22 02:11:19'),
(38, 'generate reports', 'web', '2025-12-22 02:11:19', '2025-12-22 02:11:19'),
(39, 'manage settings', 'web', '2025-12-22 02:11:19', '2025-12-22 02:11:19'),
(40, 'manage roles', 'web', '2025-12-22 02:11:19', '2025-12-22 02:11:19');

-- --------------------------------------------------------

--
-- Table structure for table `products`
--

CREATE TABLE `products` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `unit_price` decimal(10,2) NOT NULL DEFAULT 0.00,
  `description` text DEFAULT NULL,
  `category_id` bigint(20) UNSIGNED DEFAULT NULL,
  `product_code` varchar(255) DEFAULT NULL,
  `image` varchar(255) DEFAULT NULL,
  `status` enum('published','unpublished') NOT NULL DEFAULT 'published',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `products`
--

INSERT INTO `products` (`id`, `name`, `unit_price`, `description`, `category_id`, `product_code`, `image`, `status`, `created_at`, `updated_at`) VALUES
(2, 'Dell Latitude 5420', 850000.00, NULL, 3, 'PC-001', NULL, 'published', '2025-12-22 12:25:18', '2025-12-22 16:07:06'),
(3, 'MacBook Air M2', 1250000.00, NULL, NULL, 'PC-002', NULL, 'published', '2025-12-22 12:25:18', '2025-12-22 12:25:18'),
(4, 'Logitech Wireless Mouse', 25000.00, NULL, NULL, 'ACC-001', NULL, 'published', '2025-12-22 12:25:18', '2025-12-22 12:25:18'),
(5, 'External SSD 1TB', 120000.00, NULL, NULL, 'ACC-002', NULL, 'published', '2025-12-22 12:25:18', '2025-12-22 12:25:18'),
(7, 'Network Maintenance (Monthly)', 150000.00, 'Corrected', 1, 'SRV-002', NULL, 'published', '2025-12-22 12:25:18', '2025-12-22 16:06:34'),
(8, 'Computer', 0.00, 'Dell', 4, '1112', 'products/GjFB5LXXCVtSGX5AVCzB9lVU6bxXAWocLHgZxJcR.jpg', 'published', '2025-12-22 19:23:43', '2025-12-23 19:55:01');

-- --------------------------------------------------------

--
-- Table structure for table `reports`
--

CREATE TABLE `reports` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `user_id` bigint(20) UNSIGNED NOT NULL,
  `title` varchar(255) NOT NULL,
  `description` text DEFAULT NULL,
  `status` varchar(255) NOT NULL DEFAULT 'pending',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `reports`
--

INSERT INTO `reports` (`id`, `user_id`, `title`, `description`, `status`, `created_at`, `updated_at`) VALUES
(1, 1, 'Activity Log Week 1', 'This report covers all operational activities and outcomes for week 1', 'pending', '2025-12-23 15:44:15', '2025-12-23 15:44:15'),
(2, 1, 'Activity Log Week 2', 'This report covers all operational activities and outcomes for week 2', 'pending', '2025-12-23 15:44:15', '2025-12-23 15:44:15'),
(3, 1, 'Activity Log Week 3', 'This report covers all operational activities and outcomes for week 3', 'pending', '2025-12-23 15:44:15', '2025-12-23 15:44:15'),
(4, 1, 'Activity Log Week 4', 'This report covers all operational activities and outcomes for week 4', 'pending', '2025-12-23 15:44:15', '2025-12-23 15:44:15'),
(6, 1, 'Activity Log Week 1', 'This report covers all operational activities and outcomes for week 9', 'pending', '2025-12-23 15:44:55', '2025-12-23 16:31:34'),
(7, 1, 'Activity Log Week 2', 'This report covers all operational activities and outcomes for week 2', 'pending', '2025-12-23 15:44:55', '2025-12-23 15:44:55'),
(8, 1, 'Activity Log Week 3', 'This report covers all operational activities and outcomes for week 3', 'pending', '2025-12-23 15:44:55', '2025-12-23 15:44:55'),
(9, 1, 'Activity Log Week 4', 'This report covers all operational activities and outcomes for week 4', 'pending', '2025-12-23 15:44:55', '2025-12-23 15:44:55'),
(10, 1, 'Activity Log Week 5', 'This report covers all operational activities and outcomes for week 5', 'pending', '2025-12-23 15:44:55', '2025-12-23 15:44:55');

-- --------------------------------------------------------

--
-- Table structure for table `roles`
--

CREATE TABLE `roles` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `guard_name` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `roles`
--

INSERT INTO `roles` (`id`, `name`, `guard_name`, `created_at`, `updated_at`) VALUES
(1, 'admin', 'web', '2025-12-22 02:11:19', '2025-12-22 02:11:19'),
(2, 'manager', 'web', '2025-12-22 02:11:19', '2025-12-22 02:11:19'),
(3, 'employee', 'web', '2025-12-22 02:11:19', '2025-12-22 02:11:19'),
(4, 'customer', 'web', '2025-12-22 02:11:19', '2025-12-22 02:11:19');

-- --------------------------------------------------------

--
-- Table structure for table `role_has_permissions`
--

CREATE TABLE `role_has_permissions` (
  `permission_id` bigint(20) UNSIGNED NOT NULL,
  `role_id` bigint(20) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `role_has_permissions`
--

INSERT INTO `role_has_permissions` (`permission_id`, `role_id`) VALUES
(1, 1),
(1, 2),
(2, 1),
(2, 2),
(3, 1),
(3, 2),
(4, 1),
(5, 1),
(5, 2),
(6, 1),
(6, 2),
(7, 1),
(7, 2),
(8, 1),
(9, 1),
(9, 2),
(10, 1),
(11, 1),
(11, 2),
(12, 1),
(13, 1),
(13, 2),
(13, 3),
(13, 4),
(14, 1),
(14, 2),
(15, 1),
(15, 2),
(16, 1),
(17, 1),
(17, 2),
(17, 3),
(17, 4),
(18, 1),
(18, 4),
(19, 1),
(19, 2),
(20, 1),
(21, 1),
(21, 2),
(21, 3),
(21, 4),
(22, 1),
(22, 2),
(22, 4),
(23, 1),
(23, 2),
(24, 1),
(25, 1),
(25, 2),
(25, 3),
(25, 4),
(26, 1),
(26, 2),
(26, 4),
(27, 1),
(27, 2),
(28, 1),
(29, 1),
(29, 2),
(30, 1),
(30, 2),
(31, 1),
(31, 2),
(32, 1),
(33, 1),
(33, 2),
(33, 3),
(33, 4),
(34, 1),
(34, 3),
(34, 4),
(35, 1),
(35, 2),
(35, 3),
(36, 1),
(37, 1),
(37, 2),
(38, 1),
(38, 2),
(39, 1),
(40, 1);

-- --------------------------------------------------------

--
-- Table structure for table `role_page_permission`
--

CREATE TABLE `role_page_permission` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `role_id` bigint(20) UNSIGNED NOT NULL,
  `page_id` bigint(20) UNSIGNED NOT NULL,
  `permission_id` bigint(20) UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `sales`
--

CREATE TABLE `sales` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `product_id` bigint(20) UNSIGNED DEFAULT NULL,
  `customer_id` bigint(20) UNSIGNED DEFAULT NULL,
  `user_id` bigint(20) UNSIGNED DEFAULT NULL,
  `quantity` int(11) NOT NULL DEFAULT 1,
  `unit_price` decimal(10,2) NOT NULL,
  `total_amount` decimal(10,2) NOT NULL,
  `sale_date` date NOT NULL,
  `payment_method` varchar(255) DEFAULT NULL,
  `status` enum('completed','pending','cancelled') NOT NULL DEFAULT 'completed',
  `notes` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `sales`
--

INSERT INTO `sales` (`id`, `product_id`, `customer_id`, `user_id`, `quantity`, `unit_price`, `total_amount`, `sale_date`, `payment_method`, `status`, `notes`, `created_at`, `updated_at`) VALUES
(1, 2, 7, 1, 1, 21341.00, 21341.00, '2025-12-25', NULL, 'completed', NULL, '2025-12-22 17:24:28', '2025-12-22 17:24:28');

-- --------------------------------------------------------

--
-- Table structure for table `schedules`
--

CREATE TABLE `schedules` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `title` varchar(255) NOT NULL,
  `start_time` datetime NOT NULL,
  `end_time` datetime NOT NULL,
  `description` text DEFAULT NULL,
  `user_id` bigint(20) UNSIGNED DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `services`
--

CREATE TABLE `services` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `description` text DEFAULT NULL,
  `employee_id` bigint(20) UNSIGNED DEFAULT NULL,
  `image` varchar(255) DEFAULT NULL,
  `is_published` tinyint(1) NOT NULL DEFAULT 1,
  `price` decimal(10,2) DEFAULT NULL,
  `duration` int(11) DEFAULT NULL COMMENT 'Duration in minutes',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `services`
--

INSERT INTO `services` (`id`, `name`, `description`, `employee_id`, `image`, `is_published`, `price`, `duration`, `created_at`, `updated_at`) VALUES
(2, 'Networking', 'Network and Camera Installations', 2, 'services/DrgXtgJTqHqfkYaJcstUVj3VUNTcsDOAUV03k8oK.jpg', 1, NULL, NULL, '2025-12-22 15:35:33', '2025-12-23 12:57:43'),
(3, 'Photography', 'Gufotora', 26, 'services/R4NghjRXMcxrPpZOj31NoF4oHw1W7r9HxX8Kvg7c.png', 1, NULL, NULL, '2025-12-23 14:18:16', '2025-12-23 14:18:16');

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
('GHyrPdM4B98euzqsILqpxYWOqpzLTlwxbP1CtuDZ', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36', 'YToyOntzOjY6Il90b2tlbiI7czo0MDoiZm5xV2RsZVJjaGNxWWNtVTczVkVSQ3J0M0paVm1Va1JqTkZhbVdwNCI7czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319fQ==', 1767553748),
('lPBkDWDu9S0xUbdQiYVuzJk9a41mSgddXW36ip8j', 35, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36', 'YTo1OntzOjY6Il90b2tlbiI7czo0MDoiQ0lyTHZMUjlIWWM0WkljV0dqR2s1Y0pCQVdJTkJvdUE1RGhEWWhRRyI7czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319czozOiJ1cmwiO2E6MTp7czo4OiJpbnRlbmRlZCI7czozNzoiaHR0cDovLzEyNy4wLjAuMTo4MDAwL2FkbWluL2Rhc2hib2FyZCI7fXM6OToiX3ByZXZpb3VzIjthOjI6e3M6MzoidXJsIjtzOjIxOiJodHRwOi8vMTI3LjAuMC4xOjgwMDAiO3M6NToicm91dGUiO3M6NDoiaG9tZSI7fXM6NTA6ImxvZ2luX3dlYl81OWJhMzZhZGRjMmIyZjk0MDE1ODBmMDE0YzdmNThlYTRlMzA5ODlkIjtpOjM1O30=', 1767556304),
('LYenoishwYBMS97yuKIcGEONdfFLALq4UIzvc8qv', 1, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36', 'YTo0OntzOjY6Il90b2tlbiI7czo0MDoib3BBZEladlZxTTdaUzd5YmV3dUpCa2tmYjVreEc0U1FvQUhnMzFKMCI7czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6NDU6Imh0dHA6Ly8xMjcuMC4wLjE6ODAwMC9hZG1pbi9vcmRlcnM/cGVyX3BhZ2U9NSI7czo1OiJyb3V0ZSI7czoxODoiYWRtaW4ub3JkZXJzLmluZGV4Ijt9czo1MDoibG9naW5fd2ViXzU5YmEzNmFkZGMyYjJmOTQwMTU4MGYwMTRjN2Y1OGVhNGUzMDk4OWQiO2k6MTt9', 1767594459),
('pt2m5qxcZWaZPI5Vn1sU8lMWslwRavFIyVe9fAzt', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36', 'YTozOntzOjY6Il90b2tlbiI7czo0MDoiQ0c5V3dYdkh4eWFEb1NzdmZNM2g0a2FSWmhOU29NYjNkYWhGMDQybCI7czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6MzI6Imh0dHA6Ly8xMjcuMC4wLjE6ODAwMC9hdXRoL2xvZ2luIjtzOjU6InJvdXRlIjtzOjEwOiJhdXRoLmxvZ2luIjt9fQ==', 1767627675);

-- --------------------------------------------------------

--
-- Table structure for table `settings`
--

CREATE TABLE `settings` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `key` varchar(255) NOT NULL,
  `value` text DEFAULT NULL,
  `group` varchar(255) NOT NULL DEFAULT 'general',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `settings`
--

INSERT INTO `settings` (`id`, `key`, `value`, `group`, `created_at`, `updated_at`) VALUES
(1, 'app_name', 'eVubaConnect', 'general', '2025-12-22 15:05:10', '2025-12-23 19:44:10'),
(2, 'app_desc', 'Enterprise Resource Planning & Management System', 'general', '2025-12-22 15:05:10', '2025-12-22 15:05:10'),
(3, 'admin_email', 'admin@evuba.com', 'general', '2025-12-22 15:05:10', '2025-12-22 15:05:10'),
(4, 'currency_symbol', 'frw', 'general', '2025-12-22 15:05:10', '2025-12-23 19:44:10'),
(5, 'company_name', 'eVuba Solutions Inc.', 'business', '2025-12-22 15:05:10', '2025-12-22 15:05:10'),
(6, 'company_address', '123 Tech Park, Innovation Blvd, Silicon Valley, CA', 'business', '2025-12-22 15:05:10', '2025-12-22 15:05:10'),
(7, 'company_phone', '+1 (555) 123-4567', 'business', '2025-12-22 15:05:10', '2025-12-22 15:05:10'),
(8, 'tax_id', 'TAX-889977-US', 'business', '2025-12-22 15:05:10', '2025-12-22 15:05:10'),
(9, 'module_stock', '1', 'modules', '2025-12-22 15:05:10', '2025-12-22 15:05:10'),
(10, 'module_invoices', '1', 'modules', '2025-12-22 15:05:10', '2025-12-22 15:05:10'),
(11, 'module_support', '1', 'modules', '2025-12-22 15:05:10', '2025-12-22 15:05:10'),
(12, 'module_hrm', '1', 'modules', '2025-12-22 15:05:10', '2025-12-22 15:05:10'),
(13, 'notify_new_order', '1', 'notifications', '2025-12-22 15:05:10', '2025-12-22 15:05:10'),
(14, 'notify_low_stock', '1', 'notifications', '2025-12-22 15:05:10', '2025-12-22 15:05:10'),
(15, 'notify_new_message', '1', 'notifications', '2025-12-22 15:05:10', '2025-12-22 15:05:10'),
(16, 'session_timeout', '125', 'security', '2025-12-22 15:05:10', '2025-12-22 15:08:20'),
(17, 'strong_password', '1', 'security', '2025-12-22 15:05:10', '2025-12-22 15:05:10'),
(18, 'group_app_name', 'general', 'general', '2025-12-22 15:07:49', '2025-12-22 15:07:49'),
(19, 'group_app_desc', 'general', 'general', '2025-12-22 15:07:49', '2025-12-22 15:07:49'),
(20, 'group_admin_email', 'general', 'general', '2025-12-22 15:07:49', '2025-12-22 15:07:49'),
(21, 'group_currency_symbol', 'general', 'general', '2025-12-22 15:07:49', '2025-12-22 15:07:49'),
(22, 'group_company_name', 'business', 'general', '2025-12-22 15:07:49', '2025-12-22 15:07:49'),
(23, 'group_company_address', 'business', 'general', '2025-12-22 15:07:49', '2025-12-22 15:07:49'),
(24, 'group_company_phone', 'business', 'general', '2025-12-22 15:07:49', '2025-12-22 15:07:49'),
(25, 'group_tax_id', 'business', 'general', '2025-12-22 15:07:49', '2025-12-22 15:07:49'),
(26, 'group_module_stock', 'modules', 'general', '2025-12-22 15:07:49', '2025-12-22 15:07:49'),
(27, 'group_module_invoices', 'modules', 'general', '2025-12-22 15:07:49', '2025-12-22 15:07:49'),
(28, 'group_module_support', 'modules', 'general', '2025-12-22 15:07:49', '2025-12-22 15:07:49'),
(29, 'group_notify_new_order', 'notifications', 'general', '2025-12-22 15:07:49', '2025-12-22 15:07:49'),
(30, 'group_notify_low_stock', 'notifications', 'general', '2025-12-22 15:07:49', '2025-12-22 15:07:49'),
(31, 'group_notify_new_message', 'notifications', 'general', '2025-12-22 15:07:49', '2025-12-22 15:07:49'),
(32, 'group_session_timeout', 'security', 'general', '2025-12-22 15:07:49', '2025-12-22 15:07:49'),
(33, 'group_strong_password', 'security', 'general', '2025-12-22 15:07:49', '2025-12-22 15:07:49');

-- --------------------------------------------------------

--
-- Table structure for table `stock`
--

CREATE TABLE `stock` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `product_id` bigint(20) UNSIGNED NOT NULL,
  `type` enum('in','out') NOT NULL DEFAULT 'in',
  `quantity` int(11) NOT NULL DEFAULT 0,
  `unit_price` decimal(10,2) NOT NULL DEFAULT 0.00,
  `supplier_id` bigint(20) UNSIGNED DEFAULT NULL,
  `date` date NOT NULL,
  `notes` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `stock_in`
--

CREATE TABLE `stock_in` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `product_id` bigint(20) UNSIGNED NOT NULL,
  `supplier_id` bigint(20) UNSIGNED DEFAULT NULL,
  `user_id` bigint(20) UNSIGNED DEFAULT NULL,
  `quantity` int(11) NOT NULL,
  `unit_cost` decimal(10,2) NOT NULL,
  `total_cost` decimal(10,2) NOT NULL,
  `stock_in_date` date NOT NULL,
  `type` varchar(255) DEFAULT NULL,
  `note` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `stock_in`
--

INSERT INTO `stock_in` (`id`, `product_id`, `supplier_id`, `user_id`, `quantity`, `unit_cost`, `total_cost`, `stock_in_date`, `type`, `note`, `created_at`, `updated_at`) VALUES
(2, 2, 1, 1, 43, 21341.00, 917663.00, '2025-12-10', 'purchase', 'Initial inventory stock', '2025-12-22 17:11:22', '2025-12-22 17:11:22'),
(3, 3, 3, 1, 87, 462311.00, 40221057.00, '2025-12-09', 'purchase', 'Initial inventory stock', '2025-12-22 17:11:22', '2025-12-22 17:11:22'),
(4, 4, 3, 1, 86, 358485.00, 30829710.00, '2025-12-15', 'purchase', 'Initial inventory stock', '2025-12-22 17:11:22', '2025-12-22 17:11:22'),
(5, 5, 4, 1, 89, 89814.00, 7993446.00, '2025-12-16', 'purchase', 'Initial inventory stock', '2025-12-22 17:11:22', '2025-12-22 17:11:22'),
(7, 7, 4, 1, 24, 69430.00, 1666320.00, '2025-12-08', 'purchase', 'Initial inventory stock', '2025-12-22 17:11:22', '2025-12-22 17:11:22'),
(9, 5, 3, 1, 5, 0.01, 0.05, '2025-12-23', 'donation', NULL, '2025-12-23 13:26:38', '2025-12-23 13:26:38');

-- --------------------------------------------------------

--
-- Table structure for table `stock_out`
--

CREATE TABLE `stock_out` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `product_id` bigint(20) UNSIGNED NOT NULL,
  `customer_id` bigint(20) UNSIGNED DEFAULT NULL,
  `supplier_id` bigint(20) UNSIGNED DEFAULT NULL,
  `user_id` bigint(20) UNSIGNED DEFAULT NULL,
  `quantity` int(11) NOT NULL,
  `unit_price` decimal(10,2) NOT NULL,
  `total_price` decimal(10,2) NOT NULL,
  `stock_in_date` date DEFAULT NULL,
  `stock_out_date` date NOT NULL,
  `type` varchar(255) DEFAULT NULL,
  `note` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `stock_out`
--

INSERT INTO `stock_out` (`id`, `product_id`, `customer_id`, `supplier_id`, `user_id`, `quantity`, `unit_price`, `total_price`, `stock_in_date`, `stock_out_date`, `type`, `note`, `created_at`, `updated_at`) VALUES
(8, 2, 6, NULL, 1, 1, 32011.50, 32011.50, NULL, '2025-12-16', 'sale', 'Customer purchase', '2025-12-22 17:11:22', '2025-12-22 17:11:22'),
(20, 4, 14, NULL, 1, 4, 537727.50, 2150910.00, NULL, '2025-12-17', 'sale', 'Customer purchase', '2025-12-22 17:11:22', '2025-12-22 17:11:22'),
(23, 4, 16, NULL, 1, 2, 537727.50, 1075455.00, NULL, '2025-12-17', 'sale', 'Customer purchase', '2025-12-22 17:11:22', '2025-12-22 17:11:22'),
(26, 5, 14, NULL, 1, 1, 134721.00, 134721.00, NULL, '2025-12-18', 'sale', 'Customer purchase', '2025-12-22 17:11:22', '2025-12-22 17:11:22'),
(28, 5, 18, NULL, 1, 3, 134721.00, 404163.00, NULL, '2025-12-15', 'sale', 'Customer purchase', '2025-12-22 17:11:22', '2025-12-22 17:11:22'),
(43, 2, 7, NULL, 1, 1, 21341.00, 21341.00, NULL, '2025-12-25', 'sale', NULL, '2025-12-22 17:24:28', '2025-12-22 17:24:28'),
(45, 2, 34, NULL, 1, 2, 21341.00, 42682.00, NULL, '2025-12-22', 'sale', 'Invoice: INV-20251222-006', '2025-12-23 05:30:18', '2025-12-23 05:30:18'),
(46, 5, 34, NULL, 1, 1, 89814.00, 89814.00, NULL, '2025-12-22', 'sale', 'Invoice: INV-20251222-006', '2025-12-23 05:30:18', '2025-12-23 05:30:18'),
(47, 7, 34, NULL, 1, 1, 69430.00, 69430.00, NULL, '2025-12-22', 'sale', 'Invoice: INV-20251222-006', '2025-12-23 05:30:18', '2025-12-23 05:30:18'),
(48, 3, 27, NULL, 1, 3, 462311.00, 1386933.00, NULL, '2025-12-23', 'sale', 'Invoice: INV-20251223-001', '2025-12-23 19:11:51', '2025-12-23 19:11:51'),
(49, 7, 27, NULL, 1, 3, 69430.00, 208290.00, NULL, '2025-12-23', 'sale', 'Invoice: INV-20251223-001', '2025-12-23 19:11:51', '2025-12-23 19:11:51'),
(50, 5, 27, NULL, 1, 3, 85036.66, 255109.98, NULL, '2025-12-23', 'sale', 'Invoice: INV-20251223-001', '2025-12-23 19:11:51', '2025-12-23 19:11:51'),
(51, 3, 27, NULL, 1, 1, 462311.00, 462311.00, NULL, '2025-12-23', 'sale', 'Invoice: INV-20251223-001', '2025-12-23 19:11:51', '2025-12-23 19:11:51');

-- --------------------------------------------------------

--
-- Table structure for table `subscribers`
--

CREATE TABLE `subscribers` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `email` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `subscribers`
--

INSERT INTO `subscribers` (`id`, `email`, `created_at`, `updated_at`) VALUES
(1, 'innocentntakir@gmail.com', '2025-12-22 02:08:23', '2025-12-22 02:08:23'),
(2, 'inonecdreams@gmail.com', '2025-12-23 19:39:29', '2025-12-23 19:39:29');

-- --------------------------------------------------------

--
-- Table structure for table `suppliers`
--

CREATE TABLE `suppliers` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `contact` varchar(255) DEFAULT NULL,
  `email` varchar(255) DEFAULT NULL,
  `address` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `suppliers`
--

INSERT INTO `suppliers` (`id`, `name`, `contact`, `email`, `address`, `created_at`, `updated_at`) VALUES
(1, 'UMULISA Kolombe', '0787832222', 'lolombe@gmail.com', 'Rebero', '2025-12-22 16:16:39', '2025-12-22 16:16:39'),
(2, 'Tech Suppliers Ltd', NULL, 'tech@supplier.com', NULL, '2025-12-22 17:10:38', '2025-12-22 17:10:38'),
(3, 'Fashion Wholesale', NULL, 'fashion@supplier.com', NULL, '2025-12-22 17:10:38', '2025-12-22 17:10:38'),
(4, 'Food Distributors', NULL, 'food@supplier.com', NULL, '2025-12-22 17:10:38', '2025-12-22 17:10:38'),
(5, 'Umwiza Umwali', '0787832490', 'umu@gmail.com', 'Kigali Nyamirambo', '2025-12-23 13:20:18', '2025-12-23 13:20:18');

-- --------------------------------------------------------

--
-- Table structure for table `support_categories`
--

CREATE TABLE `support_categories` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `description` text DEFAULT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `support_categories`
--

INSERT INTO `support_categories` (`id`, `name`, `description`, `is_active`, `created_at`, `updated_at`) VALUES
(1, 'Maintenance', 'Computer maintenance', 1, '2025-12-22 13:53:17', '2025-12-22 13:53:17'),
(2, 'Technical', NULL, 1, '2025-12-23 17:22:41', '2025-12-23 17:22:41'),
(3, 'Billing', NULL, 1, '2025-12-23 17:22:41', '2025-12-23 17:22:41'),
(4, 'General', NULL, 1, '2025-12-23 17:22:41', '2025-12-23 17:22:41'),
(5, 'Feature Request', NULL, 1, '2025-12-23 17:22:41', '2025-12-23 17:22:41');

-- --------------------------------------------------------

--
-- Table structure for table `support_tickets`
--

CREATE TABLE `support_tickets` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `user_id` bigint(20) UNSIGNED NOT NULL,
  `subject` varchar(255) NOT NULL,
  `message` text NOT NULL,
  `status` enum('open','in_progress','closed') NOT NULL DEFAULT 'open',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `tasks`
--

CREATE TABLE `tasks` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `user_id` bigint(20) UNSIGNED NOT NULL,
  `title` varchar(255) NOT NULL,
  `description` text DEFAULT NULL,
  `status` enum('pending','in_progress','completed') NOT NULL DEFAULT 'pending',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `tasks`
--

INSERT INTO `tasks` (`id`, `user_id`, `title`, `description`, `status`, `created_at`, `updated_at`) VALUES
(1, 1, 'Update Inventory', 'Detailed task description for Update Inventory. This is an important operacional requirement.', 'completed', '2025-12-23 15:44:15', '2025-12-23 15:44:15'),
(2, 1, 'Follow up with Clients', 'Detailed task description for Follow up with Clients. This is an important operacional requirement.', 'completed', '2025-12-23 15:44:15', '2025-12-23 15:44:15'),
(3, 1, 'Prepare Weekly Report', 'Detailed task description for Prepare Weekly Report. This is an important', 'completed', '2025-12-23 15:44:15', '2025-12-23 16:25:20'),
(4, 1, 'Audit Stock Out', 'Detailed task description for Audit Stock Out. This is an important operacional requirement.', 'completed', '2025-12-23 15:44:15', '2025-12-23 20:02:32'),
(5, 1, 'Coordinate with Delivery', 'Detailed task description for Coordinate with Delivery. This is an important operacional requirement.', 'completed', '2025-12-23 15:44:15', '2025-12-23 20:03:33'),
(9, 1, 'Audit Stock Out', 'Detailed task description for Audit Stock Out. This is an important operacional requirement.', 'completed', '2025-12-23 15:44:55', '2025-12-23 15:44:55'),
(10, 1, 'Coordinate with Delivery', 'Detailed task description for Coordinate with Delivery. This is an important operacional requirement.', 'completed', '2025-12-23 15:44:55', '2025-12-23 15:44:55');

-- --------------------------------------------------------

--
-- Table structure for table `teams`
--

CREATE TABLE `teams` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `description` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `team_members`
--

CREATE TABLE `team_members` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `team_id` bigint(20) UNSIGNED NOT NULL,
  `user_id` bigint(20) UNSIGNED DEFAULT NULL,
  `name` varchar(255) DEFAULT NULL,
  `role` varchar(255) DEFAULT NULL,
  `position` varchar(255) DEFAULT NULL,
  `email` varchar(255) DEFAULT NULL,
  `phone` varchar(255) DEFAULT NULL,
  `department` varchar(255) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `tickets`
--

CREATE TABLE `tickets` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `ticket_no` varchar(255) NOT NULL,
  `customer_id` bigint(20) UNSIGNED DEFAULT NULL,
  `category_id` bigint(20) UNSIGNED DEFAULT NULL,
  `subject` varchar(255) NOT NULL,
  `description` text NOT NULL,
  `attachment` varchar(255) DEFAULT NULL,
  `status` enum('open','in_progress','resolved','closed') NOT NULL DEFAULT 'open',
  `assigned_to` bigint(20) UNSIGNED DEFAULT NULL,
  `priority` enum('low','medium','high','urgent') NOT NULL DEFAULT 'medium',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `tickets`
--

INSERT INTO `tickets` (`id`, `ticket_no`, `customer_id`, `category_id`, `subject`, `description`, `attachment`, `status`, `assigned_to`, `priority`, `created_at`, `updated_at`) VALUES
(1, 'TCK-69491DA743BC0', NULL, 1, 'Issue with Order #1000', 'I haven\'t received my order yet. Please help.', NULL, 'open', NULL, 'urgent', '2025-12-22 18:29:59', '2025-12-22 18:29:59'),
(3, 'TCK-69491DB54C4D3', 33, 1, 'Issue with Order #1004', 'I haven\'t received my order yet. Please help.', NULL, 'open', NULL, 'medium', '2025-12-22 18:30:13', '2025-12-22 18:30:13'),
(6, 'TCK-69491DE209402', 33, 1, 'Issue with Order #1004', 'I haven\'t received my order yet. Please help.', NULL, 'open', NULL, 'medium', '2025-12-22 18:30:58', '2025-12-22 18:30:58'),
(7, 'TCK-69491E0AC8B05', NULL, 1, 'Issue with Order #1000', 'I haven\'t received my order yet. Please help.', NULL, 'open', NULL, 'urgent', '2025-12-22 18:31:38', '2025-12-22 18:31:38'),
(9, 'TCK-69491E158D403', 33, 1, 'Issue with Order #1004', 'I haven\'t received my order yet. Please help.', NULL, 'open', NULL, 'medium', '2025-12-22 18:31:49', '2025-12-22 18:31:49'),
(10, 'TCK-69491E5C351C6', NULL, 1, 'Issue with Order #1000', 'I haven\'t received my order yet. Please help.', NULL, 'open', NULL, 'urgent', '2025-12-22 18:33:00', '2025-12-22 18:33:00'),
(12, 'TCK-69491E6822B36', 33, 1, 'Issue with Order #1004', 'I haven\'t received my order yet. Please help.', NULL, 'open', NULL, 'medium', '2025-12-22 18:33:12', '2025-12-22 18:33:12'),
(15, 'TCK-69491F49A89C0', 33, 1, 'Issue with Order #1004', 'I haven\'t received my order yet. Please help.', NULL, 'open', NULL, 'medium', '2025-12-22 18:36:57', '2025-12-22 18:36:57'),
(18, 'TCK-69491F93D348A', 33, 1, 'Issue with Order #1004', 'I haven\'t received my order yet. Please help.', NULL, 'open', NULL, 'medium', '2025-12-22 18:38:11', '2025-12-22 18:38:11'),
(21, 'TCK-69491FEB4FF6A', 33, 1, 'Issue with Order #1004', 'I haven\'t received my order yet. Please help.', NULL, 'open', NULL, 'medium', '2025-12-22 18:39:39', '2025-12-23 14:45:42'),
(22, 'TCK-694A4877EB42F', 6, NULL, 'Technical Issue #1', 'I am having trouble with the system integration for module 1', NULL, 'open', 1, 'medium', '2025-12-23 15:44:55', '2025-12-23 15:44:55'),
(23, 'TCK-694A4877ECFAF', 6, NULL, 'Technical Issue #2', 'I am having trouble with the system integration for module 2', NULL, 'closed', 1, 'high', '2025-12-23 15:44:55', '2025-12-23 15:44:55'),
(24, 'TCK-694A4877EF935', 6, NULL, 'Technical Issue #3', 'I am having trouble with the system integration for module 3', NULL, 'closed', 1, 'medium', '2025-12-23 15:44:55', '2025-12-23 15:44:55'),
(25, 'TCK-694A4877F0C61', 6, NULL, 'Technical Issue #4', 'I am having trouble with the system integration for module 4', NULL, 'open', 1, 'low', '2025-12-23 15:44:55', '2025-12-23 15:44:55'),
(26, 'TCK-694A4877F1D47', 6, NULL, 'Technical Issue #5', 'I am having trouble with the system integration for module 5', NULL, 'closed', 1, 'medium', '2025-12-23 15:44:55', '2025-12-23 15:44:55'),
(27, 'TCK-ESED62', 34, 1, 'Et veniam quia dolor.', 'Vero odio vitae aut nostrum omnis hic corporis maiores. Magnam sed delectus molestiae at iste. Doloribus qui iusto similique qui perspiciatis et. Praesentium omnis et est modi et iste libero blanditiis.', NULL, 'closed', NULL, 'low', '2025-12-04 23:36:43', '2025-12-23 17:26:00'),
(28, 'TCK-RRPU60', 34, 2, 'Molestias placeat dolorum occaecati cupiditate.', 'Exercitationem sit et suscipit. Et est odio accusantium cupiditate. Rem assumenda et recusandae illo. Cupiditate deserunt doloribus ducimus fugiat voluptates est.', NULL, 'open', NULL, 'medium', '2025-12-04 17:41:25', '2025-12-23 17:26:00'),
(29, 'TCK-PWYZ21', 34, 2, 'Molestias pariatur aperiam deserunt.', 'Enim tenetur aperiam vero eligendi reprehenderit sed aperiam. In rem dolor ex reprehenderit et eum occaecati. Est consectetur beatae id aut nam eos id. Dolorem ullam non iusto quasi et animi deleniti.', NULL, 'in_progress', NULL, 'high', '2025-12-23 05:40:59', '2025-12-23 17:26:00'),
(30, 'TCK-LDSF15', 34, 1, 'Accusantium est enim.', 'Ut sed ea officia alias necessitatibus saepe. Aut illum soluta sunt doloremque magnam. Nostrum tempora a corporis blanditiis accusamus qui debitis. Sunt totam omnis tempore omnis nihil.', NULL, 'resolved', NULL, 'high', '2025-12-10 20:43:11', '2025-12-23 17:26:00'),
(31, 'TCK-OEDW12', 34, 3, 'Quasi id et aperiam ducimus perspiciatis.', 'Et quia dicta sit facere voluptas laborum et. Quibusdam provident nemo assumenda. Cum adipisci consequatur quas.', NULL, 'closed', NULL, 'low', '2025-12-09 09:07:55', '2025-12-23 17:26:00'),
(32, 'TCK-RXFA61', 34, 4, 'Ex sunt velit amet.', 'In consequatur consequatur minima. Ducimus dolores dolor delectus asperiores mollitia et molestiae et. Aut consequatur consequuntur dolorem non eum. Odio facilis ullam aut voluptatem rem.', NULL, 'closed', NULL, 'low', '2025-12-15 12:40:56', '2025-12-23 17:27:28'),
(33, 'TCK-LQNO78', 34, 2, 'Velit voluptatem rem recusandae quo.', 'Sunt veniam earum laboriosam non. Optio voluptatibus non enim deleniti ut explicabo. Facilis pariatur laudantium reiciendis sit praesentium.', NULL, 'in_progress', NULL, 'medium', '2025-12-07 06:35:59', '2025-12-23 17:27:28'),
(34, 'TCK-DDFU05', 34, 1, 'Recusandae quis reiciendis veniam quaerat.', 'Optio quisquam nisi odio neque repellendus delectus. Est mollitia ea error consequatur expedita dolorem architecto. Vel eum eaque eius quae voluptatibus aspernatur.', NULL, 'closed', NULL, 'high', '2025-12-14 18:56:15', '2025-12-23 17:27:28'),
(35, 'TCK-BZAC96', 34, 2, 'Est necessitatibus nulla dolorem incidunt.', 'Non ab architecto est modi consequatur. Quis magnam molestias sequi impedit inventore nihil qui.', NULL, 'in_progress', NULL, 'medium', '2025-12-01 15:02:56', '2025-12-23 17:27:28'),
(36, 'TCK-UZNO23', 34, 1, 'Et cum debitis sequi laboriosam.', 'Sed voluptatibus ut quia unde voluptatibus. Consequatur quasi consequatur quis enim deserunt nemo. Quia nihil ut et omnis est iusto earum molestiae. Officia ratione harum alias dolorem.', NULL, 'closed', NULL, 'low', '2025-11-28 23:08:02', '2025-12-23 17:27:28');

-- --------------------------------------------------------

--
-- Table structure for table `ticket_logs`
--

CREATE TABLE `ticket_logs` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `ticket_id` bigint(20) UNSIGNED NOT NULL,
  `user_id` bigint(20) UNSIGNED DEFAULT NULL,
  `action` varchar(255) NOT NULL,
  `description` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `ticket_logs`
--

INSERT INTO `ticket_logs` (`id`, `ticket_id`, `user_id`, `action`, `description`, `created_at`, `updated_at`) VALUES
(1, 27, NULL, 'Ticket Created', 'System generated ticket.', '2025-12-23 17:26:00', '2025-12-23 17:26:00'),
(2, 28, NULL, 'Ticket Created', 'System generated ticket.', '2025-12-23 17:26:00', '2025-12-23 17:26:00'),
(3, 29, NULL, 'Ticket Created', 'System generated ticket.', '2025-12-23 17:26:00', '2025-12-23 17:26:00'),
(4, 30, NULL, 'Ticket Created', 'System generated ticket.', '2025-12-23 17:26:00', '2025-12-23 17:26:00'),
(5, 31, NULL, 'Ticket Created', 'System generated ticket.', '2025-12-23 17:26:00', '2025-12-23 17:26:00'),
(6, 32, NULL, 'Ticket Created', 'System generated ticket.', '2025-12-23 17:27:28', '2025-12-23 17:27:28'),
(7, 33, NULL, 'Ticket Created', 'System generated ticket.', '2025-12-23 17:27:28', '2025-12-23 17:27:28'),
(8, 34, NULL, 'Ticket Created', 'System generated ticket.', '2025-12-23 17:27:28', '2025-12-23 17:27:28'),
(9, 35, NULL, 'Ticket Created', 'System generated ticket.', '2025-12-23 17:27:28', '2025-12-23 17:27:28'),
(10, 36, NULL, 'Ticket Created', 'System generated ticket.', '2025-12-23 17:27:28', '2025-12-23 17:27:28');

-- --------------------------------------------------------

--
-- Table structure for table `ticket_replies`
--

CREATE TABLE `ticket_replies` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `ticket_id` bigint(20) UNSIGNED NOT NULL,
  `user_id` bigint(20) UNSIGNED NOT NULL,
  `message` text NOT NULL,
  `attachment` varchar(255) DEFAULT NULL,
  `is_staff_reply` tinyint(1) NOT NULL DEFAULT 0,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `ticket_replies`
--

INSERT INTO `ticket_replies` (`id`, `ticket_id`, `user_id`, `message`, `attachment`, `is_staff_reply`, `created_at`, `updated_at`) VALUES
(1, 15, 1, 'yyyyy', 'replies/IFaqxWtU4W7VAlgIFFQ1FK2nYh6weYRwS1SjyPQN.pdf', 0, '2025-12-22 20:39:41', '2025-12-22 20:39:41'),
(4, 26, 1, 'heff', NULL, 1, '2025-12-23 15:52:20', '2025-12-23 15:52:20'),
(5, 26, 1, 'gggg', NULL, 1, '2025-12-23 16:25:51', '2025-12-23 16:25:51');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `role` varchar(255) NOT NULL DEFAULT 'customer',
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `password` varchar(255) NOT NULL,
  `photo` varchar(255) DEFAULT NULL,
  `phone` varchar(255) DEFAULT NULL,
  `address` text DEFAULT NULL,
  `gender` varchar(255) DEFAULT NULL,
  `last_login_at` timestamp NULL DEFAULT NULL,
  `status` varchar(255) NOT NULL DEFAULT 'active',
  `theme` varchar(255) NOT NULL DEFAULT 'light',
  `remember_token` varchar(100) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `name`, `email`, `is_active`, `role`, `email_verified_at`, `password`, `photo`, `phone`, `address`, `gender`, `last_login_at`, `status`, `theme`, `remember_token`, `created_at`, `updated_at`, `deleted_at`) VALUES
(1, 'Faustino', 'admin@gmail.com', 1, 'admin', NULL, '$2y$12$XQoMN9DkFMSh2uy3IExoPe8uw1BKNN1ibggX1gyYfErZPVseRYmJ.', '1766487171_image.jpg', NULL, NULL, NULL, NULL, 'active', 'light', NULL, '2025-12-22 02:09:50', '2026-01-05 14:24:07', NULL),
(2, 'Manager', 'manager@gmail.com', 1, 'manager', NULL, '$2y$12$I4jGW4NEz43W2ZCw7XNBA.otVn4fjP05t2FGNDjp4MX.Sqzvi5yCS', NULL, NULL, NULL, NULL, NULL, 'active', 'light', NULL, '2025-12-22 02:35:11', '2025-12-22 02:56:51', NULL),
(3, 'Employee', 'employee@gmail.com', 1, 'employee', NULL, '$2y$12$9p1E2KT8do664ndYsyw9QOonbrvk5mCPyfyz3UHBimveGdodB7012', NULL, NULL, NULL, NULL, NULL, 'active', 'light', NULL, '2025-12-22 02:36:01', '2025-12-22 02:37:23', NULL),
(4, 'Customer', 'customer@gmail.com', 1, 'customer', NULL, '$2y$12$AIQIvv2ByNzL2g0398sl3OgYKZv16yEeJR2WnbrfzQr0KDKGDi/PS', NULL, NULL, NULL, NULL, NULL, 'active', 'light', NULL, '2025-12-22 02:36:42', '2025-12-22 02:36:42', NULL),
(5, 'Keza Kelia', 'kellia@gmail.com', 1, 'customer', NULL, '$2y$12$149WU4D.KLXV1JGcm7lnMOrVlm1wYjxvpsFo6yUqBocTrlNdxxoMW', NULL, NULL, NULL, NULL, NULL, 'active', 'dark', NULL, '2025-12-22 15:16:33', '2025-12-23 06:10:02', NULL),
(6, 'Ernesto Ondricka Jr.', 'reichert.mario@example.net', 1, 'customer', '2025-12-22 15:42:43', '$2y$12$dKOM8h.B4wKiUt1q8VblZOz79vD0bGFBi3dymrVFy0zmdFaKwROxG', NULL, NULL, NULL, NULL, NULL, 'active', 'light', 'ZyLYEITbnG', '2025-12-22 15:42:43', '2025-12-22 15:42:43', NULL),
(7, 'Don Kuhlman', 'kelli.daniel@example.net', 1, 'customer', '2025-12-22 15:42:43', '$2y$12$dKOM8h.B4wKiUt1q8VblZOz79vD0bGFBi3dymrVFy0zmdFaKwROxG', NULL, NULL, NULL, NULL, NULL, 'active', 'light', '4cdFtZXfEU', '2025-12-22 15:42:43', '2025-12-22 15:42:43', NULL),
(8, 'Justyn Balistreri IV', 'lamont.denesik@example.org', 1, 'customer', '2025-12-22 15:42:43', '$2y$12$dKOM8h.B4wKiUt1q8VblZOz79vD0bGFBi3dymrVFy0zmdFaKwROxG', NULL, NULL, NULL, NULL, NULL, 'active', 'light', 'B3Mr74FfxY', '2025-12-22 15:42:43', '2025-12-22 15:42:43', NULL),
(9, 'Glenda Marvin', 'akemmer@example.com', 1, 'customer', '2025-12-22 15:42:43', '$2y$12$dKOM8h.B4wKiUt1q8VblZOz79vD0bGFBi3dymrVFy0zmdFaKwROxG', NULL, NULL, NULL, NULL, NULL, 'active', 'light', 'qtGbSi8IZO', '2025-12-22 15:42:43', '2025-12-22 15:42:43', NULL),
(10, 'Dr. Jaqueline Gerhold', 'dion18@example.org', 1, 'customer', '2025-12-22 15:42:43', '$2y$12$dKOM8h.B4wKiUt1q8VblZOz79vD0bGFBi3dymrVFy0zmdFaKwROxG', NULL, NULL, NULL, NULL, NULL, 'active', 'light', 'Rb4bfIPoAy', '2025-12-22 15:42:43', '2025-12-22 15:42:43', NULL),
(11, 'Camylle Nolan III', 'urban.thiel@example.net', 1, 'customer', '2025-12-22 15:42:43', '$2y$12$dKOM8h.B4wKiUt1q8VblZOz79vD0bGFBi3dymrVFy0zmdFaKwROxG', NULL, NULL, NULL, NULL, NULL, 'active', 'light', '3KA3WdlrwU', '2025-12-22 15:42:43', '2025-12-22 15:42:43', NULL),
(12, 'Sydney Keebler', 'ykoss@example.org', 1, 'customer', '2025-12-22 15:42:43', '$2y$12$dKOM8h.B4wKiUt1q8VblZOz79vD0bGFBi3dymrVFy0zmdFaKwROxG', NULL, NULL, NULL, NULL, NULL, 'active', 'light', 'gMYzPjZ5n8', '2025-12-22 15:42:43', '2025-12-22 15:42:43', NULL),
(13, 'Alison Kris', 'cooper.rogahn@example.net', 1, 'manager', '2025-12-22 15:42:43', '$2y$12$dKOM8h.B4wKiUt1q8VblZOz79vD0bGFBi3dymrVFy0zmdFaKwROxG', NULL, NULL, NULL, NULL, NULL, 'active', 'light', '3ZFvHdCiOt', '2025-12-22 15:42:43', '2025-12-22 21:34:19', NULL),
(14, 'Jaquan Halvorson', 'frieda06@example.org', 1, 'customer', '2025-12-22 15:42:43', '$2y$12$dKOM8h.B4wKiUt1q8VblZOz79vD0bGFBi3dymrVFy0zmdFaKwROxG', NULL, NULL, NULL, NULL, NULL, 'active', 'light', 'idF7lEswVk', '2025-12-22 15:42:43', '2025-12-22 15:42:43', NULL),
(15, 'Christiana Gulgowski DDS', 'cade80@example.org', 1, 'customer', '2025-12-22 15:42:43', '$2y$12$dKOM8h.B4wKiUt1q8VblZOz79vD0bGFBi3dymrVFy0zmdFaKwROxG', NULL, NULL, NULL, NULL, NULL, 'active', 'light', '2KkEDJO1L7', '2025-12-22 15:42:43', '2025-12-22 15:42:43', NULL),
(16, 'Mr. Rhiannon Balistreri DDS', 'verona97@example.net', 1, 'customer', '2025-12-22 15:42:43', '$2y$12$dKOM8h.B4wKiUt1q8VblZOz79vD0bGFBi3dymrVFy0zmdFaKwROxG', NULL, NULL, NULL, NULL, NULL, 'active', 'light', '0xLvhdJVQw', '2025-12-22 15:42:43', '2025-12-22 15:42:43', NULL),
(17, 'Mikayla Hoeger', 'sadye.zieme@example.com', 1, 'customer', '2025-12-22 15:42:43', '$2y$12$dKOM8h.B4wKiUt1q8VblZOz79vD0bGFBi3dymrVFy0zmdFaKwROxG', NULL, NULL, NULL, NULL, NULL, 'active', 'light', '1StW86ZDg6', '2025-12-22 15:42:43', '2025-12-22 15:42:43', NULL),
(18, 'Mr. Brannon Kutch', 'cboyle@example.org', 1, 'customer', '2025-12-22 15:42:43', '$2y$12$dKOM8h.B4wKiUt1q8VblZOz79vD0bGFBi3dymrVFy0zmdFaKwROxG', NULL, NULL, NULL, NULL, NULL, 'active', 'light', 'Qb6QED1v0f', '2025-12-22 15:42:43', '2025-12-22 15:42:43', NULL),
(19, 'Therese Hayes', 'madisen.wilderman@example.org', 1, 'customer', '2025-12-22 15:42:43', '$2y$12$dKOM8h.B4wKiUt1q8VblZOz79vD0bGFBi3dymrVFy0zmdFaKwROxG', NULL, NULL, NULL, NULL, NULL, 'active', 'light', '93aztdGPMk', '2025-12-22 15:42:43', '2025-12-22 15:42:43', NULL),
(20, 'Lorenza Wilderman', 'nwisozk@example.com', 1, 'customer', '2025-12-22 15:42:43', '$2y$12$dKOM8h.B4wKiUt1q8VblZOz79vD0bGFBi3dymrVFy0zmdFaKwROxG', NULL, NULL, NULL, NULL, NULL, 'active', 'light', 'ZHQo5U7ekx', '2025-12-22 15:42:43', '2025-12-22 15:42:43', NULL),
(21, 'Orlo Rau Jr.', 'strosin.leola@example.org', 1, 'customer', '2025-12-22 15:42:43', '$2y$12$dKOM8h.B4wKiUt1q8VblZOz79vD0bGFBi3dymrVFy0zmdFaKwROxG', NULL, NULL, NULL, NULL, NULL, 'active', 'light', 'e5kHGA83SP', '2025-12-22 15:42:43', '2025-12-22 15:42:43', NULL),
(22, 'Ms. Erika O\'Kon PhD', 'qgerlach@example.com', 1, 'customer', '2025-12-22 15:42:43', '$2y$12$dKOM8h.B4wKiUt1q8VblZOz79vD0bGFBi3dymrVFy0zmdFaKwROxG', NULL, NULL, NULL, NULL, NULL, 'active', 'light', 'aVM1P3pl0K', '2025-12-22 15:42:43', '2025-12-22 15:42:43', NULL),
(23, 'Dr. Tess Stark DDS', 'halle67@example.com', 1, 'customer', '2025-12-22 15:42:43', '$2y$12$dKOM8h.B4wKiUt1q8VblZOz79vD0bGFBi3dymrVFy0zmdFaKwROxG', NULL, NULL, NULL, NULL, NULL, 'active', 'light', 'J9Fh4wyyQA', '2025-12-22 15:42:43', '2025-12-22 15:42:43', NULL),
(24, 'Flavie Kautzer', 'jammie.reichel@example.com', 1, 'customer', '2025-12-22 15:42:43', '$2y$12$dKOM8h.B4wKiUt1q8VblZOz79vD0bGFBi3dymrVFy0zmdFaKwROxG', NULL, NULL, NULL, NULL, NULL, 'active', 'light', 'V8TqASdxgN', '2025-12-22 15:42:43', '2025-12-22 15:42:43', NULL),
(25, 'Adrian DuBuque', 'dbartoletti@example.net', 1, 'customer', '2025-12-22 15:42:43', '$2y$12$dKOM8h.B4wKiUt1q8VblZOz79vD0bGFBi3dymrVFy0zmdFaKwROxG', NULL, NULL, NULL, NULL, NULL, 'active', 'light', 'BQzlYux1AP', '2025-12-22 15:42:43', '2025-12-22 15:42:43', NULL),
(26, 'John Tech', 'john.tech@evuba.com', 1, 'employee', '2025-12-22 18:27:43', '$2y$12$slHTVo1zr9HFd4N4xlooluddFoqlrB762xfQHxASeP0tO0JTsZXGi', NULL, NULL, NULL, NULL, NULL, 'active', 'light', NULL, '2025-12-22 18:27:43', '2025-12-22 18:27:43', NULL),
(27, 'Sarah Support', 'sarah.support@evuba.com', 1, 'employee', '2025-12-22 18:27:43', '$2y$12$qx9c3eCgj2P2hF0UGRsSx.ewFBdVFlGFHHIMJbKwcJD1EdOk13McW', NULL, NULL, NULL, NULL, NULL, 'active', 'light', NULL, '2025-12-22 18:27:43', '2025-12-22 18:27:43', NULL),
(28, 'Mike Sales', 'mike.sales@evuba.com', 1, 'employee', '2025-12-22 18:27:43', '$2y$12$ENBh3.NLDj06dckhDAzJ6eEyfKL7jWqB5T1C37l7dxpLFAiYHCHqm', NULL, NULL, NULL, NULL, NULL, 'active', 'light', NULL, '2025-12-22 18:27:43', '2025-12-22 18:27:43', NULL),
(29, 'Customer 1', 'customer1@test.com', 1, 'employee', '2025-12-22 18:27:43', '$2y$12$XjqF34feOQYfcq.DMQPMKOgJBsoQX7Q5tXU9T/UoGbem2Dmyc5MtS', NULL, NULL, NULL, NULL, NULL, 'active', 'light', NULL, '2025-12-22 18:27:43', '2025-12-23 05:35:12', NULL),
(30, 'Customer 2', 'customer2@test.com', 1, 'customer', '2025-12-22 18:27:44', '$2y$12$2yP3GJEI/ZCUYfcW2kXqNe.hW3ffiAYH21ZNzAcFGzxqgUwGJE.sm', NULL, NULL, NULL, NULL, NULL, 'active', 'light', NULL, '2025-12-22 18:27:44', '2025-12-22 18:27:44', NULL),
(31, 'Customer 3', 'customer3@test.com', 1, 'customer', '2025-12-22 18:27:44', '$2y$12$L0Rr7WMIIP.Kdz192iG0pOSX/4INasbjcM4aDmcmXliPb7KR6RAd6', NULL, NULL, NULL, NULL, NULL, 'active', 'light', NULL, '2025-12-22 18:27:44', '2025-12-22 18:27:44', NULL),
(32, 'Customer 4', 'customer4@test.com', 1, 'customer', '2025-12-22 18:27:44', '$2y$12$CCM3pNVlfbbO/gUd/7UI6upnAIG0pYuC0o0bJeF0g3gof8kyHdU6K', NULL, NULL, NULL, NULL, NULL, 'active', 'light', NULL, '2025-12-22 18:27:44', '2025-12-22 18:27:44', NULL),
(33, 'Customer 5', 'customer5@test.com', 1, 'customer', '2025-12-22 18:27:45', '$2y$12$RVpeR0YAvlMcK3RhoIMg4OtrbwFx6vKma0dwcELK8BbCvxXBLYxiS', NULL, NULL, NULL, NULL, NULL, 'active', 'light', NULL, '2025-12-22 18:27:45', '2025-12-22 18:27:45', NULL),
(34, 'Demo Customer', 'customer@evuba.com', 1, 'customer', NULL, '$2y$12$93s7WaZv77i8Lz95tRmCT.UccyVNtdX2MeMknNIqJPKlQcJ6p1vEm', NULL, NULL, NULL, NULL, NULL, 'active', 'light', NULL, '2025-12-23 17:20:31', '2025-12-23 17:20:31', NULL),
(35, 'Dusabe Yvonne', 'dusabe@gmail.com', 1, 'customer', NULL, '$2y$12$w/g8CacgPMum28sqv6qXJOsilyLZBJ0n57/hPAWtJ75aY4yt2qiNa', NULL, NULL, NULL, NULL, NULL, 'active', 'light', NULL, '2026-01-05 02:22:42', '2026-01-05 14:13:41', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `user_announcements`
--

CREATE TABLE `user_announcements` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `user_id` bigint(20) UNSIGNED NOT NULL,
  `announcement_id` bigint(20) UNSIGNED NOT NULL,
  `read_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `user_announcements`
--

INSERT INTO `user_announcements` (`id`, `user_id`, `announcement_id`, `read_at`, `created_at`, `updated_at`) VALUES
(1, 1, 6, NULL, NULL, NULL),
(2, 1, 7, NULL, NULL, NULL),
(3, 1, 4, NULL, NULL, NULL);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `announcements`
--
ALTER TABLE `announcements`
  ADD PRIMARY KEY (`id`),
  ADD KEY `announcements_user_id_foreign` (`user_id`);

--
-- Indexes for table `appointments`
--
ALTER TABLE `appointments`
  ADD PRIMARY KEY (`id`),
  ADD KEY `appointments_user_id_foreign` (`user_id`),
  ADD KEY `appointments_employee_id_foreign` (`employee_id`),
  ADD KEY `appointments_manager_id_foreign` (`manager_id`);

--
-- Indexes for table `bookings`
--
ALTER TABLE `bookings`
  ADD PRIMARY KEY (`id`),
  ADD KEY `bookings_user_id_foreign` (`user_id`),
  ADD KEY `bookings_employee_id_foreign` (`employee_id`),
  ADD KEY `bookings_service_id_foreign` (`service_id`);

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
-- Indexes for table `categories`
--
ALTER TABLE `categories`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `categories_slug_unique` (`slug`),
  ADD KEY `categories_parent_id_foreign` (`parent_id`);

--
-- Indexes for table `customers`
--
ALTER TABLE `customers`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `customers_email_unique` (`email`);

--
-- Indexes for table `employees`
--
ALTER TABLE `employees`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `employees_email_unique` (`email`);

--
-- Indexes for table `failed_jobs`
--
ALTER TABLE `failed_jobs`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `failed_jobs_uuid_unique` (`uuid`);

--
-- Indexes for table `inventories`
--
ALTER TABLE `inventories`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `invoices`
--
ALTER TABLE `invoices`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `invoices_invoice_number_unique` (`invoice_number`),
  ADD KEY `invoices_customer_id_foreign` (`customer_id`);

--
-- Indexes for table `invoice_items`
--
ALTER TABLE `invoice_items`
  ADD PRIMARY KEY (`id`),
  ADD KEY `invoice_items_invoice_id_foreign` (`invoice_id`),
  ADD KEY `invoice_items_product_id_foreign` (`product_id`);

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
  ADD PRIMARY KEY (`id`),
  ADD KEY `messages_sender_id_foreign` (`sender_id`),
  ADD KEY `messages_receiver_id_foreign` (`recipient_id`);

--
-- Indexes for table `message_replies`
--
ALTER TABLE `message_replies`
  ADD PRIMARY KEY (`id`),
  ADD KEY `message_replies_message_us_id_foreign` (`message_us_id`),
  ADD KEY `message_replies_user_id_foreign` (`user_id`);

--
-- Indexes for table `message_us`
--
ALTER TABLE `message_us`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `migrations`
--
ALTER TABLE `migrations`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `model_has_permissions`
--
ALTER TABLE `model_has_permissions`
  ADD PRIMARY KEY (`permission_id`,`model_id`,`model_type`),
  ADD KEY `model_has_permissions_model_id_model_type_index` (`model_id`,`model_type`);

--
-- Indexes for table `model_has_roles`
--
ALTER TABLE `model_has_roles`
  ADD PRIMARY KEY (`role_id`,`model_id`,`model_type`),
  ADD KEY `model_has_roles_model_id_model_type_index` (`model_id`,`model_type`);

--
-- Indexes for table `notifications`
--
ALTER TABLE `notifications`
  ADD PRIMARY KEY (`id`),
  ADD KEY `notifications_notifiable_type_notifiable_id_index` (`notifiable_type`,`notifiable_id`);

--
-- Indexes for table `orders`
--
ALTER TABLE `orders`
  ADD PRIMARY KEY (`id`),
  ADD KEY `orders_user_id_foreign` (`user_id`),
  ADD KEY `orders_product_id_foreign` (`product_id`);

--
-- Indexes for table `pages`
--
ALTER TABLE `pages`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `pages_name_unique` (`name`),
  ADD UNIQUE KEY `pages_slug_unique` (`slug`);

--
-- Indexes for table `password_reset_tokens`
--
ALTER TABLE `password_reset_tokens`
  ADD PRIMARY KEY (`email`);

--
-- Indexes for table `permissions`
--
ALTER TABLE `permissions`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `permissions_name_guard_name_unique` (`name`,`guard_name`);

--
-- Indexes for table `products`
--
ALTER TABLE `products`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `products_product_code_unique` (`product_code`),
  ADD KEY `products_category_id_foreign` (`category_id`);

--
-- Indexes for table `reports`
--
ALTER TABLE `reports`
  ADD PRIMARY KEY (`id`),
  ADD KEY `reports_user_id_foreign` (`user_id`);

--
-- Indexes for table `roles`
--
ALTER TABLE `roles`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `roles_name_guard_name_unique` (`name`,`guard_name`);

--
-- Indexes for table `role_has_permissions`
--
ALTER TABLE `role_has_permissions`
  ADD PRIMARY KEY (`permission_id`,`role_id`),
  ADD KEY `role_has_permissions_role_id_foreign` (`role_id`);

--
-- Indexes for table `role_page_permission`
--
ALTER TABLE `role_page_permission`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `role_page_permission_unique` (`role_id`,`page_id`,`permission_id`),
  ADD KEY `role_page_permission_page_id_foreign` (`page_id`),
  ADD KEY `role_page_permission_permission_id_foreign` (`permission_id`);

--
-- Indexes for table `sales`
--
ALTER TABLE `sales`
  ADD PRIMARY KEY (`id`),
  ADD KEY `sales_product_id_foreign` (`product_id`),
  ADD KEY `sales_customer_id_foreign` (`customer_id`),
  ADD KEY `sales_user_id_foreign` (`user_id`);

--
-- Indexes for table `schedules`
--
ALTER TABLE `schedules`
  ADD PRIMARY KEY (`id`),
  ADD KEY `schedules_user_id_foreign` (`user_id`);

--
-- Indexes for table `services`
--
ALTER TABLE `services`
  ADD PRIMARY KEY (`id`),
  ADD KEY `services_employee_id_foreign` (`employee_id`);

--
-- Indexes for table `sessions`
--
ALTER TABLE `sessions`
  ADD PRIMARY KEY (`id`),
  ADD KEY `sessions_user_id_index` (`user_id`),
  ADD KEY `sessions_last_activity_index` (`last_activity`);

--
-- Indexes for table `settings`
--
ALTER TABLE `settings`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `settings_key_unique` (`key`);

--
-- Indexes for table `stock`
--
ALTER TABLE `stock`
  ADD PRIMARY KEY (`id`),
  ADD KEY `stock_product_id_foreign` (`product_id`),
  ADD KEY `stock_supplier_id_foreign` (`supplier_id`);

--
-- Indexes for table `stock_in`
--
ALTER TABLE `stock_in`
  ADD PRIMARY KEY (`id`),
  ADD KEY `stock_in_product_id_foreign` (`product_id`),
  ADD KEY `stock_in_supplier_id_foreign` (`supplier_id`),
  ADD KEY `stock_in_user_id_foreign` (`user_id`);

--
-- Indexes for table `stock_out`
--
ALTER TABLE `stock_out`
  ADD PRIMARY KEY (`id`),
  ADD KEY `stock_out_product_id_foreign` (`product_id`),
  ADD KEY `stock_out_customer_id_foreign` (`customer_id`),
  ADD KEY `stock_out_supplier_id_foreign` (`supplier_id`),
  ADD KEY `stock_out_user_id_foreign` (`user_id`);

--
-- Indexes for table `subscribers`
--
ALTER TABLE `subscribers`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `subscribers_email_unique` (`email`);

--
-- Indexes for table `suppliers`
--
ALTER TABLE `suppliers`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `suppliers_email_unique` (`email`);

--
-- Indexes for table `support_categories`
--
ALTER TABLE `support_categories`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `support_tickets`
--
ALTER TABLE `support_tickets`
  ADD PRIMARY KEY (`id`),
  ADD KEY `support_tickets_user_id_foreign` (`user_id`);

--
-- Indexes for table `tasks`
--
ALTER TABLE `tasks`
  ADD PRIMARY KEY (`id`),
  ADD KEY `tasks_user_id_foreign` (`user_id`);

--
-- Indexes for table `teams`
--
ALTER TABLE `teams`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `team_members`
--
ALTER TABLE `team_members`
  ADD PRIMARY KEY (`id`),
  ADD KEY `team_members_team_id_foreign` (`team_id`),
  ADD KEY `team_members_user_id_foreign` (`user_id`);

--
-- Indexes for table `tickets`
--
ALTER TABLE `tickets`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `tickets_ticket_no_unique` (`ticket_no`),
  ADD KEY `tickets_customer_id_foreign` (`customer_id`),
  ADD KEY `tickets_category_id_foreign` (`category_id`),
  ADD KEY `tickets_assigned_to_foreign` (`assigned_to`);

--
-- Indexes for table `ticket_logs`
--
ALTER TABLE `ticket_logs`
  ADD PRIMARY KEY (`id`),
  ADD KEY `ticket_logs_ticket_id_foreign` (`ticket_id`),
  ADD KEY `ticket_logs_user_id_foreign` (`user_id`);

--
-- Indexes for table `ticket_replies`
--
ALTER TABLE `ticket_replies`
  ADD PRIMARY KEY (`id`),
  ADD KEY `ticket_replies_ticket_id_foreign` (`ticket_id`),
  ADD KEY `ticket_replies_user_id_foreign` (`user_id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `users_email_unique` (`email`);

--
-- Indexes for table `user_announcements`
--
ALTER TABLE `user_announcements`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_announcements_user_id_foreign` (`user_id`),
  ADD KEY `user_announcements_announcement_id_foreign` (`announcement_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `announcements`
--
ALTER TABLE `announcements`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `appointments`
--
ALTER TABLE `appointments`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=58;

--
-- AUTO_INCREMENT for table `bookings`
--
ALTER TABLE `bookings`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=52;

--
-- AUTO_INCREMENT for table `categories`
--
ALTER TABLE `categories`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT for table `customers`
--
ALTER TABLE `customers`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=45;

--
-- AUTO_INCREMENT for table `employees`
--
ALTER TABLE `employees`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=30;

--
-- AUTO_INCREMENT for table `failed_jobs`
--
ALTER TABLE `failed_jobs`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `inventories`
--
ALTER TABLE `inventories`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `invoices`
--
ALTER TABLE `invoices`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `invoice_items`
--
ALTER TABLE `invoice_items`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=26;

--
-- AUTO_INCREMENT for table `jobs`
--
ALTER TABLE `jobs`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `messages`
--
ALTER TABLE `messages`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `message_replies`
--
ALTER TABLE `message_replies`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `message_us`
--
ALTER TABLE `message_us`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;

--
-- AUTO_INCREMENT for table `migrations`
--
ALTER TABLE `migrations`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=59;

--
-- AUTO_INCREMENT for table `orders`
--
ALTER TABLE `orders`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=23;

--
-- AUTO_INCREMENT for table `pages`
--
ALTER TABLE `pages`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `permissions`
--
ALTER TABLE `permissions`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=41;

--
-- AUTO_INCREMENT for table `products`
--
ALTER TABLE `products`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `reports`
--
ALTER TABLE `reports`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT for table `roles`
--
ALTER TABLE `roles`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `role_page_permission`
--
ALTER TABLE `role_page_permission`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `sales`
--
ALTER TABLE `sales`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `schedules`
--
ALTER TABLE `schedules`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `services`
--
ALTER TABLE `services`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `settings`
--
ALTER TABLE `settings`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=34;

--
-- AUTO_INCREMENT for table `stock`
--
ALTER TABLE `stock`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `stock_in`
--
ALTER TABLE `stock_in`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT for table `stock_out`
--
ALTER TABLE `stock_out`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=52;

--
-- AUTO_INCREMENT for table `subscribers`
--
ALTER TABLE `subscribers`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `suppliers`
--
ALTER TABLE `suppliers`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `support_categories`
--
ALTER TABLE `support_categories`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `support_tickets`
--
ALTER TABLE `support_tickets`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `tasks`
--
ALTER TABLE `tasks`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `teams`
--
ALTER TABLE `teams`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `team_members`
--
ALTER TABLE `team_members`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `tickets`
--
ALTER TABLE `tickets`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=37;

--
-- AUTO_INCREMENT for table `ticket_logs`
--
ALTER TABLE `ticket_logs`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `ticket_replies`
--
ALTER TABLE `ticket_replies`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=36;

--
-- AUTO_INCREMENT for table `user_announcements`
--
ALTER TABLE `user_announcements`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `announcements`
--
ALTER TABLE `announcements`
  ADD CONSTRAINT `announcements_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE SET NULL;

--
-- Constraints for table `appointments`
--
ALTER TABLE `appointments`
  ADD CONSTRAINT `appointments_employee_id_foreign` FOREIGN KEY (`employee_id`) REFERENCES `users` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `appointments_manager_id_foreign` FOREIGN KEY (`manager_id`) REFERENCES `users` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `appointments_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `bookings`
--
ALTER TABLE `bookings`
  ADD CONSTRAINT `bookings_employee_id_foreign` FOREIGN KEY (`employee_id`) REFERENCES `users` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `bookings_service_id_foreign` FOREIGN KEY (`service_id`) REFERENCES `services` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `bookings_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `categories`
--
ALTER TABLE `categories`
  ADD CONSTRAINT `categories_parent_id_foreign` FOREIGN KEY (`parent_id`) REFERENCES `categories` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `invoices`
--
ALTER TABLE `invoices`
  ADD CONSTRAINT `invoices_customer_id_foreign` FOREIGN KEY (`customer_id`) REFERENCES `customers` (`id`) ON DELETE SET NULL;

--
-- Constraints for table `invoice_items`
--
ALTER TABLE `invoice_items`
  ADD CONSTRAINT `invoice_items_invoice_id_foreign` FOREIGN KEY (`invoice_id`) REFERENCES `invoices` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `invoice_items_product_id_foreign` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE SET NULL;

--
-- Constraints for table `messages`
--
ALTER TABLE `messages`
  ADD CONSTRAINT `messages_receiver_id_foreign` FOREIGN KEY (`recipient_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `messages_sender_id_foreign` FOREIGN KEY (`sender_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `message_replies`
--
ALTER TABLE `message_replies`
  ADD CONSTRAINT `message_replies_message_us_id_foreign` FOREIGN KEY (`message_us_id`) REFERENCES `message_us` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `message_replies_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `model_has_permissions`
--
ALTER TABLE `model_has_permissions`
  ADD CONSTRAINT `model_has_permissions_permission_id_foreign` FOREIGN KEY (`permission_id`) REFERENCES `permissions` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `model_has_roles`
--
ALTER TABLE `model_has_roles`
  ADD CONSTRAINT `model_has_roles_role_id_foreign` FOREIGN KEY (`role_id`) REFERENCES `roles` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `orders`
--
ALTER TABLE `orders`
  ADD CONSTRAINT `orders_product_id_foreign` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `orders_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `products`
--
ALTER TABLE `products`
  ADD CONSTRAINT `products_category_id_foreign` FOREIGN KEY (`category_id`) REFERENCES `categories` (`id`) ON DELETE SET NULL;

--
-- Constraints for table `reports`
--
ALTER TABLE `reports`
  ADD CONSTRAINT `reports_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `role_has_permissions`
--
ALTER TABLE `role_has_permissions`
  ADD CONSTRAINT `role_has_permissions_permission_id_foreign` FOREIGN KEY (`permission_id`) REFERENCES `permissions` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `role_has_permissions_role_id_foreign` FOREIGN KEY (`role_id`) REFERENCES `roles` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `role_page_permission`
--
ALTER TABLE `role_page_permission`
  ADD CONSTRAINT `role_page_permission_page_id_foreign` FOREIGN KEY (`page_id`) REFERENCES `pages` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `role_page_permission_permission_id_foreign` FOREIGN KEY (`permission_id`) REFERENCES `permissions` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `role_page_permission_role_id_foreign` FOREIGN KEY (`role_id`) REFERENCES `roles` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `sales`
--
ALTER TABLE `sales`
  ADD CONSTRAINT `sales_customer_id_foreign` FOREIGN KEY (`customer_id`) REFERENCES `customers` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `sales_product_id_foreign` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `sales_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE SET NULL;

--
-- Constraints for table `schedules`
--
ALTER TABLE `schedules`
  ADD CONSTRAINT `schedules_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE SET NULL;

--
-- Constraints for table `services`
--
ALTER TABLE `services`
  ADD CONSTRAINT `services_employee_id_foreign` FOREIGN KEY (`employee_id`) REFERENCES `employees` (`id`) ON DELETE SET NULL;

--
-- Constraints for table `stock`
--
ALTER TABLE `stock`
  ADD CONSTRAINT `stock_product_id_foreign` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `stock_supplier_id_foreign` FOREIGN KEY (`supplier_id`) REFERENCES `suppliers` (`id`) ON DELETE SET NULL;

--
-- Constraints for table `stock_in`
--
ALTER TABLE `stock_in`
  ADD CONSTRAINT `stock_in_product_id_foreign` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `stock_in_supplier_id_foreign` FOREIGN KEY (`supplier_id`) REFERENCES `suppliers` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `stock_in_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE SET NULL;

--
-- Constraints for table `stock_out`
--
ALTER TABLE `stock_out`
  ADD CONSTRAINT `stock_out_customer_id_foreign` FOREIGN KEY (`customer_id`) REFERENCES `customers` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `stock_out_product_id_foreign` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `stock_out_supplier_id_foreign` FOREIGN KEY (`supplier_id`) REFERENCES `suppliers` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `stock_out_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE SET NULL;

--
-- Constraints for table `support_tickets`
--
ALTER TABLE `support_tickets`
  ADD CONSTRAINT `support_tickets_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `tasks`
--
ALTER TABLE `tasks`
  ADD CONSTRAINT `tasks_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `team_members`
--
ALTER TABLE `team_members`
  ADD CONSTRAINT `team_members_team_id_foreign` FOREIGN KEY (`team_id`) REFERENCES `teams` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `team_members_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `tickets`
--
ALTER TABLE `tickets`
  ADD CONSTRAINT `tickets_assigned_to_foreign` FOREIGN KEY (`assigned_to`) REFERENCES `users` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `tickets_category_id_foreign` FOREIGN KEY (`category_id`) REFERENCES `support_categories` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `tickets_customer_id_foreign` FOREIGN KEY (`customer_id`) REFERENCES `customers` (`id`) ON DELETE SET NULL;

--
-- Constraints for table `ticket_logs`
--
ALTER TABLE `ticket_logs`
  ADD CONSTRAINT `ticket_logs_ticket_id_foreign` FOREIGN KEY (`ticket_id`) REFERENCES `tickets` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `ticket_logs_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE SET NULL;

--
-- Constraints for table `ticket_replies`
--
ALTER TABLE `ticket_replies`
  ADD CONSTRAINT `ticket_replies_ticket_id_foreign` FOREIGN KEY (`ticket_id`) REFERENCES `tickets` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `ticket_replies_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `user_announcements`
--
ALTER TABLE `user_announcements`
  ADD CONSTRAINT `user_announcements_announcement_id_foreign` FOREIGN KEY (`announcement_id`) REFERENCES `announcements` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `user_announcements_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
