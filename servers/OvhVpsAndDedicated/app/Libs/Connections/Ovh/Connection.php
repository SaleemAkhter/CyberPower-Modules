<?php

namespace ModulesGarden\Servers\OvhVpsAndDedicated\App\Libs\Connections\Ovh;

use Ovh\Api;
use ModulesGarden\Servers\OvhVpsAndDedicated\App\Libs\Connections\Base;
use ModulesGarden\Servers\OvhVpsAndDedicated\App\Libs\Connections\Interfaces\Connection as ConnectionInterface;
/**
 * Class OvhApi
 *
 * @author Artur Pilch <artur.pi@modulesgarden.com>
 */
class Connection extends Base implements ConnectionInterface
{
    /**
     * Set API Connection details
     */
    public function setConnection()
    {
       $this->connection = new Api($this->client->getApiUser() , $this->client->getApiToken(), $this->client->getUrl(), $this->client->getApiConsumer());
    }
}
