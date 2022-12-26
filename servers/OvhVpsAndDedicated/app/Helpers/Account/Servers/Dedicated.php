<?php

namespace ModulesGarden\Servers\OvhVpsAndDedicated\App\Helpers\Account\Servers;

use ModulesGarden\Servers\OvhVpsAndDedicated\App\Helpers\Account\Abstracts\Account;
use ModulesGarden\Servers\OvhVpsAndDedicated\App\Helpers\Account\Actions\Dedicated\Reinstaller;
use ModulesGarden\Servers\OvhVpsAndDedicated\App\Helpers\CustomFields;
use ModulesGarden\Servers\OvhVpsAndDedicated\App\Helpers\FieldsProvider;
use ModulesGarden\Servers\OvhVpsAndDedicated\App\Helpers\Server\Constants;
use ModulesGarden\Servers\OvhVpsAndDedicated\App\Helpers\Server\ServerSettingsManage;
use ModulesGarden\Servers\OvhVpsAndDedicated\App\Libs\Models\Client;
use ModulesGarden\Servers\OvhVpsAndDedicated\App\Libs\Ovh\Facade\Items\Dedicated\Server;
use ModulesGarden\Servers\OvhVpsAndDedicated\App\Models\Ovh\Orders;
use ModulesGarden\Servers\OvhVpsAndDedicated\App\UI\Admin\Configuration\Forms\AutomationForm;
use ModulesGarden\Servers\OvhVpsAndDedicated\App\UI\Admin\Configuration\Helpers\Dedicated\AutomationSettings;
use ModulesGarden\Servers\OvhVpsAndDedicated\App\Helpers\Account\Actions\Finalizer;
use ModulesGarden\Servers\OvhVpsAndDedicated\App\Helpers\Account\Utilities;
use ModulesGarden\OvhVpsAndDedicated\Core\Helper;
use ModulesGarden\Servers\OvhVpsAndDedicated\Core\UI\Traits\WhmcsParams;
use function ModulesGarden\Servers\OvhVpsAndDedicated\Core\Helper\debugLog;


/**
 * Class Dedicated
 *
 * @author Artur Pilch <artur.pi@modulesgarden.com>
 */
class Dedicated extends Account
{
    use WhmcsParams;
    /**
     * @var Server
     */
    protected $server;

    protected $reinstaller;
    /**
     * @var FieldsProvider
     */
    protected $fieldsProvider;
    protected $ovhSubsidiary;

    public function __construct(Client $client)
    {
        parent::__construct($client);
        $this->server = new Server($client->getServiceName(), $client->getParams());
        $this->reinstaller = new Reinstaller($this->client);
        $this->fieldsProvider = new FieldsProvider($this->client->getProductID());
        $this->ovhSubsidiary = ServerSettingsManage::getValueIfSetting( $client->getParams()['serverid'], Constants::OVH_SUBSIDIARY);

    }

    public function create($serverName = false)
    {
        if($this->isAutoAssing()){
            if (!$this->reinstall($serverName))
            {
                return;
            }

            Finalizer::finalizeCreateAction($this->client->getHostingID(), $this->reinstaller->getReinstalledServiceName());
            Helper\successLog('dedicatedMachineCreatedByAutomateAssignedAndReinstalled', [
                'params' => $this->client->getParams(),
                'object' => json_encode($this),
                'machineName' => $this->reinstaller->getReinstalledServiceName(),
            ]);
            $this->assignDomainAndIpToService();
            return;
        }
        //create order cart
        $request = [ 'ovhSubsidiary' => $this->ovhSubsidiary ];
        $cartId = $this->server->getApi()->order->post('cart',$request)['cartId'];
        //order baremetalServers
        $option = [
            'duration' => $this->fieldsProvider->getField('duration'),
            'planCode' => $this->fieldsProvider->getField('planCode'),
            'pricingMode' =>   $this->fieldsProvider->getField('pricingMode'),
            'quantity' => 1,
        ];
        $baremetalServer = $this->server->getApi()->order->post(sprintf("cart/%s/baremetalServers", $cartId),$option);
        //requiredConfiguration
        $response = $this->server->getApi()->order->get(sprintf("cart/%s/item/%s/requiredConfiguration", $cartId,$baremetalServer['itemId'] ));
        //order/cart/{cartId}/item/{itemId}/configuration
        $configuration = [
            "label" => 'dedicated_datacenter',
            "value" => strtolower( $this->fieldsProvider->getDataCenter())
            ];
        $response = $this->server->getApi()->order->post(sprintf("cart/%s/item/%s/configuration", $cartId,$baremetalServer['itemId']), $configuration);
        //dedicated_os
        $configuration = [
            "label" => 'dedicated_os',
            "value" => 'none_64.en'
        ];
        $response = $this->server->getApi()->order->post(sprintf("cart/%s/item/%s/configuration", $cartId,$baremetalServer['itemId']), $configuration);
        //options
        $option = [
            'duration' => $this->fieldsProvider->getField('duration'),
            'planCode' => $this->fieldsProvider->getField('planCode'),
            'pricingMode' =>  $this->fieldsProvider->getField('pricingMode'),
            'quantity' => 1,
            'itemId' => $baremetalServer['itemId'],
        ];
        foreach(['memory', 'storage','vrack','distributionLicense','bandwidth'] as $item){
            $option['planCode'] = $this->fieldsProvider->getField($item);
            if(! $option['planCode']){
                continue;
            }
            $response =  $this->server->getApi()->order->post(sprintf("cart/%s/baremetalServers/options", $cartId),$option);
        }
        //applicationLicense
        foreach($this->fieldsProvider->getApplicationLicense() as $item){
            $option['planCode'] = $item;
            $response =  $this->server->getApi()->order->post(sprintf("cart/%s/baremetalServers/options", $cartId),$option);
        }
        $response =  $this->server->getApi()->order->get(sprintf("cart/%s/baremetalServers/options", $cartId),[         'planCode' => $this->fieldsProvider->getField('planCode')]);
        //order/cart/{cartId}/assign
        $response =  $this->server->getApi()->order->post(sprintf("cart/%s/assign", $cartId));
        //order/cart/{cartId}/checkout
        $autoPayWithPreferredMethod = $this->fieldsProvider->getField(AutomationForm::AUTO_PAY_WITH_PREFERRED_METHOD);
        $waiveRetractationPeriod    = $this->fieldsProvider->getField(AutomationForm::WAIVE_RETRACTATION_PERIOD);
        $request = [
            'autoPayWithPreferredPaymentMethod' => $autoPayWithPreferredMethod === 'on',
            'waiveRetractationPeriod'           => $waiveRetractationPeriod === 'on',
        ];
        $response =  $this->server->getApi()->order->post(sprintf("cart/%s/checkout", $cartId),$request);
        //save order id
        $ovhOrders = new Orders();
        $ovhOrders->hosting_id = $this->getWhmcsParamByKey('serviceid');
        $ovhOrders->order_id = $response['orderId'];
        $ovhOrders->save();
    }

    public function suspend()
    {
        $fieldsProvider = new FieldsProvider($this->client->getProductID());
        $action         = $fieldsProvider->getField('dedicatedActionOnSuspendService');

        switch ($action)
        {
            case AutomationSettings::TERMINATE:
                Helper\successLog('dedicatedMachineTerminateOnSuspendAction', [
                    'params' => $this->client->getParams(),
                    'object' => json_encode($this),
                    'machineName' => $this->client->getServiceName(),
                ]);
                $this->terminate();
                break;
            case AutomationSettings::REINSTALL:
                $this->reinstall($this->client->getServiceName());
                if(!$this->reinstaller->isReinstalled())
                {
                    return;
                }
                Helper\successLog('dedicatedMachineCreatedByAutomateAssignedAndReinstalledOnSuspendAction', [
                    'params' => $this->client->getParams(),
                    'object' => json_encode($this),
                    'machineName' => $this->reinstaller->getReinstalledServiceName(),
                ]);
                break;
            case AutomationSettings::BOOT_TO_RESCUE:
                $this->server->makeBoot(Utilities\Constants::RESCUE);
                $this->server->reboot();
                Helper\successLog('dedicatedMachineWasBootedToRescueOnSuspendAction', [
                    'params' => $this->client->getParams(),
                    'object' => json_encode($this),
                    'machineName' => $this->reinstaller->getReinstalledServiceName(),
                ]);
                break;
        }
    }

    public function unsuspend()
    {
        $this->assignDomainAndIpToService();
        
        $fieldsProvider = new FieldsProvider($this->client->getProductID());
        $action         = $fieldsProvider->getField('dedicatedActionOnSuspendService');
        
        if($action == AutomationSettings::BOOT_TO_RESCUE && $this->client->getParams()['status'] == Utilities\Constants::SUSPENDED)
        {
            $this->server->makeBoot(Utilities\Constants::HARDDISK);
            $this->server->reboot();
        }
    }

    public function terminate()
    {
        $fieldsProvider = new FieldsProvider($this->client->getProductID());

        if($fieldsProvider->getField('dedicatedAutoRemoveServerFromClientOnTerminate'))
        {
            CustomFields::set($this->client->getHostingID(), Client::SERVICE_CUSTOM_FIELD_NAME, "");

            return;
        }
        $this->server->terminate();
    }

    public function isAutoAssing(){
        return $this->fieldsProvider->getField('dedicatedAutoRemoveServerFromClientOnTerminate') =="on";
    }

    public function changePackage()
    {
        //Because change package just reinstall server and reinstall is on create action
        //$this->reinstall($this->client->getServiceName());
    }

    private function reinstall($serverName)
    {
        $this->reinstaller->run($serverName);

        return $this->reinstaller->isReinstalled();
    }

    public function assignDomainAndIpToService()
    {
        $server = $this->server->model();

        $this->assignDomainAndIp($server->getName(), $server->getIp());
    }
}
