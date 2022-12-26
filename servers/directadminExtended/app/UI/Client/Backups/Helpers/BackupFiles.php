<?php

namespace ModulesGarden\Servers\DirectAdminExtended\App\UI\Client\Backups\Helpers;
use ModulesGarden\Servers\DirectAdminExtended\App\Models\BackupPath;
use ModulesGarden\Servers\DirectAdminExtended\App\Models\FTPEndPoints;
use ModulesGarden\Servers\DirectAdminExtended\App\Helpers\ServerParams;
use ModulesGarden\Servers\DirectAdminExtended\App\Libs\DirectAdmin\Models\Command\Backup;
use ModulesGarden\Servers\DirectAdminExtended\App\UI\Client\Home\Buttons\DirectAdmin;
use ModulesGarden\Servers\DirectAdminExtended\Core\Models\Whmcs\Server;
use ModulesGarden\Servers\DirectAdminExtended\Core\UI\Traits\WhmcsParams;

/**
 * Created by PhpStorm.
 * User: Mateusz Pawłowski
 * Date: 2018-10-11
 * Time: 09:46
 */

class BackupFiles
{
    use WhmcsParams;

    protected $response;
    protected $serverDetails;


    public function __construct($serverDetails, $response)
    {
        $this->serverDetails    = $serverDetails;
        $this->response         = $response;
    }

    public function isLocalFolder(){
        return ($this->response['message'] == "yes")? true : false;
    }

    public function getAllBackupFiles(){
        $allFiles = [];

        foreach($this->response as $key => $fileName){
            if(strpos($key, 'file') === false || $key === "num_files"){
                continue;
            }

            $allFiles[str_replace('file', '', $key)] = $fileName;
        }

        return $allFiles;

    }

    public function getUserBackups($userName = ""){
        $allBackups = $this->getAllBackupFiles();

        $userBackups = [];
        foreach($allBackups as $key => $backupName){

            if($this->getWhmcsParamByKey('producttype') == 'reselleraccount')
            {
                if(preg_match("/^reseller.admin." . $this->getWhmcsParamByKey('username') . ".((tar.gz)|(tar.zst))$/", $backupName) || preg_match("/^user." . $this->getWhmcsParamByKey('username') . ".(.)+.((tar.gz)|(tar.zst))$/", $backupName))
                {
                       $userBackups[] = [
                           'id' => $key,
                           'name' => $backupName
                       ];
                }
            }else
            {
                if(preg_match("/^user." .$this->serverDetails['serverusername'] . "." . $this->getWhmcsParamByKey('username') . ".((tar.gz)|(tar.zst))$/", $backupName) || preg_match("/^" . $this->getWhmcsParamByKey('username') . ".((tar.gz)|(tar.zst))$/", $backupName))
                {
                    $userBackups[] = [
                        'id' => $key,
                        'name' => $backupName
                    ];
                }
            }

        }
        return $userBackups;
    }

}
