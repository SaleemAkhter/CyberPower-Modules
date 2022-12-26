<?php
namespace ModulesGarden\Servers\DirectAdminExtended\App\Libs\PasswordGenerator\Submodules;
use ModulesGarden\Servers\DirectAdminExtended\App\Libs\PasswordGenerator\Interfaces\AbstractSubmodule;

/**
 *
 * Created by PhpStorm.
 * User: Tomasz Bielecki ( tomasz.bi@modulesgarden.com )
 * Date: 25.09.19
 * Time: 13:59
 * Class DirectAdmin
 */
class DirectAdmin extends AbstractSubmodule
{
    protected $minLength        = 6;
    protected $maxLength        = 10;
    protected $requiredNumbers  = true;
    protected $requiredChars    = true;
    protected $requiredSpecial  = true;


}