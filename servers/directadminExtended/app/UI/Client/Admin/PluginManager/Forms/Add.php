<?php

namespace ModulesGarden\Servers\DirectAdminExtended\App\UI\Client\Admin\PluginManager\Forms;

use ModulesGarden\Servers\DirectAdminExtended\App\UI\Client\Customs\Fields\ActionSwitcher;
use ModulesGarden\Servers\DirectAdminExtended\Core\Models\ProductSettings\Repository;
use ModulesGarden\Servers\DirectAdminExtended\Core\UI\Widget\Forms\BaseForm;
use ModulesGarden\Servers\DirectAdminExtended\App\UI\Client\Customs\Fields\PasswordGenerateExtended;

use ModulesGarden\Servers\DirectAdminExtended\App\UI\Client\Customs\Sections\FormGroupSection;

use ModulesGarden\Servers\DirectAdminExtended\Core\UI\Interfaces\ClientArea;
use ModulesGarden\Servers\DirectAdminExtended\Core\UI\Widget\Forms\Fields;
use ModulesGarden\Servers\DirectAdminExtended\Core\UI\Widget\Forms\FormConstants;
use ModulesGarden\Servers\DirectAdminExtended\App\UI\Client\Admin\PluginManager\Providers;
use ModulesGarden\Servers\DirectAdminExtended\App\Helpers\Validator;
use ModulesGarden\Servers\DirectAdminExtended\Core\UI\Widget\Forms\Validators\BaseValidator;
use ModulesGarden\Servers\DirectAdminExtended\App\UI\Client\Customs\Fields\Radio;
use ModulesGarden\Servers\DirectAdminExtended\App\UI\Client\Customs\Fields\Upload;

class Add extends BaseForm implements ClientArea
{
    protected $id    = 'addForm';
    protected $name  = 'addForm';
    protected $title = 'addForm';



    public function initContent()
    {
        $this->setFormType(FormConstants::CREATE)
            ->setProvider(new Providers\Plugin());

        $this->addSection($this->getUsernameSection())
        ->addSection($this->getUrlSection())
        ->addSection($this->getFileSection())
        ->addSection($this->getPasswordSection())
        ->addSection($this->getInstallSection());
        $this->loadDataToForm();
    }
    protected function getUsernameSection()
    {
        $methodSection = new FormGroupSection('uploadMethodSection');
        $methodSection->addField( (new Fields\Select('uploadMethod'))->addHtmlAttribute('@change', 'initReloadModal()')->setAvailableValues(['url'=>"URL",'file'=>"File"]));

        return $methodSection;
    }
    protected function getPasswordSection()
    {

        $passwordSection = new FormGroupSection('passwordSection');
        $password = new Fields\Password('password');
        $password->notEmpty();
        $passwordSection->addField($password);

        return $passwordSection;
    }
    protected function getUrlSection()
    {
        $urlSection = (new FormGroupSection('urlSection'))
            ->addField((new Fields\Text('url')));

        return $urlSection;
    }
    protected function getFileSection()
    {
        $fileSection = (new FormGroupSection('fileSection'))
            ->addField((new Upload('plugin')));

        return $fileSection;
    }
    protected function getInstallSection()
    {
        $installSection = (new FormGroupSection('installSection'))
            ->addField((new Fields\Checkbox('install')));

        return $installSection;
    }

    protected function reloadFormStructure()
    {
        $this->dataProvider->reload();
        $this->loadDataToForm();

        $type = $this->getRequestValue('formData')['uploadMethod'];
        if ($type === 'url')
        {
            $this->removeField('value');
            $url = new Fields\Text('url');

            $this->addField($url);
            $this->dataProvider->reload();
            $this->loadDataToForm();
        }
        elseif($type === 'file')
        {
            $this->removeField('value');


            $uploadfile = new Upload('plugin');
            $this->addField($uploadfile);
        }

    }

    function addFieldAfter($fieldId, $newField)
    {
        $array = $this->getFields();
        $index = array_search($fieldId, array_keys($array)) + 1;


        $size = count($array);
        if (!is_int($index) || $index < 0 || $index > $size)
        {
            return -1;
        }
        else
        {
            $temp   = array_slice($array, 0, $index);
            $temp[$newField->getId(). $index] = $newField;
            $this->fields = array_merge($temp, array_slice($array, $index, $size));
        }
    }
}
