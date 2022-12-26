<?php

namespace ModulesGarden\Servers\DirectAdminExtended\App\UI\Client\EmailForwarders\Providers;

class EmailForwardersEdit extends EmailForwarders
{

    public function read()
    {
        parent::loadUserApi();

        $this->data['domainsCopy']               = [];
        $this->availableValues['domainsCopy']    = $this->getDomainList();

        $indexParams    = $this->getRequestValue('index');
        $explodeParams  = explode(',', $indexParams, 2);
        $emailExplode   = explode('@', $explodeParams[0]);

        $this->data['name']             = $emailExplode[0];
        $this->data['nameCopy']         = $emailExplode[0];
        $this->data['domainsCopy']      = $emailExplode[1];
        $this->data['domains']          = $emailExplode[1];
        $this->data['destination']      = preg_replace('/\<br(\s*)?\/?\>/i', ",", html_entity_decode($explodeParams[1]));
    }
}
