<?php

use ModulesGarden\Servers\HetznerVps\Core\Hook\HookManager;

require_once __DIR__ . DIRECTORY_SEPARATOR . 'core' . DIRECTORY_SEPARATOR . 'Bootstrap.php';

HookManager::create(__DIR__);
