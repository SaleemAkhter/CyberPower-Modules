<?php

use \ModulesGarden\Servers\HetznerVps\Core\ModuleConstants;
use \ModulesGarden\Servers\HetznerVps\Core\ServiceLocator;
use \ModulesGarden\Servers\HetznerVps\Core\FileReader\PathValidator;
use \ModulesGarden\Servers\HetznerVps\Core\FileReader\File;
use \ModulesGarden\Servers\HetznerVps\Core\DependencyInjection\Builder;
use \ModulesGarden\Servers\HetznerVps\Core\DependencyInjection\Services;

if (!defined('DS')) define('DS', DIRECTORY_SEPARATOR);
if (!defined('PS')) define('PS', PATH_SEPARATOR);
if (!defined('CRLF')) define('CRLF', "\r\n");

require_once dirname(__DIR__) . DS . "vendor" . DS . "autoload.php";

/**
 * Initialize base values
 */
ModuleConstants::initialize();

/**
 * Initailize DI builder
 */
new Builder();

/**
 * Initialize Services
 */
new Services();

/**
 * Check file permission
 */
if (is_dir(ModuleConstants::getFullPath('storage')) === false 
        || is_writable(ModuleConstants::getFullPath('storage')) === false
        || is_writable(ModuleConstants::getFullPath('storage', 'app')) === false
        || is_writable(ModuleConstants::getFullPath('storage', 'crons')) === false
        || is_writable(ModuleConstants::getFullPath('storage', 'logs')) === false)
{
    File::createPaths(
            ['full' => ModuleConstants::getFullPath('storage'),              'permission' => 0777],
            ['full' => ModuleConstants::getFullPath('storage', 'app'),       'permission' => 0777],
            ['full' => ModuleConstants::getFullPath('storage', 'crons'),     'permission' => 0777],
            ['full' => ModuleConstants::getFullPath('storage', 'logs'),      'permission' => 0777]
    );
}

$pathValidator = new PathValidator();
if (!$pathValidator->validatePath(ModuleConstants::getFullPath('storage', 'logs'), true, true, true))
{
    ServiceLocator::call('errorManager')->addError(
        'Bootstrap',
        PHP_EOL . ServiceLocator::call('lang')->absoluteT('permissionsStorage'),
        ['path' => ModuleConstants::getFullPath('storage')]
    );
}
