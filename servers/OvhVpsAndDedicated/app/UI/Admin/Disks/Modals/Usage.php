<?php

namespace ModulesGarden\Servers\OvhVpsAndDedicated\App\UI\Admin\Disks\Modals;

use ModulesGarden\Servers\OvhVpsAndDedicated\App\UI\Admin\Disks\Forms\ChangeKernel;
use ModulesGarden\Servers\OvhVpsAndDedicated\Core\UI\Interfaces\ClientArea;
use ModulesGarden\Servers\OvhVpsAndDedicated\Core\UI\Interfaces\AdminArea;
use ModulesGarden\Servers\OvhVpsAndDedicated\Core\UI\Widget\Modals\BaseModal;
use ModulesGarden\Servers\OvhVpsAndDedicated\App\UI\Admin\Disks\Forms\Usage as FormUsage;

/**
 * Description of Restore
 *
 * @author Artur Pilch <artur.pi@modulesgarden.com>
 */
class Usage extends BaseModal implements ClientArea, AdminArea
{

    protected $id    = 'diskUsage';
    protected $name  = 'diskUsage';
    protected $title = 'diskUsage';

    public function initContent()
    {
        $this->setModalSizeLarge();
        $this->setConfirmButtonDanger();
        $this->addForm(new FormUsage());
    }

}
