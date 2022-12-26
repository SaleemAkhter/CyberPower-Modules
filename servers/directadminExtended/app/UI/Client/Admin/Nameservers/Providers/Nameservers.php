<?php

namespace ModulesGarden\Servers\DirectAdminExtended\App\UI\Client\Admin\Nameservers\Providers;

use ModulesGarden\Servers\DirectAdminExtended\App\Helpers\ProviderApi;
use ModulesGarden\Servers\DirectAdminExtended\App\Libs\DirectAdmin\Models;
use ModulesGarden\Servers\DirectAdminExtended\Core\UI\ResponseTemplates;

class Nameservers extends ProviderApi
{

    public function read()
    {
        $this->data['name'] = $this->actionElementId;


            if($this->getWhmcsParamByKey('producttype')  == "reselleraccount" )
            {
                $this->loadResellerApi([],false);
                $ns=$this->resellerApi->reseller->getNameservers();
                $this->data['ns1']=$ns->ns1;
                $this->data['ns2']=$ns->ns2;

            }elseif($this->getWhmcsParamByKey('producttype')  == "server" ){
                $this->loadAdminApi([],false);
                $ns=$this->adminApi->nameserver->admin_getIpListWithDetail();
                $this->data['isnsset']=false;
                $this->data['ns1']=$ns->ns1;
                $this->data['ns2']=$ns->ns2;
                $this->data['domains']=$ns->domains;
                foreach ($ns->data as $ip => $d) {
                    $this->data['ips'][$ip]=$ip;
                    if(!empty($d->ns)){
                        $this->data['isnsset']=true;
                        $this->data['virtualon']=true;
                    }
                }
                if($this->getRequestValue('index') == 'addButton'){
                     $this->data['ns1']='ns1';
                     $this->data['ns2']='ns2';
                }

            }else{
                $this->loadUserApi();
                $result=[];
            }


    }
    public function create()
    {
        parent::create();
        $data = [
            'ns1'      => $this->formData['ns1'],
            'ns2'      => $this->formData['ns2'],
            'select0'      => $this->formData['select0'],
            'select1'      => $this->formData['select1'],
            'domain'      => $this->formData['domain'],
            'virtual'      => $this->formData['virtual']
        ];
        if(isset($this->formData['virtualon']) && $this->formData['virtualon']){
            $data['virtual']='yes';
        }

        $this->loadAdminApi();
        $response=$this->adminApi->nameserver->create($data);

        return (new ResponseTemplates\RawDataJsonResponse())->setMessageAndTranslate('nameserverHasBeenCreated');
    }

public function delete($value='')
{
    parent::create();
    $data = [
            'ns1'      => $this->formData['ns1'],
            'ns2'      => $this->formData['ns2'],
            'select0'      => $this->formData['select0'],
            'select1'      => $this->formData['select1'],
            'domain'      => $this->formData['domain'],
            'virtual'      => $this->formData['virtual']
        ];
        if(isset($this->formData['virtualon']) && $this->formData['virtualon']){
            $data['virtual']='yes';
        }

        $this->loadAdminApi();
        $response=$this->adminApi->nameserver->create($data);

        return (new ResponseTemplates\RawDataJsonResponse())->setMessageAndTranslate('nameserverHasBeenCreated');
//    select0: 193.31.29.55
// ns1: ns1
// ns2: ns2
// domain: techsprinters.net
// delete: Delete Nameservers
// action: select
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
        }elseif($this->getWhmcsParamByKey('producttype')  == "server" )
        {
            $this->loadAdminApi();
            $response=$this->adminApi->nameserver->changeNameservers($data);
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
