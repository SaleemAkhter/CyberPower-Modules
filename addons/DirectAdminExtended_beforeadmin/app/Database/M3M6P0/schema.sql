ALTER TABLE `#prefix#FunctionsSettings`
ADD `hotlink_protection` VARCHAR(2) DEFAULT '' NOT NULL AFTER `manage_dns`;
ALTER TABLE `#prefix#FunctionsSettings`
ADD `protected_directories` VARCHAR(2) DEFAULT '' NOT NULL AFTER `hotlink_protection`;
