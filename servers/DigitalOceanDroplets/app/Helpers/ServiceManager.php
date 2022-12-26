<?php

namespace ModulesGarden\Servers\DigitalOceanDroplets\App\Helpers;

use Exception;
use ModulesGarden\Servers\DigitalOceanDroplets\App\Libs\DigitalOceanDroplets\Api;
use ModulesGarden\Servers\DigitalOceanDroplets\Core\Models\Whmcs\Hosting;

/**
 * Description of CustomFields
 *
 * @author Mateusz Pawłowski <mateusz.pa@modulesgarden.com>
 */
class ServiceManager {

    protected $params;
    protected $vm;

    public function __construct($params = []) {
        if (empty($params)) {
            $params = $this->getWHMCSParams();
        }
        $this->params = $params;
    }

    private function getWHMCSParams() {
        $server = new \WHMCS\Module\Server();
        $server->loadByServiceID($this->getHostingID());
        return $server->buildParams();
    }

    private function getHostingID() {
        if (!empty($_REQUEST['productselect'])) {
            return $_REQUEST['productselect'];
        }
        elseif (!empty($_REQUEST['id'])) {
            return $_REQUEST['id'];
        }
        else {
            return $this->getIDFromDB();
        }
    }

    private function getIDFromDB() {
        $hosting = Hosting::where([
                    'userid' => $_REQUEST['userid']
                ])->orderBy('domain', 'ASC')->first();
        return $hosting->id;
    }

    protected function initVM() {
        $api      = new Api($this->params);
        $this->vm = $api->droplet->one();
    }

    public function reboot() {
        $this->initVM();
        $this->vm->reboot();
    }

    public function powerOn() {
        $this->initVM();
        return $this->vm->powerOn();
    }

    public function powerOff() {
        $this->initVM();
        return $this->vm->powerOff();
    }

    public function shutdown() {
        $this->initVM();
        $this->vm->shutdown();
    }

    public function passwordReset() {
        $this->initVM();
        return $this->vm->passwordReset();
    }

    public function enableBackups() {
        $this->initVM();
        $this->vm->enableBackups();
    }

    public function disableBackups() {
        $this->initVM();
        $this->vm->disableBackups();
    }

    public function enableIpv6() {
        $this->initVM();
        $this->vm->enableIpv6();
    }

    public function enablePrivateNetworking() {
        $this->initVM();
        $this->vm->enablePrivateNetworking();
    }

    public function getTask() {
        $this->initVM();
        return $this->vm->getActions();
    }

    function getVM() {
        $this->initVM();
        return $this->vm;
    }

    function getParams() {
        return $this->params;
    }

}
