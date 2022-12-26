<?php

namespace ModulesGarden\Servers\DirectAdminExtended\App\UI\Client\Databases\Providers;


class UsersCreate extends Users
{

    public function read()
    {
        $this->loadUserApi();

        $this->data['idHidden'] = $this->actionElementId;

        $databasesSelect    = [];
        $databases          = $this->userApi->database->lists()->response;
        foreach ($databases as $database)
        {
            $databasesSelect[$database->getName()] = $database->getName();
        }

        $this->data['database']              = [];
        $this->availableValues['database']   = $databasesSelect;

    }
}
