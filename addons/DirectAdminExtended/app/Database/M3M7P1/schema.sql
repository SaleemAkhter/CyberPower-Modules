ALTER TABLE `#prefix#FunctionsSettings`
ADD `add_user` VARCHAR(2) DEFAULT '' NOT NULL AFTER `wordpress_manager`;
ALTER TABLE `#prefix#FunctionsSettings`
ADD `manage_users` VARCHAR(2) DEFAULT '' NOT NULL AFTER `wordpress_manager`;

ALTER TABLE `#prefix#FunctionsSettings`
ADD `add_package` VARCHAR(2) DEFAULT '' NOT NULL AFTER `wordpress_manager`;
ALTER TABLE `#prefix#FunctionsSettings`
ADD `manage_packages` VARCHAR(2) DEFAULT '' NOT NULL AFTER `wordpress_manager`;
ALTER TABLE `#prefix#FunctionsSettings`
ADD `manage_ips` VARCHAR(2) DEFAULT '' NOT NULL AFTER `wordpress_manager`;
ALTER TABLE `#prefix#FunctionsSettings`
ADD `change_passwords` VARCHAR(2) DEFAULT '' NOT NULL AFTER `wordpress_manager`;
ALTER TABLE `#prefix#FunctionsSettings`
ADD `nameservers` VARCHAR(2) DEFAULT '' NOT NULL AFTER `wordpress_manager`;
ALTER TABLE `#prefix#FunctionsSettings`
ADD `usage_user` VARCHAR(2) DEFAULT '' NOT NULL AFTER `usage_database`;
ALTER TABLE `#prefix#FunctionsSettings`
ADD `usage_domain` VARCHAR(2) DEFAULT '' NOT NULL AFTER `usage_database`;
