<?php

namespace ModulesGarden\Servers\OvhVpsAndDedicated\App\UI\Admin\Configuration\Pages\Dedicated;

use ModulesGarden\Servers\OvhVpsAndDedicated\App\UI\Admin\Configuration\Forms\Dedicated\EmailTemplate;
use ModulesGarden\Servers\OvhVpsAndDedicated\Core\Helper\Lang;
use ModulesGarden\Servers\OvhVpsAndDedicated\Core\UI\Interfaces\AdminArea;
use ModulesGarden\Servers\OvhVpsAndDedicated\Core\UI\Interfaces\ClientArea;
use ModulesGarden\Servers\OvhVpsAndDedicated\App\UI\Admin\Configuration\Pages\EmailOptions as VPSEmailOptions;

/**
 * Description of ProductPage
 *
 * @author Artur Pilch <artur.pi@modulesgarden.com>
 */
class EmailOptions extends VPSEmailOptions implements ClientArea, AdminArea
{
    protected $id    = 'emailOptionsForm';
    protected $name  = 'emailOptionsForm';
    protected $title = 'emailOptionsFormTitle';

    public function initContent()
    {
        $this->addElement(new EmailTemplate());
    }

}
