<?php

namespace ModulesGarden\Servers\GoogleCloudVirtualMachines\App\UI\Client\Snapshots\Helpers;

use Exception;
use ModulesGarden\Servers\GoogleCloudVirtualMachines\App\Helpers\AvailableTask;
use ModulesGarden\Servers\GoogleCloudVirtualMachines\App\Helpers\TaskManager;
use ModulesGarden\Servers\GoogleCloudVirtualMachines\Core\Helper\Lang;
use ModulesGarden\Servers\GoogleCloudVirtualMachines\App\Traits\ComputeTrait;
use ModulesGarden\Servers\GoogleCloudVirtualMachines\App\Traits\ProductSetting;
use ModulesGarden\Servers\GoogleCloudVirtualMachines\App\Api\ProjectFactory;
use ModulesGarden\Servers\GoogleCloudVirtualMachines\Core\UI\Traits\WhmcsParams;

/**
 * Description of ProductPage
 *
 * @author Mateusz Pawłowski <mateusz.pa@modulesgarden.com>
 */
class SnapshotManager {

    use ComputeTrait, ProductSetting, WhmcsParams;
    

    public function createSnapshot($name) {
        $project = (new ProjectFactory())->fromParams();
        
        $snap = new \Google_Service_Compute_Snapshot();
        $snap->setName($name);
        $this->compute()->disks->createSnapshot($project, $this->productSetting()->zone, $this->getDiskName($project), $snap);               
    }
    private function separateDiskName($disk){
        $disc = explode('-', $disk);
        return $disc[0];
    }
    
    private function getDiskName($project){
        $disks = $this->compute()->disks->listDisks($project, $this->productSetting()->zone)->items;

        foreach($disks as $disk){
            $user = explode('/',$disk->users[0]);
            $diskname = array_pop($user);
            if( $this->separateDiskName($diskname) == $this->getDomainName() ||  $diskname == $this->getWhmcsParamByKey('domain') ){
                return $diskname;
            }
        }
        return;
    }
    
    private function getDomainName(){
        $domain = explode('.',$this->getWhmcsParamByKey('domain'));
        return $domain[0];
    }

    /*
     * Return true, when snapshot creation task has in-progress status.
     */

    public function checkActiveSnapshotCreation() {
        $serviceManager = new \ModulesGarden\Servers\GoogleCloudVirtualMachines\App\Helpers\ServiceManager($this->params);
        foreach ($serviceManager->getTask() as $task) {
            if ($task->type == "snapshot" && $task->status == "in-progress") {
                return true;
            }
        }
    }

    public function deleteSnapshot($snapshot) {       
        $project = (new ProjectFactory())->fromParams();
        $this->compute()->snapshots->delete($project, $snapshot); 
        return;
    }

    public function restoreSnapshot($snapshot){
        $project = (new ProjectFactory())->fromParams();      
        $snapshotInfo = $this->getSnapshot($snapshot);
        
        $disk = new \Google_Service_Compute_Disk();
        $disk->setName($this->createDiskName($snapshot));
        $disk->setSizeGb($snapshotInfo->diskSizeGb);
        $disk->setType('zones/' . $this->productSetting()->zone . '/diskTypes/' . $this->productSetting()->diskType); 
        $disk->setSourceSnapshotId($snapshot);
        
        $this->compute()->disks->insert($project, $this->productSetting()->zone, $disk);
        return;
    }
    
    private function createDiskName($snapshot){
        $name = 'cf-' . $snapshot;
        return $name;
    }
    
    
    
    private function getSnapshot($name){
        $project = (new ProjectFactory())->fromParams();
        return $this->compute()->snapshots->get($project, $name);
    }

    public function getSnapshotsToTable() {
        $list = $this->getAllSnapshot();

        return $this->preparePrettyTable($this->filterSnapshots($list));
    }
    
    private function filterSnapshots($snapshots){
        $filteredArray = [];
        foreach ($snapshots as $snapshot){
            if($snapshot->status != 'DELETING'){
                array_push($filteredArray, $snapshot);
            }
        }
        return $filteredArray;
    }

    public function getAllSnapshot() {
        $project = (new ProjectFactory())->fromParams();
        return $this->compute()->snapshots->listSnapshots($project);
    }
    
    public function getAllDisks(){
        $project = (new ProjectFactory())->fromParams();
        return $this->compute()->disks->listDisks($project, $this->productSetting()->zone);
    }

    public function preparePrettyTable($itemList) {
        $allSnap = [];

        /* Checking if responded creation time is in old response style */
        if($itemList && $itemList[0]->createdAt)
        {
            foreach ($itemList as $item) {
                $allSnap[] = [
                    'id'   => $item->name,
                    'date' => \Carbon\Carbon::parse($item->createdAt)->toDateTimeString(),
                ];
            }
            return $allSnap;
        }

        /* If new style, need to additionally parse date into local server timezone */
        $localTimezone = (\Carbon\Carbon::now())->timezoneName;
        foreach ($itemList as $item) {
            $allSnap[] = [
                'id'   => $item->name,
                'date' => (\Carbon\Carbon::parse($item->creationTimestamp))
                    ->setTimezone($localTimezone)->toDateTimeString(),
            ];
        }
        return $allSnap;
    }

}
