<?php

namespace ModulesGarden\Servers\HetznerVps\App\UI\Graphs\Modals;

use ModulesGarden\Servers\HetznerVps\App\UI\Graphs\Forms\GraphEditForm;
use ModulesGarden\Servers\HetznerVps\Core\UI\Interfaces\AdminArea;
use ModulesGarden\Servers\HetznerVps\Core\UI\Interfaces\ClientArea;
use ModulesGarden\Servers\HetznerVps\Core\UI\Widget\Modals\BaseEditModal;

class GraphsEditModal extends BaseEditModal implements ClientArea, AdminArea
{
    protected $id               = 'graphEditModal';
    protected $name             = 'graphEditModal';
    protected $title            = 'graphEditModal';

    public function initContent()
    {
        $this->addForm(new GraphEditForm());
    }
}