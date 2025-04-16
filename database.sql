-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1
-- Temps de generació: 16-04-2025 a les 17:55:28
-- Versió del servidor: 10.4.32-MariaDB
-- Versió de PHP: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de dades: `mvc_shop`
--

-- --------------------------------------------------------

--
-- Estructura de la taula `cart`
--

CREATE TABLE `cart` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `product_id` int(11) NOT NULL,
  `quantity` int(11) NOT NULL DEFAULT 1,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de la taula `categories`
--

CREATE TABLE `categories` (
  `id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Bolcament de dades per a la taula `categories`
--

INSERT INTO `categories` (`id`, `name`, `created_at`) VALUES
(9, 'Áo Polo', '2025-04-16 13:35:02'),
(10, 'Áo Sơ mi', '2025-04-16 13:35:16'),
(11, 'Áo Khoác', '2025-04-16 13:35:33'),
(12, 'Quần Dài', '2025-04-16 13:35:49'),
(13, 'Quần Jean', '2025-04-16 13:35:59'),
(14, 'Quần Short', '2025-04-16 13:36:24'),
(15, 'Balo - Phụ kiện', '2025-04-16 13:36:41');

-- --------------------------------------------------------

--
-- Estructura de la taula `orders`
--

CREATE TABLE `orders` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `total_price` decimal(10,2) NOT NULL,
  `status` enum('pending','processing','ready_to_ship','shipping','completed','cancelled') NOT NULL DEFAULT 'pending',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Bolcament de dades per a la taula `orders`
--

INSERT INTO `orders` (`id`, `user_id`, `total_price`, `status`, `created_at`, `updated_at`) VALUES
(34, 1, 692235.00, 'pending', '2025-04-16 15:24:15', '2025-04-16 15:24:15');

-- --------------------------------------------------------

--
-- Estructura de la taula `order_items`
--

CREATE TABLE `order_items` (
  `id` int(11) NOT NULL,
  `order_id` int(11) NOT NULL,
  `product_id` int(11) NOT NULL,
  `quantity` int(11) NOT NULL,
  `price` decimal(10,2) NOT NULL,
  `size` varchar(10) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Bolcament de dades per a la taula `order_items`
--

INSERT INTO `order_items` (`id`, `order_id`, `product_id`, `quantity`, `price`, `size`) VALUES
(29, 34, 37, 1, 297000.00, NULL),
(30, 34, 47, 1, 472150.00, NULL);

-- --------------------------------------------------------

--
-- Estructura de la taula `products`
--

CREATE TABLE `products` (
  `id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `price` decimal(10,2) NOT NULL,
  `discount_price` decimal(10,2) DEFAULT NULL,
  `description` text DEFAULT NULL,
  `category_id` int(11) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `featured` tinyint(1) DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Bolcament de dades per a la taula `products`
--

INSERT INTO `products` (`id`, `name`, `price`, `discount_price`, `description`, `category_id`, `created_at`, `featured`) VALUES
(35, 'Áo Polo Non-Iron 06 Vol 24 Đen', 357000.00, NULL, '1. Kiểu sản phẩm: Áo Polo tay ngắn\r\n2. Ưu điểm:\r\nVới công nghệ ép seamless hiện đại, chiếc áo thun này mang đến trải nghiệm hoàn toàn mới. Đường may ép phẳng mềm mại, không gây cộm, giúp bạn thoải mái vận động cả ngày dài. Thiết kế tối giản, tinh tế, tôn lên vẻ đẹp nam tính và hiện đại. Chất liệu cao cấp, co giãn tốt, thấm hút mồ hôi nhanh chóng, mang đến cảm giác dễ chịu, tự tin. Hãy để chiếc áo thun seamless trở thành người bạn đồng hành thân thiết của bạn.\r\n3. Chất liệu: Polyester Pique Coffee 100%\r\n4. Kỹ thuật:\r\n• Cổ áo polyester không chỉ tạo điểm nhấn thời trang mà còn tăng cường độ bền cho sản phẩm, giúp áo luôn giữ được form dáng hoàn hảo sau nhiều lần giặt.\r\n• Nhãn ép phản quang hiện đại không chỉ là một chi tiết trang trí độc đáo mà còn giúp bạn an toàn hơn khi di chuyển trong điều kiện thiếu sáng.\r\n• Nút bóp đồng điệu tinh tế không chỉ tăng tính thẩm mỹ cho áo mà còn giúp bạn dễ dàng điều chỉnh độ ôm sát của cổ áo.\r\n• Thiết kế lai xẻ V thông minh giúp bạn thoải mái vận động mà không bị gò bó phần hông khi vận động.\r\nCông nghệ ép seamless, chiếc áo ôm trọn cơ thể, không còn cảm giác cộm hay khó chịu ở các vị trí lai, tay, lưng mang đến sự tự do vận động tối đa.\r\n5. Phù hợp với: Người làm việc văn phòng tạo nên vẻ ngoài lịch sự, chuyên nghiệp. Người yêu thích phong cách thời trang đơn giản, thanh lịch. Những ai muốn tìm kiếm một chiếc áo vừa thoải mái vừa thời trang.\r\n6. Phong cách: Minimalist, basic , casual\r\n7. Tìm kiếm sản phẩm: Áo polo nam, áo polo cổ bẻ, áo polo vải cá sấu cà phê, áo polo cao cấp, áo polo chống nắng.', 9, '2025-04-16 13:41:26', 1),
(36, 'Áo Polo ONE PIECE-WANO 06 Vol 25 Xám', 327000.00, NULL, '1. Kiểu sản phẩm: Áo Polo Dáng Vừa Tay Ngắn\r\n2. Ưu điểm:\r\nChất liệu Mesh Jacquard cao cấp với kết cấu bề mặt độc đáo gồm các mắt lưới nhỏ li ti đan xen, giúp tăng cường lưu thông không khí, mang lại cảm giác mát mẻ, dễ chịu ngay cả trong thời tiết nóng. Đồng thời, chất liệu này còn hạn chế nấm mốc và vi khuẩn tấn công, bảo vệ làn da của bạn. Các mắt lưới nhỏ cũng giúp bề mặt vải nhanh khô vượt trội, hạn chế sự phát triển của vi khuẩn và nấm mốc.\r\n3. Chất liệu: Mesh Jacquard, 100% Polyester\r\n4. Kỹ thuật: Trụ dây kéo đầu kim loại đảm bảo độ bền và mang lại trải nghiệm kéo mượt mà với thiết kế cơi che dây kéo tinh tế không chỉ tăng tính thẩm mỹ mà còn bảo vệ dây kéo tốt hơn. Bo cổ PE dày dặn giúp giữ form cổ áo, hạn chế tình trạng bai giãn sau thời gian sử dụng. Thân trước được may rã cong phá cách tạo điểm nhấn. Công nghệ thêu hiện đại với độ bền màu cao được sử dụng để đảm bảo màu sắc và độ sắc nét của hình thêu theo thời gian. Miếng đắp simili nhân vật Zoro trên ngực áo là dấu ấn thể hiện cho một fan One Piece chân chính.\r\n5. Phù hợp với: Fan ONE PIECE đặc biệt là người yêu thích nhân vật ZORO, phù hợp với nhiều hoạt động hàng ngày như đi chơi, dạo phố, tham gia các hoạt động ngoài trời.\r\n6. Phong cách: Thời trang hiện đại, trẻ trung, cá tính.\r\n7. Tìm kiếm sản phẩm: Áo thun Zoro, Áo Polo Nam, Áo Polo Basic, Áo Polo One Piece, Áo Polo ZORO, Áo Polo Nam Cao Cấp, Áo Polo Nam Thoải Mái, Áo Polo Nam In Hình, Áo thun streetwear, Áo thun unisex', 9, '2025-04-16 13:43:45', 1),
(37, 'Áo Polo No Style M30 Vol 24 Xanh Đen', 297000.00, NULL, '1. Kiểu sản phẩm: Áo polo tay ngắn.\r\n2. Ưu điểm: Với khả năng hút ẩm và thoát khí tuyệt vời, áo còn có độ co giãn hai chiều, dây gân thun tinh tế trên vai và thân áo tạo kiểu, cùng dây kéo phao đầu kim loại không gỉ, tất cả tạo nên một sản phẩm hoàn hảo.\r\n3. Chất liệu: Được làm từ Cotton Polyester 2S, với thành phần 60% Cotton và 40% Polyester, mang lại cảm giác mềm mại.\r\n4. Kỹ thuật: Với in dẻo trước ngực và thiết kế rã phối màu tinh tế, bo cổ làm từ cotton mềm mại co giãn, và tay áo được ép nhãn với hiệu ứng nhung sang trọng.\r\n5. Phù hợp với ai: Dành cho những người trẻ tuổi, yêu thích phong cách streetwear và muốn thể hiện cá tính độc đáo của mình.\r\n6. Thuộc Bộ Sưu Tập nào: No Style Collection - một bộ sưu tập đa dạng phong cách, từ tối giản đến phá cách, giúp bạn luôn nổi bật và tự tin.\r\n7. Các tên thường gọi hoặc tìm kiếm về sản phẩm này: Áo polo dáng rộng, áo phông có cổ, áo polo tay ngắn.', 9, '2025-04-16 13:45:45', 1),
(38, 'Áo Polo Seventy Seven 19 Vol 24 Trắng Xám', 227000.00, NULL, '1. Kiểu sản phẩm: Áo Thun Polo cổ bẻ, tay ngắn và dáng vừa.\r\n2. Ưu điểm:\r\n● Thấm hút: Chất liệu vải Pique giúp áo thấm hút mồ hôi, giữ bạn khô ráo và thoải mái trong suốt ngày.\r\n● Co giãn 4 chiều: Áo có khả năng co giãn theo 4 hướng, giúp bạn dễ dàng vận động mà không bị hạn chế.\r\n● Hạn chế xù lông: Vải không bị xù lông sau thời gian sử dụng, giữ cho áo luôn mới mẻ.\r\n● Thoáng khí: Thiết kế thoáng khí giúp bạn cảm thấy dễ chịu, đặc biệt trong những ngày nắng nóng.\r\n3. Chất liệu: Sản phẩm được làm từ vải Pique (cá sấu 4 chiều), có thành phần 95% Polyester và 5% Spandex.\r\n4. Kỹ thuật: Áo có họa tiết in dẻo trên bề mặt kết hợp chi tiết rã phối, tạo điểm nhấn thú vị.\r\n5. Phù hợp với ai: Áo Polo phù hợp với mọi người, đặc biệt là trong các hoạt động thể thao, dạo phố, hay khi bạn muốn trang nhã và thoải mái.\r\n6. Thuộc Bộ Sưu Tập: Bộ sưu tập Seventy Seven - thời trang trung tính, dễ phối dễ lựa chọn\r\n7. Các tên thường gọi hoặc tìm kiếm về sản phẩm này: Áo thun Polo cổ bẻ, áo thun Polo cá sấu, áo thun Polo dáng rộng, Áo thun Polo Seventy Seven, Áo thun Polo màu, Áo pique.', 9, '2025-04-16 13:48:46', 1),
(39, 'Áo Sơ Mi Seventy Seven 22 Vol 24 Xanh Rêu', 257000.00, NULL, '1. Kiểu sản phẩm: Áo sơ mi tay ngắn phối màu\r\n2. Ưu điểm:\r\n○ Với bề mặt dạng nhung hiệu ứng sọc tăm, bảng màu trầm ấm cho cảm giác ấm áp và mang vẻ đẹp Retro.\r\n○ Chất liệu sợi nhân tạo thấm hút giúp bạn luôn thoải mái, ít nhăn và duy trì màu sắc lâu dài.\r\n3. Chất liệu: Áo được làm từ vải Corduroy, với thành phần chất liệu 100% Polyester\r\n4. Kỹ thuật: Kỹ thuật thêu 2D tạo dấu ấn nổi bật cho sản phẩm, phối màu kết hợp hài hòa giữa các màu sắc trầm, tạo nên vẻ đẹp cổ điển và sang trọng.\r\n5. Phù hợp với ai: Phù hợp cho cả nam và nữ, từ công sở đến dạo phố, người yêu thích phong cách cổ điển.\r\n6. Thuộc Bộ Sưu Tập: Seventy Seven - thời trang với gam màu dễ phối đồ\r\n7. Các tên thường gọi hoặc tìm kiếm: Áo sơ mi Corduroy, áo sơ mi thêu 2D, Áo sơ mi phối màu,Áo sơ mi phối màu, Áo sơ mi nhung, áo sơ mi nam, Áo sơ mi unisex, Áo sơ mi nhung, Áo sơ mi nam ngắn tay ,Áo sơ mi corduroy cổ bẻ, Áo sơ mi unisex dáng rộng, Áo sơ mi màu trầm.', 9, '2025-04-16 13:50:27', 1),
(40, 'Áo Polo No Style M36 Vol 24 Nâu', 397000.00, NULL, '1. Kiểu sản phẩm: Áo polo tay dài.\r\n2. Ưu điểm:\r\n• Vải cotton double face mang đến cảm giác mềm mại, thoáng mát, khả năng thấm hút mồ hôi tốt, cấu trúc vải đặc biệt giúp áo giữ form tốt, ít nhăn.\r\n• Form rộng, phối màu tinh tế, đường kẻ ngang tạo nên phong cách năng động, hiện đại.\r\n• Bo cổ, bo tay: Sử dụng chất liệu cotton co giãn, tạo cảm giác thoải mái khi mặc.\r\n3. Chất liệu: Cotton double face (87% Cotton 13% Polyester).\r\n4. Kỹ thuật: Phần phối in dẻo với hai đường kẻ tinh tế ngang ngực, thêu 2D tạo phong cách riêng, và ép logo nhung sang trọng, nổi bật.\r\n5. Phù hợp với ai: Nam giới trẻ tuổi với thiết kế trẻ trung, năng động, phù hợp với phong cách hiện đại; và những người yêu thích thời trang đơn giản nhưng tinh tế, với áo polo luôn giữ vững vị thế không bao giờ lỗi mốt.\r\n6. Thuộc Bộ Sưu Tập nào: NoStyle - Bộ sưu tập thời trang đa dạng, từ tối giản đến phá cách, mang đến sự độc đáo và phong cách riêng biệt cho mọi gu thời trang.\r\n7. Các tên thường gọi hoặc tìm kiếm về sản phẩm này: Áo polo tay dài, áo polo form rộng, áo polo unisex.', 9, '2025-04-16 13:52:19', 1),
(41, 'Áo Polo Seventy Seven 20 Vol 24 Xanh Đen', 257000.00, NULL, '1. Kiểu sản phẩm: Áo thun polo tay dài\r\n2. Ưu điểm:\r\n● Thấm hút: Chất liệu cotton giúp áo thấm hút mồ hôi tốt, giữ cho cơ thể luôn khô ráo và thoải mái.\r\n● Co giãn 2 chiều: Vải có khả năng co giãn theo cả chiều ngang và chiều dọc, mang lại sự linh hoạt và thoải mái khi vận động.\r\n● Hút ẩm: Áo có khả năng hút ẩm hiệu quả, giúp duy trì sự thoáng mát và dễ chịu trong suốt ngày dài.\r\n● Thoát khí ưu việt: Chất liệu vải thoáng khí giúp không khí lưu thông dễ dàng, giảm thiểu cảm giác nóng bức và khó chịu.\r\n3. Chất liệu: Vải Cotton Polyester 2S (60% Cotton 40% Polyester).\r\n4. Kỹ thuật: In dẻo có độ bền cao, không bị nứt hay bong tróc sau nhiều lần giặt. Ngoài ra, in dẻo còn giúp màu sắc của họa tiết trở nên sắc nét và sống động hơn.\r\n5. Phù hợp với ai: Phù hợp với nhiều đối tượng khác nhau, đặc biệt là những người yêu thích sự thoải mái và tính năng thấm hút cao trong trang phục: người làm việc văn phòng, người tham gia các hoạt động ngoài trời, người yêu thích thời trang thanh lịch và hiện đại\r\n6. Thuộc Bộ Sưu Tập: Bộ sưu tập Seventy Seven - mang đến thời trang trung tính, dễ phối đồ, dễ lựa chọn.\r\n7. Các tên thường gọi hoặc tìm kiếm về sản phẩm này: Áo polo đen, Áo polo cotton, Áo polo Seventy Seven, Áo polo nam, Áo polo tay dài', 9, '2025-04-16 13:53:56', 0),
(42, 'Áo Polo PREMIUM 28 Vol 23 Xám Xanh', 277000.00, NULL, '1. Kiểu sản phẩm: Áo Polo Cổ Bẻ Tay Ngắn\r\n2. Ưu điểm:\r\n○ Nhanh khô Chất liệu đặc biệt giúp áo khô nhanh chóng sau khi giặt, tiết kiệm thời gian và công sức.\r\n○ Thoáng mát có khả năng thoáng khí tốt, giúp người mặc luôn cảm thấy mát mẻ và dễ chịu.\r\n○ Không nhăn giúp áo luôn giữ được vẻ ngoài gọn gàng, không cần ủi nhiều.\r\n3. Chất liệu:Thành phần: 95% Polyester và 5% Spandex, đảm bảo độ bền và độ co giãn tốt mang lại sự linh hoạt và thoải mái khi vận động.\r\n4. Kỹ thuật: Áo được rã phối mới lạ và tinh tế. Với công nghệ dệt hiện đại, tạo bề mặt vải mịn màng, ôm sát cơ thể mà vẫn thoải mái. Phù hợp cho cả hoạt động thể thao và hàng ngày.\r\n5. Phù hợp với ai: lý tưởng cho những ai yêu thích sự tiện lợi và thoải mái,thoáng mát và không nhăn.Người có phong cách thời trang đa dạng dễ dàng phối hợp với nhiều trang phục khác nhau, từ quần jeans đến quần kaki.\r\n6. Thuộc bộ sưu tập: PREMIUM thường được thiết kế với sự tinh tế và sang trọng, mang lại cảm giác như may đo riêng cho người mặc. Những chiếc áo trong bộ sưu tập này không chỉ đẹp mắt mà còn mang lại đẳng cấp và phong cách riêng biệt. Với nhiều kích cỡ khác nhau, bạn sẽ dễ dàng tìm được chiếc áo vừa ý nhất\r\n7. Các tên thường gọi hoặc tìm kiếm về sản phẩm này: Áo thun Polo tay ngắn, Áo thun Polo cổ trụ ,Áo thun Polo cổ bẻ, Áo thun Polo Cao Cấp, Áo thun Polo thời trang, Áo thun polo nam, Áo polo nam Pique.', 9, '2025-04-16 13:56:37', 1),
(43, 'Áo Polo No Style M31 Vol 24 Xám', 297000.00, NULL, '1. Kiểu sản phẩm: Áo Polo tay ngắn.\r\n2. Ưu điểm: Cấu trúc hút ẩm thoát khí ưu việt, co giãn 2 chiều cho sự thoải mái tối đa.\r\n3. Chất liệu: Thành phần gồm 60% Cotton và 40% Polyester mang lại cảm giác mềm mại và độ bền cao.\r\n4. Kỹ thuật: Xẻ trụ hình thang cầu kỳ và nhãn ép hiệu ứng nhung cho họa tiết thân trước và logo.\r\n5. Phù hợp với ai: Phong cách đa dạng từ tối giản đến phá cách, không giới hạn phong cách cá nhân.\r\n6. Thuộc Bộ Sưu Tập nào: No Style - gu nào cũng có, mặc đẹp, mặc độc, mặc đúng mốt.\r\n7. Các tên thường gọi hoặc tìm kiếm về sản phẩm này: Áo polo nam màu xám, áo polo raglan, áo polo phối.', 9, '2025-04-16 13:59:03', 1),
(44, 'Áo Polo Gu Tối Giản M19 Vol 23 Xanh Đen', 387000.00, NULL, '1. Kiểu sản phẩm: Áo thun polo tay ngắn.\r\n2. Ưu điểm: Thoáng mát, thấm hút mồ hôi tốt, thoát hơi nhanh chóng, mềm mại và co giãn linh hoạt.\r\n3. Chất liệu: Vải sợi nhân tạo Polyamide Spandex (60% Polyamide 40% Spandex).\r\n4. Kỹ thuật: Công nghệ Seamless giúp giảm thiểu đường may, tạo sự liền mạch, tăng độ bền cho sản phẩm mang lại sự thoải mái, linh hoạt và tính thẩm mĩ cao.\r\n5. Phù hợp với ai: Những người yêu thích sự thoải mái và phong cách tối giản trong thời trang.\r\n6. Thuộc Bộ Sưu Tập: Gu tối giản - thiết kế đơn giản nhưng tinh tế mang lại vẻ đẹp thanh lịch và sang trọng.\r\n7. Các tên thường gọi hoặc tìm kiếm về sản phẩm này: áo thun cổ trụ tối giản M18, áo polo nam, áo thun polo tay ngắn, áo thun polo co giãn.', 9, '2025-04-16 14:00:27', 0),
(45, 'Áo Khoác Seventy Seven 07 Vol 25 Đen', 357000.00, 339150.00, '1. Kiểu sản phẩm: Áo khoác có nón.\r\n2. Ưu điểm:\r\nGiữ ấm, tránh nắng.\r\nNhiều màu sắc để lựa chọn.\r\n3. Chất liệu: Vải Khaki 87% Polyester 13% Cotton\r\n4. Kỹ thuật:\r\nMay đắp nhãn dệt thiết kế riêng BST.\r\nTúi trong tiện dụng, lai luồn dây thun rút có nút chặn, nón luồn dây điều chỉnh kích thước.\r\nDây kéo đầu kim loại bọc nhựa an toàn với nẹp che thẩm mỹ.\r\n5. Phù hợp với ai: Người tìm kiếm sự tiện lợi và thoải mái trong form dáng rộng lớn và muốn bảo vệ khỏi ánh nắng mặt trời.\r\n6. Thuộc Bộ Sưu Tập: Seventy Seven, thời trang trung tính, dễ phối dễ lựa chọn.\r\n7. Các tên thường gọi hoặc tìm kiếm: Áo khoác kaki, áo khoác chống nắng form rộng, áo khoác có nón, áo khoác đi nắng.', 11, '2025-04-16 14:02:06', 1),
(46, 'Áo Khoác No Style M57 Vol 24 Xanh Dương', 597000.00, 567150.00, '1. Kiểu sản phẩm: Áo khoác Jean có nón form rộng.\r\n2. Ưu điểm: Độ bền vượt trội, khả năng thấm hút tuyệt vời, giữ phom dáng hoàn hảo, thiết kế độc đáo với sự kết hợp giữa vải jean và thun giả layer, tạo cảm giác như bạn đang mặc hai lớp áo.\r\n3. Chất liệu: Jean 100% Cotton cho phần chính, kết hợp với 94% Cotton và 6% Spandex cho phần vải phối.\r\n4. Kỹ thuật: Thêu 2D tinh xảo trước ngực, cùng hiệu ứng Wash nhẹ trên vải jean, tạo nên phong cách bụi bặm và cá tính.\r\n5. Phù hợp với ai: Dành cho những ai yêu thích sự đa dạng trong phong cách, từ tối giản đến phá cách, không giới hạn trong việc thể hiện cá tính riêng.\r\n6. Thuộc Bộ Sưu Tập nào: NoStyle - bộ sưu tập mang đến sự tự do trong phong cách, giúp bạn mặc đẹp, mặc độc, và luôn đúng mốt.\r\n7. Các tên thường gọi hoặc tìm kiếm về sản phẩm này: Áo khoác jean, áo khoác jean hai lớp, áo khoác jean có nón', 11, '2025-04-16 14:03:32', 1),
(47, 'Áo Khoác No Style M51 Vol 24 Xám Đậm', 497000.00, 472150.00, '1. Kiểu sản phẩm: Áo khoác thun\r\n2. Ưu điểm:\r\n● Mặc ấm: Chất liệu thun French Terry giúp giữ ấm cho cơ thể trong thời tiết\r\nlạnh.\r\n● Chống nắng: Áo khoác có thể là một lớp bảo vệ khỏi tác động của tia UV\r\nkhi bạn ra ngoài\r\n3. Chất liệu: Vải CVC French Terry (72% Cotton, 28% Polyester) là một loại vải mềm\r\nmại, thoáng khí và dễ mặc.\r\n4. Kỹ thuật: Áo được in dẻo, tạo ra hình ảnh hoặc chữ viết trên bề mặt vải.\r\n5. Phù hợp với ai : Áo khoác này được thiết kế để giữ ấm và chống nắng, phù hợp\r\ncho mùa đông hoặc những ngày lạnh\r\n6. Thuộc Bộ Sưu Tập nào: Bộ sưu tập No Style mang ý nghĩa thời trang đa dạng,\r\nđộc đáo và theo xu hướng. Đây là tập hợp các sản phẩm thiết kế với sự tinh tế,\r\nphù hợp cho những người yêu thích sự tự do trong cách ăn mặc.\r\n7. Các tên thường gọi hoặc tìm kiếm về sản phẩm này: Áo khoác thun, Áo khoác\r\ngiữ ấm, Áo khoác nam.', 11, '2025-04-16 14:04:46', 1),
(48, 'Áo Khoác PREMIUM 22 Vol 23 Xanh Biển', 657000.00, 624150.00, '1. Kiểu sản phẩm: Áo khoác có nón dáng rộng\r\n2. Ưu điểm:\r\nGiữ ấm, tránh nắng\r\nMềm mại, thấm hút thoát ẩm, co giãn đàn hồi, thân thiện môi trường\r\nNhiều màu sắc để lựa chọn\r\n3. Chất liệu: French Terry, 100% Cotton. Loại vải này có hai mặt khác nhau, một trơn phẳng và một mặt có các vòng lặp chéo chồng lên nhau trông khá giống vảy cá. Nhờ vào lớp “vảy cá” này, vải French Terry giúp thấm hút mồ hôi tốt hơn do bề mặt vải được tăng diện tích tiếp xúc\r\n4. Kỹ thuật:\r\nIn dẻo chữ lên bề mặt vải. Có độ đàn hồi, không bị nứt hay bong tróc khi vải co giãn\r\nPhần nón có dây luồn cùng màu vải có thể điều chỉnh được kích thước\r\nDây kéo cùng tone màu vải không gỉ đầu bọc nhựa an toàn.\r\n5. Phù hợp với ai: Người yêu thích sự thoải mái và phong cách thời trang đơn giản nhưng cao cấp.\r\n6. Thuộc Bộ Sưu Tập: Premium, thời trang đẹp như may đo, chất liệu xịn, phụ kiện đi kèm sang trọng, có gần 10 size khác nhau để vừa khít với từng người.\r\n7. Các tên thường gọi hoặc tìm kiếm về sản phẩm này: Áo khoác nam French Terry, áo khoác nam, áo khoác thun, áo khoác giữ ấm, áo khoác có nón, áo khoác nhẹ', 11, '2025-04-16 14:06:42', 0),
(49, 'Áo Khoác No Style M69 Vol 24 Xanh Dương', 497000.00, 472150.00, '1. Kiểu sản phẩm: Áo khoác vải jean\r\n2. Ưu điểm:\r\n○ Chống Nắng: Giúp bảo vệ da khỏi tác động của tia UV.\r\n○ Độ bền cao: Vải jean thường rất bền .\r\n○ Thấm hút tốt: Vải cotton giúp thấm hút mồ hôi tốt hơn.\r\n○ Đứng form: Áo khoác giữ form dáng sau nhiều lần sử dụng.\r\n3. Chất liệu: Vải Jean 100% Cotton.\r\n4. Kỹ thuật: Áo khoác được làm từ kỹ thuật thêu cao cấp.\r\n5. Phù hợp với ai: NoStyle - phù hợp với mọi gu thời trang từ cổ điển đến hiện đại và sành điệu.\r\n6. Thuộc Bộ Sưu Tập nào: Bộ sưu tập NoStyle là dòng sản phẩm thời trang đa dạng, phù hợp với nhiều phong cách khác nhau: Gu nào cũng có: Từ cổ điển đến hiện đại, đơn giản đến phá cách.\r\n7. Các tên thường gọi hoặc tìm kiếm về sản phẩm này: Áo khoác Jean, Áo Denim chống nắng.', 11, '2025-04-16 14:08:10', 0),
(50, 'Áo Sơ Mi Seventy Seven 50 Vol 24 Xanh Đậm', 457000.00, 434150.00, '1. Kiểu sản phẩm: Áo Sơ Mi Cổ Bẻ Tay Dài\r\n2. Ưu điểm: Chất liệu denim cao cấp, bền bỉ, giữ form dáng hoàn hảo, cho bạn vẻ ngoài lịch lãm. Kiểu dáng basic, dễ dàng phối đồ, biến hóa phong cách từ casual năng động đến thanh lịch tinh tế. Nút áo hợp kim siêu bền, được gia công tỉ mỉ, cho bạn an tâm tuyệt đối về chất lượng sản phẩm.\r\n3. Chất liệu: Jean (75,6% Cotton, 24,4% Polyester)\r\n4. Kỹ thuật: Túi đắp kích thước lớn trước ngực, tiện lợi cho việc đựng đồ cá nhân. Nút áo hợp kim cá tính chắc chắn, được gắn kết cẩn thận, đảm bảo độ bền cao.\r\n5. Phù hợp với: Phù hợp để mặc hàng ngày, đi chơi, dạo phố, cà phê cùng bạn bè.\r\nDễ dàng phối hợp với nhiều trang phục khác nhau, từ quần jeans, quần chinos đến quần short. Có thể mặc riêng hoặc khoác ngoài, tạo nên nhiều layer phong cách.\r\n6. Phong cách: Phong cách không giới hạn cùng chiếc áo sơ mi denim! Từ casual năng động, basic lịch lãm, đến streetwear cá tính và vintage hoài cổ.\r\n7. Tìm kiếm sản phẩm: Áo sơ mi denim nam, Áo sơ mi nam dáng rộng, Áo sơ mi nam tay dài, Áo sơ mi nam basic, Áo sơ mi denim túi hộp', 10, '2025-04-16 14:10:08', 0),
(51, 'Áo Sơ Mi Seventy Seven 23 Vol 24 Be', 257000.00, 244150.00, '1. Kiểu sản phẩm: Áo Sơ Mi Tay ngắn vải nhung\r\n2. Ưu điểm:\r\n● Chất liệu nhung thấm hút mồ hôi, vải ít nhăn và giữ form dáng tốt.\r\n● Màu sắc trung tính với bảng màu đa dạng, dễ phối dễ lựa chọn.\r\n3. Chất liệu: Vải Corduroy (100% Polyester) bề mặt nhám có cảm giác bumpy khi chạm vào.\r\n4. Kỹ thuật: Áo được thêu 2D cùng tone màu vải, tạo điểm nhấn thú vị.\r\n5. Phù hợp với ai: Áo sơ mi unisex có thể phù hợp cho cả nam và nữ, form dáng rộng phù hợp cho những người thích cảm giác thoải mái. Những ai đam mê phong cách Classic, Vintage, Retro hoặc Minimalism đều có thể thêm thiết kế này vào tủ đồ của mình.\r\n6. Thuộc Bộ Sưu Tập nào: Seventy Seven thời trang trung tính\r\n7. Các tên thường gọi hoặc tìm kiếm về sản phẩm này: Áo sơ mi dáng rộng cổ mở, Áo sơ mi Corduroy, Áo sơ mi nhung, Áo sơ mi dáng rộng unisex, Áo sơ mi cổ mở, Áo sơ mi danton, Áo sơ mi cuban, Áo sơ mi cổ pijama, Áo sơ mi cổ vest, Áo sơ mi cổ 2 ve, Áo sơmi kiểu dáng Hàn Quốc', 10, '2025-04-16 14:11:24', 0),
(52, 'Áo Sơ Mi WRINKLE FREE 10 Vol 24 Trắng', 497000.00, 472150.00, '1. Kiểu sản phẩm: Áo Sơ Mi Tay Dài Hoa Hồng Kháng Khuẩn\r\n2. Ưu điểm: Mặc không cần ủi, ít nhăn, kháng khuẩn, mềm mịn, giảm tia UV và khả năng kiểm soát mùi hôi, chiếc áo sơ mi này sẽ không chỉ đẹp mắt mà còn rất thoải mái khi mặc. Điều hòa nhiệt độ cũng là một ưu điểm quan trọng, đặc biệt trong các ngày nắng nóng.\r\n3. Chất liệu: Vải sợi có nguồn gốc từ Hoa Hồng (dệt Twill) với thành phần 55% Rose Fiber, 42% Polyester và 3% Spandex.\r\n4. Kỹ thuật: Đột chỉ vị trí viền cổ giúp cổ áo trở nên gọn gàng, chắc chắn. Nút áo được thiết kế đặc biệt là một điểm nhấn tinh tế cho chiếc áo. Icon hoa hồng được thêu 2D tỉ mỉ như một kí hiệu nhận biết cho loại vải đặc biệt này. Xếp ly đô sau tăng độ thoải mái cho phần lưng khi hoạt động\r\n5. Phù hợp với ai: Dành cho nam giới trong các hoạt động như đi làm công sở hay tham dự các sự kiện quan trọng.\r\n6. Thuộc Bộ Sưu Tập nào: WRINKLE FREE thuộc phân nhánh NON IRON ít nhăn dễ ủi\r\n7. Các tên thường gọi hoặc tìm kiếm về sản phẩm này: Áo sơ mi trơn basic nam, áo sơ mi công sở, áo sơ mi không nhăn, áo sơ mi chống UV,áo sơ mi dự sự kiện,áo sơ mi đi học, áo sơ mi công sở, áo sơ mi trắng', 10, '2025-04-16 14:12:38', 0),
(53, 'Áo Sơ Mi No Style M82 Vol 24 Đen', 277000.00, 263150.00, '1. Kiểu sản phẩm: Áo sơ mi cổ mở tay ngắn.\r\n2. Ưu điểm: Co giãn nhẹ, bền màu, kiểu dệt lạ và thời trang.\r\n3. Chất liệu: Polyester Fabric 95% Polyester, 5% Spandex.\r\n4. Kỹ thuật: Hiệu ứng vải ô vuông nổi chìm tạo sự khác biệt; thân trước có 2 túi đắp có nắp che bảo vệ đồ vật bên trong và tạo điểm nhấn.\r\n5. Phù hợp với ai: Phong cách đa dạng, từ người yêu thích sự tối giản đến người ưa chuộng phá cách.\r\n6. Thuộc Bộ Sưu Tập nào: No Style - mặc đẹp, mặc độc, mặc đúng mốt không giới hạn phong cách.\r\n7. Các tên thường gọi hoặc tìm kiếm về sản phẩm này: Áo sơ mi cổ mở tay ngắn, áo sơ nam vải họa tiết, áo sơ mi dáng rộng, áo sơ mi nam màu đen.', 10, '2025-04-16 14:18:26', 1),
(54, 'Áo Sơ Mi Seventy Seven 22 Vol 24 Be', 257000.00, 244150.00, '1. Kiểu sản phẩm: Áo sơ mi tay ngắn phối màu\r\n2. Ưu điểm:\r\n○ Với bề mặt dạng nhung hiệu ứng sọc tăm, bảng màu trầm ấm cho cảm giác ấm áp và mang vẻ đẹp Retro.\r\n○ Chất liệu sợi nhân tạo thấm hút giúp bạn luôn thoải mái, ít nhăn và duy trì màu sắc lâu dài.\r\n3. Chất liệu: Áo được làm từ vải Corduroy, với thành phần chất liệu 100% Polyester\r\n4. Kỹ thuật: Kỹ thuật thêu 2D tạo dấu ấn nổi bật cho sản phẩm, phối màu kết hợp hài hòa giữa các màu sắc trầm, tạo nên vẻ đẹp cổ điển và sang trọng.\r\n5. Phù hợp với ai: Phù hợp cho cả nam và nữ, từ công sở đến dạo phố, người yêu thích phong cách cổ điển.\r\n6. Thuộc Bộ Sưu Tập: Seventy Seven - thời trang với gam màu dễ phối đồ\r\n7. Các tên thường gọi hoặc tìm kiếm: Áo sơ mi Corduroy, áo sơ mi thêu 2D, Áo sơ mi phối màu,Áo sơ mi phối màu, Áo sơ mi nhung, áo sơ mi nam, Áo sơ mi unisex, Áo sơ mi nhung, Áo sơ mi nam ngắn tay ,Áo sơ mi corduroy cổ bẻ, Áo sơ mi unisex dáng rộng, Áo sơ mi màu trầm.\r\n', 10, '2025-04-16 14:20:49', 0),
(55, 'Quần Tây No Style M116 Vol 24 Đen', 397000.00, NULL, '1. Kiểu sản phẩm: Quần tây lưng thun ống đứng\r\n2. Ưu điểm:\r\n• Phong cách đa năng: Kết hợp hài hòa giữa phong cách công sở và thời trang đường phố, phù hợp với nhiều hoàn cảnh khác nhau.\r\n• Chất liệu cao cấp: Vải Trouser Fabric mềm mại, thoáng mát, có độ bền cao và ít nhăn, giúp bạn luôn tự tin.\r\n• Công nghệ kháng khuẩn: Giúp quần luôn sạch sẽ, hạn chế vi khuẩn gây mùi hôi.\r\n3. Chất liệu: Trouser Fabric 70% Polyester, 27% Rayon, 3% Spandex.\r\n4. Kỹ thuật: Thêu 2D thân trước logo nhãn hiệu. Thiết kế lưng thun tăng cường độ co giãn, giúp bạn thoải mái vận động, ống đứng tôn dáng chân, tạo cảm giác thon gọn.\r\n5. Phù hợp với ai: Nam giới văn phòng muốn tìm kiếm sự thoải mái và thanh lịch trong môi trường làm việc, sinh viên và học sinh thích phong cách trẻ trung, năng động. Những người yêu thích thời trang muốn sở hữu một chiếc quần vừa thời trang vừa tiện dụng.\r\n6. Thuộc Bộ Sưu Tập nào: No Style - Sản phẩm thuộc bộ sưu tập thời trang đa phong cách, không giới hạn, phù hợp với nhiều đối tượng khách hàng.\r\n7. Các tên thường gọi hoặc tìm kiếm: Quần tây nam lưng thun, quần tây nam công sở, quần tây nam thời trang.', 12, '2025-04-16 14:22:28', 1),
(56, 'Quần Tây Seventy Seven 27 Vol 24 Be', 297000.00, NULL, '1. Kiểu sản phẩm: Quần tây dáng vừa.\r\n2. Ưu điểm: Tôn dáng, có thể mặc trong nhiều hoàn cảnh khác nhau.\r\n3. Chất liệu: 96% Polyester, 4% Spandex, ít nhăn và dễ giặt ủi, co giãn 2 chiều.\r\n4. Kỹ thuật: Họa tiết thêu 2D, miếng đắp lưng làm cho chiếc quần trở nên bớt đơn điệu với thiết kế cổ điển có thể sử dụng lâu dài mà không lo lỗi mốt.\r\n5. Phù hợp với ai: Dân công sở, sinh viên, người tham dự các sự kiện trang trọng, và những ai có gu thẩm mỹ tinh tế.\r\n6. Thuộc Bộ Sưu Tập nào: Seventy Seven, thời trang trung tính, dễ phối, dễ chọn, phù hợp với mọi phong cách.\r\n7. Các tên thường gọi hoặc tìm kiếm về sản phẩm này: Quần tây lưng gài, quần tây\r\nống đứng, quần tây be, quần âu.', 12, '2025-04-16 14:24:36', 1),
(57, ' Quần Tây Gu Tối Giản M5 Vol 23', 385000.00, NULL, '1. Kiểu sản phẩm: Quần tây dáng vừa.\r\n2. Ưu điểm:\r\n- Co giãn, kháng khuẩn.\r\n- Mềm mịn, ít nhăn.\r\n- Độ bền màu tương đối tốt\r\n3. Chất liệu: Vải quần tây (87% Polyester, 10% Rayon, 3% Spandex).\r\n4. Kỹ thuật: Thiết kế giấu nút giúp quần trông gọn gàng và liền mạch hơn.\r\n5. Phù hợp với ai: Với vẻ ngoài thanh lịch và chuyên nghiệp, lý tưởng cho môi trường công sở và các sự kiện trang trọng\r\n6. Thuộc Bộ Sưu Tập nào: No Style, nơi kết hợp mọi phong cách từ thanh lịch đến cá tính, giúp bạn luôn tỏa sáng và dẫn đầu xu hướng trong mọi tình huống.\r\n7. Các tên thường gọi hoặc tìm kiếm về sản phẩm này: Quần tây lưng gài, quần công sở, quần tây xám đen, quần âu xám đen.', 12, '2025-04-16 14:26:04', 0),
(58, 'Quần Dài Beginner 08 Vol 24 Đen', 257000.00, NULL, '1. Kiểu sản phẩm: Quần dài lưng thun ống ôm.\r\n2. Ưu điểm:\r\n● Co giãn tốt và thoải mái khi mặc.\r\n● Hút ẩm tốt duy trì nhiệt độ cho cơ thể.\r\n● Đa dạng màu sắc thoải mái phối đồ theo sở thích.\r\n3. Chất liệu: Vải Polyester Double Face được làm từ 90% Polyester và 10% Spandex\r\n4. Kỹ thuật: Đường rã phối thông minh không giới hạn chuyển động. In nhũ độc đáo sử dụng các hạt nhũ hoặc kim loại để tạo hiệu ứng lấp lánh, ánh kim trên bề mặt sản phẩm.\r\n5. Phù hợp với ai: Người tìm kiếm sự thoải mái và tính năng co giãn trong quần dài\r\n6. Thuộc Bộ Sưu Tập nào: Beginner là một dòng sản phẩm thời trang thể thao dành cho những bạn mới bắt đầu chơi thể thao, đam mê vận động.\r\n7. Các tên thường gọi hoặc tìm kiếm về sản phẩm này: Quần thun dài co giãn, Quần polyester co giãn, Quần lưng thun form dáng vừa, Quần lưng thun ống ôm.', 12, '2025-04-16 14:27:53', 1),
(59, 'Quần Dài No Style M98 Vol 24 Xám', 387000.00, NULL, '1. Kiểu sản phẩm: Quần dài lưng thun ống đứng.\r\n2. Ưu điểm:\r\n• Vải dù thun có khả năng thấm hút mồ hôi tốt, tạo cảm giác khô thoáng.\r\n• Co giãn 2 chiều, chất liệu nhẹ, giúp người mặc thoải mái vận động.\r\n• Túi hộp lớn hai bên giúp đựng nhiều vật dụng cá nhân, dây rút điều chỉnh vòng eo linh hoạt.\r\n3. Chất liệu: Vải dù thun (parachute), thành phần 90% Nylon 10% Spandex.\r\n4. Kỹ thuật: Vị trí gối may xếp ly giúp tăng độ co giãn và tạo điểm nhấn cho quần.\r\n5. Phù hợp với ai: Người trẻ tuổi năng động, phù hợp với phong cách thời trang hiện đại, những người yêu thích sự thoải mái, thoáng mát, form rộng tạo cảm giác thoải mái khi vận động.\r\n6. Thuộc Bộ Sưu Tập nào: NoStyle - Một bộ sưu tập thời trang đa dạng, đáp ứng nhiều phong cách khác nhau, từ tối giản đến phá cách.\r\n7. Các tên thường gọi hoặc tìm kiếm về sản phẩm này: Quần dài lưng thun, quần dù xám, quần jogger , quần unisex.', 12, '2025-04-16 14:29:00', 0),
(60, 'Quần Jean Seventy Seven 51 Vol 24 Xanh Trắng', 457000.00, NULL, '1. Kiểu sản phẩm: Quần jean lưng gài ống đứng.\r\n2. Ưu điểm: Chất liệu denim mềm mại, co giãn tốt, kết hợp với thiết kế rách gối độc đáo và túi đồng hồ tinh xảo, giúp bạn tự tin thể hiện phong cách riêng. Mỗi đường may đều được chăm chút tỉ mỉ, đảm bảo độ bền cao và sự thoải mái tuyệt đối.\r\nĐường may tinh tế, tỉ mỉ, đảm bảo độ bền cao.\r\n• Thiết kế rách gối độc đáo, tạo điểm nhấn cá tính.\r\n• Form dáng ôm vừa vặn, tôn dáng người mặc.\r\n3. Chất liệu:Jean ,69% Cotton 20% Polyester 9% Rayon 2% Spandex\r\n4. Kỹ thuật: Với công nghệ giặt stone wash độc đáo, chiếc quần jean này mang đến vẻ ngoài bụi bặm, cá tính với hiệu ứng mài mòn tự nhiên.Túi đồng hồ tiện lợi, thiết kế tinh tế, không chỉ giúp bạn đựng được nhiều vật dụng nhỏ mà còn tạo điểm nhấn thời trang cho chiếc quần jean của bạn.\r\n5. Phù hợp với: Phù hợp với những người yêu thích phong cách thời trang đường phố, muốn thể hiện cá tính riêng.\r\n6. Phong cách: Thích hợp phong cách bụi bặm cá tính.\r\n7. Tìm kiếm sản phẩm: Quần jean nam rách gối, quần jean slim fit, quần jean dáng đứng, quần jean co giãn.', 13, '2025-04-16 14:31:23', 1),
(61, 'Quần Jean Seventy Seven 28 Ver2 Vol 24 Đen', 327000.00, NULL, '1. Kiểu sản phẩm: Quần Jean lưng gài ống đứng.\r\n2. Ưu điểm:\r\n● Khả năng chịu bền tốt.\r\n● Sợi Spandex giúp sản phẩm có độ co giãn nhẹ, tạo sự thoải mái khi di chuyển.\r\n● Giữ form dáng tốt, không bị nhão hay biến dạng sau nhiều lần giặt.\r\n● Đa dạng màu sắc dễ phối đồ và lựa chọn.\r\n3. Chất liệu: Vải Jean làm từ 85% Cotton, 14% Polyester, 1% Spandex.\r\n4. Kỹ thuật: Sản phẩm được may ống đứng giúp lên form vừa vặn không quá ôm giúp tôn dáng . May phối da thời trang tạo điểm nhấn cho quần .\r\n5. Phù hợp với ai: Những người yêu thích phong cách truyền thống, đơn giản và năng động.\r\n6. Thuộc Bộ Sưu Tập nào: Sản phẩm thuộc Bộ Sưu Tập Seventy Seven thiết kế mang tính trung tính thích hợp diện nhiều dịp khác nhau.\r\n7. Các tên thường gọi hoặc tìm kiếm về sản phẩm này: Quần Jean Seventy Seven màu đen, Quần Jean cotton co giãn, Quần Jean form dáng vừa, Quần Jean ống đứng, Quần bò, Quần bò nam.', 13, '2025-04-16 14:32:38', 1),
(62, 'Quần Jean PREMIUM 18 Vol 23 Xanh Đậm', 427000.00, NULL, '1. Kiểu sản phẩm: Quần Jean Lưng Gài Ống Đứng.\r\n2. Ưu điểm:\r\n● Co giãn vừa phải, tạo sự thoải mái khi mặc.\r\n● Form ôm giúp tôn lên đường cong cơ thể một cách tinh tế.\r\n● Ngăn túi đồng hồ tiện dụng có thể đựng các vật nhỏ gọn tránh bị trầy xước.\r\n3. Chất liệu: Quần jean được làm từ 98% cotton và 2% spandex.\r\n4. Kỹ thuật: Thêu logo nổi bật trên quần jean tạo điểm nhấn cho sản phẩm. Wash nhẹ giúp mềm vải và hiệu ứng sáng màu thời trang.\r\n5. Phù hợp với ai: Sản phẩm phù hợp cho những người thích quần jean co giãn vừa phải và form dáng ôm.\r\n6. Thuộc Bộ Sưu Tập nào: PREMIUM thời trang chất lượng cao, được thiết kế với sự tinh tế và phong cách riêng cao cấp.\r\n7. Các tên thường gọi hoặc tìm kiếm về sản phẩm này: Quần Jean Premium Xanh Đậm, Quần Jean Cotton Co Giãn, Quần Jean Lưng Gài Ống Đứng, Quần Bò, Quần Bò Nam.', 13, '2025-04-16 14:35:17', 1),
(63, 'Quần Jean PREMIUM 39 Vol 23 Xanh Đậm', 427000.00, NULL, '1. Kiểu sản phẩm: Quần Jean Lưng Gài Ống Đứng.\r\n2. Ưu điểm:\r\n● Form dáng vừa người, độ co giãn vừa phải tạo cảm thấy thoải mái khi vận động.\r\n● Ngăn túi nhỏ đồng hồ tiện lợi cho việc đựng những vật dụng nhỏ.\r\n3. Chất liệu: Jean Cotton Spandex làm từ 98% cotton và 2% spandex.\r\n4. Kỹ thuật: Wash hiệu ứng xước thân trước tạo điểm nhấn thời trang và phong cách.\r\n5. Phù hợp với ai: Những người thích kiểu dáng ôm vừa và thoải mái là lựa chọn tốt cho những ngày dạo phố hoặc gặp gỡ bạn bè phù hợp cho thanh niên đến người trung niên.\r\n6. Thuộc Bộ Sưu Tập nào: Bộ Sưu Tập PREMIUM đảm bảo chất lượng và phong cách cao cấp.\r\n7. Các tên thường gọi hoặc tìm kiếm về sản phẩm này: Quần Jean Premium Xanh Đậm, Quần Jean Co Giãn Trung Bình, Quần Jean Gam Lạnh, Quần Jean Lưng Gài Ống Đứng, Quần Bò, Quần Bò Nam.', 13, '2025-04-16 14:36:19', 0),
(64, 'Quần Jean The Day\'s Eye 38 Vol 23 Xanh Nhạt', 357000.00, NULL, '1. Kiểu sản phẩm: Quần Jean Lưng Gài Ống Đứng.\r\n2. Ưu điểm:\r\n● Độ co giãn vừa phải giúp người mặc cảm thấy linh hoạt và thoải mái trong mọi hoạt động mà không bị gò bó.\r\n● Chất vải mềm mỏng tạo cảm giác dễ chịu khi mặc, không gây cảm giác khó chịu.\r\n● Kiểu ống đứng mang lại vẻ ngoài thanh lịch và phù hợp với nhiều dáng người, dễ dàng phối hợp với nhiều loại trang phục khác nhau.\r\n3. Chất liệu: Jean Cotton Spandex gồm 98% cotton và 2% spandex, đảm bảo sự thoải mái và độ bền của sản phẩm.\r\n4. Kỹ thuật:\r\nWash nhẹ giúp quần có vẻ ngoài tự nhiên và mềm mại hơn.\r\nTúi sau được wash tạo điểm nhấn độc đáo, nổi bật phong cách người mặc.\r\nNhãn dệt được thiết kế riêng biệt, tạo dấu ấn đặc trưng cho bộ sưu tập .\r\nThiết kế lưng gài giúp quần dễ dàng điều chỉnh và ôm sát vòng eo, tạo cảm giác chắc chắn vừa vặn.\r\n5. Phù hợp với ai: Những ai yêu thích sự thoải mái và phong cách thời trang độc đáo.\r\nĐặc biệt là những người muốn tìm kiếm một chiếc quần jean có độ co giãn tốt, dễ dàng vận động và có thiết kế tinh tế.\r\n6. Thuộc Bộ Sưu Tập nào: The Day’s Eye Bộ sưu tập mang đến những thiết kế hiện đại, trẻ trung và đầy phong cách với biểu tượng hoa cúc cảm xúc tươi mới, thời trang hợp với thiên nhiên.\r\n7. Các tên thường gọi hoặc tìm kiếm về sản phẩm này: Quần Jean, Quần Jean Co Giãn, Quần Jean Dáng Vừa, Quần Jean Bộ Sưu Tập The Day’s Eye, Quần Jean Lưng Gài Ống Đứng, Quần Bò, Quần Bò Nam.', 13, '2025-04-16 14:37:54', 0),
(65, 'Quần Short 11 Inch Dáng Rộng Dragon Ball Z 25 Vol 25', 397000.00, NULL, '1.Kiểu sản phẩm: Quần short nam dáng rộng, phong cách thể thao và thời trang.\r\n2.Ưu điểm: Cotton cao cấp, mềm mại, co giãn, cho bạn tự do vận động suốt ngày dài, chất liệu thấm hút mồ hôi siêu tốc, giữ bạn luôn khô ráo.\r\n3.Chất liệu: Cotton Single , 100%cotton\r\n4.Kỹ thuật:\r\n-Đường may rã sườn tỉ mỉ: Không chỉ đảm bảo độ bền vượt trội, mà còn kiến tạo form dáng chuẩn mực, tôn lên vẻ đẹp hình thể và mang đến sự tự tin tuyệt đối cho người mặc.\r\n-Thiết kế túi đa năng, tiện lợi: Với hệ thống túi thông minh, bao gồm 2 túi cách điệu độc đáo và túi có dây kéo an toàn, bạn có thể thoải mái mang theo mọi vật dụng cá nhân mà không lo vướng víu.\r\n5.Phù hợp với: Nam giới yêu thích phong cách thể thao, năng động và cá tính. Đặc biệt phù hợp với những người hâm mộ Dragon Ball Z.\r\n6.Phong cách: Thể thao, thời trang đường phố (streetwear), cá tính.\r\n7.Tìm kiếm sản phẩm: Quần short nam Dragon Ball Z,Quần short thể thao,Quần short nam dáng rộng, Quần short thun cotton,Quần short nam nhiều túi', 14, '2025-04-16 14:39:35', 1),
(66, 'Quần Short The Beginner 17 Vol 25 Đen', 177000.00, NULL, '1. Kiểu sản phẩm: Quần short nam thể thao\r\n2. Ưu điểm: Khám phá sự khác biệt với chiếc quần short thể thao tích hợp đai vắt khăn thông minh! Không còn lo lắng về mồ hôi làm gián đoạn buổi tập, chỉ một thao tác đơn giản, bạn đã có thể giữ cho đôi tay luôn khô thoáng. Chất vải dù mỏng nhẹ, thoáng khí cùng đường xẻ lai chữ V năng động sẽ mang đến trải nghiệm vận động tuyệt vời, giúp bạn tự tin chinh phục mọi mục tiêu.\r\n3. Chất liệu: Parachute, 90% Nylon, 10% Spandex\r\n4. Kỹ thuật: An tâm tuyệt đối trong mọi hoạt động với thiết kế túi khóa kéo chắc chắn, bảo vệ an toàn cho những vật dụng cá nhân quan trọng của bạn. Không còn lo lắng đồ đạc bị rơi rớt khi vận động mạnh. Thêm vào đó, dây luồn ẩn tinh tế được thiết kế gọn gàng bên trong không chỉ mang đến vẻ ngoài tối giản, hiện đại mà còn loại bỏ hoàn toàn sự vướng víu khó chịu, giúp bạn tập trung tối đa vào bài tập.\r\n5. Phù hợp với: Chạy bộ, tập gym, chơi các môn thể thao ngoài trời hoặc hoạt động thường ngày như đi dạo, mặc ở nhà, đi du lịch.\r\n6. Phong cách: Thể thao\r\n7. Tìm kiếm sản phẩm: Quần short nam vải dù, Quần short nam thể thao, Quần short nam co giãn 4 chiều, Quần short nam đa năng, Quần short nam có đai vắt khăn.', 14, '2025-04-16 14:41:15', 0),
(67, 'Quần Short Seventy Seven 32 Vol 24 Xám', 227000.00, NULL, '1. Kiểu sản phẩm: Quần Short Lưng Thun Trên Gối Dáng Rộng\r\n2. Ưu điểm: Thấm Hút\r\n3. Chất liệu: Vải Corduroy - 100% Polyester, là một loại vải có đặc trưng bề mặt bằng sợi dạng gân xếp đan chéo tạo nên cấu trúc chắc chắn và mịn.\r\n4. Kỹ thuật: Họa tiết thêu đắp giống FEO tạo ra các chi tiết nổi bật và bắt mắt.\r\n5. Phù hợp với ai: Phù hợp với người yêu thích phong cách năng động và thích hợp\r\ncho hoạt động ngoài trời, dạo phố, du lịch.\r\n6. Thuộc Bộ Sưu Tập nào: Bộ sưu tập Seventy Seven mang phong cách hiện đại và năng động, kết hợp giữa tính thẩm mỹ và tiện dụng, phù hợp với nhiều đối tượng và hoàn cảnh khác nhau.\r\n7. Các tên thường gọi hoặc tìm kiếm về sản phẩm này: Quần Short Kaki Dáng Rộng Seventy Seven, Quần Short Corduroy Lưng Thun, Quần Short trên gối,...', 14, '2025-04-16 14:42:33', 0),
(68, 'Quần Short Beginner 04 Vol 24 Đen', 117000.00, NULL, '1. Kiểu sản phẩm: Quần Short 5 inch lưng thun, trên gối, không kèm quần lót bên trong.\r\n2. Ưu điểm:\r\n○ Siêu mỏng nhẹ, thấm hút và nhanh khô.\r\n○ Bền màu sau nhiều lần giặt.\r\n○ Dễ chăm sóc.\r\n3. Chất liệu: Vải Parachute (vải dù), 100% Polyester.\r\n4. Kỹ thuật: In nhũ bạc, rã sườn hợp lý giúp quần ít căng khi thực hiện vận động khó.\r\n5. Phù hợp với ai: Quần này phù hợp cho cả nam và nữ, đặc biệt là người mới bắt đầu chơi thể thao hoặc tập thể dục, tập gym.\r\n6. Thuộc Bộ Sưu Tập: Bộ sưu tập Beginner, mang thông điệp đồ thể thao cho những người mới bắt đầu. Đây là một tập hợp các sản phẩm thiết kế đơn giản, tiện lợi và chất lượng, giúp bạn thoải mái và tự tin trong mọi hoạt động thể thao.\r\n7. Các tên thường gọi hoặc tìm kiếm về sản phẩm này: Quần Short lưng thun, Quần Short trên gối, Quần Short thể thao, Quần 5 inch', 14, '2025-04-16 14:46:07', 1),
(69, 'Quần Short Cool Touch 04 Vol 24 Xám', 287000.00, NULL, '1. Kiểu sản phẩm: Quần short lưng thun dưới gối dáng vừa.\r\n2. Ưu điểm: Khả năng thấm hút mồ hôi tốt, giúp bạn luôn cảm thấy mát mẻ và thoải mái, đặc biệt trong những ngày hè nóng bức.\r\n3. Chất liệu: Minizurry 4S with High TPI - Cool Touch, 94% Cotton, 6% Spandex.\r\n4. Kỹ thuật:\r\n• Lưng quần sử dụng chất liệu thun và có dây luồn bên trong eo, giúp bạn dễ dàng điều chỉnh kích cỡ để quần luôn vừa vặn và thoải mái.\r\n• Hai túi trước và miếng đắp dệt thiết kế riêng tạo điểm nhấn thời trang, đồng thời mang lại sự tiện lợi khi dễ dàng mang theo các vật dụng nhỏ như điện thoại, chìa khóa, hoặc ví tiền.\r\n5. Phù hợp với ai: Những người yêu thích sự thoải mái, mát mẻ và phong cách năng động, hoặc thường xuyên hoạt động trong môi trường nhiều vận động hay thời tiết nóng nực.\r\n6. Thuộc Bộ Sưu Tập nào: Cool Touch - nổi bật với các sản phẩm được làm từ chất liệu vải mềm, mịn và mát, mang lại cảm giác dễ chịu và thoải mái cho người mặc.\r\n7. Các tên thường gọi hoặc tìm kiếm về sản phẩm này: Quần short lưng thun, quần short thun, quần short co giãn, quần short thoáng mát, quần short mùa hè, quần short casual.', 14, '2025-04-16 14:47:31', 0),
(70, 'Balo #Y2010 M01 Vol 25 Xám', 397000.00, 377150.00, '1. Kiểu sản phẩm: Balo Trượt Nước\r\n2. Ưu điểm:\r\nVới 6 màu sắc trung tính dễ dàng phối đồ, mẫu balo này sẽ là người bạn đồng hành lý tưởng cho mọi phong cách. Chất liệu “xịn sò” thách thức thời tiết với Công nghệ kháng nước WATER REPELLENT, lớp phủ Polyurethane 2 mặt chống thấm, ngăn nước thấm vào trong. Cấu trúc dệt chặt chẽ tạo nên tấm \'áo giáp\' vững chắc, bảo vệ sản phẩm khỏi các tác động bụi bẩn bên ngoài.\r\n*Lưu ý : Khả năng chống nước sẽ giảm dần theo thời gian sử dụng.\r\nKhông nên di chuyển trong điều kiện mưa lớn kéo dài, nước có thể thấm qua\r\ndây kéo và đường may.\r\n3. Chất liệu: Bề mặt balo được chế tác từ vải 600D PU 78T cao cấp . Lớp lót 210HD PU bền bỉ.\r\n4. Kỹ thuật:\r\nKích thước 43 x 30 x 12 cm\r\nNgăn chứa thông minh, tiện lợi tối đa. Ngăn lưới bên trong thoáng khí với chất liệu Air mesh, giúp bạn sắp xếp đồ đạc gọn gàng và dễ dàng tìm kiếm. Ngăn chống sốc bảo vệ laptop với lớp PE foam chống va đập hiệu quả.\r\n5. Phù hợp với ai: phù hợp với sinh viên, học sinh đựng sách vở và các vật dụng cá nhân khác. Thích hợp đi chơi, đi du lịch ngắn ngày.\r\n6. Phong cách: Phong cách tối giản Minimalism, hiện đại, trẻ trung, năng động.\r\n7. Tìm kiếm sản phẩm: Balo chống nước,Balo trượt nước, Balo laptop, Balo thời trang, Balo đi học, Balo du lịch, Balo Basic, Balo pastel, Balo trơn.', 15, '2025-04-16 14:48:40', 0),
(71, 'Balo Sức Chứa Lớn Dragon Ball Z 33 Vol 25', 497000.00, 472150.00, '1. Kiểu sản phẩm: Balo dạng cuộn (roll-top)\r\n2. Ưu điểm: Không còn lo lắng về việc thiếu chỗ chứa đồ! Với dung tích siêu khủng chiếc balo này có thể \"cân\" tất cả, từ sách vở, laptop đến đồ dùng cá nhân. Ngăn đựng giày thông minh tiện lợi tối đa cho những ai thường xuyên mang theo giày dép dự phòng hoặc giày thể thao. Thiết kế dạng cuộn (roll-top) hiện đại với khả năng xếp nhỏ lại giúp bạn tiết kiệm không gian khi không sử dụng, vô cùng tiện lợi.\r\n3. Chất liệu: 900HD PU Đen\r\n4. Kỹ thuật: Họa tiết Cân Đẩu Vân sử dụng kỹ thuật Heat transfer sắc nét, bền bỉ .\r\n5. Phù hợp với: Nam và nữ ,Học sinh, sinh viên, người đi làm, người yêu thích du lịch và hoạt động ngoài trời.\r\n6. Phong cách: Hiện đại, trẻ trung, năng động, cá tính.\r\n7. Tìm kiếm sản phẩm: Balo du lịch đa năng,Balo laptop có ngăn đựng giày,Balo chống nước,Balo thời trang nam nữ,Balo vải dù.', 15, '2025-04-16 14:50:00', 0),
(72, 'Vớ Thể Thao Beginner 84 Vol 24 Đen Cam', 77000.00, 73150.00, '1. Kiểu sản phẩm: PKTT Vớ thể thao\r\n2. Ưu điểm: Hỗ trợ vận động.\r\n• Đệm xù giảm chấn ở gót chân và mũi chân, bảo vệ chân khi tập luyện.\r\n• Chất liệu co giãn (Spandex) giúp vớ ôm sát chân, tạo cảm giác thoải mái và hỗ trợ chuyển động.\r\n• Chất liệu cotton thoáng khí giúp chân luôn khô thoáng, giảm thiểu mùi hôi.\r\n• Bền bỉ: cotton và rubber tăng độ bền cho vớ, chịu được ma sát cao.\r\n3. Chất liệu: 85% cotton, 10% Spandex, 5% Rubber.\r\n4. Kỹ thuật: Đệm xù tăng cường độ dày và độ êm ở các vị trí tiếp xúc với chân. Họa tiết dệt tạo điểm nhấn thẩm mỹ và giúp vớ ôm sát chân, thiết kế cổ vớ vừa vặn, không quá chặt cũng không quá lỏng.\r\n5. Phù hợp với ai: Người mới bắt đầu tập luyện, vớ đáp ứng tốt các yêu cầu của người mới tập, giúp bảo vệ chân và tạo cảm giác thoải mái, người tập gym, fitness phù hợp với các bài tập cường độ vừa phải.\r\n6. Thuộc Bộ Sưu Tập nào: Beginner - Dành cho người mới bắt đầu tập luyện, tập trung vào sự thoải mái và hỗ trợ cơ bản.\r\n7. Các tên thường gọi hoặc tìm kiếm về sản phẩm này: Vớ thể thao, vớ tập gym cổ ngắn, vớ thể thao thời trang.', 15, '2025-04-16 14:51:03', 0),
(73, 'Găng Tay Beginner 91 Vol 24 Đen Đỏ', 127000.00, 120650.00, '1. Kiểu sản phẩm: PKTT Găng tay thể thao.\r\n2. Ưu điểm:\r\n• Có lớp đệm: Bảo vệ lòng bàn tay và các khớp ngón tay khi tập luyện với tạ hoặc các dụng cụ thể thao khác.\r\n• Có phần chống trượt: Tăng độ bám, tránh bị trơn trượt khi cầm nắm các vật dụng.\r\n• Có lỗ thông hơi: Tăng cường khả năng thoáng khí, giúp tay luôn khô ráo.\r\n3. Chất liệu: Polyester + Spandex\r\n4. Kỹ thuật:\r\n• Găng tay kết hợp quấn cổ tay 2 in 1.\r\n• Thiết kế xỏ ngón hạn chế chai tay.\r\n• Lòng bàn tay với lớp Silicon chống trượt, tăng ma sát giúp cầm nắm chắc chắn khi tập luyện.\r\n• Dây đeo nén quanh cổ tay giúp cố định và bảo vệ khớp cổ tay, linh động điều chỉnh áp lực bằng khóa gai dán.\r\n5. Phù hợp với ai: Người mới bắt đầu chơi thể thao hoặc tập thể dục, tập gym.\r\n6. Thuộc Bộ Sưu Tập nào: Beginner - Hơi thở mới cho khởi đầu thể thao đầy hứng khởi, thể thao cho người mới bắt đầu.\r\n7. Các tên thường gọi hoặc tìm kiếm về sản phẩm này: Găng tay thể thao, găng tay tập gym, găng tay chống trượt.', 15, '2025-04-16 14:52:12', 0);

-- --------------------------------------------------------

--
-- Estructura de la taula `product_images`
--

CREATE TABLE `product_images` (
  `id` int(11) NOT NULL,
  `product_id` int(11) NOT NULL,
  `image_path` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Bolcament de dades per a la taula `product_images`
--

INSERT INTO `product_images` (`id`, `product_id`, `image_path`) VALUES
(52, 35, '1744810886_50a3b212-8529-0600-3dc9-001c11893155.jpg'),
(53, 35, '1744810886_aopolo1.jpg'),
(54, 35, '1744810886_e1f08999-7d4d-0200-b39d-001c4ec07eae.jpg'),
(55, 36, '1744811025_55642a06-7347-1d00-ec9e-001c431ace0a.jpg'),
(56, 36, '1744811025_c9fb0ae0-787f-1e00-29a7-001c431ace19.jpg'),
(57, 37, '1744811145_53f5ced3-14e5-1000-c0b9-001b8f36dd0c.jpg'),
(58, 37, '1744811145_c6839d80-571a-1200-bd07-001b8f36dd23.jpg'),
(59, 38, '1744811326_51b28c04-177a-7800-ac20-001b42cf598a.jpg'),
(60, 38, '1744811326_577313b3-7173-7d00-b253-001b42cf62d6.jpg'),
(61, 38, '1744811326_700056c8-42b4-7c00-27f8-001b42cf59af.jpg'),
(62, 38, '1744811326_bd9fe21c-ccd9-7b00-88d8-001b42cf59ab.jpg'),
(63, 39, '1744811427_1a9ca6e7-a41a-f200-65dc-001b3f090974.jpg'),
(64, 39, '1744811427_69027dd6-9e75-ee00-6603-001b3f090700.jpg'),
(65, 39, '1744811427_e84b3361-8fae-f000-dd04-001b3f090716.jpg'),
(66, 40, '1744811539_1c7ce79f-797c-8600-5913-001ba206c6f4.jpg'),
(67, 40, '1744811539_e74e96bd-56dc-8700-cc8e-001ba206c6fd (1).jpg'),
(68, 41, '1744811636_052d8048-56df-1f00-1ff5-001b4ed5db5b.jpg'),
(69, 41, '1744811636_68636c6c-b333-2000-7649-001b4ed5db60.jpg'),
(70, 41, '1744811636_c5012b7b-0b5a-2300-88bd-001b4ed5ddc6.jpg'),
(71, 42, '1744811797_0e633682-5a19-0400-1302-001ad42eb3d3.jpg'),
(72, 42, '1744811797_06a6fef6-65a4-aa00-9de1-001ad3687f41.jpg'),
(73, 42, '1744811797_50702512-9ae1-a900-a251-001ad3687f3f.jpg'),
(74, 43, '1744811943_857d11d2-f060-2100-fed9-001ba203ca14.jpg'),
(75, 43, '1744811943_b84a68e7-4337-1f00-0a7a-001ba203ca05.jpg'),
(76, 43, '1744811943_e693ec0d-df92-1e00-a295-001ba203c9fc.jpg'),
(77, 44, '1744812027_5f6243b9-ea51-0100-c4b4-0019ab0ce51e.jpg'),
(78, 44, '1744812027_56e44114-2027-5d00-9588-001999b2d247.jpg'),
(79, 44, '1744812027_aa6cb158-3319-6300-8c00-001999b3009a.jpg'),
(80, 45, '1744812126_36268900-bed7-d500-2608-001b4ed859d8.jpg'),
(81, 45, '1744812126_d8a52ccc-64ad-d700-e083-001b4ed859e5.jpg'),
(82, 45, '1744812126_ee4b9c76-6722-0100-dd18-001b55da2ad7.jpg'),
(83, 46, '1744812212_0e639bf8-b518-ae00-d20b-001ba20787f6.jpg'),
(84, 46, '1744812212_2c103155-cc29-1b00-d70c-001bbfe3124b.jpg'),
(85, 46, '1744812212_8e7ee01c-d980-b100-8477-001ba2078812.jpg'),
(86, 47, '1744812286_1d6755de-e284-9900-ff5a-001b73af50ce.jpg'),
(87, 47, '1744812286_3c4d0f19-9419-9800-aef4-001b73af1a8d.jpg'),
(88, 47, '1744812286_36268900-bed7-d500-2608-001b4ed859d8.jpg'),
(89, 48, '1744812402_632527cf-3503-1700-90e8-001afb4c33ef.jpg'),
(90, 48, '1744812402_ad7a9ec9-3b45-0e00-6aa7-001ada4f84c8.jpg'),
(91, 48, '1744812402_d4aafa6a-c553-0d00-3dc3-001ada4f84b8.jpg'),
(92, 49, '1744812490_61307a32-5ed8-0100-379b-001b7f538c83.jpg'),
(93, 49, '1744812490_4035182a-11c0-d300-827d-001b7a09f391.jpg'),
(94, 49, '1744812490_e47d0b82-c17d-d000-8054-001b7a09f364.jpg'),
(95, 50, '1744812608_2cb9271a-61f9-0800-df80-001c34e7a658.jpg'),
(96, 50, '1744812608_854af938-ced4-1e00-498e-001c34f5a435.jpg'),
(97, 50, '1744812608_44916cde-7a24-0200-16c4-001c336bcfe1.jpg'),
(98, 51, '1744812684_505a2718-33ad-fd00-66af-001b3f09576c.jpg'),
(99, 51, '1744812684_4432fb10-0e01-ff00-befe-001b3f095785.jpg'),
(100, 51, '1744812684_d94a9c8d-7933-0101-fcb4-001b3f095a07.jpg'),
(101, 52, '1744812758_42d3c349-b2a4-0200-057f-001ae3fdf5d9.jpg'),
(102, 52, '1744812758_51e6d623-8007-0100-a51e-001ae3fdf5d1.jpg'),
(103, 52, '1744812758_ac813b52-5fbf-2200-6254-001af20bd917.jpg'),
(104, 52, '1744812758_bb197a32-d341-7200-3a2b-001aeafea42a.jpg'),
(105, 53, '1744813106_4b73cc9d-898a-1600-db70-001b8f3b4cc4.jpg'),
(106, 53, '1744813106_5bc61049-43e4-1a00-31ae-001b8f3b5a84.jpg'),
(107, 53, '1744813106_00486ac7-0872-1500-d6ca-001b8f3b4cbb.jpg'),
(108, 53, '1744813106_a1b7e765-feb1-1c00-0aa1-001b8f3bd9fd.jpg'),
(109, 54, '1744813249_8b01a97e-c401-ed00-a360-001b3f08ed29.jpg'),
(110, 54, '1744813249_37a5b9fe-a458-e900-bdbf-001b3f08ea68.jpg'),
(111, 54, '1744813249_aeb21273-607a-eb00-d488-001b3f08ea79.jpg'),
(112, 55, '1744813348_0bc16d04-a793-0800-a785-001ba610f20f.jpg'),
(113, 55, '1744813348_081409f9-6211-0c00-0af9-001ba610fc52.jpg'),
(114, 55, '1744813348_b38f98b5-558c-5000-0c6d-001ba52b37a7.jpg'),
(115, 55, '1744813348_f997bb07-702b-5100-ff8b-001ba52b37b0.jpg'),
(116, 56, '1744813476_8170c041-a07f-a100-7ec9-001b4ed7bad8.jpg'),
(117, 56, '1744813476_81045a75-7e90-9b00-8b7e-001b4ed7ad0e.jpg'),
(118, 56, '1744813476_d5e0f91d-2ea3-0400-967b-001c284a0a75.jpg'),
(119, 56, '1744813476_f66aa849-a715-9e00-55f8-001b4ed7ad26.jpg'),
(120, 57, '1744813564_a9e34fe4-4dac-0400-6187-00195229c5df.jpg'),
(121, 57, '1744813564_af8db132-f566-0100-e9f8-001952299d66.jpg'),
(122, 57, '1744813564_e674cf26-18fe-0200-2b0b-001952299d76.jpg'),
(123, 58, '1744813673_7ff13c8f-bb10-1b00-491f-001b4072c8eb.jpg'),
(124, 58, '1744813673_8af55666-637b-2e00-3258-001bc4b7116b.jpg'),
(125, 58, '1744813673_b215e97c-0321-3500-fe91-001b511f9861.jpg'),
(126, 59, '1744813740_00cd0e90-b781-1500-37c0-001b8f428b91.jpg'),
(127, 59, '1744813740_7c420a88-7907-1900-55c0-001b8f428bb2.jpg'),
(128, 59, '1744813740_f01c4c43-fd27-1600-b628-001b8f428b99.jpg'),
(129, 60, '1744813883_00f2c039-85c0-4600-2c9f-001c0bfb7bdc.jpg'),
(130, 60, '1744813883_05eff4fd-c3b1-4500-6f74-001c0bfb7bcb.jpg'),
(131, 60, '1744813883_98535615-ec07-4700-f2b4-001c0bfb7bea.jpg'),
(132, 60, '1744813883_c9cad59b-58b9-4800-03a3-001c0bfb8581.jpg'),
(133, 61, '1744813958_38e6e71f-95b6-7600-18e9-001b43c6cf00.jpg'),
(134, 61, '1744813958_99dc0cc0-9af7-9e00-f17f-001bf09d25b9.jpg'),
(135, 61, '1744813958_599d8743-6d22-6000-07bc-001c38ecf426.jpg'),
(136, 61, '1744813958_1896aa52-0bab-7a00-c546-001b43c6f2eb.jpg'),
(137, 62, '1744814117_1cf1390e-5483-4f00-f0f6-001af087f09e.jpg'),
(138, 62, '1744814117_7d3e500b-6b60-4300-0f6b-001ae31445ec.jpg'),
(139, 62, '1744814117_7dd5d4a5-09f1-4500-2d35-001ae31445f5.jpg'),
(140, 63, '1744814179_2fa8c5ef-9204-3900-2bf0-001af0863996.jpg'),
(141, 63, '1744814179_98e8b89c-df3d-1b00-65fe-001ae31685dd.jpg'),
(142, 63, '1744814179_d87baa57-afc5-3e00-b56a-001ada542514.jpg'),
(143, 64, '1744814274_2ccd7c99-34a6-8800-92ef-001a4f3b4585.jpg'),
(144, 64, '1744814274_739946cb-c792-8900-f995-001a4f3b934c.jpg'),
(145, 64, '1744814274_eede8e18-5678-8700-6657-001a4f3b4568.jpg'),
(146, 64, '1744814274_f1ea301b-09e5-8b00-0101-001a4f3b935d.jpg'),
(147, 65, '1744814375_1efb5152-d11b-1700-458d-001c59c02d50.jpg'),
(148, 65, '1744814375_2af52758-8579-1500-47fd-001c59c018f3.jpg'),
(149, 65, '1744814375_60dd236f-7c5f-1300-0b27-001c59c018e4.jpg'),
(150, 65, '1744814375_aa7d9029-dd6c-1100-716e-001c59c018d9.jpg'),
(151, 66, '1744814475_773d7e69-69a7-2500-dd1c-001c520856a4.jpg'),
(152, 66, '1744814475_d362c186-b254-0200-7807-001c521e8b5e.jpg'),
(153, 66, '1744814475_d951bcb1-562e-2a00-9a9b-001c520856c2.jpg'),
(154, 67, '1744814553_5fcd6511-1c91-5000-000e-001b4957991c.jpg'),
(155, 67, '1744814553_7aab524f-7a95-1600-8ecf-001b3c8e3f31.jpg'),
(156, 67, '1744814553_29ca65e8-5d84-4e00-fa93-001b49579909.jpg'),
(157, 67, '1744814553_642f7dfe-42b0-4c00-22f7-001b495798fb.jpg'),
(158, 68, '1744814767_1f7cdd1c-e65f-3500-063b-001b3f1c6f75.jpg'),
(159, 68, '1744814767_164bdf2f-44b4-3300-f8cb-001b318c49d6.jpg'),
(160, 68, '1744814767_93785b62-e481-0500-445e-001b541a1907.jpg'),
(161, 68, '1744814767_fab2ae2c-e6ff-3600-ebed-001b3f1c6f77.jpg'),
(162, 69, '1744814851_437d2fbf-13b5-2700-49ca-001bf6fd8b73.jpg'),
(163, 69, '1744814851_6840524f-51bf-7500-175d-001aebce4311.jpg'),
(164, 69, '1744814851_cd571d74-7977-2600-ce6f-001bf6fd889b.jpg'),
(165, 69, '1744814851_e327fb5f-a221-7400-ec4e-001aebce3a53.jpg'),
(166, 70, '1744814920_6509edb8-4187-0700-2b72-001c38c7c90a.jpg'),
(167, 70, '1744814920_e3976d24-3001-0600-f25e-001c38c7c8ff.jpg'),
(168, 70, '1744814920_f308f9d9-c2cc-0500-419b-001c38c7c8f9.jpg'),
(169, 71, '1744815000_943706cd-8f20-b400-e799-001c59c8d256.jpg'),
(170, 71, '1744815000_f7ac9043-1b9e-b600-32fc-001c59c8d274.jpg'),
(171, 71, '1744815000_fe76f5d8-1945-b500-0e64-001c59c8d262.jpg'),
(172, 72, '1744815063_9c5a9eb0-20b9-1100-61de-001b8c091a15.jpg'),
(173, 72, '1744815063_60df0b99-c47e-0800-1793-001be670371c.jpg'),
(174, 72, '1744815063_dcb693d9-acaa-1300-9a9c-001b8c091a24.jpg'),
(175, 73, '1744815132_a4417e12-385a-1500-b3e5-001be6719875.jpg'),
(176, 73, '1744815132_d1dc9fc9-e2b5-1b00-1f6d-001bb59e0f47.jpg'),
(177, 73, '1744815132_ed614435-f430-1c00-da85-001bb59e0f4b.jpg');

-- --------------------------------------------------------

--
-- Estructura de la taula `product_sizes`
--

CREATE TABLE `product_sizes` (
  `id` int(11) NOT NULL,
  `product_id` int(11) DEFAULT NULL,
  `size` varchar(10) DEFAULT NULL,
  `stock` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Bolcament de dades per a la taula `product_sizes`
--

INSERT INTO `product_sizes` (`id`, `product_id`, `size`, `stock`) VALUES
(25, 35, 'S', 10),
(26, 35, 'M', 10),
(27, 35, 'L', 12),
(28, 35, 'XL', 9),
(29, 35, 'XXL', 5),
(30, 36, 'S', 12),
(31, 36, 'M', 13),
(32, 36, 'L', 20),
(33, 36, 'XL', 15),
(34, 36, 'XXL', 11),
(35, 37, 'S', 10),
(36, 37, 'M', 9),
(37, 37, 'L', 14),
(38, 37, 'XL', 12),
(39, 37, 'XXL', 13),
(40, 38, 'S', 14),
(41, 38, 'M', 12),
(42, 38, 'L', 15),
(43, 38, 'XL', 17),
(44, 38, 'XXL', 18),
(45, 39, 'S', 12),
(46, 39, 'M', 7),
(47, 39, 'L', 15),
(48, 39, 'XL', 20),
(49, 39, 'XXL', 12),
(50, 40, 'S', 12),
(51, 40, 'M', 10),
(52, 40, 'L', 14),
(53, 40, 'XL', 15),
(54, 40, 'XXL', 11),
(55, 41, 'S', 12),
(56, 41, 'M', 14),
(57, 41, 'L', 11),
(58, 41, 'XL', 14),
(59, 41, 'XXL', 16),
(60, 42, 'S', 14),
(61, 42, 'M', 12),
(62, 42, 'L', 14),
(63, 42, 'XL', 13),
(64, 42, 'XXL', 16),
(65, 43, 'S', 11),
(66, 43, 'M', 14),
(67, 43, 'L', 15),
(68, 43, 'XL', 12),
(69, 43, 'XXL', 16),
(70, 44, 'S', 14),
(71, 44, 'M', 12),
(72, 44, 'L', 14),
(73, 44, 'XL', 15),
(74, 44, 'XXL', 14),
(75, 45, 'S', 12),
(76, 45, 'M', 14),
(77, 45, 'L', 15),
(78, 45, 'XL', 13),
(79, 45, 'XXL', 17),
(80, 46, 'S', 12),
(81, 46, 'M', 5),
(82, 46, 'L', 11),
(83, 46, 'XL', 15),
(84, 46, 'XXL', 16),
(85, 47, 'S', 15),
(86, 47, 'M', 15),
(87, 47, 'L', 12),
(88, 47, 'XL', 15),
(89, 47, 'XXL', 14),
(90, 48, 'S', 12),
(91, 48, 'M', 15),
(92, 48, 'L', 13),
(93, 48, 'XL', 16),
(94, 48, 'XXL', 17),
(95, 49, 'S', 14),
(96, 49, 'M', 12),
(97, 49, 'L', 15),
(98, 49, 'XL', 16),
(99, 49, 'XXL', 13),
(100, 50, 'S', 11),
(101, 50, 'M', 12),
(102, 50, 'L', 14),
(103, 50, 'XL', 15),
(104, 50, 'XXL', 13),
(105, 51, 'S', 12),
(106, 51, 'M', 13),
(107, 51, 'L', 11),
(108, 51, 'XL', 15),
(109, 51, 'XXL', 10),
(110, 52, 'S', 12),
(111, 52, 'M', 14),
(112, 52, 'L', 11),
(113, 52, 'XL', 14),
(114, 52, 'XXL', 10),
(115, 53, 'S', 10),
(116, 53, 'M', 11),
(117, 53, 'L', 14),
(118, 53, 'XL', 15),
(119, 53, 'XXL', 12),
(120, 54, 'S', 12),
(121, 54, 'M', 11),
(122, 54, 'L', 10),
(123, 54, 'XL', 14),
(124, 54, 'XXL', 15),
(125, 55, 'S', 12),
(126, 55, 'M', 11),
(127, 55, 'L', 14),
(128, 55, 'XL', 15),
(129, 55, 'XXL', 12),
(130, 56, 'S', 12),
(131, 56, 'M', 11),
(132, 56, 'L', 10),
(133, 56, 'XL', 15),
(134, 56, 'XXL', 12),
(140, 57, 'S', 12),
(141, 57, 'M', 14),
(142, 57, 'L', 15),
(143, 57, 'XL', 16),
(144, 57, 'XXL', 17),
(145, 58, 'S', 12),
(146, 58, 'M', 4),
(147, 58, 'L', 11),
(148, 58, 'XL', 10),
(149, 58, 'XXL', 14),
(150, 59, 'S', 12),
(151, 59, 'M', 14),
(152, 59, 'L', 11),
(153, 59, 'XL', 16),
(154, 59, 'XXL', 20),
(155, 60, 'S', 11),
(156, 60, 'M', 12),
(157, 60, 'L', 14),
(158, 60, 'XL', 10),
(159, 60, 'XXL', 15),
(160, 61, 'S', 12),
(161, 61, 'M', 14),
(162, 61, 'L', 13),
(163, 61, 'XL', 10),
(164, 61, 'XXL', 15),
(165, 62, 'S', 12),
(166, 62, 'M', 11),
(167, 62, 'L', 14),
(168, 62, 'XL', 15),
(169, 62, 'XXL', 10),
(170, 63, 'S', 12),
(171, 63, 'M', 11),
(172, 63, 'L', 14),
(173, 63, 'XL', 13),
(174, 63, 'XXL', 16),
(175, 64, 'S', 12),
(176, 64, 'M', 11),
(177, 64, 'L', 14),
(178, 64, 'XL', 15),
(179, 64, 'XXL', 10),
(180, 65, 'S', 11),
(181, 65, 'M', 12),
(182, 65, 'L', 14),
(183, 65, 'XL', 13),
(184, 65, 'XXL', 15),
(185, 66, 'S', 11),
(186, 66, 'M', 14),
(187, 66, 'L', 12),
(188, 66, 'XL', 15),
(189, 66, 'XXL', 10),
(190, 67, 'S', 12),
(191, 67, 'M', 11),
(192, 67, 'L', 14),
(193, 67, 'XL', 10),
(194, 67, 'XXL', 16),
(195, 68, 'S', 11),
(196, 68, 'M', 12),
(197, 68, 'L', 14),
(198, 68, 'XL', 15),
(199, 68, 'XXL', 16),
(200, 69, 'S', 11),
(201, 69, 'M', 14),
(202, 69, 'L', 13),
(203, 69, 'XL', 10),
(204, 69, 'XXL', 9),
(205, 70, 'XXL', 20),
(206, 71, 'XXL', 19),
(207, 72, 'XXL', 20),
(208, 73, 'XXL', 12);

-- --------------------------------------------------------

--
-- Estructura de la taula `promotions`
--

CREATE TABLE `promotions` (
  `id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `description` text DEFAULT NULL,
  `discount_type` enum('percentage','fixed') NOT NULL,
  `discount_value` decimal(10,2) NOT NULL,
  `start_date` datetime DEFAULT NULL,
  `end_date` datetime DEFAULT NULL,
  `status` enum('active','inactive') NOT NULL DEFAULT 'active',
  `target_type` enum('product','category') NOT NULL,
  `created_at` datetime NOT NULL DEFAULT current_timestamp(),
  `updated_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Bolcament de dades per a la taula `promotions`
--

INSERT INTO `promotions` (`id`, `name`, `description`, `discount_type`, `discount_value`, `start_date`, `end_date`, `status`, `target_type`, `created_at`, `updated_at`) VALUES
(4, 'Giảm giá mùa hè 1', 'Mua sắm thả ga', 'percentage', 5.00, '2025-04-16 21:58:00', '2025-04-19 21:58:00', 'active', 'category', '2025-04-16 21:59:11', NULL);

-- --------------------------------------------------------

--
-- Estructura de la taula `promotion_categories`
--

CREATE TABLE `promotion_categories` (
  `id` int(11) NOT NULL,
  `promotion_id` int(11) NOT NULL,
  `category_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Bolcament de dades per a la taula `promotion_categories`
--

INSERT INTO `promotion_categories` (`id`, `promotion_id`, `category_id`) VALUES
(3, 4, 11),
(4, 4, 10),
(5, 4, 15);

-- --------------------------------------------------------

--
-- Estructura de la taula `promotion_products`
--

CREATE TABLE `promotion_products` (
  `id` int(11) NOT NULL,
  `promotion_id` int(11) NOT NULL,
  `product_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de la taula `side_banners`
--

CREATE TABLE `side_banners` (
  `id` int(11) NOT NULL,
  `title` varchar(255) NOT NULL,
  `image_path` varchar(255) NOT NULL,
  `status` enum('active','inactive') NOT NULL DEFAULT 'active',
  `created_at` datetime NOT NULL DEFAULT current_timestamp(),
  `updated_at` datetime NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Bolcament de dades per a la taula `side_banners`
--

INSERT INTO `side_banners` (`id`, `title`, `image_path`, `status`, `created_at`, `updated_at`) VALUES
(3, '1', '1744815605_WEB_2160x1080_.jpg', 'active', '2025-04-16 22:00:05', '2025-04-16 22:00:05'),
(4, '2', '1744815655_dung-luong-banner-thoi-trang.jpg', 'active', '2025-04-16 22:00:55', '2025-04-16 22:00:55');

-- --------------------------------------------------------

--
-- Estructura de la taula `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `email` varchar(150) NOT NULL,
  `password` varchar(255) NOT NULL,
  `role` enum('admin','customer') DEFAULT 'customer',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `status` enum('active','banned') DEFAULT 'active'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Bolcament de dades per a la taula `users`
--

INSERT INTO `users` (`id`, `name`, `email`, `password`, `role`, `created_at`, `status`) VALUES
(1, 'Đoàn Tô Gia Bảo2', 'doantogiabao@gmail.com', '$2y$10$6lSp1hlvZfHvl.ItA8XSeO1MG8qzli1ejPv4XGFRSnb0lXdF2z0t6', 'customer', '2025-03-29 07:02:30', 'active'),
(3, 'admin', 'Admin@gmail.com', '$2y$10$HsJmB3qdPA7Rg3oObHI1Pe3RA1FTPHgTkA1hW89tvhjJja0eYPIeW', 'admin', '2025-04-05 06:11:18', 'active');

-- --------------------------------------------------------

--
-- Estructura de la taula `vouchers`
--

CREATE TABLE `vouchers` (
  `id` int(11) NOT NULL,
  `code` varchar(20) NOT NULL,
  `discount_type` enum('percentage','fixed') NOT NULL,
  `discount_value` decimal(10,2) NOT NULL,
  `min_purchase` decimal(10,2) DEFAULT 0.00,
  `max_discount` decimal(10,2) DEFAULT NULL,
  `quantity` int(11) NOT NULL DEFAULT 1,
  `used_count` int(11) NOT NULL DEFAULT 0,
  `start_date` datetime DEFAULT NULL,
  `end_date` datetime DEFAULT NULL,
  `description` text DEFAULT NULL,
  `status` enum('active','inactive') NOT NULL DEFAULT 'active',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Bolcament de dades per a la taula `vouchers`
--

INSERT INTO `vouchers` (`id`, `code`, `discount_type`, `discount_value`, `min_purchase`, `max_discount`, `quantity`, `used_count`, `start_date`, `end_date`, `description`, `status`, `created_at`) VALUES
(1, 'TEST1', 'percentage', 10.00, 500000.00, 200000.00, 10, 1, '2025-04-16 21:54:00', '2025-04-19 21:54:00', 'Giảm giá mùa hè', 'active', '2025-04-16 14:58:32');

-- --------------------------------------------------------

--
-- Estructura de la taula `voucher_categories`
--

CREATE TABLE `voucher_categories` (
  `voucher_id` int(11) NOT NULL,
  `category_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Bolcament de dades per a la taula `voucher_categories`
--

INSERT INTO `voucher_categories` (`voucher_id`, `category_id`) VALUES
(1, 9),
(1, 10),
(1, 11),
(1, 12),
(1, 13),
(1, 14),
(1, 15);

-- --------------------------------------------------------

--
-- Estructura de la taula `voucher_usage`
--

CREATE TABLE `voucher_usage` (
  `id` int(11) NOT NULL,
  `voucher_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `order_id` int(11) NOT NULL,
  `discount_amount` decimal(10,2) NOT NULL,
  `used_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Bolcament de dades per a la taula `voucher_usage`
--

INSERT INTO `voucher_usage` (`id`, `voucher_id`, `user_id`, `order_id`, `discount_amount`, `used_at`) VALUES
(1, 1, 1, 34, 76915.00, '2025-04-16 15:24:15');

--
-- Índexs per a les taules bolcades
--

--
-- Índexs per a la taula `cart`
--
ALTER TABLE `cart`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `user_product` (`user_id`,`product_id`),
  ADD KEY `product_id` (`product_id`);

--
-- Índexs per a la taula `categories`
--
ALTER TABLE `categories`
  ADD PRIMARY KEY (`id`);

--
-- Índexs per a la taula `orders`
--
ALTER TABLE `orders`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`);

--
-- Índexs per a la taula `order_items`
--
ALTER TABLE `order_items`
  ADD PRIMARY KEY (`id`),
  ADD KEY `order_id` (`order_id`),
  ADD KEY `product_id` (`product_id`);

--
-- Índexs per a la taula `products`
--
ALTER TABLE `products`
  ADD PRIMARY KEY (`id`),
  ADD KEY `category_id` (`category_id`);

--
-- Índexs per a la taula `product_images`
--
ALTER TABLE `product_images`
  ADD PRIMARY KEY (`id`),
  ADD KEY `product_id` (`product_id`);

--
-- Índexs per a la taula `product_sizes`
--
ALTER TABLE `product_sizes`
  ADD PRIMARY KEY (`id`),
  ADD KEY `product_id` (`product_id`);

--
-- Índexs per a la taula `promotions`
--
ALTER TABLE `promotions`
  ADD PRIMARY KEY (`id`);

--
-- Índexs per a la taula `promotion_categories`
--
ALTER TABLE `promotion_categories`
  ADD PRIMARY KEY (`id`),
  ADD KEY `promotion_id` (`promotion_id`),
  ADD KEY `category_id` (`category_id`);

--
-- Índexs per a la taula `promotion_products`
--
ALTER TABLE `promotion_products`
  ADD PRIMARY KEY (`id`),
  ADD KEY `promotion_id` (`promotion_id`),
  ADD KEY `product_id` (`product_id`);

--
-- Índexs per a la taula `side_banners`
--
ALTER TABLE `side_banners`
  ADD PRIMARY KEY (`id`);

--
-- Índexs per a la taula `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- Índexs per a la taula `vouchers`
--
ALTER TABLE `vouchers`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `code` (`code`);

--
-- Índexs per a la taula `voucher_categories`
--
ALTER TABLE `voucher_categories`
  ADD PRIMARY KEY (`voucher_id`,`category_id`),
  ADD KEY `category_id` (`category_id`);

--
-- Índexs per a la taula `voucher_usage`
--
ALTER TABLE `voucher_usage`
  ADD PRIMARY KEY (`id`),
  ADD KEY `voucher_id` (`voucher_id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `order_id` (`order_id`);

--
-- AUTO_INCREMENT per les taules bolcades
--

--
-- AUTO_INCREMENT per la taula `cart`
--
ALTER TABLE `cart`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=42;

--
-- AUTO_INCREMENT per la taula `categories`
--
ALTER TABLE `categories`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;

--
-- AUTO_INCREMENT per la taula `orders`
--
ALTER TABLE `orders`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=35;

--
-- AUTO_INCREMENT per la taula `order_items`
--
ALTER TABLE `order_items`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=31;

--
-- AUTO_INCREMENT per la taula `products`
--
ALTER TABLE `products`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=74;

--
-- AUTO_INCREMENT per la taula `product_images`
--
ALTER TABLE `product_images`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=178;

--
-- AUTO_INCREMENT per la taula `product_sizes`
--
ALTER TABLE `product_sizes`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=209;

--
-- AUTO_INCREMENT per la taula `promotions`
--
ALTER TABLE `promotions`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT per la taula `promotion_categories`
--
ALTER TABLE `promotion_categories`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT per la taula `promotion_products`
--
ALTER TABLE `promotion_products`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT per la taula `side_banners`
--
ALTER TABLE `side_banners`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT per la taula `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT per la taula `vouchers`
--
ALTER TABLE `vouchers`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT per la taula `voucher_usage`
--
ALTER TABLE `voucher_usage`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- Restriccions per a les taules bolcades
--

--
-- Restriccions per a la taula `cart`
--
ALTER TABLE `cart`
  ADD CONSTRAINT `cart_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `cart_ibfk_2` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE CASCADE;

--
-- Restriccions per a la taula `orders`
--
ALTER TABLE `orders`
  ADD CONSTRAINT `orders_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Restriccions per a la taula `order_items`
--
ALTER TABLE `order_items`
  ADD CONSTRAINT `order_items_ibfk_1` FOREIGN KEY (`order_id`) REFERENCES `orders` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `order_items_ibfk_2` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE CASCADE;

--
-- Restriccions per a la taula `products`
--
ALTER TABLE `products`
  ADD CONSTRAINT `products_ibfk_1` FOREIGN KEY (`category_id`) REFERENCES `categories` (`id`) ON DELETE SET NULL;

--
-- Restriccions per a la taula `product_images`
--
ALTER TABLE `product_images`
  ADD CONSTRAINT `product_images_ibfk_1` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE CASCADE;

--
-- Restriccions per a la taula `product_sizes`
--
ALTER TABLE `product_sizes`
  ADD CONSTRAINT `product_sizes_ibfk_1` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`);

--
-- Restriccions per a la taula `promotion_categories`
--
ALTER TABLE `promotion_categories`
  ADD CONSTRAINT `promotion_categories_ibfk_1` FOREIGN KEY (`promotion_id`) REFERENCES `promotions` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `promotion_categories_ibfk_2` FOREIGN KEY (`category_id`) REFERENCES `categories` (`id`) ON DELETE CASCADE;

--
-- Restriccions per a la taula `promotion_products`
--
ALTER TABLE `promotion_products`
  ADD CONSTRAINT `promotion_products_ibfk_1` FOREIGN KEY (`promotion_id`) REFERENCES `promotions` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `promotion_products_ibfk_2` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE CASCADE;

--
-- Restriccions per a la taula `voucher_categories`
--
ALTER TABLE `voucher_categories`
  ADD CONSTRAINT `voucher_categories_ibfk_1` FOREIGN KEY (`voucher_id`) REFERENCES `vouchers` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `voucher_categories_ibfk_2` FOREIGN KEY (`category_id`) REFERENCES `categories` (`id`) ON DELETE CASCADE;

--
-- Restriccions per a la taula `voucher_usage`
--
ALTER TABLE `voucher_usage`
  ADD CONSTRAINT `voucher_usage_ibfk_1` FOREIGN KEY (`voucher_id`) REFERENCES `vouchers` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `voucher_usage_ibfk_2` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `voucher_usage_ibfk_3` FOREIGN KEY (`order_id`) REFERENCES `orders` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
