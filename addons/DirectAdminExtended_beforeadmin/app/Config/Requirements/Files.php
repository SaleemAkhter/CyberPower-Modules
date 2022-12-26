<?php

namespace ModulesGarden\DirectAdminExtended\App\Config\Requirements;

use \ModulesGarden\DirectAdminExtended\Core\App\Requirements\ActionTypes;

/**
 * Description of RemoveFiles
 *
 * @author INBSX-37H
 */
class Files extends \ModulesGarden\DirectAdminExtended\Core\App\Requirements\Instances\Files
{
    protected $fileList = [
        [
            self::PATH => self::MODULE_PATH . '/app/UI/Installations/Templates/modals/installationCreateModal.tpl',
            self::TYPE => self::REMOVE
        ],
        [
            self::PATH => self::MODULE_PATH . '/app/UI/Installations/Templates/modals/importModal.tpl',
            self::TYPE => self::REMOVE
        ]
    ];
}
