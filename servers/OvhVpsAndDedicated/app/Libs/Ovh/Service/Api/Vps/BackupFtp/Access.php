<?php

namespace ModulesGarden\Servers\OvhVpsAndDedicated\App\Libs\Ovh\Service\Api\Vps\BackupFtp;

use ModulesGarden\Servers\OvhVpsAndDedicated\App\Libs\Ovh\Service\Abstracts\AbstractApi;
use ModulesGarden\Servers\OvhVpsAndDedicated\App\Helpers\Basics\Collection;
use ModulesGarden\Servers\OvhVpsAndDedicated\App\Libs\Ovh\Service\Api\Items\Vps\Access as AccessApiItem;

/**
 * Class Access
 *
 * @author Artur Pilch <artur.pi@modulesgarden.com>
 */
class Access extends AbstractApi
{
    public function all()
    {
        $response =  $this->get();
        $collection = new Collection();
        foreach ($response as $item)
        {
            $collection->add($this->one($item));
        }
    }

    public function one($item)
    {
        $response = $this->get($item);
        return new AccessApiItem($this->api, $this->client, $this->getPathExpanded($item), $response);
    }

    public function create($params)
    {
        return $this->post(false, $params);
    }
}