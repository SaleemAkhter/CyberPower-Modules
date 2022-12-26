<?php

namespace ModulesGarden\Servers\HetznerVps\App\UI\ReverseDNS\Helpers;

use ModulesGarden\Servers\HetznerVps\App\Libs\HetznerVps\Api;

class ReverseDNSMenager
{
    protected $params = [];

    public function __construct($params)
    {
        $this->params = $params;
    }

    public function get(){

        $serverId = $this->params['customfields']['serverID'];

        $api      = new Api($this->params);
        return $api->servers()->get($serverId);
    }

    public function changeReverseDNS($ip, $dns_ptr)
    {
        $api = new Api($this->params);
        $serverId = $this->params['customfields']['serverID'];

        $result =  $api->server($serverId)->changeReverseDNS($ip, $dns_ptr);
        $response = $result->getResponsePart('action');

        return $response->error == null ? true : false;

    }


}
