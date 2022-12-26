<?php

namespace ModulesGarden\Servers\DigitalOceanDroplets\App\Service\Firewall;

use Exception;
use ModulesGarden\Servers\DigitalOceanDroplets\App\Libs\DigitalOceanDroplets\Api;
use ModulesGarden\Servers\DigitalOceanDroplets\App\Service\CustomFields\CustomFields;
use ModulesGarden\Servers\DigitalOceanDroplets\Core\Models\Whmcs\Hosting;
use ModulesGarden\Servers\DigitalOceanDroplets\Core\Models\Whmcs\Product;

class FirewallTask
{

    public function run(){

        foreach($this->getAllProducts() as $product){
            foreach($this->getAssignedHostingToProduct($product->id) as $hosting){
                try{
                    $serverID = CustomFields::get($hosting->id, 'serverID');
                    if(empty($serverID)){
                        throw new \Exception('ServerID is empty. Skipped');
                    }
                    $firewallList = $this->getFirewallList($hosting->servers->password);
                    $matchedList = $this->findMatchedFirewalls($firewallList, $serverID);

                    $this->saveFirewalls($hosting->id, $matchedList);
                    echo "Success: Update firewalls for hosting #" . $hosting->id ."\n";
                }catch(Exception $ex){
                    echo "Error: ". $ex->getMessage().". Hosting #" . $hosting->id ."\n";
                }

            }

        }
    }

    private  function getAllProducts()
    {
        return Product::where('servertype', 'DigitalOceanDroplets')->get();
    }

    private  function getAssignedHostingToProduct($productID)
    {
        return Hosting::where('packageid', $productID)->get();
    }

    private function getAPI($apiToken){
        $api = new Api(['serverpassword' => \decrypt($apiToken)]);
        return $api;
    }

    private function getFirewallList($tokenID){
        $api = $this->getAPI($tokenID);
        return $api->firewall->all();
    }

    private function findMatchedFirewalls($firewallList, $dropletID){
        $matchedList = [];
        foreach($firewallList as $firewall){
            if(in_array($dropletID, $firewall->dropletIds)){
                $matchedList[] = $firewall->id;
            }

        }
        return $matchedList;
    }

    private function saveFirewalls($serviceID, $matchedList = []){
        CustomFields::set($serviceID, 'firewalls', implode(',', $matchedList));
    }

}
