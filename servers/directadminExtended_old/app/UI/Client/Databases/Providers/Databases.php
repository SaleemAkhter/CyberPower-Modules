<?php

namespace ModulesGarden\Servers\DirectAdminExtended\App\UI\Client\Databases\Providers;

use ModulesGarden\Servers\DirectAdminExtended\App\Helpers\ProviderApi;
use ModulesGarden\Servers\DirectAdminExtended\App\Libs\DirectAdmin\Models;
use ModulesGarden\Servers\DirectAdminExtended\Core\UI\ResponseTemplates;

class Databases extends ProviderApi
{

    public function read()
    {
        $this->data['idHidden'] = $this->actionElementId;
    }

    public function create()
    {
        parent::create();

        $data = [
            'name'      => $this->formData['name'],
            'user'      => $this->formData['username'],
            'password'  => $this->formData['password']
        ];
        $this->userApi->database->create(new Models\Command\Database($data));

        return (new ResponseTemplates\RawDataJsonResponse())
            ->setMessageAndTranslate('databaseHasBeenCreated')
            ->setRefreshTargetIds(['databasesTable', 'usersTable']);
    }

    public function delete()
    {
        parent::delete();

        $data = [
            'name' => $this->formData['idHidden']
        ];
        $this->userApi->database->delete(new Models\Command\Database($data));

        return (new ResponseTemplates\RawDataJsonResponse())
            ->setMessageAndTranslate('databaseHasBeenDeleted')
            ->setRefreshTargetIds(['databasesTable', 'usersTable']);
    }

    public function massDelete()
    {
        parent::delete();

        $data = [];
        foreach ($this->getRequestValue('massActions', []) as $database)
        {
            $data[] = new Models\Command\Database(['name' => $database]);
        }
        $this->userApi->database->deleteMany($data);

        return (new ResponseTemplates\RawDataJsonResponse())
            ->setMessageAndTranslate('databasesHasBeenDeleted')
            ->setRefreshTargetIds(['databasesTable', 'usersTable']);
    }

    public function update()
    {
        
    }
}
