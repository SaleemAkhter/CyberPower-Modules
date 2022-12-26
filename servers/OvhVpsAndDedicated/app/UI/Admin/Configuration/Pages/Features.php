<?php

namespace ModulesGarden\Servers\OvhVpsAndDedicated\App\UI\Admin\Configuration\Pages;

use ModulesGarden\Servers\OvhVpsAndDedicated\App\UI\Admin\Configuration\Pages\Basics\BaseOptionsPage;
use ModulesGarden\Servers\OvhVpsAndDedicated\Core\UI\Interfaces\AdminArea;
use ModulesGarden\Servers\OvhVpsAndDedicated\Core\UI\Interfaces\ClientArea;

/**
 * Description of ProductPage
 *
 * @author Artur Pilch <artur.pi@modulesgarden.com>
 */
class Features extends BaseOptionsPage implements ClientArea, AdminArea
{
    protected $id    = 'featuresForm';
    protected $name  = 'featuresForm';
    protected $title = 'featuresFormTitle';

    public function initContent()
    {
        $this->addClass('lu-row');
        $this->addElement(new \ModulesGarden\Servers\OvhVpsAndDedicated\App\UI\Admin\Configuration\Forms\ClientAreaFeatures());
    }

}
