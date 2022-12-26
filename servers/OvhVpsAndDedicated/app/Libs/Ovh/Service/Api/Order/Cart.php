<?php

namespace ModulesGarden\Servers\OvhVpsAndDedicated\App\Libs\Ovh\Service\Api\Order;

use ModulesGarden\Servers\OvhVpsAndDedicated\App\Libs\Ovh\Service\Abstracts\AbstractApi;
use \ModulesGarden\Servers\OvhVpsAndDedicated\App\Libs\Ovh\Service\Api\Items\Order\Cart as CartApiItem;


/**
 * Class Cart
 *
 * @author Artur Pilch <artur.pi@modulesgarden.com>
 */
class Cart extends AbstractApi
{
    public function one($cartId)
    {
        return new CartApiItem($this->api, $this->client, $this->getPathExpanded($cartId));
    }

    public function create($params)
    {
        return $this->post(false, $params);
    }

    public function createForGatherData($params = [])
    {
        if(empty($params))
        {
            $params['ovhSubsidiary'] = $this->client->getCountry();
        }
        $response =  $this->post(false, $params);
        return new CartApiItem($this->api, $this->client, $this->getPathExpanded($response['cartId']));
    }

    
}