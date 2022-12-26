<?php

namespace ModulesGarden\Servers\DirectAdminExtended\App\UI\Client\Databases\Providers;


use ModulesGarden\Servers\DirectAdminExtended\App\Helpers\ProviderApi;
use ModulesGarden\Servers\DirectAdminExtended\App\Libs\DirectAdmin\Models\Command\Database;

class ChangePassword extends ProviderApi
{

    public function read()
    {
        if($this->getRequestValue('index') == 'changePasswordForm')
        {
            return;
        }

        $index      = explode(',', $this->getRequestValue('index'));

        $this->data['database'] = $index[0];
        $this->data['user']     = $index[1];
    }

    public function update()
    {
        parent::update();

        $databaseModel = [
            'domain'    =>   $this->getWhmcsParamByKey('domain'),
            'name'      =>   $this->formData['database'],
            'user'      =>   $this->formData['user'],
            'password'  =>   $this->formData['password']
        ];

        $this->userApi->database->changeUserPassword(new Database($databaseModel));

    }

}
