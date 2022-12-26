<?php

namespace ModulesGarden\Servers\OvhVpsAndDedicated\App\Libs\Ovh\Service\Api\Items\Vps;

use ModulesGarden\Servers\OvhVpsAndDedicated\App\Libs\Ovh\Service\Abstracts\AbstractApiItem;

/**
 * Class Access
 *
 * @author Artur Pilch <artur.pi@modulesgarden.com>
 */
class Access extends AbstractApiItem
{
    public function update($params)
    {
        return $this->put(false, $params);
    }

    public function remove()
    {
        return $this->delete();
    }
}