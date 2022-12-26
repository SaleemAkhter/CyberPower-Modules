<?php

namespace ModulesGarden\Servers\DigitalOceanDroplets\App\UI\Rebuild\Modals;

use ModulesGarden\Servers\DigitalOceanDroplets\Core\UI\Interfaces\ClientArea;
use ModulesGarden\Servers\DigitalOceanDroplets\Core\UI\Interfaces\AdminArea;
use ModulesGarden\Servers\DigitalOceanDroplets\Core\UI\Widget\Modals\BaseModal;

/**
 * Description of Restore
 *
 * @author Mateusz Pawlowski <mateusz.pawlowski94@onet.pl>
 */
class RebuildConfirm extends BaseModal implements ClientArea, AdminArea
{

    protected $id    = 'confirmRebuildModal';
    protected $name  = 'confirmRebuildModal';
    protected $title = 'confirmRebuildModal';

    public function initContent()
    {
        $this->setModalSizeMedium();
        $this->setConfirmButtonDanger();
        $this->addElement(new \ModulesGarden\Servers\DigitalOceanDroplets\Core\UI\Builder\BaseContainer());
        $this->addForm(new \ModulesGarden\Servers\DigitalOceanDroplets\App\UI\Rebuild\Forms\RebuildConfirm());
    }

}
