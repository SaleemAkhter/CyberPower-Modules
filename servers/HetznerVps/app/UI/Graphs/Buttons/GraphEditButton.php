<?php

namespace ModulesGarden\Servers\HetznerVps\App\UI\Graphs\Buttons;

use ModulesGarden\Servers\HetznerVps\App\UI\Graphs\Modals\GraphsEditModal;
use ModulesGarden\Servers\HetznerVps\Core\UI\Interfaces\AdminArea;
use ModulesGarden\Servers\HetznerVps\Core\UI\Interfaces\ClientArea;
use ModulesGarden\Servers\HetznerVps\Core\UI\Widget\Buttons\ButtonModal;

class GraphEditButton extends ButtonModal implements ClientArea, AdminArea
{
    protected $id               = 'graphsEditButton';
    protected $name             = 'graphsEditButton';
    protected $title            = 'graphsEditButton';
    protected $icon             = 'lu-icon lu-zmdi lu-zmdi-edit';
    protected $class            = ['lu-btn lu-btn--sm lu-btn--link lu-btn--icon lu-btn--plain lu-tooltip'];

    public function initContent()
    {
        $this->initLoadModalAction(new GraphsEditModal());
    }
}