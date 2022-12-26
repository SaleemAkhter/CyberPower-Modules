<?php

namespace ModulesGarden\Servers\DirectAdminExtended\App\UI\Client\Admin\Nameservers\Pages;

use ModulesGarden\Servers\DirectAdminExtended\App\Traits\DirectAdminAPIComponent;
use ModulesGarden\Servers\DirectAdminExtended\Core\UI\Interfaces\ClientArea;
use ModulesGarden\Servers\DirectAdminExtended\App\Helpers\ApplicationInstaller;
use ModulesGarden\Servers\DirectAdminExtended\Core\Helper\BuildUrl;
use ModulesGarden\Servers\DirectAdminExtended\App\UI\Client\Customs\Fields\AriaField;
use ModulesGarden\Servers\DirectAdminExtended\App\UI\Client\Customs\Fields\AppBox;
use ModulesGarden\Servers\DirectAdminExtended\App\UI\Client\Customs\Forms\BoxedForm;
use ModulesGarden\Servers\DirectAdminExtended\Core\UI\Widget\Forms\Sections\RawSection;
use function ModulesGarden\Servers\DirectAdminExtended\Core\Helper\sl;
use ModulesGarden\Servers\DirectAdminExtended\App\UI\Client\Customs\Sections\FormGroupSection;
use ModulesGarden\Servers\DirectAdminExtended\Core\UI\Widget\Forms\Fields;
use ModulesGarden\Servers\DirectAdminExtended\App\Helpers\Validator;

use ModulesGarden\Servers\DirectAdminExtended\App\UI\Client\Admin\Nameservers\Providers;
use ModulesGarden\Servers\DirectAdminExtended\Core\UI\Widget\Forms\FormConstants;
use ModulesGarden\Servers\DirectAdminExtended\Core\ServiceLocator;
use ModulesGarden\Servers\DirectAdminExtended\App\UI\Client\Customs\Sections\ListSection;
use ModulesGarden\Servers\DirectAdminExtended\App\UI\Client\Customs\Sections\TableSection;
use ModulesGarden\Servers\DirectAdminExtended\App\UI\Client\Admin\Nameservers\Buttons;
use \ModulesGarden\Servers\DirectAdminExtended\Core\UI\Widget\Forms\Validators as FieldsValidators;
use ModulesGarden\Servers\DirectAdminExtended\App\UI\Client\Customs\Fields as CF;

class Nameservers extends BoxedForm implements ClientArea
{
    use DirectAdminAPIComponent;

    protected $id = 'nameserversForm';
    protected $name = 'nameserversForm';
    protected $title = 'nameserversForm';
    protected $containerColClass='lu-col-md-6';

    public function getDefaultActions()
    {
        return ['changenameservers'];
    }
    public function initContent()
    {
        $this->addReplacement();
        $this->setFormType('changenameservers');
        $this->setProvider(new Providers\Nameservers());
        $this->dataProvider->read();
        $this->addSection($this->getNSFieldsSection());
        $this->loadDataToForm();
    }

    protected function getNSFieldsSection()
    {
        $selectFieldsSection = (new RawSection('packagesSection'))
            ->addField((new CF\TextHorizontal('ns1'))->addValidator(new FieldsValidators\NotEmpty()))
            ->addField((new CF\TextHorizontal('ns2'))->addValidator(new FieldsValidators\NotEmpty()));
        return $selectFieldsSection;
    }

    public function addReplacement()
    {
        $userName = $this->getRequestValue('actionElementId' ,false);
        ServiceLocator::call('lang')->addReplacementConstant('username', $userName);
    }
}
