<?php

namespace ModulesGarden\Servers\DigitalOceanDroplets\App\Libs\DigitalOceanDroplets\Api;

use ModulesGarden\Servers\DigitalOceanDroplets\App\Libs\DigitalOceanDroplets\Abstracts\AbstractApi;
use ModulesGarden\Servers\DigitalOceanDroplets\App\Libs\DigitalOceanDroplets\Base\Collection;
use ModulesGarden\Servers\DigitalOceanDroplets\App\Libs\DigitalOceanDroplets\Base\Items\Snapshot as SnapshotItem;
use ModulesGarden\Servers\DigitalOceanDroplets\App\Libs\DigitalOceanDroplets\Models\Snapshots\Criteria;

/**
 * Description of Connection
 *
 * @author Mateusz Pawłowski <mateusz.pa@modulesgarden.com>
 * 
 * @method public delete();
 * @method public getById();

 * 
 */
class Snapshot extends AbstractApi
{

    public function one($snapshotID)
    {
        $response = $this->api->snapshot()->getById($snapshotID);
        return new SnapshotItem($response, $this->api);
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
                //silence, problem was occurred when system start deleting snapshot
            }
        }

        return $collection->all();
    }

    public function all(Criteria $model = null)
    {

        if (!is_null($model))
        {
            $model = $model->toArray();
        }
        else
        {
            $model = [];
        }
        $all        = $this->api->snapshot()->getAll();
        $collection = new Collection();
        foreach ($all as $item)
        {
            $collection->add(new SnapshotItem($item, $this->api));
        }
        return $collection->all();
    }

    public function __call($name, $agruments = null)
    {
        if (method_exists($this->api->snapshot(), $name))
        {
            return $this->api->snapshot()->{$name}(...$agruments);
        }
        throw new \Exception('Method does not exist on API.');
    }

}
