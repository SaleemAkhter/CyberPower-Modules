ALTER TABLE `#prefix#AvailableImages`
DROP INDEX `image_name_region`,
ADD UNIQUE KEY `image_name_region` (`name`, `region`, `product_id`);
