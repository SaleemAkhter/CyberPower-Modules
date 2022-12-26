<?php

namespace ModulesGarden\Servers\DirectAdminExtended\App\UI\Client\ApacheHandlers\Providers;


class ApacheHandlersDelete extends ApacheHandlers
{

    public function read()
    {
        $params = json_decode(base64_decode($this->getRequestValue('index')));

        $this->data['domain']   = $params->domain;
        $this->data['name']   = $params->name;
    }
}
