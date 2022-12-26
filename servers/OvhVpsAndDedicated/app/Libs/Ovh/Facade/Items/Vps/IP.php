<?php

namespace ModulesGarden\Servers\OvhVpsAndDedicated\App\Libs\Ovh\Facade\Items\Vps;

use ModulesGarden\Servers\OvhVpsAndDedicated\App\Libs\Ovh\Facade\Repository\Abstracts\VpsBase;
use ModulesGarden\Servers\OvhVpsAndDedicated\App\Libs\Ovh\Facade\Repository\Vps\BaseVpsRepository;

/**
 * Class IP
 *
 * @author Artur Pilch <artur.pi@modulesgarden.com>
 * 
 * @method update($params = [])
 * @method remove()
 * @method model()
 *
 */
class IP extends BaseVpsRepository
{
    /**
     * @var \ModulesGarden\Servers\OvhVpsAndDedicated\App\Libs\Ovh\Service\Api\Items\Vps\IP
     */
    protected $item;

    /**
     * @var array
     */
    protected $methods;

    public function __construct($ipId, $vpsId = false, array $params = [])
    {
        parent::__construct($vpsId, $params);

        $this->item = $this->vps->ips()->one($ipId);
        $this->methods = get_class_methods($this->item);
    }
}
