<?php

namespace ModulesGarden\Servers\DirectAdminExtended\App\UI\Client\Databases\Forms;

use ModulesGarden\Servers\DirectAdminExtended\App\UI\Client\Customs\Fields\PasswordGenerateExtended;
use ModulesGarden\Servers\DirectAdminExtended\Core\UI\Widget\Forms\BaseForm;
use ModulesGarden\Servers\DirectAdminExtended\Core\UI\Widget\Forms\FormConstants;
use ModulesGarden\Servers\DirectAdminExtended\Core\UI\Interfaces\ClientArea;
use ModulesGarden\Servers\DirectAdminExtended\Core\UI\Widget\Forms\Fields;
use ModulesGarden\Servers\DirectAdminExtended\App\UI\Client\Databases\Providers;

class ChangePassword extends BaseForm implements ClientArea
{
    protected $id    = 'changePasswordForm';
    protected $name  = 'changePasswordForm';
    protected $title = 'changePasswordForm';

    public function initContent()
    {
        $this->setFormType(FormConstants::UPDATE)
                ->setProvider(new Providers\ChangePassword());

        $databases  = (new Fields\Hidden('database'));
        $username = (new Fields\Hidden('user'));
        $password = (new PasswordGenerateExtended('password'))->setDescription('passDesc')->notEmpty();

        $this->addField($username)
            ->addField($databases)
            ->addField($password)
            ->loadDataToForm();
    }
}
