<?php

namespace ModulesGarden\Servers\HetznerVps\Core\App\Requirements\Instances;

use ModulesGarden\Servers\HetznerVps\Core\App\Requirements\RequirementsList;
use ModulesGarden\Servers\HetznerVps\Core\App\Requirements\RequirementInterface;

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
        return \ModulesGarden\Servers\HetznerVps\Core\App\Requirements\Handlers\PhpExtensions::class;
    }
}
