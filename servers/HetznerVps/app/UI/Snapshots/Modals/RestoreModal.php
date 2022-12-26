<?php

namespace ModulesGarden\Servers\HetznerVps\App\UI\Snapshots\Modals;

use ModulesGarden\Servers\HetznerVps\Core\UI\Interfaces\AdminArea;
use ModulesGarden\Servers\HetznerVps\Core\UI\Interfaces\ClientArea;
use ModulesGarden\Servers\HetznerVps\Core\UI\Widget\Modals\BaseModal;

/**
 * Description of Restore
 *
 * @author Mateusz Pawlowski <mateusz.pawlowski94@onet.pl>
 */
class RestoreModal extends BaseModal implements ClientArea, AdminArea
{

    protected $id    = 'restoreModal';
    protected $name  = 'restoreModal';
    protected $title = 'restoreModal';

    public function initContent()
    {
        $this->setModalSizeMedium();
        $this->setSubmitButtonClassesDanger();
        $this->addElement(new \ModulesGarden\Servers\HetznerVps\Core\UI\Builder\BaseContainer());
        $this->addForm(new \ModulesGarden\Servers\HetznerVps\App\UI\Snapshots\Forms\RestoreForm());
    }

}
