<?php

namespace ModulesGarden\Servers\DigitalOceanDroplets\App\Libs\DigitalOceanDroplets\Api;

use ModulesGarden\Servers\DigitalOceanDroplets\App\Libs\DigitalOceanDroplets\Abstracts\AbstractApi;
use ModulesGarden\Servers\DigitalOceanDroplets\App\Libs\DigitalOceanDroplets\Base\Collection;
use ModulesGarden\Servers\DigitalOceanDroplets\App\Libs\DigitalOceanDroplets\Base\Items\Region as RegionItem;
use ModulesGarden\Servers\DigitalOceanDroplets\App\Libs\DigitalOceanDroplets\Models\Images\Criteria;

/**
 * Description of Connection
 *
 * @author Mateusz Pawłowski <mateusz.pa@modulesgarden.com>
 * 

 * 
 */
class Region extends AbstractApi
{

    public function all()
    {
        $all        = $this->api->region()->getAll();
        $collection = new Collection();
        foreach ($all as $item)
        {
            $collection->add(new RegionItem($item, $this->api));
        }
        return $collection->all();
    }

}
