<?php

namespace ModulesGarden\Servers\OvhVpsAndDedicated\App\Libs\Ovh\Service\Api\Items\Vps;

use ModulesGarden\Servers\OvhVpsAndDedicated\App\Libs\Ovh\Service\Abstracts\AbstractApiItem;

/**
 * Class AutomatedBackup
 *
 * @author Artur Pilch <artur.pi@modulesgarden.com>
 */
class AutomatedBackup extends AbstractApiItem
{
    public function attachedBackup()
    {
        return $this->get(__FUNCTION__);
    }

    public function detachBackup($params = [])
    {
        return $this->post(__FUNCTION__, $params);
    }

    public function restore($params = [])
    {
        return $this->post(__FUNCTION__, $params);
    }

    public function restorePoints($params = [])
    {
        return $this->get(__FUNCTION__, $params);
    }
}