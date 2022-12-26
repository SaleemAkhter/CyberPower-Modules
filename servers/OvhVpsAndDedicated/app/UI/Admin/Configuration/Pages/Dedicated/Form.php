<?php

namespace ModulesGarden\Servers\OvhVpsAndDedicated\App\UI\Admin\Configuration\Pages\Dedicated;

use ModulesGarden\Servers\OvhVpsAndDedicated\Core\UI\Interfaces\AdminArea;
use ModulesGarden\Servers\OvhVpsAndDedicated\Core\UI\Interfaces\ClientArea;
use ModulesGarden\Servers\OvhVpsAndDedicated\App\UI\Admin\Configuration\Pages\Form as VPSForm;

/**
 * Description of ProductPage
 *
 * @author Artur Pilch <artur.pi@modulesgarden.com>
 */
class Form extends VPSForm implements ClientArea, AdminArea
{
    
    protected $id    = 'configurationDedicatedForm';
    protected $name  = 'configurationDedicatedForm';
    protected $title = 'configurationDedicatedForm';

    public function initContent()
    {
        $this->addElement(new \ModulesGarden\Servers\OvhVpsAndDedicated\App\UI\Admin\Configuration\Forms\Dedicated\ConfigFields());
    }

}
