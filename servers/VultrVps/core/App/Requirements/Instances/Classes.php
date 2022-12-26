<?php

namespace ModulesGarden\Servers\VultrVps\Core\App\Requirements\Instances;

use ModulesGarden\Servers\VultrVps\Core\App\Requirements\RequirementInterface;
use ModulesGarden\Servers\VultrVps\Core\App\Requirements\RequirementsList;

/**
 * Description of Classes
 *
 * @author INBSX-37H
 */
abstract class Classes extends RequirementsList implements RequirementInterface
{
    const CLASS_NAME = 'className';

    final public function getHandler()
    {
        return \ModulesGarden\Servers\VultrVps\Core\App\Requirements\Handlers\Classes::class;
    }
}
