<?php

namespace ModulesGarden\Servers\OvhVpsAndDedicated\App\Helpers\Account\Actions\Dedicated;

use ModulesGarden\Servers\OvhVpsAndDedicated\App\Libs\Models\Client;
use ModulesGarden\OvhVpsAndDedicated\App\Libs\Repository\Manage\CustomFields;
use ModulesGarden\OvhVpsAndDedicated\App\UI\Admin\Servers\Providers\Dedicated as DedicatedProvider;
use ModulesGarden\OvhVpsAndDedicated\App\Libs\Repository\Manage\MachineReuseProducts;

/**
 * Class Comparator
 *
 * @author Artur Pilch <artur.pi@modulesgarden.com>
 */
class Comparator
{
    public function getReusable(Client $client)
    {
        $clientsGroupByMachineName = CustomFields::getAllClientWithVpsService();

        $servers = (new DedicatedProvider())->getDedicatedServers($clientsGroupByMachineName);



        foreach ($servers as $server)
        {
            if($server['reuse'] != 'on')
            {
                continue;
            }
            $products = $this->getProductsForReuseServer($server['id']);
            if(!in_array($client->getProductID(), $products))
            {
                continue;
            }
            return $server['id'];
        }
        return false;
    }

    private function getProductsForReuseServer($serverid)
    {
        $products  = MachineReuseProducts::getAllByName($serverid);
        $out = [];
        foreach ($products as $product)
        {
            array_push($out, $product->productId);
        }
        return $out;
    }

}