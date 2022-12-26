<?php

namespace ModulesGarden\Servers\HetznerVps\App\UI\Product\Helpers;

use ModulesGarden\Servers\HetznerVps\App\Libs\HetznerVps\Api;
use ModulesGarden\Servers\HetznerVps\App\Libs\HetznerVps\Base\Items\Droplet;
use function ModulesGarden\Servers\HetznerVps\Core\Helper\isAdmin;
use ModulesGarden\Servers\HetznerVps\Core\Helper\Lang;

/**
 * Description of ServerDetails
 *
 * @author Mateusz Pawłowski <mateusz.pa@modulesgarden.com>
 */
class ServerManager {

    protected $params = [];
    protected $data   = [];

    public function __construct($params) {
        $this->params = $params;
    }

    public function getInformation() {
        $vm = $this->getVM();

        $this->updateDomain($vm->name);
        $this->prepareDateToTable($vm);
        return $this->data;
    }

    public function getServerStatus()
    {
        $vm = $this->getVM();
        return $vm->status;
    }

    public function updateDomain($newDomainName) {
        $hosting = \ModulesGarden\Servers\HetznerVps\Core\Models\Whmcs\Hosting::where('id', $this->params['serviceid'])->update([
            'domain' => $newDomainName
        ]);
    }

    /*
     * Get VM object
     *
     * @return \ModulesGarden\Servers\HetznerVps\App\Libs\HetznerVps\Base\Items\Droplet $vm
     */

    public function getVM() {
        $api = new Api($this->params);
        return $api->servers()->get($api->getClient()->getServerID());
    }

    /*
     * Get params required to get VM
     * 
     * @return array $params
     */

    private function getParams() {
        return ServiceParams::getParams();
    }

    private function prepareDateToTable($vm) {

        $this->setRow('status', ucfirst($vm->status));
        $this->setRow('name', $vm->name);
        if(!$this->params['customfields']['sshKey']){
            $this->setRow('password', $this->params['password']);
        }
        $this->setRow('memory', $vm->serverType->memory . ' GB');
        $this->setRow('disk', $vm->serverType->disk . ' GB');
        if (sizeof($vm->volumes) > 0) {
            $this->setRow('volumes', implode(', ', $this->getVolumes($vm)));
        }
        $this->setRow('cpu', $vm->serverType->cores);

        $this->setRow('image', $vm->image->description);
        $this->setRow('ivp4', $vm->publicNet->ipv4->ip);
        $this->setRow('ivp6', $vm->publicNet->ipv6->ip);

        if(isAdmin())
        {
            $this->setRow('datacenter', $vm->datacenter->description);
            $this->setRow('location', $vm->datacenter->location->city);
        }
        $this->setRow('backups', $this->renameBackupValue($vm->backupWindow));

       // $this->setRow('backups', $this->renameStatus($vm->backupWindow));
    }

    private function getVolumes($vm) {
        $sizesArray = [];
        foreach ($vm->volumes as $volume) {
            $api = new Api($this->params);

            $sizesArray[] = $api->volumes()->get($volume)->size . " GB";
        }
        return $sizesArray;
    }

    private function setRow($name, $value) {
        $this->data[] = [
            'name'  => Lang::getInstance()->absoluteT('serviceInformation', 'tableField', $name),
            'value' => $value,
            'noLangName' => $name
        ];
    }

    private function renameStatus($status) {
        return ($status) ? Lang::getInstance()->T('yes') : Lang::getInstance()->T('no');
    }

    private function renameBackupValue($value)
    {
        return $value ? 'Enabled' : 'Disabled';
    }


}
