<?php

namespace ModulesGarden\Servers\OvhVpsAndDedicated\Core\App\Requirements\Instances;

/**
 * Description of Files
 *
 * @author INBSX-37H
 */
abstract class Files implements \ModulesGarden\Servers\OvhVpsAndDedicated\Core\App\Requirements\RequirementInterface
{
    const WHMCS_PATH = ':WHMCS_PATH:';
    const MODULE_PATH = ':MODULE_PATH:';    
    
    const PATH = 'path';
    const TYPE = 'type';
    
    const REMOVE = 'remove';
    const EXISTS = 'exists';
    const IS_WRITABLE = 'isWritable';
    const IS_READABLE = 'isReadable';
    
    protected $fileList = [];
    
    final public function getHandler()
    {
        return new \ModulesGarden\Servers\OvhVpsAndDedicated\Core\App\Requirements\Handlers\Files($this->fileList);
    }
}
