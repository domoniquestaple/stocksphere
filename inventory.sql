SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";

-- Database: `inventory`

-- --------------------------------------------------------
-- Table structure for table `order_product`
-- --------------------------------------------------------
CREATE TABLE `order_product` (
  `id` int(11) NOT NULL,
  `supplier` int(11) DEFAULT NULL,
  `product` int(11) DEFAULT NULL,
  `quantity_ordered` int(11) DEFAULT NULL,
  `quantity_received` int(11) DEFAULT NULL,
  `quantity_remaining` int(11) DEFAULT NULL,
  `status` varchar(20) DEFAULT NULL,
  `batch` varchar(100) DEFAULT NULL,
  `created_by` int(11) DEFAULT NULL,
  `requested_by` varchar(100) NOT NULL,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------
-- Table structure for table `order_product_history`
-- --------------------------------------------------------
CREATE TABLE `order_product_history` (
  `id` int(11) NOT NULL,
  `order_product_id` int(11) NOT NULL,
  `qty_received` int(11) NOT NULL,
  `date_received` datetime NOT NULL,
  `date_updated` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------
-- Table structure for table `products`
-- --------------------------------------------------------
CREATE TABLE `products` (
  `id` int(11) NOT NULL,
  `product_name` varchar(191) NOT NULL,
  `description` varchar(200) DEFAULT NULL,
  `img` varchar(100) DEFAULT NULL,
  `price` decimal(10,2) NOT NULL,
  `stock` int(11) NOT NULL,
  `created_by` int(11) NOT NULL,
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------
-- Table structure for table `productsuppliers`
-- --------------------------------------------------------
CREATE TABLE `productsuppliers` (
  `id` int(11) NOT NULL,
  `supplier` int(11) NOT NULL,
  `product` int(11) NOT NULL,
  `updated_at` datetime NOT NULL,
  `created_at` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------
-- Table structure for table `requests`
-- --------------------------------------------------------
CREATE TABLE `requests` (
  `id` int(11) NOT NULL,
  `requested_by` varchar(150) DEFAULT NULL,
  `product_id` int(11) NOT NULL,
  `quantity` int(11) DEFAULT NULL,
  `date` date DEFAULT NULL,
  `status` varchar(50) DEFAULT NULL,
  `updated_at` datetime NOT NULL,
  `created_at` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------
-- Table structure for table `suppliers`
-- --------------------------------------------------------
CREATE TABLE `suppliers` (
  `id` int(11) NOT NULL,
  `supplier_name` varchar(191) NOT NULL,
  `supplier_location` varchar(191) NOT NULL,
  `email` varchar(100) NOT NULL,
  `created_by` int(11) NOT NULL,
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------
-- Table structure for table `users`
-- --------------------------------------------------------
CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `first_name` varchar(50) NOT NULL,
  `last_name` varchar(50) NOT NULL,
  `password` varchar(2000) NOT NULL,
  `email` varchar(50) NOT NULL,
  `permissions` varchar(5000) DEFAULT NULL,
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------
-- Indexes and constraints
-- --------------------------------------------------------
ALTER TABLE `order_product`
  ADD PRIMARY KEY (`id`),
  ADD KEY `supplier` (`supplier`),
  ADD KEY `product` (`product`),
  ADD KEY `created_by` (`created_by`);

ALTER TABLE `order_product_history`
  ADD PRIMARY KEY (`id`),
  ADD KEY `order_product_id` (`order_product_id`);

ALTER TABLE `products`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_user` (`created_by`);

ALTER TABLE `productsuppliers`
  ADD PRIMARY KEY (`id`),
  ADD KEY `product` (`product`),
  ADD KEY `supplier` (`supplier`);

ALTER TABLE `requests`
  ADD PRIMARY KEY (`id`),
  ADD KEY `product_id` (`product_id`);

ALTER TABLE `suppliers`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_created_by` (`created_by`);

ALTER TABLE `users`
  ADD PRIMARY KEY (`id`);

-- --------------------------------------------------------
-- AUTO_INCREMENT settings
-- --------------------------------------------------------
ALTER TABLE `order_product` MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;
ALTER TABLE `order_product_history` MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;
ALTER TABLE `products` MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;
ALTER TABLE `productsuppliers` MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;
ALTER TABLE `requests` MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
ALTER TABLE `suppliers` MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;
ALTER TABLE `users` MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=42;

-- --------------------------------------------------------
-- Foreign key constraints
-- --------------------------------------------------------
ALTER TABLE `order_product`
  ADD CONSTRAINT `order_product_ibfk_1` FOREIGN KEY (`supplier`) REFERENCES `suppliers` (`id`),
  ADD CONSTRAINT `order_product_ibfk_2` FOREIGN KEY (`product`) REFERENCES `products` (`id`),
  ADD CONSTRAINT `order_product_ibfk_3` FOREIGN KEY (`created_by`) REFERENCES `users` (`id`);

ALTER TABLE `order_product_history`
  ADD CONSTRAINT `order_product_history_ibfk_1` FOREIGN KEY (`order_product_id`) REFERENCES `order_product` (`id`);

ALTER TABLE `products`
  ADD CONSTRAINT `fk_user` FOREIGN KEY (`created_by`) REFERENCES `users` (`id`);

ALTER TABLE `productsuppliers`
  ADD CONSTRAINT `productsuppliers_ibfk_1` FOREIGN KEY (`product`) REFERENCES `products` (`id`),
  ADD CONSTRAINT `productsuppliers_ibfk_2` FOREIGN KEY (`supplier`) REFERENCES `suppliers` (`id`);

ALTER TABLE `requests`
  ADD CONSTRAINT `requests_ibfk_1` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`);

ALTER TABLE `suppliers`
  ADD CONSTRAINT `fk_created_by` FOREIGN KEY (`created_by`) REFERENCES `users` (`id`);

COMMIT;
