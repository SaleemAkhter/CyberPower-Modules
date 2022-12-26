<?php

namespace ModulesGarden\OvhVpsAndDedicated\Core\App\Requirements\Instances;

use ModulesGarden\OvhVpsAndDedicated\Core\App\Requirements\RequirementsList;
use ModulesGarden\OvhVpsAndDedicated\Core\App\Requirements\RequirementInterface;

/**
 * Description of PhpExtensions
 *
 * @author INBSX-37H
 */
abstract class PhpExtensions extends RequirementsList implements RequirementInterface
{
    const EXTENSION_NAME = 'extensionName';

    final public function getHandler()
    {
        return \ModulesGarden\OvhVpsAndDedicated\Core\App\Requirements\Handlers\PhpExtensions::class;
    }
}
