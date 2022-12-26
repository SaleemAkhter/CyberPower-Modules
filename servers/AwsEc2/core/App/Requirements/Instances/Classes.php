<?php

namespace ModulesGarden\Servers\AwsEc2\Core\App\Requirements\Instances;

use ModulesGarden\Servers\AwsEc2\Core\App\Requirements\RequirementsList;
use ModulesGarden\Servers\AwsEc2\Core\App\Requirements\RequirementInterface;
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
        return \ModulesGarden\Servers\AwsEc2\Core\App\Requirements\Handlers\Classes::class;
    }
}
