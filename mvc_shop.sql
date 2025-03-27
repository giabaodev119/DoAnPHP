-- Đặt chế độ SQL
SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";

-- Tạo CSDL nếu chưa có
CREATE DATABASE IF NOT EXISTS `mvc_shop` DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci;
USE `mvc_shop`;

-- Tạo bảng Users
CREATE TABLE IF NOT EXISTS `users` (
  `id` INT(11) NOT NULL AUTO_INCREMENT,
  `name` VARCHAR(100) NOT NULL,
  `email` VARCHAR(150) NOT NULL UNIQUE,
  `password` VARCHAR(255) NOT NULL,
  `role` ENUM('admin','customer') DEFAULT 'customer',
  `created_at` TIMESTAMP NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Tạo bảng Categories
CREATE TABLE IF NOT EXISTS `categories` (
  `id` INT(11) NOT NULL AUTO_INCREMENT,
  `name` VARCHAR(100) NOT NULL,
  `created_at` TIMESTAMP NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Tạo bảng Products
CREATE TABLE IF NOT EXISTS `products` (
  `id` INT(11) NOT NULL AUTO_INCREMENT,
  `name` VARCHAR(255) NOT NULL,
  `price` DECIMAL(10,2) NOT NULL,
  `description` TEXT DEFAULT NULL,
  `image` VARCHAR(255) DEFAULT NULL,
  `category_id` INT(11) DEFAULT NULL,
  `created_at` TIMESTAMP NOT NULL DEFAULT current_timestamp(),
  `featured` TINYINT(1) DEFAULT 0,
  PRIMARY KEY (`id`),
  FOREIGN KEY (`category_id`) REFERENCES `categories` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Tạo bảng Cart
CREATE TABLE IF NOT EXISTS `cart` (
  `id` INT(11) NOT NULL AUTO_INCREMENT,
  `user_id` INT(11) NOT NULL,
  `product_id` INT(11) NOT NULL,
  `quantity` INT(11) UNSIGNED NOT NULL DEFAULT 1,
  PRIMARY KEY (`id`),
  FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Tạo bảng Orders
CREATE TABLE IF NOT EXISTS `orders` (
  `id` INT(11) NOT NULL AUTO_INCREMENT,
  `user_id` INT(11) NOT NULL,
  `total_price` DECIMAL(10,2) NOT NULL,
  `status` ENUM('pending','processing','shipped','delivered','cancelled') DEFAULT 'pending',
  `created_at` TIMESTAMP NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`),
  FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Tạo bảng Order Items
CREATE TABLE IF NOT EXISTS `order_items` (
  `id` INT(11) NOT NULL AUTO_INCREMENT,
  `order_id` INT(11) NOT NULL,
  `product_id` INT(11) NOT NULL,
  `quantity` INT(11) NOT NULL,
  `price` DECIMAL(10,2) NOT NULL,
  PRIMARY KEY (`id`),
  FOREIGN KEY (`order_id`) REFERENCES `orders` (`id`) ON DELETE CASCADE,
  FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Tạo bảng Product Images
CREATE TABLE IF NOT EXISTS `product_images` (
  `id` INT(11) NOT NULL AUTO_INCREMENT,
  `product_id` INT(11) NOT NULL,
  `image_path` VARCHAR(255) DEFAULT NULL,
  PRIMARY KEY (`id`),
  FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Thêm dữ liệu vào Categories
INSERT INTO `categories` (`id`, `name`, `created_at`) VALUES
(1, 'Điện thoại', NOW()),
(2, 'Laptop', NOW()),
(3, 'Phụ kiện', NOW())
ON DUPLICATE KEY UPDATE name=VALUES(name);

-- Thêm dữ liệu vào Products
INSERT INTO `products` (`id`, `name`, `price`, `description`, `image`, `category_id`, `created_at`, `featured`) VALUES
(1, 'iPhone 15 Pro', 1200.00, 'Điện thoại cao cấp từ Apple', 'iphone15.jpg', 1, NOW(), 1),
(2, 'MacBook Pro M2', 2500.00, 'Laptop mạnh mẽ của Apple', 'macbookpro.jpg', 2, NOW(), 1),
(3, 'Tai nghe AirPods Pro', 250.00, 'Tai nghe chống ồn của Apple', 'airpodspro.jpg', 3, NOW(), 1)
ON DUPLICATE KEY UPDATE name=VALUES(name), price=VALUES(price), description=VALUES(description);

-- Thêm dữ liệu vào Users
INSERT INTO `users` (`id`, `name`, `email`, `password`, `role`, `created_at`) VALUES
(1, 'Admin', 'admin@example.com', MD5('password'), 'admin', NOW()),
(2, 'Khách hàng 1', 'user1@example.com', MD5('123456'), 'customer', NOW())
ON DUPLICATE KEY UPDATE email=VALUES(email);

-- Thêm dữ liệu vào Cart
INSERT INTO `cart` (`user_id`, `product_id`, `quantity`) VALUES
(2, 1, 2),
(2, 2, 1)
ON DUPLICATE KEY UPDATE quantity=VALUES(quantity);

-- Thêm dữ liệu vào Orders
INSERT INTO `orders` (`user_id`, `total_price`, `status`, `created_at`) VALUES
(2, 1500.00, 'pending', NOW())
ON DUPLICATE KEY UPDATE total_price=VALUES(total_price);

-- Thêm dữ liệu vào Order Items
INSERT INTO `order_items` (`order_id`, `product_id`, `quantity`, `price`) VALUES
(1, 1, 2, 1200.00),
(1, 2, 1, 2500.00)
ON DUPLICATE KEY UPDATE quantity=VALUES(quantity);

-- Thêm dữ liệu vào Product Images
INSERT INTO `product_images` (`product_id`, `image_path`) VALUES
(1, 'iphone15.jpg'),
(2, 'macbookpro.jpg'),
(3, 'airpodspro.jpg')
ON DUPLICATE KEY UPDATE image_path=VALUES(image_path);

COMMIT;
