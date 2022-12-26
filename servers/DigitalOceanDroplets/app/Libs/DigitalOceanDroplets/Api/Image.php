<?php

namespace ModulesGarden\Servers\DigitalOceanDroplets\App\Libs\DigitalOceanDroplets\Api;

use ModulesGarden\Servers\DigitalOceanDroplets\App\Libs\DigitalOceanDroplets\Abstracts\AbstractApi;
use ModulesGarden\Servers\DigitalOceanDroplets\App\Libs\DigitalOceanDroplets\Base\Collection;
use ModulesGarden\Servers\DigitalOceanDroplets\App\Libs\DigitalOceanDroplets\Base\Items\Image as ImageItem;
use ModulesGarden\Servers\DigitalOceanDroplets\App\Libs\DigitalOceanDroplets\Models\Images\Criteria;

/**
 * Description of Connection
 *
 * @author Mateusz Pawłowski <mateusz.pa@modulesgarden.com>
 *
 * 
 */
class Image extends AbstractApi
{

    public function one($imageID = null)
    {
        if (is_null($imageID))
        {
            $imageID = $this->client->getImage();
        }
        $response = $this->api->image()->getBySlug($imageID);
        return new ImageItem($response, $this->api);
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
        $all        = $this->api->image()->getAll($model);
        $collection = new Collection();
        foreach ($all as $item)
        {
            $collection->add(new ImageItem($item, $this->api));
        }
        return $collection->all();
    }

    public function __call($name, $agruments = null)
    {
        if (method_exists($this->api->image(), $name))
        {
            return $this->api->image()->{$name}($this->client->getImage(), ...$agruments);
        }
        throw new \Exception('Method does not exist on API.');
    }

}
