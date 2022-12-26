<?php

namespace ModulesGarden\Servers\OvhVpsAndDedicated\App\Libs\Ovh\Service\Api\Items\Dedicated;

use ModulesGarden\Servers\OvhVpsAndDedicated\App\Libs\Api\Exception\ApiException;
use ModulesGarden\Servers\OvhVpsAndDedicated\App\Libs\Ovh\Models\Dedicated\Server as ServerModel;
use ModulesGarden\Servers\OvhVpsAndDedicated\App\Libs\Ovh\Service\Abstracts\AbstractApiItem;
use ModulesGarden\Servers\OvhVpsAndDedicated\App\Libs\Ovh\Service\Api\Dedicated\Features;
use ModulesGarden\Servers\OvhVpsAndDedicated\App\Libs\Ovh\Service\Api\Dedicated\Install;
use ModulesGarden\Servers\OvhVpsAndDedicated\App\Libs\Ovh\Service\Api\Dedicated\Ips;
use ModulesGarden\Servers\OvhVpsAndDedicated\App\Libs\Ovh\Service\Api\Dedicated\Boot;
use ModulesGarden\Servers\OvhVpsAndDedicated\App\Libs\Ovh\Service\Api\Dedicated\Task;

/**
 * Class Server
 *
 * @author Artur Pilch <artur.pi@modulesgarden.com>
 */
class Server extends AbstractApiItem
{
    public function model()
    {
        return new ServerModel($this->getInfo());
    }

    public function ips()
    {
        return new Ips($this->api, $this->client, $this->getPathExpanded(__FUNCTION__));
    }

    public function task()
    {
        return new Task($this->api, $this->client, $this->getPathExpanded(__FUNCTION__));
    }

    public function features()
    {
        return new Features($this->api, $this->client, $this->getPathExpanded(__FUNCTION__));
    }

    public function mrtg($params)
    {
        return $this->get(__FUNCTION__, $params);
    }

    public function terminate()
    {
        return $this->post(__FUNCTION__);
    }

    public function install()
    {
        return new Install($this->api, $this->client, $this->getPathExpanded(__FUNCTION__));
    }

    public function reboot()
    {
        return $this->post(__FUNCTION__);
    }

    public function boot()
    {
        return new Boot($this->api, $this->client, $this->getPathExpanded(__FUNCTION__));
    }

    public function update($params)
    {
        return $this->put(false, $params);
    }

    public function makeBoot($bootType)
    {
        $bootObject = $this->boot();
        $boots = $bootObject->allToModel($bootType);

        $chosen = $boots[0];

        if(!$chosen)
        {
            throw new ApiException("There is not boot '{$bootType}' available for this server");
        }

        $params = [
            'bootId' => $chosen->getBootId(),
        ];

        return $this->update($params);
    }
}
