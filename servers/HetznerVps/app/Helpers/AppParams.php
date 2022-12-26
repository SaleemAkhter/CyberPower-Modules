<?php

namespace ModulesGarden\Servers\HetznerVps\App\Helpers;

use function ModulesGarden\Servers\HetznerVps\Core\Helper\sl;

class AppParams
{

    public function initFromWhmcsParams(){
        sl('whmcsParams')->setParams(sl('whmcsParams')->getWhmcsParams());
    }

    public function initFromParams($params){
        sl('whmcsParams')->setParams($params);
    }

}