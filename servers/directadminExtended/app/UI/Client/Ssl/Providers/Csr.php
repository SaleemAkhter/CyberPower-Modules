<?php

namespace ModulesGarden\Servers\DirectAdminExtended\App\UI\Client\Ssl\Providers;


class Csr extends Ssl
{

    public function read()
    {
        $this->data['csr']  = html_entity_decode($_REQUEST['index']);
    }
}
