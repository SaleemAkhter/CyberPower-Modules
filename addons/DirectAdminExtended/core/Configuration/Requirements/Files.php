<?php

namespace ModulesGarden\DirectAdminExtended\Core\Configuration\Requirements;

/**
 * Description of RemoveFiles
 *
 * @author INBSX-37H
 */
class Files extends \ModulesGarden\DirectAdminExtended\Core\App\Requirements\Instances\Files
{
    protected $requirementsList = [
        [
            self::PATH => self::MODULE_PATH . '/storage',
            self::TYPE => self::IS_WRITABLE
        ],
        [
            self::PATH => self::MODULE_PATH . '/storage/app',
            self::TYPE => self::IS_WRITABLE
        ],
        [
            self::PATH => self::MODULE_PATH . '/storage/crons',
            self::TYPE => self::IS_WRITABLE
        ],
        [
            self::PATH => self::MODULE_PATH . '/storage/logs',
            self::TYPE => self::IS_WRITABLE
        ]
    ];
}
