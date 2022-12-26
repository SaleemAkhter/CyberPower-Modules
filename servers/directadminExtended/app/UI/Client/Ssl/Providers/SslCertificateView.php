<?php

namespace ModulesGarden\Servers\DirectAdminExtended\App\UI\Client\Ssl\Providers;

use ModulesGarden\Servers\DirectAdminExtended\App\Helpers\ProviderApi;

class SslCertificateView extends ProviderApi
{
    public function read()
    {

        $data = json_decode(base64_decode($this->getRequestValue('index')));

        $this->data['cert']  = $data->certificate;
        $this->data['expiryDays']  = $data->expiryDays." days";
        $this->data['expiryDate']  = $data->Not_After;
        $this->data['renewalDays']  = ($data->renewalDays)?$data->renewalDays." days":'';
        $this->data['renewalDate']  = $data->Not_Before;
    }
}
