ALTER TABLE `#prefix#AvailableImages`
ADD `name` varchar(128) NOT NULL;

ALTER TABLE `#prefix#AvailableImages`
ADD `region` varchar(128) NOT NULL;

ALTER TABLE `#prefix#AvailableImages`
ADD UNIQUE KEY `image_name_region` (`name`, `region`);