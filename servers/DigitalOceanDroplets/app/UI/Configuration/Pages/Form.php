<?php

namespace ModulesGarden\Servers\DigitalOceanDroplets\App\UI\Configuration\Pages;

use ModulesGarden\Servers\DigitalOceanDroplets\App\Models\TaskHistory;
use ModulesGarden\Servers\DigitalOceanDroplets\Core\UI\Builder\BaseContainer;
use ModulesGarden\Servers\DigitalOceanDroplets\Core\UI\Interfaces\AdminArea;
use ModulesGarden\Servers\DigitalOceanDroplets\Core\UI\Interfaces\ClientArea;
use ModulesGarden\Servers\DigitalOceanDroplets\Core\UI\Widget\DataTable\Column;
use ModulesGarden\Servers\DigitalOceanDroplets\Core\UI\Widget\DataTable\DataProviders\Providers\QueryDataProvider;

/**
 * Description of ProductPage
 *
 * @author Mateusz Pawłowski <mateusz.pa@modulesgarden.com>
 */
class Form extends BaseContainer implements ClientArea, AdminArea
{

    protected $id    = 'configurationForm';
    protected $name  = 'configurationForm';
    protected $title = 'configurationForm';

    public function initContent()
    {
        $this->setTitle(\ModulesGarden\Servers\DigitalOceanDroplets\Core\Helper\Lang::getInstance()->T('configurationForm'));
        $this->addElement(new \ModulesGarden\Servers\DigitalOceanDroplets\App\UI\Configuration\Forms\ConfigFields());
    }

}
