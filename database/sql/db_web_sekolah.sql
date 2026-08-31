-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Waktu pembuatan: 30 Agu 2026 pada 18.12
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
-- Database: `db_web_sekolah`
--

-- --------------------------------------------------------

--
-- Struktur dari tabel `achievements`
--

CREATE TABLE `achievements` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `title` varchar(255) NOT NULL,
  `description` text DEFAULT NULL,
  `image_url` varchar(255) DEFAULT NULL,
  `extracurricular_id` bigint(20) UNSIGNED DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Struktur dari tabel `activity_logs`
--

CREATE TABLE `activity_logs` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `user_id` bigint(20) UNSIGNED DEFAULT NULL,
  `action` varchar(255) NOT NULL,
  `table_affected` varchar(255) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Struktur dari tabel `banners`
--

CREATE TABLE `banners` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `image_url` varchar(255) NOT NULL,
  `link_url` varchar(255) DEFAULT NULL,
  `sort_order` int(11) NOT NULL DEFAULT 0,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Struktur dari tabel `categories`
--

CREATE TABLE `categories` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `category_name` varchar(255) NOT NULL,
  `slug` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Struktur dari tabel `classes`
--

CREATE TABLE `classes` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `class_name` varchar(255) NOT NULL,
  `program_id` bigint(20) UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data untuk tabel `classes`
--

INSERT INTO `classes` (`id`, `class_name`, `program_id`, `created_at`, `updated_at`) VALUES
(1, 'RPL 1', 1, '2026-08-30 09:11:12', '2026-08-30 09:11:12'),
(2, 'RPL 2', 1, '2026-08-30 09:11:12', '2026-08-30 09:11:12'),
(3, 'TKJ 1', 2, '2026-08-30 09:11:12', '2026-08-30 09:11:12'),
(4, 'TKJ 2', 2, '2026-08-30 09:11:12', '2026-08-30 09:11:12'),
(5, 'AK 1', 3, '2026-08-30 09:11:12', '2026-08-30 09:11:12');

-- --------------------------------------------------------

--
-- Struktur dari tabel `contact_messages`
--

CREATE TABLE `contact_messages` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `sender_name` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `message` text NOT NULL,
  `status` enum('unread','read') NOT NULL DEFAULT 'unread',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Struktur dari tabel `dudi_partners`
--

CREATE TABLE `dudi_partners` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `company_name` varchar(255) NOT NULL,
  `industry_field` varchar(255) DEFAULT NULL,
  `logo_url` varchar(255) DEFAULT NULL,
  `mou_document` varchar(255) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Struktur dari tabel `events`
--

CREATE TABLE `events` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `title` varchar(255) NOT NULL,
  `description` text DEFAULT NULL,
  `event_date` date NOT NULL,
  `created_by` bigint(20) UNSIGNED DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Struktur dari tabel `extracurriculars`
--

CREATE TABLE `extracurriculars` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `description` text DEFAULT NULL,
  `schedule` varchar(255) DEFAULT NULL,
  `coach_id` bigint(20) UNSIGNED DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Struktur dari tabel `extracurricular_registrations`
--

CREATE TABLE `extracurricular_registrations` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `student_id` bigint(20) UNSIGNED NOT NULL,
  `extracurricular_id` bigint(20) UNSIGNED NOT NULL,
  `status` enum('pending','approved') NOT NULL DEFAULT 'pending',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Struktur dari tabel `facilities`
--

CREATE TABLE `facilities` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `facility_name` varchar(255) NOT NULL,
  `image_url` varchar(255) DEFAULT NULL,
  `program_id` bigint(20) UNSIGNED DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Struktur dari tabel `job_vacancies`
--

CREATE TABLE `job_vacancies` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `job_title` varchar(255) NOT NULL,
  `description` text DEFAULT NULL,
  `dudi_id` bigint(20) UNSIGNED NOT NULL,
  `status` enum('open','closed') NOT NULL DEFAULT 'open',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Struktur dari tabel `learning_modules`
--

CREATE TABLE `learning_modules` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `title` varchar(255) NOT NULL,
  `file_url` varchar(255) NOT NULL,
  `program_id` bigint(20) UNSIGNED NOT NULL,
  `teacher_id` bigint(20) UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Struktur dari tabel `menus`
--

CREATE TABLE `menus` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `label` varchar(255) NOT NULL,
  `url` varchar(255) NOT NULL,
  `sort_order` int(11) NOT NULL DEFAULT 0,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

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
(1, '2024_01_01_000001_create_users_table', 1),
(2, '2024_01_01_000002_create_roles_table', 1),
(3, '2024_01_01_000003_create_permissions_table', 1),
(4, '2024_01_01_000004_create_role_user_table', 1),
(5, '2024_01_01_000005_create_role_permission_table', 1),
(6, '2024_01_01_000006_create_activity_logs_table', 1),
(7, '2024_01_01_000007_create_programs_table', 1),
(8, '2024_01_01_000008_create_classes_table', 1),
(9, '2024_01_01_000009_create_students_table', 1),
(10, '2024_01_01_000010_create_subjects_table', 1),
(11, '2024_01_01_000011_create_teacher_class_subject_table', 1),
(12, '2024_01_01_000012_create_categories_table', 1),
(13, '2024_01_01_000013_create_posts_table', 1),
(14, '2024_01_01_000014_create_events_table', 1),
(15, '2024_01_01_000015_create_banners_table', 1),
(16, '2024_01_01_000016_create_menus_table', 1),
(17, '2024_01_01_000017_create_site_settings_table', 1),
(18, '2024_01_01_000018_create_contact_messages_table', 1),
(19, '2024_01_01_000019_create_facilities_table', 1),
(20, '2024_01_01_000020_create_learning_modules_table', 1),
(21, '2024_01_01_000021_create_student_grades_table', 1),
(22, '2024_01_01_000022_create_dudi_partners_table', 1),
(23, '2024_01_01_000023_create_job_vacancies_table', 1),
(24, '2024_01_01_000024_create_tracer_studies_table', 1),
(25, '2024_01_01_000025_create_tefa_products_table', 1),
(26, '2024_01_01_000026_create_tefa_orders_table', 1),
(27, '2024_01_01_000027_create_tefa_order_items_table', 1),
(28, '2024_01_01_000028_create_extracurriculars_table', 1),
(29, '2024_01_01_000029_create_extracurricular_registrations_table', 1),
(30, '2024_01_01_000030_create_achievements_table', 1);

-- --------------------------------------------------------

--
-- Struktur dari tabel `permissions`
--

CREATE TABLE `permissions` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `permission_name` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data untuk tabel `permissions`
--

INSERT INTO `permissions` (`id`, `permission_name`, `created_at`, `updated_at`) VALUES
(1, 'manage_users', '2026-08-30 09:11:11', '2026-08-30 09:11:11'),
(2, 'manage_roles', '2026-08-30 09:11:11', '2026-08-30 09:11:11'),
(3, 'manage_permissions', '2026-08-30 09:11:11', '2026-08-30 09:11:11'),
(4, 'backup_database', '2026-08-30 09:11:11', '2026-08-30 09:11:11'),
(5, 'manage_banners', '2026-08-30 09:11:11', '2026-08-30 09:11:11'),
(6, 'manage_menus', '2026-08-30 09:11:11', '2026-08-30 09:11:11'),
(7, 'manage_site_settings', '2026-08-30 09:11:11', '2026-08-30 09:11:11'),
(8, 'manage_posts', '2026-08-30 09:11:11', '2026-08-30 09:11:11'),
(9, 'manage_dudi_partners', '2026-08-30 09:11:11', '2026-08-30 09:11:11'),
(10, 'manage_job_vacancies', '2026-08-30 09:11:11', '2026-08-30 09:11:11'),
(11, 'manage_tracer_studies', '2026-08-30 09:11:11', '2026-08-30 09:11:11'),
(12, 'manage_tefa_products', '2026-08-30 09:11:11', '2026-08-30 09:11:11'),
(13, 'manage_tefa_orders', '2026-08-30 09:11:11', '2026-08-30 09:11:11'),
(14, 'manage_learning_modules', '2026-08-30 09:11:11', '2026-08-30 09:11:11'),
(15, 'input_grades', '2026-08-30 09:11:11', '2026-08-30 09:11:11'),
(16, 'manage_extracurriculars', '2026-08-30 09:11:11', '2026-08-30 09:11:11'),
(17, 'approve_registrations', '2026-08-30 09:11:11', '2026-08-30 09:11:11'),
(18, 'manage_achievements', '2026-08-30 09:11:11', '2026-08-30 09:11:11');

-- --------------------------------------------------------

--
-- Struktur dari tabel `posts`
--

CREATE TABLE `posts` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `title` varchar(255) NOT NULL,
  `slug` varchar(255) NOT NULL,
  `content` longtext NOT NULL,
  `image_url` varchar(255) DEFAULT NULL,
  `category_id` bigint(20) UNSIGNED DEFAULT NULL,
  `author_id` bigint(20) UNSIGNED DEFAULT NULL,
  `status` enum('draft','published') NOT NULL DEFAULT 'draft',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Struktur dari tabel `programs`
--

CREATE TABLE `programs` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `program_name` varchar(255) NOT NULL,
  `description` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data untuk tabel `programs`
--

INSERT INTO `programs` (`id`, `program_name`, `description`, `created_at`, `updated_at`) VALUES
(1, 'Rekayasa Perangkat Lunak', NULL, '2026-08-30 09:11:12', '2026-08-30 09:11:12'),
(2, 'Teknik Komputer dan Jaringan', NULL, '2026-08-30 09:11:12', '2026-08-30 09:11:12'),
(3, 'Akuntansi', NULL, '2026-08-30 09:11:12', '2026-08-30 09:11:12');

-- --------------------------------------------------------

--
-- Struktur dari tabel `roles`
--

CREATE TABLE `roles` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `role_name` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data untuk tabel `roles`
--

INSERT INTO `roles` (`id`, `role_name`, `created_at`, `updated_at`) VALUES
(1, 'Super Admin', '2026-08-30 09:11:10', '2026-08-30 09:11:10'),
(2, 'Hubin', '2026-08-30 09:11:10', '2026-08-30 09:11:10'),
(3, 'Koperasi', '2026-08-30 09:11:10', '2026-08-30 09:11:10'),
(4, 'Guru', '2026-08-30 09:11:10', '2026-08-30 09:11:10'),
(5, 'Eskul', '2026-08-30 09:11:11', '2026-08-30 09:11:11');

-- --------------------------------------------------------

--
-- Struktur dari tabel `role_permission`
--

CREATE TABLE `role_permission` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `role_id` bigint(20) UNSIGNED NOT NULL,
  `permission_id` bigint(20) UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data untuk tabel `role_permission`
--

INSERT INTO `role_permission` (`id`, `role_id`, `permission_id`, `created_at`, `updated_at`) VALUES
(1, 1, 17, '2026-08-30 09:11:11', '2026-08-30 09:11:11'),
(2, 1, 4, '2026-08-30 09:11:11', '2026-08-30 09:11:11'),
(3, 1, 15, '2026-08-30 09:11:11', '2026-08-30 09:11:11'),
(4, 1, 18, '2026-08-30 09:11:11', '2026-08-30 09:11:11'),
(5, 1, 5, '2026-08-30 09:11:11', '2026-08-30 09:11:11'),
(6, 1, 9, '2026-08-30 09:11:11', '2026-08-30 09:11:11'),
(7, 1, 16, '2026-08-30 09:11:11', '2026-08-30 09:11:11'),
(8, 1, 10, '2026-08-30 09:11:11', '2026-08-30 09:11:11'),
(9, 1, 14, '2026-08-30 09:11:11', '2026-08-30 09:11:11'),
(10, 1, 6, '2026-08-30 09:11:11', '2026-08-30 09:11:11'),
(11, 1, 3, '2026-08-30 09:11:11', '2026-08-30 09:11:11'),
(12, 1, 8, '2026-08-30 09:11:11', '2026-08-30 09:11:11'),
(13, 1, 2, '2026-08-30 09:11:11', '2026-08-30 09:11:11'),
(14, 1, 7, '2026-08-30 09:11:11', '2026-08-30 09:11:11'),
(15, 1, 13, '2026-08-30 09:11:11', '2026-08-30 09:11:11'),
(16, 1, 12, '2026-08-30 09:11:11', '2026-08-30 09:11:11'),
(17, 1, 11, '2026-08-30 09:11:11', '2026-08-30 09:11:11'),
(18, 1, 1, '2026-08-30 09:11:11', '2026-08-30 09:11:11'),
(19, 2, 9, '2026-08-30 09:11:11', '2026-08-30 09:11:11'),
(20, 2, 10, '2026-08-30 09:11:11', '2026-08-30 09:11:11'),
(21, 2, 11, '2026-08-30 09:11:11', '2026-08-30 09:11:11'),
(22, 3, 13, '2026-08-30 09:11:11', '2026-08-30 09:11:11'),
(23, 3, 12, '2026-08-30 09:11:11', '2026-08-30 09:11:11'),
(24, 4, 15, '2026-08-30 09:11:11', '2026-08-30 09:11:11'),
(25, 4, 14, '2026-08-30 09:11:11', '2026-08-30 09:11:11'),
(26, 5, 17, '2026-08-30 09:11:11', '2026-08-30 09:11:11'),
(27, 5, 18, '2026-08-30 09:11:11', '2026-08-30 09:11:11'),
(28, 5, 16, '2026-08-30 09:11:11', '2026-08-30 09:11:11');

-- --------------------------------------------------------

--
-- Struktur dari tabel `role_user`
--

CREATE TABLE `role_user` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `user_id` bigint(20) UNSIGNED NOT NULL,
  `role_id` bigint(20) UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data untuk tabel `role_user`
--

INSERT INTO `role_user` (`id`, `user_id`, `role_id`, `created_at`, `updated_at`) VALUES
(1, 1, 1, NULL, NULL);

-- --------------------------------------------------------

--
-- Struktur dari tabel `site_settings`
--

CREATE TABLE `site_settings` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `key` varchar(255) NOT NULL,
  `value` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Struktur dari tabel `students`
--

CREATE TABLE `students` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `nis` varchar(255) NOT NULL,
  `name` varchar(255) NOT NULL,
  `class_id` bigint(20) UNSIGNED NOT NULL,
  `status` enum('aktif','lulus') NOT NULL DEFAULT 'aktif',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Struktur dari tabel `student_grades`
--

CREATE TABLE `student_grades` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `student_id` bigint(20) UNSIGNED NOT NULL,
  `subject_id` bigint(20) UNSIGNED NOT NULL,
  `teacher_id` bigint(20) UNSIGNED DEFAULT NULL,
  `theory_score` decimal(5,2) DEFAULT NULL,
  `practice_score` decimal(5,2) DEFAULT NULL,
  `ukk_score` decimal(5,2) DEFAULT NULL,
  `pkl_score` decimal(5,2) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Struktur dari tabel `subjects`
--

CREATE TABLE `subjects` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `subject_name` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data untuk tabel `subjects`
--

INSERT INTO `subjects` (`id`, `subject_name`, `created_at`, `updated_at`) VALUES
(1, 'Matematika', '2026-08-30 09:11:12', '2026-08-30 09:11:12'),
(2, 'Bahasa Indonesia', '2026-08-30 09:11:12', '2026-08-30 09:11:12'),
(3, 'Bahasa Inggris', '2026-08-30 09:11:12', '2026-08-30 09:11:12'),
(4, 'Pemrograman Web', '2026-08-30 09:11:12', '2026-08-30 09:11:12'),
(5, 'Basis Data', '2026-08-30 09:11:12', '2026-08-30 09:11:12');

-- --------------------------------------------------------

--
-- Struktur dari tabel `teacher_class_subject`
--

CREATE TABLE `teacher_class_subject` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `teacher_id` bigint(20) UNSIGNED NOT NULL,
  `class_id` bigint(20) UNSIGNED NOT NULL,
  `subject_id` bigint(20) UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Struktur dari tabel `tefa_orders`
--

CREATE TABLE `tefa_orders` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `order_code` varchar(255) NOT NULL,
  `buyer_name` varchar(255) NOT NULL,
  `buyer_contact` varchar(255) DEFAULT NULL,
  `total_price` decimal(12,2) NOT NULL DEFAULT 0.00,
  `status` enum('pending','paid','completed') NOT NULL DEFAULT 'pending',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Struktur dari tabel `tefa_order_items`
--

CREATE TABLE `tefa_order_items` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `order_id` bigint(20) UNSIGNED NOT NULL,
  `product_id` bigint(20) UNSIGNED NOT NULL,
  `quantity` int(11) NOT NULL,
  `subtotal` decimal(12,2) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Struktur dari tabel `tefa_products`
--

CREATE TABLE `tefa_products` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `product_name` varchar(255) NOT NULL,
  `description` text DEFAULT NULL,
  `price` decimal(12,2) NOT NULL,
  `stock` int(11) NOT NULL DEFAULT 0,
  `image_url` varchar(255) DEFAULT NULL,
  `program_id` bigint(20) UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Struktur dari tabel `tracer_studies`
--

CREATE TABLE `tracer_studies` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `student_id` bigint(20) UNSIGNED NOT NULL,
  `graduation_year` year(4) NOT NULL,
  `current_status` enum('Kerja','Kuliah','Wirausaha') NOT NULL,
  `company_or_campus_name` varchar(255) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Struktur dari tabel `users`
--

CREATE TABLE `users` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `remember_token` varchar(100) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data untuk tabel `users`
--

INSERT INTO `users` (`id`, `name`, `email`, `password`, `remember_token`, `created_at`, `updated_at`) VALUES
(1, 'Super Admin', 'superadmin@smk.sch.id', '$2y$12$igX9UF.uUWz6hzZNqBLuTe1x962m4Lwm4VeEo.6EYASg/sSoQf6Wa', NULL, '2026-08-30 09:11:12', '2026-08-30 09:11:12');

--
-- Indexes for dumped tables
--

--
-- Indeks untuk tabel `achievements`
--
ALTER TABLE `achievements`
  ADD PRIMARY KEY (`id`),
  ADD KEY `achievements_extracurricular_id_index` (`extracurricular_id`);

--
-- Indeks untuk tabel `activity_logs`
--
ALTER TABLE `activity_logs`
  ADD PRIMARY KEY (`id`),
  ADD KEY `activity_logs_user_id_index` (`user_id`),
  ADD KEY `activity_logs_created_at_index` (`created_at`);

--
-- Indeks untuk tabel `banners`
--
ALTER TABLE `banners`
  ADD PRIMARY KEY (`id`),
  ADD KEY `banners_sort_order_index` (`sort_order`);

--
-- Indeks untuk tabel `categories`
--
ALTER TABLE `categories`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `categories_slug_unique` (`slug`),
  ADD KEY `categories_slug_index` (`slug`);

--
-- Indeks untuk tabel `classes`
--
ALTER TABLE `classes`
  ADD PRIMARY KEY (`id`),
  ADD KEY `classes_program_id_index` (`program_id`);

--
-- Indeks untuk tabel `contact_messages`
--
ALTER TABLE `contact_messages`
  ADD PRIMARY KEY (`id`),
  ADD KEY `contact_messages_status_index` (`status`);

--
-- Indeks untuk tabel `dudi_partners`
--
ALTER TABLE `dudi_partners`
  ADD PRIMARY KEY (`id`),
  ADD KEY `dudi_partners_industry_field_index` (`industry_field`);

--
-- Indeks untuk tabel `events`
--
ALTER TABLE `events`
  ADD PRIMARY KEY (`id`),
  ADD KEY `events_created_by_foreign` (`created_by`),
  ADD KEY `events_event_date_index` (`event_date`);

--
-- Indeks untuk tabel `extracurriculars`
--
ALTER TABLE `extracurriculars`
  ADD PRIMARY KEY (`id`),
  ADD KEY `extracurriculars_coach_id_index` (`coach_id`);

--
-- Indeks untuk tabel `extracurricular_registrations`
--
ALTER TABLE `extracurricular_registrations`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `reg_unique_student_eskul` (`student_id`,`extracurricular_id`),
  ADD KEY `extracurricular_registrations_extracurricular_id_index` (`extracurricular_id`),
  ADD KEY `extracurricular_registrations_status_index` (`status`);

--
-- Indeks untuk tabel `facilities`
--
ALTER TABLE `facilities`
  ADD PRIMARY KEY (`id`),
  ADD KEY `facilities_program_id_index` (`program_id`);

--
-- Indeks untuk tabel `job_vacancies`
--
ALTER TABLE `job_vacancies`
  ADD PRIMARY KEY (`id`),
  ADD KEY `job_vacancies_dudi_id_index` (`dudi_id`),
  ADD KEY `job_vacancies_status_index` (`status`);

--
-- Indeks untuk tabel `learning_modules`
--
ALTER TABLE `learning_modules`
  ADD PRIMARY KEY (`id`),
  ADD KEY `learning_modules_program_id_index` (`program_id`),
  ADD KEY `learning_modules_teacher_id_index` (`teacher_id`);

--
-- Indeks untuk tabel `menus`
--
ALTER TABLE `menus`
  ADD PRIMARY KEY (`id`),
  ADD KEY `menus_sort_order_index` (`sort_order`);

--
-- Indeks untuk tabel `migrations`
--
ALTER TABLE `migrations`
  ADD PRIMARY KEY (`id`);

--
-- Indeks untuk tabel `permissions`
--
ALTER TABLE `permissions`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `permissions_permission_name_unique` (`permission_name`);

--
-- Indeks untuk tabel `posts`
--
ALTER TABLE `posts`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `posts_slug_unique` (`slug`),
  ADD KEY `posts_author_id_foreign` (`author_id`),
  ADD KEY `posts_slug_index` (`slug`),
  ADD KEY `posts_status_index` (`status`),
  ADD KEY `posts_category_id_index` (`category_id`);

--
-- Indeks untuk tabel `programs`
--
ALTER TABLE `programs`
  ADD PRIMARY KEY (`id`);

--
-- Indeks untuk tabel `roles`
--
ALTER TABLE `roles`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `roles_role_name_unique` (`role_name`);

--
-- Indeks untuk tabel `role_permission`
--
ALTER TABLE `role_permission`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `role_permission_role_id_permission_id_unique` (`role_id`,`permission_id`),
  ADD KEY `role_permission_permission_id_foreign` (`permission_id`);

--
-- Indeks untuk tabel `role_user`
--
ALTER TABLE `role_user`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `role_user_user_id_role_id_unique` (`user_id`,`role_id`),
  ADD KEY `role_user_role_id_foreign` (`role_id`);

--
-- Indeks untuk tabel `site_settings`
--
ALTER TABLE `site_settings`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `site_settings_key_unique` (`key`);

--
-- Indeks untuk tabel `students`
--
ALTER TABLE `students`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `students_nis_unique` (`nis`),
  ADD KEY `students_nis_index` (`nis`),
  ADD KEY `students_class_id_index` (`class_id`),
  ADD KEY `students_status_index` (`status`);

--
-- Indeks untuk tabel `student_grades`
--
ALTER TABLE `student_grades`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `grades_unique_student_subject` (`student_id`,`subject_id`),
  ADD KEY `student_grades_subject_id_index` (`subject_id`),
  ADD KEY `student_grades_teacher_id_index` (`teacher_id`);

--
-- Indeks untuk tabel `subjects`
--
ALTER TABLE `subjects`
  ADD PRIMARY KEY (`id`);

--
-- Indeks untuk tabel `teacher_class_subject`
--
ALTER TABLE `teacher_class_subject`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `tcs_unique_assignment` (`teacher_id`,`class_id`,`subject_id`),
  ADD KEY `teacher_class_subject_class_id_index` (`class_id`),
  ADD KEY `teacher_class_subject_subject_id_index` (`subject_id`);

--
-- Indeks untuk tabel `tefa_orders`
--
ALTER TABLE `tefa_orders`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `tefa_orders_order_code_unique` (`order_code`),
  ADD KEY `tefa_orders_order_code_index` (`order_code`),
  ADD KEY `tefa_orders_status_index` (`status`);

--
-- Indeks untuk tabel `tefa_order_items`
--
ALTER TABLE `tefa_order_items`
  ADD PRIMARY KEY (`id`),
  ADD KEY `tefa_order_items_order_id_index` (`order_id`),
  ADD KEY `tefa_order_items_product_id_index` (`product_id`);

--
-- Indeks untuk tabel `tefa_products`
--
ALTER TABLE `tefa_products`
  ADD PRIMARY KEY (`id`),
  ADD KEY `tefa_products_program_id_index` (`program_id`);

--
-- Indeks untuk tabel `tracer_studies`
--
ALTER TABLE `tracer_studies`
  ADD PRIMARY KEY (`id`),
  ADD KEY `tracer_studies_student_id_index` (`student_id`),
  ADD KEY `tracer_studies_graduation_year_index` (`graduation_year`),
  ADD KEY `tracer_studies_current_status_index` (`current_status`);

--
-- Indeks untuk tabel `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `users_email_unique` (`email`),
  ADD KEY `users_email_index` (`email`);

--
-- AUTO_INCREMENT untuk tabel yang dibuang
--

--
-- AUTO_INCREMENT untuk tabel `achievements`
--
ALTER TABLE `achievements`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT untuk tabel `activity_logs`
--
ALTER TABLE `activity_logs`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT untuk tabel `banners`
--
ALTER TABLE `banners`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT untuk tabel `categories`
--
ALTER TABLE `categories`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT untuk tabel `classes`
--
ALTER TABLE `classes`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT untuk tabel `contact_messages`
--
ALTER TABLE `contact_messages`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT untuk tabel `dudi_partners`
--
ALTER TABLE `dudi_partners`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT untuk tabel `events`
--
ALTER TABLE `events`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT untuk tabel `extracurriculars`
--
ALTER TABLE `extracurriculars`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT untuk tabel `extracurricular_registrations`
--
ALTER TABLE `extracurricular_registrations`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT untuk tabel `facilities`
--
ALTER TABLE `facilities`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT untuk tabel `job_vacancies`
--
ALTER TABLE `job_vacancies`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT untuk tabel `learning_modules`
--
ALTER TABLE `learning_modules`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT untuk tabel `menus`
--
ALTER TABLE `menus`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT untuk tabel `migrations`
--
ALTER TABLE `migrations`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=31;

--
-- AUTO_INCREMENT untuk tabel `permissions`
--
ALTER TABLE `permissions`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=19;

--
-- AUTO_INCREMENT untuk tabel `posts`
--
ALTER TABLE `posts`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT untuk tabel `programs`
--
ALTER TABLE `programs`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT untuk tabel `roles`
--
ALTER TABLE `roles`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT untuk tabel `role_permission`
--
ALTER TABLE `role_permission`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=29;

--
-- AUTO_INCREMENT untuk tabel `role_user`
--
ALTER TABLE `role_user`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT untuk tabel `site_settings`
--
ALTER TABLE `site_settings`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT untuk tabel `students`
--
ALTER TABLE `students`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT untuk tabel `student_grades`
--
ALTER TABLE `student_grades`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT untuk tabel `subjects`
--
ALTER TABLE `subjects`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT untuk tabel `teacher_class_subject`
--
ALTER TABLE `teacher_class_subject`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT untuk tabel `tefa_orders`
--
ALTER TABLE `tefa_orders`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT untuk tabel `tefa_order_items`
--
ALTER TABLE `tefa_order_items`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT untuk tabel `tefa_products`
--
ALTER TABLE `tefa_products`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT untuk tabel `tracer_studies`
--
ALTER TABLE `tracer_studies`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT untuk tabel `users`
--
ALTER TABLE `users`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- Ketidakleluasaan untuk tabel pelimpahan (Dumped Tables)
--

--
-- Ketidakleluasaan untuk tabel `achievements`
--
ALTER TABLE `achievements`
  ADD CONSTRAINT `achievements_extracurricular_id_foreign` FOREIGN KEY (`extracurricular_id`) REFERENCES `extracurriculars` (`id`) ON DELETE SET NULL;

--
-- Ketidakleluasaan untuk tabel `activity_logs`
--
ALTER TABLE `activity_logs`
  ADD CONSTRAINT `activity_logs_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE SET NULL;

--
-- Ketidakleluasaan untuk tabel `classes`
--
ALTER TABLE `classes`
  ADD CONSTRAINT `classes_program_id_foreign` FOREIGN KEY (`program_id`) REFERENCES `programs` (`id`);

--
-- Ketidakleluasaan untuk tabel `events`
--
ALTER TABLE `events`
  ADD CONSTRAINT `events_created_by_foreign` FOREIGN KEY (`created_by`) REFERENCES `users` (`id`) ON DELETE SET NULL;

--
-- Ketidakleluasaan untuk tabel `extracurriculars`
--
ALTER TABLE `extracurriculars`
  ADD CONSTRAINT `extracurriculars_coach_id_foreign` FOREIGN KEY (`coach_id`) REFERENCES `users` (`id`) ON DELETE SET NULL;

--
-- Ketidakleluasaan untuk tabel `extracurricular_registrations`
--
ALTER TABLE `extracurricular_registrations`
  ADD CONSTRAINT `extracurricular_registrations_extracurricular_id_foreign` FOREIGN KEY (`extracurricular_id`) REFERENCES `extracurriculars` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `extracurricular_registrations_student_id_foreign` FOREIGN KEY (`student_id`) REFERENCES `students` (`id`) ON DELETE CASCADE;

--
-- Ketidakleluasaan untuk tabel `facilities`
--
ALTER TABLE `facilities`
  ADD CONSTRAINT `facilities_program_id_foreign` FOREIGN KEY (`program_id`) REFERENCES `programs` (`id`) ON DELETE SET NULL;

--
-- Ketidakleluasaan untuk tabel `job_vacancies`
--
ALTER TABLE `job_vacancies`
  ADD CONSTRAINT `job_vacancies_dudi_id_foreign` FOREIGN KEY (`dudi_id`) REFERENCES `dudi_partners` (`id`) ON DELETE CASCADE;

--
-- Ketidakleluasaan untuk tabel `learning_modules`
--
ALTER TABLE `learning_modules`
  ADD CONSTRAINT `learning_modules_program_id_foreign` FOREIGN KEY (`program_id`) REFERENCES `programs` (`id`),
  ADD CONSTRAINT `learning_modules_teacher_id_foreign` FOREIGN KEY (`teacher_id`) REFERENCES `users` (`id`);

--
-- Ketidakleluasaan untuk tabel `posts`
--
ALTER TABLE `posts`
  ADD CONSTRAINT `posts_author_id_foreign` FOREIGN KEY (`author_id`) REFERENCES `users` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `posts_category_id_foreign` FOREIGN KEY (`category_id`) REFERENCES `categories` (`id`) ON DELETE SET NULL;

--
-- Ketidakleluasaan untuk tabel `role_permission`
--
ALTER TABLE `role_permission`
  ADD CONSTRAINT `role_permission_permission_id_foreign` FOREIGN KEY (`permission_id`) REFERENCES `permissions` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `role_permission_role_id_foreign` FOREIGN KEY (`role_id`) REFERENCES `roles` (`id`) ON DELETE CASCADE;

--
-- Ketidakleluasaan untuk tabel `role_user`
--
ALTER TABLE `role_user`
  ADD CONSTRAINT `role_user_role_id_foreign` FOREIGN KEY (`role_id`) REFERENCES `roles` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `role_user_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Ketidakleluasaan untuk tabel `students`
--
ALTER TABLE `students`
  ADD CONSTRAINT `students_class_id_foreign` FOREIGN KEY (`class_id`) REFERENCES `classes` (`id`);

--
-- Ketidakleluasaan untuk tabel `student_grades`
--
ALTER TABLE `student_grades`
  ADD CONSTRAINT `student_grades_student_id_foreign` FOREIGN KEY (`student_id`) REFERENCES `students` (`id`),
  ADD CONSTRAINT `student_grades_subject_id_foreign` FOREIGN KEY (`subject_id`) REFERENCES `subjects` (`id`),
  ADD CONSTRAINT `student_grades_teacher_id_foreign` FOREIGN KEY (`teacher_id`) REFERENCES `users` (`id`) ON DELETE SET NULL;

--
-- Ketidakleluasaan untuk tabel `teacher_class_subject`
--
ALTER TABLE `teacher_class_subject`
  ADD CONSTRAINT `teacher_class_subject_class_id_foreign` FOREIGN KEY (`class_id`) REFERENCES `classes` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `teacher_class_subject_subject_id_foreign` FOREIGN KEY (`subject_id`) REFERENCES `subjects` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `teacher_class_subject_teacher_id_foreign` FOREIGN KEY (`teacher_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Ketidakleluasaan untuk tabel `tefa_order_items`
--
ALTER TABLE `tefa_order_items`
  ADD CONSTRAINT `tefa_order_items_order_id_foreign` FOREIGN KEY (`order_id`) REFERENCES `tefa_orders` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `tefa_order_items_product_id_foreign` FOREIGN KEY (`product_id`) REFERENCES `tefa_products` (`id`);

--
-- Ketidakleluasaan untuk tabel `tefa_products`
--
ALTER TABLE `tefa_products`
  ADD CONSTRAINT `tefa_products_program_id_foreign` FOREIGN KEY (`program_id`) REFERENCES `programs` (`id`);

--
-- Ketidakleluasaan untuk tabel `tracer_studies`
--
ALTER TABLE `tracer_studies`
  ADD CONSTRAINT `tracer_studies_student_id_foreign` FOREIGN KEY (`student_id`) REFERENCES `students` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
