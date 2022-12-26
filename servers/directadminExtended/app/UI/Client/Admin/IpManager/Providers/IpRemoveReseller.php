<?php

namespace ModulesGarden\Servers\DirectAdminExtended\App\UI\Client\Admin\IpManager\Providers;

use ModulesGarden\Servers\DirectAdminExtended\App\Libs\DirectAdmin\Command\Domain;
use ModulesGarden\Servers\DirectAdminExtended\Core\Helper\Lang;
use ModulesGarden\Servers\DirectAdminExtended\App\Helpers\ProviderApi;
use ModulesGarden\Servers\DirectAdminExtended\Core\UI\ResponseTemplates;
use ModulesGarden\Servers\DirectAdminExtended\App\Libs\DirectAdmin\Models;

class IpRemoveReseller extends ProviderApi
{
    use \ModulesGarden\Servers\DirectAdminExtended\Core\Traits\Lang;

    public function read()
    {
        if($this->getRequestValue('index') == 'editForm')
        {
            return;
        }

        parent::read();
        $data = json_decode(base64_decode($this->actionElementId));
        // debug($data);die();
        $this->loadLang();
        $this->data['ip']=$data->ip;
        // $this->data['reseller']=$data->reseller;
        $resellers=['all'=>'All Resellers'];
        foreach ($data->resellers as $key => $value) {
            $resellers[$value]=$value;
        }
        $this->availableValues['reseller']   = $resellers;
    }



    /**
     *
     */
    public function update()
    {
        parent::update();
        // {"json":"yes","action":"select","remove":"yes","reseller":"digu087","select0":"193.31.29.61"}: :
        $data = [
            'reseller'      => $this->formData['reseller'],
            'select0' => $this->formData['ip'] ,
        ];
        $this->loadAdminApi();
        $response=$this->adminApi->ip->admin_remove($data);


        return (new ResponseTemplates\RawDataJsonResponse())->setMessageAndTranslate('ipremovedFromReseller');
    }

    public function reload()
    {
        foreach($this->formData as $key => $value)
        {
            $this->data[$key] = $value;
        }
    }

}
