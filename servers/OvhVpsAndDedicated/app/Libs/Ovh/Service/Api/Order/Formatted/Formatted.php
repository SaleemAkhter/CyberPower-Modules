<?php

namespace ModulesGarden\Servers\OvhVpsAndDedicated\App\Libs\Ovh\Service\Api\Order\Formatted;

use ModulesGarden\Servers\OvhVpsAndDedicated\App\Helpers\Server\Constants;
use ModulesGarden\Servers\OvhVpsAndDedicated\App\Libs\Ovh\Service\Abstracts\AbstractApi;

/**
 * Class Formatted
 *
 * @author Artur Pilch <artur.pi@modulesgarden.com>
 */
class Formatted extends AbstractApi
{
    public function vps($params = [])
    {
        if (empty($params))
        {
            $params = [
                Constants::OVH_SUBSIDIARY => $this->client->getOvhSubsidiary()
            ];
        }
        return $this->get(__FUNCTION__, $params);
    }
}