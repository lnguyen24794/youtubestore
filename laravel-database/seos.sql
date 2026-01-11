-- phpMyAdmin SQL Dump
-- version 5.2.2
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Generation Time: Jan 11, 2026 at 05:04 AM
-- Server version: 10.11.15-MariaDB-cll-lve-log
-- PHP Version: 8.4.16

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `erosvnco_youtubestore`
--

-- --------------------------------------------------------

--
-- Table structure for table `seos`
--

CREATE TABLE `seos` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `title` varchar(191) DEFAULT NULL,
  `canonical` varchar(191) DEFAULT NULL,
  `description` varchar(191) DEFAULT NULL,
  `robots` varchar(191) DEFAULT NULL,
  `seoable_id` bigint(20) UNSIGNED NOT NULL,
  `seoable_type` varchar(191) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `seos`
--

INSERT INTO `seos` (`id`, `title`, `canonical`, `description`, `robots`, `seoable_id`, `seoable_type`, `created_at`, `updated_at`) VALUES
(7, 'Hướng dẫn tối ưu kênh Youtube mới nhất 2021', 'huong-dan-toi-uu-kenh-youtube-moi-nhat-2021', 'Bạn đang muốn tối ưu kênh youtube của mình để bật kiếm tiền? Để  đạt được hiệu quả cao nhất trong những video, hãy tham khảo ngay hướng dẫn tối ưu kênh Youtube mới nhất 2021 trong bài viết sa', NULL, 103, 'App\\Models\\Post', '2021-05-04 13:29:14', '2021-05-04 13:47:53'),
(8, 'Hướng dẫn kiếm tiền trên youtube mới nhất 2021', 'huong-dan-kiem-tien-tren-youtube-moi-nhat-2021', 'Kiếm hàng triệu đô mỗi tháng trên Youtube không phải là giấc mơ nếu bạn biết làm đúng cách. Youtubestore.vn sẽ “chỉ lối” cho bạn ngay trong bài viết này. Đừng bỏ qua nhé.', NULL, 104, 'App\\Models\\Post', '2021-05-04 13:47:02', '2021-05-04 13:47:02'),
(9, 'Hướng dẫn bật kiếm tiền trên Youtube nhanh chóng nhất', 'huong-dan-bat-kiem-tien-tren-youtube-nhanh-chong-nhat', 'Không chỉ dừng lại ở việc cung cấp tiện ích giải trí, Youtube còn có thể giúp bạn kiếm bội tiền khi xây dựng kênh hiệu quả. Nếu đã có kênh, làm sao để bật kiếm tiền trên Youtube chính xác nhấ', NULL, 105, 'App\\Models\\Post', '2021-05-04 14:30:35', '2021-05-04 14:30:35'),
(10, 'Điều kiện bật kiếm tiền trên youtube update 2021', 'dieu-kien-bat-kiem-tien-tren-youtube-update-2021', 'Bạn đang tìm hướng đi mới và kiếm tiền trên nền tảng Youtube là nơi bạn đang hướng đến? Để bắt đầu phải làm gì? Tham khảo ngay các chính sách về điều kiện bật kiếm tiền trên Youtube mới nhất', NULL, 106, 'App\\Models\\Post', '2021-05-05 12:39:17', '2021-05-05 12:40:21'),
(11, 'Tại sao mỗi doanh nghiệp kinh doanh nên sở hữu 1 kênh youtube', 'tai-sao-moi-doanh-nghiep-kinh-doanh-nen-so-huu-1-kenh-youtube', 'Sự cạnh tranh khốc liệt giữa các ngành hàng đòi hỏi doanh nghiệp phải thay đổi trong tư duy kinh doanh. Tại sao youtube lại trở thành \"phao\" của doanh nghiệp? 5 lý do đưa ra sau đây sẽ giúp b', NULL, 107, 'App\\Models\\Post', '2021-05-05 14:44:04', '2021-05-05 14:44:04'),
(12, 'Youtubestore.vn đơn vị mua bán - chuyển nhượng kênh youtube uy tín nhất tại Việt Nam', 'youtubestorevn-don-vi-mua-ban-chuyen-nhuong-kenh-youtube-uy-tin-nhat-tai-viet-nam', 'Bạn muốn nhanh chóng sở hữu một kênh Youtube có lượng sub lớn để kinh doanh hay kiếm tiền từ nền tảng này mà không phải xây từ đầu? Đơn vị nào chuyển nhượng kênh uy tín nhất? Youtubestore là', NULL, 108, 'App\\Models\\Post', '2021-05-05 15:04:12', '2021-05-06 21:25:59'),
(13, 'Cách tạo kênh youtube mới nhất 2021', 'cach-tao-kenh-youtube-moi-nhat-2021', 'Bạn đang muốn tạo kênh Youtube cho mình nhưng chưa biết bắt đầu từ đâu? Youtubestore.vn sẽ hướng dẫn bạn cách tạo kênh Youtube mới nhất từ A-Z ngay trong 3 phút đọc.  Cùng theo dõi ngay để xâ', NULL, 109, 'App\\Models\\Post', '2021-05-05 19:11:53', '2021-05-05 19:11:53'),
(14, 'Lợi ích từ việc xây dựng thương hiệu, sản phẩm bằng kênh Youtube', 'loi-ich-tu-viec-xay-dung-thuong-hieu-san-pham-bang-kenh-youtube', 'Xây dựng thương hiệu trên nhiều nền tảng mới lạ đang thu hút sự quan tâm của nhiều doanh nghiệp. Bạn đang muốn tìm kiếm cơ hội đưa tên tuổi sản phẩm đến gần hơn với người dùng trên kênh Youtu', NULL, 110, 'App\\Models\\Post', '2021-05-05 19:23:30', '2021-05-05 19:23:30'),
(15, 'Hướng dẫn cách nhận tiền từ Youtube 2021', 'huong-dan-cach-nhan-tien-tu-youtube-2021', 'Khi bạn đã là đối tác của Youtube và đã được phê duyệt cách kiếm tiền, việc của bạn bây giờ kiếm tiền và biết cách nhận tiền từ kênh này. Vậy làm thế nào để tiền từ Youtube \"chảy\" vào tài kho', NULL, 111, 'App\\Models\\Post', '2021-05-05 19:36:19', '2021-05-05 19:36:19'),
(16, 'Cách xây dựng và phát triển kênh Youtube hiệu quả', 'cach-xay-dung-va-phat-trien-kenh-youtube-hieu-qua', 'Để thương hiệu của bạn được nhiều người biết đến cũng như bán hàng nhanh chóng, hãy hướng đến hình thức quảng cáo bằng video, đặc biệt là Youtube. Vậy làm sao để xây dựng và phát triển kênh Y', NULL, 112, 'App\\Models\\Post', '2021-05-05 19:46:46', '2021-05-05 19:46:46'),
(17, 'Mua kênh Youtube uy tín - Bảng giá & danh sách kênh Youtube', 'mua-kenh-youtube-uy-tin-bang-gia-danh-sach-kenh-youtube', 'Bạn muốn mua kênh Youtube phục vụ cho mục đích kinh doanh, quảng cáo thương hiệu đến người dùng hay kiếm tiền từ những lượt view trên chính Youtube? Youtubestore.vn sẽ giúp bạn.', 'mua kênh youtube', 13, 'App\\Models\\Page', '2021-05-06 02:27:28', '2024-12-05 01:12:04'),
(19, 'Quy trình chuyển nhượng lại kênh youtube cho Youtubestore.vn', 'quy-trinh-chuyen-nhuong-lai-kenh-youtube-cho-youtubestorevn', 'Youtube Store có thu mua lại các kênh youtube có lượng sub từ 1000 đến 1 triệu sub với giá cực tốt và uy tín. Quy trình mua và chuyển nhượng lại kênh youtube cho Youtubestore.vn', NULL, 11, 'App\\Models\\Page', '2021-05-06 18:56:16', '2021-05-06 18:56:16'),
(20, 'Cách tạo kênh Youtube và bật kiếm tiền dễ hiểu nhất', 'cach-tao-kenh-youtube-va-bat-kiem-tien-de-hieu-nhat', 'Sử dụng Youtube không chỉ để giải trí, bạn hoàn toàn có thể kiếm được tiền từ kênh này một cách dễ dàng nếu biết cách. Youtubestore sẽ hướng dẫn bạn cách tạo kênh Youtube và bật kiếm tiền nha', NULL, 114, 'App\\Models\\Post', '2021-05-10 01:41:19', '2021-05-10 01:41:19'),
(21, NULL, NULL, 'Mua bán kênh Youtube đã trở thành nhu cầu thiết yếu của người nhiều trong việc xây dựng hình ảnh và kinh doanh. Vậy, có những lưu ý gì trước khi giao dịch mua bán trên nền tảng này?', NULL, 115, 'App\\Models\\Post', '2021-05-10 02:05:07', '2021-05-10 02:05:07'),
(22, 'Gợi ý 20 tưởng kinh doanh trước khi quyết định mua kênh Youtube', 'goi-y-20-tuong-kinh-doanh-truoc-khi-quyet-dinh-mua-kenh-youtube', 'Nếu biết cách triển khai những ý tưởng kinh doanh tuyệt đúng thời điểm sau khi mua kênh Youtube bật kiếm tiền thành công có thể mang lại thành công không nhỏ cho các cá nhân cũng như doanh ng', NULL, 116, 'App\\Models\\Post', '2021-05-10 18:41:34', '2021-05-10 18:41:34'),
(23, NULL, NULL, 'Bạn đang muốn tìm hiểu cách mua và giá mua bán kênh Youtube đã bật tính năng kiếm tiền nhưng chưa biết nên chọn địa điểm nào uy tín? Thông tin mới nhất năm 2021 sẽ được gửi đến bạn ngay sau đ', NULL, 117, 'App\\Models\\Post', '2021-05-10 21:08:07', '2021-05-10 21:08:07'),
(24, 'Hướng dẫn mua bán kênh Youtube an toàn tuyệt đối', 'huong-dan-mua-ban-kenh-youtube-an-toan-tuyet-doi', 'Nếu bạn muốn mua nick youtube an toàn để kiếm tiền online hay để phát triển thương hiệu, hãy lưu ý một số điều quan trọng khi mua hoặc bán kênh Youtube trong bài viết dưới đây.', NULL, 118, 'App\\Models\\Post', '2021-05-11 19:17:37', '2021-05-11 19:17:37'),
(25, 'Mua nick Youtube có vi phạm chính sách không?', 'mua-nick-youtube-co-vi-pham-chinh-sach-khong', 'Bạn đang tìm hiểu mua kênh Youtube nhưng chưa biết bắt đầu từ đâu và nơi nào uy tín? Liệu rằng khi mua bán tài khoản Youtube có bị vi phạm chính sách hay không? Hãy cùng tìm hiểu qua bài viết', NULL, 119, 'App\\Models\\Post', '2021-05-11 19:53:19', '2021-05-11 19:53:19'),
(26, '12 khó khăn Youtuber thường gặp sau khi mua ACC Youtube', '12-kho-khan-youtuber-thuong-gap-sau-khi-mua-acc-youtube', 'Làm thế nào để kiếm tiền hiệu quả sau khi đã mua tài khoản Youtube thành công? Bạn sẽ có khả năng gặp phải những khó khăn nào trong quá trình xây dựng và phát triển kênh?', NULL, 120, 'App\\Models\\Post', '2021-05-12 13:48:23', '2021-05-12 13:48:23'),
(27, 'Những ai nên mua tài khoản Youtube?', 'nhung-ai-nen-mua-tai-khoan-youtube', 'Bạn muốn kiếm tiền, tăng độ viral của thương hiệu hay đơn giản chỉ để giải trí trên Youtube? Có thực sự cần thiết mua hay không và quy trình mua như thế nào?', NULL, 121, 'App\\Models\\Post', '2021-05-12 14:18:31', '2021-05-12 14:18:31'),
(28, NULL, NULL, 'Bạn đang còn phân vân mua Youtube với giá rẻ liệu có an toàn? Địa điểm nào uy tín để mua tài khoản này nhanh chóng và thuận tiện? Cung cấp ngay cho bạn mua kênh Youtube uy tín.', NULL, 122, 'App\\Models\\Post', '2021-05-13 13:41:28', '2021-05-13 13:41:28'),
(29, 'Nên mua lại kênh Youtube hay xây dựng từ đầu?', 'nen-mua-lai-kenh-youtube-hay-xay-dung-tu-dau', 'Bạn đang có dự định quảng bán sản phẩm hoặc kiếm tiền online bằng kênh Youtube, nhưng lại đang băn khoăn không biết nên mua hay kênh Youtube hay xây dựng từ đầu?', NULL, 123, 'App\\Models\\Post', '2021-05-13 14:13:00', '2021-05-13 14:13:00'),
(30, 'Mua kênh Youtube 50k sub giá bao nhiêu?', 'mua-kenh-youtube-50k-sub-gia-bao-nhieu', 'Bạn đang muốn sở hữu kênh Youtube có 50k sub mà chưa biết giá cụ thể cũng như chưa biết địa chỉ uy tín và đáng tin cậy? Thắc mắc của bạn sẽ được giải đáp ngay sau đây.', NULL, 124, 'App\\Models\\Post', '2021-05-13 14:25:32', '2021-05-13 14:25:32'),
(31, 'Tiết lộ 3 cách tăng giờ xem Youtube cực hiệu quả', 'tiet-lo-3-cach-tang-gio-xem-youtube-cuc-hieu-qua', 'Bạn đang muốn kiếm tiền thật nhanh chóng và tiện lợi nhưng kênh Youtube chưa đủ điều kiện số lượt người xem và số giờ xem? Youtubestore sẽ chỉ cho bạn 3 cách tăng giờ xem cho kênh Youtube nha', NULL, 125, 'App\\Models\\Post', '2021-05-14 18:36:00', '2021-05-14 18:36:00'),
(33, 'Điều gì xảy ra khi mua Youtube ở nơi không uy tín?', 'dieu-gi-xay-ra-khi-mua-youtube-o-noi-khong-uy-tin', 'Điều gì xảy ra khi mua bán kênh Youtube ở nơi không uy tín? Đây là vấn đề không của riêng ai và cũng có rất nhiều người gặp phải tình trạng “tiền mất tật mang”. Hãy note ngay những lưu ý sau.', NULL, 127, 'App\\Models\\Post', '2021-05-17 18:36:48', '2021-05-17 18:36:48'),
(34, 'Hướng dẫn cách mua kênh Youtube an toàn', 'huong-dan-cach-mua-kenh-youtube-an-toan', 'Có rất nhiều trường hợp người mua Youtube đã bị lừa do thiếu kiến thức. Vậy giao dịch mua kênh youtube an toàn như thế nào?', NULL, 128, 'App\\Models\\Post', '2021-07-02 20:21:07', '2021-07-02 20:21:07'),
(35, 'Cách chuyển kênh youtube sang email khác nhanh nhất', 'cach-chuyen-kenh-youtube-sang-email-khac-nhanh-nhat', 'Bạn muốn chuyển kênh youtube sang một email khác nhưng chưa biết cách để thực hiện. Bài viết sẽ hướng dẫn bạn các thao tác dễ thực hiện để chuyển đổi tài khoản.', NULL, 129, 'App\\Models\\Post', '2021-07-02 20:32:20', '2021-07-02 20:32:20'),
(36, 'Mua kênh Youtube uy tín - nền móng phát triển kênh an toàn, chất lượng', 'mua-kenh-youtube-uy-tin-nen-mong-phat-trien-kenh-an-toan-chat-luong', 'Bạn muốn đẩy kênh của mình nhanh chóng kiếm tiền hoặc có độ viral tốt? Bước đầu tiên, hãy mua kênh youtube uy tín có chất lượng tốt từ nhà cung cấp uy tín.', NULL, 130, 'App\\Models\\Post', '2021-07-03 18:37:05', '2021-07-03 18:37:05'),
(37, 'Mua kênh Youtube 1k sub -  Đường tắt để xây dựng kênh Youtube', 'mua-kenh-youtube-1k-sub-duong-tat-de-xay-dung-kenh-youtube', 'Mua kênh youtube 1k sub là cách nhanh nhất để bạn có thể phát triển kênh mà không mất nhiều công sức. Vậy mua kênh như thế nào để đạt được hiệu quả tốt nhất?', NULL, 131, 'App\\Models\\Post', '2021-07-03 18:55:33', '2021-07-03 18:55:33'),
(38, 'Mua kênh Youtube 10k sub ở đâu bảo mật nhất?', 'mua-kenh-youtube-10k-sub-o-dau-bao-mat-nhat', 'Bạn đang muốn tìm một đơn vị uy tín để mua kênh youtube 10k sub nhằm phục vụ cho việc kiếm tiền của mình? Youtubestore.vn là một gợi ý không tồi dành cho bạn.', NULL, 132, 'App\\Models\\Post', '2021-07-03 19:06:18', '2021-07-03 19:06:18'),
(39, 'Có nên mua kênh Youtube 100k sub không? Khi nào cần mua và mua ở đâu?', 'co-nen-mua-kenh-youtube-100k-sub-khong-khi-nao-can-mua-va-mua-o-dau', 'Mua kênh Youtube 100k sub, sau đó sáng tạo nội dung và kiếm tiền là phương thức được nhiều người lựa chọn hiện này. Bài viết sau sẽ chia sẽ tới bạn địa chỉ mua kênh Youtube ở đâu uy tín chấ', NULL, 133, 'App\\Models\\Post', '2021-07-03 19:27:10', '2021-07-03 19:27:10'),
(40, 'Tại sao nên sử dụng dịch vụ mua bán kênh Youtube 1 triệu sub?', 'tai-sao-nen-su-dung-dich-vu-mua-ban-kenh-youtube-1-trieu-sub', 'Youtube đã trở thành một kênh truyền thông phát triển nhất trên thế giới. Vì thế, việc lựa chọn sử dụng dịch vụ bán kênh Youtube 1 triệu sub là nên tìm hiểu và thực hiện.', NULL, 134, 'App\\Models\\Post', '2021-07-05 21:16:55', '2021-07-05 21:16:55'),
(41, 'Có nên mua 1 kênh Youtube đã bật kiếm tiền?', 'co-nen-mua-1-kenh-youtube-da-bat-kiem-tien', 'Bạn đang có nhu cầu mua một kênh Youtube để phát triển tài năng bản thân. Những chưa biết lựa chọn loại nào, có nên mua kênh đã bật kiếm tiền không? Hãy để bài viết làm rõ câu hỏi này.', NULL, 135, 'App\\Models\\Post', '2021-07-09 15:05:31', '2021-07-09 15:05:31'),
(42, 'Hướng dẫn cách mua để có giá 1 kênh Youtube hợp lý', 'huong-dan-cach-mua-de-co-gia-1-kenh-youtube-hop-ly', 'Hiện nay, nhiều đơn vị cung cấp tài khoản Youtube ra đời, tuy nhiên không phải đơn vị nào cũng uy tín, giá rẻ. Vậy làm sao có được giá 1 kênh Youtube hợp lý. Hãy cùng tìm hiểu qua bài viết dư', NULL, 136, 'App\\Models\\Post', '2021-07-09 15:11:23', '2021-07-09 15:11:23'),
(43, 'Youtubestore - đơn vị bán kênh youtube 2k sub chất lượng tốt nhất', 'youtubestore-don-vi-ban-kenh-youtube-2k-sub-chat-luong-tot-nhat', 'Bán kênh youtube 2k sub giúp nhiều người có cơ hội kiếm tiền và quản bá thương hiệu sản phẩm thông qua acc youtube. Hãy tìm hiểu đơn vị bán kênh youtube uy tín chất lượng qua bài viết sau.', NULL, 137, 'App\\Models\\Post', '2021-07-09 15:15:44', '2021-07-09 15:15:44'),
(44, 'Shop bán kênh Youtube uy tín, chất lượng nhất hiện nay', 'shop-ban-kenh-youtube-uy-tin-chat-luong-nhat-hien-nay', 'Bạn đang muốn mua kênh Youtube với mục đích kiếm tiền hoặc quảng bá hình ảnh sản phẩm thương hiệu. Hãy theo dõi bài viết sau để biết địa chỉ shop bán kênh Youtube uy tín, chất lượng nhất h', NULL, 138, 'App\\Models\\Post', '2021-07-09 15:25:06', '2021-07-09 15:25:06'),
(45, 'Hướng dẫn cách đăng video lên Youtube được nhiều lượt xem đề xuất', 'huong-dan-cach-dang-video-len-youtube-duoc-nhieu-luot-xem-de-xuat', 'Để đăng video lên YouTube và tối ưu hóa nó để được đề xuất cho nhiều người xem hơn, bạn có thể tuân theo các bước sau:', NULL, 141, 'App\\Models\\Post', '2023-11-06 09:16:04', '2023-11-06 09:16:04'),
(46, 'Hướng dẫn cách đăng video tiktok tương tác cao đề xuất tốt', 'huong-dan-cach-dang-video-tiktok-tuong-tac-cao-de-xuat-tot', 'Để đăng video TikTok để tăng tương tác và đề xuất tốt, bạn có thể tuân theo các bước và chiến lược sau', NULL, 142, 'App\\Models\\Post', '2023-11-06 10:34:23', '2023-11-06 10:34:23'),
(47, 'Hướng dẫn cách tăng sub cho kênh youtube', 'huong-dan-cach-tang-sub-cho-kenh-youtube', 'Việc tăng số lượng người đăng ký (subscribers) cho kênh YouTube yêu cầu thời gian, công sức và chiến lược. Dưới đây là một số cách bạn có thể thử để tăng số lượng sub cho kênh của mình', NULL, 143, 'App\\Models\\Post', '2023-11-06 10:39:24', '2023-11-06 10:39:24'),
(48, 'Buy a safe and reputable YouTube channel', 'buy-a-safe-and-reputable-youtube-channel', 'In the ever-evolving landscape of digital marketing and content creation, YouTube has emerged as a powerhouse platform. As a result, buying and selling YouTube channels has become a lucrative', NULL, 144, 'App\\Models\\Post', '2023-11-06 10:56:32', '2023-11-06 10:56:32'),
(49, 'Hướng dẫn chi tiết: Cách Kiếm Tiền Trên YouTube', 'huong-dan-chi-tiet-cach-kiem-tien-tren-youtube', 'Muốn biết cách bắt đầu kiếm tiền trên YouTube? Bài viết này sẽ cung cấp cho bạn hướng dẫn chi tiết từ việc tạo kênh cho đến tối ưu hóa nội dung và các phương pháp kiếm tiền hiệu quả trên YouT', NULL, 145, 'App\\Models\\Post', '2023-11-06 11:06:04', '2023-11-06 11:06:04'),
(50, 'Hướng Dẫn Chi Tiết Cách Bắt Đầu Kiếm Tiền Trên YouTube Và Xây Dựng Kênh Youtube', 'huong-dan-chi-tiet-cach-bat-dau-kiem-tien-tren-youtube-va-xay-dung-kenh-youtube', 'YouTube không chỉ là một nền tảng chia sẻ video nổi tiếng mà còn là cơ hội tuyệt vời để kiếm tiền và xây dựng cộng đồng trực tuyến của bạn. Bài viết này sẽ hướng dẫn bạn từng bước để bắt đầu ', NULL, 146, 'App\\Models\\Post', '2023-12-01 01:53:38', '2023-12-01 01:53:38'),
(51, 'Buff Sub YouTube – Giải Pháp Tăng Lượt Đăng Ký Kênh Nhanh Chóng', 'buff-sub-youtube-giai-phap-tang-luot-dang-ky-kenh-nhanh-chong', 'Buff sub YouTube đang trở thành một xu hướng phổ biến đối với các nhà sáng tạo nội dung muốn tăng nhanh lượt đăng ký (subscriber) và phát triển kênh.', 'Buff sub YouTube', 150, 'App\\Models\\Post', '2024-12-05 00:51:05', '2024-12-05 00:51:05'),
(52, 'Mua Sub YouTube – Giải Pháp Tăng Lượt Đăng Ký Nhanh Chóng Và Hiệu Quả', 'mua-sub-youtube-giai-phap-tang-luot-dang-ky-nhanh-chong-va-hieu-qua', 'Trong thời đại mà YouTube trở thành nền tảng sáng tạo nội dung hàng đầu, việc sở hữu một kênh với lượt đăng ký cao là mơ ước của nhiều người.', 'mua sub youtube', 151, 'App\\Models\\Post', '2024-12-05 02:20:16', '2024-12-05 02:20:16'),
(53, 'Cách Mua Bán Kênh YouTube: Hướng Dẫn Từ A Đến Z', 'cach-mua-ban-kenh-youtube-huong-dan-tu-a-den-z', 'Mua bán kênh YouTube là một hoạt động phổ biến trong cộng đồng sáng tạo nội dung, giúp bạn nhanh chóng sở hữu kênh với lượt theo dõi và tương tác cao.', 'cách mua bán kênh youtube', 152, 'App\\Models\\Post', '2024-12-08 11:22:19', '2024-12-08 11:22:19'),
(56, 'TĂNG SUB YOUTUBE - DỊCH VỤ YOUTUBE HIỆU QUẢ TẠI YOUTUBESTORE.VN', 'tang-sub-youtube-dich-vu-youtube-hieu-qua-tai-youtubestorevn', 'Bạn đang tìm kiếm cách tăng sub YouTube nhanh chóng và đảm bảo uy tín? Hãy đến với YouTubeStore.vn, đơn vị cung cấp dịch vụ tăng subcriber YouTube', 'tăng sub youtube', 155, 'App\\Models\\Post', '2024-12-15 07:33:16', '2024-12-15 07:33:16'),
(59, 'Hướng dẫn làm YouTube trên điện thoại kiếm 80 - 100 triệu/tháng', 'huong-dan-lam-youtube-tren-dien-thoai-kiem-80-100-trieuthang', 'Điện thoại thông minh đang trở thành công cụ đắc lực giúp mọi người dễ dàng sáng tạo nội dung và kiếm tiền trên YouTube. Với chỉ một chiếc điện thoại, bạn hoàn toàn có thể quay, chỉnh sửa và', 'Hướng dẫn làm YouTube trên điện thoại', 158, 'App\\Models\\Post', '2024-12-18 02:53:57', '2024-12-18 03:09:28'),
(60, 'Youtubestore.vn Chỉ Bạn Cách Kiếm Tiền Trên YouTube Đơn Giản Nhất', 'youtubestorevn-chi-ban-cach-kiem-tien-tren-youtube-don-gian-nhat', 'YouTube không chỉ là nền tảng giải trí mà còn là nguồn thu nhập lớn cho hàng triệu người trên thế giới. Với sự phát triển của nội dung số, kiếm tiền trên YouTube đã trở thành một xu hướng phổ', 'Cách Kiếm Tiền Trên YouTube', 159, 'App\\Models\\Post', '2024-12-18 04:38:42', '2024-12-18 04:38:42'),
(62, 'Tất Tần Tật Về YouTube Shopping Shopee Cho Người Mới', 'tat-tan-tat-ve-youtube-shopping-shopee-cho-nguoi-moi', 'Trong thời đại video lên ngôi, YouTube Shopping Shopee đang trở thành xu hướng bán hàng online hiện đại, kết hợp giữa sức mạnh của nội dung và nền tảng thương mại điện tử hàng đầu Đông Nam Á', 'YouTube Shopping Shopee', 161, 'App\\Models\\Post', '2025-06-05 10:07:38', '2025-06-05 10:07:38');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `seos`
--
ALTER TABLE `seos`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `seos`
--
ALTER TABLE `seos`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=63;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
