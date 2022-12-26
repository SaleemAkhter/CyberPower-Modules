<?php

namespace ModulesGarden\Servers\GoogleCloudVirtualMachines\App\UI\Client\Snapshots\Modals;

use ModulesGarden\Servers\GoogleCloudVirtualMachines\Core\UI\Interfaces\ClientArea;
use ModulesGarden\Servers\GoogleCloudVirtualMachines\Core\UI\Interfaces\AdminArea;
use ModulesGarden\Servers\GoogleCloudVirtualMachines\Core\UI\Widget\Modals\BaseEditModal;

/**
 * Description of Restore
 *
 * @author Mateusz Pawlowski <mateusz.pawlowski94@onet.pl>
 */
class ConfirmCreate extends BaseEditModal implements ClientArea, AdminArea
{

    protected $id    = 'confirmModal';
    protected $name  = 'confirmModal';
    protected $title = 'confirmModal';

    public function initContent()
    {       
        $this->addForm(new \ModulesGarden\Servers\GoogleCloudVirtualMachines\App\UI\Client\Snapshots\Forms\AddSnapshot());
    }

}
