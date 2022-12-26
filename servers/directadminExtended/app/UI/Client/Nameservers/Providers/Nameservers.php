<?php

namespace ModulesGarden\Servers\DirectAdminExtended\App\UI\Client\Nameservers\Providers;

use ModulesGarden\Servers\DirectAdminExtended\App\Helpers\ProviderApi;
use ModulesGarden\Servers\DirectAdminExtended\App\Libs\DirectAdmin\Models;
use ModulesGarden\Servers\DirectAdminExtended\Core\UI\ResponseTemplates;

class Nameservers extends ProviderApi
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


    public function changenameservers()
    {

        $formdata =$this->formData;

        $data=[
            'json'=>'yes',
            'action'=> 'modify',
            'ns1'=> $formdata['ns1'],
            'ns2'=> $formdata['ns2']
        ];
        if($this->getWhmcsParamByKey('producttype')  == "reselleraccount" )
        {
            $this->loadResellerApi();
            $response=$this->resellerApi->reseller->changeNameservers($data);
            if(isset($response->success) && $response->success=="Success"){
                return (new ResponseTemplates\RawDataJsonResponse())->setMessageAndTranslate('changeNSSuccessfull');
            }else{
                return (new ResponseTemplates\RawDataJsonResponse())->setMessageAndTranslate('changeNSFailed');
            }
        }else{

        }
        return (new ResponseTemplates\RawDataJsonResponse())->setMessageAndTranslate('changeNSFailed');
    }





}
