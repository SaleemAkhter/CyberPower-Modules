<?php

namespace ModulesGarden\Servers\DigitalOceanDroplets\App\UI\Configuration\Forms;

use ModulesGarden\Servers\DigitalOceanDroplets\App\Helpers\FieldsProvider;
use ModulesGarden\Servers\DigitalOceanDroplets\App\Helpers\UserData;
use ModulesGarden\Servers\DigitalOceanDroplets\App\UI\Configuration\Helpers\Config;
use ModulesGarden\Servers\DigitalOceanDroplets\Core\UI\Interfaces\AdminArea;
use ModulesGarden\Servers\DigitalOceanDroplets\Core\UI\Interfaces\ClientArea;
use ModulesGarden\Servers\DigitalOceanDroplets\Core\UI\Widget\Forms\BaseForm;
use ModulesGarden\Servers\DigitalOceanDroplets\Core\UI\Widget\Forms\Fields\Select;
use ModulesGarden\Servers\DigitalOceanDroplets\Core\UI\Widget\Forms\Fields\Switcher;
use ModulesGarden\Servers\DigitalOceanDroplets\Core\UI\Widget\Forms\Fields\Text;
use ModulesGarden\Servers\DigitalOceanDroplets\Core\UI\Widget\Forms\Sections\HalfPageSection;

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


        $this->addSection($this->leftSecion($config));
        $this->addSection($this->rightSection($config));
        $this->loadDataToForm();
    }

    private function leftSecion(FieldsProvider $config)
    {
        $section = new HalfPageSection('leftSection');
        $section->addField($this->getProjectField($config->getField('project')));
        $section->addField($this->getRegionField($config->getField('region')));
        $section->addField($this->getSizesField($config->getField('size')));
        $section->addField($this->getImageField($config->getField('image')));
        $section->addField($this->getVolumeField($config->getField('volume')));
        $section->addField($this->getUserDataField($config->getField('userData')));
        $section->addField($this->getRandomDomainPrefixField($config->getField('randomDomainPrefix')));
        $section->addField($this->getSshKeyField($config->getField('sshkey')));
        $section->addField($this->getFloatingIpEnaledField($config->getField('floatingIpEnabled')));
        $section->addField($this->getBackupsField($config->getField('backup')));
        $section->addField($this->getMonitoringField($config->getField('monitoring')));




        return $section;
    }

    private function rightSection(FieldsProvider $config)
    {
        $section = new HalfPageSection('rightSection');
        $section->addField($this->getSnapshotLimitField($config->getField('snapshotLimit')));
        $section->addField($this->getFirewallLimitField($config->getField('firewallLimit')));

        $section->addField($this->getInboundRulesLimit($config->getField('inboundRulesLimit')));
        $section->addField($this->getOutboundRulesLimit($config->getField('outboundRulesLimit')));
        $section->addField($this->getTotalRulesLimit($config->getField('totalRulesLimit')));

        $section->addField($this->getTagsField($config->getField('tags')));
        $section->addField($this->getFirewallPrefix($config->getField('firewallPrefix')));

        $section->addField($this->getIPv6Field($config->getField('ipv6')));
        $section->addField($this->getPrivateNetworkField($config->getField('privateNetwork')));
        $section->addField($this->getDebugModeField($config->getField('debugMode')));



        return $section;
    }
    
    private function getSshKeyField($value = null){
        $field = new Select('packageconfigoption[sshkey]');
        $field->setAvalibleValues(Config::getSshKeys());
        $field->setDescription('sshkeyDescription');
        $field->setValue($value);
        $field->setSelectedValue($value);
        return $field;
    }

    private function getProjectField($value = null)
    {
        $field = new Select('packageconfigoption[project]');
        $field->setAvalibleValues(Config::getProjectList());
        $field->setDescription('regionDescription');
        $field->setValue($value);
        $field->setSelectedValue($value);
        return $field;
    }

    private function getRegionField($value = null)
    {
        $field = new Select('packageconfigoption[region]');
        $field->setAvalibleValues(Config::getRegionsAndSiezes()['regions']);
        $field->setDescription('regionDescription');
        $field->setValue($value);
        $field->setSelectedValue($value);
        return $field;
    }

    private function getImageField($value = null)
    {

        $field = new Select('packageconfigoption[image]');
        $field->setAvalibleValues(Config::getImagesList());
         $field->setDescription('imageDescription');
        $field->setValue($value);
        $field->setSelectedValue($value);
        return $field;
    }

    private function getSizesField($value = null)
    {
        $field = new Select('packageconfigoption[size]');
        $field->setAvalibleValues(Config::getRegionsAndSiezes()['size']);
        $field->setDescription('sizeDescription');
        $field->setValue($value);
        $field->setSelectedValue($value);
        return $field;
    }

    private function getBackupsField($value = null)
    {
        $field = new Switcher('packageconfigoption[backup]');
        $field->setDescription('backupDescription');
        $field->setValue($value);
        return $field;
    }

    private function getIPv6Field($value = null)
    {
        $field = new Switcher('packageconfigoption[ipv6]');
        $field->setDescription('ipv6Description');
        $field->setValue($value);
        return $field;
    }

    private function getVolumeField($value = null)
    {
        $field = new Text('packageconfigoption[volume]');
        $field->setDescription('volumeDescription');
        $field->setValue($value);
        return $field;
    }

    private function getTagsField($value = null)
    {
        $field = new Text('packageconfigoption[tags]');
        $field->setDescription('tagsDescription');
        $field->setValue($value);
        return $field;
    }

    private function getRandomDomainPrefixField($value = null)
    {
        $field = new Text('packageconfigoption[randomDomainPrefix]');
        $field->setDescription('randomDomainPrefixDescription');
        $field->setValue($value);
        return $field;
    }

    private function getUserDataField($value = null)
    {
        $field = new Select('packageconfigoption[userData]');
        $field->setAvalibleValues(UserData::getFilesNames());
        $field->setDescription('userDataDescription');
        $field->setValue($value);
        $field->setSelectedValue($value);
        return $field;
    }

    private function getMonitoringField($value = null)
    {
        $field = new Switcher('packageconfigoption[monitoring]');
        $field->setDescription('monitoirngDescription');
        $field->setValue($value);
        return $field;
    }

    private function getPrivateNetworkField($value = null)
    {
        $field = new Switcher('packageconfigoption[privateNetwork]');
        $field->setDescription('privateNetworkDescription');
        $field->setValue($value);
        return $field;
    }

    private function getSnapshotLimitField($value = null)
    {
        $field = new Text('packageconfigoption[snapshotLimit]');
        $field->setValue($value);
        return $field;
    }
    private function getFirewallLimitField($value = null)
    {
        $field = new Text('packageconfigoption[firewallLimit]');
        $field->setValue($value);
        return $field;
    }

    private function getDebugModeField($value = null)
    {
        $field = new Switcher('packageconfigoption[debugMode]');
        $field->setDescription('debugModeDescription');
        $field->setValue($value);
        return $field;
    }

    private function getFloatingIpEnaledField( $value = null )
    {
        $field = new Switcher('packageconfigoption[floatingIpEnabled]');
        $field->setDescription('floatingIpEnabledDescription');
        $field->setValue($value);
        return $field;
    }

    private function getInboundRulesLimit( $value = null )
    {
        $field = new Text('packageconfigoption[inboundRulesLimit]');
        $field->setDescription('inboundRulesLimitDescription');
        $field->setValue($value);
        return $field;
    }

    private function getOutboundRulesLimit( $value  = null )
    {
        $field = new Text('packageconfigoption[outboundRulesLimit]');
        $field->setDescription('outboundRulesLimitDescription');
        $field->setValue($value);
        return $field;
    }

    private function getTotalRulesLimit( $value = null )
    {
        $field = new Text('packageconfigoption[totalRulesLimit]');
        $field->setDescription('totalRulesLimitDescription');
        $field->setValue($value);
        return $field;
    }

    private function getFirewallPrefix( $value = null)
    {
        $field = new Text('packageconfigoption[firewallPrefix]');
        $field->setDescription('firewallPrefixDescription');
        $field->setValue($value);
        return $field;
    }
}
