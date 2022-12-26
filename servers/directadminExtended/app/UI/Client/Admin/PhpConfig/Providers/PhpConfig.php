<?php

namespace ModulesGarden\Servers\DirectAdminExtended\App\UI\Client\Admin\PhpConfig\Providers;

use ModulesGarden\Servers\DirectAdminExtended\App\Helpers\ProviderApi;
use ModulesGarden\Servers\DirectAdminExtended\App\Libs\DirectAdmin\Models;
use ModulesGarden\Servers\DirectAdminExtended\Core\UI\ResponseTemplates;

class PhpConfig extends ProviderApi
{

    public function read()
    {
        $this->data['name'] = $this->actionElementId;

        if($this->data['name']){

        }else{
            if($this->getWhmcsParamByKey('producttype')  == "reselleraccount" )
            {
                $this->loadResellerApi([],false);
                $ns=$this->resellerApi->reseller->getNameservers();
                $this->data['ns1']=$ns->ns1;
                $this->data['ns2']=$ns->ns2;

            }else{
                $this->loadUserApi();
                $result=[];
            }
        }

    }


    public function enableOpenbasedir()
    {

        $formdata =$this->formData;
        $domains = $this->getRequestValue('massActions', []);
        $data=[
            'json'=>'yes',
            'action'=> 'set',
            'enable_obd'=> 'yes',
        ];
        foreach ($domains as $key => $domain) {
            $d=json_decode(base64_decode($domain));
            $data['select'.$key]=$d->domain;
        }

        if($this->getWhmcsParamByKey('producttype')  == "server" )
        {
            $this->loadAdminApi();
            $response=$this->adminApi->phpConfig->enableOpenbasedir($data);
            return (new ResponseTemplates\RawDataJsonResponse())->setMessageAndTranslate('openbasedirenabled');
        }else{

        }
        return (new ResponseTemplates\RawDataJsonResponse())->setMessageAndTranslate('openbasedirenablefailed');
    }
     public function disableOpenbasedir()
    {
        $formdata =$this->formData;
        $domains = $this->getRequestValue('massActions', []);
        $data=[
            'json'=>'yes',
            'action'=> 'set',
            'disable_obd'=> 'yes',
        ];
        foreach ($domains as $key => $domain) {
            $d=json_decode(base64_decode($domain));
            $data['select'.$key]=$d->domain;
        }

        if($this->getWhmcsParamByKey('producttype')  == "server" )
        {
            $this->loadAdminApi();
            $response=$this->adminApi->phpConfig->disableOpenbasedir($data);
            return (new ResponseTemplates\RawDataJsonResponse())->setMessageAndTranslate('openbasedirdisabled');

        }else{

        }
        return (new ResponseTemplates\RawDataJsonResponse())->setMessageAndTranslate('openbasedirenablefailed');
    }





}
