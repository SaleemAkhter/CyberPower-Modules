<?php
/**
 * Created by PhpStorm.
 * User: User
 * Date: 2018-07-17
 * Time: 13:03
 */
namespace ModulesGarden\Servers\DirectAdminExtended\App\Libs\DirectAdmin\Interfaces;

interface Rule
{
    /**
     * validating method
     *
     * @throw ModulesGarden\Servers\DirectAdminExtended\App\Libs\DirectAdmin\ErrorHandler\Exceptions
     */
    public function isSatisfiedBy($argument);
}