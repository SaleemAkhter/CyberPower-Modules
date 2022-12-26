
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