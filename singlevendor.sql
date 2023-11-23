-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Generation Time: Oct 14, 2023 at 06:26 PM
-- Server version: 10.4.28-MariaDB
-- PHP Version: 8.2.4

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `singlevendor`
--

-- --------------------------------------------------------

--
-- Table structure for table `currencies`
--

CREATE TABLE `currencies` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `dinarPrice` decimal(8,2) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `currencies`
--

INSERT INTO `currencies` (`id`, `dinarPrice`, `created_at`, `updated_at`) VALUES
(1, 1550.00, '2023-10-11 11:24:28', '2023-10-11 11:30:55');

-- --------------------------------------------------------

--
-- Table structure for table `customers`
--

CREATE TABLE `customers` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `phone` varchar(255) NOT NULL,
  `address` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `customers`
--

INSERT INTO `customers` (`id`, `name`, `phone`, `address`, `created_at`, `updated_at`) VALUES
(1, 'Mohammed', '07504749450', 'fghfgh', '2023-10-14 11:03:12', '2023-10-14 11:03:12');

-- --------------------------------------------------------

--
-- Table structure for table `customer_payments`
--

CREATE TABLE `customer_payments` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `amount` decimal(30,2) NOT NULL,
  `note` varchar(255) NOT NULL,
  `customers_id` bigint(20) UNSIGNED DEFAULT NULL,
  `selling_invoices_id` bigint(20) UNSIGNED DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `priceType` varchar(255) NOT NULL DEFAULT '$',
  `dolarPrice` decimal(8,2) NOT NULL,
  `user_name` varchar(255) DEFAULT NULL,
  `discount` decimal(30,2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `customer_payments`
--

INSERT INTO `customer_payments` (`id`, `amount`, `note`, `customers_id`, `selling_invoices_id`, `created_at`, `updated_at`, `priceType`, `dolarPrice`, `user_name`, `discount`) VALUES
(1, 37419.35, '.', 1, NULL, '2023-10-14 11:05:04', '2023-10-14 11:05:04', 'د.ع', 1550.00, 'محمد جلال حمد', 125000.00);

-- --------------------------------------------------------

--
-- Table structure for table `employees`
--

CREATE TABLE `employees` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `DOB` date NOT NULL,
  `IDCardType` varchar(255) DEFAULT NULL,
  `IDCardNumber` varchar(255) DEFAULT NULL,
  `DateOfWork` date NOT NULL,
  `salary` decimal(30,2) NOT NULL,
  `lasSalary` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `phoneNumber` varchar(255) DEFAULT NULL,
  `salaryType` int(11) NOT NULL,
  `address` varchar(255) NOT NULL,
  `totalAbsense` int(11) NOT NULL DEFAULT 0,
  `monthAbsense` int(11) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `employees_absenses`
--

CREATE TABLE `employees_absenses` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `employees_id` bigint(20) UNSIGNED DEFAULT 1,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `expenses`
--

CREATE TABLE `expenses` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `expenses_type_id` bigint(20) UNSIGNED DEFAULT NULL,
  `note` varchar(255) DEFAULT NULL,
  `priceType` int(11) NOT NULL DEFAULT 0,
  `amount` decimal(30,2) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `user_name` varchar(255) NOT NULL,
  `name` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `expenses`
--

INSERT INTO `expenses` (`id`, `expenses_type_id`, `note`, `priceType`, `amount`, `created_at`, `updated_at`, `user_name`, `name`) VALUES
(1, 1, '.', 0, 0.00, '2023-10-14 09:57:46', '2023-10-14 09:57:46', 'محمد جلال حمد', '.'),
(2, 1, '.', 0, 1257.00, '2023-10-14 11:00:29', '2023-10-14 11:00:29', 'محمد جلال حمد', 'محمد جلال حمد'),
(3, 1, '250', 1, 25000000.00, '2023-10-14 11:00:51', '2023-10-14 11:00:51', 'محمد جلال حمد', 'محمد جلال حمد');

-- --------------------------------------------------------

--
-- Table structure for table `expenses_balances`
--

CREATE TABLE `expenses_balances` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `dinarBalance` decimal(8,2) NOT NULL,
  `dollarBalance` decimal(8,2) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `status` int(1) NOT NULL DEFAULT 0,
  `starus1` int(1) NOT NULL DEFAULT 0,
  `status2` int(1) NOT NULL DEFAULT 0,
  `status3` int(1) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `expenses_balance_exchanges`
--

CREATE TABLE `expenses_balance_exchanges` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `note` varchar(255) NOT NULL,
  `priceType` varchar(255) NOT NULL DEFAULT '$',
  `amount` decimal(30,2) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `expenses_balances_id` bigint(20) UNSIGNED DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `expenses_types`
--

CREATE TABLE `expenses_types` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `ExpenseType` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `expenses_types`
--

INSERT INTO `expenses_types` (`id`, `ExpenseType`, `created_at`, `updated_at`) VALUES
(1, 'راتب', '2023-10-14 09:57:39', '2023-10-14 09:57:39');

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
-- Table structure for table `migrations`
--

CREATE TABLE `migrations` (
  `id` int(10) UNSIGNED NOT NULL,
  `migration` varchar(255) NOT NULL,
  `batch` int(11) NOT NULL
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
-- Table structure for table `personal_access_tokens`
--

CREATE TABLE `personal_access_tokens` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `tokenable_type` varchar(255) NOT NULL,
  `tokenable_id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `token` varchar(64) NOT NULL,
  `abilities` text DEFAULT NULL,
  `last_used_at` timestamp NULL DEFAULT NULL,
  `expires_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `purchase_products`
--

CREATE TABLE `purchase_products` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `code` varchar(255) NOT NULL,
  `unit` varchar(255) NOT NULL,
  `purchasePricw` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `status` int(1) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `purchase_products`
--

INSERT INTO `purchase_products` (`id`, `name`, `code`, `unit`, `purchasePricw`, `created_at`, `updated_at`, `status`) VALUES
(1, 'یورو', 'fgfgj', 'fgjhghj', '550', '2023-10-14 11:10:14', '2023-10-14 11:10:14', 0);

-- --------------------------------------------------------

--
-- Table structure for table `purchasing_invoices`
--

CREATE TABLE `purchasing_invoices` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `amount` decimal(8,2) NOT NULL DEFAULT 0.00,
  `paymented` decimal(8,2) NOT NULL DEFAULT 0.00,
  `vendors_id` bigint(20) UNSIGNED DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `invoice_id` varchar(255) NOT NULL,
  `status` int(1) NOT NULL DEFAULT 0,
  `note` varchar(255) DEFAULT NULL,
  `priceType` varchar(255) NOT NULL DEFAULT '$',
  `dolarPrice` decimal(8,2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `purchasing_invoices`
--

INSERT INTO `purchasing_invoices` (`id`, `amount`, `paymented`, `vendors_id`, `created_at`, `updated_at`, `invoice_id`, `status`, `note`, `priceType`, `dolarPrice`) VALUES
(1, 302500.00, 302500.00, 1, '2023-10-14 11:10:44', '2023-10-14 11:11:50', '50', 0, NULL, '$', 1550.00);

-- --------------------------------------------------------

--
-- Table structure for table `purchasing_invoice_products`
--

CREATE TABLE `purchasing_invoice_products` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `qty` int(11) NOT NULL,
  `purchase_price` decimal(8,2) NOT NULL,
  `purchasing_invoices_id` bigint(20) UNSIGNED DEFAULT NULL,
  `purchase_products_id` bigint(20) UNSIGNED DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `total` int(1) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `purchasing_invoice_products`
--

INSERT INTO `purchasing_invoice_products` (`id`, `qty`, `purchase_price`, `purchasing_invoices_id`, `purchase_products_id`, `created_at`, `updated_at`, `total`) VALUES
(1, 550, 550.00, 1, 1, '2023-10-14 11:11:05', '2023-10-14 11:11:05', 0);

-- --------------------------------------------------------

--
-- Table structure for table `reciepts`
--

CREATE TABLE `reciepts` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `partnersName` varchar(255) NOT NULL,
  `note` varchar(255) NOT NULL,
  `type` int(11) NOT NULL,
  `priceType` varchar(255) NOT NULL,
  `amount` decimal(30,2) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `reciepts`
--

INSERT INTO `reciepts` (`id`, `partnersName`, `note`, `type`, `priceType`, `amount`, `created_at`, `updated_at`) VALUES
(1, 'صبر محمد مولود', '.', 0, '$', 0.00, '2023-10-14 09:58:17', '2023-10-14 09:58:17'),
(2, 'ئەیاد عبدولشریف', '.', 0, '$', 0.00, '2023-10-14 09:58:59', '2023-10-14 09:58:59'),
(3, 'ئەشقی ئەحمەد ئیبراهیم', '.', 0, '$', 0.00, '2023-10-14 09:59:10', '2023-10-14 09:59:10'),
(4, 'صبر محمد مولود', '.', 0, 'د.ع', 0.00, '2023-10-14 09:59:24', '2023-10-14 09:59:24'),
(5, 'ئەیاد عبدولشریف', '.', 0, 'د.ع', 0.00, '2023-10-14 09:59:36', '2023-10-14 09:59:36'),
(6, 'ئەشقی ئەحمەد ئیبراهیم', '.', 0, 'د.ع', 0.00, '2023-10-14 09:59:50', '2023-10-14 09:59:50'),
(7, 'صبر محمد مولود', '.', 0, '$', 1500.00, '2023-10-14 10:57:28', '2023-10-14 10:57:28'),
(8, 'ئەیاد عبدولشریف', '.', 0, '$', 1500.00, '2023-10-14 10:57:39', '2023-10-14 10:57:39'),
(9, 'ئەشقی ئەحمەد ئیبراهیم', '.', 0, '$', 1500.00, '2023-10-14 10:57:49', '2023-10-14 10:57:49'),
(10, 'صبر محمد مولود', '.', 0, 'د.ع', 20000000.00, '2023-10-14 10:58:22', '2023-10-14 10:58:22'),
(11, 'ئەیاد عبدولشریف', '.', 0, 'د.ع', 20000000.00, '2023-10-14 10:58:34', '2023-10-14 10:58:34'),
(12, 'ئەشقی ئەحمەد ئیبراهیم', '.', 0, 'د.ع', 200000000.00, '2023-10-14 10:58:48', '2023-10-14 10:58:48'),
(13, 'ئەشقی ئەحمەد ئیبراهیم', '.', 1, 'د.ع', 180000000.00, '2023-10-14 10:59:10', '2023-10-14 10:59:10');

-- --------------------------------------------------------

--
-- Table structure for table `selling_invoices`
--

CREATE TABLE `selling_invoices` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `customers_id` bigint(20) UNSIGNED DEFAULT NULL,
  `amount` decimal(30,2) NOT NULL DEFAULT 0.00,
  `paymented` decimal(30,2) NOT NULL DEFAULT 0.00,
  `status` int(11) DEFAULT NULL,
  `note` varchar(255) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `priceType` varchar(255) NOT NULL DEFAULT '$',
  `dolarPrice` decimal(8,2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `selling_invoices`
--

INSERT INTO `selling_invoices` (`id`, `customers_id`, `amount`, `paymented`, `status`, `note`, `created_at`, `updated_at`, `priceType`, `dolarPrice`) VALUES
(1, 1, 37500.00, 37500.00, NULL, '.', '2023-10-14 11:03:16', '2023-10-14 11:05:04', '$', 1550.00);

-- --------------------------------------------------------

--
-- Table structure for table `selling_invoice_products`
--

CREATE TABLE `selling_invoice_products` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `sallingPrice` decimal(8,2) NOT NULL,
  `qty` int(11) NOT NULL,
  `total` int(11) NOT NULL DEFAULT 0,
  `selling_products_id` bigint(20) UNSIGNED DEFAULT NULL,
  `selling_invoices_id` bigint(20) UNSIGNED DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `selling_invoice_products`
--

INSERT INTO `selling_invoice_products` (`id`, `sallingPrice`, `qty`, `total`, `selling_products_id`, `selling_invoices_id`, `created_at`, `updated_at`) VALUES
(1, 25.00, 1500, 0, 1, 1, '2023-10-14 11:03:31', '2023-10-14 11:03:31');

-- --------------------------------------------------------

--
-- Table structure for table `selling_products`
--

CREATE TABLE `selling_products` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `code` varchar(255) NOT NULL,
  `unit` varchar(255) NOT NULL,
  `salePrice` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `colorCofe` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `selling_products`
--

INSERT INTO `selling_products` (`id`, `name`, `code`, `unit`, `salePrice`, `created_at`, `updated_at`, `colorCofe`) VALUES
(1, 'لقی یەکەم', 'cb', 'مەتر', '25', '2023-10-14 11:02:52', '2023-10-14 11:02:52', '26');

-- --------------------------------------------------------

--
-- Table structure for table `used_products`
--

CREATE TABLE `used_products` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `qty` decimal(30,2) NOT NULL,
  `purchase_products_id` bigint(20) UNSIGNED DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `password` varchar(255) NOT NULL,
  `role` int(11) NOT NULL DEFAULT 0,
  `remember_token` varchar(100) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `name`, `email`, `email_verified_at`, `password`, `role`, `remember_token`, `created_at`, `updated_at`) VALUES
(1, 'محمد جلال حمد', 'admin@admin.com', NULL, '$2y$10$GhWWCUO3ULlQ8gYmOnKJo.Ag4eflKrE5l/aKKHHn8hbyuVZ2Jipxy', 0, 'SWMRZEnvyJMmPEdbcAOpuxwBm4YoGKdc3OP2P8fRKJgb6Ei4qdK9nyC8driA', '2023-10-09 05:27:19', '2023-10-09 05:27:19');

-- --------------------------------------------------------

--
-- Table structure for table `vendors`
--

CREATE TABLE `vendors` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `phone` varchar(255) NOT NULL,
  `address` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `vendors`
--

INSERT INTO `vendors` (`id`, `name`, `phone`, `address`, `created_at`, `updated_at`) VALUES
(1, 'Mohammed', '07504749450', 'fghfgh', '2023-10-14 11:10:40', '2023-10-14 11:10:40');

-- --------------------------------------------------------

--
-- Table structure for table `vendor_payments`
--

CREATE TABLE `vendor_payments` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `amount` decimal(8,2) NOT NULL,
  `note` varchar(255) NOT NULL,
  `vendors_id` bigint(20) UNSIGNED DEFAULT NULL,
  `purchasing_invoices_id` bigint(20) UNSIGNED DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `priceType` varchar(255) NOT NULL DEFAULT '$',
  `dolarPrice` decimal(8,2) NOT NULL,
  `user_name` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `vendor_payments`
--

INSERT INTO `vendor_payments` (`id`, `amount`, `note`, `vendors_id`, `purchasing_invoices_id`, `created_at`, `updated_at`, `priceType`, `dolarPrice`, `user_name`) VALUES
(1, 302500.00, '.', 1, NULL, '2023-10-14 11:11:50', '2023-10-14 11:11:50', 'د.ع', 1550.00, 'محمد جلال حمد');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `currencies`
--
ALTER TABLE `currencies`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `customers`
--
ALTER TABLE `customers`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `customer_payments`
--
ALTER TABLE `customer_payments`
  ADD PRIMARY KEY (`id`),
  ADD KEY `customer_payments_customers_id_foreign` (`customers_id`),
  ADD KEY `customer_payments_selling_invoices_id_foreign` (`selling_invoices_id`);

--
-- Indexes for table `employees`
--
ALTER TABLE `employees`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `employees_absenses`
--
ALTER TABLE `employees_absenses`
  ADD PRIMARY KEY (`id`),
  ADD KEY `employees_absenses_employees_id_foreign` (`employees_id`);

--
-- Indexes for table `expenses`
--
ALTER TABLE `expenses`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fotreign1` (`expenses_type_id`);

--
-- Indexes for table `expenses_balances`
--
ALTER TABLE `expenses_balances`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `expenses_balance_exchanges`
--
ALTER TABLE `expenses_balance_exchanges`
  ADD PRIMARY KEY (`id`),
  ADD KEY `expenses_balance_exchanges_expenses_balances_id_foreign` (`expenses_balances_id`);

--
-- Indexes for table `expenses_types`
--
ALTER TABLE `expenses_types`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `failed_jobs`
--
ALTER TABLE `failed_jobs`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `failed_jobs_uuid_unique` (`uuid`);

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
-- Indexes for table `personal_access_tokens`
--
ALTER TABLE `personal_access_tokens`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `personal_access_tokens_token_unique` (`token`),
  ADD KEY `personal_access_tokens_tokenable_type_tokenable_id_index` (`tokenable_type`,`tokenable_id`);

--
-- Indexes for table `purchase_products`
--
ALTER TABLE `purchase_products`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `purchasing_invoices`
--
ALTER TABLE `purchasing_invoices`
  ADD PRIMARY KEY (`id`),
  ADD KEY `purchasing_invoices_vendors_id_foreign` (`vendors_id`);

--
-- Indexes for table `purchasing_invoice_products`
--
ALTER TABLE `purchasing_invoice_products`
  ADD PRIMARY KEY (`id`),
  ADD KEY `purchasing_invoice_products_purchase_products_id_foreign` (`purchase_products_id`),
  ADD KEY `purchasing_invoice_products_purchasing_invoices_id_foreign` (`purchasing_invoices_id`);

--
-- Indexes for table `reciepts`
--
ALTER TABLE `reciepts`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `selling_invoices`
--
ALTER TABLE `selling_invoices`
  ADD PRIMARY KEY (`id`),
  ADD KEY `selling_invoices_customers_id_foreign` (`customers_id`);

--
-- Indexes for table `selling_invoice_products`
--
ALTER TABLE `selling_invoice_products`
  ADD PRIMARY KEY (`id`),
  ADD KEY `selling_invoice_products_selling_products_id_foreign` (`selling_products_id`),
  ADD KEY `selling_invoice_products_selling_invoices_id_foreign` (`selling_invoices_id`);

--
-- Indexes for table `selling_products`
--
ALTER TABLE `selling_products`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `used_products`
--
ALTER TABLE `used_products`
  ADD PRIMARY KEY (`id`),
  ADD KEY `used_products_purchase_products_id_foreign` (`purchase_products_id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `users_email_unique` (`email`);

--
-- Indexes for table `vendors`
--
ALTER TABLE `vendors`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `vendor_payments`
--
ALTER TABLE `vendor_payments`
  ADD PRIMARY KEY (`id`),
  ADD KEY `vendor_payments_vendors_id_foreign` (`vendors_id`),
  ADD KEY `vendor_payments_purchasing_invoices_id_foreign` (`purchasing_invoices_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `currencies`
--
ALTER TABLE `currencies`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `customers`
--
ALTER TABLE `customers`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `customer_payments`
--
ALTER TABLE `customer_payments`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `employees`
--
ALTER TABLE `employees`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `employees_absenses`
--
ALTER TABLE `employees_absenses`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `expenses`
--
ALTER TABLE `expenses`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `expenses_balances`
--
ALTER TABLE `expenses_balances`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `expenses_balance_exchanges`
--
ALTER TABLE `expenses_balance_exchanges`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `expenses_types`
--
ALTER TABLE `expenses_types`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `failed_jobs`
--
ALTER TABLE `failed_jobs`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `migrations`
--
ALTER TABLE `migrations`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `personal_access_tokens`
--
ALTER TABLE `personal_access_tokens`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `purchase_products`
--
ALTER TABLE `purchase_products`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `purchasing_invoices`
--
ALTER TABLE `purchasing_invoices`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `purchasing_invoice_products`
--
ALTER TABLE `purchasing_invoice_products`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `reciepts`
--
ALTER TABLE `reciepts`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- AUTO_INCREMENT for table `selling_invoices`
--
ALTER TABLE `selling_invoices`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `selling_invoice_products`
--
ALTER TABLE `selling_invoice_products`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `selling_products`
--
ALTER TABLE `selling_products`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `used_products`
--
ALTER TABLE `used_products`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `vendors`
--
ALTER TABLE `vendors`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `vendor_payments`
--
ALTER TABLE `vendor_payments`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `customer_payments`
--
ALTER TABLE `customer_payments`
  ADD CONSTRAINT `customer_payments_customers_id_foreign` FOREIGN KEY (`customers_id`) REFERENCES `customers` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `customer_payments_selling_invoices_id_foreign` FOREIGN KEY (`selling_invoices_id`) REFERENCES `selling_invoices` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `employees_absenses`
--
ALTER TABLE `employees_absenses`
  ADD CONSTRAINT `employees_absenses_employees_id_foreign` FOREIGN KEY (`employees_id`) REFERENCES `employees` (`id`) ON DELETE SET NULL;

--
-- Constraints for table `expenses`
--
ALTER TABLE `expenses`
  ADD CONSTRAINT `fotreign1` FOREIGN KEY (`expenses_type_id`) REFERENCES `expenses_types` (`id`) ON DELETE SET NULL;

--
-- Constraints for table `expenses_balance_exchanges`
--
ALTER TABLE `expenses_balance_exchanges`
  ADD CONSTRAINT `expenses_balance_exchanges_expenses_balances_id_foreign` FOREIGN KEY (`expenses_balances_id`) REFERENCES `expenses_balances` (`id`) ON DELETE SET NULL;

--
-- Constraints for table `purchasing_invoices`
--
ALTER TABLE `purchasing_invoices`
  ADD CONSTRAINT `purchasing_invoices_vendors_id_foreign` FOREIGN KEY (`vendors_id`) REFERENCES `vendors` (`id`) ON DELETE SET NULL;

--
-- Constraints for table `purchasing_invoice_products`
--
ALTER TABLE `purchasing_invoice_products`
  ADD CONSTRAINT `purchasing_invoice_products_purchase_products_id_foreign` FOREIGN KEY (`purchase_products_id`) REFERENCES `purchase_products` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `purchasing_invoice_products_purchasing_invoices_id_foreign` FOREIGN KEY (`purchasing_invoices_id`) REFERENCES `purchasing_invoices` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `selling_invoices`
--
ALTER TABLE `selling_invoices`
  ADD CONSTRAINT `selling_invoices_customers_id_foreign` FOREIGN KEY (`customers_id`) REFERENCES `customers` (`id`) ON DELETE SET NULL;

--
-- Constraints for table `selling_invoice_products`
--
ALTER TABLE `selling_invoice_products`
  ADD CONSTRAINT `selling_invoice_products_selling_invoices_id_foreign` FOREIGN KEY (`selling_invoices_id`) REFERENCES `selling_invoices` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `selling_invoice_products_selling_products_id_foreign` FOREIGN KEY (`selling_products_id`) REFERENCES `selling_products` (`id`) ON DELETE SET NULL;

--
-- Constraints for table `used_products`
--
ALTER TABLE `used_products`
  ADD CONSTRAINT `used_products_purchase_products_id_foreign` FOREIGN KEY (`purchase_products_id`) REFERENCES `purchase_products` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `vendor_payments`
--
ALTER TABLE `vendor_payments`
  ADD CONSTRAINT `vendor_payments_purchasing_invoices_id_foreign` FOREIGN KEY (`purchasing_invoices_id`) REFERENCES `purchasing_invoices` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `vendor_payments_vendors_id_foreign` FOREIGN KEY (`vendors_id`) REFERENCES `vendors` (`id`) ON DELETE SET NULL;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
