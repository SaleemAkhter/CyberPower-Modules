<?php

namespace ModulesGarden\Servers\OvhVpsAndDedicated\App\Libs\Ovh\Managers;


use ModulesGarden\Servers\OvhVpsAndDedicated\App\Helpers\Ovh\Vps\Order\Provider;

use ModulesGarden\Servers\OvhVpsAndDedicated\App\Libs\Models\Client;
use ModulesGarden\Servers\OvhVpsAndDedicated\App\Libs\Ovh\Managers\Abstracts\ApiManagerBase;
use ModulesGarden\Servers\OvhVpsAndDedicated\App\Libs\Ovh\Models\Order\OrderedVpsItem;
use \ModulesGarden\Servers\OvhVpsAndDedicated\App\Libs\Ovh\Facade\Items\Me\Order;
use ModulesGarden\Servers\OvhVpsAndDedicated\App\Libs\Ovh\Service\Api\Items\Order\Cart;

use ModulesGarden\Servers\OvhVpsAndDedicated\App\Libs\Ovh\Models\Vps\Create\Vps as VpsCreate;


/**
 * Class Order
 *
 * @author Artur Pilch <artur.pi@modulesgarden.com>
 */
class OrderVps extends ApiManagerBase
{
    private $cartId;

    /**
     * @var Cart
     */
    private $cart;

    public function __construct(Client $client)
    {
        parent::__construct($client);

        $cart              = $this->api->order->cart()
            ->create(Provider::getDataToCreateCartFromParams($client->getOvhSubsidiary()));

        $this->cartId      = $cart['cartId'];
        $this->cart        = $this->api->order->cart()->one($this->cartId);
    }

    public function add($orderParams)
    {
        $result = $this->cart->vps()->add($orderParams);
        $res =  new OrderedVpsItem($result);
        return $res->getItemId();
    }

    public function addOption($params)
    {
        $this->cart->vps()->options()->add($params);
    }

    public function getOrderCartDetails()
    {
        return $this->api->order->cart()->one($this->cartId)->getInfo();
    }

    public function checkout()
    {
        $this->cart->assign();
        $response = $this->cart->checkout()->validate();

        return $response['orderId'];
    }

    public function addConfig($itemId, $params)
    {
        return $this->cart->item()->one($itemId)->configuration()->add($params);
    }

    public function addConfigFromArrayOptions($itemId, $configuration = [])
    {
        foreach ($configuration as $option)
        {
            $this->addConfig($itemId, $option);
        }
    }

    public function addOptionsFromArray($options)
    {

        foreach ($options as $option)
        {
            $this->addOption($option);
        }
    }


    public function getRequiredConfiguration($itemId)
    {
        return $this->cart->item()->one($itemId)->requiredConfiguration();
    }

    public function getOrderedVpsName($orderId)
    {
        $ovhAccountOrder = new OvhAccountOrder($this->client);
        $vpsName = $ovhAccountOrder->getServiceNameFromOrder($orderId);

        return $vpsName;
    }

    public function getPossiblyOptions($params)
    {
        return $this->cart->vps()->options()->getOptions($params);
    }


}
