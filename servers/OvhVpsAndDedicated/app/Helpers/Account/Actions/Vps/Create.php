<?php

namespace ModulesGarden\Servers\OvhVpsAndDedicated\App\Helpers\Account\Actions\Vps;

use ModulesGarden\Servers\OvhVpsAndDedicated\App\Helpers\Account\Actions\Finalizer;
use ModulesGarden\Servers\OvhVpsAndDedicated\App\Helpers\FieldsProvider;
use ModulesGarden\Servers\OvhVpsAndDedicated\App\Libs\Models\Client;
use ModulesGarden\Servers\OvhVpsAndDedicated\App\Libs\Ovh\Models\Vps\Create\Vps as VpsCreate;
use ModulesGarden\Servers\OvhVpsAndDedicated\App\Libs\Ovh\Managers\OrderVps;
use ModulesGarden\OvhVpsAndDedicated\Core\Helper;
use ModulesGarden\Servers\OvhVpsAndDedicated\App\Helpers\Logger;
use ModulesGarden\Servers\OvhVpsAndDedicated\Core\UI\Traits\WhmcsParams;

/**
 * Class Create
 *
 * @author Artur Pilch <artur.pi@modulesgarden.com>
 */
class Create
{
    use WhmcsParams;
    /**
     * @var FieldsProvider
     */
    private $fieldsProvider;
    /**
     * @var Client
     */
    private $client;

    public function __construct(Client $client)
    {
        $this->client = $client;
    }


    public function run()
    {
        $this->create();
    }

    public function create()
    {
        $this->fieldsProvider            = new FieldsProvider($this->getWhmcsParamByKey('pid'));

        $createModel     = new VpsCreate($this->client->getProductConfig());
        $orderVpsManager = new OrderVps($this->client);

        $itemId          = $orderVpsManager->add($createModel->getItemOrderParams());
        $required        = $orderVpsManager->getRequiredConfiguration($itemId);
        $possiblyOption =  $orderVpsManager->getPossiblyOptions($createModel->getProductPlanCodeParams());
        //snapshoot
        if($this->getWhmcsConfigOption('snapshot') ||
           !$this->isWhmcsConfigOption('snapshot') && $this->fieldsProvider->getField('vpsClientAreaSnapshots')=="on"){
            foreach ($possiblyOption as $item) {
                if($item['family']=="snapshot"){
                    $option = [
                        'duration' => sprintf('P%sM', $this->fieldsProvider->getField('vpsDuration')),
                        'itemId' => $itemId,
                        'planCode' => $item['planCode'],
                        'pricingMode' => 'default',
                        'quantity' => 1
                    ];
                    $orderVpsManager->addOption($option);
                    break;
                }
            }

        }
        $orderVpsManager->addOptionsFromArray($createModel->createOptions($possiblyOption, $itemId));
        $requiredConfiguration = $createModel->createRequiredConfiguration($required);
        $orderVpsManager->addConfigFromArrayOptions($itemId, $requiredConfiguration);
        //license
        if($this->hasLicense()){
            $option = [
                'duration' => sprintf('P%sM', $this->fieldsProvider->getField('vpsDuration')),
                'itemId' => $itemId,
                'planCode' => $this->getLicense(),
                'pricingMode' => 'default',
                'quantity' => 1
            ];
            $orderVpsManager->addOption($option);
        }


        $orderId = $orderVpsManager->checkout();

        $orderVpsManager->getOrderedVpsName($orderId);



        Finalizer::finalizeCreateVpsAction($this->client->getHostingID(),  $orderId);

        Helper\successLog('vpsCreateMachineSuccess', [
            'params' => $this->client->getParams(),
            'object' => json_encode($this)
        ]);
    }

    private function hasLicense(){

        if($this->getWhmcsConfigOption("planCodeVpsOsAddon")){
            list($planCode, $os, $addon) = explode(":", $this->getWhmcsConfigOption("planCodeVpsOsAddon"));
            return $addon;
        }
        if($this->isWhmcsConfigOption("license") ){
            return $this->getWhmcsConfigOption("license");
        }
        return $this->fieldsProvider->getField('license') ;
    }

    private function getLicense(){
        if($this->getWhmcsConfigOption("planCodeVpsOsAddon")){
            list($planCode, $os, $addon) = explode(":", $this->getWhmcsConfigOption("planCodeVpsOsAddon"));
            return $addon;
        }
        $licence = $this->getWhmcsParamByKey("configoptions")['license'];
        if(!$licence){
            return $this->fieldsProvider->getField('license') ;
        }
        //configurable option only
        if($licence == "option-windows"){
            $planCode = $this->getWhmcsParamByKey("configoptions")['product'] ? $this->getWhmcsParamByKey("configoptions")['product']: $this->fieldsProvider->getField('vpsProduct');
            $planCode = str_replace("vps-", "-", $planCode);
            return $licence.$planCode;//i.e: option-windows-elite-8-8-320
        }
        return $licence;

    }
}
