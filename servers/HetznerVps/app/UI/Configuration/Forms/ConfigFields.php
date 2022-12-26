<?php

namespace ModulesGarden\Servers\HetznerVps\App\UI\Configuration\Forms;

use ModulesGarden\Servers\HetznerVps\App\Helpers\FieldsProvider;
use ModulesGarden\Servers\HetznerVps\App\Helpers\UserData;
use ModulesGarden\Servers\HetznerVps\App\UI\Configuration\Helpers\Config;
use ModulesGarden\Servers\HetznerVps\Core\UI\Interfaces\AdminArea;
use ModulesGarden\Servers\HetznerVps\Core\UI\Interfaces\ClientArea;
use ModulesGarden\Servers\HetznerVps\Core\UI\Widget\Forms\BaseForm;
use ModulesGarden\Servers\HetznerVps\Core\UI\Widget\Forms\Fields\Number;
use ModulesGarden\Servers\HetznerVps\Core\UI\Widget\Forms\Fields\Select;
use ModulesGarden\Servers\HetznerVps\Core\UI\Widget\Forms\Fields\Switcher;
use ModulesGarden\Servers\HetznerVps\Core\UI\Widget\Forms\Fields\Text;
use ModulesGarden\Servers\HetznerVps\Core\UI\Widget\Forms\Sections\HalfPageSection;

/**
 * Description of RebuildVM
 *
 * @author Mateusz Pawłowski <mateusz.pa@modulesgarden.com>
 */
class ConfigFields extends BaseForm implements ClientArea, AdminArea
{

    protected $id    = 'ConfigFields';
    protected $name  = 'ConfigFields';
    protected $title = 'ConfigFields';

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
        $section->addField($this->getLocationField($config->getField('location')));
        $section->addField($this->getDataCenterField($config->getField('datacenter')));
        $section->addField($this->getImageField($config->getField('image')));
        $section->addField($this->getTypeField($config->getField('type')));
        $section->addField($this->getFloatingIpsNumberField($config->getField('floatingIpsNumber')));
        $section->addField($this->getFirewallsLimitField($config->getField('firewallsLimitNumber')));
        $section->addField($this->getFirewallTotalRulesLimitNumberField($config->getField('firewallsTotalRulesLimitNumber')));



        return $section;
    }

    private function rightSection(FieldsProvider $config)
    {
        $section = new HalfPageSection('rightSection');
        $section->addField($this->getVolumeField($config->getField('volume')));
        $section->addField($this->getSnapshotsField($config->getField('snapshots')));
        $section->addField($this->getUserDataField($config->getField('userData')));
        $section->addField($this->getRandomDomainPrefixField($config->getField('randomDomainPrefix')));
        $section->addField($this->getBackupsEnabledField($config->getField('enableBackups')));
        $section->addField($this->getFirewallInboundNumberField($config->getField('firewallsInboundRulesNumber')));
        $section->addField($this->getFirewallOutboundNumberField($config->getField('firewallsOutboundRulesNumber')));

        return $section;
    }

    private function getLocationField($value = null)
    {
        $field = new Select('packageconfigoption[location]');
        $field->setAvailableValues(Config::getLocations());
        $field->setDescription('locationDescription');
        $field->setValue($value);
        $field->setSelectedValue($value);
        return $field;
    }
    private function getDataCenterField($value = null)
    {
        $field = new Select('packageconfigoption[datacenter]');
        $field->setAvailableValues(Config::getDatacenter());
        $field->setDescription('datacenterDescription');
        $field->setValue($value);
        $field->setSelectedValue($value);
        return $field;
    }

    private function getImageField($value = null)
    {
        $field = new Select('packageconfigoption[image]');
        $field->setAvailableValues(Config::getImagesWithoutBackups());
        $field->setDescription('imageDescription');
        $field->setValue($value);
        $field->setSelectedValue($value);
        return $field;
    }

    private function getTypeField($value = null)
    {
        $field = new Select('packageconfigoption[type]');
        $field->setAvailableValues(Config::getTypes());
        $field->setDescription('typeDescription');
        $field->setValue($value);
        $field->setSelectedValue($value);
        return $field;
    }

    private function getVolumeField($value = null)
    {
        $field = new Text('packageconfigoption[volume]');
        $field->setDescription('volumeDescription');
        $field->setValue($value);
        return $field;
    }

    private function getSnapshotsField($value = null)
    {
        $field = new Number();
        $field->setName('packageconfigoption[snapshots]');
        $field->setTitle('packageconfigoption[snapshots]');
        $field->setDescription('snapshotsDescription');
        $field->setValue($value);
        return $field;
    }

    private function getUserDataField($value = null)
    {
        $field = new Select('packageconfigoption[userdata]');
        $field->setAvailableValues(UserData::getFilesNames());
        $field->setDescription('userdatadescription');
        $field->setValue($value);
        $field->setSelectedValue($value);
        return $field;
    }

    private function getRandomDomainPrefixField($value = null)
    {
        $field = new Text('packageconfigoption[randomDomainPrefix]');
        $field->setDescription('randomDomainPrefixDescription');
        $field->setValue($value);
        return $field;
    }

    private function getFloatingIpsNumberField($value = null)
    {
        $field = new Number();
        $field->setName('packageconfigoption[floatingIpsNumber]');
        $field->setTitle('packageconfigoption[floatingIpsNumber]');
        $field->setDescription('floatingIpsNumberDescription');
        $field->setValue($value);
        return $field;
    }

    private function getBackupsEnabledField($value = null)
    {
        $field = new Switcher();
        $field->setName('packageconfigoption[enableBackups]');
        $field->setTitle('packageconfigoption[enableBackups]');
        $field->setDescription('enableBackupsDescription');
        $field->setValue($value);

        return $field;
    }

    private function getFirewallsLimitField($value = null)
    {
        $field = new Number();
        $field->setName('packageconfigoption[firewallsLimitNumber]');
        $field->setTitle('packageconfigoption[firewallsLimitNumber]');
        $field->setDescription('firewallsLimitNumberDescription');
        $field->setValue($value);

        return $field;
    }

    private function getFirewallInboundNumberField($value = null)
    {
        $field = new Number();
        $field->setName('packageconfigoption[firewallInboundRulesNumber]');
        $field->setTitle('packageconfigoption[firewallInboundRulesNumber]');
        $field->setDescription('firewallInboundRulesNumberDescription');
        $field->setValue($value);

        return $field;
    }

    private function getFirewallOutboundNumberField($value = null)
    {
        $field = new Number();
        $field->setName('packageconfigoption[firewallOutboundRulesNumber]');
        $field->setTitle('packageconfigoption[firewallOutboundRulesNumber]');
        $field->setDescription('firewallOutboundRulesNumberDescription');
        $field->setValue($value);

        return $field;
    }

    private function getFirewallTotalRulesLimitNumberField($value = null)
    {
        $field = new Number();
        $field->setName('packageconfigoption[firewallTotalRulesLimitNumber]');
        $field->setTitle('packageconfigoption[firewallTotalRulesLimitNumber]');
        $field->setDescription('firewallTotalRulesLimitNumberDescription');
        $field->setValue($value);

        return $field;
    }


}
