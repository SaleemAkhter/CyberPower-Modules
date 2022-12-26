<?php

namespace ModulesGarden\Servers\OvhVpsAndDedicated\App\Libs\Ovh\Service\Api\Items\Vps;

use ModulesGarden\Servers\OvhVpsAndDedicated\App\Libs\Ovh\Service\Abstracts\AbstractApiItem;
use ModulesGarden\Servers\OvhVpsAndDedicated\App\Libs\Ovh\Models\Vps\Disk\Disk as DiskModel;

/**
 * Class Disk
 *
 * @author Artur Pilch <artur.pi@modulesgarden.com>
 */
class Disk extends AbstractApiItem
{
    public function update($params)
    {
        die('update');
        return $this->put(false, $params);
    }

    public function monitoring($params)
    {
        return $this->get(__FUNCTION__, $params);
    }

    public function usage($params)
    {
        return $this->get('use', $params);
    }
    
    public function model()
    {
        return new DiskModel($this->getInfo());
    }
}
