<?php

namespace ModulesGarden\Servers\OvhVpsAndDedicated\App\UI\Admin\Configuration\Forms;

use ModulesGarden\Servers\OvhVpsAndDedicated\App\Helpers\FieldsProvider;
use ModulesGarden\Servers\OvhVpsAndDedicated\App\UI\Admin\Configuration\Forms\Basics\BaseProductAppForm;
use ModulesGarden\Servers\OvhVpsAndDedicated\Core\UI\Interfaces\AdminArea;
use ModulesGarden\Servers\OvhVpsAndDedicated\Core\UI\Interfaces\ClientArea;
use ModulesGarden\Servers\OvhVpsAndDedicated\Core\UI\Widget\Forms\Fields\Select;
use ModulesGarden\Servers\OvhVpsAndDedicated\Core\UI\Widget\Forms\Fields\Switcher;
use ModulesGarden\Servers\OvhVpsAndDedicated\Core\UI\Widget\Forms\Sections\HalfPageSection;
use ModulesGarden\Servers\OvhVpsAndDedicated\App\UI\Admin\Configuration\Helpers\AutomationSettings;

/**
 * Description of RebuildVM
 *
 * @author Artur Pilch <artur.pi@modulesgarden.com>
 */
class AutomationForm extends BaseProductAppForm implements ClientArea, AdminArea
{
    protected $id    = 'AutomationForm';
    protected $name  = 'AutomationForm';
    protected $title = 'AutomationForm';

    const WAIVE_RETRACTATION_PERIOD = 'waiveRetractationPeriod';
    const AUTO_PAY_WITH_PREFERRED_METHOD = 'autoPayWithPreferredMethod';

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
        $section
            ->addField($this->getActionOnSuspendService($config->getField('vpsActionOnSuspendService')))
            ->addField($this->getVpsToReuseListOnTerminate($config->getField('autoAssignVpsToReuseListOnTerminate')));

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


    protected function getOVHautoPayWithPreferredMethod($value = null)
    {
        $field = new Switcher("packageconfigoption[".self::AUTO_PAY_WITH_PREFERRED_METHOD."]");
        $field->setDescription('autoPayWithPreferredMethodDescription')
            ->setValue($value);
        return $field;
    }

    protected function getOVHwaiveRetractationPeriod($value = null)
    {
        $field = new Switcher("packageconfigoption[".self::WAIVE_RETRACTATION_PERIOD."]");
        $field->setDescription('waiveRetractationPeriodDescription')
            ->setValue($value);
        return $field;
    }

    protected function getVpsToReuseListOnTerminate($value = null)
    {
        $field = new Switcher('packageconfigoption[autoAssignVpsToReuseListOnTerminate]');
        $field->setDescription('autoAssignVpsToReuseListOnTerminateDescription')
            ->setValue($value);
        return $field;
    }

    protected function getActionOnSuspendService($value = null)
    {
        $field = new Select('packageconfigoption[vpsActionOnSuspendService]');

        $field->setDescription('vpsActionOnSuspendServiceDescription')
            ->setAvailableValues(AutomationSettings::getLangedOptionsOnSuspendAction())
            ->setSelectedValue($value);

        return $field;
    }
}
