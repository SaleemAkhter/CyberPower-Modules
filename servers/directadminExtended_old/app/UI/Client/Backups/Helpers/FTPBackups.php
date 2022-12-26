<?php

namespace ModulesGarden\Servers\DirectAdminExtended\App\UI\Client\Backups\Helpers;
use ModulesGarden\Servers\DirectAdminExtended\App\Models\BackupPath;
use ModulesGarden\DirectAdminExtended\App\Models\FTPEndPoints;
use ModulesGarden\Servers\DirectAdminExtended\App\Helpers\ServerParams;
use ModulesGarden\Servers\DirectAdminExtended\App\Libs\DirectAdmin\Models\Command\Backup;
use ModulesGarden\Servers\DirectAdminExtended\App\Models\Hosting;
use ModulesGarden\Servers\DirectAdminExtended\App\UI\Client\Home\Buttons\DirectAdmin;
use ModulesGarden\Servers\DirectAdminExtended\Core\UI\Traits\RequestObjectHandler;
use ModulesGarden\Servers\DirectAdminExtended\Core\UI\Traits\WhmcsParams;
use function ModulesGarden\Servers\DirectAdminExtended\Core\Helper;

/**
 * Created by PhpStorm.
 * User: Mateusz Pawłowski
 * Date: 2018-10-11
 * Time: 09:46
 */

class FTPBackups
{
    use RequestObjectHandler, WhmcsParams;

    protected $serverDetails;
    protected $api;


    /**
     * @return array
     */
    public function getSettings()
    {
        $ftpSettings =  $this->getFTPBackupSettings();

        if(empty($ftpSettings)) {
            return [];
        }
        foreach($ftpSettings as $setting)
        {
            return [
                'ftpIp' => $setting['host'],
                'ftpUsername' => $setting['user'],
                'ftpPassword' => $setting['password'],
                'ftpPath' => $setting['path'],
                'ftpPort' => $setting['port'],
                'ftpSecure' => $setting['admin_access'],
            ];
        }

    }


    public function getBackupSettings($serverID){

        $check = FTPEndPoints::where('id', $serverID)->first();

        if(!is_null($check)){
            return $check->toArray();
        }

        return [];
    }

    private function getProductDetails($serviceID){
        return Hosting::where('id', $serviceID)->first();
    }
    public function checkToShow(){

        $productDetails =  $this->getProductDetails($this->getRequestValue('id'));

        $backups = FTPEndPoints::where([
            'id' => $this->getRequestValue('ftpbackup'),
            'server_id' => $productDetails->server,
            'product_id' => $productDetails->packageid,
        ])->count();

        if($backups > 0){
            return true;
        }
        return false;
    }

    public function isEnable(){
        $backups = FTPEndPoints::where([
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
        $backupSettings = $this->getBackupSettings($serverID);

        if($backupSettings['enable_restore'] === "on"){
            return true;
        }
        return false;
    }
    private function setServerDetails($directServerID){
        $this->serverDetails =  ServerParams::getServerParamsById($directServerID);
    }

    public function getFTPBackupsList($serverID = ""){
        if(empty($serverID)){
            return [];
        }
        $pathSettings = $this->getBackupSettings($serverID);

        $this->setServerDetails($pathSettings['server_id']);

        $this->setApiObject($this->serverDetails, $pathSettings['admin_access']);

        $data =[
            'ftpIp'        => $pathSettings['host'],
            'ftpPassword'  => $pathSettings['password'],
            'ftpPath'      => $pathSettings['path'],
            'ftpPort'      => $pathSettings['port'],
            'ftpUsername'  => $pathSettings['user'],
        ];

        $backusList = $this->api->backup->getListFromFTP(new Backup($data));

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