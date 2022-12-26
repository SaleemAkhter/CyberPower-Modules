<?php

namespace ModulesGarden\Servers\DirectAdminExtended\App\UI\Client\Admin\ResellerPackages\Providers;

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
        $package = json_decode(json_encode($this->adminApi->adminPackage->inforesellerpackage(new Models\Command\AdminPackage([
            'name'  => $this->actionElementId
        ]))));
        // debug($package);die("hajhkhkh");


        $this->data['oldname']=$this->data['name'] = $this->actionElementId;
        $this->data['bandwidth'] = ($package->bandwidth->value === "unlimited") ? "" : $package->bandwidth->value;
        $this->data['bandwidthUnlimited'] = ($package->bandwidth->value === "unlimited") ? "on" : "";
        $this->data['diskspace'] = ($package->quota->value === "unlimited") ? "" : $package->quota->value;
        $this->data['diskspaceUnlimited'] = ($package->quota->value === "unlimited") ? "on" : "";
        $this->data['inode'] = ($package->inode->value === "unlimited") ? "" : $package->inode->value;
        $this->data['inodeUnlimited'] = ($package->inode->value === "unlimited") ? "on" : "";
        $this->data['vdomains'] = ($package->vdomains->value === "unlimited") ? "" : $package->vdomains->value;
        $this->data['vdomainsUnlimited'] = ($package->vdomains->value === "unlimited") ? "on" : "";
        $this->data['nsubdomains'] = ($package->nsubdomains->value === "unlimited") ? "" : $package->nsubdomains->value;
        $this->data['nsubdomainsUnlimited'] = ($package->nsubdomains->value === "unlimited") ? "on" :"";
        $this->data['nemails'] = ($package->nemails->value === "unlimited") ? "" : $package->nemails->value;
        $this->data['nemailsUnlimited'] = ($package->nemails->value === "unlimited") ? "on" : "";
        $this->data['nemailf'] = ($package->nemailf->value === "unlimited") ? "" : $package->nemailf->value;
        $this->data['nemailfUnlimited'] = ($package->nemailf->value === "unlimited") ? "on" : "";
        $this->data['nemailml'] = ($package->nemailml->value === "unlimited") ? "" : $package->nemailml->value;
        $this->data['nemailmlUnlimited'] = ($package->nemailml->value === "unlimited") ? "on" : "";
        $this->data['nemailr'] = ($package->nemailr->value === "unlimited") ? "" : $package->nemailr->value;
        $this->data['nemailrUnlimited'] = ($package->nemailr->value === "unlimited") ? "on" : "";
        $this->data['mysql'] = ($package->mysql->value === "unlimited") ? "" : $package->mysql->value;
        $this->data['mysqlUnlimited'] = ($package->mysql->value === "unlimited") ? "on" : "";
        $this->data['domainptr'] = ($package->domainptr->value === "unlimited") ? "" : $package->domainptr->value;
        $this->data['domainptrUnlimited'] = ($package->domainptr->value === "unlimited") ? "on" : "";
        $this->data['ftp'] = ($package->ftp->value === "unlimited") ? "" : $package->ftp->value;
        $this->data['ftpUnlimited'] = ($package->ftp->value === "unlimited") ? "on" : "";
        $this->data['nusers'] = ($package->nusers->value === "unlimited") ? "" : $package->nusers->value;
        $this->data['nusersUnlimited'] = ($package->nusers->value === "unlimited") ? "on" : "";
        $this->data['ips'] = $package->ips;

        $this->data['oversell'] = ($package->oversell->checked === "yes") ? "on" : "off";

        $this->data['aftp'] = ($package->cgi->checked === "yes") ? "on" : "off";
        $this->data['cgi'] = ($package->cgi->checked === "yes") ? "on" : "off";
        $this->data['php'] = ($package->php->checked === "yes") ? "on" : "off";
        $this->data['spam'] = ($package->spam->checked === "yes") ? "on" : "off";
        $this->data['catchall'] = ($package->catchall->checked === "yes") ? "on" : "off";
        $this->data['ssl'] = ($package->ssl->checked === "yes") ? "on" : "off";
        $this->data['ssh'] = ($package->ssh->checked === "yes") ? "on" : "off";
        $this->data['userssh'] = ($package->userssh->checked === "yes") ? "on" : "off";
        $this->data['cron'] = ($package->cron->checked === "yes") ? "on" : "off";
        $this->data['sysinfo'] = ($package->sysinfo->checked === "yes") ? "on" : "off";
        $this->data['login_keys'] = ($package->login_keys->checked === "yes") ? "on" : "off";
        $this->data['dnscontrol'] = ($package->dnscontrol->checked === "yes") ? "on" : "off";
        $this->data['serverip'] = ($package->serverip->checked === "yes") ? "on" : "off";

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
        if($package->nusers=== 'unlimited')
        {
            $this->data['nusersUnlimited'] ="on";
        }
        if($package->nusers && $package->nusers->value >0)
        {
            $this->data['nusers'] =$package->nusers->value ;
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
            'nusers' => $this->formData['nusersUnlimited'] === 'on' ? '' : $this->formData['nusers'],
            'ips' => $this->formData['ips'] ,
            'aftp'      => $this->formData['aftp'] == 'on' ? strtoupper($this->formData['aftp']) : '',
            'ssl'       => $this->formData['ssl'] == 'on' ? strtoupper($this->formData['ssl']) : '',
            'cgi'       => $this->formData['cgi'] == 'on' ? strtoupper($this->formData['cgi']) : '',
            'php'       => $this->formData['php'] == 'on' ? strtoupper($this->formData['php']) : '',
            'spam'       => $this->formData['spam'] == 'on' ? strtoupper($this->formData['spam']) : '',
            'catchall'       => $this->formData['catchall'] == 'on' ? strtoupper($this->formData['catchall']) : '',
            'ssh'       => $this->formData['ssh'] == 'on' ? strtoupper($this->formData['ssh']) : '',
            'userssh'       => $this->formData['userssh'] == 'on' ? strtoupper($this->formData['userssh']) : '',
            'oversell'       => $this->formData['oversell'] == 'on' ? strtoupper($this->formData['oversell']) : '',
            'cron'       => $this->formData['cron'] == 'on' ? strtoupper($this->formData['cron']) : '',
            'sysinfo'   => $this->formData['sysinfo'] == 'on' ? strtoupper($this->formData['sysinfo']) : '',
            'login_keys'=> $this->formData['login_keys'] == 'on' ? strtoupper($this->formData['login_keys']) : '',
            'dnscontrol'       => $this->formData['dnscontrol'] == 'on' ? strtoupper($this->formData['dnscontrol']) : '',
            'serverIp'       => $this->formData['serverip'] == 'on' ? strtoupper($this->formData['serverip']) : '',
        ];

        if($data['name']!=$data['old_packagename']){
            $data['rename']='yes';
        }else{
            $data['rename']='no';
        }

        if($this->formData['nusersUnlimited'] === 'on')
        {
            $data['unusers'] ="yes";
        }
        foreach ($data as $key => $value) {
            if(empty($value)){
                unset($data[$key]);
            }
        }

        $this->loadAdminApi();
        $response=$this->adminApi->adminPackage->updateResellerPackage($data);

        return (new ResponseTemplates\RawDataJsonResponse([
            'url' => $this->getPackagesIndexURL(),
        ]))->setMessageAndTranslate('resellerpackageHasBeenUpdated')->setCallBackFunction('redirectAfterFormSubmit');
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
            'mg-page'     => 'ResellerPackages',
        ];

        return 'clientarea.php?'. \http_build_query($params);

    }
}
