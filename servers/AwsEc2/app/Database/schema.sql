--
-- `#prefix#AvailableImages`
--
CREATE TABLE IF NOT EXISTS `#prefix#AvailableImages` (
    `id`          int(10) unsigned NOT NULL AUTO_INCREMENT,
    `image_id`    VARCHAR(128) NOT NULL,
    `product_id`  INT(10) NOT NULL,
    `description` TEXT NOT NULL,
    `name`        varchar(128) NOT NULL,
    `region`      varchar(128) NOT NULL,
    `details`     TEXT NOT NULL,
    PRIMARY KEY (`id`),
    UNIQUE KEY `image_name_region` (`name`, `region`, `product_id`)
) ENGINE=InnoDB DEFAULT CHARSET=#charset# DEFAULT COLLATE #collation#;

CREATE TABLE IF NOT EXISTS `#prefix#SshKeys` (
    `id` int(10) unsigned  NOT NULL AUTO_INCREMENT,
    `service_id` int(25) unsigned  NOT NULL,
    `public_key` varchar(2000) NOT NULL,
    `private_key` varchar(2000)  NOT NULL,
    `salt` varchar(255) NOT NULL,
    PRIMARY KEY (`id`),
    KEY `service_id` (`service_id`)
) ENGINE=InnoDB DEFAULT CHARSET=#charset# DEFAULT COLLATE #collation#;
