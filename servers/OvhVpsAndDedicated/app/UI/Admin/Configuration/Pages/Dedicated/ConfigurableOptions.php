<?php

namespace ModulesGarden\Servers\OvhVpsAndDedicated\App\UI\Admin\Configuration\Pages\Dedicated;

use ModulesGarden\Servers\OvhVpsAndDedicated\App\Service\ConfigurableOptions\ConfigurableOptions as ConfigurableOptionsService;
use ModulesGarden\Servers\OvhVpsAndDedicated\App\UI\Admin\Configuration\Buttons\Dedicated\CreateConfigurableOptionsBaseModalButton;
use ModulesGarden\Servers\OvhVpsAndDedicated\App\UI\Admin\Configuration\Helpers\ConfigurableOptionsBuilder;
use ModulesGarden\Servers\OvhVpsAndDedicated\Core\UI\Interfaces\AdminArea;
use ModulesGarden\Servers\OvhVpsAndDedicated\Core\UI\Interfaces\ClientArea;
use ModulesGarden\Servers\OvhVpsAndDedicated\App\UI\Admin\Configuration\Pages\ConfigurableOptions as VPSConfigurableOptions;

/**
 * Description of ProductPage
 *
 * @author Artur Pilch <artur.pi@modulesgarden.com>
 */
class ConfigurableOptions extends VPSConfigurableOptions implements ClientArea, AdminArea
{

    public function initContent()
    {
        $this->addButton(new CreateConfigurableOptionsBaseModalButton());
    }

    public function getOptions()
    {
        $configurableOptions = new ConfigurableOptionsService($this->getRequestValue('id'));
        ConfigurableOptionsBuilder::buildAllDedicated($configurableOptions);
        return $configurableOptions->showForPage();
    }

}
