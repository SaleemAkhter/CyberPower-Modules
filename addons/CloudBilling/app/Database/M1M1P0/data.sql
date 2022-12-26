ALTER TABLE `#prefix#api_credentials_details`
MODIFY COLUMN `option_value` TEXT;

REPLACE INTO `#prefix#ModuleSettings` (setting, value)
values('cronSetup', 1)