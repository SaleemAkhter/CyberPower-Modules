<?php


namespace ModulesGarden\Servers\GoogleCloudVirtualMachines\App\UI\Admin\ProductConfig\Sections;


use ModulesGarden\Servers\GoogleCloudVirtualMachines\App\UI\Admin\ProductConfig\Fields\CustomMachineTypeSelect;
use ModulesGarden\Servers\GoogleCloudVirtualMachines\App\UI\Admin\ProductConfig\Fields\ImageSelect;
use ModulesGarden\Servers\GoogleCloudVirtualMachines\Core\UI\Interfaces\AdminArea;
use ModulesGarden\Servers\GoogleCloudVirtualMachines\Core\UI\Widget\Forms\Fields\Text;
use ModulesGarden\Servers\GoogleCloudVirtualMachines\Core\UI\Widget\Forms\Sections\BoxSection;
use ModulesGarden\Servers\GoogleCloudVirtualMachines\Core\UI\Widget\Forms\Sections\HalfPageSection;
use ModulesGarden\Servers\GoogleCloudVirtualMachines\Core\UI\Widget\Forms\Sections\SectionLuRow;

class CustomMachineSection extends BoxSection implements AdminArea
{
    protected $id = 'customMachineSection';
    protected $name = 'customMachineSection';
    protected $title = 'customMachineSection';

    public function initContent()
    {
        //init rows
        $row = new SectionLuRow('igRow');
        $row->setMainContainer($this->mainContainer);
        $columnRight = new HalfPageSection('hps');
        $columnRight->setMainContainer($this->mainContainer);
        $row->addSection($columnRight);
        $this->addSection($columnRight);

        $field =  new CustomMachineTypeSelect('customMachineType');
        $field->addGroupName('mgpci');
        $field->setDescription('description');
        $field->setDefaultValue('');
        $this->addField($field );

        $field = new Text('customMachineCpu');
        $field->addGroupName('mgpci');
        $field->setDescription('description');
        $columnRight->addField($field);

        $field = new Text('customMachineRam');
        $field->addGroupName('mgpci');
        $field->setDescription('description');
        $this->addField($field);
        
    }
}