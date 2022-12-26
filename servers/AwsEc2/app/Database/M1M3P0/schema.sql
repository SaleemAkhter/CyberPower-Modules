--
-- `#prefix#SshKeys`
--
CREATE TABLE IF NOT EXISTS `#prefix#SshKeys` (
    `id` int(10) unsigned  NOT NULL AUTO_INCREMENT,
    `service_id` int(25) unsigned  NOT NULL,
    `public_key` TEXT NOT NULL,
    `private_key` TEXT  NOT NULL,
    `salt` varchar(255) NOT NULL,
    PRIMARY KEY (`id`),
    KEY `service_id` (`service_id`)
) ENGINE=InnoDB DEFAULT CHARSET=#charset# DEFAULT COLLATE #collation#;