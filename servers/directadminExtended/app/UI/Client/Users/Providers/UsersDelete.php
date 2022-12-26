<?php

namespace ModulesGarden\Servers\DirectAdminExtended\App\UI\Client\Users\Providers;


class UsersDelete extends Users
{

    public function read()
    {
        $this->data['user'] = $this->actionElementId;
    }
}
