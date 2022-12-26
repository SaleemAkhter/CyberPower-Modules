<?php

namespace ModulesGarden\Servers\DirectAdminExtended\App\UI\Client\Admin\SshKey\Providers;

use ModulesGarden\Servers\DirectAdminExtended\App\Helpers\ProviderApi;
use ModulesGarden\Servers\DirectAdminExtended\App\Libs\DirectAdmin\Models;
use ModulesGarden\Servers\DirectAdminExtended\Core\UI\ResponseTemplates;

class SshKey extends ProviderApi
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

    public function create()
    {
         parent::create();
         // {"id":"sdt","comment":"ertert","keysize":"2048","passwd":"tertretert","overwrite":"no","authorize":"no","json":"yes","action":"create","type":"rsa","passwd2":"tertretert"}:
            $data = [
                'id'      => $this->formData['keyid'],
                'comment'      => $this->formData['comment'],
                'keysize'      => $this->formData['keysize'],
                'passwd'       => $this->formData['password'],
                'overwrite'    =>'no',
                'authorize'    => ($this->formData['authorize']=="on")?"yes":"no",
                'json'         => $this->formData['ip'],
                'action'=>'create',
                'type'=>'rsa',
                'passwd2'=>$this->formData['password'],
            ];

            $this->loadAdminApi();
            $response=$this->adminApi->sshKey->create($data);

            if(isset($response->success)){
                return (new ResponseTemplates\RawDataJsonResponse())->setMessageAndTranslate('keyHasBeenCreated');
            }else{
                return (new ResponseTemplates\RawDataJsonResponse())->setMessageAndTranslate('keyCreationFailed');
            }
            return (new ResponseTemplates\RawDataJsonResponse())->setMessageAndTranslate('keyCreationFailed');
    }
    public function authorize()
    {
        $formdata =$this->formData;
        $keys = $this->getRequestValue('massActions', []);
        $data=[
            'json'=>'yes',
            'action'=> 'select',
            'type'=> 'public',
            'authorize'=>'yes'
        ];
        foreach ($keys as $k => $key) {
            $d=json_decode(base64_decode($key));
            $data['select'.$k]=$d->fingerprint;
        }

        if($this->getWhmcsParamByKey('producttype')  == "server" )
        {
            $this->loadAdminApi();
            $response=$this->adminApi->sshKey->authorize($data);
            return (new ResponseTemplates\RawDataJsonResponse())->setMessageAndTranslate('sshkeyauthorized');
        }else{

        }
        return (new ResponseTemplates\RawDataJsonResponse())->setMessageAndTranslate('sshkeyauthorizefailed');
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
