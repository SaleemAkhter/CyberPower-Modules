<?php

namespace ModulesGarden\DirectAdminExtended\Core\App\Requirements\Instances;

use ModulesGarden\DirectAdminExtended\Core\App\Requirements\RequirementsList;
use ModulesGarden\DirectAdminExtended\Core\App\Requirements\RequirementInterface;

/**
 * Description of Files
 *
 * @author INBSX-37H
 */
abstract class Files extends RequirementsList implements RequirementInterface
{
    const WHMCS_PATH = ':WHMCS_PATH:';
    const MODULE_PATH = ':MODULE_PATH:';    
    
    const PATH = 'path';
    const TYPE = 'type';
    
    const REMOVE = 'remove';
    const EXISTS = 'exists';
    const IS_WRITABLE = 'isWritable';
    const IS_READABLE = 'isReadable';

    final public function getHandler()
    {
        return \ModulesGarden\DirectAdminExtended\Core\App\Requirements\Handlers\Files::class;
    }
}
