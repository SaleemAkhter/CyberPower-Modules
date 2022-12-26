<?php

namespace ModulesGarden\Servers\DirectAdminExtended\App\UI\Client\Databases\Forms\MassAction;

use \ModulesGarden\Servers\DirectAdminExtended\Core\UI\Widget\Forms\BaseForm;
use \ModulesGarden\Servers\DirectAdminExtended\Core\UI\Interfaces\ClientArea;

class DeleteUser extends BaseForm implements ClientArea
{
    protected $id    = 'deleteUserForm';
    protected $name  = 'deleteUserForm';
    protected $title = 'deleteUserForm';

    public function getDefaultActions()
    {
        return ['massDelete'];
    }

    public function initContent()
    {
        $this->setFormType('massDelete')
                ->setProvider(new \ModulesGarden\Servers\DirectAdminExtended\App\UI\Client\Databases\Providers\UsersDelete())
                ->setConfirmMessage('confirmMassDelete');
    }
}
