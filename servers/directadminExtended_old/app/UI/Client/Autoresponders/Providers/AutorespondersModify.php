<?php

namespace ModulesGarden\Servers\DirectAdminExtended\App\UI\Client\Autoresponders\Providers;

use ModulesGarden\Servers\DirectAdminExtended\App\Libs\DirectAdmin\Models;

class AutorespondersModify extends Autoresponders
{

    public function read()
    {
        if($this->getRequestValue('index') == 'editForm')
        {
            return;
        }
        parent::read();

        $requestedValue             = explode(',' ,$this->getRequestValue('index'));
        $this->data['addressCopy']  = $requestedValue[0];
        $this->data['address']      = $requestedValue[0];
        $this->data['Copy']         = $requestedValue[1];
        $this->data['domain']       = $requestedValue[1];

        $data     = [
            'user'      => $requestedValue[0],
            'domain'    => $requestedValue[1]
        ];
        $autoResponder = $this->userApi->autoresponder->view(new Models\Command\Autoresponder($data))->first();
        if($autoResponder)
        {
            $this->data['message']  = html_entity_decode($autoResponder->getText());
            $this->data['cc']       = $autoResponder->getEmail();
        }


        $this->data['domainCopy']               = [];
        $this->availableValues['domainCopy']    = $this->getDomainList();
    }

}
