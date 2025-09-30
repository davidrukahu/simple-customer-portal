-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Generation Time: Sep 26, 2025 at 02:34 PM
-- Server version: 10.5.25-MariaDB-cll-lve
-- PHP Version: 8.1.33

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `onechamber_portal`
--

-- --------------------------------------------------------

--
-- Table structure for table `customers`
--

CREATE TABLE `customers` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `user_id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `company` varchar(255) DEFAULT NULL,
  `email` varchar(255) NOT NULL,
  `phone` varchar(255) DEFAULT NULL,
  `billing_address_json` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`billing_address_json`)),
  `currency` varchar(255) NOT NULL DEFAULT 'KES',
  `notes` text DEFAULT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `customers`
--

INSERT INTO `customers` (`id`, `user_id`, `name`, `company`, `email`, `phone`, `billing_address_json`, `currency`, `notes`, `is_active`, `created_at`, `updated_at`, `deleted_at`) VALUES
(1, 2, 'MMC ASAFO', 'MMC ASAFO', 'imungai@mmcafrica.law', '254 20 2167301', '{\"street\":\"MMC Arches, Spring Valley Crescent, Off Peponi Road, Westlands Kenya\",\"city\":\"Nairobi\",\"state\":\"Nairobi\",\"postal_code\":\"00100\",\"country\":\"Kenya\"}', 'USD', NULL, 1, '2025-09-24 15:12:07', '2025-09-24 15:12:07', NULL),
(2, 3, 'Test Customer', NULL, 'test@user.com', NULL, '{\"street\":null,\"city\":null,\"state\":null,\"postal_code\":null,\"country\":null}', 'USD', NULL, 1, '2025-09-25 05:24:14', '2025-09-25 05:24:14', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `domains`
--

CREATE TABLE `domains` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `customer_id` bigint(20) UNSIGNED NOT NULL,
  `fqdn` varchar(255) NOT NULL,
  `registrar` varchar(255) DEFAULT NULL,
  `registered_at` date DEFAULT NULL,
  `expires_at` date NOT NULL,
  `term_years` int(11) NOT NULL DEFAULT 1,
  `price` decimal(10,2) NOT NULL,
  `currency` varchar(255) NOT NULL DEFAULT 'KES',
  `status` enum('active','expired','grace','redemption','transfer-pending') NOT NULL DEFAULT 'active',
  `auto_renew` tinyint(1) NOT NULL DEFAULT 0,
  `service_notes` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `domains`
--

INSERT INTO `domains` (`id`, `customer_id`, `fqdn`, `registrar`, `registered_at`, `expires_at`, `term_years`, `price`, `currency`, `status`, `auto_renew`, `service_notes`, `created_at`, `updated_at`, `deleted_at`) VALUES
(1, 1, 'mmcafrica.legal', 'GoDaddy', '2024-11-19', '2025-10-24', 1, 82.00, 'USD', 'active', 0, NULL, '2025-09-24 15:28:05', '2025-09-24 17:24:48', NULL),
(2, 1, 'wakili.legal', 'NameCheap', '2023-09-18', '2025-09-25', 1, 82.00, 'USD', 'active', 1, NULL, '2025-09-24 15:33:24', '2025-09-24 17:18:59', NULL),
(3, 1, 'mmcafrica.law', 'GoDaddy', '2024-09-19', '2025-09-25', 1, 135.00, 'USD', 'active', 1, NULL, '2025-09-24 15:36:20', '2025-09-24 15:36:20', NULL),
(4, 1, 'wakili.law', 'Porkbun', '2024-11-19', '2025-10-24', 1, 135.00, 'USD', 'active', 1, NULL, '2025-09-24 15:40:44', '2025-09-24 17:28:28', NULL),
(5, 1, 'wakili.co.ke', 'SawaSawa', '2024-09-22', '2025-09-25', 1, 21.00, 'USD', 'active', 1, NULL, '2025-09-24 15:45:02', '2025-09-24 15:45:02', NULL),
(6, 1, 'mmc.law', 'Porkburn', '2024-11-19', '2025-10-19', 1, 330.00, 'USD', 'active', 1, NULL, '2025-09-24 15:47:30', '2025-09-24 17:35:43', NULL),
(7, 1, 'mmcasafo.legal', 'GoDaddy', '2023-09-19', '2025-09-25', 1, 92.00, 'USD', 'active', 1, NULL, '2025-09-24 16:40:39', '2025-09-24 17:21:20', NULL),
(8, 1, 'mmcasafo.africa', 'GoDaddy', '2024-11-19', '2025-11-19', 1, 55.00, 'USD', 'active', 1, NULL, '2025-09-24 16:47:02', '2025-09-24 16:47:02', NULL),
(9, 1, 'mmcasafo.law', 'Namecheap', '2025-03-25', '2026-02-28', 1, 365.00, 'USD', 'active', 1, NULL, '2025-09-24 17:00:19', '2025-09-24 17:00:19', NULL),
(10, 1, 'mmcasafo.net', 'GoDaddy', '2025-03-18', '2026-02-28', 1, 46.00, 'USD', 'active', 1, NULL, '2025-09-24 17:03:26', '2025-09-24 17:03:26', NULL),
(11, 1, 'mmcasafo.org', 'Namecheap', '2025-04-14', '2026-02-28', 1, 46.00, 'USD', 'active', 0, NULL, '2025-09-24 17:06:08', '2025-09-24 17:06:08', NULL),
(12, 1, 'mmcasafo.group', 'GoDaddy', '2025-02-14', '2026-02-28', 1, 46.00, 'USD', 'active', 1, NULL, '2025-09-24 17:08:32', '2025-09-24 17:08:32', NULL),
(13, 1, 'mmcasafo.co.ke', 'HostAfrica', '2025-04-14', '2026-04-17', 1, 55.00, 'USD', 'active', 1, NULL, '2025-09-24 17:14:28', '2025-09-24 17:14:28', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `invoices`
--

CREATE TABLE `invoices` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `customer_id` bigint(20) UNSIGNED NOT NULL,
  `number` varchar(255) NOT NULL,
  `status` enum('draft','sent','paid','overdue','void') NOT NULL DEFAULT 'draft',
  `issue_date` date NOT NULL,
  `due_date` date NOT NULL,
  `subtotal` decimal(10,2) NOT NULL DEFAULT 0.00,
  `tax_total` decimal(10,2) NOT NULL DEFAULT 0.00,
  `total` decimal(10,2) NOT NULL DEFAULT 0.00,
  `currency` varchar(255) NOT NULL DEFAULT 'KES',
  `notes` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `invoice_items`
--

CREATE TABLE `invoice_items` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `invoice_id` bigint(20) UNSIGNED NOT NULL,
  `item_type` enum('domain','service','manual') NOT NULL,
  `item_id` bigint(20) UNSIGNED DEFAULT NULL,
  `description` varchar(255) NOT NULL,
  `qty` decimal(10,2) NOT NULL DEFAULT 1.00,
  `unit_price` decimal(10,2) NOT NULL,
  `line_total` decimal(10,2) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

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
(1, '2024_01_01_000000_create_users_table', 1),
(2, '2024_01_01_000001_add_role_to_users_table', 1),
(3, '2024_01_01_000002_create_customers_table', 1),
(4, '2024_01_01_000003_create_domains_table', 1),
(5, '2024_01_01_000004_create_services_table', 1),
(6, '2024_01_01_000006_create_invoices_table', 1),
(7, '2024_01_01_000007_create_invoice_items_table', 1),
(8, '2024_01_01_000008_create_payments_table', 1),
(9, '2024_01_01_000009_create_settings_table', 1),
(10, '2025_09_24_163809_create_sessions_table', 1);

-- --------------------------------------------------------

--
-- Table structure for table `payments`
--

CREATE TABLE `payments` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `invoice_id` bigint(20) UNSIGNED NOT NULL,
  `amount` decimal(10,2) NOT NULL,
  `currency` varchar(255) NOT NULL DEFAULT 'KES',
  `method` enum('wire','mpesa','cash','cheque','other') NOT NULL,
  `paid_on` date NOT NULL,
  `reference` varchar(255) DEFAULT NULL,
  `notes` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `services`
--

CREATE TABLE `services` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `customer_id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `description` text DEFAULT NULL,
  `billing_cycle` enum('one_time','monthly','yearly') NOT NULL,
  `price` decimal(10,2) NOT NULL,
  `currency` varchar(255) NOT NULL DEFAULT 'KES',
  `next_invoice_on` date DEFAULT NULL,
  `status` enum('active','paused','cancelled') NOT NULL DEFAULT 'active',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `services`
--

INSERT INTO `services` (`id`, `customer_id`, `name`, `description`, `billing_cycle`, `price`, `currency`, `next_invoice_on`, `status`, `created_at`, `updated_at`, `deleted_at`) VALUES
(1, 1, '- Privacy & Business Protection. - Transfer Protection. - Ownership Reports.', NULL, 'yearly', 41.00, 'KES', NULL, 'active', '2025-09-24 15:51:30', '2025-09-24 15:52:36', '2025-09-24 15:52:36'),
(2, 1, 'Domain Add-ons', '- Privacy & Business Protection. \r\n- Transfer Protection. \r\n- Ownership Reports.', 'yearly', 41.00, 'USD', NULL, 'active', '2025-09-24 15:52:12', '2025-09-24 16:00:53', NULL),
(3, 1, 'Cloudflare - Pro plan.', '- DNS Zone Management. \r\n- Unmetered Mitigation of DDoS. \r\n- Shared SSL Certificate. \r\n- Global Content Delivery Network (CDN). \r\n- Web Application Firewall (WAF). \r\n- Image optimizations with PolishTM.\r\n - Mobile accelerations with MirageTM.', 'yearly', 198.60, 'USD', NULL, 'active', '2025-09-24 15:59:33', '2025-09-24 15:59:33', NULL),
(4, 1, 'Microsoft Azure App Service', '- Plesk Control Panel.\r\n- 1 Dedicated CPU Core.\r\n- 1.75 GB RAM.\r\n- 10 GB Storage.\r\n- Unlimited Web, mobile, or API apps.\r\n- Daily Automated Backups.\r\n- Dedicated IP Address.', 'yearly', 370.00, 'USD', NULL, 'active', '2025-09-24 16:03:32', '2025-09-24 16:03:32', NULL);

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
('0N1UGve3R9fxOzpuMlBk4zC0HzbqkrJqhOnvX4Y1', NULL, '105.163.1.190', 'Mozilla/5.0 (Linux; Android 6.0; Nexus 5 Build/MRA58N) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/140.0.0.0 Mobile Safari/537.36', 'YToyOntzOjY6Il90b2tlbiI7czo0MDoibENaNlNOSlhHZklXV3JxeFdYSmVmMVVnSTY4Z00wRjBzZ0gzV1ZKNSI7czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319fQ==', 1758868264),
('2sfiqu2JqXdmKOYBnGHMEQ3ZBiYKWIbNx6X4q1VD', NULL, '62.210.90.189', 'curl/7.81.0', 'YToyOntzOjY6Il90b2tlbiI7czo0MDoiWHJ5dUU3VnhPZ2RxZm9kcGdLYTI4cFE3bndlVUpTQ0JvUDVHa09PNSI7czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319fQ==', 1758864134),
('5pDnPiU5qUvg2bJNlHA6IPcapjGT12729QcAWLS4', NULL, '51.159.14.95', 'curl/7.81.0', 'YToyOntzOjY6Il90b2tlbiI7czo0MDoiZE1Kc2VjYUxsVTFiVTcwWDAxMERzRmI1dVNDalhjZjh1dXUxVTBuZiI7czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319fQ==', 1758863850),
('8kbaTBqIvnrofP7C6AjD3n5DhfdlgYY5UusjLcYf', NULL, '51.159.14.95', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/132.0.0.0 Safari/537.3', 'YTozOntzOjY6Il90b2tlbiI7czo0MDoiY1phMGFPQ1NhSjc3cmxIcXZYT3ZZTTdTbzVaaGc0THZrdml5dnN4dCI7czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6MzY6Imh0dHA6Ly9wb3J0YWwub25lY2hhbWJlci5jby5rZS9sb2dpbiI7fXM6NjoiX2ZsYXNoIjthOjI6e3M6Mzoib2xkIjthOjA6e31zOjM6Im5ldyI7YTowOnt9fX0=', 1758863857),
('FmWk3HwhGHOEqbiZxoRbMFEVP2Y9jLpbAIoDelCQ', NULL, '62.210.90.189', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/132.0.0.0 Safari/537.3', 'YTozOntzOjY6Il90b2tlbiI7czo0MDoieEMzODAydkhEYTJNTGRLTUlYOEdjMFdOZWhWeG50U2FuaTduMGd6USI7czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6Mzc6Imh0dHBzOi8vcG9ydGFsLm9uZWNoYW1iZXIuY28ua2UvbG9naW4iO31zOjY6Il9mbGFzaCI7YToyOntzOjM6Im9sZCI7YTowOnt9czozOiJuZXciO2E6MDp7fX19', 1758864159),
('jKyOax77VR7VI92F3QzcY8wmR5EoZOOrROb7pcbb', NULL, '62.210.90.189', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/132.0.0.0 Safari/537.3', 'YTozOntzOjY6Il90b2tlbiI7czo0MDoic2JPa0xiSzVPUHhkUHFsNUNnZXVUZlVzUXFXY1R0TmJKdUJsS015RiI7czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6MzY6Imh0dHA6Ly9wb3J0YWwub25lY2hhbWJlci5jby5rZS9sb2dpbiI7fXM6NjoiX2ZsYXNoIjthOjI6e3M6Mzoib2xkIjthOjA6e31zOjM6Im5ldyI7YTowOnt9fX0=', 1758864159),
('kvBFb7yW5IjB8aJkMyLTgVsa0620ubmBd8urgfre', NULL, '62.210.90.189', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/132.0.0.0 Safari/537.3', 'YTozOntzOjY6Il90b2tlbiI7czo0MDoiMHhPUEx3TWRvSnRxT1FWakhhSE5CYlVTV0lkTnFORm15TUNhck9YNiI7czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6MzY6Imh0dHA6Ly9wb3J0YWwub25lY2hhbWJlci5jby5rZS9sb2dpbiI7fXM6NjoiX2ZsYXNoIjthOjI6e3M6Mzoib2xkIjthOjA6e31zOjM6Im5ldyI7YTowOnt9fX0=', 1758864140),
('O4eS2Kkn51yLZBU1Hyye4xoBgwtGHJnv7BTeD5Bu', NULL, '51.159.14.95', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/132.0.0.0 Safari/537.3', 'YTozOntzOjY6Il90b2tlbiI7czo0MDoiUGFkdlNhdmg3TW1UMmdvcWdGa0xhTFhySmlyRVd4dXVzd2twaE1WdyI7czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6Mzc6Imh0dHBzOi8vcG9ydGFsLm9uZWNoYW1iZXIuY28ua2UvbG9naW4iO31zOjY6Il9mbGFzaCI7YToyOntzOjM6Im9sZCI7YTowOnt9czozOiJuZXciO2E6MDp7fX19', 1758863857),
('Qe40KMhVPpx7mFYBCqBPCRJViGm6g4rVaZfru6og', NULL, '62.210.90.189', 'curl/7.81.0', 'YToyOntzOjY6Il90b2tlbiI7czo0MDoiRnZDbnp2dGtmdU8yTnJFV3NDUXFxUGZmMUZxNlJ6ZURtb3ZGZm9pZyI7czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319fQ==', 1758864153),
('tcrKOHf1I7dHtbgK6uU7z7G5eWfpyvwblGlaheey', NULL, '62.210.90.189', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/132.0.0.0 Safari/537.3', 'YTozOntzOjY6Il90b2tlbiI7czo0MDoiVWZSZDdhMExrMFpoaVpMc05IUmNvajRxN01YNGx6azhVOXdxS3NBVSI7czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6Mzc6Imh0dHBzOi8vcG9ydGFsLm9uZWNoYW1iZXIuY28ua2UvbG9naW4iO31zOjY6Il9mbGFzaCI7YToyOntzOjM6Im9sZCI7YTowOnt9czozOiJuZXciO2E6MDp7fX19', 1758864141);

-- --------------------------------------------------------

--
-- Table structure for table `settings`
--

CREATE TABLE `settings` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `business_name` varchar(255) NOT NULL DEFAULT 'OneChamber LTD',
  `email_from` varchar(255) NOT NULL DEFAULT 'noreply@onechamber.com',
  `support_email` varchar(255) NOT NULL DEFAULT 'support@onechamber.com',
  `phone` varchar(255) DEFAULT NULL,
  `billing_instructions_md` text DEFAULT NULL,
  `address_json` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`address_json`)),
  `default_currency` varchar(255) NOT NULL DEFAULT 'KES',
  `timezone` varchar(255) NOT NULL DEFAULT 'Africa/Nairobi',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `settings`
--

INSERT INTO `settings` (`id`, `business_name`, `email_from`, `support_email`, `phone`, `billing_instructions_md`, `address_json`, `default_currency`, `timezone`, `created_at`, `updated_at`) VALUES
(1, 'OneChamber LTD', 'noreply@onechamber.com', 'support@onechamber.com', '+254 700 000 000', 'Terms\r\nAccount number: 8311797128\r\nAccount type: Deposit\r\nRouting number (for wire and ACH): 026073150\r\nSwift/BIC: CMFGUS33\r\nBank name and address: Community Federal Savings Bank, 89-16 Jamaica Ave, Woodhaven, NY, 11421, United States', '{\"street\":\"Worldwide Printing Center, 4th Floor, Mushebi Road\",\"city\":\"Parklands\",\"state\":\"Nairobi\",\"postal_code\":\"00100\",\"country\":\"Kenya\"}', 'KES', 'Africa/Nairobi', '2025-09-24 17:37:29', '2025-09-24 17:43:01');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `role` enum('admin','customer') NOT NULL DEFAULT 'customer',
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `password` varchar(255) NOT NULL,
  `remember_token` varchar(100) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `name`, `email`, `role`, `email_verified_at`, `password`, `remember_token`, `created_at`, `updated_at`) VALUES
(1, 'Francis', 'francis.nagi@gmail.com', 'admin', '2025-09-24 14:53:54', '$2y$12$oR1RSMP1O1NvceQXCsdSbOSYe9MXvnhwbqyBS11ThQe7HLZuMqg3.', 'v5711nvQFRMoXsbF5jiRcS5b15OI3BsSHC4juWxUI5wGTEicZ4ACHeyYur1N', '2025-09-24 14:53:54', '2025-09-24 14:53:54'),
(2, 'MMC ASAFO', 'imungai@mmcafrica.law', 'customer', NULL, '$2y$12$iKNxQMxrL5x4pMpk9lIZrOY2oQhfeI8Off8.qujUPFcU0o8Dazm8O', NULL, '2025-09-24 15:12:07', '2025-09-24 15:12:07'),
(3, 'Test Customer', 'test@user.com', 'customer', NULL, '$2y$12$fvTk8fKO8znzxBAYPWJ8yuZB1TN/wOuUW.VbMeexmM9KPUZUxj7/6', NULL, '2025-09-25 05:24:14', '2025-09-25 05:24:14');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `customers`
--
ALTER TABLE `customers`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `customers_email_unique` (`email`),
  ADD KEY `customers_user_id_foreign` (`user_id`),
  ADD KEY `customers_is_active_created_at_index` (`is_active`,`created_at`);

--
-- Indexes for table `domains`
--
ALTER TABLE `domains`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `domains_customer_id_fqdn_unique` (`customer_id`,`fqdn`),
  ADD KEY `domains_customer_id_expires_at_index` (`customer_id`,`expires_at`),
  ADD KEY `domains_status_expires_at_index` (`status`,`expires_at`),
  ADD KEY `domains_expires_at_index` (`expires_at`);

--
-- Indexes for table `invoices`
--
ALTER TABLE `invoices`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `invoices_number_unique` (`number`),
  ADD KEY `invoices_customer_id_status_index` (`customer_id`,`status`),
  ADD KEY `invoices_status_due_date_index` (`status`,`due_date`),
  ADD KEY `invoices_due_date_index` (`due_date`),
  ADD KEY `invoices_number_index` (`number`);

--
-- Indexes for table `invoice_items`
--
ALTER TABLE `invoice_items`
  ADD PRIMARY KEY (`id`),
  ADD KEY `invoice_items_invoice_id_item_type_index` (`invoice_id`,`item_type`),
  ADD KEY `invoice_items_item_type_item_id_index` (`item_type`,`item_id`);

--
-- Indexes for table `migrations`
--
ALTER TABLE `migrations`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `payments`
--
ALTER TABLE `payments`
  ADD PRIMARY KEY (`id`),
  ADD KEY `payments_invoice_id_paid_on_index` (`invoice_id`,`paid_on`),
  ADD KEY `payments_method_paid_on_index` (`method`,`paid_on`),
  ADD KEY `payments_paid_on_index` (`paid_on`);

--
-- Indexes for table `services`
--
ALTER TABLE `services`
  ADD PRIMARY KEY (`id`),
  ADD KEY `services_customer_id_status_index` (`customer_id`,`status`);

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
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `users_email_unique` (`email`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `customers`
--
ALTER TABLE `customers`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `domains`
--
ALTER TABLE `domains`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- AUTO_INCREMENT for table `invoices`
--
ALTER TABLE `invoices`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `invoice_items`
--
ALTER TABLE `invoice_items`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `migrations`
--
ALTER TABLE `migrations`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `payments`
--
ALTER TABLE `payments`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `services`
--
ALTER TABLE `services`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `settings`
--
ALTER TABLE `settings`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `customers`
--
ALTER TABLE `customers`
  ADD CONSTRAINT `customers_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `domains`
--
ALTER TABLE `domains`
  ADD CONSTRAINT `domains_customer_id_foreign` FOREIGN KEY (`customer_id`) REFERENCES `customers` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `invoices`
--
ALTER TABLE `invoices`
  ADD CONSTRAINT `invoices_customer_id_foreign` FOREIGN KEY (`customer_id`) REFERENCES `customers` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `invoice_items`
--
ALTER TABLE `invoice_items`
  ADD CONSTRAINT `invoice_items_invoice_id_foreign` FOREIGN KEY (`invoice_id`) REFERENCES `invoices` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `payments`
--
ALTER TABLE `payments`
  ADD CONSTRAINT `payments_invoice_id_foreign` FOREIGN KEY (`invoice_id`) REFERENCES `invoices` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `services`
--
ALTER TABLE `services`
  ADD CONSTRAINT `services_customer_id_foreign` FOREIGN KEY (`customer_id`) REFERENCES `customers` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
