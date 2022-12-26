<?php

namespace ModulesGarden\Servers\HetznerVps\App\UI\Rebuild\Modals;

use ModulesGarden\Servers\HetznerVps\Core\UI\Interfaces\ClientArea;
use ModulesGarden\Servers\HetznerVps\Core\UI\Interfaces\AdminArea;
use ModulesGarden\Servers\HetznerVps\Core\UI\Widget\Modals\BaseModal;

/**
 * Description of Restore
 *
 * @author Mateusz Pawlowski <mateusz.pawlowski94@onet.pl>
 */
class RebuildConfirm extends BaseModal implements ClientArea, AdminArea
{

    protected $id    = 'confirmRebuildModal';
    protected $name  = 'confirmRebuildModal';
    protected $title = 'confirmRebuildModal';

    public function initContent()
    {
        $this->setModalSizeMedium();
        $this->setModalTitleTypeDanger();
        $this->setSubmitButtonClassesDanger();
        $this->addElement(new \ModulesGarden\Servers\HetznerVps\Core\UI\Builder\BaseContainer());
        $this->addForm(new \ModulesGarden\Servers\HetznerVps\App\UI\Rebuild\Forms\RebuildConfirm());
    }

}
