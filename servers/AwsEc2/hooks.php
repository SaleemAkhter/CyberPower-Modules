<?php

/*
 * Using Cron Manager. Do not edit this file.
 * Add your hooks in /app/hooks/ directory.
 */

require_once __DIR__ . DIRECTORY_SEPARATOR . 'core' . DIRECTORY_SEPARATOR . 'Bootstrap.php';

\ModulesGarden\Servers\AwsEc2\Core\Hook\HookManager::create(__DIR__);
