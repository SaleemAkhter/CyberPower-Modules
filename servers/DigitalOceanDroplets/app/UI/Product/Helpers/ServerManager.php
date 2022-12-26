<?php

namespace ModulesGarden\Servers\DigitalOceanDroplets\App\UI\Product\Helpers;

use ModulesGarden\Servers\DigitalOceanDroplets\App\Helpers\PageController;
use ModulesGarden\Servers\DigitalOceanDroplets\App\Libs\DigitalOceanDroplets\Adapters\Client;
use ModulesGarden\Servers\DigitalOceanDroplets\App\Libs\DigitalOceanDroplets\Api;
use ModulesGarden\Servers\DigitalOceanDroplets\App\Libs\DigitalOceanDroplets\Base\Items\Droplet;
use ModulesGarden\Servers\DigitalOceanDroplets\App\UI\Backups\Helpers\BackupsManager;
use function ModulesGarden\Servers\DigitalOceanDroplets\Core\Helper\isAdmin;
use ModulesGarden\Servers\DigitalOceanDroplets\Core\Helper\Lang;

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

    public function updateDomain($newDomainName) {
        $hosting = \ModulesGarden\Servers\DigitalOceanDroplets\Core\Models\Whmcs\Hosting::where('id', $this->params['serviceid'])->update([
            'domain' => $newDomainName
        ]);
    }

    /*
     * Get VM object
     *
     * @return \ModulesGarden\Servers\DigitalOceanDroplets\App\Libs\DigitalOceanDroplets\Base\Items\Droplet $vm
     */

    public function getVM() {
        $api = new Api($this->params);
        return $api->droplet->one();
    }

    /*
     * Get params required to get VM
     * 
     * @return array $params
     */

    private function getParams() {
        return ServiceParams::getParams();
    }

    private function prepareDateToTable(Droplet $vm) {
        $client = (new Client($this->params));
        $this->setRow('stauts', ucfirst($vm->status));
        $this->setRow('name', $vm->name);
        $this->setRow('memory', $vm->memory . ' MB');
        $this->setRow('disk', $vm->disk . ' GB');

        if (sizeof($vm->volumeIds) > 0) {
            $this->setRow('volumes', implode(', ', $this->getVolumes($vm)));
        }
        $this->setRow('cpu', $vm->vcpus);
        $this->setRow('region', $vm->region->name);
        $this->setRow('image', $vm->image->distribution . ' ' . $vm->image->name);
        if(isset($vm->kernel->name)) {
            $this->setRow('kernel', $vm->kernel->name);
        }
        $this->setRow('locked', $this->renameStatus($vm->locked));
        $this->setRow('tags', implode(', ', $vm->tags));
        $this->setRow('snapshots', count($vm->snapshotIds));

        $this->setRow('snapshotsLimit', $client->getSnapshotLimit());
        $this->setRow('firewall', count($client->getFirewalls()));
        if($client->getFirewallsLimit() != "") {
            $this->setRow('firewallsLimit', $client->getFirewallsLimit());
        }
        $this->setRow('ipv6', $this->renameStatus($vm->ipv6Enabled));
        $this->setRow('monitoring', $this->renameStatus(in_array('monitoring', $vm->features)));
        $this->setRow('privateNetwork', $this->renameStatus($vm->privateNetworkingEnabled));
        $this->setRow('backups', $this->renameStatus($vm->backupsEnabled));
        
        $nextBackupWindow = $vm->nextBackupWindow;       
        if($nextBackupWindow->start){
            $backupTime = (new BackupsManager($this->params))->getBackupTime($vm);
            Lang::getInstance()->addReplacementConstant('from', $backupTime['start']);
            Lang::getInstance()->addReplacementConstant('to', $backupTime['stop']);
            $this->setRow('backupWindow', Lang::getInstance()->absoluteT('serviceInformation', 'tableField', 'backupWindowValue'));

        }
    }

    private function getVolumes(Droplet $vm) {
        $sizesArray = [];
        foreach ($vm->volumeList() as $volume) {
            $sizesArray[] = $volume->sizeGigabytes . " GB";
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


}
