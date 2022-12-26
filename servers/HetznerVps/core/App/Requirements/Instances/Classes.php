<?php

namespace ModulesGarden\Servers\HetznerVps\Core\App\Requirements\Instances;

use ModulesGarden\Servers\HetznerVps\Core\App\Requirements\RequirementsList;
use ModulesGarden\Servers\HetznerVps\Core\App\Requirements\RequirementInterface;
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
        return \ModulesGarden\Servers\HetznerVps\Core\App\Requirements\Handlers\Classes::class;
    }
}
