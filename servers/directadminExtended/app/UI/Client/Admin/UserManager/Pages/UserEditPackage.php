<?php

namespace ModulesGarden\Servers\DirectAdminExtended\App\UI\Client\Admin\UserManager\Pages;

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
use ModulesGarden\Servers\DirectAdminExtended\Core\UI\Widget\Buttons\ButtonSubmitForm;
use ModulesGarden\Servers\DirectAdminExtended\App\UI\Client\Customs\Fields\Select;
use ModulesGarden\Servers\DirectAdminExtended\Core\UI\Widget\Forms\Sections\BoxSection;
use ModulesGarden\Servers\DirectAdminExtended\Core\UI\Widget\Forms\Sections;

class UserEditPackage extends BaseStandaloneForm implements ClientArea
{
    use DirectAdminAPIComponent;

    protected $id = 'editPackageForm';
    protected $name = 'editPackageForm';
    protected $title = '';

    public function initContent()
    {

        $this->addReplacement();
        $this->setFormType(FormConstants::UPDATE);
        $this->setProvider(new Providers\UsersEdit());
        $this->setRawTitle("<h6><strong>Change Package for User ".$this->actionElementId."</strong></h6>");
        // $dataBaseSection = new Sections\BaseSection('dataBaseSection');
        $this->addSection($this->getPackageFieldsSection());
        $this->addField(new Fields\Hidden('user'));
        $this->addField(new Fields\Hidden('editPackage'));

        $this->loadDataToForm();
    }

    protected function getPackageFieldsSection()
    {
        $selectFieldsSection = (new FormGroupSection('packagesSection'))
            ->addField((new Select('package'))->setFormId($this->id)->setContainerClasss(['lu-col-md-8']));
        return $selectFieldsSection;
    }

    public function addReplacement()
    {
        $userName = $this->getRequestValue('actionElementId' ,false);
        ServiceLocator::call('lang')->addReplacementConstant('username', $userName);
    }
}
