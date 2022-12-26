<?php

namespace ModulesGarden\Servers\DigitalOceanDroplets\App\UI\Configuration\Pages;

use ModulesGarden\Servers\DigitalOceanDroplets\App\Service\ConfigurableOptions\ConfigurableOptions as ConfigurableOptionsService;
use ModulesGarden\Servers\DigitalOceanDroplets\App\UI\Configuration\Buttons\CreateConfigurableOptionsBaseModalButton;
use ModulesGarden\Servers\DigitalOceanDroplets\App\UI\Configuration\Helpers\ConfigurableOptionsBuilder;
use ModulesGarden\Servers\DigitalOceanDroplets\Core\Helper\Lang;
use ModulesGarden\Servers\DigitalOceanDroplets\Core\UI\Builder\BaseContainer;
use ModulesGarden\Servers\DigitalOceanDroplets\Core\UI\Interfaces\AdminArea;
use ModulesGarden\Servers\DigitalOceanDroplets\Core\UI\Interfaces\ClientArea;

/**
 * Description of ProductPage
 *
 * @author Mateusz Pawłowski <mateusz.pa@modulesgarden.com>
 */
class ConfigurableOptions extends BaseContainer implements ClientArea, AdminArea
{

    protected $id    = 'configurableOptions';
    protected $name  = 'configurableOptions';
    protected $title = 'configurableOptions';

    public function initContent()
    {
        $this->setTitle(Lang::getInstance()->T('configurableOptions'));

        $this->addButton(new CreateConfigurableOptionsBaseModalButton());
    }

    public function getOptions()
    {
        $configurableOptions = new ConfigurableOptionsService($_REQUEST['id']);
        ConfigurableOptionsBuilder::buildAll($configurableOptions);
        return $configurableOptions->show();
    }

}
