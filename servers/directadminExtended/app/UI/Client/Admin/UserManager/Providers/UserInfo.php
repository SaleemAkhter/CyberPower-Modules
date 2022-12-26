<?php

namespace ModulesGarden\Servers\DirectAdminExtended\App\UI\Client\Admin\UserManager\Providers;

use ModulesGarden\Servers\DirectAdminExtended\App\Libs\DirectAdmin\Command\Domain;
use ModulesGarden\Servers\DirectAdminExtended\Core\Helper\Lang;
use ModulesGarden\Servers\DirectAdminExtended\App\Helpers\ProviderApi;
use ModulesGarden\Servers\DirectAdminExtended\Core\UI\ResponseTemplates;
use ModulesGarden\Servers\DirectAdminExtended\App\Libs\DirectAdmin\Models;

class UserInfo extends ProviderApi
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

        // debug($_SESSION['fetcheduserdetail'][$this->actionElementId]);die();
        if(isset($_SESSION['userfetched']) && isset($_SESSION['userfetched'][$this->actionElementId]) && $_SESSION['userfetched'][$this->actionElementId]>time()){
            $user=$_SESSION['fetcheduserdetail'][$this->actionElementId];
        }else{
             $_SESSION['fetcheduserdetail'][$this->actionElementId]=$user = json_decode(json_encode($this->adminApi->reseller->config(new Models\Command\User([
                'username'  => $this->actionElementId
            ]))));
            $_SESSION['userfetched'][$this->actionElementId]=time()+5;

        }

        $this->data['bandwidth'] = ($user->bandwidth === "unlimited") ? "0" : $user->bandwidth;
        $this->data['bandwidthUnlimited'] = ($user->bandwidth === "unlimited")  ? "on" : 'off';
        $this->data['usage'] = ($user->quota === "unlimited") ? "0" : $user->quota;
        $this->data['usageUnlimited'] = ($user->quota === "unlimited")  ? "on" : 'off';
        $this->data['inode'] = ($user->inode === "unlimited") ? "0" : $user->inode;
        $this->data['inodeUnlimited'] = ($user->inode === "unlimited") ? "on" : 'off';
        $this->data['vdomains'] = ($user->vdomains === "unlimited") ? "0" : $user->vdomains;
        $this->data['vdomainsUnlimited'] = ($user->vdomains === "unlimited")  ? "on" : 'off';
        $this->data['nsubdomains'] = ($user->nsubdomains === "unlimited") ? "0" : $user->nsubdomains;
        $this->data['nsubdomainsUnlimited'] = ($user->nsubdomains === "unlimited")  ? "on" : 'off';
        $this->data['nemails'] = ($user->nemails === "unlimited") ? "0" : $user->nemails;
        $this->data['nemailsUnlimited'] = ($user->nemails === "unlimited")  ? "on" : 'off';
        $this->data['nemailf'] = ($user->nemailf === "unlimited") ? "0" : $user->nemailf;
        $this->data['nemailfUnlimited'] = ($user->nemailf === "unlimited")  ? "on" : 'off';
        $this->data['nemailml'] = ($user->nemailml === "unlimited") ? "0" : $user->nemailml;
        $this->data['nemailmlUnlimited'] = ($user->nemailml === "unlimited")  ? "on" : 'off';
        $this->data['nemailr'] = ($user->nemailr === "unlimited") ? "0" : $user->nemailr;
        $this->data['nemailrUnlimited'] = ($user->nemailr === "unlimited")  ? "on" : 'off';
        $this->data['mysql'] = ($user->mysql === "unlimited") ? "0" : $user->mysql;
        $this->data['mysqlUnlimited'] = ($user->mysql === "unlimited")  ? "on" : 'off';
        $this->data['domainptr'] = ($user->domainptr === "unlimited") ? "0" : $user->domainptr;
        $this->data['domainptrUnlimited'] = ($user->domainptr === "unlimited")  ? "on" : 'off';
        $this->data['ftp'] = ($user->ftp === "unlimited") ? "0" : $user->ftp;
        $this->data['ftpUnlimited'] = ($user->ftp === "unlimited")  ? "on" : 'off';

        $this->data['aftp'] = ($user->cgi === "ON") ? "on" : "off";
        $this->data['cgi'] = ($user->cgi === "ON") ? "on" : "off";
        $this->data['php'] = ($user->php === "ON") ? "on" : "off";
        $this->data['spam'] = ($user->spam === "ON") ? "on" : "off";
        $this->data['catchall'] = ($user->catchall === "ON") ? "on" : "off";
        $this->data['ssl'] = ($user->ssl === "ON") ? "on" : "off";
        $this->data['ssh'] = ($user->ssh === "ON") ? "on" : "off";
        $this->data['jailedhome'] = ($user->jail === "ON") ? "on" : "off";
        $this->data['cron'] = ($user->cron === "ON") ? "on" : "off";
        $this->data['sysinfo'] = ($user->sysinfo === "ON") ? "on" : "off";
        $this->data['login_keys'] = ($user->login_keys === "ON") ? "on" : "off";
        $this->data['dnscontrol'] = ($user->dnscontrol === "ON") ? "on" : "off";
        $this->data['suspend_at_limit'] = ($user->suspend_at_limit === "ON") ? "on" : "off";
// email_limit_value

        $this->data['user']=$this->actionElementId;
        $this->data['username']=$this->actionElementId;
        $this->data['email']=$user->email;
        $this->data['name']=$user->name;
        $this->data['domain']=$user->domain;
        $this->data['ips']=$user->ips;
        $this->data['package']              = $user->package;
        $this->data['oldpackage']              = $user->package;
        $this->data['ip']              = $user->ip;
        $this->data['ns1']              = $user->ns1;
        $this->data['ns2']              = $user->ns2;
        $this->data['language']=$user->language;
        $this->data['email_limit_value']=$user->email_limit;

        $this->availableValues['language'] =[
            'en'=>'en'
        ];


        if($user->email_daily_limit=== 'unlimited')
        {
            $this->data['uemail_daily_limitUnlimited'] ="on";
        }
        if($user->email_limit && $user->email_limit >0)
        {
            $this->data['uemail_daily_limit'] =$user->email_limit ;
        }

        $result     = $this->adminApi->resellerPackage->list();
        $packageopts=[];
        foreach($result as $items){
            $packageopts[$items['name']] = $items['name'];
        }

        $this->availableValues['package']   = $packageopts;
        $iplist=$this->adminApi->reseller->getIPs();
        $ips=[
            $this->data['ip']=>$this->data['ip']." - user's '".$this->actionElementId."' current IP"
        ];

        $currentips=explode("|",$user->ips);
        $this->data['currentips']=[];

        foreach($iplist as $item){
            $title=$item['ip'];

            if($item['status']=='server'){
                $title.=" - Shared - Server";
                $type='Main';
            }elseif($item['status']=='shared'){
                $title.=" - Shared";
                if($item['ip']==$user->ip){
                    $type='Main';
                }else{
                    $type='Additional';
                }

            }elseif($item['status']=='owned'){
                $type='Additional';
            }
            $ips[$item['ip']] = $title;
            if(in_array($item['ip'], $currentips)){
                $this->data['currentips'][]=[
                    'type'=>$type,
                    'ip'=>$item['ip']
                ];
            }


        }
        $this->data['editPackage']=1;
        $this->data['editIp']=1;
        $this->data['editUsage']=1;
        $this->availableValues['ip']   = $ips;
    }

    protected function preparePhpOptions($phpOptions)
    {
        $options = [];

        foreach ($phpOptions as $option)
        {
            if(isset($option->selected) && $option->selected == "yes")
            {
                $options['selected'] = $option->value;
            }

            $options['options'][$option->value] = $option->text;
        }

        return $options;
    }
    private function getPHPVersionsArray()
    {
        $phpVersion = [];
        $info   = $this->userApi->systemInfo->lists();


        if(!is_null($info->getPhp()))
        {
            $phpVersion[1] = $this->lang->absoluteTranslate($info->getPhp());
        }
        if(!is_null($info->getPhp2()))
        {
            $phpVersion[2] = $this->lang->absoluteTranslate($info->getPhp2());
        }

        return $phpVersion;
    }



    public function reload()
    {
        foreach($this->formData as $key => $value)
        {
            $this->data[$key] = $value;
        }
    }

}

