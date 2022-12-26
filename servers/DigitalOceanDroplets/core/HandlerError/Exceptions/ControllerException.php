<?php

namespace ModulesGarden\Servers\DigitalOceanDroplets\Core\HandlerError\Exceptions;

/**
 * Description of ControllerException
 *
 * @author Rafał Ossowski <rafal.os@modulesgarden.com>
 */
class ControllerException extends MGModuleException
{
    public function __construct($class, $message, $code, $previous = null)
    {
        parent::__construct($class, $message, $code, $previous);
    }
}
