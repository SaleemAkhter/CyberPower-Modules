<?php

namespace ModulesGarden\Servers\DirectAdminExtended\App\UI\Client\ChangePasswords\Providers;

use ModulesGarden\Servers\DirectAdminExtended\App\Helpers\ProviderApi;
use ModulesGarden\Servers\DirectAdminExtended\App\Libs\DirectAdmin\Models;
use ModulesGarden\Servers\DirectAdminExtended\Core\UI\ResponseTemplates;

class ChangePassword extends ProviderApi
{

    public function read()
    {
        $this->data['name'] = $this->actionElementId;

        if($this->data['name']){

        }else{
            if($this->getWhmcsParamByKey('producttype')  == "reselleraccount" ||  $this->getWhmcsParamByKey('producttype')  == "server")
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


    public function changePassword()
    {
        $formdata =$this->formData;

        $data=[
            'username'=>$formdata['username'],
            'passwd'=>$formdata['password'],
            'passwd2'=>$formdata['confirmpassword'],

        ];
        if($this->getWhmcsParamByKey('producttype')  == "reselleraccount" ||  $this->getWhmcsParamByKey('producttype')  == "server" )
        {
            $this->loadResellerApi();
            $response=$this->resellerApi->reseller->changePassword($data);
            if(isset($response['error']) && $response['error']==0){
                return (new ResponseTemplates\RawDataJsonResponse())->setMessageAndTranslate('changePasswordSuccessfull');
            }else{
                return (new ResponseTemplates\RawDataJsonResponse())->setMessageAndTranslate('changePasswordFailed');
            }
        }else{

        }
        return (new ResponseTemplates\RawDataJsonResponse())->setMessageAndTranslate('changePasswordFailed');
    }





}
