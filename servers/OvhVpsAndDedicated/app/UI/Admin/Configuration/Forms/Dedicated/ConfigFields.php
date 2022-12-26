<?php

namespace ModulesGarden\Servers\OvhVpsAndDedicated\App\UI\Admin\Configuration\Forms\Dedicated;

use ModulesGarden\Servers\OvhVpsAndDedicated\App\Helpers\Basics\BaseConstants;
use ModulesGarden\Servers\OvhVpsAndDedicated\App\Helpers\FieldsProvider;
use ModulesGarden\Servers\OvhVpsAndDedicated\App\UI\Admin\Configuration\Fields\Dedicated\ApplicationLicenseSelect;
use ModulesGarden\Servers\OvhVpsAndDedicated\App\UI\Admin\Configuration\Fields\Dedicated\BandwidtSelect;
use ModulesGarden\Servers\OvhVpsAndDedicated\App\UI\Admin\Configuration\Fields\Dedicated\DataCenterSelect;
use ModulesGarden\Servers\OvhVpsAndDedicated\App\UI\Admin\Configuration\Fields\Dedicated\DistributionLicenseSelect;
use ModulesGarden\Servers\OvhVpsAndDedicated\App\UI\Admin\Configuration\Fields\Dedicated\DurationSelect;
use ModulesGarden\Servers\OvhVpsAndDedicated\App\UI\Admin\Configuration\Fields\Dedicated\MemorySelect;
use ModulesGarden\Servers\OvhVpsAndDedicated\App\UI\Admin\Configuration\Fields\Dedicated\PlanCodeSelect;
use ModulesGarden\Servers\OvhVpsAndDedicated\App\UI\Admin\Configuration\Fields\Dedicated\PricingModeSelect;
use ModulesGarden\Servers\OvhVpsAndDedicated\App\UI\Admin\Configuration\Fields\Dedicated\StorageSelect;
use ModulesGarden\Servers\OvhVpsAndDedicated\App\UI\Admin\Configuration\Fields\Dedicated\SystemTemplates;
use ModulesGarden\Servers\OvhVpsAndDedicated\App\UI\Admin\Configuration\Fields\Dedicated\Languages;
use ModulesGarden\Servers\OvhVpsAndDedicated\App\UI\Admin\Configuration\Fields\Dedicated\VrackSelect;
use ModulesGarden\Servers\OvhVpsAndDedicated\App\UI\Admin\Configuration\Forms\Basics\BaseProductAppForm;
use ModulesGarden\Servers\OvhVpsAndDedicated\App\UI\Admin\Configuration\Helpers\Dedicated\Config;
use ModulesGarden\Servers\OvhVpsAndDedicated\Core\UI\Interfaces\AdminArea;
use ModulesGarden\Servers\OvhVpsAndDedicated\Core\UI\Interfaces\ClientArea;
use ModulesGarden\Servers\OvhVpsAndDedicated\Core\UI\Widget\Forms\Fields\Select;
use ModulesGarden\Servers\OvhVpsAndDedicated\Core\UI\Widget\Forms\Sections\HalfPageSection;

/**
 * Description of RebuildVM
 *
 * @author Artur Pilch <artur.pi@modulesgarden.com>
 */
class ConfigFields extends BaseProductAppForm implements ClientArea, AdminArea
{

    protected $id    = 'ConfigDedicatedFields';
    protected $name  = 'ConfigDedicatedFields';
    protected $title = 'ConfigDedicatedFields';

    protected $config;

    public function __construct($baseId = null)
    {
        parent::__construct($baseId);
        $this->config = new Config($this->getRequestValue('id'));
    }

    public function getClass()
    {

    }

    public function initContent()
    {
        $config = new FieldsProvider($this->getRequestValue(BaseConstants::ID));

        $this->addSection($this->leftSection($config));
        $this->addSection($this->rightSection($config));

        $this->loadDataToForm();
    }

    private function leftSection(FieldsProvider $config)
    {
        $section = new HalfPageSection("leftDedicatedSection");
        //Operation System Template
        $section->addField($this->getSystemTemplates($config->getField('dedicatedSystemTemplate')));
        //planCode
        $field = new PlanCodeSelect('packageconfigoption_planCode');
        $field->setDescription('description');
        $field->setValue($config->getField('planCode'));
        $section->addField($field);
        //storage
        $field = new StorageSelect();
        $field->addReloadOnChangeField('packageconfigoption_planCode');
        $field->setDescription('description');
        $section->addField($field);
        //distribution-license
        $field = new DistributionLicenseSelect();
        $field->addReloadOnChangeField('packageconfigoption_planCode');
        $field->setDescription('description');
        $section->addField($field);
        //application-license
        $field = new ApplicationLicenseSelect();
        $field->addReloadOnChangeField('packageconfigoption_planCode');
        $field->setDescription('description');
        $section->addField($field);
        //Pricing Mode
        $field = new PricingModeSelect();
        $field->setDescription('description');
        $field->setDefaultValue('default');
        $section->addField($field);
        return $section;
    }


    private function rightSection(FieldsProvider $config)
    {
        $section = new HalfPageSection("rightDedicatedSection");
        $section->addField($this->getLanguages($config->getField('dedicatedLanguage')));
        //memory
        $field = new MemorySelect();
        $field->addReloadOnChangeField('packageconfigoption_planCode');
        $field->setDescription('description');
        $section->addField($field);
        //vrack
        $field = new VrackSelect();
        $field->addReloadOnChangeField('packageconfigoption_planCode');
        $field->setDescription('description');
        $section->addField($field);
        //bandwidth
        $field = new BandwidtSelect();
        $field->addReloadOnChangeField('packageconfigoption_planCode');
        $field->setDescription('description');
        $section->addField($field);
        //datacenter
        $field = new DataCenterSelect();
        $field->addReloadOnChangeField('packageconfigoption_planCode');
        $field->setDescription('description');
        $section->addField($field);
        //duration
        $field = new DurationSelect();
        $field->setDescription('description');
        $section->addField($field);
        return $section;
    }


    private function getSystemTemplates($value = null)
    {
        $field = new SystemTemplates('packageconfigoption_dedicatedSystemTemplates');
        $field->setDescription('systemTemplateDescription');

        return $field;
    }

    private function getLanguages($value = null)
    {
        $field = new Languages('packageconfigoption_dedicatedLanguages');
        $field->setDescription('dedicatedLanguageDescription');
        $field->addReloadOnChangeField('packageconfigoption_dedicatedSystemTemplates');

        return $field;
    }
}
