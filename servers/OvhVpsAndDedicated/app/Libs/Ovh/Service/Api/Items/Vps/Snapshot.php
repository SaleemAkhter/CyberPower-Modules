<?php

namespace ModulesGarden\Servers\OvhVpsAndDedicated\App\Libs\Ovh\Service\Api\Items\Vps;

use GuzzleHttpOvh\Exception\ClientException;
use ModulesGarden\Servers\OvhVpsAndDedicated\App\Libs\Ovh\Service\Abstracts\AbstractApiItem;
use ModulesGarden\Servers\OvhVpsAndDedicated\App\Libs\Ovh\Models\Vps\Snapshot\Snapshot as SnapshotModel;

/**
 * Class Snapshot
 *
 * @author Artur Pilch <artur.pi@modulesgarden.com>
 */
class Snapshot extends AbstractApiItem
{
    /**
     * @return mixed
     */
    public function getInfo()
    {
        try
        {
            return parent::getInfo();
        }
        catch (\Exception $exception)
        {

            return null;
        }
    }

    public function update($params)
    {
        return $this->put(false, $params);
    }

    public function remove()
    {
        return $this->delete();
    }

    public function revert()
    {
        return $this->post(__FUNCTION__);
    }
    
    public function model()
    {
        $this->getInfo();
        if($this->itemObject)
        {
            return new SnapshotModel($this->itemObject);
        }
        return null;
    }
}