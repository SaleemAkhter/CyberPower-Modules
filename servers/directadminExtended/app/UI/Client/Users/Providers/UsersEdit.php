<?php

namespace ModulesGarden\Servers\DirectAdminExtended\App\UI\Client\Users\Providers;

use ModulesGarden\Servers\DirectAdminExtended\App\Libs\DirectAdmin\Command\Domain;
use ModulesGarden\Servers\DirectAdminExtended\Core\Helper\Lang;
use ModulesGarden\Servers\DirectAdminExtended\App\Helpers\ProviderApi;
use ModulesGarden\Servers\DirectAdminExtended\Core\UI\ResponseTemplates;
use ModulesGarden\Servers\DirectAdminExtended\App\Libs\DirectAdmin\Models;

class UsersEdit extends ProviderApi
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

        $this->loadResellerApi();


        if(isset($_SESSION['userfetched']) && isset($_SESSION['userfetched'][$this->actionElementId]) && $_SESSION['userfetched'][$this->actionElementId]>time()){
            $user=$_SESSION['fetcheduserdetail'][$this->actionElementId];
        }else{
             $_SESSION['fetcheduserdetail'][$this->actionElementId]=$user = json_decode(json_encode($this->resellerApi->reseller->config(new Models\Command\User([
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

        $this->data['cgi'] = ($user->cgi === "ON") ? "on" : "off";
        $this->data['php'] = ($user->php === "ON") ? "on" : "off";
        $this->data['spam'] = ($user->spam === "ON") ? "on" : "off";
        $this->data['catchall'] = ($user->catchall === "ON") ? "on" : "off";
        $this->data['ssl'] = ($user->ssl === "ON") ? "on" : "off";
        $this->data['ssh'] = ($user->ssh === "ON") ? "on" : "off";
        $this->data['jailedhome'] = ($user->jail === "ON") ? "on" : "off";
        $this->data['cron'] = ($user->cron === "ON") ? "on" : "off";
        $this->data['dnscontrol'] = ($user->dnscontrol === "ON") ? "on" : "off";
        $this->data['suspend_at_limit'] = ($user->suspend_at_limit === "ON") ? "on" : "off";


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

        $this->availableValues['language'] =[
            'en'=>'en'
        ];




        $result     = $this->resellerApi->resellerPackage->list();
        $packageopts=[];
        foreach($result as $items){
            $packageopts[$items['name']] = $items['name'];
        }

        $this->availableValues['package']   = $packageopts;
        $iplist=$this->resellerApi->reseller->getIPs();
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

    /**
     *
     */
    public function update()
    {
        parent::update();


        $this->loadResellerApi();
        if(isset($this->formData['editPackage']) && $this->formData['editPackage']==1){
            $packagedata=[
                'username'=>$this->formData['user'],
                'package'=>$this->formData['package'],
            ];
            $response=$this->resellerApi->reseller->modifyUserPackage(new Models\Command\User($packagedata));
            unset($_SESSION['userfetched'][$this->actionElementId]);
        }elseif(isset($this->formData['editIp']) && $this->formData['editIp']==1){
            $ipdata=[
                'username'=>$this->formData['user'],
                'ip' => $this->formData['ip']
            ];
            $response=$this->resellerApi->reseller->modifyUserIp(new Models\Command\User($ipdata));
        }elseif(isset($this->formData['editUsage']) && $this->formData['editUsage']==1){
            $usagedata=[
                'username'=>$this->formData['user'],
                'bandwidth' => $this->formData['bandwidthUnlimited'] === 'on' ? '' : $this->formData['bandwidth'],
                'ubandwidth' => $this->formData['bandwidthUnlimited'] === 'on' ? 'unlimited' : '',
                'quota' => $this->formData['usageUnlimited'] === 'on' ? '' : $this->formData['usage'],
                'uquota' => $this->formData['usageUnlimited'] === 'on' ? 'unlimited' : '',
                'inode' => $this->formData['inodeUnlimited'] === 'on' ? '' : $this->formData['inode'],
                'uinode' => $this->formData['inodeUnlimited'] === 'on' ? 'unlimited' : '',
                'vdomains' => $this->formData['vdomainsUnlimited'] === 'on' ? '' : $this->formData['vdomains'],
                'uvdomains' => $this->formData['vdomainsUnlimited'] === 'on' ? 'unlimited' : '',
                'nsubdomains' => $this->formData['nsubdomainsUnlimited'] === 'on' ? '' : $this->formData['nsubdomains'],
                'unsubdomains' => $this->formData['nsubdomainsUnlimited'] === 'on' ? 'unlimited' : '',
                'nemails' => $this->formData['nemailsUnlimited'] === 'on' ? '' : $this->formData['nemails'],
                'unemails' => $this->formData['nemailsUnlimited'] === 'on' ? 'unlimited' :'',
                'nemailf' => $this->formData['nemailfUnlimited'] === 'on' ? '' : $this->formData['nemailf'],
                'unemailf' => $this->formData['nemailfUnlimited'] === 'on' ? 'unlimited' : '',
                'nemailml' => $this->formData['nemailmlUnlimited'] === 'on' ? '' : $this->formData['nemailml'],
                'unemailml' => $this->formData['nemailmlUnlimited'] === 'on' ? 'unlimited' : '',
                'nemailr' => $this->formData['nemailrUnlimited'] === 'on' ? '' : $this->formData['nemailr'],
                'unemailr' => $this->formData['nemailrUnlimited'] === 'on' ? 'unlimited' : '',
                'mysql' => $this->formData['mysqlUnlimited'] === 'on' ? '' : $this->formData['mysql'],
                'umysql' => $this->formData['mysqlUnlimited'] === 'on' ? 'unlimited' : '',
                'domainptr' => $this->formData['domainptrUnlimited'] === 'on' ? '' : $this->formData['domainptr'],
                'udomainptr' => $this->formData['domainptrUnlimited'] === 'on' ? 'unlimited' : '',
                'ftp' => $this->formData['ftpUnlimited'] === 'on' ? '' : $this->formData['ftp'],
                'uftp' => $this->formData['ftpUnlimited'] === 'on' ? 'unlimited' : '',
                'ssl'       => $this->formData['ssl'] == 'on' ? strtoupper($this->formData['ssl']) : '',
                'cgi'       => $this->formData['cgi'] == 'on' ? strtoupper($this->formData['cgi']) : '',
                'php'       => $this->formData['php'] == 'on' ? strtoupper($this->formData['php']) : '',
                'spam'       => $this->formData['spam'] == 'on' ? strtoupper($this->formData['spam']) : '',
                'catchall'       => $this->formData['catchall'] == 'on' ? strtoupper($this->formData['catchall']) : '',
                'ssh'       => $this->formData['ssh'] == 'on' ? strtoupper($this->formData['ssh']) : '',
                'jail'       => $this->formData['jailedhome'] == 'on' ? strtoupper($this->formData['jailedhome']) : '',
                'cron'=>$this->formData['cron'] == 'on' ? strtoupper($this->formData['cron']) : '',
                'dnscontrol'       => $this->formData['dnscontrol'] == 'on' ? strtoupper($this->formData['dnscontrol']) : '',
                'suspend_at_limit'       => $this->formData['suspend_at_limit'] == 'on' ? strtoupper($this->formData['suspend_at_limit']) : '',
                'ns1'=>$this->formData['ns1'],
                'ns2'=>$this->formData['ns2']
            ];

            $response=$this->resellerApi->reseller->upgradeConfigOptions(new Models\Command\User($usagedata));
            unset($_SESSION['userfetched'][$this->actionElementId]);

        }
        unset($_SESSION['userfetched'][$this->actionElementId]);
        if(is_array($response) && !empty($response['details'])){
            $message=$response['details'];
            return (new ResponseTemplates\RawDataJsonResponse())->setMessage($message);
        }else{
            $message='userHasBeenUpdated';
            return (new ResponseTemplates\RawDataJsonResponse())->setMessageAndTranslate($message);
        }

    }

    public function reload()
    {
        foreach($this->formData as $key => $value)
        {
            $this->data[$key] = $value;
        }
    }

}
