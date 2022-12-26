<?php

namespace ModulesGarden\Servers\OvhVpsAndDedicated\App\Libs\Ovh\Service\Api\Items\Order\Vps;

use ModulesGarden\Servers\OvhVpsAndDedicated\App\Libs\Ovh\Service\Abstracts\AbstractApiItem;

/**
 * Class Upgrade
 *
 * @author Artur Pilch <artur.pi@modulesgarden.com>
 */
class Upgrade extends AbstractApiItem
{
    public function run($duration, $model)
    {
        $params = [
            'model' => $model
        ];

        return $this->post($duration, $params);
    }

    public function getDurations($model)
    {
        $params = [
            'model' => $model
        ];

        return $this->get(false, $params);
    }
}
