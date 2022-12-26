<?php

namespace ModulesGarden\Servers\GoogleCloudVirtualMachines\App\UI\Admin\ProductConfig\Pages;

use ModulesGarden\Servers\GoogleCloudVirtualMachines\App\UI\Admin\ProductConfig\Providers\ConfigProvider;
use ModulesGarden\Servers\GoogleCloudVirtualMachines\App\UI\Admin\ProductConfig\Sections\CustomMachineSection;
use ModulesGarden\Servers\GoogleCloudVirtualMachines\App\UI\Admin\ProductConfig\Sections\MainSection;
use ModulesGarden\Servers\GoogleCloudVirtualMachines\App\UI\Admin\ProductConfig\Sections\NetworkSection;
use ModulesGarden\Servers\GoogleCloudVirtualMachines\Core\UI\Interfaces\AdminArea;
use ModulesGarden\Servers\GoogleCloudVirtualMachines\Core\UI\Widget\Forms\FormIntegration;
use \ModulesGarden\Servers\GoogleCloudVirtualMachines\App\UI\Admin\ProductConfig\Sections\ClientAreaFeaturesSection;

class ConfigForm extends FormIntegration implements AdminArea
{
    protected $id = 'configForm';
    protected $name = 'configForm';
    protected $title = 'configFormTitle';

    public function initContent()
    {
        $provider = new ConfigProvider();
        $this->setProvider($provider);
        $this->addSection(new MainSection());
        $this->addSection(new CustomMachineSection());
        $this->addSection(new ClientAreaFeaturesSection());
        $this->addSection( new NetworkSection());
        $this->loadDataToForm();
    }
}
