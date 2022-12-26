<?php

namespace ModulesGarden\Servers\DirectAdminExtended\App\UI\Client\Ssl\Providers;

use ModulesGarden\Servers\DirectAdminExtended\App\Helpers\ProviderApi;

class SslKeyView extends ProviderApi
{
    public function read()
    {

        $data = json_decode(base64_decode($this->getRequestValue('index')));

        $this->data['key'] = $data->key;

    }
}
