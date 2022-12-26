<?php

namespace ModulesGarden\Servers\HetznerVps\App\UI\Isos\Modals;

use ModulesGarden\Servers\HetznerVps\App\UI\Isos\Forms\UnmountForm;
use ModulesGarden\Servers\HetznerVps\Core\UI\Interfaces\ClientArea;
use ModulesGarden\Servers\HetznerVps\Core\UI\Interfaces\AdminArea;
use ModulesGarden\Servers\HetznerVps\Core\UI\Widget\Modals\BaseEditModal;
use ModulesGarden\Servers\HetznerVps\Core\UI\Widget\Modals\BaseModal;

/**
 * Description of Restore
 *
 * @author Mateusz Pawlowski <mateusz.pawlowski94@onet.pl>
 */
class UnmountModal extends BaseEditModal implements ClientArea, AdminArea
{

    protected $id    = 'unmountModal';
    protected $name  = 'unmountModal';
    protected $title = 'unmountModal';

    public function initContent()
    {
        $this->setModalSizeMedium();
        $this->addElement(new \ModulesGarden\Servers\HetznerVps\Core\UI\Builder\BaseContainer());
        $this->addForm(new UnmountForm());
    }

}
