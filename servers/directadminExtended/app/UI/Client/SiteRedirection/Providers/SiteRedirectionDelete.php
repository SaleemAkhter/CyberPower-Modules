<?php

namespace ModulesGarden\Servers\DirectAdminExtended\App\UI\Client\SiteRedirection\Providers;


class SiteRedirectionDelete extends SiteRedirection
{

    public function read()
    {
        $params = json_decode(base64_decode($this->actionElementId));

        $this->data['domain'] = $params->domain;
        $this->data['from']   = $params->local_url;
    }

}
