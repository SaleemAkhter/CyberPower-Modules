<?php

namespace ModulesGarden\Servers\HetznerVps\App\UI\Snapshots\Modals;

use ModulesGarden\Servers\HetznerVps\App\UI\Snapshots\Forms\CreateForm;
use ModulesGarden\Servers\HetznerVps\Core\UI\Interfaces\AdminArea;
use ModulesGarden\Servers\HetznerVps\Core\UI\Interfaces\ClientArea;
use ModulesGarden\Servers\HetznerVps\Core\UI\Widget\Modals\BaseEditModal;

/**
 * Description of Restore
 *
 * @author Mateusz Pawlowski <mateusz.pawlowski94@onet.pl>
 */
class CreateModal extends BaseEditModal implements ClientArea, AdminArea
{

    protected $id    = 'createModal';
    protected $name  = 'createModal';
    protected $title = 'createModal';

    public function initContent()
    {
        $this->setModalSizeMedium();
        $this->addForm(new CreateForm());
    }

}
