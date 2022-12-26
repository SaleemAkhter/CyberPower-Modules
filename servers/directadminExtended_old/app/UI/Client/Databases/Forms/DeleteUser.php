<?php

namespace ModulesGarden\Servers\DirectAdminExtended\App\UI\Client\Databases\Forms;

use \ModulesGarden\Servers\DirectAdminExtended\Core\UI\Widget\Forms\BaseForm;
use \ModulesGarden\Servers\DirectAdminExtended\Core\UI\Widget\Forms\Fields\Hidden;
use \ModulesGarden\Servers\DirectAdminExtended\Core\UI\Widget\Forms\FormConstants;
use \ModulesGarden\Servers\DirectAdminExtended\Core\UI\Interfaces\ClientArea;

class DeleteUser extends BaseForm implements ClientArea
{
    protected $id    = 'deleteForm';
    protected $name  = 'deleteForm';
    protected $title = 'deleteForm';

    public function initContent()
    {
        $this->setFormType(FormConstants::DELETE)
                ->setProvider(new \ModulesGarden\Servers\DirectAdminExtended\App\UI\Client\Databases\Providers\UsersDelete())
                ->setConfirmMessage('confirmUserDelete');

        $user = new Hidden('user');
        $db   = new Hidden('database');

        $this->addField($user)
            ->addField($db)
            ->loadDataToForm();
    }
}
