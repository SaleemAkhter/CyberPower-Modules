<?php

namespace ModulesGarden\Servers\DirectAdminExtended\App\UI\Client\ApacheHandlers\Providers;


class ApacheHandlersEdit extends ApacheHandlers
{

    public function read()
    {
        if ($this->getRequestValue('index') === 'editForm')
        {
            return;
        }

        $params = json_decode(base64_decode($this->getRequestValue('index')));
        $this->data['domain']   = $params->domain;
        $this->data['domainHidden']   = $params->domain;
        $this->data['handler']   = $params->name;
        $this->data['hanHidden'] = $params->name;
        $this->data['extHidden'] = $params->extension;
        $this->data['extensions'] = $params->extension;
    }
}
