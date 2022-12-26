<?php

namespace ModulesGarden\Servers\DigitalOceanDroplets\App\Libs\DigitalOceanDroplets\Api;

use ModulesGarden\Servers\DigitalOceanDroplets\App\Libs\DigitalOceanDroplets\Abstracts\AbstractApi;
use ModulesGarden\Servers\DigitalOceanDroplets\App\Libs\DigitalOceanDroplets\Base\Collection;
use ModulesGarden\Servers\DigitalOceanDroplets\App\Libs\DigitalOceanDroplets\Base\Items\Droplet as DropletItem;
use ModulesGarden\Servers\DigitalOceanDroplets\App\Libs\DigitalOceanDroplets\Models\Droplets\Create;

/**
 * Description of Connection
 *
 * @author Mateusz Pawłowski <mateusz.pa@modulesgarden.com>
 * 

 * 
 */
class Droplet extends AbstractApi
{

    public function createAccount(Create $model)
    {
        $params = $model->toArray();

        return $this->api->droplet()->create(
                        $params['name'], $params['region'], $params['size'], $params['image'], $params['backups'], $params['ipv6'], $params['private_networking'], $params['ssh_keys'], $params['user_data'], $params['monitoring'], $params['volumes'], $params['tags']
        );
    }

    public function one()
    {
        $resonse = $this->api->droplet()->getById($this->client->getServerID());
        return new DropletItem($resonse, $this->api);
    }
    
    public function powerOn($id){
        return $this->api->droplet()->powerOn($id);
    }

    public function all()
    {

        $all        = $this->api->droplet()->getAll();
        $collection = new Collection();
        foreach ($all as $item)
        {
            $collection->add(new DropletItem($item, $this->api));
        }
        return $collection->all();
    }

    public function resize($size = null, $disk = true)
    {
        if (is_null($size))
        {
            $size = $this->client->getSize();
        }
        return $this->api->droplet()->resize($this->client->getServerID(), $size, $disk);
    }
    
    public function getDropletAction($dropletID, $actionID){
        return $this->api->droplet()->getDropletAction($dropletID, $actionID);
    }
    
    public function tagAResource($tag, $resource){
        return $this->api->droplet()->tagAResource($tag, $resource);
    }
    
    public function untagAResource($tag, $resource){
        return $this->api->droplet()->untagAResource($tag, $resource);
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
