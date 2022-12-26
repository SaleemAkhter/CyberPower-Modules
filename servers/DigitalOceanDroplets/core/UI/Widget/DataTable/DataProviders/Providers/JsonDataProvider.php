<?php

namespace ModulesGarden\Servers\DigitalOceanDroplets\Core\UI\Widget\DataTable\DataProviders\Providers;

/**
 *
 */
class JsonDataProvider extends \ModulesGarden\Servers\DigitalOceanDroplets\Core\UI\Widget\DataTable\DataProviders\Providers\ArrayDataProvider
{

    public function setData($data)
    {
        $this->data = json_decode($data);
    }
}
