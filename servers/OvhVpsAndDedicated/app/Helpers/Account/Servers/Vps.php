<?php

namespace ModulesGarden\Servers\OvhVpsAndDedicated\App\Helpers\Account\Servers;

use ModulesGarden\Servers\OvhVpsAndDedicated\App\Enum\CustomField;
use ModulesGarden\Servers\OvhVpsAndDedicated\App\Helpers\Account\Abstracts\Account;
use ModulesGarden\Servers\OvhVpsAndDedicated\App\Helpers\CustomFields;
use ModulesGarden\Servers\OvhVpsAndDedicated\App\Helpers\FieldsProvider;
use ModulesGarden\Servers\OvhVpsAndDedicated\App\Libs\Models\Client;
use ModulesGarden\Servers\OvhVpsAndDedicated\App\Libs\Ovh\Facade\Items\Vps\Vps as VpsApi;
use ModulesGarden\Servers\OvhVpsAndDedicated\App\Helpers\Account\Actions\Vps as Actions;
use ModulesGarden\Servers\OvhVpsAndDedicated\App\Helpers\Addon;
use ModulesGarden\Servers\OvhVpsAndDedicated\App\Libs\Ovh\Facade\Repository\Vps\Snapshot\Repository;
use ModulesGarden\Servers\OvhVpsAndDedicated\App\Libs\Ovh\Managers\UpgradeManager;
use ModulesGarden\Servers\OvhVpsAndDedicated\App\Libs\Ovh\Managers\VpsReinstaller;
use ModulesGarden\OvhVpsAndDedicated\Core\Helper;
use ModulesGarden\Servers\OvhVpsAndDedicated\App\Traits\WhmcsParamsApp;
use ModulesGarden\Servers\OvhVpsAndDedicated\App\UI\Admin\Configuration\Helpers\AutomationSettings;
use ModulesGarden\Servers\OvhVpsAndDedicated\App\UI\Admin\Product\Providers\ServerInformationProvider;
use ModulesGarden\Servers\OvhVpsAndDedicated\Core\UI\Traits\WhmcsParams;


/**
 * Class Vps
 *
 * @author Artur Pilch <artur.pi@modulesgarden.com>
 */
class Vps extends Account
{
    use WhmcsParamsApp;
    /**
     * @var VpsApi
     */
    protected $vps;

    public function __construct(Client $client)
    {
        parent::__construct($client);
        $this->setVps();
    }

    public function create()
    {
        $reinstall = new Actions\Reinstall($this->client);
        $reinstall->run();
        if($reinstall->isReinstalled())
        {
            $this->setVps($reinstall->getReinstalledVpsName());
            $this->assignDomainAndIpToService();
            return;
        }
        $create = new Actions\Create($this->client);
        $create->run();
    }

    public function changePackage()
    {
        $vpsName = $this->vps->model()->getName();

        $upgradeManager = new UpgradeManager($this->client);
        $upgradeManager->run();

        $fieldsProvider = new FieldsProvider($this->client->getProductID());
        if($fieldsProvider->getField('vpsPreventSystemReinstall') != "on")
        {
            $vpsReinstaller = new VpsReinstaller($this->client);
            $vpsReinstaller->reinstallWithSystemCheck($vpsName);
        }

        Helper\successLog("changePackage", $this->client->getParams());
    }

    public function suspend()
    {
       $fieldsProvider = new FieldsProvider($this->client->getProductID());
        $action = $fieldsProvider->getField('vpsActionOnSuspendService');
        switch ($action)
        {
            case AutomationSettings::STOP:
                return $this->vps->stop();
        }
    }

    public function unsuspend()
    {
        if($this->vps->model()->getState() == 'running');
        {
            return;
        }
        
        return $this->vps->start();
    }

    public function terminate()
    {
        $fieldsProvider = new FieldsProvider($this->client->getProductID());

        if($this->getWhmcsCustomField(CustomField::SERVER_NAME)){
            try{
                $snapshotRepostiory = new Repository(false, $this->getWhmcsEssentialParams());
                $snapshot = $snapshotRepostiory->get();
                if($snapshot){
                    $snapshot->remove();
                }
            }catch (\Exception $ex){
                //nothing to do
            }
        }
        if($fieldsProvider->getField('autoAssignVpsToReuseListOnTerminate'))
        {
            Helper\infoLog('vpsMachineAssignedToReuseWithTerminateAction', [
                'params' => $this->client->getParams(),
                'object' => json_encode($this),
                'vpsName' => $this->client->getServiceName(),
            ]);
            Addon\Models\Vps::assignVpsAsReusable($this->client->getServiceName());
            CustomFields::set($this->client->getHostingID(), Client::SERVICE_CUSTOM_FIELD_NAME, "");
            return;
        }

        return $this->vps->terminate();
    }

    private function setVps($serviceName = false)
    {
        if($serviceName !== false)
        {
            $this->vps = new VpsApi($serviceName, $this->client->getParams());
        }
        elseif($this->client->getAction() != 'create')
        {
            $this->vps = new VpsApi($this->client->getServiceName(), $this->client->getParams());
        }
    }

    public function assignDomainAndIpToService()
    {

        $vpsName = $this->vps->model()->getName();

        $serverInformation = new ServerInformationProvider();
        $ip = $serverInformation->getPrimaryIp($vpsName);
        $ipAddress = $ip->getIpAddress();

        $this->assignDomainAndIp($vpsName, $ipAddress);
    }
}