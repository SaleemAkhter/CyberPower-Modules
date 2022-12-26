<?php

namespace ModulesGarden\Servers\OvhVpsAndDedicated\App\Libs\Ovh\Service\Api\Dedicated;

use ModulesGarden\Servers\OvhVpsAndDedicated\App\Libs\Models\Client;
use ModulesGarden\Servers\OvhVpsAndDedicated\App\Libs\Ovh\Service\Api\Items\Dedicated\Task as TaskItem;
use ModulesGarden\Servers\OvhVpsAndDedicated\App\Libs\Ovh\Service\Abstracts\AbstractApi;
use Ovh\Api;

/**
 * Class Ips
 *
 * @author Artur Pilch <artur.pi@modulesgarden.com>
 */
class Task extends AbstractApi
{
    public function __construct(Api $api, Client $client, $path = '')
    {
        parent::__construct($api, $client, $path);
    }

    public function all()
    {
        return $this->get();
    }

    public function one($taskId)
    {
        return new TaskItem($this->api, $this->client, $this->getPathExpanded($taskId));
    }




}