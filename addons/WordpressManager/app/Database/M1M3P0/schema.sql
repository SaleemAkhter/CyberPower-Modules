
--
-- `#prefix#Installations`
--
ALTER TABLE `#prefix#Installations` ADD `staging` VARCHAR(10) NULL AFTER `version`;
ALTER TABLE `#prefix#Installations` ADD `username` VARCHAR(255) NULL AFTER `staging`;
--
-- `#prefix#PluginPackage` 
--
CREATE TABLE IF NOT EXISTS `#prefix#PluginPackage` (
    `id`            int(10) unsigned NOT NULL AUTO_INCREMENT,
    `name`         VARCHAR(255)     NOT NULL,
    `description`  TEXT, 
    `enable` tinyint(1) DEFAULT 1,
    PRIMARY KEY (`id`)
)  DEFAULT CHARSET=#charset# DEFAULT COLLATE #collation#;


--
-- `#prefix#PluginPackageItem`
--
CREATE TABLE IF NOT EXISTS `#prefix#PluginPackageItem` (
    `id`            int(10) unsigned NOT NULL AUTO_INCREMENT,
    `name`         VARCHAR(255)     NOT NULL,
    `slug`         VARCHAR(255)     NOT NULL,
    `plugin_package_id`            int(10) unsigned NOT NULL,
    PRIMARY KEY (`id`),
    FOREIGN KEY (plugin_package_id)
    REFERENCES #prefix#PluginPackage(id) ON DELETE  CASCADE
)  DEFAULT CHARSET=#charset# DEFAULT COLLATE #collation#;

--
-- `#prefix#Commands`
--
CREATE TABLE IF NOT EXISTS `#prefix#Commands` (
    `name`             VARCHAR(64) NOT NULL UNIQUE,
    `uuid`             VARCHAR(64) NOT NULL UNIQUE,
    `parent_uuid`      VARCHAR(64) DEFAULT NULL,
    `status`           enum('stopped', 'running', 'error') DEFAULT 'stopped',
    `action`           enum('none', 'stop', 'reboot') DEFAULT 'none',
    `params`           TEXT NOT NULL,
    `created_at`       timestamp DEFAULT CURRENT_TIMESTAMP,
    `updated_at`       timestamp,
    PRIMARY KEY (`name`)
) ENGINE=InnoDB DEFAULT CHARSET=#charset# DEFAULT COLLATE #collation#;


--
-- `#prefix#InstanceImage` 
--
CREATE TABLE IF NOT EXISTS `#prefix#InstanceImage` (
    `id`            int(10) unsigned NOT NULL AUTO_INCREMENT,
    `name`         VARCHAR(255)     NOT NULL,
    `soft`    int(10) unsigned NOT NULL,
    `domain`     VARCHAR(255)     NOT NULL,
    `protocol`     VARCHAR(6)     NOT NULL,
    `server_host` VARCHAR(255)     NOT NULL,
    `port`      INT(8)     NOT NULL,
    `ftp_user` VARCHAR(255)     NOT NULL,
    `ftp_pass` VARCHAR(255)     NOT NULL,
    `ftp_path` VARCHAR(255)     NOT NULL,
    `installed_path` VARCHAR(255)     NULL,
    `installation_id`            int(10) NOT NULL DEFAULT 0,
    `enable` tinyint(1) DEFAULT 1,
    `user_id` int(10) NOT NULL DEFAULT 0,
    `created_at`    timestamp        NOT NULL DEFAULT '0000-00-00 00:00:00',
    `updated_at`    timestamp        NOT NULL DEFAULT '0000-00-00 00:00:00',
    PRIMARY KEY (`id`)
)  DEFAULT CHARSET=#charset# DEFAULT COLLATE #collation#;

CREATE TABLE IF NOT EXISTS `#prefix#Job` (
    `id` int(10) unsigned  NOT NULL AUTO_INCREMENT,
    `retry_after` datetime NOT NULL,
    `retry_count` int(10) unsigned NOT NULL,
    `job` varchar(255) NOT NULL,
    `data` text,
    `queue` varchar(32) DEFAULT 'default',
    `status` varchar(32) NOT NULL,
    `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
    `updated_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
    PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=#charset# DEFAULT COLLATE #collation#;

CREATE TABLE IF NOT EXISTS `#prefix#JobLog` (
    `id` int(10) unsigned  NOT NULL AUTO_INCREMENT,
    `job_id` int(10) unsigned NOT NULL,
    `type` varchar(32) NOT NULL,
    `message` varchar(512) NOT NULL,
    `additional` text,
    `created_at` datetime NOT NULL,
    `updated_at` datetime NOT NULL,
    PRIMARY KEY (`id`),
    KEY `job_id` (`job_id`),
    KEY `type` (`type`)
) ENGINE=InnoDB DEFAULT CHARSET=#charset# DEFAULT COLLATE #collation#;