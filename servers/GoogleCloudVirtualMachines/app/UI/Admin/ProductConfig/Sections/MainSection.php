<?php

namespace ModulesGarden\Servers\GoogleCloudVirtualMachines\App\UI\Admin\ProductConfig\Sections;

use ModulesGarden\Servers\GoogleCloudVirtualMachines\App\UI\Admin\ProductConfig\Fields\DiskTypeSelect;
use ModulesGarden\Servers\GoogleCloudVirtualMachines\App\UI\Admin\ProductConfig\Fields\ImageProjectSelect;
use ModulesGarden\Servers\GoogleCloudVirtualMachines\App\UI\Admin\ProductConfig\Fields\ImageSelect;
use ModulesGarden\Servers\GoogleCloudVirtualMachines\App\UI\Admin\ProductConfig\Fields\MachineTypeSelect;
use ModulesGarden\Servers\GoogleCloudVirtualMachines\App\UI\Admin\ProductConfig\Fields\NetworkSelect;
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
use ModulesGarden\Servers\GoogleCloudVirtualMachines\App\UI\Admin\ProductConfig\Fields\UserDataSelect;


class MainSection extends BoxSection implements AdminArea
{
    protected $id = 'mainSection';
    protected $name = 'mainSection';
    protected $title = 'mainSection';

    public function initContent()
    {
        //init rows
        $row = new SectionLuRow('igRow');
        $row->setMainContainer($this->mainContainer);
        $columRight = new HalfPageSection('hps');
        $columRight->setMainContainer($this->mainContainer);
        $row->addSection( $columRight);
        $this->addSection( $columRight);
        //region
        $field =  new RegionSelect('region');
        $field->addGroupName('mgpci');
        $field->setDescription('description');
        $field->setDefaultValue('europe-west3-c');
        $this->addField($field );
        //zone
        $field =  new ZoneSelect('zone');
        $field->addGroupName('mgpci');
        $field->setDescription('description');
        $field->setDefaultValue('europe-west3-c');
        $field->addReloadOnChangeField('region');
        $columRight->addField($field );
        //hostnamePrefix
        $field =  new Text('hostnamePrefix');
        $field->addGroupName('mgpci');
        $field->setDescription('description');
        $this->addField($field );
        //machineType
        $field =  new MachineTypeSelect('machineType');
        $field->addGroupName('mgpci');
        $field->setDescription('description');
        $field->setDefaultValue('f1-micro');
        $field->addReloadOnChangeField('zone');
        $columRight->addField($field );
        //imageProject
        $field =  new ImageProjectSelect('imageProject');
        $field->addGroupName('mgpci');
        $field->setDescription('description');
        $this->addField($field );
        //image
        $field =  new ImageSelect('image');
        $field->addGroupName('mgpci');
        $field->setDescription('description');
        $field->addReloadOnChangeField('imageProject');
        $columRight->addField($field );
        //diskType
        $field =  new DiskTypeSelect('diskType');
        $field->addGroupName('mgpci');
        $field->setDescription('description');
        $field->setDefaultValue('pd-standard');
        $field->addReloadOnChangeField('zone');
        $this->addField($field );
        //diskSize
        $field =  new Text('diskSize');
        $field->addGroupName('mgpci');
        $field->setDescription('description');
        $field->setDefaultValue('10');
        $columRight->addField($field );
        //tags
        $field = new Text('tags');
        $field->addGroupName('mgpci');
        $field->setDescription('description');
        $this->addField($field);
        //userdata
        $field = new UserDataSelect('userData');
        $field->addGroupName('mgpci');
        $field->setDescription('description');
        $columRight->addField($field);
    }
}
