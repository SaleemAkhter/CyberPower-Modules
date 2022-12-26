<?php

namespace ModulesGarden\Servers\OvhVpsAndDedicated\App\Libs\Ovh\Facade\Repository\Dedicated;

use ModulesGarden\Servers\OvhVpsAndDedicated\App\Libs\Ovh\Facade\BaseRepository;

/**
 * Class VpsBase
 *
 * @author Artur Pilch <artur.pi@modulesgarden.com>
 */
abstract class BaseDedicatedRepository extends BaseRepository
{
    protected $server;

    protected $serverId;

    public function __construct($serverId = false, array $params = [])
    {
        parent::__construct($params);

        $this->serverId = $serverId ? $serverId : $this->api->getClient()->getServiceName();
        $this->server = $this->api->dedicated->server()->one($this->serverId);
    }
}
