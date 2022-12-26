ALTER TABLE `#prefix#FunctionsSettings`
ADD `admin_add_user` VARCHAR(2) DEFAULT '' NOT NULL AFTER `wordpress_manager`,
ADD `user_manager` VARCHAR(2) DEFAULT '' NOT NULL AFTER `wordpress_manager`,
ADD `my_users` VARCHAR(2) DEFAULT '' NOT NULL AFTER `wordpress_manager`,
ADD `user_packages` VARCHAR(2) DEFAULT '' NOT NULL AFTER `wordpress_manager`,
ADD `move_between_resellers` VARCHAR(2) DEFAULT '' NOT NULL AFTER `wordpress_manager`,
ADD `create_reseller` VARCHAR(2) DEFAULT '' NOT NULL AFTER `wordpress_manager`,
ADD `list_reseller` VARCHAR(2) DEFAULT '' NOT NULL AFTER `wordpress_manager`,
ADD `reseller_packages` VARCHAR(2) DEFAULT '' NOT NULL AFTER `wordpress_manager`,
ADD `settings` VARCHAR(2) DEFAULT '' NOT NULL AFTER `wordpress_manager`,
ADD `httpd_config` VARCHAR(2) DEFAULT '' NOT NULL AFTER `wordpress_manager`,
ADD `dns_manager` VARCHAR(2) DEFAULT '' NOT NULL AFTER `wordpress_manager`,
ADD `ip_manager` VARCHAR(2) DEFAULT '' NOT NULL AFTER `wordpress_manager`,
ADD `php_config` VARCHAR(2) DEFAULT '' NOT NULL AFTER `wordpress_manager`,
ADD `ssh_key` VARCHAR(2) DEFAULT '' NOT NULL AFTER `wordpress_manager`,
ADD `backup_transfer` VARCHAR(2) DEFAULT '' NOT NULL AFTER `wordpress_manager`,
ADD `brute_force_monitor` VARCHAR(2) DEFAULT '' NOT NULL AFTER `wordpress_manager`,
ADD `process_monitor` VARCHAR(2) DEFAULT '' NOT NULL AFTER `wordpress_manager`,
ADD `mail_queue` VARCHAR(2) DEFAULT '' NOT NULL AFTER `wordpress_manager`,
ADD `service_monitor` VARCHAR(2) DEFAULT '' NOT NULL AFTER `wordpress_manager`,
ADD `system_backup` VARCHAR(2) DEFAULT '' NOT NULL AFTER `wordpress_manager`,
ADD `message` VARCHAR(2) DEFAULT '' NOT NULL AFTER `wordpress_manager`,
ADD `file_manager` VARCHAR(2) DEFAULT '' NOT NULL AFTER `wordpress_manager`,
ADD `file_editor` VARCHAR(2) DEFAULT '' NOT NULL AFTER `wordpress_manager`,
ADD `sys_info` VARCHAR(2) DEFAULT '' NOT NULL AFTER `wordpress_manager`,
ADD `log_viewer` VARCHAR(2) DEFAULT '' NOT NULL AFTER `wordpress_manager`,
ADD `plugin_manager` VARCHAR(2) DEFAULT '' NOT NULL AFTER `wordpress_manager`,
ADD `firewall` VARCHAR(2) DEFAULT '' NOT NULL AFTER `wordpress_manager`,
ADD `custom_build` VARCHAR(2) DEFAULT '' NOT NULL AFTER `wordpress_manager`;
