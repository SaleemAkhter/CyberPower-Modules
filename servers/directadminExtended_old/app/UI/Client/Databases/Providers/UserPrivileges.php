<?php

namespace ModulesGarden\Servers\DirectAdminExtended\App\UI\Client\Databases\Providers;


use ModulesGarden\Servers\DirectAdminExtended\App\Helpers\ProviderApi;

class UserPrivileges extends ProviderApi
{
    protected $privileges   = [
        'select',
        'insert',
        'update',
        'delete',
        'create',
        'create_routine_priv',
        'create_view_priv',
        'drop',
        'alter',
        'alter_routine_priv',
        'index',
        'grant',
        'reference',
        'tmp_table',
        'lock_table',
        'event_priv',
        'execute_priv',
        'show_view_priv',
        'trigger_priv',
    ];

    public function read()
    {
        if($this->getRequestValue('index') == 'editFormPrivileges')
        {
            return;
        }

        parent::read();

        $index      = explode(',', $this->getRequestValue('index'));
        $details    = $this->userApi->database->userPrivileges($this->getWhmcsParamByKey('domain'), $index[0], $index[1]);

        foreach($details as $key => $row)
        {
            $this->data[$key]   = $row == 'Y' ? 'on' : 'off';
        }

        $this->data['database'] = $index[0];
        $this->data['user']     = $index[1];
    }

    public function update()
    {
        parent::update();

        $privileges = [];

        foreach($this->privileges as $key => $val)
        {
            $privileges[$val]   = $this->formData[$val] == 'on' ? 'Y' : 'N';
        }

        $this->userApi->database->updateUserPrivileges($this->getWhmcsParamByKey('domain'), $this->formData['database'], $this->formData['user'], $privileges);
    }

    public function getPrivileges()
    {
        return $this->privileges;
    }
}
