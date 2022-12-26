--
-- `#prefix#FunctionsSettings`
--
CREATE TABLE IF NOT EXISTS `#prefix#FunctionsSettings` (
                            `product_id` int(11) NOT NULL,
                            `ftp` varchar(2) NOT NULL DEFAULT '',
                            `emails` varchar(2) NOT NULL DEFAULT '',
                            `databases` varchar(2) NOT NULL DEFAULT '',
                            `subdomains` varchar(2) NOT NULL DEFAULT '',
                            `addon_domains` varchar(2) NOT NULL DEFAULT '',
                            `domain_forwarders` varchar(2) NOT NULL DEFAULT '',
                            `directadmin_login` varchar(2) NOT NULL DEFAULT '',
                            `webmail_login` varchar(2) NOT NULL DEFAULT '',
                            `sitepad_login` varchar(2) NOT NULL DEFAULT '',
                            `webmail_type` int(2) NOT NULL DEFAULT '1',
                            `show_ip` varchar(2) NOT NULL DEFAULT '1',
                            `email_forwarding` varchar(2) NOT NULL DEFAULT '',
                            `parked_domains` varchar(2) NOT NULL DEFAULT '',
                            `cron` varchar(2) NOT NULL DEFAULT '',
                            `change_password` varchar(2) NOT NULL DEFAULT '',
                            `backups` varchar(2) NOT NULL DEFAULT '',
                            `autoresponders` varchar(2) NOT NULL DEFAULT '',
                            `vacation` varchar(2) NOT NULL DEFAULT '',
                            `apachehandlers` varchar(2) NOT NULL DEFAULT '',
                            `emailfilters` varchar(2) NOT NULL DEFAULT '',
                            `mailinglists` varchar(2) NOT NULL DEFAULT '',
                            `filemanager` varchar(2) NOT NULL DEFAULT '',
                            `errorpages` varchar(2) NOT NULL DEFAULT '',
                            `stats` varchar(2) NOT NULL DEFAULT '',
                            `spamassasin` varchar(2) NOT NULL DEFAULT '',
                            `recreate` varchar(2) NOT NULL DEFAULT '',
                            `ftp_endpoints` text NOT NULL,
                            `apps_order_assign` int(1) NOT NULL DEFAULT '0',
                            `apps_config_option_gid` int(11) NOT NULL DEFAULT '0',
                            `apps` varchar(2) NOT NULL DEFAULT '',
                            `apps_installer_type` int(1) NOT NULL DEFAULT '1',
                            `apps_app_name` text NOT NULL,
                            `apps_lang` varchar(10) NOT NULL DEFAULT '',
                            `apps_installation` varchar(2) NOT NULL DEFAULT '',
                            `apps_backups` varchar(2) NOT NULL DEFAULT '',
                            `login_phpmyadmin` varchar(2) NOT NULL DEFAULT '',
                            `usage_disk` varchar(2) NOT NULL DEFAULT '',
                            `usage_bandwidth` varchar(2) NOT NULL DEFAULT '',
                            `usage_email` varchar(2) NOT NULL DEFAULT '',
                            `usage_ftp` varchar(2) NOT NULL DEFAULT '',
                            `usage_database` varchar(2) NOT NULL DEFAULT '',
                            `backup_directories` text NOT NULL,
                            `ssl` varchar(2) NOT NULL DEFAULT '',
                            `ssh` varchar(2) NOT NULL DEFAULT '',
                            `ssl_allow_encrypt` varchar(2) NOT NULL DEFAULT '',
                            `perl_modules` varchar(2) NOT NULL DEFAULT '',
                            `auto_apps_backups` varchar(2) NOT NULL DEFAULT '',
                            `auto_apps_backups_default` varchar(2) NOT NULL DEFAULT '',
                            `catch_emails` varchar(2) NOT NULL DEFAULT '',
                            `manage_dns` varchar(2) NOT NULL DEFAULT '',
                            `hotlink_protection` varchar(2) NOT NULL DEFAULT '',
                            `protected_directories` varchar(2) NOT NULL DEFAULT '',
                            `wordpress_manager` varchar(2) NOT NULL DEFAULT '',
                            PRIMARY KEY (`product_id`)
        ) ENGINE=InnoDB  DEFAULT CHARSET=#charset# DEFAULT COLLATE #collation#;

--
-- `#prefix#Product`
--
CREATE TABLE IF NOT EXISTS `#prefix#ProductSettings` (
                        `id` int NOT NULL AUTO_INCREMENT UNIQUE,
                        `setting` varchar(100) NOT NULL,
                        `pid` int(10) unsigned NOT NULL,
                        `value` text,
                        PRIMARY KEY (`setting`,`pid`)
        ) ENGINE=InnoDB  DEFAULT CHARSET=#charset# DEFAULT COLLATE #collation#;

--
-- `#prefix#FTPEndPoints`
--
CREATE TABLE IF NOT EXISTS `#prefix#FTPEndPoints` (
                        `id` int NOT NULL AUTO_INCREMENT UNIQUE,
                        `product_id` int(11) NOT NULL,
                        `server_id` int(11) NOT NULL,
                        `name` varchar(255) NOT NULL,
                        `host` varchar(255) NOT NULL,
                        `port` int(11) NOT NULL,
                        `user` varchar(100) NOT NULL,
                        `password` varchar(100) NOT NULL,
                        `path` varchar(255) NOT NULL,
                        `admin_access` varchar(2) NOT NULL,
                        `enable_restore` varchar(2) NOT NULL,
                        PRIMARY KEY (`id`)
        ) ENGINE=InnoDB  DEFAULT CHARSET=#charset# DEFAULT COLLATE #collation#;

--
-- `#prefix#Backups`
--
CREATE TABLE IF NOT EXISTS `#prefix#Backups` (
                        `id` int NOT NULL AUTO_INCREMENT UNIQUE,
                        `product_id` int(11) NOT NULL,
                        `server_id` int(11) NOT NULL,
                        `name` varchar(255) NOT NULL,
                        `path` varchar(255) NOT NULL,
                        `admin_access` varchar(2) NOT NULL,
                        `enable_restore` varchar(2) NOT NULL,
                        PRIMARY KEY (`id`)
        ) ENGINE=InnoDB  DEFAULT CHARSET=#charset# DEFAULT COLLATE #collation#;
