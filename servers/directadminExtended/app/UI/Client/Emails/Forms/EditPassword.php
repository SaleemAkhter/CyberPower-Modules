<?php

namespace ModulesGarden\Servers\DirectAdminExtended\App\UI\Client\Emails\Forms;

use ModulesGarden\Servers\DirectAdminExtended\App\UI\Client\Customs\Fields\PasswordGenerateExtended;
use ModulesGarden\Servers\DirectAdminExtended\App\UI\Client\Customs\Sections\FormGroupSection;
use ModulesGarden\Servers\DirectAdminExtended\Core\UI\Interfaces\ClientArea;
use ModulesGarden\Servers\DirectAdminExtended\Core\UI\Widget\Forms\BaseForm;
use ModulesGarden\Servers\DirectAdminExtended\Core\UI\Widget\Forms\Fields\Hidden;
use ModulesGarden\Servers\DirectAdminExtended\Core\UI\Widget\Forms\Fields\Password;
use ModulesGarden\Servers\DirectAdminExtended\Core\UI\Widget\Forms\Fields\Switcher;
use ModulesGarden\Servers\DirectAdminExtended\Core\UI\Widget\Forms\FormConstants;
use ModulesGarden\Servers\DirectAdminExtended\App\UI\Client\Emails\Providers;
use ModulesGarden\Servers\DirectAdminExtended\Core\UI\Widget\Forms\Fields\InputGroupElements;
use ModulesGarden\Servers\DirectAdminExtended\Core\UI\Widget\Forms\Sections\InputGroup;

class EditPassword extends BaseForm implements ClientArea
{
    protected $id    = 'editForm';
    protected $name  = 'editForm';
    protected $title = 'editForm';

    public function initContent()
    {
        $this->setFormType(FormConstants::UPDATE)
            ->setProvider(new Providers\EmailsEdit());

        $this->checkRecord();
    }

    protected function checkRecord()
    {
        $actionData = json_decode(base64_decode($this->getRequestValue('actionElementId')));

        if($actionData->main)
        {
            return $this->addChangePasswordMain();
        }

        return $this->addChangePasswordNormal();
    }

    protected function addChangePasswordMain()
    {
        $this->addField((new Password('oldPassword')));
        $this->addSection($this->getPasswordSection());

        $this->addField((new Switcher('daAccount'))->setDescription('daPassword'));
        $this->addField(new Switcher('ftpAccount'));
        $this->addField(new Switcher('dbAccount'));

        $this->loadDataToForm();
    }

    protected function getPasswordSection()
    {
        $passwordSection = new FormGroupSection('passwordSection');
        $password = new PasswordGenerateExtended('password');
        $password->notEmpty();
        $passwordSection->addField($password);

        return $passwordSection;
    }

    protected function addChangePasswordNormal()
    {
        $this->addSection($this->getAccountSection())
            ->addSection($this->getPasswordSection());

        $quota = new Hidden('quota');
        $customQuota = new Hidden('customQuota');
        $limit = new Hidden('limit');
        $customLimit = new Hidden('customLimit');

        $this->addField($quota)
            ->addField($limit)
            ->addField($customQuota)
            ->addField($customLimit);

        $this->loadDataToForm();
    }

    protected function getAccountSection()
    {
        $email = (new InputGroup('emailGroup'))
            ->addInputComponent((new InputGroupElements\Text('accountCopy')))
            ->addInputAddon('@', false, '@')
            ->addInputComponent((new InputGroupElements\Select('domainsCopy'))->disableField())
            ->setDescription('')
            ->addField(new Hidden('domains'))
            ->addField(new Hidden('account'));

        return $email;
    }
}