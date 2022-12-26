<?php

namespace ModulesGarden\Servers\VultrVps\Core\App\Requirements\Instances;

use ModulesGarden\Servers\VultrVps\Core\App\Requirements\RequirementInterface;
use ModulesGarden\Servers\VultrVps\Core\App\Requirements\RequirementsList;

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
        return \ModulesGarden\Servers\VultrVps\Core\App\Requirements\Handlers\Files::class;
    }
}
