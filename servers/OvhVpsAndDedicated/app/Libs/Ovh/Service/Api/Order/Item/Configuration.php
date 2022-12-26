<?php

namespace ModulesGarden\Servers\OvhVpsAndDedicated\App\Libs\Ovh\Service\Api\Order\Item;

use ModulesGarden\Servers\OvhVpsAndDedicated\App\Helpers\Basics\Collection;
use ModulesGarden\Servers\OvhVpsAndDedicated\App\Libs\Ovh\Service\Abstracts\AbstractApi;
use ModulesGarden\Servers\OvhVpsAndDedicated\App\Libs\Ovh\Service\Api\Items\Order\Item\Configuration as ConfigurationItem;

/**
 * Class Configuration
 *
 * @author Artur Pilch <artur.pi@modulesgarden.com>
 */
class Configuration extends AbstractApi
{
    public function all()
    {
        $configurations = $this->get();
        $collection = new Collection();

        foreach ($configurations as $config)
        {
            $collection->add($this->one($config));
        }

        return $collection->all();
    }

    public function one($id)
    {
        return new ConfigurationItem($this->api, $this->client, $this->getPathExpanded($id));
    }

    public function add($params)
    {
        return $this->post(false, $params);
    }
}