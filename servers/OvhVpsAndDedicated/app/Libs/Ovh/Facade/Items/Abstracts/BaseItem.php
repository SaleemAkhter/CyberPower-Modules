<?php

namespace ModulesGarden\Servers\OvhVpsAndDedicated\App\Libs\Ovh\Facade\Items\Vps\Abstracts;

use ModulesGarden\Servers\OvhVpsAndDedicated\App\Libs\Ovh\Facade\Repository\Vps\BaseVpsRepository;

/**
 * Class BaseItem
 *
 * @author Artur Pilch <artur.pi@modulesgarden.com>
 */
class BaseItem extends BaseVpsRepository
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
        $this->methods = get_class_methods($this->getItem());
    }

    public function getItem()
    {
        return $this->item;
    }
}