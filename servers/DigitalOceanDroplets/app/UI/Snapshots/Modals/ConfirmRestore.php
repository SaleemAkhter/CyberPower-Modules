<?php

namespace ModulesGarden\Servers\DigitalOceanDroplets\App\UI\Snapshots\Modals;

use ModulesGarden\Servers\DigitalOceanDroplets\App\UI\Snapshots\Forms\RestoreDroplet;
use ModulesGarden\Servers\DigitalOceanDroplets\Core\UI\Interfaces\ClientArea;
use ModulesGarden\Servers\DigitalOceanDroplets\Core\UI\Interfaces\AdminArea;
use ModulesGarden\Servers\DigitalOceanDroplets\Core\UI\Widget\Modals\BaseModal;

/**
 * Description of Restore
 *
 * @author Mateusz Pawlowski <mateusz.pawlowski94@onet.pl>
 */
class ConfirmRestore extends BaseModal implements ClientArea, AdminArea
{

    protected $id    = 'confirmRestoreModal';
    protected $name  = 'confirmRestoreModal';
    protected $title = 'confirmRestoreModal';

    public function initContent()
    {
        $this->setModalSizeLarge();
        $this->setConfirmButtonDanger();
        $this->addForm(new RestoreDroplet());
    }

}
