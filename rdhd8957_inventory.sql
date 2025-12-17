-- phpMyAdmin SQL Dump
-- version 5.2.2
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Generation Time: Dec 17, 2025 at 11:14 PM
-- Server version: 10.11.14-MariaDB-cll-lve
-- PHP Version: 8.4.15

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `rdhd8957_inventory`
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

--
-- Dumping data for table `cache`
--

INSERT INTO `cache` (`key`, `value`, `expiration`) VALUES
('laravel-cache-30ccdd91abbb881e7ca041db8a1390f4589fc7d6', 'i:1;', 1765985043),
('laravel-cache-30ccdd91abbb881e7ca041db8a1390f4589fc7d6:timer', 'i:1765985043;', 1765985043),
('laravel-cache-5c785c036466adea360111aa28563bfd556b5fba', 'i:1;', 1765860646),
('laravel-cache-5c785c036466adea360111aa28563bfd556b5fba:timer', 'i:1765860646;', 1765860646),
('laravel-cache-60907c3c5a92bd8470493c7ada4f97d012fadc6d', 'i:1;', 1765878225),
('laravel-cache-60907c3c5a92bd8470493c7ada4f97d012fadc6d:timer', 'i:1765878225;', 1765878225),
('laravel-cache-9b13f5039b1587202399634f03a7c43e6bd3d961', 'i:1;', 1765887921),
('laravel-cache-9b13f5039b1587202399634f03a7c43e6bd3d961:timer', 'i:1765887921;', 1765887921),
('laravel-cache-c0cd222c367f5b8629a40ac2002110e104e8ab7e', 'i:1;', 1765934943),
('laravel-cache-c0cd222c367f5b8629a40ac2002110e104e8ab7e:timer', 'i:1765934943;', 1765934943);

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
-- Table structure for table `inventory_items`
--

CREATE TABLE `inventory_items` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `total_stock` int(11) NOT NULL,
  `available_stock` int(11) NOT NULL,
  `damaged_stock` int(11) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `inventory_items`
--

INSERT INTO `inventory_items` (`id`, `name`, `total_stock`, `available_stock`, `damaged_stock`, `created_at`, `updated_at`) VALUES
(1, 'Laptop Dell', 1, 1, 0, '2025-12-15 21:49:24', '2025-12-16 02:38:24'),
(2, 'Monitor LG', 1, 1, 0, '2025-12-15 21:49:24', '2025-12-16 02:38:53'),
(3, 'Keyboard Logitech', 1, 1, 0, '2025-12-15 21:49:24', '2025-12-17 08:49:01');

-- --------------------------------------------------------

--
-- Table structure for table `inventory_units`
--

CREATE TABLE `inventory_units` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `inventory_item_id` bigint(20) UNSIGNED NOT NULL,
  `serial_number` varchar(255) DEFAULT NULL,
  `photo` varchar(255) DEFAULT NULL,
  `condition_status` enum('available','in_use','damaged','maintenance') NOT NULL DEFAULT 'available',
  `current_holder` varchar(255) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `inventory_units`
--

INSERT INTO `inventory_units` (`id`, `inventory_item_id`, `serial_number`, `photo`, `condition_status`, `current_holder`, `created_at`, `updated_at`) VALUES
(2, 1, '12312313123', 'unit_photos/1765877903_6941288fed020.jpg', 'in_use', 'ANGPEN', '2025-12-16 02:38:24', '2025-12-16 02:43:19'),
(3, 2, '1234444241', 'unit_photos/1765877932_694128ace2eb6.jpg', 'available', 'ANGPEN', '2025-12-16 02:38:53', '2025-12-16 02:38:53'),
(6, 3, '2131231231231', 'unit_photos/1765986541_6942d0edc4415.jpeg', 'available', 'ANGPEN', '2025-12-17 08:49:01', '2025-12-17 08:49:01');

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
(4, '2025_12_12_142217_create_inventory_items_table', 1),
(5, '2025_12_13_115753_add_photo_column_to_inventory_items_table', 1),
(6, '2025_12_13_121459_create_inventory_units_table', 1),
(7, '2025_12_13_123403_remove_photo_from_inventory_items_table', 1),
(8, '2025_12_13_123434_add_photo_to_inventory_units_table', 1),
(9, '2025_12_14_074032_create_permission_tables', 1),
(10, '2025_12_14_075108_add_role_column_to_users_table', 1);

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
(1, 'create inventory', 'web', '2025-12-15 21:49:24', '2025-12-15 21:49:24'),
(2, 'edit inventory', 'web', '2025-12-15 21:49:24', '2025-12-15 21:49:24'),
(3, 'delete inventory', 'web', '2025-12-15 21:49:24', '2025-12-15 21:49:24'),
(4, 'view inventory', 'web', '2025-12-15 21:49:24', '2025-12-15 21:49:24'),
(5, 'create unit', 'web', '2025-12-15 21:49:24', '2025-12-15 21:49:24'),
(6, 'delete unit', 'web', '2025-12-15 21:49:24', '2025-12-15 21:49:24');

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
(1, 'admin', 'web', '2025-12-15 21:49:24', '2025-12-15 21:49:24'),
(2, 'pelaksana', 'web', '2025-12-15 21:49:24', '2025-12-15 21:49:24');

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
(4, 1),
(4, 2),
(5, 1),
(5, 2),
(6, 1);

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
('5cIUEpzJ9MukXtHSy2WBCP93Vz68SsJpzfTuVsjo', NULL, '162.142.125.120', 'Mozilla/5.0 (compatible; CensysInspect/1.1; +https://about.censys.io/)', 'YTozOntzOjY6Il90b2tlbiI7czo0MDoiSVZtcVM0d09sS2lDV1NjMXZIWjlOQlpJanlkTk5jaDVZekQyOTJHNSI7czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6MzE6Imh0dHBzOi8vbWFpbC5yZGhob3N0LnNpdGUvbG9naW4iO3M6NToicm91dGUiO3M6NToibG9naW4iO31zOjY6Il9mbGFzaCI7YToyOntzOjM6Im9sZCI7YTowOnt9czozOiJuZXciO2E6MDp7fX19', 1765982025),
('9EL06gjTj5E3lSwzzNRdXraLimBz4TvoH4FdlMMZ', NULL, '202.170.91.69', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_14_5; iPhone) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/72.0.3942.87 Safari/537.36 HuaweiCrawler', 'YTozOntzOjY6Il90b2tlbiI7czo0MDoiZ0g5VGkyb2sxT0dHYkJrOFBHNm1hdmY2MEJjaUkzTTBTYXM1RTlBdyI7czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6MjY6Imh0dHBzOi8vcmRoaG9zdC5zaXRlL2xvZ2luIjtzOjU6InJvdXRlIjtzOjU6ImxvZ2luIjt9czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319fQ==', 1765981313),
('g0it7eovhMoXuPoyrmoMD8H5OhvG6d4Fc2ZmnoFv', NULL, '100.30.248.17', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/125.0.0.0 Safari/537.36', 'YTozOntzOjY6Il90b2tlbiI7czo0MDoidU1uVDZoMkFDbGY2Mm85WFJRRzZISjVHdzN6OUY2aVBmWTl6SmkxOSI7czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6MjY6Imh0dHBzOi8vcmRoaG9zdC5zaXRlL2xvZ2luIjtzOjU6InJvdXRlIjtzOjU6ImxvZ2luIjt9czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319fQ==', 1765985495),
('GJH4rESROUq5Cp3aZXrPZUkPJOQ3TqxOOngBKOFs', NULL, '43.130.106.18', 'Mozilla/5.0 (iPhone; CPU iPhone OS 13_2_3 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/13.0.3 Mobile/15E148 Safari/604.1', 'YTo0OntzOjY6Il90b2tlbiI7czo0MDoiaTB1cTF2emNVVjRiT0NqS20zcVlXSXFkSEl0SmJxSVV2aEhTSXFndiI7czozOiJ1cmwiO2E6MTp7czo4OiJpbnRlbmRlZCI7czoyMzoiaHR0cDovL3d3dy5yZGhob3N0LnNpdGUiO31zOjk6Il9wcmV2aW91cyI7YToyOntzOjM6InVybCI7czoyMzoiaHR0cDovL3d3dy5yZGhob3N0LnNpdGUiO3M6NToicm91dGUiO3M6OToiZGFzaGJvYXJkIjt9czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319fQ==', 1765985694),
('hAu3OnEgtDYhaW1TmiDxGkXkS0FxS2cxqhKlJisT', NULL, '49.0.237.195', 'Java/1.8.0_322', 'YToyOntzOjY6Il90b2tlbiI7czo0MDoiRGxlUHpHWXIxbzNKeWwwUnpqckFCWmdQaTRybmJEV1lCUkx1QVpWRCI7czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319fQ==', 1765981311),
('jBLkebD7B8oSmMMGTwQiKAr9cIkdHwNTNPax3zBN', NULL, '43.130.139.136', 'Mozilla/5.0 (iPhone; CPU iPhone OS 13_2_3 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/13.0.3 Mobile/15E148 Safari/604.1', 'YTo0OntzOjY6Il90b2tlbiI7czo0MDoiN2wyUU9pdzM2N0E5NGxPNFkyNG9iSUJ4N1hjN1JqVnhIczJpQXBSMCI7czozOiJ1cmwiO2E6MTp7czo4OiJpbnRlbmRlZCI7czoyMzoiaHR0cDovL3d3dy5yZGhob3N0LnNpdGUiO31zOjk6Il9wcmV2aW91cyI7YToyOntzOjM6InVybCI7czoyMzoiaHR0cDovL3d3dy5yZGhob3N0LnNpdGUiO3M6NToicm91dGUiO3M6OToiZGFzaGJvYXJkIjt9czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319fQ==', 1765983781),
('mNUrHy42ufrwa92aG90iADslCZDRzQM5r4hiTNhl', NULL, '100.30.248.17', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/83.0.4093.0 Safari/537.36 Edg/83.0.470.0', 'YTo0OntzOjY6Il90b2tlbiI7czo0MDoiSEptWllFams2NGY1VWpvMzZJeExjdkh0Slk0RnF2N3lNTVNkY0FEQSI7czozOiJ1cmwiO2E6MTp7czo4OiJpbnRlbmRlZCI7czoyNToiaHR0cHM6Ly9tYWlsLnJkaGhvc3Quc2l0ZSI7fXM6OToiX3ByZXZpb3VzIjthOjI6e3M6MzoidXJsIjtzOjI1OiJodHRwczovL21haWwucmRoaG9zdC5zaXRlIjtzOjU6InJvdXRlIjtzOjk6ImRhc2hib2FyZCI7fXM6NjoiX2ZsYXNoIjthOjI6e3M6Mzoib2xkIjthOjA6e31zOjM6Im5ldyI7YTowOnt9fX0=', 1765985495),
('NxjUHEh0CXpYLRI5NhBmT34SfoWchwCgBY23v6dG', 1, '103.81.221.127', 'Mozilla/5.0 (X11; Linux x86_64; rv:146.0) Gecko/20100101 Firefox/146.0', 'YTo1OntzOjY6Il90b2tlbiI7czo0MDoiQ2RUUTJTU2Q5V1pBcWl0ZjRzdWRuVnJwR0NweVVrc1pvZjF6NUZZQiI7czozOiJ1cmwiO2E6MDp7fXM6OToiX3ByZXZpb3VzIjthOjI6e3M6MzoidXJsIjtzOjQyOiJodHRwczovL3JkaGhvc3Quc2l0ZS9pbnZlbnRvcmllcy8zL3VuaXRzLzYiO3M6NToicm91dGUiO3M6MjI6ImludmVudG9yaWVzLnVuaXRzLnNob3ciO31zOjY6Il9mbGFzaCI7YToyOntzOjM6Im9sZCI7YTowOnt9czozOiJuZXciO2E6MDp7fX1zOjUwOiJsb2dpbl93ZWJfNTliYTM2YWRkYzJiMmY5NDAxNTgwZjAxNGM3ZjU4ZWE0ZTMwOTg5ZCI7aToxO30=', 1765987029),
('RUOB1859vSvEIeMe7b0efGCIygwURB8noryM3H0c', NULL, '43.130.139.136', 'Mozilla/5.0 (iPhone; CPU iPhone OS 13_2_3 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/13.0.3 Mobile/15E148 Safari/604.1', 'YTozOntzOjY6Il90b2tlbiI7czo0MDoianlRbklZVzJiekg4Q3VGa3E3bzhyQW44TzRPc1BTNkdzTkppOWU2eiI7czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6Mjk6Imh0dHA6Ly93d3cucmRoaG9zdC5zaXRlL2xvZ2luIjtzOjU6InJvdXRlIjtzOjU6ImxvZ2luIjt9czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319fQ==', 1765983783),
('t0pqFco8l72CI5jnnFDmb96B3UCgLszjmuUd1OEK', NULL, '202.170.91.69', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_14_5; iPhone) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/72.0.3942.87 Safari/537.36 HuaweiCrawler', 'YTo0OntzOjY6Il90b2tlbiI7czo0MDoia3dzRkNBZjd5SU9zUGdkSFozVW93V0IwQ3hCck1rNEhWMVNxYnR4RiI7czozOiJ1cmwiO2E6MTp7czo4OiJpbnRlbmRlZCI7czoyMDoiaHR0cHM6Ly9yZGhob3N0LnNpdGUiO31zOjk6Il9wcmV2aW91cyI7YToyOntzOjM6InVybCI7czoyMDoiaHR0cHM6Ly9yZGhob3N0LnNpdGUiO3M6NToicm91dGUiO3M6OToiZGFzaGJvYXJkIjt9czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319fQ==', 1765981313),
('TSaf7dSiDGHfLRsOj6fuAB9IhdAsJMgGUVmIHim9', NULL, '162.142.125.120', 'Mozilla/5.0 (compatible; CensysInspect/1.1; +https://about.censys.io/)', 'YTo0OntzOjY6Il90b2tlbiI7czo0MDoid0RoNlNybDR5TGhqUWVnaEU2WDlZOHh6QXNRTzM5UExCU2xLdmhPbCI7czozOiJ1cmwiO2E6MTp7czo4OiJpbnRlbmRlZCI7czoyNToiaHR0cHM6Ly9tYWlsLnJkaGhvc3Quc2l0ZSI7fXM6OToiX3ByZXZpb3VzIjthOjI6e3M6MzoidXJsIjtzOjI1OiJodHRwczovL21haWwucmRoaG9zdC5zaXRlIjtzOjU6InJvdXRlIjtzOjk6ImRhc2hib2FyZCI7fXM6NjoiX2ZsYXNoIjthOjI6e3M6Mzoib2xkIjthOjA6e31zOjM6Im5ldyI7YTowOnt9fX0=', 1765982019),
('UVMSczBvfn8SQEZXN826kaFW7qNFiyOMhS8z421y', NULL, '100.30.248.17', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/83.0.4093.0 Safari/537.36 Edg/83.0.470.0', 'YTozOntzOjY6Il90b2tlbiI7czo0MDoiN3ZiM1J1R29zMXJoYVpzN1pwZlA2SXlmc2h5UzhoNTBGVGtLU0g5bCI7czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6MzE6Imh0dHBzOi8vbWFpbC5yZGhob3N0LnNpdGUvbG9naW4iO3M6NToicm91dGUiO3M6NToibG9naW4iO31zOjY6Il9mbGFzaCI7YToyOntzOjM6Im9sZCI7YTowOnt9czozOiJuZXciO2E6MDp7fX19', 1765985495),
('wBQvEfOFNdhf1BMbBtzyWeQaKAmci0Wuu8WrO78L', NULL, '202.170.91.69', 'Java/1.8.0_322', 'YTozOntzOjY6Il90b2tlbiI7czo0MDoiTm5ZYlRCaUtybEZyMXNVMjdlWlNJaFp6cFFmOFZZejd0alNkSEZKNiI7czozOiJ1cmwiO2E6MTp7czo4OiJpbnRlbmRlZCI7czoyMDoiaHR0cHM6Ly9yZGhob3N0LnNpdGUiO31zOjY6Il9mbGFzaCI7YToyOntzOjM6Im9sZCI7YTowOnt9czozOiJuZXciO2E6MDp7fX19', 1765981311),
('WecCHdlLyOhreYD6bnGyKWgv1e5iCC2KiXgshP6P', NULL, '43.130.106.18', 'Mozilla/5.0 (iPhone; CPU iPhone OS 13_2_3 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/13.0.3 Mobile/15E148 Safari/604.1', 'YTozOntzOjY6Il90b2tlbiI7czo0MDoiMTVUU3Y1TFJaTm1iYk96TzJqNHhUOWFPMk1oS3VsdzNTNmF4ZmJyQyI7czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6Mjk6Imh0dHA6Ly93d3cucmRoaG9zdC5zaXRlL2xvZ2luIjtzOjU6InJvdXRlIjtzOjU6ImxvZ2luIjt9czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319fQ==', 1765985695),
('yFtBJOcA9PpHNjXj0S5TiWodwEtEuBgNvffgOzaj', NULL, '100.30.248.17', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/125.0.0.0 Safari/537.36', 'YTo0OntzOjY6Il90b2tlbiI7czo0MDoiQXRqY0p5V0R4eFE3cmRHeFFzaXowd0xqeFRmRmtBdWVSc3lhakowNyI7czozOiJ1cmwiO2E6MTp7czo4OiJpbnRlbmRlZCI7czoyMDoiaHR0cHM6Ly9yZGhob3N0LnNpdGUiO31zOjk6Il9wcmV2aW91cyI7YToyOntzOjM6InVybCI7czoyMDoiaHR0cHM6Ly9yZGhob3N0LnNpdGUiO3M6NToicm91dGUiO3M6OToiZGFzaGJvYXJkIjt9czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319fQ==', 1765985495);

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `role` varchar(255) NOT NULL DEFAULT 'pegawai',
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
(1, 'Admin User', 'admin@example.com', 'admin', NULL, '$2y$12$HemKmgEarmXDbAd6ant22OmH2YTji3N308wt1CjaB52V29co8BHfW', NULL, '2025-12-15 21:49:24', '2025-12-15 21:49:24'),
(2, 'Pegawai User', 'pegawai@example.com', 'pegawai', NULL, '$2y$12$BnuSjgveq0c9IDPzjtGUY.yn5.e6UIJO4ql4gn32bw3Lkcn8A0sXa', NULL, '2025-12-15 21:49:24', '2025-12-15 21:49:24');

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
-- Indexes for table `failed_jobs`
--
ALTER TABLE `failed_jobs`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `failed_jobs_uuid_unique` (`uuid`);

--
-- Indexes for table `inventory_items`
--
ALTER TABLE `inventory_items`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `inventory_items_name_unique` (`name`);

--
-- Indexes for table `inventory_units`
--
ALTER TABLE `inventory_units`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `inventory_units_serial_number_unique` (`serial_number`),
  ADD KEY `inventory_units_inventory_item_id_foreign` (`inventory_item_id`);

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
-- Indexes for table `sessions`
--
ALTER TABLE `sessions`
  ADD PRIMARY KEY (`id`),
  ADD KEY `sessions_user_id_index` (`user_id`),
  ADD KEY `sessions_last_activity_index` (`last_activity`);

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
-- AUTO_INCREMENT for table `failed_jobs`
--
ALTER TABLE `failed_jobs`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `inventory_items`
--
ALTER TABLE `inventory_items`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `inventory_units`
--
ALTER TABLE `inventory_units`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `jobs`
--
ALTER TABLE `jobs`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `migrations`
--
ALTER TABLE `migrations`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `permissions`
--
ALTER TABLE `permissions`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `roles`
--
ALTER TABLE `roles`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `inventory_units`
--
ALTER TABLE `inventory_units`
  ADD CONSTRAINT `inventory_units_inventory_item_id_foreign` FOREIGN KEY (`inventory_item_id`) REFERENCES `inventory_items` (`id`) ON DELETE CASCADE;

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
-- Constraints for table `role_has_permissions`
--
ALTER TABLE `role_has_permissions`
  ADD CONSTRAINT `role_has_permissions_permission_id_foreign` FOREIGN KEY (`permission_id`) REFERENCES `permissions` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `role_has_permissions_role_id_foreign` FOREIGN KEY (`role_id`) REFERENCES `roles` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
