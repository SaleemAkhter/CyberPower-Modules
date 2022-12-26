<?php

namespace ModulesGarden\Servers\DirectAdminExtended\App\UI\Client\Databases\Providers;

use ModulesGarden\Servers\DirectAdminExtended\App\Helpers\ProviderApi;
use ModulesGarden\Servers\DirectAdminExtended\Core\UI\ResponseTemplates;
use ModulesGarden\Servers\DirectAdminExtended\App\Libs\DirectAdmin\Models;

class Users extends ProviderApi
{

    public function read()
    {
        $this->data['idHidden'] = $this->actionElementId;
    }

    public function create()
    {
        parent::create();

        $data = [
            'name'      => $this->formData['database'],
            'user'      => $this->formData['username'],
            'password'  => $this->formData['password'],
        ];
        $this->userApi->database->createUser(new Models\Command\Database($data));

        return (new ResponseTemplates\RawDataJsonResponse())->setMessageAndTranslate('databaseUserHasBeenCreated');
    }

    public function delete()
    {
        parent::delete();

        $data = [
            'name'  => $this->formData['database'],
            'user'  => $this->formData['user']
        ];

        $this->userApi->database->deleteUser(new Models\Command\Database($data));

        return (new ResponseTemplates\RawDataJsonResponse())->setMessageAndTranslate('databaseUserHasBeenDeleted');
    }

    public function massDelete()
    {
        parent::delete();

        $data = [];


        foreach ($this->getRequestValue('massActions', []) as $users)
        {
            $formData = unserialize(base64_decode($users));
            $data[$formData['database']][] = $formData['user'];
        }

        $this->userApi->database->deleteManyUsers($data);

        return (new ResponseTemplates\RawDataJsonResponse())->setMessageAndTranslate('massDatabaseUserHasBeenDeleted');
    }

    public function deleteMany()
    {
        parent::delete();
    }

    public function update()
    {
        parent::update();
    }
}
