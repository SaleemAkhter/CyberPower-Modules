<?php

namespace ModulesGarden\Servers\HetznerVps\App\UI\Configuration\Pages;

use ModulesGarden\Servers\HetznerVps\Core\UI\Builder\BaseContainer;
use ModulesGarden\Servers\HetznerVps\Core\UI\Interfaces\AdminArea;
use ModulesGarden\Servers\HetznerVps\Core\UI\Interfaces\ClientArea;

/**
 * Description of Product
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
        $this->setTitle(\ModulesGarden\Servers\HetznerVps\Core\Helper\Lang::getInstance()->T('featuresForm'));
        $this->addElement(new \ModulesGarden\Servers\HetznerVps\App\UI\Configuration\Forms\ClientAreaFeatures());
    }

}
