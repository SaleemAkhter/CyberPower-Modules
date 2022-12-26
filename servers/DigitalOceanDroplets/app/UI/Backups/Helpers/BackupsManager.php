<?php

namespace ModulesGarden\Servers\DigitalOceanDroplets\App\UI\Backups\Helpers;

use Exception;
use ModulesGarden\Servers\DigitalOceanDroplets\App\Libs\DigitalOceanDroplets\Api;

/**
 * Description of ProductPage
 *
 * @author Mateusz Pawłowski <mateusz.pa@modulesgarden.com>
 */
class BackupsManager
{

    protected $params = [];

    public function __construct($params)
    {
        $this->params = $params;
    }

    public function getDroplet()
    {
        $api = new Api($this->params);
        return $api->droplet->one();
    }
    public function restoreFromBackup($backupID)
    {
        $api     = new Api($this->params);
        $api->droplet->restore((int)$backupID);

    }
    public function getBackupsToTable()
    {
        $list = $this->getAllBackups();
        return $this->preparePrettyTable($list);
    }

    public function getAllBackups()
    {
        return $this->getDroplet()->getBackups();
    }

    public function preparePrettyTable($itemList)
    {
        $allBackups = [];

        foreach ($itemList as $item)
        {

            $allBackups[] = [
                'id'   => $item->id,
                'name' => $item->name,
                'date' => \Carbon\Carbon::parse($item->createdAt)->toDateTimeString(),
            ];
        }
        return $allBackups;
    }
    
    public function backupsEnabled(){
        if($this->getDroplet()->backupsEnabled){
            return true;
        } 
        return false;
    }

    public function getBackupTime($droplet = null)
    {
        $droplet = $droplet ? $droplet : $this->getDroplet();
        return [
            'start' => $this->formatDate($droplet->nextBackupWindow->start),
            'stop'  => $this->formatDate($droplet->nextBackupWindow->end),
        ];
    }

    protected function formatDate($date)
    {
        $dt = new \DateTime($date);
        return $dt->format('l ga');
    }
}