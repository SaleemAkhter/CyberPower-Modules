<?php

namespace ModulesGarden\Servers\DigitalOceanDroplets\App\Libs\DigitalOceanDroplets\Api;

use ModulesGarden\Servers\DigitalOceanDroplets\App\Libs\DigitalOceanDroplets\Abstracts\AbstractApi;
use ModulesGarden\Servers\DigitalOceanDroplets\App\Libs\DigitalOceanDroplets\Base\Collection;
use ModulesGarden\Servers\DigitalOceanDroplets\App\Libs\DigitalOceanDroplets\Base\Items\Volume as VolumeItem;
use ModulesGarden\Servers\DigitalOceanDroplets\App\Libs\DigitalOceanDroplets\Models\Images\Criteria;
use ModulesGarden\Servers\DigitalOceanDroplets\App\Libs\DigitalOceanDroplets\Models\Volume\Create;
use ModulesGarden\Servers\DigitalOceanDroplets\App\Libs\DigitalOceanDroplets\Models\Volume\Resize;

/**
 * Description of Connection
 *
 * @author Damian Lipski <damian@modulesgarden.com>
 * 
 * @method public getNeighborsById();
 * 
 */
class Volume extends AbstractApi
{

    public function createVolume(Create $model)
    {
        $params = $model->toArray();

        return $this->api->volume()->create(
                        $params['name'], $params['description'], $params['sizeInGigabytes'], $params['regionSlug']
        );
    }
    
    public function resizeVolume($volumeID, Resize $model)
    {
        $params = $model->toArray();
        
        return $this->api->volume()->resize(
                        $volumeID, $params['sizeInGigabytes'], $params['regionSlug']
        );
    }

    public function one($volumeID = null)
    {
        if (is_null($volumeID))
        {
            $volumeID = $this->client->getVolume();
        }
        $response = $this->api->volume()->getById($volumeID);
        return new VolumeItem($response, $this->api);
    }

    public function all()
    {
        $all = $this->api->volume()->getAll();

        $collection = new Collection();
        foreach ($all as $item)
        {
            $collection->add(new VolumeItem($item, $this->api));
        }
        return $collection->all();
    }

    public function getAssgined($assigned = [])
    {
        $collection = new Collection();

        foreach ($assigned as $item)
        {
            try
            {

                $collection->add($this->one($item));
            }
            catch (\Exception $ex)
            {
                //silence, problem was occurred when system start deleting volume
            }
        }

        return $collection->all();
    }

    public function deleteByID($volumeID = null)
    {
        if (is_null($volumeID))
        {
            $volumeID = $this->client->getVolume();
        }
        return $this->api->volume()->delete($volumeID);
    }
    
    public function __call($name, $agruments = null)
    {
        if (method_exists($this->api->volume(), $name))
        {
            return $this->api->volume()->{$name}(...$agruments);
        }
        throw new \Exception('Method does not exist on API.');
    }

}
