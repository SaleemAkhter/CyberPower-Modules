<?php

namespace ModulesGarden\Servers\DigitalOceanDroplets\App\UI\Snapshots\Helpers;

use Exception;
use ModulesGarden\Servers\DigitalOceanDroplets\App\Helpers\AvailableTask;
use ModulesGarden\Servers\DigitalOceanDroplets\App\Helpers\TaskManager;
use ModulesGarden\Servers\DigitalOceanDroplets\App\Libs\DigitalOceanDroplets\Adapters\Client;
use ModulesGarden\Servers\DigitalOceanDroplets\App\Libs\DigitalOceanDroplets\Api;
use ModulesGarden\Servers\DigitalOceanDroplets\Core\Helper\Lang;

/**
 * Description of ProductPage
 *
 * @author Mateusz Pawłowski <mateusz.pa@modulesgarden.com>
 */
class SnapshotManager {

    protected $params = [];

    public function __construct($params) {
        $this->params = $params;
    }

    public function createSnapshot($name) {
        if ($this->checkNumberOfSnapshotIsOver()) {
            throw new Exception(sprintf(Lang::getInstance()->T('snapshotLimitError'), $this->getSnapshotLimit()));
        }
        if ($this->checkActiveSnapshotCreation()) {
            throw new Exception(sprintf(Lang::getInstance()->T('snapshotCreationInProgress'), $this->getSnapshotLimit()));
        }
        $name = trim(str_replace(" ", "", $name));
        $api  = new Api($this->params);
        $info = $api->droplet->snapshot($name);
        return;
    }

    /*
     * Return true, when snapshot creation task has in-progress status.
     */

    public function checkActiveSnapshotCreation() {
        $serviceManager = new \ModulesGarden\Servers\DigitalOceanDroplets\App\Helpers\ServiceManager($this->params);
        foreach ($serviceManager->getTask() as $task) {
            if ($task->type == "snapshot" && $task->status == "in-progress") {
                return true;
            }
        }
    }

    public function deleteSnapshots(array $snapshotIDs = [])
    {
        foreach ($snapshotIDs as $snapshotID)
        {
            $this->deleteSnapshot($snapshotID);
        }
    }

    public function deleteSnapshot($snapshotID) {
        $api                                                      = new Api($this->params);
        $snapshot                                                 = $api->snapshot->one($snapshotID);
        if($snapshot->getItemObject()->resourceId != $this->params['customfields']['serverID']){
            throw new Exception(Lang::getInstance()->absoluteT('serverIDNotMatch'));
        } 
        $snapshot->delete();
        $_SESSION['mgSnapshotCount' . $this->params['serviceid']] -= 1;
    }

    public function restoreFromSnapshot($snapshotID) {
        $api     = new Api($this->params);
        $details = $api->snapshot->one($snapshotID);
        if($details->getItemObject()->resourceId != $this->params['customfields']['serverID']){
            throw new Exception(Lang::getInstance()->absoluteT('serverIDNotMatch'));
        }
        $api->droplet->restore((int) $snapshotID);
    }

    public function getDroplet() {
        $api = new Api($this->params);
        return $api->droplet->one();
    }

    private function getSnapshotLimit() {
        return (new Client($this->params))->getSnapshotLimit();
    }

    public function checkNumberOfSnapshotIsOver() {
        if ($this->getSnapshotLimit() <= count($this->getDroplet()->snapshotIds)) {
            return true;
        }
    }

    public function getSnapshotsToTable() {
        $list = $this->getAllSnapshot();

        return $this->preparePrettyTable($list);
    }

    public function getAllSnapshot() {
        return $this->getDroplet()->snapshotList();
    }

    public function preparePrettyTable($itemList) {
        $allSnap = [];
        foreach ($itemList as $item) {
            $allSnap[] = [
                'id'   => $item->id,
                'name' => $item->name,
                'date' => \Carbon\Carbon::parse($item->createdAt)->toDateTimeString(),
            ];
        }
        return $allSnap;
    }

}
