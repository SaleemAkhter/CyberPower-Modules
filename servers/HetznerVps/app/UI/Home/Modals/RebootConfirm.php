<?php

namespace ModulesGarden\Servers\HetznerVps\App\UI\Home\Modals;

use ModulesGarden\Servers\HetznerVps\App\UI\Home\Forms\RebootAction;
use ModulesGarden\Servers\HetznerVps\Core\UI\Interfaces\AdminArea;
use ModulesGarden\Servers\HetznerVps\Core\UI\Interfaces\ClientArea;
use ModulesGarden\Servers\HetznerVps\Core\UI\Widget\Modals\BaseModal;

/**
 * Description of Restore
 *
 * @author Mateusz Pawlowski <mateusz.pawlowski94@onet.pl>
 */
class RebootConfirm extends BaseModal implements ClientArea, AdminArea
{

    protected $id    = 'rebootConfirmModal';
    protected $name  = 'rebootConfirmModal';
    protected $title = 'rebootConfirmModal';

    public function initContent()
    {
        $this->setModalSizeLarge();
        $this->setModalTitleTypeDanger();
        $this->setSubmitButtonClassesDanger();
        $this->addForm(new RebootAction());
    }

}
