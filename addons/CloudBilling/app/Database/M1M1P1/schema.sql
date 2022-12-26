--
-- `#prefix#billing_services_replacements`
--
CREATE TABLE IF NOT EXISTS `#prefix#billing_service_replacements` (
    `id`                  int(10) unsigned NOT NULL AUTO_INCREMENT,
    `service_id`          int(10) unsigned NOT NULL,
    `original_service`    VARCHAR(256) NOT NULL,
    `resulting_service`   VARCHAR(256) NOT NULL,
    PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=#charset# DEFAULT COLLATE #collation#;
