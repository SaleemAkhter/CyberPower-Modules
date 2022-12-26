<?php

namespace ModulesGarden\Servers\DigitalOceanDroplets\App\UI\Configuration\Modals;

use ModulesGarden\Servers\DigitalOceanDroplets\App\UI\Configuration\Forms\CreateConfigurableAction;
use ModulesGarden\Servers\DigitalOceanDroplets\Core\UI\Interfaces\AdminArea;
use ModulesGarden\Servers\DigitalOceanDroplets\Core\UI\Widget\Modals\BaseEditModal;

/**
 * Description of Restore
 *
 * @author Mateusz Pawlowski <mateusz.pawlowski94@onet.pl>
 */
class CreateConfigurableOptionsConfirm extends BaseEditModal implements AdminArea
{

    protected $id    = 'createCOConfirmModal';
    protected $name  = 'createCOConfirmModal';
    protected $title = 'createCOConfirmModal';

    public function initContent()
    {
        $this->setModalSizeLarge();
        $this->addForm(new CreateConfigurableAction());
    }

}
