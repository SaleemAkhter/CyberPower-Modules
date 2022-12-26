<?php

namespace ModulesGarden\Servers\OvhVpsAndDedicated\App\Libs\Ovh\Facade\Items\Vps;

use ModulesGarden\Servers\OvhVpsAndDedicated\App\Libs\Ovh\Facade\Repository\Vps\BaseVpsRepository;

/**
 * Class Disk
 *
 * @author Artur Pilch <artur.pi@modulesgarden.com>
 *
 * @method update($params)
 * @method monitoring($params)
 * @method usage($params)
 * @method model()
 */
class Disk extends BaseVpsRepository
{
    /**
     * @var \ModulesGarden\Servers\OvhVpsAndDedicated\App\Libs\Ovh\Service\Api\Items\Vps\Disk
     */
    protected $item;

    /**
     * @var array
     */
    protected $methods;

    public function __construct($vpsId, $diskId, array $params = [])
    {
        parent::__construct($vpsId, $params);

        $this->item = $this->vps->disks()->one($diskId);
        $this->methods = get_class_methods($this->item);
    }
}