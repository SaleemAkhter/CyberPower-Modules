<?php

namespace ModulesGarden\Servers\DirectAdminExtended\App\UI\Client\Backups\Providers;

use ModulesGarden\Servers\DirectAdminExtended\App\Helpers\ProviderApi;
use ModulesGarden\Servers\DirectAdminExtended\App\UI\Client\Backups\Helpers\AdminBackups;
use ModulesGarden\Servers\DirectAdminExtended\App\UI\Client\Backups\Helpers\FTPBackups;
use ModulesGarden\Servers\DirectAdminExtended\Core\UI\ResponseTemplates;
use ModulesGarden\Servers\DirectAdminExtended\App\Libs\DirectAdmin\Models;

class BackupsExtended extends ProviderApi
{

    public function read()
    {

        $data = json_decode(base64_decode($this->getRequestValue('index')));

        $this->data['id']       = $data->id;
        $this->data['backup']   = $data->name;
    }


    public function restoreLocal()
    {
        $backupHelper = new AdminBackups();
        $backupPath = $backupHelper->getBackupPath($this->getRequestValue('adminbackup'));

        $data   = [
            'backupDomain'  => $this->getWhmcsParamByKey('domain'),
            'file'          => $this->formData['backup'],
            'localPath'     => $backupPath['path'],
            'selectID'      => $this->formData['id'],
        ];

        if($backupPath['admin_access'] == "on"){
            $this->loadApi();
            $api = $this->api;
        }else{
            $this->loadUserApi();
            $api = $this->loadUserApi();
        }

        $api->backup->restoreLocal(new Models\Command\Backup($data));

        return (new  ResponseTemplates\DataJsonResponse())->setMessageAndTranslate('backupStartRestoration');
    }

    public function restoreFTP()
    {
        $backupHelper = new FTPBackups();
        $backupPath = $backupHelper->getBackupSettings($this->getRequestValue('ftpbackup'));

        $data   = [
            'backupDomain'  => $this->getWhmcsParamByKey('domain'),
            'ftpIp'        => $backupPath['host'],
            'ftpPassword'  => $backupPath['password'],
            'ftpPath'      => $backupPath['path'],
            'ftpPort'      => $backupPath['port'],
            'ftpUsername'  => $backupPath['user'],
            'file'          => $this->formData['backup'],
            'localPath'     => $backupPath['path'],
            'selectID'      => $this->formData['id'],
        ];

        if($backupPath['admin_access'] == "on"){
            $this->loadApi();
            $api = $this->api;
        }else{
            $this->loadUserApi();
            $api = $this->loadUserApi();
        }

        $api->backup->restoreFTP(new Models\Command\Backup($data));

        return (new  ResponseTemplates\DataJsonResponse())->setMessageAndTranslate('backupStartRestoration');
    }
}
