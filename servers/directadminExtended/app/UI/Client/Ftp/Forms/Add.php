<?php

namespace ModulesGarden\Servers\DirectAdminExtended\App\UI\Client\Ftp\Forms;

use ModulesGarden\Servers\DirectAdminExtended\App\UI\Client\Customs\Fields\PasswordGenerateExtended;
use ModulesGarden\Servers\DirectAdminExtended\App\UI\Client\Customs\Sections\FormGroupSection;
use ModulesGarden\Servers\DirectAdminExtended\Core\UI\Interfaces\ClientArea;
use ModulesGarden\Servers\DirectAdminExtended\Core\UI\Widget\Forms\BaseForm;
use ModulesGarden\Servers\DirectAdminExtended\Core\UI\Widget\Forms\Fields;
use ModulesGarden\Servers\DirectAdminExtended\App\UI\Client\Customs\Buttons\GeneratePassword;
use ModulesGarden\Servers\DirectAdminExtended\Core\UI\Widget\Forms\FormConstants;
use ModulesGarden\Servers\DirectAdminExtended\Core\UI\Widget\Forms\Fields\InputGroupElements;
use ModulesGarden\Servers\DirectAdminExtended\Core\UI\Widget\Forms\Sections;
use ModulesGarden\Servers\DirectAdminExtended\App\UI\Client\Ftp\Providers;

class Add extends BaseForm implements ClientArea
{
    protected $id    = 'addForm';
    protected $name  = 'addForm';
    protected $title = 'addForm';

    public function initContent()
    {
        $this->setFormType(FormConstants::CREATE)
                ->setProvider(new Providers\FtpCreate());

        $this->addSection($this->getLoginSection())
                ->addSection($this->getPasswordSection())
                ->addSection($this->getDirectorySection())
                ->loadDataToForm();
    }

    protected function getLoginSection()
    {
        $loginGroup = (new Sections\InputGroup('loginGroup'))
            ->addTextField('name')
            ->addInputAddon('@', false, '@')
            ->addInputComponent(new InputGroupElements\Select('domain'));

        return $loginGroup;
    }

    protected function getPasswordSection()
    {

        $passwordSection = new FormGroupSection('passwordSection');
        $password = (new PasswordGenerateExtended('password'))->setDescription('passDesc')->notEmpty();
        $passwordSection->addField($password);

        return $passwordSection;
    }

    protected function getDirectorySection()
    {
        $directorySection = (new FormGroupSection('directionSection'))
            ->addField((new Fields\Select('directory'))
                ->addHtmlAttribute('bi-event-change', 'initReloadModal'));

        return $directorySection;
    }

    protected function reloadFormStructure()
    {
        $this->removeField('customDirectory');

        $directoryValue = $this->getRequestValue('formData')['directory'];
        if ($directoryValue === 'custom')
        {
            $pathSection =  new FormGroupSection('customPathSection');
            $pathSection->addField(new Fields\Text('customDirectory'));

            $this->addSection($pathSection);
            $this->dataProvider->reload();
            $this->loadDataToForm();
        }

    }
}

