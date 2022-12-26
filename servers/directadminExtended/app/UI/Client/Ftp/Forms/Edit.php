<?php

namespace ModulesGarden\Servers\DirectAdminExtended\App\UI\Client\Ftp\Forms;

use ModulesGarden\Servers\DirectAdminExtended\App\UI\Client\Customs\Fields\PasswordGenerateExtended;
use ModulesGarden\Servers\DirectAdminExtended\App\UI\Client\Customs\Sections\FormGroupSection;
use ModulesGarden\Servers\DirectAdminExtended\Core\UI\Interfaces\ClientArea;
use ModulesGarden\Servers\DirectAdminExtended\Core\UI\Widget\Forms\FormConstants;
use ModulesGarden\Servers\DirectAdminExtended\App\UI\Client\Ftp\Providers;
use ModulesGarden\Servers\DirectAdminExtended\Core\UI\Widget\Forms\Fields\InputGroupElements;
use ModulesGarden\Servers\DirectAdminExtended\Core\UI\Widget\Forms\Sections\InputGroup;

use ModulesGarden\Servers\DirectAdminExtended\Core\UI\Widget\Forms\Fields;

class Edit extends Add implements ClientArea
{
    protected $id    = 'editForm';
    protected $name  = 'editForm';
    protected $title = 'editForm';

    public function initContent()
    {
        $this->setFormType(FormConstants::UPDATE)
            ->setProvider(new Providers\FtpEdit());

        $this->addSection($this->getLoginSection())
            ->addSection($this->getPasswordSection())
            ->addSection($this->getDirectorySection())
            ->addField(new Fields\Text('customDirectory'))
            ->loadDataToForm();

        if ($this->getSection('directionSection')->getField('directory')->getValue() !== 'custom')
        {
            unset($this->fields['customDirectory']);
        }
    }

    protected function getPasswordSection()
    {

        $passwordSection = new FormGroupSection('passwordSection');
        $password = (new PasswordGenerateExtended('password'))->setDescription('passDesc')->notEmpty();
        $passwordSection->addField($password);

        return $passwordSection;
    }

    protected function getLoginSection()
    {
        $login = (new InputGroup('loginGroup'))
            ->addInputComponent((new InputGroupElements\Text('nameCopy'))->disableField())
            ->addInputAddon('@', false, '@')
            ->addInputComponent((new InputGroupElements\Select('domainCopy'))->disableField())
            ->setDescription('')
            ->addField(new Fields\Hidden('name'))
            ->addField(new Fields\Hidden('domain'));

        return $login;
    }
}
