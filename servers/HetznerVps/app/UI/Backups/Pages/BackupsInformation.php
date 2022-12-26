<?php

namespace ModulesGarden\Servers\HetznerVps\App\UI\Backups\Pages;

use ModulesGarden\Servers\HetznerVps\App\UI\Backups\Others\BackupsDescription;
use ModulesGarden\Servers\HetznerVps\Core\Helper\Lang;
use ModulesGarden\Servers\HetznerVps\Core\UI\Builder\BaseContainer;
use ModulesGarden\Servers\HetznerVps\Core\UI\Interfaces\AdminArea;
use ModulesGarden\Servers\HetznerVps\Core\UI\Interfaces\ClientArea;

/**
 * Description of Product
 *
 * @author Mateusz Pawłowski <mateusz.pa@modulesgarden.com>
 */
class BackupsInformation extends BaseContainer implements ClientArea, AdminArea
{

    protected $id    = 'cronInformation';
    protected $name  = 'cronInformation';

    public function initContent()
    {
        $this->addElement(BackupsDescription::class);
        $this->addHtmlAttribute('style', 'width: 98%');
    }

}
