<?php

namespace ModulesGarden\Servers\DigitalOceanDroplets\App\UI\Configuration\Pages;

use ModulesGarden\Servers\DigitalOceanDroplets\Core\UI\Builder\BaseContainer;
use ModulesGarden\Servers\DigitalOceanDroplets\Core\UI\Interfaces\AdminArea;
use ModulesGarden\Servers\DigitalOceanDroplets\Core\UI\Interfaces\ClientArea;

/**
 * Description of ProductPage
 *
 * @author Mateusz Pawłowski <mateusz.pa@modulesgarden.com>
 */
class Features extends BaseContainer implements ClientArea, AdminArea
{

    protected $id    = 'featuresForm';
    protected $name  = 'featuresForm';
    protected $title = 'featuresForm';

    public function initContent()
    {
        $this->setTitle(\ModulesGarden\Servers\DigitalOceanDroplets\Core\Helper\Lang::getInstance()->T('featuresForm'));
        $this->addElement(new \ModulesGarden\Servers\DigitalOceanDroplets\App\UI\Configuration\Forms\ClientAreaFeatures());
    }

}
