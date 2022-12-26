<?php

namespace ModulesGarden\Servers\OvhVpsAndDedicated\App\Libs\Ovh\Service\Api\Vps;

use ModulesGarden\Servers\OvhVpsAndDedicated\App\Libs\Ovh\Service\Abstracts\AbstractApi;
use ModulesGarden\Servers\OvhVpsAndDedicated\App\Libs\Ovh\Service\Api\Items\Vps\Distribution as DistributionItem;
use ModulesGarden\Servers\OvhVpsAndDedicated\App\Libs\Ovh\Models\Vps\Distribution\Model as DistributionModel;

/**
 * Class Distribution
 *
 * @author Artur Pilch <artur.pi@modulesgarden.com>
 */
class Distribution extends AbstractApi
{
    public function info()
    {
        $response =  $this->get();
        return new DistributionItem($this->api, $this->client, $this->path, $response);
    }

    public function software()
    {
        return new Software($this->api, $this->client, $this->getPathExpanded(__FUNCTION__));
    }

    public function model()
    {
        return new DistributionModel($this->get());
    }
}