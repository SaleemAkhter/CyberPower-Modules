<?php

namespace ModulesGarden\Servers\HetznerVps\App\UI\Configuration\Pages;

use ModulesGarden\Servers\HetznerVps\App\Models\TaskHistory;
use ModulesGarden\Servers\HetznerVps\Core\UI\Builder\BaseContainer;
use ModulesGarden\Servers\HetznerVps\Core\UI\Interfaces\AdminArea;
use ModulesGarden\Servers\HetznerVps\Core\UI\Interfaces\ClientArea;
use ModulesGarden\Servers\HetznerVps\Core\UI\Widget\DataTable\Column;
use ModulesGarden\Servers\HetznerVps\Core\UI\Widget\DataTable\DataProviders\Providers\QueryDataProvider;

/**
 * Description of Product
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
        $this->setTitle(\ModulesGarden\Servers\HetznerVps\Core\Helper\Lang::getInstance()->T('configurationForm'));
        $this->addElement(new \ModulesGarden\Servers\HetznerVps\App\UI\Configuration\Forms\ConfigFields());
    }

}
