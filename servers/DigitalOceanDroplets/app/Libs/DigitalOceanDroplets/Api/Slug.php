<?php

namespace ModulesGarden\Servers\DigitalOceanDroplets\App\Libs\DigitalOceanDroplets\Api;

use ModulesGarden\Servers\DigitalOceanDroplets\App\Libs\DigitalOceanDroplets\Abstracts\AbstractApi;
use ModulesGarden\Servers\DigitalOceanDroplets\App\Libs\DigitalOceanDroplets\Base\Collection;
use ModulesGarden\Servers\DigitalOceanDroplets\App\Libs\DigitalOceanDroplets\Base\Items\Slug as SlugItem;

class Slug extends AbstractApi
{

    public function all(): array
    {
        $all = $this->api->size()->getAll();
        $formattedSlugs = [];
        foreach ($all as $slug){
            $formattedSlugs[$slug->slug] = $slug;
        }

        return $formattedSlugs;
    }
}