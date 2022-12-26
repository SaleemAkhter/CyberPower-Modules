<?php

namespace ModulesGarden\Servers\DirectAdminExtended\App\UI\Client\Emails\Providers;

class EmailsEdit extends Emails
{

    public function read()
    {
        parent::loadUserApi();

        $this->data['domains']              = [];
        $this->availableValues['domains']   = $this->getDomainList();

        $indexParams    = $this->getRequestValue('index');
        $explodeParams  = explode(',', $indexParams);
        $account        = $explodeParams[0];
        $usage          = $explodeParams[1];

        $explodeAccount = explode('@', $account);
        $explodeUsage   = explode('/', $usage);


        $this->data['account']   = $explodeAccount[0];
        $this->data['domains']   = $explodeAccount[1];
        if (trim($explodeUsage[1]) !== 'unlimited')
        {
            $this->data['quota']       = 'off';
            $this->data['customQuota'] = trim($explodeUsage[1]);
        }
    }
}
