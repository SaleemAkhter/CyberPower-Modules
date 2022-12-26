<?php

namespace ModulesGarden\Servers\HetznerVps\App\UI\Configuration\Forms;

use ModulesGarden\Servers\HetznerVps\App\Helpers\FieldsProvider;

use ModulesGarden\Servers\HetznerVps\App\UI\Configuration\Fields\CustomCheckBox;
use ModulesGarden\Servers\HetznerVps\App\UI\Configuration\Fields\CustomSelect;
use ModulesGarden\Servers\HetznerVps\App\UI\Configuration\Helpers\Config;
use ModulesGarden\Servers\HetznerVps\Core\UI\Interfaces\AdminArea;
use ModulesGarden\Servers\HetznerVps\Core\UI\Interfaces\ClientArea;
use ModulesGarden\Servers\HetznerVps\Core\UI\Widget\Forms\BaseForm;
use ModulesGarden\Servers\HetznerVps\Core\UI\Widget\Forms\Fields\Select2vue;
use ModulesGarden\Servers\HetznerVps\Core\UI\Widget\Forms\Fields\Select2vueByValueOnly;
use ModulesGarden\Servers\HetznerVps\Core\UI\Widget\Forms\Fields\Switcher;
use ModulesGarden\Servers\HetznerVps\Core\UI\Widget\Forms\Sections\HalfPageSection;

/**
 * Description of RebuildVM
 *
 * @author Mateusz Pawłowski <mateusz.pa@modulesgarden.com>
 */
class ClientAreaFeatures extends BaseForm implements ClientArea, AdminArea
{

    protected $id    = 'ClientAreaFeatures';
    protected $name  = 'ClientAreaFeatures';
    protected $title = 'ClientAreaFeatures';

    public function getClass()
    {
        
    }

    public function initContent()
    {
        $config = new FieldsProvider($_REQUEST['id']);


        $this->addSection($this->leftSection($config));
        $this->addSection($this->rightSection($config));

        $this->loadDataToForm();
    }

    private function leftSection(FieldsProvider $config)
    {
        $section = new HalfPageSection('leftSection');
        $section->addField($this->getClientAreaRebuild($config->getField('clientAreaRebuild')))
                ->addField($this->getClientAreaConsole($config->getField('clientAreaConsole')))
                ->addField($this->getClientAreaReverseDNS($config->getField('clientAreaReverseDNS')))
                ->addField($this->getClientAreaFloatingIPs($config->getField('clientAreaFloatingIPs')))
                ->addField($this->getClientAreaBackups($config->getField('clientAreaBackups')))
                ->addField($this->getClientAreaGraphs($config->getField('clientAreaGraphs')))
        ;
        return $section;
    }

    private function rightSection(FieldsProvider $config)
    {
        $section = new HalfPageSection('rightSection');
        $section->addField($this->getClientAreaSnapshots($config->getField('clientAreaSnapshots')));
        $section->addField($this->getClientAreaIsos($config->getField('clientAreaIsos')));
        $section->addField($this->getClientAreaFirewalls($config->getField('clientAreaFirewalls')));
        $section->addField($this->getClientAreaAvailableImages($config->getField('clientAreaAvailableImages')));
        $section->addField($this->getClientAreaAvailableIsos($config->getField('clientAreaAvailableIsos')));
        return $section;
    }

    private function getClientAreaRebuild($value = null)
    {
        $field = new Switcher('packageconfigoption[clientAreaRebuild]');
        $field->setDescription('clientAreaRebuildDescription')
                ->setValue($value);
        return $field;
    }

    private function getClientAreaIsos($value = null)
    {
        $field = new Switcher('packageconfigoption[clientAreaIsos]');
        $field->setDescription('clientAreaIsosDescription')
            ->setValue($value);
        return $field;
    }

    private function getClientAreaSnapshots($value = null)
    {
        $field = new Switcher('packageconfigoption[clientAreaSnapshots]');
        $field->setDescription('clientAreaSnapshotsDescription')
            ->setValue($value);
        return $field;
    }

    private function getClientAreaConsole($value = null)
    {
        $field = new Switcher('packageconfigoption[clientAreaConsole]');
        $field->setDescription('clientAreaConsoleDescription')
            ->setValue($value);
        return $field;
    }

    private function getClientAreaReverseDNS($value = null)
    {
        $field = new Switcher('packageconfigoption[clientAreaReverseDNS]');
        $field->setDescription('clientAreaReverseDNSDescription')
            ->setValue($value);
        return $field;
    }

    private function getClientAreaAvailableImages($value = null)
    {
        $field = new CustomSelect('packageconfigoption[clientAreaAvailableImages][]');
        $field->setDescription('clientAreaAvailableImagesDescription')
            ->setAvailableValues(Config::getImagesWithoutBackups())
            ->setValue($value === '[]' ? null : json_decode($value))
            ->enableMultiple();

        return $field;
    }

    private function getClientAreaAvailableIsos($value = null)
    {
        $field = new CustomSelect('packageconfigoption[clientAreaAvailableIsos][]');
        $field->setDescription('clientAreaAvailableIsosDescription')
            ->setAvailableValues(Config::getIsos())
            ->setValue($value === '[]' ? null : json_decode($value))
            ->enableMultiple();

        return $field;
    }

    private function getClientAreaFloatingIPs($value = null)
    {
        $field = new Switcher('packageconfigoption[clientAreaFloatingIPs]');
        $field->setDescription('clientAreaFloatingIPsDescription')
            ->setValue($value);
        return $field;
    }

    private function getClientAreaBackups($value = null)
    {
        $field = new Switcher('packageconfigoption[clientAreaBackups]');
        $field->setDescription('clientAreaBackupsDescription')
            ->setValue($value);
        return $field;
    }

    private function getClientAreaGraphs($value = null)
    {
        $field = new Switcher('packageconfigoption[clientAreaGraphs]');
        $field->setDescription('clientAreaGraphsDescription')
            ->setValue($value);
        return $field;
    }

    private function getClientAreaFirewalls($value = null)
    {
        $field = new Switcher('packageconfigoption[clientAreaFirewalls]');
        $field->setDescription('clientAreaFirewallsDescription')
            ->setValue($value);
        return $field;
    }


}
