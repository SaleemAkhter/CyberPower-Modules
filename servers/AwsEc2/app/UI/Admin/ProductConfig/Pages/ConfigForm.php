<?php

namespace ModulesGarden\Servers\AwsEc2\App\UI\Admin\ProductConfig\Pages;

use ModulesGarden\Servers\AwsEc2\App\UI\Admin\ProductConfig\Providers\Config;
use ModulesGarden\Servers\AwsEc2\App\UI\Admin\ProductConfig\Sections\CaConfig;
use ModulesGarden\Servers\AwsEc2\App\UI\Admin\ProductConfig\Sections\First;
use ModulesGarden\Servers\AwsEc2\Core\UI\Interfaces\AdminArea;
use ModulesGarden\Servers\AwsEc2\Core\UI\Widget\Forms\FormIntegration;

class ConfigForm extends FormIntegration implements AdminArea
{
    protected $id = 'configForm';
    protected $name = 'configForm';
    protected $title = 'configFormTitle';

    public function initContent()
    {
        $provider = new Config();
        $this->setProvider($provider);

        $region = new First();
        $this->addSection($region);

        $caConfig = new CaConfig();
        $this->addSection($caConfig);

        $this->loadDataToForm();
    }
}
