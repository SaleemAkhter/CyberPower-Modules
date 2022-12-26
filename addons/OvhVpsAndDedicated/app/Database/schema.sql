CREATE TABLE IF NOT EXISTS `#prefix#Machine` (
    `name`  varchar (64),
    `setting` varchar (255),
    `value` varchar (255),
     UNIQUE KEY `uniq` (`name`, `setting`)
) ENGINE=InnoDB DEFAULT CHARSET=#charset# DEFAULT COLLATE #collation#;


CREATE TABLE IF NOT EXISTS `#prefix#ReuseProducts` (
    `name`  varchar (64),
    `productId` INT(11)
) ENGINE=InnoDB DEFAULT CHARSET=#charset# DEFAULT COLLATE #collation#;

CREATE TABLE IF NOT EXISTS `#prefix#ServerSettings` (
    `server_id` int(11),
    `setting`  varchar (255),
    `value`  varchar (255),
    UNIQUE KEY `uniq` (`server_id`, `setting`)
) ENGINE=InnoDB DEFAULT CHARSET=#charset# DEFAULT COLLATE #collation#;

CREATE TABLE IF NOT EXISTS `#prefix#ProductConfiguration` (
    `id` int(11) NOT NULL AUTO_INCREMENT,
    `product_id` int(11),
    `setting`  varchar (255),
    `value`  varchar (255),
    `productId` INT(11),
    primary key (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=#charset# DEFAULT COLLATE #collation#;

CREATE TABLE IF NOT EXISTS `#prefix#MailboxRead` (
    `id` int(11) NOT NULL AUTO_INCREMENT,
    `hosting_id` int(11),
    `product_id` int(11),
    `mail_id` int(11),
    `mail`  varchar (255),
    `status`  varchar (64),
    `message` text,
    primary key (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=#charset# DEFAULT COLLATE #collation#;

CREATE TABLE IF NOT EXISTS `#prefix#Orders` (
    `id` int(11) NOT NULL AUTO_INCREMENT,
    `hosting_id` int(11),
    `order_id` int(11),
    primary key (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=#charset# DEFAULT COLLATE #collation#;