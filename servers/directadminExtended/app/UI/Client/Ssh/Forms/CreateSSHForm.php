<?php

namespace ModulesGarden\Servers\DirectAdminExtended\App\UI\Client\Ssh\Forms;

use ModulesGarden\Servers\DirectAdminExtended\App\UI\Client\Customs\Fields\PasswordGenerateExtended;
use ModulesGarden\Servers\DirectAdminExtended\App\UI\Client\Customs\Sections\FormGroupSection;
use ModulesGarden\Servers\DirectAdminExtended\App\UI\Client\Ssh\Providers\SshProvider;
use ModulesGarden\Servers\DirectAdminExtended\Core\UI\Interfaces\ClientArea;
use ModulesGarden\Servers\DirectAdminExtended\Core\UI\Widget\Forms\BaseForm;
use ModulesGarden\Servers\DirectAdminExtended\Core\UI\Widget\Forms\Fields\Checkbox;
use ModulesGarden\Servers\DirectAdminExtended\Core\UI\Widget\Forms\Fields\Hidden;
use ModulesGarden\Servers\DirectAdminExtended\Core\UI\Widget\Forms\Fields\Password;
use ModulesGarden\Servers\DirectAdminExtended\Core\UI\Widget\Forms\Fields\Select;
use ModulesGarden\Servers\DirectAdminExtended\Core\UI\Widget\Forms\Fields\Switcher;
use ModulesGarden\Servers\DirectAdminExtended\Core\UI\Widget\Forms\Fields\Text;
use ModulesGarden\Servers\DirectAdminExtended\Core\UI\Widget\Forms\FormConstants;
use ModulesGarden\Servers\DirectAdminExtended\Core\UI\Widget\Forms\Sections;


class CreateSSHForm extends BaseForm implements  ClientArea
{
    protected $id    = 'addForm';
    protected $name  = 'addForm';
    protected $title = 'addForm';

    public function initContent()
    {
        $this->setFormType(FormConstants::CREATE)
            ->setProvider(new SshProvider());

        $keyid = (new Sections\InputGroup('id'))
            ->addTextField('keyid')
            ->addInputAddon('_rsa', false, '_rsa');

        $keyid->getField('keyid')->setPlaceholder('id');

        $authorize = (new Switcher('authorize'))
            ->setDescription('authorizeSwitcherDesc');

        $comment = (new Text('comment'))
            ->initIds('comment')
            ->notEmpty()
            ->setDescription('sshKeyComment')
            ->setPlaceholder('descriptive@comment.com');

        $passwordSection = new FormGroupSection('SSHPasswordSection');
        $password = (new PasswordGenerateExtended('SSHPassword'))->setDescription('SSHPasswordDesc');
        $passwordSection->addField($password);

        $keySize = (new Select('keySize'))->setDefaultValue(['2048' =>'2048']);

        $type = (new Hidden('type'))->setDefaultValue('rsa');

        $this
            ->addSection($keyid)
            ->addField($comment)
            ->addField($keySize)
            ->addSection($passwordSection)
            ->addField($authorize)
            ->addField($type);

        $this->loadDataToForm();

    }

}