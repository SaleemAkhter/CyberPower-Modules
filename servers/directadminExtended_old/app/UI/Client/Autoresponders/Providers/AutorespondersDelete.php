<?php

namespace ModulesGarden\Servers\DirectAdminExtended\App\UI\Client\Autoresponders\Providers;

class AutorespondersDelete extends Autoresponders
{

    public function read()
    {
        $requestedValue         = explode(',' ,$this->getRequestValue('index'));
        $this->data['user']     = $requestedValue[0];
        $this->data['domain']   = $requestedValue[1];
    }
}
