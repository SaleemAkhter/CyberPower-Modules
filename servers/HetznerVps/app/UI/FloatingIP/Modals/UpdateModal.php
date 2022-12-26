<?php

namespace ModulesGarden\Servers\HetznerVps\App\UI\FloatingIP\Modals;

use ModulesGarden\Servers\HetznerVps\App\UI\FloatingIP\Forms\UpdateForm;
use ModulesGarden\Servers\HetznerVps\Core\UI\Interfaces\AdminArea;
use ModulesGarden\Servers\HetznerVps\Core\UI\Interfaces\ClientArea;
use ModulesGarden\Servers\HetznerVps\Core\UI\Widget\Modals\BaseEditModal;

class  UpdateModal extends BaseEditModal implements ClientArea, AdminArea
{
    protected $id = 'floatingIPsEditModal';
    protected $name = 'floatingIPsEditModal';
    protected $title = 'floatingIPsEditModal';

    public function initContent()
    {
        $this->addElement(new \ModulesGarden\Servers\HetznerVps\Core\UI\Builder\BaseContainer());
        $this->addForm(new UpdateForm());
    }

}
