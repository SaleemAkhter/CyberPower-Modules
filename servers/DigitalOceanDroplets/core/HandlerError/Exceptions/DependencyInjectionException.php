<?php

namespace ModulesGarden\Servers\DigitalOceanDroplets\Core\HandlerError\Exceptions;

/**
 * Description of ServiceLocatorException
 *
 * @author Rafał Ossowski <rafal.os@modulesgarden.com>
 */
class DependencyInjectionException extends MGModuleException
{
    public function __construct($class = '', $message = '', $code = 0, $previous = null)
    {
        parent::__construct($class, $message, $code, $previous);
    }
}
