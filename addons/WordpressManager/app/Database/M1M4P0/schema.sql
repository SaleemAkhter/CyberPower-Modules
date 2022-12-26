--
-- ALTER TABLE `#prefix#Installations`
--
ALTER TABLE `#prefix#Installations` ADD `domain_id` int(10) NULL AFTER `username`;
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