-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Máy chủ: 127.0.0.1
-- Thời gian đã tạo: Th2 27, 2026 lúc 08:11 AM
-- Phiên bản máy phục vụ: 10.4.32-MariaDB
-- Phiên bản PHP: 8.0.30

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Cơ sở dữ liệu: `phu_tho_tourist`
--

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `cart_items`
--

CREATE TABLE `cart_items` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `user_id` bigint(20) UNSIGNED NOT NULL,
  `product_id` bigint(20) UNSIGNED NOT NULL,
  `product_name` varchar(255) NOT NULL,
  `product_price` bigint(20) UNSIGNED NOT NULL,
  `quantity` int(11) NOT NULL DEFAULT 1,
  `is_selected` tinyint(1) NOT NULL DEFAULT 1,
  `product_image` varchar(255) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `categories`
--

CREATE TABLE `categories` (
  `category_id` int(10) UNSIGNED NOT NULL,
  `category_name` varchar(255) NOT NULL,
  `parent_id` int(10) UNSIGNED DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `contents`
--

CREATE TABLE `contents` (
  `content_id` int(10) UNSIGNED NOT NULL,
  `content` varchar(255) NOT NULL,
  `description` varchar(255) NOT NULL,
  `image_url` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `cvs`
--

CREATE TABLE `cvs` (
  `cvs_id` int(10) UNSIGNED NOT NULL,
  `job_id` int(10) UNSIGNED DEFAULT NULL,
  `ho_ten` varchar(255) NOT NULL,
  `age` varchar(255) NOT NULL,
  `sex` varchar(10) NOT NULL DEFAULT 'other',
  `current_residence` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `level` varchar(255) NOT NULL,
  `applied_position` varchar(255) DEFAULT NULL,
  `willing_to_travel` int(11) NOT NULL DEFAULT 0,
  `place_of_birth` varchar(255) NOT NULL,
  `phone` varchar(255) NOT NULL,
  `url_facebook` text NOT NULL,
  `file_path` varchar(255) NOT NULL,
  `willing_to_work_overtime` int(11) NOT NULL DEFAULT 0,
  `previous_experiences` text DEFAULT NULL,
  `personal_experience` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Đang đổ dữ liệu cho bảng `cvs`
--

INSERT INTO `cvs` (`cvs_id`, `job_id`, `ho_ten`, `age`, `sex`, `current_residence`, `email`, `level`, `applied_position`, `willing_to_travel`, `place_of_birth`, `phone`, `url_facebook`, `file_path`, `willing_to_work_overtime`, `previous_experiences`, `personal_experience`, `created_at`, `updated_at`) VALUES
(1, NULL, 'Võ Đăng Kỷ', '24', 'male', 'Gò Vấp', 'vodangky.dev@gmail.com', 'Đại học', 'Nhân viên kinh doanh', 1, 'Bình Thuận', '0972471300', '', '1767953993_dsadwdwq.pdf', 0, 'nói', 'nghe', '2026-01-09 10:19:53', '2026-01-09 10:19:53');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `documents`
--

CREATE TABLE `documents` (
  `document_id` int(10) UNSIGNED NOT NULL,
  `document_name` varchar(255) NOT NULL,
  `document_url` varchar(255) NOT NULL,
  `image_url` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `failed_jobs`
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
-- Cấu trúc bảng cho bảng `favorites`
--

CREATE TABLE `favorites` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `user_id` bigint(20) UNSIGNED NOT NULL,
  `product_id` bigint(20) UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Đang đổ dữ liệu cho bảng `favorites`
--

INSERT INTO `favorites` (`id`, `user_id`, `product_id`, `created_at`, `updated_at`) VALUES
(102, 1, 8, '2026-01-13 10:05:27', '2026-01-13 10:05:27'),
(105, 3, 8, '2026-01-14 04:04:34', '2026-01-14 04:04:34'),
(106, 1, 7, '2026-01-14 04:15:11', '2026-01-14 04:15:11'),
(107, 1, 13, '2026-01-14 05:45:12', '2026-01-14 05:45:12'),
(109, 1, 2, '2026-01-14 06:17:43', '2026-01-14 06:17:43'),
(111, 1, 16, '2026-01-16 03:45:45', '2026-01-16 03:45:45'),
(116, 1, 19, '2026-02-11 09:41:59', '2026-02-11 09:41:59');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `images`
--

CREATE TABLE `images` (
  `images_id` int(10) UNSIGNED NOT NULL,
  `image_url` varchar(255) NOT NULL,
  `sub_images` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`sub_images`)),
  `content` text NOT NULL,
  `description` text DEFAULT NULL,
  `price` decimal(10,2) DEFAULT NULL COMMENT 'Giá sản phẩm',
  `size` varchar(50) DEFAULT NULL,
  `product_type` varchar(100) DEFAULT NULL COMMENT 'Loại hàng',
  `discount_percent` tinyint(3) UNSIGNED DEFAULT NULL COMMENT 'Phần trăm giảm giá, ví dụ 10 = giảm 10%',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Đang đổ dữ liệu cho bảng `images`
--

INSERT INTO `images` (`images_id`, `image_url`, `sub_images`, `content`, `description`, `price`, `size`, `product_type`, `discount_percent`, `created_at`, `updated_at`) VALUES
(7, 'products/yHfIFteI1BEy519q6nt658JlKxXKey5kShR2Zs3t.jpg', NULL, 'ca loc bong 23', NULL, 200000.00, NULL, 'Sơ chế', NULL, '2026-01-09 06:15:05', '2026-01-14 04:35:54'),
(8, 'products/mEqRLVlnXHRHIYYJ4h34P55pb56oAzlSFYTKWMVM.jpg', '[\"products\\/ck5xdEHZvlS0oyWJgeld2tc4xY2NxQb2dQ7td0hK.jpg\",\"products\\/nwj6aN3YudNBI3cdqctvXlWQ6RwYaux2J5O8qbny.jpg\",\"products\\/jTb1mHUPIhQkCw1nUOd0YODpqujQO433A8g3O40J.jpg\"]', 'ca loc bong 234', 'TIÊU CHÍ CHÍNH CHO CÁ HỒI HỮU CƠ LEROY/SALMAR:\r\n- Mật độ nuôi không được phép vượt mức 10 kg/m3\r\n\r\n- Cá hồi hữu cơ được nuôi bằng thức ăn nguồn gốc hải sản nhưng phải từ nguồn cá bền vững (từ các nhà đánh bắt có chứng nhận MSC)\r\n\r\n- SalMar đang sử dụng cá sạch hơn để giúp giữ cho cá hồi không có rận biển.\r\n\r\n- Lưới không được xử lý bằng chất chống bẩn có chứa đồng. Điều này sẽ có tác động ít hơn đến môi trường.\r\n\r\n- Nếu cá phải điều trị bằng thuốc thì thời gian cách ly thuốc dài gấp đôi so với cá hồi thông thường.\r\n\r\n- MOM (giám sát môi trường) là một yếu tố quan trọng trong các cuộc kiểm tra DEBIO.\r\n\r\n- Các địa điểm được sử dụng cho canh tác hữu cơ sẽ được bỏ hoang tối thiểu 4 tháng sau khi thu hoạch - so với 2 tháng đối với các địa điểm thông thường.\r\n\r\nCá Hồi Nauy phi lê sạch xương 300g/gói\r\n\r\nCá Hồi Nauy phi lê sạch xương 300g/gói\r\n\r\nCá Hồi Nauy phi lê sạch xương 300g/gói\r\n\r\nCá hồi sẽ được chuyên gia xử lý cá đạt chuẩn của TOH Fish tiến hành xẻ thịt và phi lê sạch xương ngay tại kho cấp đông nhanh theo tiêu chuẩn xuất khẩu Châu Âu - Nhờ vậy giúp cho cá vẫn giữ được hương vị cùng với giá trị dinh dưỡng. Những lát cá hồi Nauy phi lê sạch xương này sẽ được TOH Fish giao trực tiếp đến tay khách hàng ngay trong ngày. Nếu Quý khách muốn đặt cá tươi sống và nguyên con hoặc số lượng thì hãy liên hệ ngay với TOH Fish để đặt hàng và sẽ được hẹn lịch giao cá trong thời gian sớm nhất.\r\n\r\nCác thành phần dinh dưỡng có trong 100 gram cá hồi Nauy phi lê sạch xương:\r\nSở dĩ các chuyên gia dinh dưỡng đều chúng ta nên tích cực ăn cá hồi vì theo nghiên cứu, trong 100 gram cá hồi, có các thành phần dinh dưỡng sau:\r\n\r\nNăng lượng: khoảng 206 calo\r\nProtein: khoảng 22 gram\r\nChất béo: khoảng 13 gram\r\nChất béo bão hòa: khoảng 2,5 gram\r\nChất béo không bão hòa: khoảng 6 gram\r\nChất béo omega-3: khoảng 2,6 gram\r\nChất béo omega-6: khoảng 0,3 gram\r\nCarbohydrate: khoảng 0 gram\r\nChất xơ: khoảng 0 gram\r\nCholesterol: khoảng 51 mg\r\nVitamin D: khoảng 15 IU\r\nCanxi: khoảng 7 mg\r\nSắt: khoảng 0,5 mg\r\nKali: khoảng 363 mg\r\nNatri: khoảng 59 mg\r\nMagiê: khoảng 35 mg\r\nKẽm: khoảng 0,5 mg\r\nTuy nhiên giá trị dinh dưỡng có thể có sự dao động nhỏ tùy thuộc vào từng nguồn và cách chế biến cá hồi. Do đó nếu như những lát thịt cá hồi được xử lý và chế biến đúng kỹ thuật sẽ góp phần giữ giá trị dinh dưỡng ở mức cao nhất.\r\nMột số lợi ích của việc ăn cá hồi:\r\nViệc ăn cá hồi mang lại nhiều lợi ích cho sức khỏe. Dưới đây là một số lợi ích chính của việc ăn cá hồi:\r\n\r\n- Cung cấp chất dinh dưỡng: Cá hồi giàu protein, chất béo omega-3, vitamin D, vitamin B12, selen và iodine. Các chất dinh dưỡng này có vai trò quan trọng trong sự phát triển và chức năng của cơ thể.\r\n\r\n- Tăng cường sức khỏe tim mạch: Omega-3 trong cá hồi giúp làm giảm các yếu tố nguy cơ gây bệnh tim như huyết áp cao, cholesterol cao và triglyceride cao. Nó cũng giúp giảm nguy cơ bị đau tim và đột quỵ.\r\n\r\n- Hỗ trợ sức khỏe não: Chất béo omega-3 có vai trò quan trọng trong sự phát triển và chức năng của não bộ. Việc ăn cá hồi có thể cung cấp đủ dưỡng chất cho não, giúp cải thiện trí nhớ, tăng cường tư duy và giảm nguy cơ mắc các bệnh như Alzheimer và trầm cảm.\r\n\r\n- Tăng cường sức đề kháng: Cá hồi là nguồn giàu protein, selen và vitamin D, các chất này có vai trò quan trọng trong hệ thống miễn dịch của cơ thể. Việc ăn cá hồi thường xuyên có thể giúp tăng cường sức đề kháng và giảm nguy cơ mắc bệnh nhiễm trùng.\r\n\r\n- Hỗ trợ cho quá trình giảm cân: Cá hồi có thể là một lựa chọn tốt cho những người đang muốn giảm cân. Với hàm lượng protein cao và ít chất béo bão hòa, nó giúp tăng cường cảm giác no lâu hơn và duy trì lượng calo ổn định trong cơ thể.\r\n\r\n- Chăm sóc da và tóc: Cá hồi chứa chất béo Omega-3 và các chất dinh dưỡng khác có khả năng giữ ẩm và giúp tái tạo da. Nó cũng có thể cải thiện sự mềm mại của da và tóc, giúp bảo vệ chúng khỏi tác động của môi trường.', 20000.00, '3000g, 1kg', 'Sơ chế', NULL, '2026-01-09 06:18:14', '2026-01-14 04:12:12'),
(9, 'products/6cMDz67wahYNNHT2cfznPlRy7dOVatMd2Gmh88Bx.jpg', '[\"products\\/iGHXbIgit8DPtOmxOXn7isbkQZQlnwZDeBQg6JbF.jpg\"]', 'ca loc bong b32', 'ca loc bong b32ca loc bong b32ca loc bong b32ca loc bong b32', 20000.00, '300g, 1kg', 'Chế biến', NULL, '2026-01-14 04:45:37', '2026-01-14 04:45:37'),
(10, 'products/xrCoErMAnlTvu62UasxexakBMGSnd6LHKl4PstYv.jpg', '[\"products\\/ufxF3BomwZ0TJvQXCKmt1k5mMvp8fIFddNwGJpQq.jpg\"]', 'ca loc bong c32', 'ca loc bong c32ca loc bong c32ca loc bong c32', 200000.00, '300g, 1kg', 'Chế biến sẵn', NULL, '2026-01-14 04:49:41', '2026-01-14 04:49:41'),
(11, 'products/um7c75h5lZqyDMZTrjofvHFpo3JkgMefQaVvZ1kt.jpg', NULL, 'bun ca', 'bun cabun cabun cabun cabun cabun cabun cabun cabun cabun cabun cabun cabun cabun cabun ca', 20000.00, '300g, 1kg', 'Bún cá TOH', NULL, '2026-01-14 04:56:39', '2026-01-14 04:56:39'),
(12, 'products/g7GyL1exhdFAS2VlcXDz8OaGcdv1yxi8Y2n6wBWF.jpg', NULL, 'rau vi', 'rau virau virau virau vi', 20000.00, '300g, 1kg', 'Rau gia vị', NULL, '2026-01-14 04:56:59', '2026-01-14 04:56:59'),
(13, 'products/2fsSeQf0A3qQbDcmztqcOrY11It24HjKxXQUJ9cB.jpg', NULL, 'san pham khac', 'san pham khacsan pham khacsan pham khacsan pham khac', 20000.00, '300g, 1kg', 'Khác', NULL, '2026-01-14 04:57:17', '2026-01-14 04:57:17'),
(14, 'products/TC4jgWLiJAZ3y6uQHgSHkMgpDhhliD5WICH0VQNi.jpg', '[\"products\\/CPb76yjuokTbThB5bvRxACFdRcedOgLBHj9lvV9d.jpg\"]', 'san pham khuyen mai', 'san pham khuyen maisan pham khuyen maisan pham khuyen maisan pham khuyen maisan pham khuyen maisan pham khuyen maisan pham khuyen mai', 20000.00, '300g, 1kg', 'Khuyến mãi', NULL, '2026-01-14 06:15:22', '2026-01-14 06:15:22'),
(15, 'products/AJgnDGcR8KRNHcY96896ZDyuqnXMSgMn03u41qpK.jpg', NULL, 'san pham khuyen mai 2', 'san pham khuyen mai 2san pham khuyen mai 2san pham khuyen mai 2', 200000.00, '300g, 1kg', 'Khuyến mãi', NULL, '2026-01-14 15:44:53', '2026-01-14 15:44:53'),
(16, 'products/WHOUd0KtUfEo8H9ZecK60VxneREu1QZyKlVy1g98.jpg', NULL, 'ca loc bong 244vv', 'ca loc bong 244vvca loc bong 244vvca loc bong 244vv', 200000.00, '300g, 1kg', 'Sơ chế', NULL, '2026-01-14 15:45:56', '2026-01-14 15:45:56'),
(17, 'products/I9qXv3pRjI4j0GMei30EIPmGHMSKxLYBq65judby.jpg', NULL, 'ca loc bong number2', 'ca loc bong number2ca loc bong number2ca loc bong number2', 200000.00, '300g, 1kg', 'Sơ chế', 10, '2026-01-14 15:47:11', '2026-01-22 05:28:23'),
(18, 'products/TswGdAGDrJUh2fwFOvxQBsFQ0YSaIBqtudVC49kw.jpg', NULL, 'ca loc bong test', 'ca loc bong testca loc bong test', 1000.00, '300g, 1kg', 'Sơ chế', NULL, '2026-01-16 06:17:23', '2026-01-16 06:17:23'),
(19, 'products/Eev30HthEEltMXcgcgfzhnPqMMiYwxqfbIjdXCP8.jpg', NULL, 'ca loc bong test cake', 'ca loc bong test cakeca loc bong test cake', 2000.00, '300g, 1kg', 'Sơ chế', NULL, '2026-01-18 07:05:06', '2026-01-18 07:05:06');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `jobs`
--

CREATE TABLE `jobs` (
  `job_id` int(10) UNSIGNED NOT NULL,
  `description` text NOT NULL,
  `status` tinyint(1) NOT NULL,
  `url_job_details` varchar(255) NOT NULL,
  `role_category_id` int(10) UNSIGNED DEFAULT NULL,
  `position_category_id` int(10) UNSIGNED DEFAULT NULL,
  `location_category_id` int(10) UNSIGNED DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `job_details`
--

CREATE TABLE `job_details` (
  `job_details_id` int(10) UNSIGNED NOT NULL,
  `job_id` int(10) UNSIGNED DEFAULT NULL,
  `vi_tri` varchar(255) NOT NULL,
  `total` int(11) DEFAULT NULL,
  `workplace` varchar(255) DEFAULT NULL,
  `work_address` varchar(255) DEFAULT NULL,
  `job_description` text DEFAULT NULL,
  `workday` datetime DEFAULT NULL,
  `business_hours` varchar(255) DEFAULT NULL,
  `interest` text DEFAULT NULL,
  `request` text DEFAULT NULL,
  `age` varchar(255) DEFAULT NULL,
  `level` varchar(255) DEFAULT NULL,
  `profile_included` varchar(255) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `job_positions`
--

CREATE TABLE `job_positions` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `title` varchar(255) NOT NULL,
  `content` text NOT NULL,
  `published_at` datetime DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Đang đổ dữ liệu cho bảng `job_positions`
--

INSERT INTO `job_positions` (`id`, `title`, `content`, `published_at`, `created_at`, `updated_at`) VALUES
(1, 'bla trap', '<p><strong>viet nam</strong> dsadsa</p>', '2026-01-09 00:00:00', '2026-01-09 10:37:17', '2026-01-09 10:37:45'),
(2, 'dsa', '<p>dsadwcew</p>', '2026-01-09 00:00:00', '2026-01-09 10:52:26', '2026-01-09 10:52:26');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `migrations`
--

CREATE TABLE `migrations` (
  `id` int(10) UNSIGNED NOT NULL,
  `migration` varchar(255) NOT NULL,
  `batch` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Đang đổ dữ liệu cho bảng `migrations`
--

INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES
(1, '2014_10_12_000000_create_users_table', 1),
(2, '2014_10_12_100000_create_password_resets_table', 1),
(3, '2019_08_19_000000_create_failed_jobs_table', 1),
(4, '2019_12_14_000001_create_personal_access_tokens_table', 1),
(5, '2024_07_19_195850_create_parents_table', 1),
(6, '2024_07_19_201201_create_categories_table', 1),
(7, '2024_08_11_075516_create_jobs_table', 1),
(8, '2024_08_13_224215_create_job_details_table', 2),
(9, '2024_08_14_004259_create_cvs_table', 2),
(10, '2024_08_14_161653_create_images_table', 2),
(11, '2024_08_15_024409_create_posts_table', 2),
(12, '2024_08_15_030816_create_contents_table', 2),
(13, '2024_08_18_231345_create_documents_table', 2),
(14, '2024_08_19_120729_create_posts_detail_table', 2),
(15, '2026_01_07_000000_create_orders_table', 2),
(16, '2026_01_07_120000_add_contact_to_users_table', 3),
(17, '2026_01_07_130000_add_city_district_to_users_table', 4),
(18, '2026_01_20_000000_add_price_and_product_type_to_images_table', 5),
(19, '2026_01_09_131647_add_sub_images_to_images_table', 6),
(20, '2026_01_09_131651_add_sub_images_to_posts_table', 6),
(21, '2026_01_09_132438_add_description_to_images_table', 7),
(22, '2026_01_09_133533_add_size_to_images_table', 8),
(23, '2026_01_09_163705_add_job_id_to_cvs_table', 9),
(24, '2026_01_09_171754_alter_sex_column_in_cvs_table', 10),
(25, '2026_01_09_171850_add_applied_position_to_cvs_table', 11),
(26, '2026_01_09_173022_create_job_positions_table', 12),
(27, '2026_01_11_145132_add_blog_type_to_posts_table', 13),
(28, '2026_01_11_150531_make_description_nullable_in_posts_table', 14),
(29, '2026_01_11_152605_add_status_to_posts_table', 15),
(30, '2026_01_11_152624_make_image_url_nullable_in_posts_table', 15),
(31, '2026_01_12_151412_create_sliders_table', 16),
(33, '2026_01_12_153739_add_second_button_to_sliders_table', 17),
(34, '2026_01_12_172235_create_favorites_table', 18),
(36, '2026_01_16_113142_modify_order_items_foreign_key_constraint', 19),
(38, '2026_01_16_111658_add_payment_fields_to_orders_table', 20),
(39, '2026_01_21_000001_add_discount_percent_to_images_table', 21),
(40, '2026_01_12_212324_create_favorites_table', 22),
(41, '2026_01_16_111642_create_order_items_table', 23),
(42, '2026_01_16_114104_add_shipping_address_phone_to_orders_table', 24),
(43, '2026_01_16_131237_create_payment_transactions_table', 25),
(44, '2026_01_18_135339_create_cart_items_table', 26),
(45, '2026_01_18_140904_add_columns_to_payment_transactions_table', 27),
(46, '2026_01_19_094704_add_momo_fields_to_payment_transactions_table', 28),
(47, '2026_01_22_131207_add_is_selected_to_cart_items_table', 29);

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `orders`
--

CREATE TABLE `orders` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `user_id` bigint(20) UNSIGNED NOT NULL,
  `shipping_name` varchar(255) DEFAULT NULL,
  `shipping_address` varchar(255) DEFAULT NULL,
  `shipping_phone` varchar(20) DEFAULT NULL,
  `code` varchar(255) DEFAULT NULL,
  `total_amount` bigint(20) UNSIGNED NOT NULL DEFAULT 0,
  `status` varchar(255) NOT NULL DEFAULT 'pending',
  `payment_method` varchar(255) NOT NULL DEFAULT 'cash',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `email` varchar(255) DEFAULT NULL,
  `city` varchar(255) DEFAULT NULL,
  `district` varchar(255) DEFAULT NULL,
  `note` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Đang đổ dữ liệu cho bảng `orders`
--

INSERT INTO `orders` (`id`, `user_id`, `shipping_name`, `shipping_address`, `shipping_phone`, `code`, `total_amount`, `status`, `payment_method`, `created_at`, `updated_at`, `email`, `city`, `district`, `note`) VALUES
(1, 1, 'Võ Đăng Kỷ', 'duong so 51, phuong 14', '0972471301', 'ORD202601160001', 420000, 'pending', 'cash', '2026-01-16 04:41:54', '2026-01-16 04:41:54', 'vodangky.dev@gmail.com', 'TP. Hồ Chí Minh', 'go vap', NULL),
(2, 1, 'Võ Đăng Kỷ', 'duong so 51, phuong 14', '0972471301', 'ORD202601160002', 440000, 'cancelled', 'bank', '2026-01-16 04:44:35', '2026-01-16 05:06:48', 'vodangky.dev@gmail.com', 'TP. Hồ Chí Minh', 'go vap', NULL),
(3, 1, 'Võ Đăng Kỷ', 'duong so 51, phuong 14', '0972471301', 'ORD202601160003', 840000, 'completed', 'cash', '2026-01-16 05:28:11', '2026-01-16 06:00:38', 'vodangky.dev@gmail.com', 'TP. Hồ Chí Minh', 'go vap', NULL),
(4, 1, 'Võ Đăng Kỷ', 'duong so 51, phuong 14', '0972471301', 'ORD202601160004', 440000, 'processing', 'cash', '2026-01-16 05:30:22', '2026-01-16 06:01:55', 'vodangky.dev@gmail.com', 'TP. Hồ Chí Minh', 'go vap', NULL),
(5, 1, 'Võ Đăng Kỷ', 'duong so 51, phuong 14', '0972471301', 'ORD202601160005', 420000, 'pending', 'bank', '2026-01-16 06:07:35', '2026-01-16 06:07:35', 'vodangky.dev@gmail.com', 'TP. Hồ Chí Minh', 'go vap', NULL),
(8, 1, 'Võ Đăng Kỷ', 'duong so 51, phuong 14', '0972471301', 'ORD202601160006', 420000, 'pending', 'cash', '2026-01-16 06:14:15', '2026-01-16 06:14:15', 'vodangky.dev@gmail.com', 'TP. Hồ Chí Minh', 'go vap', NULL),
(11, 1, 'Võ Đăng Kỷ', 'duong so 51, phuong 14', '0972471301', 'ORD202601160007', 420000, 'pending', 'cash', '2026-01-16 06:15:15', '2026-01-16 06:15:15', 'vodangky.dev@gmail.com', 'TP. Hồ Chí Minh', 'go vap', NULL),
(18, 1, 'Võ Đăng Kỷ', 'duong so 51, phuong 14', '0972471301', 'ORD202601160008', 400000, 'pending', 'cash', '2026-01-16 06:17:50', '2026-01-16 06:17:50', 'vodangky.dev@gmail.com', 'TP. Hồ Chí Minh', 'go vap', NULL),
(22, 1, 'Võ Đăng Kỷ', 'duong so 51, phuong 14', '0972471301', 'ORD202601160009', 1000, 'pending', 'cash', '2026-01-16 06:20:25', '2026-01-16 06:20:25', 'vodangky.dev@gmail.com', 'TP. Hồ Chí Minh', 'go vap', NULL),
(25, 1, 'Võ Đăng Kỷ', 'duong so 51, phuong 14', '0972471301', 'ORD202601160010', 1000, 'pending', 'cash', '2026-01-16 06:24:39', '2026-01-16 06:24:39', 'vodangky.dev@gmail.com', 'TP. Hồ Chí Minh', 'go vap', NULL),
(30, 1, 'Võ Đăng Kỷ', 'duong so 51, phuong 14', '0972471301', 'ORD202601160011', 1000, 'pending', 'bank', '2026-01-16 06:27:27', '2026-01-16 06:27:27', 'vodangky.dev@gmail.com', 'TP. Hồ Chí Minh', 'go vap', NULL),
(31, 1, 'Võ Đăng Kỷ', 'duong so 51, phuong 14', '0972471301', 'ORD202601180001', 1000, 'pending', 'bank', '2026-01-18 05:32:19', '2026-01-18 05:32:19', 'vodangky.dev@gmail.com', 'TP. Hồ Chí Minh', 'go vap', NULL),
(32, 1, 'Võ Đăng Kỷ', 'duong so 51, phuong 14', '0972471301', 'ORD202601180002', 201000, 'pending', 'cash', '2026-01-18 06:30:21', '2026-01-18 06:30:21', 'vodangky.dev@gmail.com', 'TP. Hồ Chí Minh', 'go vap', NULL),
(33, 1, 'Võ Đăng Kỷ', 'duong so 51, phuong 14', '0972471301', 'ORD202601180003', 1000, 'pending', 'bank', '2026-01-18 07:00:34', '2026-01-18 07:00:34', 'vodangky.dev@gmail.com', 'TP. Hồ Chí Minh', 'go vap', NULL),
(34, 1, 'Võ Đăng Kỷ', 'duong so 51, phuong 14', '0972471301', 'ORD202601180004', 1000, 'pending', 'cash', '2026-01-18 07:04:08', '2026-01-18 07:04:08', 'vodangky.dev@gmail.com', 'TP. Hồ Chí Minh', 'go vap', NULL),
(35, 1, 'Võ Đăng Kỷ', 'duong so 51, phuong 14', '0972471301', 'ORD202601180005', 2000, 'pending', 'bank', '2026-01-18 07:05:19', '2026-01-18 07:05:19', 'vodangky.dev@gmail.com', 'TP. Hồ Chí Minh', 'go vap', NULL),
(36, 1, 'Võ Đăng Kỷ', 'duong so 51, phuong 14', '0972471301', 'ORD202601180006', 2000, 'pending', 'cash', '2026-01-18 07:20:28', '2026-01-18 07:20:28', 'vodangky.dev@gmail.com', 'TP. Hồ Chí Minh', 'go vap', NULL),
(37, 1, 'Võ Đăng Kỷ', 'duong so 51, phuong 14', '0972471301', 'ORD202601180007', 2000, 'pending', 'cash', '2026-01-18 07:20:37', '2026-01-18 07:20:37', 'vodangky.dev@gmail.com', 'TP. Hồ Chí Minh', 'go vap', NULL),
(38, 1, 'Võ Đăng Kỷ', 'duong so 51, phuong 14', '0972471301', 'ORD202601180008', 2000, 'pending', 'bank', '2026-01-18 07:21:03', '2026-01-18 07:21:03', 'vodangky.dev@gmail.com', 'TP. Hồ Chí Minh', 'go vap', NULL),
(39, 1, 'Võ Đăng Kỷ', 'duong so 51, phuong 14', '0972471301', 'ORD202601180009', 2000, 'pending', 'bank', '2026-01-18 07:22:48', '2026-01-18 07:22:48', 'vodangky.dev@gmail.com', 'TP. Hồ Chí Minh', 'go vap', NULL),
(40, 1, 'Võ Đăng Kỷ', 'duong so 51, phuong 14', '0972471301', 'ORD202601180010', 2000, 'pending', 'bank', '2026-01-18 07:23:14', '2026-01-18 07:23:14', 'vodangky.dev@gmail.com', 'TP. Hồ Chí Minh', 'go vap', NULL),
(41, 1, 'Võ Đăng Kỷ', 'duong so 51, phuong 14', '0972471301', 'ORD202601180011', 2000, 'pending', 'bank', '2026-01-18 07:25:45', '2026-01-18 07:25:45', 'vodangky.dev@gmail.com', 'TP. Hồ Chí Minh', 'go vap', NULL),
(42, 1, 'Võ Đăng Kỷ', 'duong so 51, phuong 14', '0972471301', 'ORD202601180012', 2000, 'pending', 'bank', '2026-01-18 07:26:17', '2026-01-18 07:26:17', 'vodangky.dev@gmail.com', 'TP. Hồ Chí Minh', 'go vap', NULL),
(43, 1, 'Võ Đăng Kỷ', 'duong so 51, phuong 14', '0972471301', 'ORD202601180013', 2000, 'pending', 'bank', '2026-01-18 07:33:57', '2026-01-18 07:33:57', 'vodangky.dev@gmail.com', 'TP. Hồ Chí Minh', 'go vap', NULL),
(44, 1, 'Võ Đăng Kỷ', 'duong so 51, phuong 14', '0972471301', 'ORD202601180014', 2000, 'pending', 'bank', '2026-01-18 07:37:37', '2026-01-18 07:37:37', 'vodangky.dev@gmail.com', 'TP. Hồ Chí Minh', 'go vap', NULL),
(45, 1, 'Võ Đăng Kỷ', 'duong so 51, phuong 14', '0972471301', 'ORD202601180015', 2000, 'pending', 'bank', '2026-01-18 07:38:07', '2026-01-18 07:38:07', 'vodangky.dev@gmail.com', 'TP. Hồ Chí Minh', 'go vap', NULL),
(46, 1, 'Võ Đăng Kỷ', 'duong so 51, phuong 14', '0972471301', 'ORD202601180016', 2000, 'pending', 'bank', '2026-01-18 07:40:36', '2026-01-18 07:40:36', 'vodangky.dev@gmail.com', 'TP. Hồ Chí Minh', 'go vap', NULL),
(47, 1, 'Võ Đăng Kỷ', 'duong so 51, phuong 14', '0972471301', 'ORD202601180017', 2000, 'completed', 'bank', '2026-01-18 07:43:02', '2026-01-18 07:43:38', 'vodangky.dev@gmail.com', 'TP. Hồ Chí Minh', 'go vap', NULL),
(48, 1, 'Võ Đăng Kỷ', 'duong so 51, phuong 14', '0972471301', 'ORD202601180018', 2000, 'pending', 'bank', '2026-01-18 07:47:23', '2026-01-18 07:47:23', 'vodangky.dev@gmail.com', 'TP. Hồ Chí Minh', 'go vap', NULL),
(49, 1, 'Võ Đăng Kỷ', 'duong so 51, phuong 14', '0972471301', 'ORD202601180019', 2000, 'pending', 'bank', '2026-01-18 07:50:31', '2026-01-18 07:50:31', 'vodangky.dev@gmail.com', 'TP. Hồ Chí Minh', 'go vap', NULL),
(50, 1, 'Võ Đăng Kỷ', 'duong so 51, phuong 14', '0972471301', 'ORD202601180020', 2000, 'completed', 'bank', '2026-01-18 07:58:20', '2026-01-18 08:00:13', 'vodangky.dev@gmail.com', 'TP. Hồ Chí Minh', 'go vap', NULL),
(51, 1, 'Võ Đăng Kỷ', 'duong so 51, phuong 14', '0972471301', 'ORD202601180021', 2000, 'pending', 'bank', '2026-01-18 08:03:06', '2026-01-18 08:03:06', 'vodangky.dev@gmail.com', 'TP. Hồ Chí Minh', 'go vap', NULL),
(52, 1, 'Võ Đăng Kỷ', 'duong so 51, phuong 14', '0972471301', 'ORD202601190001', 2000, 'pending', 'momo', '2026-01-19 02:47:59', '2026-01-19 02:47:59', 'vodangky.dev@gmail.com', 'TP. Hồ Chí Minh', 'go vap', NULL),
(53, 1, 'Võ Đăng Kỷ', 'duong so 51, phuong 14', '0972471301', 'ORD202601190002', 2000, 'pending', 'momo', '2026-01-19 02:48:28', '2026-01-19 02:48:28', 'vodangky.dev@gmail.com', 'TP. Hồ Chí Minh', 'go vap', NULL),
(54, 1, 'Võ Đăng Kỷ', 'duong so 51, phuong 14', '0972471301', 'ORD202601190003', 2000, 'pending', 'bank', '2026-01-19 02:48:51', '2026-01-19 02:48:51', 'vodangky.dev@gmail.com', 'TP. Hồ Chí Minh', 'go vap', NULL),
(55, 1, 'Võ Đăng Kỷ', 'duong so 51, phuong 14', '0972471301', 'ORD202601190004', 2000, 'pending', 'cash', '2026-01-19 02:49:15', '2026-01-19 02:49:15', 'vodangky.dev@gmail.com', 'TP. Hồ Chí Minh', 'go vap', NULL),
(56, 1, 'Võ Đăng Kỷ', 'duong so 51, phuong 14', '0972471301', 'ORD202601190005', 2000, 'pending', 'momo', '2026-01-19 02:49:44', '2026-01-19 02:49:44', 'vodangky.dev@gmail.com', 'TP. Hồ Chí Minh', 'go vap', NULL),
(57, 1, 'Võ Đăng Kỷ', 'duong so 51, phuong 14', '0972471301', 'ORD202601190006', 2000, 'pending', 'bank', '2026-01-19 02:50:35', '2026-01-19 02:50:35', 'vodangky.dev@gmail.com', 'TP. Hồ Chí Minh', 'go vap', NULL),
(58, 1, 'Võ Đăng Kỷ', 'duong so 51, phuong 14', '0972471301', 'ORD202601190007', 2000, 'pending', 'bank', '2026-01-19 02:52:25', '2026-01-19 02:52:25', 'vodangky.dev@gmail.com', 'TP. Hồ Chí Minh', 'go vap', NULL),
(59, 1, 'Võ Đăng Kỷ', 'duong so 51, phuong 14', '0972471301', 'ORD202601190008', 2000, 'pending', 'momo', '2026-01-19 02:53:21', '2026-01-19 02:53:21', 'vodangky.dev@gmail.com', 'TP. Hồ Chí Minh', 'go vap', NULL),
(60, 1, 'Võ Đăng Kỷ', 'duong so 51, phuong 14', '0972471301', 'ORD202601190009', 2000, 'pending', 'bank', '2026-01-19 02:54:05', '2026-01-19 02:54:05', 'vodangky.dev@gmail.com', 'TP. Hồ Chí Minh', 'go vap', NULL),
(61, 1, 'Võ Đăng Kỷ', 'duong so 51, phuong 14', '0972471301', 'ORD202601190010', 2000, 'pending', 'bank', '2026-01-19 02:55:12', '2026-01-19 02:55:12', 'vodangky.dev@gmail.com', 'TP. Hồ Chí Minh', 'go vap', NULL),
(62, 1, 'Võ Đăng Kỷ', 'duong so 51, phuong 14', '0972471301', 'ORD202601190011', 2000, 'cancelled', 'momo', '2026-01-19 02:55:35', '2026-01-19 02:56:15', 'vodangky.dev@gmail.com', 'TP. Hồ Chí Minh', 'go vap', NULL),
(63, 1, 'Võ Đăng Kỷ', 'duong so 51, phuong 14', '0972471301', 'ORD202601190012', 2000, 'cancelled', 'momo', '2026-01-19 02:56:23', '2026-01-19 02:57:45', 'vodangky.dev@gmail.com', 'TP. Hồ Chí Minh', 'go vap', NULL),
(64, 1, 'Võ Đăng Kỷ', 'duong so 51, phuong 14', '0972471301', 'ORD202601190013', 2000, 'pending', 'momo', '2026-01-19 02:57:52', '2026-01-19 02:57:52', 'vodangky.dev@gmail.com', 'TP. Hồ Chí Minh', 'go vap', NULL),
(65, 1, 'Võ Đăng Kỷ', 'duong so 51, phuong 14', '0972471301', 'ORD202601190014', 2000, 'pending', 'momo', '2026-01-19 02:58:19', '2026-01-19 02:58:19', 'vodangky.dev@gmail.com', 'TP. Hồ Chí Minh', 'go vap', NULL),
(66, 1, 'Võ Đăng Kỷ', 'duong so 51, phuong 14', '0972471301', 'ORD202601190015', 2000, 'pending', 'momo', '2026-01-19 03:10:22', '2026-01-19 03:10:22', 'vodangky.dev@gmail.com', 'TP. Hồ Chí Minh', 'go vap', NULL),
(67, 1, 'Võ Đăng Kỷ', 'duong so 51, phuong 14', '0972471301', 'ORD202601190016', 2000, 'pending', 'momo', '2026-01-19 03:12:18', '2026-01-19 03:12:18', 'vodangky.dev@gmail.com', 'TP. Hồ Chí Minh', 'go vap', NULL),
(68, 1, 'Võ Đăng Kỷ', 'duong so 51, phuong 14', '0972471301', 'ORD202601190017', 2000, 'pending', 'momo', '2026-01-19 03:15:26', '2026-01-19 03:15:26', 'vodangky.dev@gmail.com', 'TP. Hồ Chí Minh', 'go vap', NULL),
(69, 1, 'Võ Đăng Kỷ', 'duong so 51, phuong 14', '0972471301', 'ORD202601190018', 2000, 'pending', 'momo', '2026-01-19 03:17:11', '2026-01-19 03:17:11', 'vodangky.dev@gmail.com', 'TP. Hồ Chí Minh', 'go vap', NULL),
(70, 1, 'Võ Đăng Kỷ', 'duong so 51, phuong 14', '0972471301', 'ORD202601190019', 2000, 'pending', 'momo', '2026-01-19 03:23:59', '2026-01-19 03:23:59', 'vodangky.dev@gmail.com', 'TP. Hồ Chí Minh', 'go vap', NULL),
(71, 1, 'Võ Đăng Kỷ', 'duong so 51, phuong 14', '0972471301', 'ORD202601190020', 2000, 'pending', 'momo', '2026-01-19 03:28:56', '2026-01-19 03:28:56', 'vodangky.dev@gmail.com', 'TP. Hồ Chí Minh', 'go vap', NULL),
(72, 1, 'Võ Đăng Kỷ', 'duong so 51, phuong 14', '0972471301', 'ORD202601190021', 2000, 'pending', 'momo', '2026-01-19 03:33:20', '2026-01-19 03:33:20', 'vodangky.dev@gmail.com', 'TP. Hồ Chí Minh', 'go vap', NULL),
(73, 1, 'Võ Đăng Kỷ', 'duong so 51, phuong 14', '0972471301', 'ORD202601190022', 2000, 'completed', 'momo', '2026-01-19 03:38:41', '2026-02-25 04:07:14', 'vodangky.dev@gmail.com', 'TP. Hồ Chí Minh', 'go vap', NULL),
(74, 1, 'Võ Đăng Kỷ', 'duong so 51, phuong 14', '0972471301', 'ORD202601190023', 204000, 'pending', 'momo', '2026-01-19 04:20:06', '2026-01-19 04:20:06', 'vodangky.dev@gmail.com', 'TP. Hồ Chí Minh', 'go vap', NULL),
(75, 1, 'Võ Đăng Kỷ', 'duong so 51, phuong 14', '0972471301', 'ORD202601190024', 2000, 'pending', 'momo', '2026-01-19 04:21:47', '2026-01-19 04:21:47', 'vodangky.dev@gmail.com', 'TP. Hồ Chí Minh', 'go vap', NULL),
(76, 1, 'Võ Đăng Kỷ', 'duong so 51, phuong 14', '0972471301', 'ORD202601190025', 2000, 'pending', 'cash', '2026-01-19 04:23:37', '2026-01-19 04:23:37', 'vodangky.dev@gmail.com', 'TP. Hồ Chí Minh', 'go vap', NULL),
(77, 1, 'Võ Đăng Kỷ', 'duong so 51, phuong 14', '0972471301', 'ORD202601220001', 1203000, 'completed', 'cash', '2026-01-22 04:10:45', '2026-02-25 03:59:10', 'vodangky.dev@gmail.com', 'TP. Hồ Chí Minh', 'go vap', NULL),
(78, 1, 'Võ Đăng Kỷ', 'duong so 51, phuong 14', '0972471301', 'ORD202601220002', 2000, 'pending', 'momo', '2026-01-22 04:21:20', '2026-01-22 04:21:20', 'vodangky.dev@gmail.com', 'TP. Hồ Chí Minh', 'go vap', NULL),
(79, 1, 'Võ Đăng Kỷ', 'duong so 51, phuong 14', '0972471301', 'ORD202601220003', 2000, 'pending', 'momo', '2026-01-22 04:26:21', '2026-01-22 04:26:21', 'vodangky.dev@gmail.com', 'TP. Hồ Chí Minh', 'go vap', NULL),
(80, 1, 'Võ Đăng Kỷ', 'duong so 51, phuong 14', '0972471301', 'ORD202601220004', 2000, 'pending', 'bank', '2026-01-22 04:31:04', '2026-01-22 04:31:04', 'vodangky.dev@gmail.com', 'TP. Hồ Chí Minh', 'go vap', NULL),
(81, 1, 'Võ Đăng Kỷ', 'duong so 51, phuong 14', '0972471301', 'ORD202601220005', 2000, 'pending', 'momo', '2026-01-22 04:33:23', '2026-01-22 04:33:23', 'vodangky.dev@gmail.com', 'TP. Hồ Chí Minh', 'go vap', NULL),
(82, 1, 'Võ Đăng Kỷ', 'duong so 51, phuong 14', '0972471301', 'ORD202601220006', 2000, 'pending', 'momo', '2026-01-22 04:51:31', '2026-01-22 04:51:31', 'vodangky.dev@gmail.com', 'TP. Hồ Chí Minh', 'go vap', NULL),
(83, 1, 'Võ Đăng Kỷ', 'duong so 51, phuong 14', '0972471301', 'ORD202601220007', 2000, 'pending', 'cash', '2026-01-22 04:57:28', '2026-01-22 04:57:28', 'vodangky.dev@gmail.com', 'TP. Hồ Chí Minh', 'go vap', NULL),
(84, 1, 'Võ Đăng Kỷ', 'duong so 51, phuong 14', '0972471301', 'ORD202601220008', 2000, 'pending', 'cash', '2026-01-22 05:01:51', '2026-01-22 05:01:51', 'vodangky.dev@gmail.com', 'TP. Hồ Chí Minh', 'go vap', NULL),
(85, 1, 'Võ Đăng Kỷ', 'duong so 51, phuong 14', '0972471301', 'ORD202602110001', 2000, 'pending', 'momo', '2026-02-11 04:11:49', '2026-02-11 04:11:49', 'vodangky.dev@gmail.com', 'TP. Hồ Chí Minh', 'go vap', NULL),
(86, 1, 'Võ Đăng Kỷ', 'duong so 51, phuong 14', '0972471301', 'ORD202602110002', 14000, 'pending', 'bank', '2026-02-11 06:38:40', '2026-02-11 06:38:40', 'vodangky.dev@gmail.com', 'TP. Hồ Chí Minh', 'go vap', NULL),
(87, 1, 'Võ Đăng Kỷ', 'duong so 51, phuong 14', '0972471301', 'ORD202602250001', 27000, 'completed', 'cash', '2026-02-25 03:57:52', '2026-02-25 03:58:45', 'vodangky.dev@gmail.com', 'TP. Hồ Chí Minh', 'go vap', NULL),
(88, 1, 'Võ Đăng Kỷ', 'duong so 51, phuong 14', '0972471301', 'ORD202602250002', 20000, 'completed', 'cash', '2026-02-25 03:58:05', '2026-02-25 03:58:42', 'vodangky.dev@gmail.com', 'TP. Hồ Chí Minh', 'go vap', NULL),
(89, 1, 'Võ Đăng Kỷ', 'duong so 51, phuong 14', '0972471301', 'ORD202602250003', 20000, 'completed', 'cash', '2026-02-25 04:07:45', '2026-02-25 04:07:53', 'vodangky.dev@gmail.com', 'TP. Hồ Chí Minh', 'go vap', NULL),
(90, 1, 'Võ Đăng Kỷ', 'duong so 51, phuong 14', '0972471301', 'ORD202602250004', 20000, 'completed', 'cash', '2026-02-25 04:08:12', '2026-02-25 04:08:17', 'vodangky.dev@gmail.com', 'TP. Hồ Chí Minh', 'go vap', NULL);

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `order_items`
--

CREATE TABLE `order_items` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `order_id` bigint(20) UNSIGNED NOT NULL,
  `product_id` bigint(20) UNSIGNED NOT NULL,
  `product_name` varchar(255) NOT NULL,
  `product_price` bigint(20) UNSIGNED NOT NULL,
  `quantity` int(11) NOT NULL,
  `subtotal` bigint(20) UNSIGNED NOT NULL,
  `product_image` varchar(255) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Đang đổ dữ liệu cho bảng `order_items`
--

INSERT INTO `order_items` (`id`, `order_id`, `product_id`, `product_name`, `product_price`, `quantity`, `subtotal`, `product_image`, `created_at`, `updated_at`) VALUES
(1, 1, 15, 'san pham khuyen mai 2', 200000, 2, 400000, 'http://localhost:8080/Phuthotourt/storage/products/AJgnDGcR8KRNHcY96896ZDyuqnXMSgMn03u41qpK.jpg', '2026-01-16 04:41:54', '2026-01-16 04:41:54'),
(2, 1, 9, 'ca loc bong b32', 20000, 1, 20000, 'http://localhost:8080/Phuthotourt/storage/products/6cMDz67wahYNNHT2cfznPlRy7dOVatMd2Gmh88Bx.jpg', '2026-01-16 04:41:54', '2026-01-16 04:41:54'),
(3, 2, 16, 'ca loc bong 244vv', 200000, 1, 200000, 'http://localhost:8080/Phuthotourt/storage/products/WHOUd0KtUfEo8H9ZecK60VxneREu1QZyKlVy1g98.jpg', '2026-01-16 04:44:35', '2026-01-16 04:44:35'),
(4, 2, 8, 'ca loc bong 234', 20000, 2, 40000, 'http://localhost:8080/Phuthotourt/storage/products/mEqRLVlnXHRHIYYJ4h34P55pb56oAzlSFYTKWMVM.jpg', '2026-01-16 04:44:35', '2026-01-16 04:44:35'),
(5, 2, 7, 'ca loc bong 23', 200000, 1, 200000, 'http://localhost:8080/Phuthotourt/storage/products/yHfIFteI1BEy519q6nt658JlKxXKey5kShR2Zs3t.jpg', '2026-01-16 04:44:35', '2026-01-16 04:44:35'),
(6, 3, 16, 'ca loc bong 244vv', 200000, 2, 400000, 'http://localhost:8080/Phuthotourt/storage/products/WHOUd0KtUfEo8H9ZecK60VxneREu1QZyKlVy1g98.jpg', '2026-01-16 05:28:11', '2026-01-16 05:28:11'),
(7, 3, 15, 'san pham khuyen mai 2', 200000, 1, 200000, 'http://localhost:8080/Phuthotourt/storage/products/AJgnDGcR8KRNHcY96896ZDyuqnXMSgMn03u41qpK.jpg', '2026-01-16 05:28:11', '2026-01-16 05:28:11'),
(8, 3, 8, 'ca loc bong 234', 20000, 2, 40000, 'http://localhost:8080/Phuthotourt/storage/products/mEqRLVlnXHRHIYYJ4h34P55pb56oAzlSFYTKWMVM.jpg', '2026-01-16 05:28:11', '2026-01-16 05:28:11'),
(9, 3, 7, 'ca loc bong 23', 200000, 1, 200000, 'http://localhost:8080/Phuthotourt/storage/products/yHfIFteI1BEy519q6nt658JlKxXKey5kShR2Zs3t.jpg', '2026-01-16 05:28:11', '2026-01-16 05:28:11'),
(10, 4, 16, 'ca loc bong 244vv', 200000, 1, 200000, 'http://localhost:8080/Phuthotourt/storage/products/WHOUd0KtUfEo8H9ZecK60VxneREu1QZyKlVy1g98.jpg', '2026-01-16 05:30:22', '2026-01-16 05:30:22'),
(11, 4, 8, 'ca loc bong 234', 20000, 2, 40000, 'http://localhost:8080/Phuthotourt/storage/products/mEqRLVlnXHRHIYYJ4h34P55pb56oAzlSFYTKWMVM.jpg', '2026-01-16 05:30:22', '2026-01-16 05:30:22'),
(12, 4, 7, 'ca loc bong 23', 200000, 1, 200000, 'http://localhost:8080/Phuthotourt/storage/products/yHfIFteI1BEy519q6nt658JlKxXKey5kShR2Zs3t.jpg', '2026-01-16 05:30:22', '2026-01-16 05:30:22'),
(13, 5, 16, 'ca loc bong 244vv', 200000, 1, 200000, 'http://localhost:8080/Phuthotourt/storage/products/WHOUd0KtUfEo8H9ZecK60VxneREu1QZyKlVy1g98.jpg', '2026-01-16 06:07:35', '2026-01-16 06:07:35'),
(14, 5, 15, 'san pham khuyen mai 2', 200000, 1, 200000, 'http://localhost:8080/Phuthotourt/storage/products/AJgnDGcR8KRNHcY96896ZDyuqnXMSgMn03u41qpK.jpg', '2026-01-16 06:07:35', '2026-01-16 06:07:35'),
(15, 5, 14, 'san pham khuyen mai', 20000, 1, 20000, 'http://localhost:8080/Phuthotourt/storage/products/TC4jgWLiJAZ3y6uQHgSHkMgpDhhliD5WICH0VQNi.jpg', '2026-01-16 06:07:35', '2026-01-16 06:07:35'),
(22, 8, 16, 'ca loc bong 244vv', 200000, 1, 200000, 'http://localhost:8080/Phuthotourt/storage/products/WHOUd0KtUfEo8H9ZecK60VxneREu1QZyKlVy1g98.jpg', '2026-01-16 06:14:15', '2026-01-16 06:14:15'),
(23, 8, 15, 'san pham khuyen mai 2', 200000, 1, 200000, 'http://localhost:8080/Phuthotourt/storage/products/AJgnDGcR8KRNHcY96896ZDyuqnXMSgMn03u41qpK.jpg', '2026-01-16 06:14:15', '2026-01-16 06:14:15'),
(24, 8, 14, 'san pham khuyen mai', 20000, 1, 20000, 'http://localhost:8080/Phuthotourt/storage/products/TC4jgWLiJAZ3y6uQHgSHkMgpDhhliD5WICH0VQNi.jpg', '2026-01-16 06:14:15', '2026-01-16 06:14:15'),
(31, 11, 16, 'ca loc bong 244vv', 200000, 1, 200000, 'http://localhost:8080/Phuthotourt/storage/products/WHOUd0KtUfEo8H9ZecK60VxneREu1QZyKlVy1g98.jpg', '2026-01-16 06:15:15', '2026-01-16 06:15:15'),
(32, 11, 15, 'san pham khuyen mai 2', 200000, 1, 200000, 'http://localhost:8080/Phuthotourt/storage/products/AJgnDGcR8KRNHcY96896ZDyuqnXMSgMn03u41qpK.jpg', '2026-01-16 06:15:15', '2026-01-16 06:15:15'),
(33, 11, 14, 'san pham khuyen mai', 20000, 1, 20000, 'http://localhost:8080/Phuthotourt/storage/products/TC4jgWLiJAZ3y6uQHgSHkMgpDhhliD5WICH0VQNi.jpg', '2026-01-16 06:15:15', '2026-01-16 06:15:15'),
(46, 18, 16, 'ca loc bong 244vv', 200000, 1, 200000, 'http://localhost:8080/Phuthotourt/storage/products/WHOUd0KtUfEo8H9ZecK60VxneREu1QZyKlVy1g98.jpg', '2026-01-16 06:17:50', '2026-01-16 06:17:50'),
(47, 18, 15, 'san pham khuyen mai 2', 200000, 1, 200000, 'http://localhost:8080/Phuthotourt/storage/products/AJgnDGcR8KRNHcY96896ZDyuqnXMSgMn03u41qpK.jpg', '2026-01-16 06:17:50', '2026-01-16 06:17:50'),
(51, 22, 18, 'ca loc bong test', 1000, 1, 1000, 'http://localhost:8080/Phuthotourt/storage/products/TswGdAGDrJUh2fwFOvxQBsFQ0YSaIBqtudVC49kw.jpg', '2026-01-16 06:20:25', '2026-01-16 06:20:25'),
(54, 25, 18, 'ca loc bong test', 1000, 1, 1000, 'http://localhost:8080/Phuthotourt/storage/products/TswGdAGDrJUh2fwFOvxQBsFQ0YSaIBqtudVC49kw.jpg', '2026-01-16 06:24:39', '2026-01-16 06:24:39'),
(59, 30, 18, 'ca loc bong test', 1000, 1, 1000, 'http://localhost:8080/Phuthotourt/storage/products/TswGdAGDrJUh2fwFOvxQBsFQ0YSaIBqtudVC49kw.jpg', '2026-01-16 06:27:27', '2026-01-16 06:27:27'),
(60, 31, 18, 'ca loc bong test', 1000, 1, 1000, 'http://localhost:8080/Phuthotourt/storage/products/TswGdAGDrJUh2fwFOvxQBsFQ0YSaIBqtudVC49kw.jpg', '2026-01-18 05:32:19', '2026-01-18 05:32:19'),
(61, 32, 17, 'ca loc bong number2', 200000, 1, 200000, 'http://localhost:8080/Phuthotourt/storage/products/I9qXv3pRjI4j0GMei30EIPmGHMSKxLYBq65judby.jpg', '2026-01-18 06:30:21', '2026-01-18 06:30:21'),
(62, 32, 18, 'ca loc bong test', 1000, 1, 1000, 'http://localhost:8080/Phuthotourt/storage/products/TswGdAGDrJUh2fwFOvxQBsFQ0YSaIBqtudVC49kw.jpg', '2026-01-18 06:30:21', '2026-01-18 06:30:21'),
(63, 33, 18, 'ca loc bong test', 1000, 1, 1000, 'http://localhost:8080/Phuthotourt/storage/products/TswGdAGDrJUh2fwFOvxQBsFQ0YSaIBqtudVC49kw.jpg', '2026-01-18 07:00:34', '2026-01-18 07:00:34'),
(64, 34, 18, 'ca loc bong test', 1000, 1, 1000, 'http://localhost:8080/Phuthotourt/storage/products/TswGdAGDrJUh2fwFOvxQBsFQ0YSaIBqtudVC49kw.jpg', '2026-01-18 07:04:08', '2026-01-18 07:04:08'),
(65, 35, 19, 'ca loc bong test cake', 2000, 1, 2000, 'http://localhost:8080/Phuthotourt/storage/products/Eev30HthEEltMXcgcgfzhnPqMMiYwxqfbIjdXCP8.jpg', '2026-01-18 07:05:19', '2026-01-18 07:05:19'),
(66, 36, 18, 'ca loc bong test', 1000, 2, 2000, 'http://localhost:8080/Phuthotourt/storage/products/TswGdAGDrJUh2fwFOvxQBsFQ0YSaIBqtudVC49kw.jpg', '2026-01-18 07:20:28', '2026-01-18 07:20:28'),
(67, 37, 19, 'ca loc bong test cake', 2000, 1, 2000, 'http://localhost:8080/Phuthotourt/storage/products/Eev30HthEEltMXcgcgfzhnPqMMiYwxqfbIjdXCP8.jpg', '2026-01-18 07:20:37', '2026-01-18 07:20:37'),
(68, 38, 19, 'ca loc bong test cake', 2000, 1, 2000, 'http://localhost:8080/Phuthotourt/storage/products/Eev30HthEEltMXcgcgfzhnPqMMiYwxqfbIjdXCP8.jpg', '2026-01-18 07:21:03', '2026-01-18 07:21:03'),
(69, 39, 19, 'ca loc bong test cake', 2000, 1, 2000, 'http://localhost:8080/Phuthotourt/storage/products/Eev30HthEEltMXcgcgfzhnPqMMiYwxqfbIjdXCP8.jpg', '2026-01-18 07:22:48', '2026-01-18 07:22:48'),
(70, 40, 19, 'ca loc bong test cake', 2000, 1, 2000, 'http://localhost:8080/Phuthotourt/storage/products/Eev30HthEEltMXcgcgfzhnPqMMiYwxqfbIjdXCP8.jpg', '2026-01-18 07:23:14', '2026-01-18 07:23:14'),
(71, 41, 19, 'ca loc bong test cake', 2000, 1, 2000, 'http://localhost:8080/Phuthotourt/storage/products/Eev30HthEEltMXcgcgfzhnPqMMiYwxqfbIjdXCP8.jpg', '2026-01-18 07:25:45', '2026-01-18 07:25:45'),
(72, 42, 19, 'ca loc bong test cake', 2000, 1, 2000, 'http://localhost:8080/Phuthotourt/storage/products/Eev30HthEEltMXcgcgfzhnPqMMiYwxqfbIjdXCP8.jpg', '2026-01-18 07:26:17', '2026-01-18 07:26:17'),
(73, 43, 19, 'ca loc bong test cake', 2000, 1, 2000, 'http://localhost:8080/Phuthotourt/storage/products/Eev30HthEEltMXcgcgfzhnPqMMiYwxqfbIjdXCP8.jpg', '2026-01-18 07:33:57', '2026-01-18 07:33:57'),
(74, 44, 19, 'ca loc bong test cake', 2000, 1, 2000, 'http://localhost:8080/Phuthotourt/storage/products/Eev30HthEEltMXcgcgfzhnPqMMiYwxqfbIjdXCP8.jpg', '2026-01-18 07:37:37', '2026-01-18 07:37:37'),
(75, 45, 19, 'ca loc bong test cake', 2000, 1, 2000, 'http://localhost:8080/Phuthotourt/storage/products/Eev30HthEEltMXcgcgfzhnPqMMiYwxqfbIjdXCP8.jpg', '2026-01-18 07:38:07', '2026-01-18 07:38:07'),
(76, 46, 19, 'ca loc bong test cake', 2000, 1, 2000, 'http://localhost:8080/Phuthotourt/storage/products/Eev30HthEEltMXcgcgfzhnPqMMiYwxqfbIjdXCP8.jpg', '2026-01-18 07:40:36', '2026-01-18 07:40:36'),
(77, 47, 19, 'ca loc bong test cake', 2000, 1, 2000, 'http://localhost:8080/Phuthotourt/storage/products/Eev30HthEEltMXcgcgfzhnPqMMiYwxqfbIjdXCP8.jpg', '2026-01-18 07:43:02', '2026-01-18 07:43:02'),
(78, 48, 19, 'ca loc bong test cake', 2000, 1, 2000, 'http://localhost:8080/Phuthotourt/storage/products/Eev30HthEEltMXcgcgfzhnPqMMiYwxqfbIjdXCP8.jpg', '2026-01-18 07:47:23', '2026-01-18 07:47:23'),
(79, 49, 19, 'ca loc bong test cake', 2000, 1, 2000, 'http://localhost:8080/Phuthotourt/storage/products/Eev30HthEEltMXcgcgfzhnPqMMiYwxqfbIjdXCP8.jpg', '2026-01-18 07:50:31', '2026-01-18 07:50:31'),
(80, 50, 19, 'ca loc bong test cake', 2000, 1, 2000, 'http://localhost:8080/Phuthotourt/storage/products/Eev30HthEEltMXcgcgfzhnPqMMiYwxqfbIjdXCP8.jpg', '2026-01-18 07:58:20', '2026-01-18 07:58:20'),
(81, 51, 19, 'ca loc bong test cake', 2000, 1, 2000, 'http://localhost:8080/Phuthotourt/storage/products/Eev30HthEEltMXcgcgfzhnPqMMiYwxqfbIjdXCP8.jpg', '2026-01-18 08:03:06', '2026-01-18 08:03:06'),
(82, 52, 19, 'ca loc bong test cake', 2000, 1, 2000, 'http://localhost:8080/Phuthotourt/storage/products/Eev30HthEEltMXcgcgfzhnPqMMiYwxqfbIjdXCP8.jpg', '2026-01-19 02:47:59', '2026-01-19 02:47:59'),
(83, 53, 19, 'ca loc bong test cake', 2000, 1, 2000, 'http://localhost:8080/Phuthotourt/storage/products/Eev30HthEEltMXcgcgfzhnPqMMiYwxqfbIjdXCP8.jpg', '2026-01-19 02:48:28', '2026-01-19 02:48:28'),
(84, 54, 19, 'ca loc bong test cake', 2000, 1, 2000, 'http://localhost:8080/Phuthotourt/storage/products/Eev30HthEEltMXcgcgfzhnPqMMiYwxqfbIjdXCP8.jpg', '2026-01-19 02:48:51', '2026-01-19 02:48:51'),
(85, 55, 19, 'ca loc bong test cake', 2000, 1, 2000, 'http://localhost:8080/Phuthotourt/storage/products/Eev30HthEEltMXcgcgfzhnPqMMiYwxqfbIjdXCP8.jpg', '2026-01-19 02:49:15', '2026-01-19 02:49:15'),
(86, 56, 19, 'ca loc bong test cake', 2000, 1, 2000, 'http://localhost:8080/Phuthotourt/storage/products/Eev30HthEEltMXcgcgfzhnPqMMiYwxqfbIjdXCP8.jpg', '2026-01-19 02:49:44', '2026-01-19 02:49:44'),
(87, 57, 19, 'ca loc bong test cake', 2000, 1, 2000, 'http://localhost:8080/Phuthotourt/storage/products/Eev30HthEEltMXcgcgfzhnPqMMiYwxqfbIjdXCP8.jpg', '2026-01-19 02:50:35', '2026-01-19 02:50:35'),
(88, 58, 19, 'ca loc bong test cake', 2000, 1, 2000, 'http://localhost:8080/Phuthotourt/storage/products/Eev30HthEEltMXcgcgfzhnPqMMiYwxqfbIjdXCP8.jpg', '2026-01-19 02:52:25', '2026-01-19 02:52:25'),
(89, 59, 19, 'ca loc bong test cake', 2000, 1, 2000, 'http://localhost:8080/Phuthotourt/storage/products/Eev30HthEEltMXcgcgfzhnPqMMiYwxqfbIjdXCP8.jpg', '2026-01-19 02:53:21', '2026-01-19 02:53:21'),
(90, 60, 19, 'ca loc bong test cake', 2000, 1, 2000, 'http://localhost:8080/Phuthotourt/storage/products/Eev30HthEEltMXcgcgfzhnPqMMiYwxqfbIjdXCP8.jpg', '2026-01-19 02:54:05', '2026-01-19 02:54:05'),
(91, 61, 19, 'ca loc bong test cake', 2000, 1, 2000, 'http://localhost:8080/Phuthotourt/storage/products/Eev30HthEEltMXcgcgfzhnPqMMiYwxqfbIjdXCP8.jpg', '2026-01-19 02:55:12', '2026-01-19 02:55:12'),
(92, 62, 19, 'ca loc bong test cake', 2000, 1, 2000, 'http://localhost:8080/Phuthotourt/storage/products/Eev30HthEEltMXcgcgfzhnPqMMiYwxqfbIjdXCP8.jpg', '2026-01-19 02:55:35', '2026-01-19 02:55:35'),
(93, 63, 19, 'ca loc bong test cake', 2000, 1, 2000, 'http://localhost:8080/Phuthotourt/storage/products/Eev30HthEEltMXcgcgfzhnPqMMiYwxqfbIjdXCP8.jpg', '2026-01-19 02:56:23', '2026-01-19 02:56:23'),
(94, 64, 19, 'ca loc bong test cake', 2000, 1, 2000, 'http://localhost:8080/Phuthotourt/storage/products/Eev30HthEEltMXcgcgfzhnPqMMiYwxqfbIjdXCP8.jpg', '2026-01-19 02:57:52', '2026-01-19 02:57:52'),
(95, 65, 19, 'ca loc bong test cake', 2000, 1, 2000, 'http://localhost:8080/Phuthotourt/storage/products/Eev30HthEEltMXcgcgfzhnPqMMiYwxqfbIjdXCP8.jpg', '2026-01-19 02:58:19', '2026-01-19 02:58:19'),
(96, 66, 19, 'ca loc bong test cake', 2000, 1, 2000, 'http://localhost:8080/Phuthotourt/storage/products/Eev30HthEEltMXcgcgfzhnPqMMiYwxqfbIjdXCP8.jpg', '2026-01-19 03:10:22', '2026-01-19 03:10:22'),
(97, 67, 19, 'ca loc bong test cake', 2000, 1, 2000, 'http://localhost:8080/Phuthotourt/storage/products/Eev30HthEEltMXcgcgfzhnPqMMiYwxqfbIjdXCP8.jpg', '2026-01-19 03:12:18', '2026-01-19 03:12:18'),
(98, 68, 19, 'ca loc bong test cake', 2000, 1, 2000, 'http://localhost:8080/Phuthotourt/storage/products/Eev30HthEEltMXcgcgfzhnPqMMiYwxqfbIjdXCP8.jpg', '2026-01-19 03:15:26', '2026-01-19 03:15:26'),
(99, 69, 19, 'ca loc bong test cake', 2000, 1, 2000, 'http://localhost:8080/Phuthotourt/storage/products/Eev30HthEEltMXcgcgfzhnPqMMiYwxqfbIjdXCP8.jpg', '2026-01-19 03:17:11', '2026-01-19 03:17:11'),
(100, 70, 19, 'ca loc bong test cake', 2000, 1, 2000, 'http://localhost:8080/Phuthotourt/storage/products/Eev30HthEEltMXcgcgfzhnPqMMiYwxqfbIjdXCP8.jpg', '2026-01-19 03:23:59', '2026-01-19 03:23:59'),
(101, 71, 19, 'ca loc bong test cake', 2000, 1, 2000, 'http://localhost:8080/Phuthotourt/storage/products/Eev30HthEEltMXcgcgfzhnPqMMiYwxqfbIjdXCP8.jpg', '2026-01-19 03:28:56', '2026-01-19 03:28:56'),
(102, 72, 19, 'ca loc bong test cake', 2000, 1, 2000, 'http://localhost:8080/Phuthotourt/storage/products/Eev30HthEEltMXcgcgfzhnPqMMiYwxqfbIjdXCP8.jpg', '2026-01-19 03:33:20', '2026-01-19 03:33:20'),
(103, 73, 19, 'ca loc bong test cake', 2000, 1, 2000, 'http://localhost:8080/Phuthotourt/storage/products/Eev30HthEEltMXcgcgfzhnPqMMiYwxqfbIjdXCP8.jpg', '2026-01-19 03:38:41', '2026-01-19 03:38:41'),
(104, 74, 19, 'ca loc bong test cake', 2000, 2, 4000, 'http://localhost:8080/Phuthotourt/storage/products/Eev30HthEEltMXcgcgfzhnPqMMiYwxqfbIjdXCP8.jpg', '2026-01-19 04:20:06', '2026-01-19 04:20:06'),
(105, 74, 17, 'ca loc bong number2', 200000, 1, 200000, 'http://localhost:8080/Phuthotourt/storage/products/I9qXv3pRjI4j0GMei30EIPmGHMSKxLYBq65judby.jpg', '2026-01-19 04:20:06', '2026-01-19 04:20:06'),
(106, 75, 19, 'ca loc bong test cake', 2000, 1, 2000, 'http://localhost:8080/Phuthotourt/storage/products/Eev30HthEEltMXcgcgfzhnPqMMiYwxqfbIjdXCP8.jpg', '2026-01-19 04:21:47', '2026-01-19 04:21:47'),
(107, 76, 18, 'ca loc bong test', 1000, 2, 2000, 'http://localhost:8080/Phuthotourt/storage/products/TswGdAGDrJUh2fwFOvxQBsFQ0YSaIBqtudVC49kw.jpg', '2026-01-19 04:23:37', '2026-01-19 04:23:37'),
(108, 77, 16, 'ca loc bong 244vv', 200000, 6, 1200000, 'http://localhost:8080/Phuthotourt/storage/products/WHOUd0KtUfEo8H9ZecK60VxneREu1QZyKlVy1g98.jpg', '2026-01-22 04:10:45', '2026-01-22 04:10:45'),
(109, 77, 18, 'ca loc bong test', 1000, 1, 1000, 'http://localhost:8080/Phuthotourt/storage/products/TswGdAGDrJUh2fwFOvxQBsFQ0YSaIBqtudVC49kw.jpg', '2026-01-22 04:10:45', '2026-01-22 04:10:45'),
(110, 77, 19, 'ca loc bong test cake', 2000, 1, 2000, 'http://localhost:8080/Phuthotourt/storage/products/Eev30HthEEltMXcgcgfzhnPqMMiYwxqfbIjdXCP8.jpg', '2026-01-22 04:10:45', '2026-01-22 04:10:45'),
(111, 78, 19, 'ca loc bong test cake', 2000, 1, 2000, 'http://localhost:8080/Phuthotourt/storage/products/Eev30HthEEltMXcgcgfzhnPqMMiYwxqfbIjdXCP8.jpg', '2026-01-22 04:21:20', '2026-01-22 04:21:20'),
(112, 79, 19, 'ca loc bong test cake', 2000, 1, 2000, 'http://localhost:8080/Phuthotourt/storage/products/Eev30HthEEltMXcgcgfzhnPqMMiYwxqfbIjdXCP8.jpg', '2026-01-22 04:26:21', '2026-01-22 04:26:21'),
(113, 80, 19, 'ca loc bong test cake', 2000, 1, 2000, 'http://localhost:8080/Phuthotourt/storage/products/Eev30HthEEltMXcgcgfzhnPqMMiYwxqfbIjdXCP8.jpg', '2026-01-22 04:31:04', '2026-01-22 04:31:04'),
(114, 81, 19, 'ca loc bong test cake', 2000, 1, 2000, 'http://localhost:8080/Phuthotourt/storage/products/Eev30HthEEltMXcgcgfzhnPqMMiYwxqfbIjdXCP8.jpg', '2026-01-22 04:33:23', '2026-01-22 04:33:23'),
(115, 82, 19, 'ca loc bong test cake', 2000, 1, 2000, 'http://localhost:8080/Phuthotourt/storage/products/Eev30HthEEltMXcgcgfzhnPqMMiYwxqfbIjdXCP8.jpg', '2026-01-22 04:51:31', '2026-01-22 04:51:31'),
(116, 83, 19, 'ca loc bong test cake', 2000, 1, 2000, 'http://localhost:8080/Phuthotourt/storage/products/Eev30HthEEltMXcgcgfzhnPqMMiYwxqfbIjdXCP8.jpg', '2026-01-22 04:57:28', '2026-01-22 04:57:28'),
(117, 84, 19, 'ca loc bong test cake', 2000, 1, 2000, 'http://localhost:8080/Phuthotourt/storage/products/Eev30HthEEltMXcgcgfzhnPqMMiYwxqfbIjdXCP8.jpg', '2026-01-22 05:01:51', '2026-01-22 05:01:51'),
(118, 85, 19, 'ca loc bong test cake', 2000, 1, 2000, 'http://localhost:8080/Phuthotourt/storage/products/Eev30HthEEltMXcgcgfzhnPqMMiYwxqfbIjdXCP8.jpg', '2026-02-11 04:11:49', '2026-02-11 04:11:49'),
(119, 86, 18, 'ca loc bong test', 1000, 4, 4000, 'http://localhost:8080/Tohfish/storage/products/TswGdAGDrJUh2fwFOvxQBsFQ0YSaIBqtudVC49kw.jpg', '2026-02-11 06:38:40', '2026-02-11 06:38:40'),
(120, 86, 19, 'ca loc bong test cake', 2000, 5, 10000, 'http://localhost:8080/Tohfish/storage/products/Eev30HthEEltMXcgcgfzhnPqMMiYwxqfbIjdXCP8.jpg', '2026-02-11 06:38:40', '2026-02-11 06:38:40'),
(121, 87, 19, 'ca loc bong test cake', 2000, 3, 6000, 'http://localhost:8080/Tohfish/storage/products/Eev30HthEEltMXcgcgfzhnPqMMiYwxqfbIjdXCP8.jpg', '2026-02-25 03:57:52', '2026-02-25 03:57:52'),
(122, 87, 18, 'ca loc bong test', 1000, 1, 1000, 'http://localhost:8080/Tohfish/storage/products/TswGdAGDrJUh2fwFOvxQBsFQ0YSaIBqtudVC49kw.jpg', '2026-02-25 03:57:52', '2026-02-25 03:57:52'),
(123, 87, 8, 'ca loc bong 234', 20000, 1, 20000, 'http://localhost:8080/Tohfish/storage/products/mEqRLVlnXHRHIYYJ4h34P55pb56oAzlSFYTKWMVM.jpg', '2026-02-25 03:57:52', '2026-02-25 03:57:52'),
(124, 88, 14, 'san pham khuyen mai', 20000, 1, 20000, 'http://localhost:8080/Tohfish/storage/products/TC4jgWLiJAZ3y6uQHgSHkMgpDhhliD5WICH0VQNi.jpg', '2026-02-25 03:58:05', '2026-02-25 03:58:05'),
(125, 89, 12, 'rau vi', 20000, 1, 20000, 'http://localhost:8080/Tohfish/storage/products/g7GyL1exhdFAS2VlcXDz8OaGcdv1yxi8Y2n6wBWF.jpg', '2026-02-25 04:07:45', '2026-02-25 04:07:45'),
(126, 90, 9, 'ca loc bong b32', 20000, 1, 20000, 'http://localhost:8080/Tohfish/storage/products/6cMDz67wahYNNHT2cfznPlRy7dOVatMd2Gmh88Bx.jpg', '2026-02-25 04:08:12', '2026-02-25 04:08:12');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `parents`
--

CREATE TABLE `parents` (
  `parent_id` int(10) UNSIGNED NOT NULL,
  `parent_name` varchar(255) NOT NULL,
  `parent_url` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `password_resets`
--

CREATE TABLE `password_resets` (
  `email` varchar(255) NOT NULL,
  `token` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `payment_transactions`
--

CREATE TABLE `payment_transactions` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `order_id` bigint(20) UNSIGNED NOT NULL,
  `transaction_id` varchar(255) DEFAULT NULL,
  `payment_method` varchar(255) NOT NULL,
  `amount` bigint(20) UNSIGNED NOT NULL,
  `status` varchar(255) NOT NULL DEFAULT 'pending',
  `payment_data` text DEFAULT NULL,
  `paid_at` timestamp NULL DEFAULT NULL,
  `momo_request_id` varchar(255) DEFAULT NULL,
  `momo_trans_id` varchar(255) DEFAULT NULL,
  `momo_response_data` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Đang đổ dữ liệu cho bảng `payment_transactions`
--

INSERT INTO `payment_transactions` (`id`, `order_id`, `transaction_id`, `payment_method`, `amount`, `status`, `payment_data`, `paid_at`, `momo_request_id`, `momo_trans_id`, `momo_response_data`, `created_at`, `updated_at`) VALUES
(18, 38, NULL, 'bank', 2000, 'expired', NULL, NULL, NULL, NULL, NULL, '2026-01-18 07:21:03', '2026-01-18 07:21:03'),
(19, 39, NULL, 'bank', 2000, 'expired', NULL, NULL, NULL, NULL, NULL, '2026-01-18 07:22:48', '2026-01-18 07:22:48'),
(20, 40, NULL, 'bank', 2000, 'expired', NULL, NULL, NULL, NULL, NULL, '2026-01-18 07:23:14', '2026-01-18 07:23:14'),
(21, 41, NULL, 'bank', 2000, 'expired', NULL, NULL, NULL, NULL, NULL, '2026-01-18 07:25:45', '2026-01-18 07:25:45'),
(22, 42, NULL, 'bank', 2000, 'expired', NULL, NULL, NULL, NULL, NULL, '2026-01-18 07:26:17', '2026-01-18 07:26:17'),
(23, 43, NULL, 'bank', 2000, 'expired', NULL, NULL, NULL, NULL, NULL, '2026-01-18 07:33:57', '2026-01-18 07:33:57'),
(24, 44, NULL, 'bank', 2000, 'expired', NULL, NULL, NULL, NULL, NULL, '2026-01-18 07:37:37', '2026-01-18 07:37:37'),
(25, 45, NULL, 'bank', 2000, 'expired', NULL, NULL, NULL, NULL, NULL, '2026-01-18 07:38:07', '2026-01-18 07:38:07'),
(26, 46, NULL, 'bank', 2000, 'expired', NULL, NULL, NULL, NULL, NULL, '2026-01-18 07:40:36', '2026-01-18 07:40:36'),
(27, 47, NULL, 'bank', 2000, 'expired', NULL, NULL, NULL, NULL, NULL, '2026-01-18 07:43:02', '2026-01-18 07:43:02'),
(28, 48, NULL, 'bank', 2000, 'expired', NULL, NULL, NULL, NULL, NULL, '2026-01-18 07:47:23', '2026-01-18 07:47:23'),
(29, 49, NULL, 'bank', 2000, 'expired', NULL, NULL, NULL, NULL, NULL, '2026-01-18 07:50:31', '2026-01-18 07:50:31'),
(30, 50, NULL, 'bank', 2000, 'completed', NULL, '2026-01-18 08:00:03', NULL, NULL, NULL, '2026-01-18 07:58:20', '2026-01-18 08:00:03'),
(31, 51, NULL, 'bank', 2000, 'expired', NULL, NULL, NULL, NULL, NULL, '2026-01-18 08:03:06', '2026-01-18 08:03:06'),
(32, 52, NULL, 'momo', 2000, 'expired', NULL, NULL, NULL, NULL, NULL, '2026-01-19 02:47:59', '2026-01-19 02:47:59'),
(33, 53, NULL, 'momo', 2000, 'expired', NULL, NULL, NULL, NULL, NULL, '2026-01-19 02:48:28', '2026-01-19 02:48:28'),
(34, 54, NULL, 'bank', 2000, 'expired', NULL, NULL, NULL, NULL, NULL, '2026-01-19 02:48:51', '2026-01-19 02:48:51'),
(35, 56, NULL, 'momo', 2000, 'expired', NULL, NULL, NULL, NULL, NULL, '2026-01-19 02:49:45', '2026-01-19 02:49:45'),
(36, 57, NULL, 'bank', 2000, 'expired', NULL, NULL, NULL, NULL, NULL, '2026-01-19 02:50:35', '2026-01-19 02:50:35'),
(37, 58, NULL, 'bank', 2000, 'expired', NULL, NULL, NULL, NULL, NULL, '2026-01-19 02:52:25', '2026-01-19 02:52:25'),
(38, 59, NULL, 'momo', 2000, 'expired', NULL, NULL, NULL, NULL, NULL, '2026-01-19 02:53:21', '2026-01-19 02:53:21'),
(39, 60, NULL, 'bank', 2000, 'expired', NULL, NULL, NULL, NULL, NULL, '2026-01-19 02:54:05', '2026-01-19 02:54:05'),
(40, 61, NULL, 'bank', 2000, 'expired', NULL, NULL, NULL, NULL, NULL, '2026-01-19 02:55:12', '2026-01-19 02:55:12'),
(41, 62, NULL, 'momo', 2000, 'expired', NULL, NULL, NULL, NULL, NULL, '2026-01-19 02:55:35', '2026-01-19 02:55:35'),
(42, 63, NULL, 'momo', 2000, 'expired', NULL, NULL, NULL, NULL, NULL, '2026-01-19 02:56:23', '2026-01-19 02:56:23'),
(43, 64, NULL, 'momo', 2000, 'expired', NULL, NULL, NULL, NULL, NULL, '2026-01-19 02:57:52', '2026-01-19 02:57:52'),
(44, 64, NULL, 'momo', 2000, 'pending', NULL, NULL, NULL, NULL, NULL, '2026-01-19 02:57:54', '2026-01-19 02:57:54'),
(45, 65, NULL, 'momo', 2000, 'expired', NULL, NULL, NULL, NULL, NULL, '2026-01-19 02:58:19', '2026-01-19 02:58:19'),
(46, 65, NULL, 'momo', 2000, 'pending', NULL, NULL, NULL, NULL, NULL, '2026-01-19 02:58:21', '2026-01-19 02:58:21'),
(47, 66, NULL, 'momo', 2000, 'expired', NULL, NULL, NULL, NULL, NULL, '2026-01-19 03:10:22', '2026-01-19 03:10:22'),
(48, 66, NULL, 'momo', 2000, 'pending', NULL, NULL, NULL, NULL, NULL, '2026-01-19 03:10:24', '2026-01-19 03:10:24'),
(49, 67, NULL, 'momo', 2000, 'expired', NULL, NULL, NULL, NULL, NULL, '2026-01-19 03:12:18', '2026-01-19 03:12:18'),
(50, 68, NULL, 'momo', 2000, 'expired', NULL, NULL, NULL, NULL, NULL, '2026-01-19 03:15:26', '2026-01-19 03:15:27'),
(51, 68, NULL, 'momo', 2000, 'pending', NULL, NULL, NULL, NULL, NULL, '2026-01-19 03:15:29', '2026-01-19 03:15:29'),
(52, 69, NULL, 'momo', 2000, 'expired', NULL, NULL, NULL, NULL, NULL, '2026-01-19 03:17:11', '2026-01-19 03:17:11'),
(53, 69, NULL, 'momo', 2000, 'pending', NULL, NULL, NULL, NULL, NULL, '2026-01-19 03:17:13', '2026-01-19 03:17:13'),
(54, 70, NULL, 'momo', 2000, 'expired', NULL, NULL, NULL, NULL, NULL, '2026-01-19 03:23:59', '2026-01-19 03:23:59'),
(55, 70, NULL, 'momo', 2000, 'pending', NULL, NULL, NULL, NULL, NULL, '2026-01-19 03:24:01', '2026-01-19 03:24:01'),
(56, 71, NULL, 'momo', 2000, 'expired', NULL, NULL, NULL, NULL, NULL, '2026-01-19 03:28:56', '2026-01-19 03:28:56'),
(57, 71, NULL, 'momo', 2000, 'pending', NULL, NULL, NULL, NULL, NULL, '2026-01-19 03:28:58', '2026-01-19 03:28:58'),
(58, 72, NULL, 'momo', 2000, 'expired', NULL, NULL, NULL, NULL, NULL, '2026-01-19 03:33:20', '2026-01-19 03:33:20'),
(59, 72, NULL, 'momo', 2000, 'pending', NULL, NULL, NULL, NULL, NULL, '2026-01-19 03:33:22', '2026-01-19 03:33:22'),
(60, 73, NULL, 'momo', 2000, 'expired', NULL, NULL, NULL, NULL, NULL, '2026-01-19 03:38:41', '2026-01-19 03:38:42'),
(61, 73, NULL, 'momo', 2000, 'completed', NULL, '2026-02-25 04:07:14', '1768793924_696da744130c7_1606', NULL, NULL, '2026-01-19 03:38:44', '2026-02-25 04:07:14'),
(62, 74, NULL, 'momo', 204000, 'expired', NULL, NULL, NULL, NULL, NULL, '2026-01-19 04:20:06', '2026-01-19 04:20:06'),
(63, 74, NULL, 'momo', 204000, 'pending', NULL, NULL, '1768796408_696db0f8defc2_9835', NULL, NULL, '2026-01-19 04:20:08', '2026-01-19 04:20:09'),
(64, 75, NULL, 'momo', 2000, 'expired', NULL, NULL, NULL, NULL, NULL, '2026-01-19 04:21:47', '2026-01-19 04:21:47'),
(65, 75, NULL, 'momo', 2000, 'pending', NULL, NULL, '1768796573_696db19d0f7b3_2771', NULL, NULL, '2026-01-19 04:21:50', '2026-01-19 04:22:53'),
(66, 78, NULL, 'momo', 2000, 'expired', NULL, NULL, NULL, NULL, NULL, '2026-01-22 04:21:20', '2026-01-22 04:21:21'),
(67, 78, NULL, 'momo', 2000, 'pending', NULL, NULL, '1769055715_6971a5e34bba2_6847', NULL, NULL, '2026-01-22 04:21:23', '2026-01-22 04:21:55'),
(68, 79, NULL, 'momo', 2000, 'expired', NULL, NULL, '1769055981_6971a6ed992c0_2177', NULL, NULL, '2026-01-22 04:26:21', '2026-01-22 04:26:22'),
(69, 80, NULL, 'bank', 2000, 'expired', NULL, NULL, NULL, NULL, NULL, '2026-01-22 04:31:04', '2026-01-22 04:31:04'),
(70, 81, NULL, 'momo', 2000, 'expired', NULL, NULL, '1769056403_6971a8937f8c4_2307', NULL, NULL, '2026-01-22 04:33:23', '2026-01-22 04:33:24'),
(71, 82, NULL, 'momo', 2000, 'expired', NULL, NULL, '1769057491_6971acd3a38f8_6909', NULL, NULL, '2026-01-22 04:51:31', '2026-01-22 04:51:34'),
(72, 85, NULL, 'momo', 2000, 'expired', NULL, NULL, '1770783109_698c01853f465_8992', NULL, NULL, '2026-02-11 04:11:49', '2026-02-11 04:11:49'),
(73, 86, NULL, 'bank', 14000, 'expired', NULL, NULL, NULL, NULL, NULL, '2026-02-11 06:38:40', '2026-02-11 06:38:40');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `personal_access_tokens`
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
-- Cấu trúc bảng cho bảng `posts`
--

CREATE TABLE `posts` (
  `post_id` int(10) UNSIGNED NOT NULL,
  `role` tinyint(1) NOT NULL,
  `content` varchar(255) NOT NULL,
  `description` varchar(255) DEFAULT NULL,
  `view` int(11) NOT NULL,
  `status` tinyint(1) DEFAULT 0,
  `image_url` varchar(255) DEFAULT NULL,
  `sub_images` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`sub_images`)),
  `role_parent_id` int(10) UNSIGNED DEFAULT NULL,
  `position_parent_id` int(10) UNSIGNED DEFAULT NULL,
  `location_parent_id` int(10) UNSIGNED DEFAULT NULL,
  `blog_type` varchar(50) DEFAULT NULL COMMENT 'Loại bài viết blog',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Đang đổ dữ liệu cho bảng `posts`
--

INSERT INTO `posts` (`post_id`, `role`, `content`, `description`, `view`, `status`, `image_url`, `sub_images`, `role_parent_id`, `position_parent_id`, `location_parent_id`, `blog_type`, `created_at`, `updated_at`) VALUES
(1, 0, 'Cá lóc nấu gì ngon? Gợi ý các món ăn hấp dẫn từ cá lóc bông mới nhất', NULL, 6, 0, 'posts/bcEuZ384QtpuaNZqKUj4qP26ihqodfjVEXjQwg15.jpg', '[\"posts\\/xPuMHo6LnbfctSQ3s0bEtfbE5RYkVplaQMs82rs9.jpg\"]', NULL, NULL, NULL, 'congthuc', '2026-01-11 08:42:50', '2026-02-11 04:18:35'),
(2, 1, 'Cá lóc nướng trui: Món ăn dân dã, thơm ngon từ cá lóc bông', NULL, 13, 1, 'posts/c9hB8PQr5EOqSxtSo5NYEqBmYFVXaIpsr6S91qzi.jpg', '[\"posts\\/dYDMYYISGTjTORX6GZTYZCatIFj9lNQKvAaGYmQC.jpg\"]', NULL, NULL, NULL, 'congthuc', '2026-01-11 08:44:44', '2026-02-11 04:18:41');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `posts_detail`
--

CREATE TABLE `posts_detail` (
  `posts_detail_id` int(10) UNSIGNED NOT NULL,
  `content` text NOT NULL,
  `post_url` varchar(255) NOT NULL,
  `post_id` int(10) UNSIGNED DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Đang đổ dữ liệu cho bảng `posts_detail`
--

INSERT INTO `posts_detail` (`posts_detail_id`, `content`, `post_url`, `post_id`, `created_at`, `updated_at`) VALUES
(1, '<p>&aacute; l&oacute;c l&agrave; một loại thực phẩm quen thuộc v&agrave; gi&agrave;u dinh dưỡng trong ẩm thực Việt Nam. Với thịt c&aacute; chắc, ngọt v&agrave; &iacute;t xương, c&aacute; l&oacute;c c&oacute; thể chế biến th&agrave;nh rất nhiều m&oacute;n ăn ngon v&agrave; hấp dẫn. Tuy nhi&ecirc;n, C&aacute; l&oacute;c nấu g&igrave; ngon? Đặc biệt l&agrave; với loại c&aacute; l&oacute;c b&ocirc;ng. B&agrave;i viết n&agrave;y của&nbsp;<a href=\"https://tohfish.com/\">TOH Fish</a>&nbsp;sẽ chia sẻ những b&iacute; quyết gi&uacute;p bạn chọn c&aacute; l&oacute;c b&ocirc;ng tươi ngon v&agrave; gợi &yacute; những m&oacute;n ăn hấp dẫn từ c&aacute; l&oacute;c b&ocirc;ng, c&ugrave;ng những lưu &yacute; quan trọng khi chế biến loại c&aacute; n&agrave;y.</p>\r\n\r\n<h2><strong>1. C&aacute;ch chọn c&aacute; l&oacute;c b&ocirc;ng tươi ngon</strong></h2>\r\n\r\n<p>Để c&oacute; m&oacute;n c&aacute; l&oacute;c ngon, bước đầu ti&ecirc;n v&agrave; quan trọng nhất l&agrave; chọn được những con c&aacute; tươi ngon. C&aacute; l&oacute;c tươi sẽ ảnh hưởng trực tiếp đến hương vị v&agrave; chất lượng của m&oacute;n ăn. Dưới đ&acirc;y l&agrave; một số mẹo gi&uacute;p bạn lựa chọn c&aacute; l&oacute;c b&ocirc;ng tươi ngon:</p>\r\n\r\n<p><img alt=\"Ca-loc-bong-tuoi-ngon-den-tu-nha-TOH-fish\" src=\"https://file.hstatic.net/1000105320/file/img_3663copylogo_026782db583b434f8f6117b52c1bd9a4.jpg\" /></p>\r\n\r\n<p><em>C&aacute; l&oacute;c b&ocirc;ng tươi ngon đến từ nh&agrave; TOH fish</em></p>\r\n\r\n<h3><strong>1.1. Quan s&aacute;t h&igrave;nh d&aacute;ng v&agrave; m&agrave;u sắc c&aacute;:</strong></h3>\r\n\r\n<p><strong>H&igrave;nh d&aacute;ng:&nbsp;</strong>C&aacute; l&oacute;c tươi c&oacute; th&acirc;n h&igrave;nh thon d&agrave;i, chắc thịt, kh&ocirc;ng bị mềm nhũn hay biến dạng. Phần bụng c&aacute; kh&ocirc;ng bị ph&igrave;nh to.</p>\r\n\r\n<p><strong>M&agrave;u sắc:&nbsp;</strong>C&aacute; l&oacute;c tươi c&oacute; m&agrave;u đen hoặc x&aacute;m đen ở lưng, bụng m&agrave;u trắng hoặc v&agrave;ng nhạt. M&agrave;u sắc c&aacute; tươi tắn, kh&ocirc;ng bị t&aacute;i nhợt. Đặc biệt, n&ecirc;n chọn những con c&aacute; c&oacute; m&agrave;u sắc tươi s&aacute;ng v&agrave; c&oacute; độ b&oacute;ng nhẹ.</p>\r\n\r\n<h3><strong>1.2. Kiểm tra mắt, v&acirc;y v&agrave; mang c&aacute;:</strong></h3>\r\n\r\n<p><strong>Mắt c&aacute;:&nbsp;</strong>Mắt c&aacute; l&oacute;c tươi trong veo, kh&ocirc;ng bị đục hay l&otilde;m v&agrave;o.</p>\r\n\r\n<p><strong>V&acirc;y c&aacute;:</strong>&nbsp;V&acirc;y c&aacute; nguy&ecirc;n vẹn, kh&ocirc;ng bị r&aacute;ch hay xơ x&aacute;c.</p>\r\n\r\n<p><strong>Mang c&aacute;:&nbsp;</strong>Mang c&aacute; c&oacute; m&agrave;u đỏ tươi, kh&ocirc;ng bị th&acirc;m đen hay c&oacute; m&ugrave;i h&ocirc;i. Đ&acirc;y l&agrave; dấu hiệu quan trọng nhất để nhận biết c&aacute; c&ograve;n tươi hay kh&ocirc;ng.</p>\r\n\r\n<h2><strong>2. C&aacute;c m&oacute;n ngon từ c&aacute; l&oacute;c</strong></h2>\r\n\r\n<p>C&aacute; l&oacute;c nấu g&igrave; ngon? Sau đ&acirc;y l&agrave; c&aacute;c m&oacute;n ăn ngon v&agrave; bổ dưỡng được chế biến từ c&aacute; l&oacute;c, ph&ugrave; hợp với khẩu vị của nhiều người. Dưới đ&acirc;y l&agrave; một v&agrave;i gợi &yacute;:</p>\r\n\r\n<h3><strong>2.1 C&aacute; l&oacute;c kho tộ</strong></h3>\r\n\r\n<p>C&aacute; l&oacute;c kho tộ l&agrave; m&oacute;n ăn d&acirc;n d&atilde;, đậm đ&agrave; hương vị miền qu&ecirc;. C&aacute; l&oacute;c được kho trong tộ đất c&ugrave;ng với nước mắm, đường, ti&ecirc;u v&agrave; c&aacute;c gia vị kh&aacute;c, tạo n&ecirc;n một m&oacute;n ăn c&oacute; m&agrave;u sắc hấp dẫn, vị mặn ngọt h&agrave;i h&ograve;a, thịt c&aacute; mềm thơm, ăn k&egrave;m với cơm n&oacute;ng th&igrave; thật tuyệt vời. Đ&acirc;y l&agrave; m&oacute;n ăn quen thuộc trong bữa cơm gia đ&igrave;nh Việt Nam.</p>\r\n\r\n<p><img alt=\"ca-loc-kho-to-thom-ngon\" src=\"https://file.hstatic.net/1000105320/file/thiet_ke_chua_co_ten__17__d6a1ea9bca384f019a75746e57c7ee8b.jpg\" /></p>\r\n\r\n<p><em>C&aacute; l&oacute;c kho tộ thơm ngon</em></p>\r\n\r\n<p><strong>Xem ngay:</strong>&nbsp;<a href=\"https://tohfish.com/blogs/toh-blog/cach-nau-mon-ca-loc-kho-to\">C&aacute;ch nấu c&aacute; l&oacute;c kho tộ thơm ngon&nbsp;</a></p>\r\n\r\n<h3><strong>2.2 Canh chua c&aacute; l&oacute;c</strong></h3>', 'http://localhost:8080/Phuthotourt/chi-tiet-bai-viet/1', 1, '2026-01-11 08:42:50', '2026-01-11 08:42:50'),
(2, '<p>C&aacute; l&oacute;c nướng trui, m&oacute;n ăn d&acirc;n d&atilde; thấm đẫm hồn qu&ecirc; Việt Nam, đ&acirc;y c&ograve;n l&agrave; cả một n&eacute;t văn h&oacute;a ẩm thực độc đ&aacute;o. Hương vị ngọt thơm của thịt c&aacute; l&oacute;c b&ocirc;ng h&ograve;a quyện với m&ugrave;i kh&oacute;i rơm nồng n&agrave;n, c&ugrave;ng với ch&eacute;n nước chấm đậm đ&agrave;, tất cả tạo n&ecirc;n một trải nghiệm ẩm thực kh&oacute; qu&ecirc;n. TOH Fish sẽ chia sẻ chi tiết c&aacute;ch l&agrave;m m&oacute;n c&aacute; l&oacute;c nướng trui thơm ngon chuẩn vị, từ kh&acirc;u chọn c&aacute; tươi ngon, sơ chế đ&uacute;ng c&aacute;ch, đến b&iacute; quyết nướng c&aacute; kh&ocirc;ng bị kh&ocirc; v&agrave; c&aacute;ch pha nước chấm ngon b&aacute; ch&aacute;y.</p>\r\n\r\n<h2><strong>1. Giới thiệu sơ về c&aacute; l&oacute;c nướng trui</strong></h2>\r\n\r\n<p>C&aacute; l&oacute;c nướng trui, m&oacute;n ăn d&acirc;n d&atilde; đặc trưng của v&ugrave;ng đồng bằng s&ocirc;ng Cửu Long, từ l&acirc;u đ&atilde; trở th&agrave;nh một phần kh&ocirc;ng thể thiếu trong văn h&oacute;a ẩm thực Việt Nam.</p>\r\n\r\n<p>M&oacute;n ăn n&agrave;y kh&ocirc;ng chỉ chinh phục thực kh&aacute;ch bởi hương vị mộc mạc, đậm đ&agrave; m&agrave; c&ograve;n bởi sự kết hợp h&agrave;i h&ograve;a giữa vị ngọt tự nhi&ecirc;n của c&aacute; l&oacute;c, m&ugrave;i thơm của rơm rạ v&agrave; vị chua cay của nước chấm. H&atilde;y c&ugrave;ng kh&aacute;m ph&aacute; b&iacute; quyết l&agrave;m m&oacute;n c&aacute; l&oacute;c nướng trui thơm ngon chuẩn vị ngay sau đ&acirc;y nh&eacute;!</p>\r\n\r\n<p><img alt=\"Mon-ca-loc-nuong-trui-dac-trung-cua-mien-Tay-song-nuoc\" src=\"https://file.hstatic.net/1000105320/file/image2_3e5a1688ed274a6a86ad2bb915c9f180.jpg\" /></p>\r\n\r\n<p><em>M&oacute;n c&aacute; l&oacute;c nướng trui đặc trưng của miền T&acirc;y s&ocirc;ng nước</em></p>\r\n\r\n<h2><strong>2. Nguy&ecirc;n liệu cần chuẩn bị</strong></h2>\r\n\r\n<h3><strong>2.1 C&aacute; l&oacute;c b&ocirc;ng tươi ngon</strong></h3>\r\n\r\n<p>C&oacute; nhiều loại c&aacute; l&oacute;c kh&aacute;c nhau, tuy nhi&ecirc;n, để m&oacute;n c&aacute; l&oacute;c nướng trui thơm ngon hơn bạn c&oacute; thể chọn loại c&aacute; l&oacute;c b&ocirc;ng. Bạn n&ecirc;n chọn những con c&aacute; l&oacute;c b&ocirc;ng c&ograve;n sống để đảm bảo độ tươi ngon.&nbsp;</p>\r\n\r\n<h3><strong>2.2 Gia vị</strong></h3>\r\n\r\n<p>- H&agrave;nh t&iacute;m: 2-3 củ</p>\r\n\r\n<p>- Tỏi: 1 củ&nbsp;</p>\r\n\r\n<p>- Ớt tươi: 2-3 quả (t&ugrave;y khẩu vị)&nbsp;</p>\r\n\r\n<p>- Nước mắm, đường, ti&ecirc;u&nbsp;</p>\r\n\r\n<p>- Sả: 2-3 c&acirc;y&nbsp;</p>\r\n\r\n<p>- Chanh: 1 quả&nbsp;</p>\r\n\r\n<h3><strong>2.3 C&aacute;c loại rau thơm</strong></h3>\r\n\r\n<p>Tuỳ v&agrave;o sở th&iacute;ch bạn c&oacute; thể chuẩn bị th&ecirc;m c&aacute;c loại nguy&ecirc;n liệu như l&aacute; chanh, l&aacute; lốt, rau thơm c&aacute;c loại (rau diếp c&aacute;, rau h&uacute;ng, rau thơm),...</p>\r\n\r\n<h3><strong>2.4 Nguy&ecirc;n liệu nướng trui</strong></h3>', 'http://localhost:8080/Phuthotourt/chi-tiet-bai-viet/2', 2, '2026-01-11 08:44:44', '2026-01-11 08:44:44');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `sliders`
--

CREATE TABLE `sliders` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `title` varchar(255) NOT NULL,
  `description` text DEFAULT NULL,
  `image_url` varchar(255) DEFAULT NULL,
  `link` varchar(255) DEFAULT NULL,
  `link_2` varchar(255) DEFAULT NULL,
  `button_text` varchar(255) DEFAULT NULL,
  `button_text_2` varchar(255) DEFAULT NULL,
  `type` enum('home','promotion') NOT NULL DEFAULT 'home',
  `order` int(11) NOT NULL DEFAULT 0,
  `status` tinyint(1) NOT NULL DEFAULT 1,
  `background_color` varchar(255) DEFAULT NULL,
  `icon` varchar(255) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Đang đổ dữ liệu cho bảng `sliders`
--

INSERT INTO `sliders` (`id`, `title`, `description`, `image_url`, `link`, `link_2`, `button_text`, `button_text_2`, `type`, `order`, `status`, `background_color`, `icon`, `created_at`, `updated_at`) VALUES
(1, 'sale cuoi thang', 'sale cuoi thang cac san pham ca loc', 'sliders/1768205960_C3DBBNfdTM.jpeg', 'http://localhost:8080/Phuthotourt/gioi-thieu', NULL, 'Về Chúng Tôi', NULL, 'home', 4, 1, NULL, NULL, '2026-01-12 08:19:20', '2026-01-12 08:44:36'),
(2, 'sale cuoi ky 😊', 'sale cuoi ky cac san pham ca', NULL, 'http://localhost:8080/Phuthotourt/san-pham-so-che', NULL, 'Mua Ngay', NULL, 'home', 2, 1, '#0fb340', NULL, '2026-01-12 08:45:16', '2026-01-12 10:19:54');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `users`
--

CREATE TABLE `users` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `phone` varchar(20) DEFAULT NULL,
  `address` varchar(255) DEFAULT NULL,
  `city` varchar(100) DEFAULT NULL,
  `district` varchar(100) DEFAULT NULL,
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `password` varchar(255) NOT NULL,
  `remember_token` varchar(100) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Đang đổ dữ liệu cho bảng `users`
--

INSERT INTO `users` (`id`, `name`, `email`, `phone`, `address`, `city`, `district`, `email_verified_at`, `password`, `remember_token`, `created_at`, `updated_at`) VALUES
(1, 'Võ Đăng Kỷ', 'vodangky.dev@gmail.com', '0972471301', 'duong so 51, phuong 14', 'TP. Hồ Chí Minh', 'go vap', NULL, '$2y$10$wzL.IdILOOE41fugQv.sIeJ5mEOxq5fx2iV1YcEML9t07OdT7eXZC', NULL, '2026-01-07 06:58:28', '2026-01-16 04:13:17'),
(2, 'vodangky2', 'vodangky2@gmail.com', NULL, NULL, NULL, NULL, NULL, '$2y$10$szfiGq68mTfFSru9kFBOsOLDewV2H3rK9/vBZb0OlBXZP6r19z5mW', NULL, '2026-01-07 08:02:46', '2026-01-07 08:02:46'),
(3, 'vodangky.it@gmail.com', 'vodangky.it@gmail.com', NULL, NULL, NULL, NULL, NULL, '$2y$10$LEthkUQGbFcrriiMi/sLeOXp4.C2DR74WQIQRf/hscYda2h4TuxKO', NULL, '2026-01-14 04:04:30', '2026-01-14 04:04:30');

--
-- Chỉ mục cho các bảng đã đổ
--

--
-- Chỉ mục cho bảng `cart_items`
--
ALTER TABLE `cart_items`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `cart_items_user_id_product_id_unique` (`user_id`,`product_id`),
  ADD KEY `cart_items_user_id_index` (`user_id`);

--
-- Chỉ mục cho bảng `categories`
--
ALTER TABLE `categories`
  ADD PRIMARY KEY (`category_id`),
  ADD KEY `categories_parent_id_foreign` (`parent_id`);

--
-- Chỉ mục cho bảng `contents`
--
ALTER TABLE `contents`
  ADD PRIMARY KEY (`content_id`);

--
-- Chỉ mục cho bảng `cvs`
--
ALTER TABLE `cvs`
  ADD PRIMARY KEY (`cvs_id`),
  ADD KEY `cvs_job_id_foreign` (`job_id`);

--
-- Chỉ mục cho bảng `documents`
--
ALTER TABLE `documents`
  ADD PRIMARY KEY (`document_id`);

--
-- Chỉ mục cho bảng `failed_jobs`
--
ALTER TABLE `failed_jobs`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `failed_jobs_uuid_unique` (`uuid`);

--
-- Chỉ mục cho bảng `favorites`
--
ALTER TABLE `favorites`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `favorites_user_id_product_id_unique` (`user_id`,`product_id`),
  ADD KEY `favorites_product_id_index` (`product_id`);

--
-- Chỉ mục cho bảng `images`
--
ALTER TABLE `images`
  ADD PRIMARY KEY (`images_id`);

--
-- Chỉ mục cho bảng `jobs`
--
ALTER TABLE `jobs`
  ADD PRIMARY KEY (`job_id`),
  ADD KEY `jobs_role_category_id_foreign` (`role_category_id`),
  ADD KEY `jobs_position_category_id_foreign` (`position_category_id`),
  ADD KEY `jobs_location_category_id_foreign` (`location_category_id`);

--
-- Chỉ mục cho bảng `job_details`
--
ALTER TABLE `job_details`
  ADD PRIMARY KEY (`job_details_id`),
  ADD KEY `job_details_job_id_foreign` (`job_id`);

--
-- Chỉ mục cho bảng `job_positions`
--
ALTER TABLE `job_positions`
  ADD PRIMARY KEY (`id`);

--
-- Chỉ mục cho bảng `migrations`
--
ALTER TABLE `migrations`
  ADD PRIMARY KEY (`id`);

--
-- Chỉ mục cho bảng `orders`
--
ALTER TABLE `orders`
  ADD PRIMARY KEY (`id`),
  ADD KEY `orders_user_id_foreign` (`user_id`);

--
-- Chỉ mục cho bảng `order_items`
--
ALTER TABLE `order_items`
  ADD PRIMARY KEY (`id`),
  ADD KEY `order_items_order_id_foreign` (`order_id`);

--
-- Chỉ mục cho bảng `parents`
--
ALTER TABLE `parents`
  ADD PRIMARY KEY (`parent_id`);

--
-- Chỉ mục cho bảng `password_resets`
--
ALTER TABLE `password_resets`
  ADD PRIMARY KEY (`email`);

--
-- Chỉ mục cho bảng `payment_transactions`
--
ALTER TABLE `payment_transactions`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `payment_transactions_transaction_id_unique` (`transaction_id`),
  ADD KEY `payment_transactions_order_id_foreign` (`order_id`);

--
-- Chỉ mục cho bảng `personal_access_tokens`
--
ALTER TABLE `personal_access_tokens`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `personal_access_tokens_token_unique` (`token`),
  ADD KEY `personal_access_tokens_tokenable_type_tokenable_id_index` (`tokenable_type`,`tokenable_id`);

--
-- Chỉ mục cho bảng `posts`
--
ALTER TABLE `posts`
  ADD PRIMARY KEY (`post_id`),
  ADD KEY `posts_role_parent_id_foreign` (`role_parent_id`),
  ADD KEY `posts_position_parent_id_foreign` (`position_parent_id`),
  ADD KEY `posts_location_parent_id_foreign` (`location_parent_id`);

--
-- Chỉ mục cho bảng `posts_detail`
--
ALTER TABLE `posts_detail`
  ADD PRIMARY KEY (`posts_detail_id`),
  ADD KEY `posts_detail_post_id_foreign` (`post_id`);

--
-- Chỉ mục cho bảng `sliders`
--
ALTER TABLE `sliders`
  ADD PRIMARY KEY (`id`);

--
-- Chỉ mục cho bảng `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `users_email_unique` (`email`);

--
-- AUTO_INCREMENT cho các bảng đã đổ
--

--
-- AUTO_INCREMENT cho bảng `cart_items`
--
ALTER TABLE `cart_items`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=178;

--
-- AUTO_INCREMENT cho bảng `categories`
--
ALTER TABLE `categories`
  MODIFY `category_id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT cho bảng `contents`
--
ALTER TABLE `contents`
  MODIFY `content_id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT cho bảng `cvs`
--
ALTER TABLE `cvs`
  MODIFY `cvs_id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT cho bảng `documents`
--
ALTER TABLE `documents`
  MODIFY `document_id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT cho bảng `failed_jobs`
--
ALTER TABLE `failed_jobs`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT cho bảng `favorites`
--
ALTER TABLE `favorites`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=117;

--
-- AUTO_INCREMENT cho bảng `images`
--
ALTER TABLE `images`
  MODIFY `images_id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=20;

--
-- AUTO_INCREMENT cho bảng `jobs`
--
ALTER TABLE `jobs`
  MODIFY `job_id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT cho bảng `job_details`
--
ALTER TABLE `job_details`
  MODIFY `job_details_id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT cho bảng `job_positions`
--
ALTER TABLE `job_positions`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT cho bảng `migrations`
--
ALTER TABLE `migrations`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=48;

--
-- AUTO_INCREMENT cho bảng `orders`
--
ALTER TABLE `orders`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=91;

--
-- AUTO_INCREMENT cho bảng `order_items`
--
ALTER TABLE `order_items`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=127;

--
-- AUTO_INCREMENT cho bảng `parents`
--
ALTER TABLE `parents`
  MODIFY `parent_id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT cho bảng `payment_transactions`
--
ALTER TABLE `payment_transactions`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=74;

--
-- AUTO_INCREMENT cho bảng `personal_access_tokens`
--
ALTER TABLE `personal_access_tokens`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT cho bảng `posts`
--
ALTER TABLE `posts`
  MODIFY `post_id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT cho bảng `posts_detail`
--
ALTER TABLE `posts_detail`
  MODIFY `posts_detail_id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT cho bảng `sliders`
--
ALTER TABLE `sliders`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT cho bảng `users`
--
ALTER TABLE `users`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- Các ràng buộc cho các bảng đã đổ
--

--
-- Các ràng buộc cho bảng `cart_items`
--
ALTER TABLE `cart_items`
  ADD CONSTRAINT `cart_items_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Các ràng buộc cho bảng `categories`
--
ALTER TABLE `categories`
  ADD CONSTRAINT `categories_parent_id_foreign` FOREIGN KEY (`parent_id`) REFERENCES `parents` (`parent_id`) ON DELETE SET NULL;

--
-- Các ràng buộc cho bảng `cvs`
--
ALTER TABLE `cvs`
  ADD CONSTRAINT `cvs_job_id_foreign` FOREIGN KEY (`job_id`) REFERENCES `jobs` (`job_id`) ON DELETE SET NULL;

--
-- Các ràng buộc cho bảng `favorites`
--
ALTER TABLE `favorites`
  ADD CONSTRAINT `favorites_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Các ràng buộc cho bảng `jobs`
--
ALTER TABLE `jobs`
  ADD CONSTRAINT `jobs_location_category_id_foreign` FOREIGN KEY (`location_category_id`) REFERENCES `categories` (`category_id`) ON DELETE SET NULL,
  ADD CONSTRAINT `jobs_position_category_id_foreign` FOREIGN KEY (`position_category_id`) REFERENCES `categories` (`category_id`) ON DELETE SET NULL,
  ADD CONSTRAINT `jobs_role_category_id_foreign` FOREIGN KEY (`role_category_id`) REFERENCES `categories` (`category_id`) ON DELETE SET NULL;

--
-- Các ràng buộc cho bảng `job_details`
--
ALTER TABLE `job_details`
  ADD CONSTRAINT `job_details_job_id_foreign` FOREIGN KEY (`job_id`) REFERENCES `jobs` (`job_id`) ON DELETE SET NULL;

--
-- Các ràng buộc cho bảng `orders`
--
ALTER TABLE `orders`
  ADD CONSTRAINT `orders_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Các ràng buộc cho bảng `order_items`
--
ALTER TABLE `order_items`
  ADD CONSTRAINT `order_items_order_id_foreign` FOREIGN KEY (`order_id`) REFERENCES `orders` (`id`) ON DELETE CASCADE;

--
-- Các ràng buộc cho bảng `payment_transactions`
--
ALTER TABLE `payment_transactions`
  ADD CONSTRAINT `payment_transactions_order_id_foreign` FOREIGN KEY (`order_id`) REFERENCES `orders` (`id`) ON DELETE CASCADE;

--
-- Các ràng buộc cho bảng `posts`
--
ALTER TABLE `posts`
  ADD CONSTRAINT `posts_location_parent_id_foreign` FOREIGN KEY (`location_parent_id`) REFERENCES `parents` (`parent_id`) ON DELETE SET NULL,
  ADD CONSTRAINT `posts_position_parent_id_foreign` FOREIGN KEY (`position_parent_id`) REFERENCES `parents` (`parent_id`) ON DELETE SET NULL,
  ADD CONSTRAINT `posts_role_parent_id_foreign` FOREIGN KEY (`role_parent_id`) REFERENCES `parents` (`parent_id`) ON DELETE SET NULL;

--
-- Các ràng buộc cho bảng `posts_detail`
--
ALTER TABLE `posts_detail`
  ADD CONSTRAINT `posts_detail_post_id_foreign` FOREIGN KEY (`post_id`) REFERENCES `posts` (`post_id`) ON DELETE SET NULL;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
