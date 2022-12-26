<?php

namespace ModulesGarden\Servers\OvhVpsAndDedicated\App\Libs\Ovh\Service\Api\Items\Vps;

use ModulesGarden\Servers\OvhVpsAndDedicated\App\Libs\Ovh\Service\Abstracts\AbstractApiItem;
use ModulesGarden\Servers\OvhVpsAndDedicated\App\Libs\Ovh\Models\Vps\IP\IP as IPModel;

/**
 * Class IP
 *
 * @author Artur Pilch <artur.pi@modulesgarden.com>
 */
class IP extends AbstractApiItem
{
    public function update($reverse)
    {
        $params = [
            'reverse' => $reverse
        ];

        return $this->put(false, $params);
    }

    public function remove()
    {
        die('remove');
        $this->delete();
    }
    
    public function model()
    {
        return new IPModel($this->getInfo());
    }

}