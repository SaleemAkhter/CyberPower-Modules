<?php

namespace ModulesGarden\Servers\HetznerVps\App\UI\Snapshots\Modals;

use ModulesGarden\Servers\HetznerVps\App\UI\Snapshots\Forms\UpdateForm;
use ModulesGarden\Servers\HetznerVps\Core\UI\Interfaces\AdminArea;
use ModulesGarden\Servers\HetznerVps\Core\UI\Interfaces\ClientArea;
use ModulesGarden\Servers\HetznerVps\Core\UI\Widget\Modals\BaseEditModal;

/**
 * Description of Restore
 *
 * @author Mateusz Pawlowski <mateusz.pawlowski94@onet.pl>
 */
class  UpdateModal extends  BaseEditModal implements ClientArea, AdminArea
{
    protected $id    = 'updateModal';
    protected $name  = 'updateModal';
    protected $title = 'updateModal';

    public function initContent()
    {
        $this->setModalSizeMedium();
        $this->addElement(new \ModulesGarden\Servers\HetznerVps\Core\UI\Builder\BaseContainer());
        $this->addForm(new UpdateForm());
    }

}
