<?php

namespace ModulesGarden\Servers\HetznerVps\App\Helpers;

use HttpException;
use ModulesGarden\Servers\HetznerVps\Core\FileReader\Reader;
use ModulesGarden\Servers\HetznerVps\Core\ModuleConstants;

/**
 * Description of ConfigOptions
 *
 * @author Mateusz Pawłowski <mateusz.pa@modulesgarden.com>
 *
 * @method clientAreaRebuild();
 * @method clientAreaConsole();
 * @method clientAreaIsos();
 * @method clientAreaReverseDNS();
 * @method clientAreaFloatingIPs();
 * @method clientAreaBackups();
 * @method clientAreaFirewalls();
 *
 */
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
                if ($this->runMethod($params['uri'])) {
                    $pages[$page] = $params;
                }
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
        foreach(explode(',', $functions) as $function){
            $function = (explode('&mg-page=', $function)[1]);
            $status = $this->{$function}();
            if($status === true){
                continue;
            }
            $status = false;
            break;
        }
        return $status;
    }

    public function __call($name, $arguments = null)
    {
        if(empty($this->params['packageid'])){
            $serviceManager = new ServiceManager($this->params);
            $this->params = $serviceManager->getParams();
        }
        $fieldsProvider = new FieldsProvider($this->params['packageid']);

        if($fieldsProvider->getField('clientArea'.$name) == "on"){
            return true;
        }

        return false;
    }

    public function checkBackups()
    {
        try
        {
            $backupsEnabled = $this->getVM();
            return $backupsEnabled->backupsEnabled;
        }
        catch (HttpException $ex)
        {
            return false;
        }
    }

    public function checkIsOwenrFirewall($firewallID){
        $firewalls = explode(',',$this->params['customfields']['firewalls']);
        if(in_array($firewallID, $firewalls)){
            return true;
        }
        return false;
    }

    public function getVM()
    {
        $serviceManager = new ServiceManager($this->params);
        return $serviceManager->getVM();
    }

}
