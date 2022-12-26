<?php

namespace ModulesGarden\Servers\HetznerVps\App\UI\FloatingIP\Helpers;

use LKDev\HetznerCloud\Models\Images\Image;
use LKDev\HetznerCloud\Models\FloatingIP\ISO;
use ModulesGarden\Servers\HetznerVps\App\Enum\ConfigurableOption;
use ModulesGarden\Servers\HetznerVps\App\Helpers\FieldsProvider;
use ModulesGarden\Servers\HetznerVps\App\Libs\HetznerVps\Api;
use ModulesGarden\Servers\HetznerVps\Core\Helper\Lang;

/**
 * Description of ImageManager
 *
 * @author Mateusz Pawłowski <mateusz.pa@modulesgarden.com>
 */
class FloatingIPManager
{
    protected $params = [];
    private $serverId;

    public function __construct($params)
    {
        $this->params = $params;
        $this->serverId = $this->params['customfields']['serverID'];
    }

    public function read($id)
    {
        $api = new Api($this->params);
        $entity = $api->floatingIps()->get($id);
        return $entity;
    }

    public function get()
    {
        $api = new Api($this->params);
        $allFloatingIPs = $api->floatingIps()->all();
        $allFloatingIPs = array_filter(
            $allFloatingIPs,
            function ($floatingIP) {
                return $floatingIP->server == $this->serverId;
            });
        return $allFloatingIPs;

    }
}
