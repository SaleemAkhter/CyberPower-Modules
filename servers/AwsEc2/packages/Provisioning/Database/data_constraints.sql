ALTER TABLE `#prefix#AvailableImages`
ADD UNIQUE KEY `image_name_region` (`name`, `region`, `product_id`);
