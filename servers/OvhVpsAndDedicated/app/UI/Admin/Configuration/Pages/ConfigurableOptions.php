<?php

namespace ModulesGarden\Servers\OvhVpsAndDedicated\App\UI\Admin\Configuration\Pages;

use ModulesGarden\Servers\OvhVpsAndDedicated\App\Service\ConfigurableOptions\ConfigurableOptions as ConfigurableOptionsService;
use ModulesGarden\Servers\OvhVpsAndDedicated\App\UI\Admin\Configuration\Buttons\CreateConfigurableOptionsBaseModalButton;
use ModulesGarden\Servers\OvhVpsAndDedicated\App\UI\Admin\Configuration\Helpers\ConfigurableOptionsBuilder;
use ModulesGarden\Servers\OvhVpsAndDedicated\App\UI\Admin\Configuration\Pages\Basics\BaseConfigurationPage;
use ModulesGarden\Servers\OvhVpsAndDedicated\Core\UI\Interfaces\AdminArea;
use ModulesGarden\Servers\OvhVpsAndDedicated\Core\UI\Interfaces\ClientArea;

/**
 * Description of ProductPage
 *
 * @author Artur Pilch <artur.pi@modulesgarden.com>
 */
class ConfigurableOptions extends BaseConfigurationPage implements ClientArea, AdminArea
{
    protected $id    = 'configurableOptions';
    protected $name  = 'configurableOptions';
    protected $title = 'configurableOptions';

    public function initContent()
    {
        $this->addButton(new CreateConfigurableOptionsBaseModalButton());
    }

    /**
     * Get Options to generate it on template
     *
     * @return array
     */
    public function getOptions()
    {
        $configurableOptions = new ConfigurableOptionsService($this->getRequestValue('id'));
        ConfigurableOptionsBuilder::buildAll($configurableOptions, false);

        return $configurableOptions->showForPage();
    }

}
