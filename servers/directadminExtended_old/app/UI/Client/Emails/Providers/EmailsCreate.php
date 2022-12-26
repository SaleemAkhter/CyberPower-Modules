<?php

namespace ModulesGarden\Servers\DirectAdminExtended\App\UI\Client\Emails\Providers;

class EmailsCreate extends Emails
{

    public function read()
    {
        parent::loadUserApi();

        $this->data['domains']              = [];
        $this->availableValues['domains']   = $this->getDomainList();
        $this->data['quota']   = 'on';
        $this->data['limit']   = 'on';
    }




}
