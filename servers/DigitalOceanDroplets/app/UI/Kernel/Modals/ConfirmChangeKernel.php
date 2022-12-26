<?php

namespace ModulesGarden\Servers\DigitalOceanDroplets\App\UI\Kernel\Modals;

use ModulesGarden\Servers\DigitalOceanDroplets\App\UI\Kernel\Forms\ChangeKernel;
use ModulesGarden\Servers\DigitalOceanDroplets\Core\UI\Interfaces\ClientArea;
use ModulesGarden\Servers\DigitalOceanDroplets\Core\UI\Interfaces\AdminArea;
use ModulesGarden\Servers\DigitalOceanDroplets\Core\UI\Widget\Modals\BaseModal;

/**
 * Description of Restore
 *
 * @author Mateusz Pawlowski <mateusz.pawlowski94@onet.pl>
 */
class ConfirmChangeKernel extends BaseModal implements ClientArea, AdminArea
{

    protected $id    = 'confirmChangeKernelModal';
    protected $name  = 'confirmChangeKernelModal';
    protected $title = 'confirmChangeKernelModal';

    public function initContent()
    {
        $this->setModalSizeLarge();
        $this->setConfirmButtonDanger();
        $this->addForm(new ChangeKernel());
    }

}
