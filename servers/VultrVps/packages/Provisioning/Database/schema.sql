--
-- `#prefix#ProductSettings`
--
CREATE TABLE IF NOT EXISTS `#prefix#ProductSettings` (
    `product_id` int(11),
    `setting`  varchar (255),
    `value`  text,
    PRIMARY KEY (`setting`,`product_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=#charset# DEFAULT COLLATE #collation#;