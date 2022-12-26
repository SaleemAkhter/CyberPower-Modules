<?php

namespace ModulesGarden\Servers\DirectAdminExtended\App\UI\Client\Admin\IpManager\Providers;


class IpDelete extends Ip
{

    public function read()
    {
        $data = json_decode(base64_decode($this->actionElementId));
        $this->data['ip'] = $data->ip;
    }
}
