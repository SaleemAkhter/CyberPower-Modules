--
-- `#prefix#ElasticIp`
--
CREATE TABLE IF NOT EXISTS `#prefix#ElasticIps` (
    `id` int(10) unsigned  NOT NULL AUTO_INCREMENT,
    `service_id` int(25) unsigned  NOT NULL,
    `elastic_ip` TEXT NOT NULL,
    `allocation_id` TEXT  NOT NULL,
    PRIMARY KEY (`id`),
    KEY `service_id` (`service_id`)
) ENGINE=InnoDB DEFAULT CHARSET=#charset# DEFAULT COLLATE #collation#;

CREATE TABLE IF NOT EXISTS `#prefix#PrivateIps` (
    `id` int(10) unsigned  NOT NULL AUTO_INCREMENT,
    `service_id` int(25) unsigned  NOT NULL,
    `private_ip` TEXT NOT NULL,
    `allocation_id` TEXT  NOT NULL,
    `salt` varchar(255) NOT NULL,
    PRIMARY KEY (`id`),
    KEY `service_id` (`service_id`)
) ENGINE=InnoDB DEFAULT CHARSET=#charset# DEFAULT COLLATE #collation#;

CREATE TABLE IF NOT EXISTS `#prefix#NetworkInterfaces` (
    `id` int(10) unsigned  NOT NULL AUTO_INCREMENT,
    `service_id` int(25) unsigned  NOT NULL,
    `allocation_id` TEXT NOT NULL,
    PRIMARY KEY (`id`),
    KEY `service_id` (`service_id`)
) ENGINE=InnoDB DEFAULT CHARSET=#charset# DEFAULT COLLATE #collation#;
