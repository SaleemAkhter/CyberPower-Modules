<?php


namespace ModulesGarden\Servers\GoogleCloudVirtualMachines\App\UI\Admin\ProductConfig\Sections;

use \ModulesGarden\Servers\GoogleCloudVirtualMachines\Core\UI\Widget\Forms\Sections\BoxSection;
use \ModulesGarden\Servers\GoogleCloudVirtualMachines\Core\UI\Interfaces\AdminArea;
use \ModulesGarden\Servers\GoogleCloudVirtualMachines\Core\UI\Widget\Forms\Sections\SectionLuRow;
use \ModulesGarden\Servers\GoogleCloudVirtualMachines\Core\UI\Widget\Forms\Sections\HalfPageSection;
use \ModulesGarden\Servers\GoogleCloudVirtualMachines\Core\UI\Widget\Forms\Fields\Switcher;
use \ModulesGarden\Servers\GoogleCloudVirtualMachines\App\UI\Admin\ProductConfig\Providers\ConfigProvider;

class ClientAreaFeaturesSection extends BoxSection implements AdminArea{
    
    
    protected $id = 'clientAreaFeaturesSection';
    protected $name = 'clientAreaFeaturesSection';
    protected $title = 'clientAreaFeaturesSection';
    
    public function initContent() {
        //init rows
        $row = new SectionLuRow('igRow');
        $row->setMainContainer($this->mainContainer);
        $columRight = new HalfPageSection('hps');
        $columRight->setMainContainer($this->mainContainer);
        $row->addSection( $columRight);
        $this->addSection( $columRight);



        $field =  new Switcher('snapshots');
        $field->addGroupName('mgpci');
        $field->setDescription('description');
        $provider = new ConfigProvider();
        $field->setDefaultValue($provider->getSnapshotsEnabledValue());               
        $this->addField($field);

        $field =  new Switcher('graphs');
        $field->addGroupName('mgpci');
        $field->setDescription('description');
        $provider = new ConfigProvider();
        $field->setDefaultValue($provider->getGraphsEnabledValue());
        $columRight->addField($field);
    }
    
}
