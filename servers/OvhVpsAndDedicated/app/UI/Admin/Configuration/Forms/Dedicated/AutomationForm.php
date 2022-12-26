<?php

namespace ModulesGarden\Servers\OvhVpsAndDedicated\App\UI\Admin\Configuration\Forms\Dedicated;


use ModulesGarden\Servers\OvhVpsAndDedicated\App\Helpers\FieldsProvider;
use ModulesGarden\Servers\OvhVpsAndDedicated\App\UI\Admin\Configuration\Forms\AutomationForm as VpsAutomationForm;
use ModulesGarden\Servers\OvhVpsAndDedicated\App\UI\Admin\Configuration\Helpers\Dedicated\AutomationSettings;
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
class AutomationForm extends VpsAutomationForm implements ClientArea, AdminArea
{

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

        $section
            ->addField($this->getRemoveServerFromClientOnTerminate($config->getField('dedicatedAutoRemoveServerFromClientOnTerminate')))
            ->addField($this->getActionOnSuspendService($config->getField('dedicatedActionOnSuspendService')))
        ;
        return $section;
    }

    protected function rightSection(FieldsProvider $config)
    {
        $section = new HalfPageSection('rightSection');
        $section
            ->addField($this->getOVHautoPayWithPreferredMethod($config->getField(self::AUTO_PAY_WITH_PREFERRED_METHOD)))
            ->addField($this->getOVHwaiveRetractationPeriod($config->getField(self::WAIVE_RETRACTATION_PERIOD)));
        return $section;
    }


    protected function getRemoveServerFromClientOnTerminate($value = null)
    {
        $field = new Switcher('packageconfigoption[dedicatedAutoRemoveServerFromClientOnTerminate]');
        $field->setDescription('dedicatedAutoRemoveServerFromClientOnTerminateDescription')
            ->setValue($value);
        return $field;
    }

    protected function getActionOnSuspendService($value = null)
    {
        $field = new Select('packageconfigoption[dedicatedActionOnSuspendService]');

        $field->setDescription('dedicatedActionOnSuspendServiceDescription')
            ->setAvailableValues(AutomationSettings::getLangedOptionsOnSuspendAction())
            ->setSelectedValue($value);
        return $field;
    }

}
