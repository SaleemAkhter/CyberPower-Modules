<?php

namespace ModulesGarden\Servers\DigitalOceanDroplets\App\Libs\DigitalOceanDroplets\Api;

use DigitalOceanV2\DigitalOceanV2;
use ModulesGarden\Servers\DigitalOceanDroplets\App\Libs\DigitalOceanDroplets\Abstracts\AbstractApi;
use ModulesGarden\Servers\DigitalOceanDroplets\App\Libs\DigitalOceanDroplets\Adapters\Client;
use ModulesGarden\Servers\DigitalOceanDroplets\App\Libs\DigitalOceanDroplets\Base\Collection;
use ModulesGarden\Servers\DigitalOceanDroplets\App\Libs\DigitalOceanDroplets\Base\Items\Projects as ProjectItem;
use ModulesGarden\Servers\DigitalOceanDroplets\App\Libs\DigitalOceanDroplets\Models\Projects\Resources;

/**
 * Description of Connection
 *
 * @author Mateusz Pawłowski <mateusz.pa@modulesgarden.com>
 * 

 * 
 */
class Projects extends AbstractApi
{
    public function all()
    {
        $all        = $this->api->projects()->getAll();


        $collection = new Collection();
        foreach ($all as $item)
        {
            $collection->add(new ProjectItem($item, $this->api));
        }
        return $collection->all();
    }

    public function assign(Resources $model, $projectID = null)
    {

        if(is_null($projectID))
        {
            $projectID = $this->client->getProject();
        }

        return $this->api->projects()->assign($projectID, $model->toArray());

    }

}
