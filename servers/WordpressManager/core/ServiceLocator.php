<?php

namespace ModulesGarden\WordpressManager\Core;

/**
 * Class ServiceLocator
 * @package ModulesGarden\WordpressManager\Core
 * @TODO remove that class //MM
 */
class ServiceLocator extends  \ModulesGarden\WordpressManager\Core\DependencyInjection\DependencyInjection
{
    /**
     * @var bool
     * @TODO - move it to different class //MM
     */
    public static $isDebug = true;
}
