<?php

namespace ModulesGarden\Servers\DirectAdminExtended\App\UI\Client\Admin\ListReseller\Providers;

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

        $this->loadAdminApi();
unset($_SESSION['userfetched'],$_SESSION['fetcheduserdetail']);
        // debug($_SESSION['fetcheduserdetail'][$this->actionElementId]);die();
        if(isset($_SESSION['userfetched']) && isset($_SESSION['userfetched'][$this->actionElementId]) && $_SESSION['userfetched'][$this->actionElementId]>time()){
            $user=$_SESSION['fetcheduserdetail'][$this->actionElementId];
        }else{
             $_SESSION['fetcheduserdetail'][$this->actionElementId]=$user = json_decode(json_encode($this->adminApi->reseller->resellerConfig(new Models\Command\User([
                'username'  => $this->actionElementId
            ]))));
            $_SESSION['userfetched'][$this->actionElementId]=time()+5;

        }
        // debug($user);die();
        $this->data['user']=$this->data['oldname']=$this->data['name'] = $this->actionElementId;
        $this->data['bandwidth'] = ($user->bandwidth->value === "unlimited") ? "" : $user->bandwidth->value;
        $this->data['bandwidthUnlimited'] = ($user->bandwidth->value === "unlimited") ? "on" : "";
        $this->data['diskspace'] = ($user->quota->value === "unlimited") ? "" : $user->quota->value;
        $this->data['diskspaceUnlimited'] = ($user->quota->value === "unlimited") ? "on" : "";
        $this->data['usage'] = ($user->quota->value === "unlimited") ? "" : $user->quota->value;
        $this->data['usageUnlimited'] = ($user->quota->value === "unlimited") ? "on" : "";
        $this->data['inode'] = ($user->inode->value === "unlimited") ? "" : $user->inode->value;
        $this->data['inodeUnlimited'] = ($user->inode->value === "unlimited") ? "on" : "";
        $this->data['vdomains'] = ($user->vdomains->value === "unlimited") ? "" : $user->vdomains->value;
        $this->data['vdomainsUnlimited'] = ($user->vdomains->value === "unlimited") ? "on" : "";
        $this->data['nsubdomains'] = ($user->nsubdomains->value === "unlimited") ? "" : $user->nsubdomains->value;
        $this->data['nsubdomainsUnlimited'] = ($user->nsubdomains->value === "unlimited") ? "on" :"";
        $this->data['nemails'] = ($user->nemails->value === "unlimited") ? "" : $user->nemails->value;
        $this->data['nemailsUnlimited'] = ($user->nemails->value === "unlimited") ? "on" : "";
        $this->data['nemailf'] = ($user->nemailf->value === "unlimited") ? "" : $user->nemailf->value;
        $this->data['nemailfUnlimited'] = ($user->nemailf->value === "unlimited") ? "on" : "";
        $this->data['nemailml'] = ($user->nemailml->value === "unlimited") ? "" : $user->nemailml->value;
        $this->data['nemailmlUnlimited'] = ($user->nemailml->value === "unlimited") ? "on" : "";
        $this->data['nemailr'] = ($user->nemailr->value === "unlimited") ? "" : $user->nemailr->value;
        $this->data['nemailrUnlimited'] = ($user->nemailr->value === "unlimited") ? "on" : "";
        $this->data['mysql'] = ($user->mysql->value === "unlimited") ? "" : $user->mysql->value;
        $this->data['mysqlUnlimited'] = ($user->mysql->value === "unlimited") ? "on" : "";
        $this->data['domainptr'] = ($user->domainptr->value === "unlimited") ? "" : $user->domainptr->value;
        $this->data['domainptrUnlimited'] = ($user->domainptr->value === "unlimited") ? "on" : "";
        $this->data['ftp'] = ($user->ftp->value === "unlimited") ? "" : $user->ftp->value;
        $this->data['ftpUnlimited'] = ($user->ftp->value === "unlimited") ? "on" : "";
        $this->data['nusers'] = ($user->nusers->value === "unlimited") ? "" : $user->nusers->value;
        $this->data['nusersUnlimited'] = ($user->nusers->value === "unlimited") ? "on" : "";
        $this->data['ips'] = $user->ips;

        $this->data['oversell'] = ($user->oversell->checked === "yes") ? "on" : "off";

        $this->data['aftp'] = ($user->cgi->checked === "yes") ? "on" : "off";
        $this->data['cgi'] = ($user->cgi->checked === "yes") ? "on" : "off";
        $this->data['php'] = ($user->php->checked === "yes") ? "on" : "off";
        $this->data['spam'] = ($user->spam->checked === "yes") ? "on" : "off";
        $this->data['catchall'] = ($user->catchall->checked === "yes") ? "on" : "off";
        $this->data['ssl'] = ($user->ssl->checked === "yes") ? "on" : "off";
        $this->data['ssh'] = ($user->ssh->checked === "yes") ? "on" : "off";
        $this->data['userssh'] = ($user->userssh->checked === "yes") ? "on" : "off";
        $this->data['cron'] = ($user->cron->checked === "yes") ? "on" : "off";
        $this->data['sysinfo'] = ($user->sysinfo->checked === "yes") ? "on" : "off";
        $this->data['login_keys'] = ($user->login_keys->checked === "yes") ? "on" : "off";
        $this->data['dnscontrol'] = ($user->dnscontrol->checked === "yes") ? "on" : "off";
        $this->data['serverip'] = ($user->serverip->checked === "yes") ? "on" : "off";


        $this->availableValues['language'] =[
            'en'=>'en'
        ];


        if($this->data['bandwidthUnlimited']!="on")
        {
            $this->data['bandwidth']=$this->data['bandwidth']/1048576;
        }

        if($this->data['diskspaceUnlimited'] !="on")
        {

            $this->data['diskspace']=$this->data['diskspace']/1048576;
            $this->data['usage']=$this->data['usage']/1048576;
        }
        if($user->inode == 'unlimited')
        {
            $this->data['inodeUnlimited'] = 'on';
        }
        if($user->vdomains == 'unlimited')
        {
            $this->data['vdomainsUnlimited'] = 'on';
        }

        if($user->email_daily_limit=== 'unlimited')
        {
            $this->data['uemail_daily_limitUnlimited'] ="on";
        }
        if($user->email_daily_limit && $user->email_daily_limit >0)
        {
            $this->data['uemail_daily_limit'] =$user->email_daily_limit ;
        }
        if($user->nusers=== 'unlimited')
        {
            $this->data['nusersUnlimited'] ="on";
        }
        if($user->nusers && $user->nusers->value >0)
        {
            $this->data['nusers'] =$user->nusers->value ;
        }
        // debug($user);
        // debug($this->data);die();
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

    /**
     *
     */
    public function update()
    {
        parent::update();


        $this->loadAdminApi();
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
                'nusers' => $this->formData['nusersUnlimited'] === 'on' ? '' : $this->formData['nusers'],

                'uftp' => $this->formData['ftpUnlimited'] === 'on' ? 'unlimited' : '',
                'aftp'       => $this->formData['aftp'] == 'on' ? strtoupper($this->formData['aftp']) : '',
                'ssl'       => $this->formData['ssl'] == 'on' ? strtoupper($this->formData['ssl']) : '',
                'cgi'       => $this->formData['cgi'] == 'on' ? strtoupper($this->formData['cgi']) : '',
                'php'       => $this->formData['php'] == 'on' ? strtoupper($this->formData['php']) : '',
                'spam'       => $this->formData['spam'] == 'on' ? strtoupper($this->formData['spam']) : '',
                'catchall'       => $this->formData['catchall'] == 'on' ? strtoupper($this->formData['catchall']) : '',
                'ssh'       => $this->formData['ssh'] == 'on' ? strtoupper($this->formData['ssh']) : '',
                'userssh'       => $this->formData['userssh'] == 'on' ? strtoupper($this->formData['userssh']) : '',
                'oversell'       => $this->formData['oversell'] == 'on' ? strtoupper($this->formData['oversell']) : '',
                'jail'       => $this->formData['jailedhome'] == 'on' ? strtoupper($this->formData['jailedhome']) : '',
                'cron'=>$this->formData['cron'] == 'on' ? strtoupper($this->formData['cron']) : '',
                'sysinfo'=>$this->formData['sysinfo'] == 'on' ? strtoupper($this->formData['sysinfo']) : '',
                'login_keys'=>$this->formData['login_keys'] == 'on' ? strtoupper($this->formData['login_keys']) : '',
                'dnscontrol'       => $this->formData['dnscontrol'] == 'on' ? strtoupper($this->formData['dnscontrol']) : '',

            ];
            if($this->formData['uemail_daily_limitUnlimited'] === 'on')
            {
                $data['uemail_daily_limit'] ="on";
            }
            if(isset($this->formData['uemail_daily_limit']) && $this->formData['uemail_daily_limit']>0)
            {
                $data['email_daily_limit'] =$this->formData['uemail_daily_limit'];
            }
            if($this->formData['nusersUnlimited'] === 'on')
            {
                $data['unusers'] ="yes";
            }
            $emaildata=[
                'username'=>$this->formData['user'],
                'email_limit_value' => $data['email_daily_limit']
            ];
            foreach ($usagedata as $key => $value) {
                if(empty($value)){
                    unset($usagedata[$key]);
                }
            }
            // debug($usagedata);die();
            // $response=$this->resellerApi->reseller->modifyUserEmailLimit($emaildata);

            $response=$this->adminApi->reseller->upgradeResellerConfigOptions(($usagedata));


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

