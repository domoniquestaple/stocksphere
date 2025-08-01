SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";

-- --------------------------------------------------------
-- Table structure for table `requests`
-- --------------------------------------------------------
CREATE TABLE `requests` (
  `id` int(11) NOT NULL,
  `requested_by` varchar(150) DEFAULT NULL,
  `product_id` int(11) NOT NULL,
  `quantity` int(11) DEFAULT NULL,
  `date` date DEFAULT NULL,
  `updated_at` datetime NOT NULL,
  `created_at` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------
-- Indexes for table `requests`
-- --------------------------------------------------------
ALTER TABLE `requests`
  ADD PRIMARY KEY (`id`),
  ADD KEY `product_id` (`product_id`);

-- --------------------------------------------------------
-- AUTO_INCREMENT for table `requests`
-- --------------------------------------------------------
ALTER TABLE `requests`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

-- --------------------------------------------------------
-- Constraints for table `requests`
-- --------------------------------------------------------
ALTER TABLE `requests`
  ADD CONSTRAINT `requests_ibfk_1` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`);

COMMIT;
