<?php

namespace ModulesGarden\Servers\DirectAdminExtended\App\Traits;

use ModulesGarden\Servers\DirectAdminExtended\App\Libs\DirectAdmin\DirectAdmin;
use ModulesGarden\Servers\DirectAdminExtended\App\Libs\FileManager;
use ModulesGarden\Servers\DirectAdminExtended\Core\Traits\Params;
use ModulesGarden\Servers\DirectAdminExtended\Core\UI\Traits\WhmcsParams;

/**
 * DirectAdminAPIComponent trait
 *
 * @author Michal Zarow <michal.za@modulesgarden.com>
 */
trait DirectAdminAPIComponent
{
    use WhmcsParams;

    protected $api;
    protected $userApi;
    protected $fileManager;

    protected function loadRequiredParams ()
    {
       return $this->getWhmcsParamsByKeys([
           'serverusername',
           'serverpassword',
           'serveraccesshash',
           'serverip',
           'serverhostname',
           'serverport',
           'serversecure',
           'username',
           'password',
           'pid'
       ]);
    }
    protected function loadApi(array $params = [])
    {
        $params     = $params ? $params : $this->loadRequiredParams();
        $this->api  = new DirectAdmin($params);

        return $this;
    }

    protected function loadUserApi(array $params = [])
    {
        $params         = $params ? $params : $this->loadRequiredParams();
        $this->userApi  = (new DirectAdmin($params))->setUserMode(true);

        return $this;
    }

    protected function loadFileManager(array $params = [])
    {
        $params             = $params ? $params : $this->loadRequiredParams();
        $directAdminAPI     = (new DirectAdmin($params))->setUserMode(true);
        $this->fileManager  = new FileManager\FileManager(new FileManager\Provider\DirectAdmin($directAdminAPI));

        return $this;
    }
}
