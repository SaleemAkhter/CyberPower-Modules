<?php

namespace ModulesGarden\Servers\DirectAdminExtended\App\UI\Client\IpManagement\Providers;

use ModulesGarden\Servers\DirectAdminExtended\App\Helpers\ProviderApi;
use ModulesGarden\Servers\DirectAdminExtended\App\Libs\DirectAdmin\Models;
use ModulesGarden\Servers\DirectAdminExtended\Core\UI\ResponseTemplates;

class IpManagement extends ProviderApi
{

    public function read()
    {
        $this->data['name'] = $this->actionElementId;

        if($this->data['name']){

        }else{
            if($this->getWhmcsParamByKey('producttype')  == "reselleraccount" )
            {
                $this->loadResellerApi([],false);
                $messages=$this->resellerApi->messageSystem->listdetail();

                $this->data['clear_messages']=$messages->clear_messages;

            }else{
                $this->loadUserApi();
                $result=[];
            }
        }

    }


    public function clearMessages($data)
    {

    }
    public function massFreeMany()
    {
        $ips= $this->getRequestValue('massActions', []);
        $data=[
          'json' => 'yes',
          'free' => 'Free Selected',
          'action'=>'select'
        ];
        foreach ($ips as $k=>$ip)
        {
            $data['select'.$k] = $ip;

        }

        if($this->getWhmcsParamByKey('producttype')  == "reselleraccount" )
        {
            $this->loadUserApi();
            $response=$this->userApi->Ip->configip($data);
            if($response->success){
                return (new ResponseTemplates\RawDataJsonResponse())->setMessageAndTranslate($response->result);
            }else{
                return (new ResponseTemplates\RawDataJsonResponse())->setMessageAndTranslate('massFreeManyFailed');
            }
        }else{

        }

        return (new ResponseTemplates\RawDataJsonResponse())->setMessageAndTranslate('massFreeManySuccess');
    }
    public function markSharedMany()
    {
        $ips= $this->getRequestValue('massActions', []);
        $data=[
          'json' => 'yes',
          'share' => 'Share Selected',
          'action'=>'select'
        ];
        foreach ($ips as $k=>$ip)
        {
            $data['select'.$k] = $ip;

        }

        if($this->getWhmcsParamByKey('producttype')  == "reselleraccount" )
        {
            $this->loadUserApi();
            $response=$this->userApi->Ip->configip($data);
            if($response->success){
                return (new ResponseTemplates\RawDataJsonResponse())->setMessageAndTranslate($response->result);
            }else{
                return (new ResponseTemplates\RawDataJsonResponse())->setMessageAndTranslate('markSharedManyFailed');
            }
        }else{

        }
        return (new ResponseTemplates\RawDataJsonResponse())->setMessageAndTranslate('markSharedManySuccess');

    }





}
