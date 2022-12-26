<?php

namespace ModulesGarden\Servers\DirectAdminExtended\App\UI\Client\Emails\Providers;

class EmailsEdit extends Emails
{

    public function read()
    {
        parent::loadUserApi();

        $this->data['domainsCopy']              = [];
        $this->availableValues['domainsCopy']   = $this->getDomainList();

        $data = json_decode(base64_decode($this->actionElementId));
        $account        = $data->email;
        $limit          = $data->limit;
        $explodeAccount = explode('@', $account);

        $this->data['accountCopy']      = $explodeAccount[0];
        $this->data['account']          = $explodeAccount[0];
        $this->data['domains']          = $explodeAccount[1];
        $this->data['domainsCopy']      = $explodeAccount[1];

        $this->data['daAccount'] = 'on';
        $this->data['ftpAccount'] = 'on';
        $this->data['dbAccount'] = 'on';

        if (strtolower(trim($data->quota))!== 'unlimited')
        {
            $this->data['quota']       = 'off';
            $this->data['customQuota'] = trim($data->quota);
        }
        else
        {
            $this->data['quota']       = 'on';
        }
        if(is_string($limit) && strtolower($limit) !== "unlimited")
        {
            $this->data['limit']       = 'off';
            $this->data['customLimit'] = $limit;
        }
        else
        {
            $this->data['limit']       = 'on';
        }
    }
}
