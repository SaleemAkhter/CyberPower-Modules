--
-- `#prefix#pricing_group`
--
CREATE TABLE IF NOT EXISTS `#prefix#pricing_group` (
    `id`          int(10) unsigned NOT NULL AUTO_INCREMENT,
    `name`        VARCHAR(256) NOT NULL,
    `provider`    VARCHAR(256) NOT NULL,
    PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=#charset# DEFAULT COLLATE #collation#;

--
-- `#prefix#pricing`
--
CREATE TABLE IF NOT EXISTS `#prefix#pricing` (
    `id`          int(10) unsigned NOT NULL AUTO_INCREMENT,
    `group_id`    int(10) unsigned NOT NULL,
    `name`        VARCHAR(256) NOT NULL,
    `enabled`   int(1) NOT NULL,
    `margin`    VARCHAR(10) NOT NULL,
    PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=#charset# DEFAULT COLLATE #collation#;

--
-- `#prefix#products_relations`
--
CREATE TABLE IF NOT EXISTS `#prefix#products_relations` (
    `id`          int(10) unsigned NOT NULL AUTO_INCREMENT,
    `product_id`    int(10) unsigned NOT NULL,
    `group_id`    int(10) unsigned NOT NULL,
    `enabled`   int(1) NOT NULL,
    `invoicing_method` VARCHAR(32) NOT NULL,
    PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=#charset# DEFAULT COLLATE #collation#;

--
-- `#prefix#api_credentials`
--
CREATE TABLE IF NOT EXISTS `#prefix#api_credentials` (
    `id`         int(10) unsigned NOT NULL AUTO_INCREMENT,
    `name`       VARCHAR(256) NOT NULL,
    `type`    VARCHAR(256) NOT NULL,
    PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=#charset# DEFAULT COLLATE #collation#;

--
-- `#prefix#api_credentials_details`
--
CREATE TABLE IF NOT EXISTS `#prefix#api_credentials_details` (
    `id`         int(10) unsigned NOT NULL AUTO_INCREMENT,
    `relation_id`    int(10) unsigned NOT NULL,
    `option_name`       VARCHAR(256) NOT NULL,
    `option_value`    TEXT NOT NULL,
    PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=#charset# DEFAULT COLLATE #collation#;

--
-- `#prefix#services_relations`
--
CREATE TABLE IF NOT EXISTS `#prefix#services_relations` (
    `id`          int(10) unsigned NOT NULL AUTO_INCREMENT,
    `service_id`    int(10) unsigned NOT NULL,
    `group_id`    int(10) unsigned NOT NULL,
    `credentials_id`    int(10) unsigned NOT NULL,
    PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=#charset# DEFAULT COLLATE #collation#;

--
-- `#prefix#services_relations_data`
--
CREATE TABLE IF NOT EXISTS `#prefix#services_relations_data` (
    `id`          int(10) unsigned NOT NULL AUTO_INCREMENT,
    `relation_id`    int(10) unsigned NOT NULL,
    `name`       VARCHAR(64) NOT NULL,
    `value`     TEXT,
    PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=#charset# DEFAULT COLLATE #collation#;

--
-- `#prefix#billing_records`
--
CREATE TABLE IF NOT EXISTS `#prefix#billing_records` (
    `id`          int(10) unsigned NOT NULL AUTO_INCREMENT,
    `relation_id`    int(10) unsigned NOT NULL,
    `billing_service_id`    int(10) unsigned NOT NULL,
    `raw_amount`       DECIMAL(18, 10) NOT NULL,
    `amount`       DECIMAL(18, 10) NOT NULL,
    `date`     DATE,
    `invoice_id`    int(10) unsigned NOT NULL,
    PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=#charset# DEFAULT COLLATE #collation#;

--
-- `#prefix#billing_invoices`
--
CREATE TABLE IF NOT EXISTS `#prefix#billing_invoices` (
    `id`          int(10) unsigned NOT NULL AUTO_INCREMENT,
    `service_id`    int(10) unsigned NOT NULL,
    `invoice_id`    int(10) unsigned NOT NULL,
    PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=#charset# DEFAULT COLLATE #collation#;

--
-- `#prefix#paid_api_usage`
--
CREATE TABLE IF NOT EXISTS `#prefix#paid_api_usage` (
    `id`            int(10) unsigned NOT NULL AUTO_INCREMENT,
    `month`         VARCHAR(10) NOT NULL,
    `count`    int(10) unsigned NOT NULL,
    PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=#charset# DEFAULT COLLATE #collation#;

--
-- `#prefix#logs`
--
CREATE TABLE IF NOT EXISTS `#prefix#logs` (
    `id`            int(10) unsigned NOT NULL AUTO_INCREMENT,
    `service_id`    int(10) unsigned NOT NULL,
    `type`          VARCHAR(32) NOT NULL,
    `message`       TEXT,
    `date`          DATETIME,
    PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=#charset# DEFAULT COLLATE #collation#;

--
-- `#prefix#billing_services_replacements`
--
CREATE TABLE IF NOT EXISTS `#prefix#billing_service_replacements` (
    `id`                  int(10) unsigned NOT NULL AUTO_INCREMENT,
    `pricing_group_id`    int(10) unsigned NOT NULL,
    `original_service`    VARCHAR(256) NOT NULL,
    `resulting_service`   VARCHAR(256) NOT NULL,
    PRIMARY KEY (`id`)
    ) ENGINE=InnoDB DEFAULT CHARSET=#charset# DEFAULT COLLATE #collation#;

