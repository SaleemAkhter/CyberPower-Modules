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

class ConnectionParams implements Rule
{
    public function isSatisfiedBy($params)
    {
        if (!isset($params['serverusername']) ||  !$params['serverusername'])
        {
            throw new Exceptions\InvalidParamsException("Server username is empty");
        }
        if ((!isset($params['serverpassword']) && !isset($params['serveraccesshash'])) || (!$params['serverpassword'] && !$params['serveraccesshash']))
        {
            throw new Exceptions\InvalidParamsException("Server password and accesshash fields are empty");
        }
        if ((!isset($params['serverip']) && !isset($params['serverhostname'])) || (!$params['serverip'] && !$params['serverhostname']))
        {
            throw new Exceptions\InvalidParamsException("Server's ip is empty");
        }
    }
}