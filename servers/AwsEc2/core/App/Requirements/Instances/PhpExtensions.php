<?php

namespace ModulesGarden\Servers\AwsEc2\Core\App\Requirements\Instances;

use ModulesGarden\Servers\AwsEc2\Core\App\Requirements\RequirementsList;
use ModulesGarden\Servers\AwsEc2\Core\App\Requirements\RequirementInterface;

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
        return \ModulesGarden\Servers\AwsEc2\Core\App\Requirements\Handlers\PhpExtensions::class;
    }
}
