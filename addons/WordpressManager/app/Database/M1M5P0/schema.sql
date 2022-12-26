--
-- `#prefix#CustomTheme`
--
CREATE TABLE IF NOT EXISTS `#prefix#CustomTheme` (
    `id`            int(10) unsigned NOT NULL AUTO_INCREMENT,
    `name`         VARCHAR(255)     NOT NULL,
    `description`  TEXT,
    `url`  TEXT,
    `version`  VARCHAR(16),
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
    `version`  VARCHAR(16),
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