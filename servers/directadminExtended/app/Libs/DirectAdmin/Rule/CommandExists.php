<?php
/**
 * Created by PhpStorm.
 * User: User
 * Date: 2018-07-17
 * Time: 13:02
 */
namespace ModulesGarden\Servers\DirectAdminExtended\App\Libs\DirectAdmin\Rule;

use ModulesGarden\Servers\DirectAdminExtended\App\Libs\DirectAdmin\Interfaces\Rule;
use ModulesGarden\Servers\DirectAdminExtended\App\Libs\DirectAdmin\ErrorHandler\Exceptions;

class CommandExists implements Rule
{
    public function isSatisfiedBy($argument)
    {
        if(!class_exists('ModulesGarden\Servers\DirectAdminExtended\App\Libs\DirectAdmin\Command\\' . ucfirst($argument)))
        {
            throw new Exceptions\InvalidCommandException('Invalid API command.' .'ModulesGarden\Servers\DirectAdminExtended\App\Libs\DirectAdmin\Command\\' . ucfirst($argument));
        }
    }
}
