<?php

namespace ModulesGarden\Servers\OvhVpsAndDedicated\App\Helpers;

use ModulesGarden\Servers\OvhVpsAndDedicated\App\Helpers\Ovh\Vps\Vm\Vm;
use ModulesGarden\Servers\OvhVpsAndDedicated\Core\FileReader\Reader;
use ModulesGarden\Servers\OvhVpsAndDedicated\Core\ModuleConstants;

/**
 * Description of ConfigOptions
 *
 * @author Artur Pilch <artur.pi@modulesgarden.com>
 *
 * @method vpsClientAreaDisks();
 * @method vpsClientAreaSnapshots();
 * @method vpsClientAreaReinstall();
 * @method vpsClientAreaConsole();
 * @method vpsClientAreaRescue();
 * @method vpsClientAreaIpsReverse();
 * @method vpsClientAreaIps();
 *
 * @method dedicatedClientAreaIps();
 * @method dedicatedClientAreaIpsReverse();
 * @method dedicatedClientAreaGraphs();
 * @method dedicatedClientAreaReinstall();
 * @method dedicatedClientAreaRescue();
 *
 */

//@method vpsClientAreaChangePassword(); //currently not supproted

class PageController
{

    protected $params;

    public function __construct($params = null)
    {
        $this->params = $params;
    }

    public function getPages()
    {
        return $this->checkPages($this->readClientFile());
    }

    public function checkPages($items)
    {
        $pages = [];
        foreach ($items as $page => $params)
        {
            if (isset($params['validate']))
            {
                if ($this->runMethod($params['validate']))
                {
                    unset($params['validate']);
                    $pages[$page] = $params;
                }
            }
            else
            {
                $pages[$page] = $params;
            }
        }
        return $pages;
    }

    private function readClientFile()
    {
        $fileClient = Reader::read(ModuleConstants::getDevConfigDir() . DS . 'menu' . DS . 'client.yml')->get();
        return $fileClient['mg-provisioning-module']['children'];
    }

    private function runMethod($functions)
    {
        $status = false;
        foreach (explode(',', $functions) as $function)
        {
            $status = $this->{$function}();
            if ($status === true)
            {
                continue;
            }
            $status = false;
            break;
        }
        return $status;
    }

    public function __call($name, $arguments = null)
    {
        if (empty($this->params['packageid']))
        {
            $serviceManager = new ServiceManager($this->params);
            $this->params   = $serviceManager->getParams();
        }
        $fieldsProvider = new FieldsProvider($this->params['packageid']);

        return $fieldsProvider->getField($name) == "on";
    }
}
