<?php

namespace ModulesGarden\Servers\VultrVps\Core\App\Requirements\Instances;

use ModulesGarden\Servers\VultrVps\Core\App\Requirements\RequirementInterface;
use ModulesGarden\Servers\VultrVps\Core\App\Requirements\RequirementsList;

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
        return \ModulesGarden\Servers\VultrVps\Core\App\Requirements\Handlers\PhpExtensions::class;
    }
}
