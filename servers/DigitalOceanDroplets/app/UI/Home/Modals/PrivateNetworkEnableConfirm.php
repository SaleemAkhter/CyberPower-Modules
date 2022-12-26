<?php

namespace ModulesGarden\Servers\DigitalOceanDroplets\App\UI\Home\Modals;

use ModulesGarden\Servers\DigitalOceanDroplets\App\UI\Home\Forms\PrivateNetworkEnableAction;
use ModulesGarden\Servers\DigitalOceanDroplets\Core\UI\Interfaces\AdminArea;
use ModulesGarden\Servers\DigitalOceanDroplets\Core\UI\Widget\Modals\BaseModal;

/**
 * Description of Restore
 *
 * @author Mateusz Pawlowski <mateusz.pawlowski94@onet.pl>
 */
class PrivateNetworkEnableConfirm extends BaseModal implements  AdminArea
{

    protected $id    = 'privateNetworkEnableConfirmModal';
    protected $name  = 'privateNetworkEnableConfirmModal';
    protected $title = 'privateNetworkEnableConfirmModal';

    public function initContent()
    {
        $this->setModalSizeLarge();
        $this->addForm(new PrivateNetworkEnableAction());
    }

}
