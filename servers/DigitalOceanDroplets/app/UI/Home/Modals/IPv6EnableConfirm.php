<?php

namespace ModulesGarden\Servers\DigitalOceanDroplets\App\UI\Home\Modals;

use ModulesGarden\Servers\DigitalOceanDroplets\App\UI\Home\Forms\IPv6EnableAction;
use ModulesGarden\Servers\DigitalOceanDroplets\Core\UI\Interfaces\AdminArea;
use ModulesGarden\Servers\DigitalOceanDroplets\Core\UI\Widget\Modals\BaseModal;

/**
 * Description of Restore
 *
 * @author Mateusz Pawlowski <mateusz.pawlowski94@onet.pl>
 */
class IPv6EnableConfirm extends BaseModal implements  AdminArea
{

    protected $id    = 'iPv6EnableConfirmModal';
    protected $name  = 'iPv6EnableConfirmModal';
    protected $title = 'iPv6EnableConfirmModal';

    public function initContent()
    {
        $this->setModalSizeLarge();
        $this->addForm(new IPv6EnableAction());
    }

}
