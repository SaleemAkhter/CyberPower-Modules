<?php

namespace ModulesGarden\Servers\DigitalOceanDroplets\App\Libs\DigitalOceanDroplets\Api;

use ModulesGarden\Servers\DigitalOceanDroplets\App\Libs\DigitalOceanDroplets\Abstracts\AbstractApi;
use ModulesGarden\Servers\DigitalOceanDroplets\App\Libs\DigitalOceanDroplets\Base\Collection;
use ModulesGarden\Servers\DigitalOceanDroplets\App\Libs\DigitalOceanDroplets\Base\Items\Firewall as FirewallItem;
use ModulesGarden\Servers\DigitalOceanDroplets\App\Libs\DigitalOceanDroplets\Models\Firewall\Create;
use ModulesGarden\Servers\DigitalOceanDroplets\App\Libs\DigitalOceanDroplets\Models\Firewall\Rule;

/**
 * Description of Connection
 *
 * @author Mateusz Pawłowski <mateusz.pa@modulesgarden.com>
 * 

 * 
 */
class Firewall extends AbstractApi
{

    public function createFirewall(Create $model)
    {
        $params = $model->toArray();

        return $this->api->firewall()->create($params['name'],$params['inboundRules'],$params['outboundRules'],$params['dropletIds'], $params['tags']);
    }
    public function addRule($firewallID, Rule $model){
        $params = $model->toArray();

        return $this->api->firewall()->addRule($firewallID, $params['type'], $params['protocol'], $params['port'], ['addresses' => $params['sources']]);
    }
    public function deleteRule($firewallID, $ruleData){

        return $this->api->firewall()->deleteRule($firewallID, $ruleData);
    }

    public function editRule( $firewall, $dropletIds,$inbound, $outbound )
    {
        return $this->api->firewall()->edit($firewall->id, $firewall->name,$dropletIds, $inbound, $outbound);
    }
    public function one($id)
    {
        $response = $this->api->firewall()->getById($id);
        return new FirewallItem($response, $this->api);
    }

    public function all()
    {

        $all        = $this->api->firewall()->getAll();
        $collection = new Collection();
        foreach ($all as $item)
        {
            $collection->add(new FirewallItem($item, $this->api));
        }
        return $collection->all();
    }

    public function delete($id)
    {
        return $this->api->firewall()->delete($id);
    }

    public function __call($name, $agruments = null)
    {
        if (method_exists($this->api->droplet(), $name))
        {
            return $this->api->droplet()->{$name}($this->client->getServerID(), ...$agruments);
        }
        throw new \Exception('Method does not exist on API.');
    }

}
