ALTER TABLE `#prefix#Installations` ADD `site_name` VARCHAR(500) NULL AFTER `relation_id`;
ALTER TABLE `#prefix#Installations` ADD `additional_data` TEXT NULL AFTER `site_name`;
