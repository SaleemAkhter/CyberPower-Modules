<?php

namespace ModulesGarden\Servers\OvhVpsAndDedicated\App\Libs\Ovh\Facade\Repository\Vps;

use ModulesGarden\Servers\OvhVpsAndDedicated\App\Libs\Ovh\Facade\BaseRepository;

/**
 * Class VpsBase
 *
 * @author Artur Pilch <artur.pi@modulesgarden.com>
 */
abstract class BaseOrderRepository extends BaseRepository
{
    protected $order;

    public function __construct($orderId, array $params = [])
    {
        parent::__construct($params);

        $this->order = $this->api->me->one($orderId);
    }
}
