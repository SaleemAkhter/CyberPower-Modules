INSERT INTO `#prefix#SubmoduleSettings` (`submodule`, `setting`, `value`)
    VALUES ('DefaultSubmodule', 'enableSubmodule', 'on')
    ON DUPLICATE KEY UPDATE `value` = 'on';