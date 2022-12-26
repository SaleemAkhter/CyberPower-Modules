<?php

namespace ModulesGarden\Servers\OvhVpsAndDedicated\App\Libs\Ovh\Facade\Items\Vps;

use ModulesGarden\Servers\OvhVpsAndDedicated\App\Libs\Ovh\Facade\Repository\Vps\BaseVpsRepository;

/**
 * Class Snapshot
 *
 * @author Artur Pilch <artur.pi@modulesgarden.com>
 * 
 * @method update($params)
 * @method revert()
 * @method model()
 * @method remove()
 */
class Snapshot extends BaseVpsRepository
{
    /**
     * @var \ModulesGarden\Servers\OvhVpsAndDedicated\App\Libs\Ovh\Service\Api\Items\Vps\Snapshot
     */
    protected $item;

    /**
     * @var array
     */
    protected $methods;

    public function __construct($vpsId, array $params = [])
    {
        parent::__construct($vpsId, $params);

        if(!$this->vps)
        {
            return [];
        }
        $this->item = $this->vps->snapshot();
        $this->methods = get_class_methods($this->item);
    }

}