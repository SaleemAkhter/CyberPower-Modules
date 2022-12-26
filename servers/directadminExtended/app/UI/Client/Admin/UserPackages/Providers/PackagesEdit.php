<?php

namespace ModulesGarden\Servers\DirectAdminExtended\App\UI\Client\Admin\UserPackages\Providers;

use ModulesGarden\Servers\DirectAdminExtended\App\Libs\DirectAdmin\Command\Domain;
use ModulesGarden\Servers\DirectAdminExtended\Core\Helper\Lang;
use ModulesGarden\Servers\DirectAdminExtended\App\Helpers\ProviderApi;
use ModulesGarden\Servers\DirectAdminExtended\Core\UI\ResponseTemplates;
use ModulesGarden\Servers\DirectAdminExtended\App\Libs\DirectAdmin\Models;

class PackagesEdit extends ProviderApi
{
    use \ModulesGarden\Servers\DirectAdminExtended\Core\Traits\Lang;

    public function read()
    {
        if($this->getRequestValue('index') == 'editForm')
        {
            return;
        }

        parent::read();

        $this->loadLang();
        $this->loadAdminApi();
        $package = json_decode(json_encode($this->adminApi->adminPackage->info(new Models\Command\AdminPackage([
            'name'  => $this->actionElementId
        ]))));

        $this->data['oldname']=$this->data['name'] = $this->actionElementId;
        $this->data['bandwidth'] = ($package->bandwidth === "unlimited") ? "" : $package->bandwidth;
        $this->data['bandwidthUnlimited'] = ($package->bandwidth === "unlimited") ? "on" : "";
        $this->data['diskspace'] = ($package->quota === "unlimited") ? "" : $package->quota;
        $this->data['diskspaceUnlimited'] = ($package->quota === "unlimited") ? "on" : "";
        $this->data['inode'] = ($package->inode === "unlimited") ? "" : $package->inode;
        $this->data['inodeUnlimited'] = ($package->inode === "unlimited") ? "on" : "";
        $this->data['vdomains'] = ($package->vdomains === "unlimited") ? "" : $package->vdomains;
        $this->data['vdomainsUnlimited'] = ($package->vdomains === "unlimited") ? "on" : "";
        $this->data['nsubdomains'] = ($package->nsubdomains === "unlimited") ? "" : $package->nsubdomains;
        $this->data['nsubdomainsUnlimited'] = ($package->nsubdomains === "unlimited") ? "on" :"";
        $this->data['nemails'] = ($package->nemails === "unlimited") ? "" : $package->nemails;
        $this->data['nemailsUnlimited'] = ($package->nemails === "unlimited") ? "on" : "";
        $this->data['nemailf'] = ($package->nemailf === "unlimited") ? "" : $package->nemailf;
        $this->data['nemailfUnlimited'] = ($package->nemailf === "unlimited") ? "on" : "";
        $this->data['nemailml'] = ($package->nemailml === "unlimited") ? "" : $package->nemailml;
        $this->data['nemailmlUnlimited'] = ($package->nemailml === "unlimited") ? "on" : "";
        $this->data['nemailr'] = ($package->nemailr === "unlimited") ? "" : $package->nemailr;
        $this->data['nemailrUnlimited'] = ($package->nemailr === "unlimited") ? "on" : "";
        $this->data['mysql'] = ($package->mysql === "unlimited") ? "" : $package->mysql;
        $this->data['mysqlUnlimited'] = ($package->mysql === "unlimited") ? "on" : "";
        $this->data['domainptr'] = ($package->domainptr === "unlimited") ? "" : $package->domainptr;
        $this->data['domainptrUnlimited'] = ($package->domainptr === "unlimited") ? "on" : "";
        $this->data['ftp'] = ($package->ftp === "unlimited") ? "" : $package->ftp;
        $this->data['ftpUnlimited'] = ($package->ftp === "unlimited") ? "on" : "";
        $this->data['aftp'] = ($package->cgi === "ON") ? "on" : "off";
        $this->data['cgi'] = ($package->cgi === "ON") ? "on" : "off";
        $this->data['php'] = ($package->php === "ON") ? "on" : "off";
        $this->data['spam'] = ($package->spam === "ON") ? "on" : "off";
        $this->data['catchall'] = ($package->catchall === "ON") ? "on" : "off";
        $this->data['ssl'] = ($package->ssl === "ON") ? "on" : "off";
        $this->data['ssh'] = ($package->ssh === "ON") ? "on" : "off";
        $this->data['jailedhome'] = ($package->jail === "ON") ? "on" : "off";
        $this->data['cron'] = ($package->cron === "ON") ? "on" : "off";
        $this->data['sysinfo'] = ($package->sysinfo === "ON") ? "on" : "off";
        $this->data['login_keys'] = ($package->login_keys === "ON") ? "on" : "off";
        $this->data['dnscontrol'] = ($package->dnscontrol === "ON") ? "on" : "off";
        $this->data['suspend_at_limit'] = ($package->suspend_at_limit === "ON") ? "on" : "off";

        // $this->data['phpAccess'] = strtolower($details->php);
        // $this->data['php1']             = $phpInformation['selected'];
        // $this->availableValues['php1']  = $phpInformation['options'];

        // $this->data['domain'] = $this->actionElementId;
        // $this->data['bandwidth'] = ($domain->bandwidth === "unlimited") ? "" : $domain->bandwidth;
        // $this->data['diskUsage'] = ($domain->quota === "unlimited") ? "" : $domain->quota;
        // $this->data['cgiAccess'] = ($domain->cgi === "ON") ? "on" : "off";
        // $this->data['localMail'] = ($localMail->getLocalMail() == "yes") ? "on" : "off";
        // $this->data['secureSsl'] = ($domain->ssl === "ON") ? "on" : "off";
        // $this->data['redirect'] = ($domain->www_pointers === "yes") ? "on" : "off";
        // $this->data['forceSsl'] = ($domain->force_ssl === "yes") ? "on" : "off";

        if($package->bandwidth == 'unlimited')
        {
            $this->data['bandwidthUnlimited'] = 'on';
        }

        if($package->quota == 'unlimited')
        {
            $this->data['usageUnlimited'] = 'on';
        }
        if($package->inode == 'unlimited')
        {
            $this->data['inodeUnlimited'] = 'on';
        }
        if($package->vdomains == 'unlimited')
        {
            $this->data['vdomainsUnlimited'] = 'on';
        }

        if($package->email_daily_limit=== 'unlimited')
        {
            $this->data['uemail_daily_limitUnlimited'] ="on";
        }
        if($package->email_daily_limit && $package->email_daily_limit >0)
        {
            $this->data['uemail_daily_limit'] =$package->email_daily_limit ;
        }
        // debug($this->data);die();
    }



    /**
     *
     */
    public function update()
    {
        parent::update();

        $data = [
            'name'      => $this->formData['name'],
            'old_packagename'=>$this->formData['oldname'],
            'bandwidth' => $this->formData['bandwidthUnlimited'] === 'on' ? 'unlimited' : $this->formData['bandwidth'],
            'quota' => $this->formData['usageUnlimited'] === 'on' ? 'unlimited' : $this->formData['diskspace'],
            'inode' => $this->formData['inodeUnlimited'] === 'on' ? 'unlimited' : $this->formData['inode'],
            'vdomains' => $this->formData['vdomainsUnlimited'] === 'on' ? 'unlimited' : $this->formData['vdomains'],
            'nsubdomains' => $this->formData['nsubdomainsUnlimited'] === 'on' ? 'unlimited' : $this->formData['nsubdomains'],
            'nemails' => $this->formData['nemailsUnlimited'] === 'on' ? 'unlimited' : $this->formData['nemails'],
            'nemailf' => $this->formData['nemailfUnlimited'] === 'on' ? 'unlimited' : $this->formData['nemailf'],
            'nemailml' => $this->formData['nemailmlUnlimited'] === 'on' ? 'unlimited' : $this->formData['nemailml'],
            'nemailr' => $this->formData['nemailrUnlimited'] === 'on' ? 'unlimited' : $this->formData['nemailr'],
            'mysql' => $this->formData['mysqlUnlimited'] === 'on' ? 'unlimited' : $this->formData['mysql'],
            'domainptr' => $this->formData['domainptrUnlimited'] === 'on' ? 'unlimited' : $this->formData['domainptr'],
            'ftp' => $this->formData['ftpUnlimited'] === 'on' ? 'unlimited' : $this->formData['ftp'],
            'aftp'      => $this->formData['aftp'] == 'on' ? strtoupper($this->formData['aftp']) : '',
            'ssl'       => $this->formData['ssl'] == 'on' ? strtoupper($this->formData['ssl']) : '',
            'cgi'       => $this->formData['cgi'] == 'on' ? strtoupper($this->formData['cgi']) : '',
            'php'       => $this->formData['php'] == 'on' ? strtoupper($this->formData['php']) : '',
            'spam'       => $this->formData['spam'] == 'on' ? strtoupper($this->formData['spam']) : '',
            'catchall'       => $this->formData['catchall'] == 'on' ? strtoupper($this->formData['catchall']) : '',
            'ssh'       => $this->formData['ssh'] == 'on' ? strtoupper($this->formData['ssh']) : '',
            'jail'       => $this->formData['jailedhome'] == 'on' ? strtoupper($this->formData['jailedhome']) : '',
            'cron'       => $this->formData['cron'] == 'on' ? strtoupper($this->formData['cron']) : '',
            'sysinfo'   => $this->formData['sysinfo'] == 'on' ? strtoupper($this->formData['sysinfo']) : '',
            'login_keys'=> $this->formData['login_keys'] == 'on' ? strtoupper($this->formData['login_keys']) : '',
            'dnscontrol'       => $this->formData['dnscontrol'] == 'on' ? strtoupper($this->formData['dnscontrol']) : '',
            'suspend_at_limit'       => $this->formData['suspend_at_limit'] == 'on' ? strtoupper($this->formData['suspend_at_limit']) : '',
        ];

        if($data['name']!=$data['old_packagename']){
            $data['rename']='yes';
        }else{
            $data['rename']='no';
        }

        if($this->formData['uemail_daily_limitUnlimited'] === 'on')
        {
            $data['uemail_daily_limit'] ="on";
        }
        if(isset($this->formData['uemail_daily_limit']) && $this->formData['uemail_daily_limit']>0)
        {
            $data['email_daily_limit'] =$this->formData['uemail_daily_limit'];
        }
        $this->loadResellerApi();
        $response=$this->resellerApi->adminPackage->update($data);


        return (new ResponseTemplates\RawDataJsonResponse([
            'url' => $this->getPackagesIndexURL(),
        ]))->setMessageAndTranslate('packageHasBeenUpdated')->setCallBackFunction('redirectAfterFormSubmit');
    }

    public function reload()
    {
        foreach($this->formData as $key => $value)
        {
            $this->data[$key] = $value;
        }
    }
protected function getPackagesIndexURL()
    {
        $params = [
            'action' => 'productdetails',
            'id'     =>   $this->getRequestValue('id', false),
            'modop'     => 'custom',
            'a'     => 'management',
            'mg-page'     => 'UserPackages',
        ];

        return 'clientarea.php?'. \http_build_query($params);

    }
}
