<?php

namespace ModulesGarden\Servers\HetznerVps\App\Helpers;

use Exception;
use ModulesGarden\Servers\HetznerVps\App\Libs\HetznerVps\Api;
use ModulesGarden\Servers\HetznerVps\Core\Helper\Lang;
use ModulesGarden\Servers\HetznerVps\Core\Models\Whmcs\Hosting;

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
        if(empty($api->getClient()->getServerID()))
        {
            throw new Exception(Lang::getInstance()->absoluteT('vmIsEmpty'));
        }
        $this->vm = $api->servers()->get($api->getClient()->getServerID());
    }

    public function reboot() {
        $this->initVM();
        $this->vm->reset();
    }

    public function powerOn() {
        $this->initVM();
        $this->vm->powerOn();
    }

    public function powerOff() {
        $this->initVM();
        $this->vm->powerOff();
    }

    public function shutdown() {
        $this->initVM();
        $this->vm->shutdown();
    }

    /**
     * @return \LKDev\HetznerCloud\Models\Servers\Server
     * @throws Exception
     */
    function getVM() {
        $this->initVM();
        return $this->vm;
    }
    function passwordReset(){
        $this->initVM();
        $password = $this->vm->resetRootPassword();

        $this->updatePasswordInDB($this->params['serviceid'], $password->getResponse()['root_password']);

        return;
    }

    private function updatePasswordInDB($hostingID, $password)
    {
        Hosting::where('id', $hostingID)
            ->update([
                'password' => \encrypt($password)
            ]);
    }

    function getParams() {
        return $this->params;
    }

}
