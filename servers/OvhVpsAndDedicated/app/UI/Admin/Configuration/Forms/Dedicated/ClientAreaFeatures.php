<?php

namespace ModulesGarden\Servers\OvhVpsAndDedicated\App\UI\Admin\Configuration\Forms\Dedicated;

use ModulesGarden\Servers\OvhVpsAndDedicated\App\Enum\Dedicatedformation;
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
    }

    protected function leftSection(FieldsProvider $config)
    {
        $section = new HalfPageSection('leftSection');
        $section
            ->addField($this->getClientAreaIps($config->getField('dedicatedClientAreaIps')))
            ->addField($this->getClientAreaIpReverse($config->getField('dedicatedClientAreaIpsReverse')))
            ->addField($this->getClientAreaRescue($config->getField('dedicatedClientAreaRescue')));
        //ipmiAccess
        $field = new Switcher('packageconfigoption[ipmiAccess]');
        $field->setDescription('description');
        $field->setValue($config->getField('ipmiAccess'));
        $section->addField($field);
        return $section;
    }

    public function rightSection(FieldsProvider $config)
    {
        $section = new HalfPageSection('rightSection');
        $section->addField($this->getClientAreaGraphs($config->getField('dedicatedClientAreaGraphs')));
        $section->addField($this->getClientAreaReinstall($config->getField('dedicatedClientAreaReinstall')));
        //Service Information
        $field = new Select('packageconfigoption[serviceInformation][]');
        $field->setDescription('description');
        $field->enableMultiple();
        $field->setAvailableValues(Dedicatedformation::getKeysAndTranslate());
        $field->setValue($config->getServiceInformation());
        $section->addField($field);

        return $section;
    }

    protected function getClientAreaReinstall($value = null)
    {
        $field = new Switcher('packageconfigoption[dedicatedClientAreaReinstall]');
        $field->setDescription('dedicatedClientAreaReinstallDescription')
            ->setValue($value);
        return $field;
    }


    protected function getClientAreaIps($value = null)
    {
        $field = new Switcher('packageconfigoption[dedicatedClientAreaIps]');
        $field->setDescription('dedicatedClientAreaIpsDescription')
            ->setValue($value);
        return $field;
    }

    protected function getClientAreaIpReverse($value = null)
    {
        $field = new Switcher('packageconfigoption[dedicatedClientAreaIpsReverse]');
        $field->setDescription('dedicatedClientAreaIpReverseDedicatedDesc')
            ->setValue($value);
        return $field;
    }



    protected function getClientAreaGraphs($value = null)
    {
        $field = new Switcher('packageconfigoption[dedicatedClientAreaGraphs]');
        $field->setDescription('dedicatedClientAreaGraphsDedicatedDesc')
            ->setValue($value);
        return $field;
    }

    protected function getClientAreaRescue($value = null)
    {
        $field = new Switcher('packageconfigoption[dedicatedClientAreaRescue]');
        $field->setDescription('dedicatedClientAreaRescueDesc')
            ->setValue($value);
        return $field;
    }

}
