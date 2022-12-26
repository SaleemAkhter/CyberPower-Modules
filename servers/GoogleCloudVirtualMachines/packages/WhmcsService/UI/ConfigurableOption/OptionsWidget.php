<?php

namespace ModulesGarden\Servers\GoogleCloudVirtualMachines\Packages\WhmcsService\UI\ConfigurableOption;

use ModulesGarden\Servers\GoogleCloudVirtualMachines\Core\UI\Interfaces\AdminArea;
use ModulesGarden\Servers\GoogleCloudVirtualMachines\Core\UI\Widget\Others\ConfigurableOptionsWidget;
use ModulesGarden\Servers\GoogleCloudVirtualMachines\Packages\WhmcsService\Traits\ConfigurableOptionsConfig;
use ModulesGarden\Servers\GoogleCloudVirtualMachines\Packages\WhmcsService\UI\ConfigurableOption\Buttons\AddOptions;

class OptionsWidget extends ConfigurableOptionsWidget implements AdminArea
{
    use ConfigurableOptionsConfig;

    protected $id = 'optionsWidget';
    protected $name = 'optionsWidget';
    protected $title = 'optionsWidgetTitle';

    public function initContent()
    {
        $this->loadConfigurableOptionsList();

        $this->customTplVars['options'] = $this->configOptionsList;

        $this->addButton(AddOptions::class);
    }
}
