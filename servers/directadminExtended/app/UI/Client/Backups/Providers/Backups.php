<?php

namespace ModulesGarden\Servers\DirectAdminExtended\App\UI\Client\Backups\Providers;

use ModulesGarden\Servers\DirectAdminExtended\App\Helpers\ProviderApi;
use ModulesGarden\Servers\DirectAdminExtended\Core\UI\Interfaces\ClientArea;
use ModulesGarden\Servers\DirectAdminExtended\Core\UI\ResponseTemplates;
use ModulesGarden\Servers\DirectAdminExtended\App\Libs\DirectAdmin\Models;

class Backups extends ProviderApi implements ClientArea
{

    public function read()
    {
        if($this->getRequestValue('index') == 'restoreForm' || $this->getRequestValue('index') == 'editForm')
        {
            return;
        }

        if($this->getRequestValue('index') == 'deleteForm') {
            $this->data['backup']   = $this->actionElementId;
            return;
        }

        parent::read();
        $data   = [
            'backupDomain'  => $this->getWhmcsParamByKey('domain'),
            'file'          => $this->actionElementId
        ];
        if($this->getWhmcsParamByKey('producttype')  == "reselleraccount" )
        {
            $this->loadResellerApi();
            $domains=$this->resellerApi->domain->lists();
            $domainlist=$domains->getResponse();
            if(!empty($domainlist)){
                $domain=$domainlist[0];
                $domainname=$domain->name;
                $data ['backupDomain']    = $domainname;

            }
            $apiData = $this->resellerApi->backup->viewBackup(new Models\Command\Backup($data));

        }else{
            $apiData = $this->userApi->backup->viewBackup(new Models\Command\Backup($data));
        }

        foreach($apiData["list"] as $setting){
            $this->data[$setting] = "show";
        }
        $this->data['backup']   = $this->actionElementId;
    }

    public function create()
    {
        parent::create();

        $data = [
            'backupDomain'  => $this->getWhmcsParamByKey('domain'),
            'domain'        => $this->formData['domain'],
            'subdomain'     => $this->formData['subdomain'],
            'email'         => $this->formData['email'],
            'email_data'    => $this->formData['emailData'],
            'forwarder'     => $this->formData['forwarder'],
            'autoresponder' => $this->formData['autoresponder'],
            'vacation'      => $this->formData['vacation'],
            'list'          => $this->formData['list'],
            'emailsettings' => $this->formData['emailSettings'],
            'ftp'           => $this->formData['ftp'],
            'ftpsettings'   => $this->formData['ftpSettings'],
            'database'      => $this->formData['database'],
            'database_data' => $this->formData['databaseData'],
        ];
        if($this->getWhmcsParamByKey('producttype')  == "reselleraccount" )
        {
            $this->loadResellerApi();
            $domains=$this->resellerApi->domain->lists();
            $domainlist=$domains->getResponse();
            if(!empty($domainlist)){
                $domain=$domainlist[0];
                $domainname=$domain->name;
                $data ['backupDomain']    = $domainname;

            }
            $this->resellerApi->backup->create(new Models\Command\Backup($data));

        }else{
            $this->userApi->backup->create(new Models\Command\Backup($data));
        }


        return (new  ResponseTemplates\DataJsonResponse())->setMessageAndTranslate('backupStartCreating');
    }

    public function delete()
    {
        parent::delete();

        $data   = [
            'backupDomain'  => $this->getWhmcsParamByKey('domain'),
            'file'          => $this->formData['backup']
        ];
        if($this->getWhmcsParamByKey('producttype')  == "reselleraccount" )
        {
            $this->loadResellerApi();
            $domains=$this->resellerApi->domain->lists();
            $domainlist=$domains->getResponse();
            if(!empty($domainlist)){
                $domain=$domainlist[0];
                $domainname=$domain->name;
                $data ['backupDomain']    = $domainname;

            }
            $this->resellerApi->backup->delete(new Models\Command\Backup($data));

        }else{
            $this->userApi->backup->delete(new Models\Command\Backup($data));
        }


        return (new  ResponseTemplates\DataJsonResponse())->setMessageAndTranslate('backupDeleted');
    }


    public function update()
    {
        parent::update();

        $data   = [
            'backupDomain'  => $this->getWhmcsParamByKey('domain'),
            'file'          => $this->formData['backup'],
            'domain'        => $this->formData['domainLocal'],
            'subdomain'     => $this->formData['subdomainLocal'],
            'email'         => $this->formData['emailLocal'],
            'email_data'    => $this->formData['emailDataLocal'],
            'forwarder'     => $this->formData['forwarderLocal'],
            'autoresponder' => $this->formData['autoresponderLocal'],
            'vacation'      => $this->formData['vacationLocal'],
            'list'          => $this->formData['listLocal'],
            'emailsettings' => $this->formData['emailSettingsLocal'],
            'ftp'           => $this->formData['ftpLocal'],
            'ftpsettings'   => $this->formData['ftpSettingsLocal'],
            'database'      => $this->formData['databaseLocal'],
            'database_data' => $this->formData['databaseDataLocal'],
        ];

        if($this->getWhmcsParamByKey('producttype')  == "reselleraccount" )
        {
            $this->loadResellerApi();
            $domains=$this->resellerApi->domain->lists();
            $domainlist=$domains->getResponse();
            if(!empty($domainlist)){
                $domain=$domainlist[0];
                $domainname=$domain->name;
                $data ['backupDomain']    = $domainname;

            }
            $this->resellerApi->backup->restore(new Models\Command\Backup($data));

        }else{
            $this->userApi->backup->restore(new Models\Command\Backup($data));
        }


        return (new  ResponseTemplates\DataJsonResponse())->setMessageAndTranslate('backupStartRestoration');
    }

    public function deleteMassive()
    {
        parent::delete();

        $data = [];
        foreach ($this->getRequestValue('massActions', []) as $backup)
        {
            $data[] = new Models\Command\Backup(['backupName' => $backup]);
        }

        $this->userApi->backup->deleteMany($data);

        return (new ResponseTemplates\HtmlDataJsonResponse())->setMessageAndTranslate('massBackupHasBeenDeleted');
    }

}
