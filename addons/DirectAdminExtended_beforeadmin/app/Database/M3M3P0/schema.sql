ALTER TABLE #prefix#FunctionsSettings
ADD ssh varchar(2) NOT NULL DEFAULT;

ALTER TABLE `#prefix#FunctionsSettings`
ADD `sitepad_login` VARCHAR(2) DEFAULT 'on' NOT NULL AFTER `webmail_login`;