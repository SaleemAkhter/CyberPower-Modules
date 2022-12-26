<?php

namespace ModulesGarden\Servers\OvhVpsAndDedicated\App\Helpers\Account\Actions\Vps;

use ModulesGarden\Servers\OvhVpsAndDedicated\App\Helpers\Account\Actions\Finalizer;
use ModulesGarden\Servers\OvhVpsAndDedicated\App\Helpers\Account\Utilities\Provider;
use ModulesGarden\Servers\OvhVpsAndDedicated\App\Libs\Api\OvhApiFactory;
use ModulesGarden\Servers\OvhVpsAndDedicated\App\Libs\Models\Client;
use ModulesGarden\Servers\OvhVpsAndDedicated\App\Helpers\Ovh\Vps\Order\VpsComparator;
use ModulesGarden\Servers\OvhVpsAndDedicated\App\Libs\Ovh\Managers\VpsReinstaller;
use ModulesGarden\Servers\OvhVpsAndDedicated\App\Helpers\Logger;
use ModulesGarden\OvhVpsAndDedicated\Core\Helper;

/**
 * Class Reinstall
 *
 * @author Artur Pilch <artur.pi@modulesgarden.com>
 */
class Reinstall
{

    /**
     * @var Client
     */
    private $client;

    private $reinstalled = false;

    /**
     * @var string
     */
    private $reinstalledVpsName;

    /**
     * @var array
     */
    private $reusableProducts;

    public function __construct(Client $client)
    {
        $this->client = $client;
    }

    public function run()
    {
        $this->setReusableProducts();

        if(!$this->hasReusableProducts())
        {
            return;
        }

        $this->reinstall();
    }

    public function reinstall()
    {
        $comparator = new VpsComparator($this->client, $this->reusableProducts);
        if(!$comparator->isFitted())
        {
            return false;
        }
        //server name
        $serverName = $comparator->getVpsNameToReinstall();
        //os template
        $api = (new OvhApiFactory())->formParams();
        foreach ($api ->get(sprintf('/vps/%s/images/available',$serverName)) as $imageId){
            $detail = $api->get(sprintf('/vps/%s/images/available/%s',$serverName, $imageId));
            if($detail == $this->client->getProductConfig()->getSystemVersions()){
                break;
            }
        }
        //rebuild
        $request = [
            'doNotSendPassword' => false,
            'imageId'        => $imageId,
        ];
        $api->post(sprintf('/vps/%s/rebuild',$serverName),$request) ;
        Finalizer::finalizeCreateAction($this->client->getHostingID(),  $comparator->getVpsNameToReinstall());
        $this->setReinstalled(true);
        $this->reinstalledVpsName = $comparator->getVpsNameToReinstall();

        Helper\successLog('vpsReusedMachineSuccess', [
            'params' => $this->client->getParams(),
            'reusedMachine' => $comparator->getVpsNameToReinstall(),
            'object' => json_encode($this)
        ]);
    }

    public function isReinstalled()
    {
        return $this->reinstalled;
    }

    private function hasReusableProducts()
    {
        return !empty($this->reusableProducts);
    }

    private function setReusableProducts()
    {
        $this->reusableProducts = Provider::getReusableProducts($this->client->getProductID());

        return $this;
    }

    private function setReinstalled($reinstalled)
    {
        $this->reinstalled = $reinstalled;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getReinstalledVpsName()
    {
        return $this->reinstalledVpsName;
    }


}
