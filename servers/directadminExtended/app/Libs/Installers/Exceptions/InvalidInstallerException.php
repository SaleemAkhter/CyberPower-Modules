<?php

namespace ModulesGarden\Servers\DirectAdminExtended\App\Libs\Installers\Exceptions;

use ModulesGarden\Servers\DirectAdminExtended\Core\HandlerError\Exceptions\Exception;

/**
 * Description of InvalidInstallerException
 *
 * @author Michal Zarow <michal.za@modulesgarden.com>
 */
class InvalidInstallerException extends Exception
{
    public function __construct($message, $code = 0, $previous = null)
    {
        parent::__construct(self::class, $message, $code, $previous);
    }
}
