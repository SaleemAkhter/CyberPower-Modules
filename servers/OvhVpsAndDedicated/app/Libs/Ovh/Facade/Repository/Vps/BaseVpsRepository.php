<?php

namespace ModulesGarden\Servers\OvhVpsAndDedicated\App\Libs\Ovh\Facade\Repository\Vps;

use ModulesGarden\Servers\OvhVpsAndDedicated\App\Libs\Ovh\Facade\BaseRepository;

/**
 * Class VpsBase
 *
 * @author Artur Pilch <artur.pi@modulesgarden.com>
 */
abstract class BaseVpsRepository extends BaseRepository
{
    protected $vps;

    protected $vpsId;

    public function __construct($vpsId = false, array $params = [])
    {
        parent::__construct($params);

        $this->vpsId = $vpsId ? $vpsId : $this->api->getClient()->getServiceName();
        $this->vps = $this->api->vps->one($this->vpsId);
    }
}
