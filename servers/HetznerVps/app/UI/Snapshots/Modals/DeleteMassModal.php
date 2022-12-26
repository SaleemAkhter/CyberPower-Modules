<?php

namespace ModulesGarden\Servers\HetznerVps\App\UI\Snapshots\Modals;

use ModulesGarden\Servers\HetznerVps\App\UI\Snapshots\Forms\DeleteMassForm;
use ModulesGarden\Servers\HetznerVps\Core\UI\Interfaces\AdminArea;
use ModulesGarden\Servers\HetznerVps\Core\UI\Interfaces\ClientArea;
use ModulesGarden\Servers\HetznerVps\Core\UI\Widget\Modals\BaseModal;

/**
 * Description of Restore
 *
 * @author Mateusz Pawlowski <mateusz.pawlowski94@onet.pl>
 */
class DeleteMassModal extends BaseModal implements ClientArea, AdminArea
{
    protected $id    = 'deleteMassModal';
    protected $name  = 'deleteMassModal';
    protected $title = 'deleteMassModal';

    public function initContent()
    {
        $this->setModalSizeMedium();
        $this->setSubmitButtonClassesDanger();
        $this->addElement(new \ModulesGarden\Servers\HetznerVps\Core\UI\Builder\BaseContainer());
        $this->addForm(new DeleteMassForm());
    }

}
