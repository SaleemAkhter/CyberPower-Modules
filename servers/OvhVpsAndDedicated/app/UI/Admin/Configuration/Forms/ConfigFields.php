<?php

namespace ModulesGarden\Servers\OvhVpsAndDedicated\App\UI\Admin\Configuration\Forms;

use ModulesGarden\Servers\OvhVpsAndDedicated\App\Helpers\FieldsProvider;
use ModulesGarden\Servers\OvhVpsAndDedicated\App\UI\Admin\Configuration\Fields\Vps\Category;
use ModulesGarden\Servers\OvhVpsAndDedicated\App\UI\Admin\Configuration\Fields\Vps\Distribution;
use ModulesGarden\Servers\OvhVpsAndDedicated\App\UI\Admin\Configuration\Fields\Vps\Duration;
use ModulesGarden\Servers\OvhVpsAndDedicated\App\UI\Admin\Configuration\Fields\Vps\Language;
use ModulesGarden\Servers\OvhVpsAndDedicated\App\UI\Admin\Configuration\Fields\Vps\LicenseSelect;
use ModulesGarden\Servers\OvhVpsAndDedicated\App\UI\Admin\Configuration\Fields\Vps\Localizations;
use ModulesGarden\Servers\OvhVpsAndDedicated\App\UI\Admin\Configuration\Fields\Vps\Os;
use ModulesGarden\Servers\OvhVpsAndDedicated\App\UI\Admin\Configuration\Fields\Vps\Product;
use ModulesGarden\Servers\OvhVpsAndDedicated\App\UI\Admin\Configuration\Fields\Vps\Version;
use ModulesGarden\Servers\OvhVpsAndDedicated\App\UI\Admin\Configuration\Forms\Basics\BaseProductAppForm;
use ModulesGarden\Servers\OvhVpsAndDedicated\App\UI\Admin\Configuration\Helpers\Config;
use ModulesGarden\Servers\OvhVpsAndDedicated\App\UI\Admin\Configuration\Helpers\Fields;
use ModulesGarden\Servers\OvhVpsAndDedicated\Core\UI\Interfaces\AdminArea;
use ModulesGarden\Servers\OvhVpsAndDedicated\Core\UI\Interfaces\ClientArea;
use ModulesGarden\Servers\OvhVpsAndDedicated\Core\UI\Widget\Forms\Fields\Switcher;
use ModulesGarden\Servers\OvhVpsAndDedicated\Core\UI\Widget\Forms\Sections\HalfPageSection;

/**
 * Description of RebuildVM
 *
 * @author Artur Pilch <artur.pi@modulesgarden.com>
 */
class ConfigFields extends BaseProductAppForm implements ClientArea, AdminArea
{

    protected $id = 'ConfigFields';
    protected $name = 'ConfigFields';
    protected $title = 'ConfigFields';


    const LEFT_SECTION  = 'leftSection';
    const RIGHT_SECTION = 'rightSection';

    private $leftSection;
    /**
     * @var FieldsProvider
     */
    private $fieldsProvider;

    protected $config;

    public function __construct($baseId = null)
    {

        parent::__construct($baseId);
        $this->config = new Config($this->getRequestValue('id'), false);
    }

    public function getClass()
    {

    }

    public function initContent()
    {
        $this->fieldsProvider = new FieldsProvider($this->getRequestValue('id'));

        $this->addSection($this->leftSection());
        $this->addSection($this->rightSection());
    }

    private function leftSection()
    {
        $this->leftSection = new HalfPageSection(self::LEFT_SECTION);
        $this->leftSection->addField($this->getVpsProduct($this->fieldsProvider->getField('vpsProduct')));

        $this->leftSection->addField($this->getOs($this->fieldsProvider->getField('vpsOs')));
        //license
        $field = new LicenseSelect('packageconfigoption_license');
        $field->setDescription('description');
        $field->addReloadOnChangeField('packageconfigoption_vpsProduct');
        $this->leftSection->addField($field);
        return $this->leftSection;
    }


    private function rightSection()
    {
        $section = new HalfPageSection(self::RIGHT_SECTION);
        $section->addField($this->getLocalization($this->fieldsProvider->getField('vpsLocalizations')));
        $section->addField($this->getDuration($this->fieldsProvider->getField('vpsDuration')));

        $section = $this->addOptions($section);
        
        $section->addField($this->getPreventSystemReinstall($this->fieldsProvider->getField('vpsPreventSystemReinstall')));
        $section->addField($this->getClientAreaSnapshots($this->fieldsProvider->getField('vpsClientAreaSnapshots')));
        return $section;
    }

    private function getVpsCategory($value = null)
    {
        $field = new Category('packageconfigoption_vpsCategory');
        $field->setDescription('categoryDescription');

        return $field;
    }

    private function getVpsProduct($value = null)
    {
        $field = new Product('packageconfigoption_vpsProduct');
        $field
            ->setDescription('productDescription');

        return $field;
    }

    private function getOs($value = null)
    {
        $field = new Os('packageconfigoption_vpsOs');
        $field
          ->setDescription('osDescription');

        return $field;
    }

    private function getDistribution($value = null)
    {
        $field = new Distribution('packageconfigoption_vpsDistribution');
        $field
            ->setDescription('distributionDescription');

        return $field;
    }

    private function getDistributionVersion($value = null)
    {
        $field = new Version('packageconfigoption_vpsVersion');
        $field
            ->setDescription('versionDescription');

        return $field;
    }

    private function getDistributionLanguage($value = null)
    {
        $field = new Language('packageconfigoption_vpsLanguage');
        $field
            ->setDescription('languageDescription');

        return $field;
    }

    private function getLocalization($value = null)
    {
        $field = new Localizations('packageconfigoption_vpsLocalizations');
        $field->setDescription('localizationDescription');
        $field->addReloadOnChangeField('packageconfigoption_vpsProduct');
        return $field;
    }

    private function getDuration($value = null)
    {
        $field = new Duration('packageconfigoption_vpsDuration');
        $field->setDescription('durationDescription');

        return $field;
    }

    private function addOptions(HalfPageSection $section)
    {
        foreach (Fields::getOptionsFields() as $key => $name)
        {
            $field = $this->getCorrectField($name['fieldType'], $key);
            if ($field !== null)
            {
                $section->addField($field);
            }
        }

        return $section;
    }

    private function getPreventSystemReinstall($value = null)
    {
        $field = new Switcher("packageconfigoption_vpsPreventSystemReinstall");
        $field->setDescription("vpsPreventSystemReinstallDescription")
            ->setValue($value);
        return $field;
    }
    
    private function addSelectField($key)
    {
        return null;
        $classNamespace = "ModulesGarden\Servers\OvhVpsAndDedicated\App\UI\Admin\Configuration\Fields\Vps\Options\\";
        $class          = $classNamespace . ucfirst($key);

        if (!class_exists($class))
        {
            return null;
        }
        $key = "vps" . ucfirst($key);

        $field = new $class("packageconfigoption_{$key}");
        $field->setDescription("{$key}Description");
        $field->addClass('hidden');

        return $field;
    }

    private function addSwitcherField($key)
    {
        $id = "vps" . ucfirst($key);

        $field = new Switcher("packageconfigoption_{$id}");
        $field->setDescription("{$id}Description");
        $field->setValue($this->fieldsProvider->getField($id));
        if($key != 'snapshot')
        {
            $field->addClass('hidden');
        }

        return $field;
    }

    protected function getCorrectField($key, $name)
    {
        switch ($key)
        {
            case Fields::SELECT:
                return $this->addSelectField($name);

            case Fields::SWITCHER:
                return $this->addSwitcherField($name);
        }

        return null;
    }

    protected function getClientAreaSnapshots($value = null)
    {
        $field = new Switcher('packageconfigoption[vpsClientAreaSnapshots]');
        $field->setDescription('clientAreaSnapshotsDescription')
            ->setValue($value);
        return $field;
    }
}
