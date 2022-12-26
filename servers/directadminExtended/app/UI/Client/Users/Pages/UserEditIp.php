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
use ModulesGarden\Servers\DirectAdminExtended\Core\UI\Widget\Forms\Sections\BoxSection;
use ModulesGarden\Servers\DirectAdminExtended\Core\UI\Widget\Forms\Sections;
use ModulesGarden\Servers\DirectAdminExtended\App\UI\Client\Customs\Fields\Select;

class UserEditIp extends BaseStandaloneForm implements ClientArea
{
    use DirectAdminAPIComponent;

    protected $id = 'editIpForm';
    protected $name = 'editIpForm';
    protected $title = '';

    public function initContent()
    {

        $this->addReplacement();
        $this->setRawTitle("<h6 class='pt-20'><strong>Change the User's IP</strong></h6>");
        $this->setFormType(FormConstants::UPDATE);
        $this->setProvider(new Providers\UsersEdit());
        $this->dataProvider->read();
        $data=$this->dataProvider->getData();


        $selectFieldsSection=$this->getPackageIpFieldsSection($formBox)
        ->addField(new Fields\Hidden('user'))
        ->addField(new Fields\Hidden('username'))
        ->addField(new Fields\Hidden('editIp'));
        $tableSection=new TableSection('currentips' );
        $tableSection->setMainContainer($formBox)->setContainerClasss(['lu-col-md-8']);
        $tableSection->setHeaders(['Current IPs','IP Type']);
        $tableSection->setItems($data['currentips']);

        $this->addSection($formBox)->addSection($selectFieldsSection)
                ->addSection($tableSection);
        // $this->addSection($this->getSkinFieldsSection());


        $this->loadDataToForm();


    }

    protected function getPackageIpFieldsSection()
    {
        $selectFieldsSection =(new FormGroupSection('packagesSection'))
            ->addField((new Select('ip'))->setFormId($this->id)->setContainerClasss(['lu-col-md-8']));
        return $selectFieldsSection;
    }

    public function addReplacement()
    {
        $userName = $this->getRequestValue('actionElementId' ,false);
        ServiceLocator::call('lang')->addReplacementConstant('username', $userName);
    }

}
