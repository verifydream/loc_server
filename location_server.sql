-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Waktu pembuatan: 04 Nov 2025 pada 08.44
-- Versi server: 10.4.32-MariaDB
-- Versi PHP: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `location_server`
--

-- --------------------------------------------------------

--
-- Struktur dari tabel `admins`
--

CREATE TABLE `admins` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `remember_token` varchar(100) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data untuk tabel `admins`
--

INSERT INTO `admins` (`id`, `name`, `email`, `password`, `remember_token`, `created_at`, `updated_at`) VALUES
(1, 'Administrator', 'admin@locationserver.com', '$2y$10$GlK2ciVgpyHvnTCpJhTRyOGUuucIqzz9vUrWJlIzd0sBSvGEbzSZK', 'wYCcTxbw406MqI3yIZYNjS6pttYrmjTDeorJRd7qYn7VnZTOHQq9CJXzWUuN', '2025-11-02 09:46:31', '2025-11-02 09:46:31');

-- --------------------------------------------------------

--
-- Struktur dari tabel `app_versions`
--

CREATE TABLE `app_versions` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `version_name` varchar(255) NOT NULL,
  `version_code` int(11) NOT NULL,
  `file_path` varchar(255) NOT NULL,
  `release_notes` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data untuk tabel `app_versions`
--

INSERT INTO `app_versions` (`id`, `version_name`, `version_code`, `file_path`, `release_notes`, `created_at`, `updated_at`) VALUES
(1, '1.0.0', 1, 'public/updates/WCeyD1vGFtCU1u7XA6T0KeOpYnH0br7J75n1HV4h.zip', 'test', '2025-11-03 23:26:37', '2025-11-03 23:26:37'),
(2, '1.0.1', 2, 'public/updates/4YScLhvHimgbMcoSDr3gQ5o6CP1gYlQijPtJPQ80.zip', 'test 2', '2025-11-03 23:28:23', '2025-11-03 23:28:23'),
(3, '1.0.2', 3, 'public/updates/app-v1.0.2-1762239609.apk', 'asd as sda', '2025-11-04 07:00:10', '2025-11-04 07:00:10');

-- --------------------------------------------------------

--
-- Struktur dari tabel `failed_jobs`
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
-- Struktur dari tabel `locations`
--

CREATE TABLE `locations` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `location_code` varchar(10) NOT NULL,
  `location_name` varchar(100) NOT NULL,
  `online_url` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data untuk tabel `locations`
--

INSERT INTO `locations` (`id`, `location_code`, `location_name`, `online_url`, `created_at`, `updated_at`) VALUES
(1, 'sby', 'Surabaya', 'https://sby.mydeposys.com', '2025-11-02 09:46:31', '2025-11-02 21:37:35'),
(2, 'jkt', 'Jakarta', 'jkt.web.com', '2025-11-02 09:46:31', '2025-11-02 09:46:31'),
(3, 'blw', 'Belawan', 'blw.web.com', '2025-11-02 09:46:31', '2025-11-02 09:46:31'),
(4, 'smr', 'Semarang', 'smr.web.com', '2025-11-02 09:46:31', '2025-11-02 09:46:31'),
(5, 'bns', 'BNS', 'bns.web.com', '2025-11-02 09:46:31', '2025-11-02 09:46:31'),
(6, 'java', 'Java', 'java.web.com', '2025-11-02 09:46:31', '2025-11-02 09:46:31'),
(7, 'test', 'Test', 'https://apidepotest.dwipakharismamitra.com', '2025-11-02 09:46:31', '2025-11-02 21:16:29'),
(8, 'dev', 'Dev', 'https://dev.mydeposys.com', '2025-11-02 09:46:31', '2025-11-02 18:58:47');

-- --------------------------------------------------------

--
-- Struktur dari tabel `migrations`
--

CREATE TABLE `migrations` (
  `id` int(10) UNSIGNED NOT NULL,
  `migration` varchar(255) NOT NULL,
  `batch` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data untuk tabel `migrations`
--

INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES
(1, '2014_10_11_000000_create_locations_table', 1),
(2, '2014_10_12_000000_create_users_table', 1),
(3, '2014_10_12_100000_create_password_reset_tokens_table', 1),
(4, '2014_10_13_000000_create_admins_table', 1),
(5, '2019_08_19_000000_create_failed_jobs_table', 1),
(6, '2019_12_14_000001_create_personal_access_tokens_table', 1),
(7, '2025_11_04_054436_create_app_versions_table', 2);

-- --------------------------------------------------------

--
-- Struktur dari tabel `password_reset_tokens`
--

CREATE TABLE `password_reset_tokens` (
  `email` varchar(255) NOT NULL,
  `token` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Struktur dari tabel `personal_access_tokens`
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
-- Struktur dari tabel `users`
--

CREATE TABLE `users` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `email` varchar(255) NOT NULL,
  `location_id` bigint(20) UNSIGNED NOT NULL,
  `status` enum('active','inactive') NOT NULL DEFAULT 'active',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data untuk tabel `users`
--

INSERT INTO `users` (`id`, `email`, `location_id`, `status`, `created_at`, `updated_at`) VALUES
(1, 'sby_survey@web.com', 1, 'active', '2025-11-02 09:51:49', '2025-11-02 09:51:49'),
(2, 'sby_crani@web.com', 1, 'active', '2025-11-02 09:51:49', '2025-11-02 09:51:49'),
(4, 'jkt_crani@web.com', 2, 'active', '2025-11-02 09:51:49', '2025-11-02 09:51:49'),
(5, 'blw_survey@web.com', 3, 'active', '2025-11-02 09:51:49', '2025-11-02 09:51:49'),
(6, 'blw_crani@web.com', 3, 'active', '2025-11-02 09:51:49', '2025-11-02 09:51:49'),
(7, 'smr_survey@web.com', 4, 'active', '2025-11-02 09:51:49', '2025-11-02 09:51:49'),
(8, 'smr_crani@web.com', 4, 'active', '2025-11-02 09:51:49', '2025-11-02 09:51:49'),
(17, 'test_survey@dkm', 7, 'active', '2025-11-02 19:00:03', '2025-11-02 19:00:03'),
(18, 'test_crani@dkm', 7, 'active', '2025-11-02 19:03:25', '2025-11-02 19:03:25'),
(19, 'test_multi@dkm', 7, 'active', '2025-11-02 19:03:39', '2025-11-02 19:03:39'),
(20, 'dev_survey@dkm', 8, 'active', '2025-11-02 19:03:55', '2025-11-02 19:03:55'),
(21, 'dev_crani@dkm', 8, 'active', '2025-11-02 19:04:05', '2025-11-02 19:04:05'),
(22, 'dev_multi@dkm', 8, 'active', '2025-11-02 19:04:15', '2025-11-02 19:04:15'),
(24, 'test@example.com', 3, 'active', '2025-11-03 01:01:46', '2025-11-03 01:01:46'),
(25, 'admin@siac.com', 5, 'active', '2025-11-03 01:01:59', '2025-11-03 01:01:59'),
(26, 'azdaha4545@gmail.com', 2, 'inactive', '2025-11-03 01:02:12', '2025-11-03 01:09:42');

--
-- Indexes for dumped tables
--

--
-- Indeks untuk tabel `admins`
--
ALTER TABLE `admins`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `admins_email_unique` (`email`),
  ADD KEY `admins_email_index` (`email`);

--
-- Indeks untuk tabel `app_versions`
--
ALTER TABLE `app_versions`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `app_versions_version_code_unique` (`version_code`);

--
-- Indeks untuk tabel `failed_jobs`
--
ALTER TABLE `failed_jobs`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `failed_jobs_uuid_unique` (`uuid`);

--
-- Indeks untuk tabel `locations`
--
ALTER TABLE `locations`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `locations_location_code_unique` (`location_code`),
  ADD KEY `locations_location_code_index` (`location_code`);

--
-- Indeks untuk tabel `migrations`
--
ALTER TABLE `migrations`
  ADD PRIMARY KEY (`id`);

--
-- Indeks untuk tabel `password_reset_tokens`
--
ALTER TABLE `password_reset_tokens`
  ADD PRIMARY KEY (`email`);

--
-- Indeks untuk tabel `personal_access_tokens`
--
ALTER TABLE `personal_access_tokens`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `personal_access_tokens_token_unique` (`token`),
  ADD KEY `personal_access_tokens_tokenable_type_tokenable_id_index` (`tokenable_type`,`tokenable_id`);

--
-- Indeks untuk tabel `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `users_email_unique` (`email`),
  ADD KEY `users_email_index` (`email`),
  ADD KEY `users_location_id_index` (`location_id`),
  ADD KEY `users_status_index` (`status`);

--
-- AUTO_INCREMENT untuk tabel yang dibuang
--

--
-- AUTO_INCREMENT untuk tabel `admins`
--
ALTER TABLE `admins`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT untuk tabel `app_versions`
--
ALTER TABLE `app_versions`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT untuk tabel `failed_jobs`
--
ALTER TABLE `failed_jobs`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT untuk tabel `locations`
--
ALTER TABLE `locations`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT untuk tabel `migrations`
--
ALTER TABLE `migrations`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT untuk tabel `personal_access_tokens`
--
ALTER TABLE `personal_access_tokens`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT untuk tabel `users`
--
ALTER TABLE `users`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=27;

--
-- Ketidakleluasaan untuk tabel pelimpahan (Dumped Tables)
--

--
-- Ketidakleluasaan untuk tabel `users`
--
ALTER TABLE `users`
  ADD CONSTRAINT `users_location_id_foreign` FOREIGN KEY (`location_id`) REFERENCES `locations` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
