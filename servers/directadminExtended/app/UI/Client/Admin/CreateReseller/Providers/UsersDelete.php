<?php

namespace ModulesGarden\Servers\DirectAdminExtended\App\UI\Client\Admin\UserManager\Providers;


class UsersDelete extends Users
{

    public function read()
    {
        $this->data['user'] = $this->actionElementId;
    }
}
