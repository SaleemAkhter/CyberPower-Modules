<?php

namespace ModulesGarden\Servers\HetznerVps\App\UI\Isos\Modals;

use ModulesGarden\Servers\HetznerVps\Core\UI\Interfaces\ClientArea;
use ModulesGarden\Servers\HetznerVps\Core\UI\Interfaces\AdminArea;
use ModulesGarden\Servers\HetznerVps\Core\UI\Widget\Modals\BaseEditModal;
use ModulesGarden\Servers\HetznerVps\Core\UI\Widget\Modals\BaseModal;

/**
 * Description of Restore
 *
 * @author Mateusz Pawlowski <mateusz.pawlowski94@onet.pl>
 */
class MountModal extends BaseEditModal implements ClientArea, AdminArea
{

    protected $id    = 'mountModal';
    protected $name  = 'mountModal';
    protected $title = 'mountModal';

    public function initContent()
    {
        $this->setModalSizeMedium();
        $this->addElement(new \ModulesGarden\Servers\HetznerVps\Core\UI\Builder\BaseContainer());
        $this->addForm(new \ModulesGarden\Servers\HetznerVps\App\UI\Isos\Forms\MountForm());
    }

}
