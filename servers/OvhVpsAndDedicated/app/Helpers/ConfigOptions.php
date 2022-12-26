<?php

namespace ModulesGarden\Servers\OvhVpsAndDedicated\App\Helpers;

use ModulesGarden\Servers\OvhVpsAndDedicated\App\Enum\CustomField;
use ModulesGarden\Servers\OvhVpsAndDedicated\Core\Traits\Lang;


/**
 * Description of ConfigOptions
 *
 * @author Artur Pilch <artur.pi@modulesgarden.com>
 */
class ConfigOptions
{
    use Lang;

    const OVH_SERVER_FIELD = 'serverName|Server Name';

    const TEMPLATE_DIR = '/app/UI/Admin/Templates/configuration/pages/productConfiguration';

    public function __construct($productId)
    {
        $this->createCustomFields($productId);
    }

    private function createCustomFields($productId)
    {
        CustomFields::create($productId, SELF::OVH_SERVER_FIELD);

    }
}

