--
-- `#prefix#Installations`
--
CREATE TABLE IF NOT EXISTS `#prefix#Installations` (
    `id`            int(10) unsigned NOT NULL AUTO_INCREMENT,
    `user_id`       int(10) unsigned NOT NULL,
    `hosting_id`    int(10) unsigned NOT NULL,
    `relation_id`   VARCHAR(255)     NOT NULL,
    `domain`        VARCHAR(255)     NOT NULL,
    `url`           VARCHAR(255)     NOT NULL,
    `path`          VARCHAR(255)     NOT NULL,
    `version`       VARCHAR(8)           NULL,
    `staging`       VARCHAR(30)          NULL,
    `username`      VARCHAR(255)    NULL,
    `domain_id`     int(10) NULL,
    `auto`  INT(1) unsigned NULL DEFAULT '0',
    `created_at`    timestamp        NOT NULL DEFAULT '0000-00-00 00:00:00',
    `updated_at`    timestamp        NOT NULL DEFAULT '0000-00-00 00:00:00',
    PRIMARY KEY (`id`)
)  DEFAULT CHARSET=#charset# DEFAULT COLLATE #collation#;
--
-- `#prefix#ProductSettings`
--
CREATE TABLE IF NOT EXISTS `#prefix#ProductSettings` (
    `id`               int(10) unsigned NOT NULL AUTO_INCREMENT,
    `product_id`       int(10) unsigned NOT NULL,
    `enable`           int(1)  unsigned NULL     DEFAULT '0',
    `settings`         TEXT             NULL,
    PRIMARY KEY (`id`)
) DEFAULT CHARSET=#charset# DEFAULT COLLATE #collation#;
--
-- `#prefix#PluginsBlocked`
--
CREATE TABLE IF NOT EXISTS `#prefix#PluginsBlocked` (
    `id`            int(10) unsigned NOT NULL AUTO_INCREMENT,
    `product_id`       int(10) unsigned NOT NULL,
    `name`         VARCHAR(255)     NOT NULL,
    `slug`         VARCHAR(255)     NOT NULL,
    PRIMARY KEY (`id`)
)  DEFAULT CHARSET=#charset# DEFAULT COLLATE #collation#;
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
--
-- `#prefix#ThemePackage`
--
CREATE TABLE IF NOT EXISTS `#prefix#ThemePackage` (
    `id`            int(10) unsigned NOT NULL AUTO_INCREMENT,
    `name`         VARCHAR(255)     NOT NULL,
    `description`  TEXT,
    `enable` tinyint(1) DEFAULT 1,
    PRIMARY KEY (`id`)
)  DEFAULT CHARSET=#charset# DEFAULT COLLATE #collation#;
--
-- `#prefix#ThemePackageItem`
--
CREATE TABLE IF NOT EXISTS `#prefix#ThemePackageItem` (
    `id`            int(10) unsigned NOT NULL AUTO_INCREMENT,
    `name`         VARCHAR(255)     NOT NULL,
    `slug`         VARCHAR(255)     NOT NULL,
    `theme_package_id`            int(10) unsigned NOT NULL,
    PRIMARY KEY (`id`),
    FOREIGN KEY (theme_package_id)
    REFERENCES #prefix#ThemePackage(id) ON DELETE  CASCADE
)  DEFAULT CHARSET=#charset# DEFAULT COLLATE #collation#;
--
-- `#prefix#CustomTheme`
--
CREATE TABLE IF NOT EXISTS `#prefix#CustomTheme` (
    `id`            int(10) unsigned NOT NULL AUTO_INCREMENT,
    `name`         VARCHAR(255)     NOT NULL,
    `description`  TEXT,
    `url`  TEXT,
    `version`  VARCHAR(255),
    `enable` tinyint(1) DEFAULT 1,
    PRIMARY KEY (`id`)
)  DEFAULT CHARSET=#charset# DEFAULT COLLATE #collation#;
--
-- `#prefix#CustomPlugin`
--
CREATE TABLE IF NOT EXISTS `#prefix#CustomPlugin` (
    `id`            int(10) unsigned NOT NULL AUTO_INCREMENT,
    `name`         VARCHAR(255)     NOT NULL,
    `description`  TEXT,
    `url`  TEXT,
    `version`  VARCHAR(255),
    `enable` tinyint(1) DEFAULT 1,
    PRIMARY KEY (`id`)
)  DEFAULT CHARSET=#charset# DEFAULT COLLATE #collation#;
--
-- `#prefix#UpdateMails`
--
CREATE TABLE IF NOT EXISTS `#prefix#UpdateMails` (
    `id`            int(11) unsigned NOT NULL AUTO_INCREMENT,
    `user_id`         int(11) unsigned NOT NULL,
    `current_version`  VARCHAR(16) NOT NULL,
    PRIMARY KEY (`id`)
)  DEFAULT CHARSET=#charset# DEFAULT COLLATE #collation#;
--
-- `#prefix#ThemesBlocked`
--
CREATE TABLE IF NOT EXISTS `#prefix#ThemesBlocked` (
    `id`            int(10) unsigned NOT NULL AUTO_INCREMENT,
    `product_id`       int(10) unsigned NOT NULL,
    `name`         VARCHAR(255)     NOT NULL,
    `slug`         VARCHAR(255)     NOT NULL,
    PRIMARY KEY (`id`)
)  DEFAULT CHARSET=#charset# DEFAULT COLLATE #collation#;
--
-- `#prefix#WebsiteDetails`
--
CREATE TABLE IF NOT EXISTS `#prefix#WebsiteDetails` (
    `wpid`            int(10) unsigned NOT NULL ,
    `desktop`           mediumblob NULL,
    `mobile`            mediumblob NULL,
    `screenshot`              blob NULL,
    `created_at`         timestamp,
    `updated_at`         timestamp,
    PRIMARY KEY (`wpid`)
)  DEFAULT CHARSET=#charset# DEFAULT COLLATE #collation#;