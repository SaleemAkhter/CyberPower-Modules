<?php

namespace ModulesGarden\Servers\DigitalOceanDroplets\App\Libs\DigitalOceanDroplets\Base\Items;

use ModulesGarden\Servers\DigitalOceanDroplets\App\Libs\DigitalOceanDroplets\Base\AbstractItems;

/**
 * Description of Droplet
 *
 * @author Mateusz Pawłowski <mateusz.pa@modulesgarden.com>
 * 
 * 

 * 
 * @property $id
 * @property $name
 * @property $memory
 * @property $vcpus
 * @property $disk
 * @property $region
 * @property $image
 * @property $kernel
 * @property $size
 * @property $sizeSlug
 * @property $locked
 * @property $createdAt
 * @property $status
 * @property $tags
 * @property $networks
 * @property $backupIds
 * @property $volumeIds
 * @property $snapshotIds
 * @property $features
 * @property $backupsEnabled
 * @property $privateNetworkingEnabled
 * @property $ipv6Enabled
 * @property $virtIOEnabled
 * @property $nextBackupWindow



 */
class Droplet extends AbstractItems
{

    protected $primaryKey = 'id';
    protected $itemType   = 'droplet';

    public function snapshotList()
    {

        if (is_array($this->snapshotIds))
        {
            $snapshotAPI = new \ModulesGarden\Servers\DigitalOceanDroplets\App\Libs\DigitalOceanDroplets\Api\Snapshot($this->api);

            return $snapshotAPI->getAssgined($this->snapshotIds);
        }
    }
    public function volumeList()
    {
        if (is_array($this->volumeIds))
        {
            $volumeAPI = new \ModulesGarden\Servers\DigitalOceanDroplets\App\Libs\DigitalOceanDroplets\Api\Volume($this->api);

            return $volumeAPI->getAssgined($this->volumeIds);
        }
    }
    
    public function getItemType(){
        return $this->itemType;
    }

}
