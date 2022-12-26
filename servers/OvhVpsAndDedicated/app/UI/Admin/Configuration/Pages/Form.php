<?php

namespace ModulesGarden\Servers\OvhVpsAndDedicated\App\UI\Admin\Configuration\Pages;

use ModulesGarden\Servers\OvhVpsAndDedicated\App\Models\TaskHistory;
use ModulesGarden\Servers\OvhVpsAndDedicated\App\UI\Admin\Configuration\Pages\Basics\BaseConfigurationPage;
use ModulesGarden\Servers\OvhVpsAndDedicated\Core\Traits\Lang;
use ModulesGarden\Servers\OvhVpsAndDedicated\Core\UI\Builder\BaseContainer;
use ModulesGarden\Servers\OvhVpsAndDedicated\Core\UI\Interfaces\AdminArea;
use ModulesGarden\Servers\OvhVpsAndDedicated\Core\UI\Interfaces\ClientArea;
use ModulesGarden\Servers\OvhVpsAndDedicated\Core\UI\Widget\DataTable\Column;
use ModulesGarden\Servers\OvhVpsAndDedicated\Core\UI\Widget\DataTable\DataProviders\Providers\QueryDataProvider;

/**
 * Description of ProductPage
 *
 * @author Artur Pilch <artur.pi@modulesgarden.com>
 */
class Form extends BaseConfigurationPage implements ClientArea, AdminArea
{
    protected $id    = 'configurationForm';
    protected $name  = 'configurationForm';
    protected $title = 'configurationFormTitle';

    public function initContent()
    {
        $this->addElement(new \ModulesGarden\Servers\OvhVpsAndDedicated\App\UI\Admin\Configuration\Forms\ConfigFields());
    }

}
