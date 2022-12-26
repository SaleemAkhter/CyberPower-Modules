<?php

namespace ModulesGarden\Servers\VultrVps\Core\Configuration\Addon;

/**
 * Description of AbstractAfter
 *
 * @author Rafał Ossowski <rafal.os@modulesgarden.com>
 */
abstract class AbstractAfter
{
    public function __construct()
    {
        
    }

    abstract public function execute(array $params = []);
}
