<?php

namespace ModulesGarden\Servers\DirectAdminExtended\App\UI\Client\Backups\Helpers;
use ModulesGarden\DirectAdminExtended\App\Models\BackupPath;
use ModulesGarden\Servers\DirectAdminExtended\Core\UI\Traits\RequestObjectHandler;
use ModulesGarden\Servers\DirectAdminExtended\Core\UI\Traits\WhmcsParams;
use ModulesGarden\Servers\DirectAdminExtended\App\Helpers\ServerParams;
use ModulesGarden\Servers\DirectAdminExtended\App\Libs\DirectAdmin\Models\Command\Backup;
use ModulesGarden\Servers\DirectAdminExtended\App\Models\Hosting;
use ModulesGarden\Servers\DirectAdminExtended\App\UI\Client\Home\Buttons\DirectAdmin;

/**
 * Created by PhpStorm.
 * User: Mateusz Pawłowski
 * Date: 2018-10-11
 * Time: 09:46
 */

class AdminBackups
{
    use RequestObjectHandler, WhmcsParams;

    protected $serverDetails;
    protected $api;


    public function getBackupPath($serverID){

        return BackupPath::where('id', $serverID)->first()->toArray();
    }
    private function getProductDetails($serviceID){
        return Hosting::where('id', $serviceID)->first();
    }
    public function checkToShow(){

        $productDetails =  $this->getProductDetails($this->getRequestValue('id'));

        $backups = BackupPath::where([
            'id' => $this->getRequestValue('adminbackup'),
            'server_id' => $productDetails->server,
            'product_id' => $productDetails->packageid,
        ])->count();
        if($backups > 0){
            return true;
        }
        return false;
    }
    public function isEnable(){
        $backups = BackupPath::where([
            'server_id' => $this->getWhmcsParamByKey('serverid'),
            'product_id' => $this->getWhmcsParamByKey('packageid'),
        ])->count();

        if($backups > 0){
            return true;
        }
        return false;
    }
    public function checkIsEnableRestore($serverID){
        if(empty($serverID)){
            return false;
        }
        $pathSettings = $this->getBackupPath($serverID);
        if($pathSettings['enable_restore'] === "on"){
            return true;
        }
        return false;
    }
    private function setServerDetails($directServerID){
        $this->serverDetails =  ServerParams::getServerParamsById($directServerID);
    }

    public function getLocalBackupsList($serverID = ""){
        if(empty($serverID)){
            return [];
        }
        $pathSettings = $this->getBackupPath($serverID);
        $this->setServerDetails($pathSettings['server_id']);

        $this->setApiObject($this->serverDetails, $pathSettings['admin_access']);

        $data =[
            'localPath' => trim($pathSettings['path'])
        ];
        $backusList = $this->api->backup->getLocalList(new Backup($data));

        return $this->checkAndPrepareResponse($backusList);
    }

    private function checkAndPrepareResponse($response){
        $responseHelper =  new BackupFiles($this->serverDetails, $response);

        if($responseHelper->isLocalFolder()){
            return $responseHelper->getUserBackups();

        }
        return [];
    }
    private function setApiObject($serverDetails, $adminAccess = ""){
        $this->api = new \ModulesGarden\Servers\DirectAdminExtended\App\Libs\DirectAdmin\DirectAdmin($serverDetails);
        if($adminAccess !== "on"){
            $this->api->setUserMode(true);
        }
    }
}