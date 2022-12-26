<?php

namespace ModulesGarden\Servers\DirectAdminExtended\App\UI\Client\Databases\Forms;

use ModulesGarden\Servers\DirectAdminExtended\App\UI\Client\Customs\Fields\PasswordGenerateExtended;
use ModulesGarden\Servers\DirectAdminExtended\App\UI\Client\Customs\Sections\FormGroupSection;
use ModulesGarden\Servers\DirectAdminExtended\Core\UI\Widget\Forms\BaseForm;
use ModulesGarden\Servers\DirectAdminExtended\Core\UI\Widget\Forms\FormConstants;
use ModulesGarden\Servers\DirectAdminExtended\Core\UI\Interfaces\ClientArea;
use ModulesGarden\Servers\DirectAdminExtended\Core\UI\Widget\Forms\Fields;
use ModulesGarden\Servers\DirectAdminExtended\App\UI\Client\Databases\Providers;
use ModulesGarden\Servers\DirectAdminExtended\App\Helpers\Validator;
use ModulesGarden\Servers\DirectAdminExtended\App\UI\Client\Customs\Fields\TextWithButton;
use ModulesGarden\Servers\DirectAdminExtended\App\UI\Client\Customs\Buttons\GeneratePassword;
use ModulesGarden\Servers\DirectAdminExtended\Core\UI\Widget\Forms\Sections;
use ModulesGarden\Servers\DirectAdminExtended\Core\UI\Widget\Forms\Fields\InputGroupElements;

class AddUser extends BaseForm implements ClientArea
{
    protected $id    = 'addUserForm';
    protected $name  = 'addUserForm';
    protected $title = 'addUserForm';

    public function initContent()
    {
        $this->setFormType(FormConstants::CREATE)
                ->setProvider(new Providers\UsersCreate());

        $databaseSection = new FormGroupSection('databaseSection');
        $databases  = (new Fields\Select('database'))->notEmpty();
        $databaseSection->addField($databases);
        $this->addSection($databaseSection);

        $username = (new Sections\InputGroup('usernameSection'))
            ->addInputAddon($this->getWhmcsParamByKey('username').'_', false, $this->getWhmcsParamByKey('username').'_')
            ->addInputComponent((new InputGroupElements\Text('username'))->addValidator(new Validator\DatabaseName(true)))
            ->setDescription('');

        $this->addSection($username);


        $passwordSection = new FormGroupSection('passwordSection');
        $password = (new PasswordGenerateExtended('password'))
                ->setDescription('passDesc')
                ->notEmpty();

        $passwordSection->addField($password);

        $this->addSection($passwordSection)
            ->loadDataToForm();
    }
}
