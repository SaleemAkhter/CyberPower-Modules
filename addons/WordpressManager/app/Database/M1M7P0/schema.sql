--
-- `#prefix#WebsiteDetails`
--
CREATE TABLE IF NOT EXISTS `#prefix#WebsiteDetails` (
    `wpid`            int(10) unsigned NOT NULL ,
    `desktop`           mediumblob,
    `mobile`            mediumblob,
    `screenshot`              blob,
    `created_at`         timestamp,
    `updated_at`         timestamp,
    PRIMARY KEY (`wpid`)
)  DEFAULT CHARSET=#charset# DEFAULT COLLATE #collation#;
