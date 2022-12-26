<?php

namespace ModulesGarden\Servers\DirectAdminExtended\App\UI\Client\Databases\Providers;


class UsersDelete extends Users
{
    public function read()
    {
        $data = unserialize(base64_decode($this->actionElementId));

        $this->data['user']     = $data['user'];
        $this->data['database'] = $data['database'];
    }
}
