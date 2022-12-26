<?php

namespace ModulesGarden\Servers\AwsEc2\Core;

/**
 * Class ServiceLocator
 * @package ModulesGarden\Servers\AwsEc2\Core
 * @TODO remove that class //MM
 */
class ServiceLocator extends  \ModulesGarden\Servers\AwsEc2\Core\DependencyInjection\DependencyInjection
{
    /**
     * @var bool
     * @TODO - move it to different class //MM
     */
    public static $isDebug = false;
}
