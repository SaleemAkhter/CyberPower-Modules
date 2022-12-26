<?php

namespace ModulesGarden\Servers\OvhVpsAndDedicated\App\UI\Admin\Configuration\Forms;

use ModulesGarden\Servers\OvhVpsAndDedicated\App\Enum\ServiceInformation;
use ModulesGarden\Servers\OvhVpsAndDedicated\App\Helpers\FieldsProvider;
use ModulesGarden\Servers\OvhVpsAndDedicated\App\UI\Admin\Configuration\Forms\Basics\BaseProductAppForm;
use ModulesGarden\Servers\OvhVpsAndDedicated\Core\UI\Interfaces\AdminArea;
use ModulesGarden\Servers\OvhVpsAndDedicated\Core\UI\Interfaces\ClientArea;
use ModulesGarden\Servers\OvhVpsAndDedicated\Core\UI\Widget\Forms\Fields\Select;
use ModulesGarden\Servers\OvhVpsAndDedicated\Core\UI\Widget\Forms\Fields\Switcher;
use ModulesGarden\Servers\OvhVpsAndDedicated\Core\UI\Widget\Forms\Sections\HalfPageSection;

/**
 * Description of RebuildVM
 *
 * @author Artur Pilch <artur.pi@modulesgarden.com>
 */
class ClientAreaFeatures extends BaseProductAppForm implements ClientArea, AdminArea
{

    protected $id    = 'ClientAreaFeatures';
    protected $name  = 'ClientAreaFeatures';
    protected $title = 'ClientAreaFeatures';


    public function getClass()
    {
        
    }

    public function initContent()
    {
        $this->addClass('lu-row');
        $config = new FieldsProvider($this->getRequestValue('id'));


        $this->addSection($this->leftSection($config));
        $this->addSection($this->rightSection($config));
        $this->loadDataToForm();
    }

    protected function leftSection(FieldsProvider $config)
    {
        $section = new HalfPageSection('leftSection');
        $section->addField($this->getClientAreaDisks($config->getField('vpsClientAreaDisks')))
                ->addField($this->getClientAreaReinstall($config->getField('vpsClientAreaReinstall')))
                ->addField($this->getClientAreaConsole($config->getField('vpsClientAreaConsole')));
        //Service Information
        $field = new Select('packageconfigoption[serviceInformation][]');
        $field->enableMultiple();
        $field->setDescription('description');
        $field->setAvailableValues(ServiceInformation::getKeysAndTranslate());
        $field->setValue($config->getServiceInformation());
        $section->addField($field);
        return $section;
    }

    protected function rightSection(FieldsProvider $config)
    {
        $section = new HalfPageSection('rightSection');
        $section
            ->addField($this->getClientAreaRescue($config->getField('vpsClientAreaRescue')))
            ->addField($this->getClientAreaIps($config->getField('vpsClientAreaIps')))
            ->addField($this->getClientAreaIpsReverse($config->getField('vpsClientAreaIpsReverse')));
        return $section;
    }


    protected function getClientAreaDisks($value = null)
    {
        $field = new Switcher('packageconfigoption[vpsClientAreaDisks]');
        $field->setDescription('clientAreaDisksDescription')
            ->setValue($value);
        return $field;
    }
    protected function getClientAreaIps($value = null)
    {
        $field = new Switcher('packageconfigoption[vpsClientAreaIps]');
        $field->setDescription('clientAreaIpsDescription')
            ->setValue($value);
        return $field;
    }

    protected function getClientAreaIpsReverse($value = null)
    {
        $field = new Switcher('packageconfigoption[vpsClientAreaIpsReverse]');
        $field->setDescription('clientAreaIpsReverseDescription')
            ->setValue($value);
        return $field;
    }

    protected function getClientAreaConsole($value = null)
    {
        $field = new Switcher('packageconfigoption[vpsClientAreaConsole]');
        $field->setDescription('clientAreaConsoleDescription')
            ->setValue($value);
        return $field;
    }

    protected function getClientAreaRescue($value = null)
    {
        $field = new Switcher('packageconfigoption[vpsClientAreaRescue]');
        $field->setDescription('clientAreaRescueDescription')
            ->setValue($value);
        return $field;
    }

    protected function getClientAreaVpsChangePassword($value = null)
    {
        $field = new Switcher('packageconfigoption[vpsClientAreaChangePassword]');
        $field->setDescription('vpsClientAreaChangePasswordDescription')
            ->setValue($value);
        return $field;
    }

    protected function getClientAreaReinstall($value = null)
    {
        $field = new Switcher('packageconfigoption[vpsClientAreaReinstall]');
        $field->setDescription('clientAreaVpsReinstallDescription')
            ->setValue($value);
        return $field;
    }
}
