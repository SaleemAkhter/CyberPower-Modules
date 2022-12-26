<?php

namespace ModulesGarden\Servers\VultrVps\App\UI\Admin\ProductConfig\Pages;

use ModulesGarden\Servers\VultrVps\App\UI\Admin\ProductConfig\Providers\ConfigProvider;
use ModulesGarden\Servers\VultrVps\App\UI\Admin\ProductConfig\Sections\ClientAreaSection;
use ModulesGarden\Servers\VultrVps\App\UI\Admin\ProductConfig\Sections\MainSection;
use ModulesGarden\Servers\VultrVps\Core\UI\Interfaces\AdminArea;
use ModulesGarden\Servers\VultrVps\Core\UI\Widget\Forms\FormIntegration;

class ConfigForm extends FormIntegration implements AdminArea
{
    protected $id = 'configForm';
    protected $name = 'configForm';
    protected $title = 'configFormTitle';

    public function initContent()
    {
        $provider = new ConfigProvider();
        $this->setProvider($provider);

        $mainSection = new MainSection();
        $this->addSection($mainSection);
        $this->addSection(new ClientAreaSection());

        $this->loadDataToForm();
    }
}
