<?php

namespace ModulesGarden\Servers\GoogleCloudVirtualMachines\App\UI\Admin\ProductConfig\Sections;

use ModulesGarden\Servers\GoogleCloudVirtualMachines\App\UI\Admin\ProductConfig\Fields\DiskTypeSelect;
use ModulesGarden\Servers\GoogleCloudVirtualMachines\App\UI\Admin\ProductConfig\Fields\ImageProjectSelect;
use ModulesGarden\Servers\GoogleCloudVirtualMachines\App\UI\Admin\ProductConfig\Fields\ImageSelect;
use ModulesGarden\Servers\GoogleCloudVirtualMachines\App\UI\Admin\ProductConfig\Fields\MachineTypeSelect;
use ModulesGarden\Servers\GoogleCloudVirtualMachines\App\UI\Admin\ProductConfig\Fields\NetworkSelect;
use ModulesGarden\Servers\GoogleCloudVirtualMachines\App\UI\Admin\ProductConfig\Fields\NetworkStaticTypeSelect;
use ModulesGarden\Servers\GoogleCloudVirtualMachines\App\UI\Admin\ProductConfig\Fields\NetworkTierSelect;
use ModulesGarden\Servers\GoogleCloudVirtualMachines\App\UI\Admin\ProductConfig\Fields\RegionSelect;
use ModulesGarden\Servers\GoogleCloudVirtualMachines\App\UI\Admin\ProductConfig\Fields\ZoneSelect;
use ModulesGarden\Servers\GoogleCloudVirtualMachines\Core\UI\Interfaces\AdminArea;
use ModulesGarden\Servers\GoogleCloudVirtualMachines\Core\UI\Widget\Forms\Fields\Select;
use ModulesGarden\Servers\GoogleCloudVirtualMachines\Core\UI\Widget\Forms\Fields\Switcher;
use ModulesGarden\Servers\GoogleCloudVirtualMachines\Core\UI\Widget\Forms\Fields\Text;
use ModulesGarden\Servers\GoogleCloudVirtualMachines\Core\UI\Widget\Forms\Fields\Textarea;
use ModulesGarden\Servers\GoogleCloudVirtualMachines\Core\UI\Widget\Forms\Sections\BoxSection;
use ModulesGarden\Servers\GoogleCloudVirtualMachines\Core\UI\Widget\Forms\Sections\HalfPageSection;
use ModulesGarden\Servers\GoogleCloudVirtualMachines\Core\UI\Widget\Forms\Sections\SectionLuRow;


class NetworkSection extends BoxSection implements AdminArea
{
    protected $id = 'networkSection';
    protected $name = 'networkSection';
    protected $title = 'networkSection';

    public function initContent()
    {
        //init rows
        $row = new SectionLuRow('igRow');
        $row->setMainContainer($this->mainContainer);
        $columRight = new HalfPageSection('hps');
        $columRight->setMainContainer($this->mainContainer);
        $row->addSection( $columRight);
        $this->addSection( $columRight);
        //network
        $field =  new NetworkSelect('network');
        $field->addGroupName('mgpci');
        $field->setDescription('description');
        $field->setDefaultValue('default');
        $this->addField($field );
        //networkTier
        $field =  new NetworkTierSelect('networkTier');
        $field->addGroupName('mgpci');
        $field->setDescription('description');
        $field->setDefaultValue('default');
        $columRight->addField($field );
        //ipv4
        $field =  new Switcher('ipv4');
        $field->addGroupName('mgpci');
        $field->setDescription('description');
        $field->setDefaultValue('default');
        $this->addField($field );
    }
}
