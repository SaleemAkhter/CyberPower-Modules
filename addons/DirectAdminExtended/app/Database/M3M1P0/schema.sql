RENAME TABLE IF EXISTS `#prefix#Product` TO `#prefix#ProductSettings`;

ALTER TABLE `#prefix#ProductSettings` CHANGE `product_id` `pid` INT(10) UNSIGNED NOT NULL;

