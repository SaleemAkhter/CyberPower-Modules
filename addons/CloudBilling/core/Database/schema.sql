--
-- `#prefix#ModuleSettings`
--
CREATE TABLE IF NOT EXISTS `#prefix#ModuleSettings` (
    `setting`              VARCHAR(64) NOT NULL UNIQUE,
    `value`            TEXT NOT NULL,
    PRIMARY KEY (`setting`)
) ENGINE=InnoDB DEFAULT CHARSET=#charset# DEFAULT COLLATE #collation#;


CREATE TABLE IF NOT EXISTS `#prefix#Commands` (
    `name`             VARCHAR(64) NOT NULL UNIQUE,
    `uuid`             VARCHAR(64) NOT NULL UNIQUE,
    `parent_uuid`      VARCHAR(64) DEFAULT NULL,
    `status`           enum('stopped', 'running', 'error') DEFAULT 'stopped',
    `action`           enum('none', 'stop', 'reboot') DEFAULT 'none',
    `params`           TEXT NOT NULL,
    `created_at`       timestamp DEFAULT CURRENT_TIMESTAMP,
    `updated_at`       timestamp,
    PRIMARY KEY (`name`)
) ENGINE=InnoDB DEFAULT CHARSET=#charset# DEFAULT COLLATE #collation#;