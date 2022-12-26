<?php
namespace ModulesGarden\Servers\HetznerVps\App\UI\Console\Helpers;

use ModulesGarden\Servers\HetznerVps\App\Libs\HetznerVps\Api;
use ModulesGarden\Servers\HetznerVps\Core\Helper\Lang;

/**
 * Created by PhpStorm.
 * User: Mateusz Pawłowski
 * Date: 26.03.2019
 * Time: 13:25
 */

class ConsoleManager
{
    protected $params = [];

    public function __construct($params)
    {
        $this->params = $params;
    }

    public function getVM()
    {
        $api = new Api($this->params);

        $vm = $api->servers()->get($api->getClient()->getServerID());

        if($vm->status !== "running")
        {
            throw new \Exception(Lang::getInstance()->absoluteT('vmShouldBeRunning'));
        }

        return $vm;
    }

    public function getConsole()
    {

        return $this->getVM()->requestConsole()->getResponse();
    }
}