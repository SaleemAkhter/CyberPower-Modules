<?php

namespace ModulesGarden\Servers\DirectAdminExtended\App\UI\Client\Users\Pages;

use ModulesGarden\Servers\DirectAdminExtended\App\Traits\DirectAdminAPIComponent;
use ModulesGarden\Servers\DirectAdminExtended\Core\UI\Interfaces\ClientArea;
use ModulesGarden\Servers\DirectAdminExtended\App\Helpers\ApplicationInstaller;
use ModulesGarden\Servers\DirectAdminExtended\Core\Helper\BuildUrl;
use ModulesGarden\Servers\DirectAdminExtended\App\UI\Client\Customs\Fields\AriaField;
use ModulesGarden\Servers\DirectAdminExtended\App\UI\Client\Customs\Fields\AppBox;
use ModulesGarden\Servers\DirectAdminExtended\Core\UI\Widget\Forms\BaseStandaloneForm;
use ModulesGarden\Servers\DirectAdminExtended\Core\UI\Widget\Forms\Sections\RawSection;
use function ModulesGarden\Servers\DirectAdminExtended\Core\Helper\sl;
use ModulesGarden\Servers\DirectAdminExtended\App\UI\Client\Customs\Sections\FormGroupSection;
use ModulesGarden\Servers\DirectAdminExtended\Core\UI\Widget\Forms\Fields;
use ModulesGarden\Servers\DirectAdminExtended\App\Helpers\Validator;
use ModulesGarden\Servers\DirectAdminExtended\App\UI\Client\Users\Providers;
use ModulesGarden\Servers\DirectAdminExtended\Core\UI\Widget\Forms\FormConstants;
use ModulesGarden\Servers\DirectAdminExtended\Core\ServiceLocator;
use ModulesGarden\Servers\DirectAdminExtended\App\UI\Client\Customs\Sections\ListSection;
use ModulesGarden\Servers\DirectAdminExtended\App\UI\Client\Customs\Sections\TableSection;
use ModulesGarden\Servers\DirectAdminExtended\App\UI\Client\Users\Buttons;

class UserEdit extends BaseStandaloneForm implements ClientArea
{
    use DirectAdminAPIComponent;

    protected $id = 'editForm';
    protected $name = 'editForm';
    protected $title = 'editForm';

    public function initContent()
    {

        $this->addReplacement();
        $this->setFormType(FormConstants::UPDATE);
        $this->setProvider(new Providers\UsersEdit());
        $this->dataProvider->read();
        $data=$this->dataProvider->getData();

        $this->addSection($this->getPackageIpFieldsSection());
        $this->addButton(new Buttons\Add());

        $this->addSection((new TableSection('currentips' ))->setItems($data['currentips']));

        $this->addSection($this->getSkinFieldsSection());
        $this->addField(new Fields\Hidden('user'));
        $this->addField(new Fields\Hidden('username'));
        $this->addField(new Fields\Hidden('oldpackage'));

        $this->loadDataToForm();


    }

    protected function getPackageIpFieldsSection()
    {
        $selectFieldsSection = (new FormGroupSection('packagesSection'))
            ->addField(new Fields\Select('package'))
            ->addField(new Fields\Select('ip'))
             ;
        return $selectFieldsSection;
    }
    protected function getSkinFieldsSection()
    {
        $selectFieldsSection = (new FormGroupSection('skinSection'))
            ->addField(new Fields\Select('skin'));

        return $selectFieldsSection;
    }
    public function addReplacement()
    {
        $userName = $this->getRequestValue('actionElementId' ,false);
        ServiceLocator::call('lang')->addReplacementConstant('username', $userName);
    }
    protected function getUsernameSection()
    {

        $usernameSection = new FormGroupSection('usernameSection');

        $usernameSection->addField((new Fields\Text('name')))
                        ->addField((new Fields\Text('email'))->addValidator(new Validator\Email()));

        return $usernameSection;
    }



}
