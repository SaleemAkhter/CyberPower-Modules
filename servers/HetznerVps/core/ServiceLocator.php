<?php

namespace ModulesGarden\Servers\HetznerVps\Core;

/**
 * Class ServiceLocator
 * @package ModulesGarden\Servers\HetznerVps\Core
 * @TODO remove that class //MM
 */
class ServiceLocator extends  \ModulesGarden\Servers\HetznerVps\Core\DependencyInjection\DependencyInjection
{
    /**
     * @var bool
     * @TODO - move it to different class //MM
     */
    public static $isDebug = false;
}
