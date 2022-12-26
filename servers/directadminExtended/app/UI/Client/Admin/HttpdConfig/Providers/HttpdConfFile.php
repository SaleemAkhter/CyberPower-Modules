<?php

namespace ModulesGarden\Servers\DirectAdminExtended\App\UI\Client\Admin\HttpdConfig\Providers;


class HttpdConfFile extends HttpdConfig
{

    public function read()
    {
        $this->data['domain'] = $this->actionElementId;

        $this->loadAdminApi();

        $result    = $this->adminApi->httpdConfig->gethttpdconffile($this->data['domain']);

        // HTTPD
        $_SESSION['httpdconfigfile'][$this->data['domain']] =$result;
        $this->data['httpd']=$result->HTTPD;
        $this->data['result'] = $result;
    }
}
