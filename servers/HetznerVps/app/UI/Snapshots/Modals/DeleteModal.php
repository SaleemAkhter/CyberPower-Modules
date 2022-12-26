<?php

namespace ModulesGarden\Servers\HetznerVps\App\UI\Snapshots\Modals;

use ModulesGarden\Servers\HetznerVps\App\UI\Snapshots\Forms\DeleteForm;
use ModulesGarden\Servers\HetznerVps\Core\UI\Interfaces\AdminArea;
use ModulesGarden\Servers\HetznerVps\Core\UI\Interfaces\ClientArea;
use ModulesGarden\Servers\HetznerVps\Core\UI\Widget\Modals\BaseModal;

/**
 * Description of Restore
 *
 * @author Mateusz Pawlowski <mateusz.pawlowski94@onet.pl>
 */
class DeleteModal extends  BaseModal implements ClientArea, AdminArea
{
    protected $id    = 'deleteModal';
    protected $name  = 'deleteModal';
    protected $title = 'deleteModal';

    public function initContent()
    {
        $this->setModalSizeMedium();
        $this->setSubmitButtonClassesDanger();
        $this->addElement(new \ModulesGarden\Servers\HetznerVps\Core\UI\Builder\BaseContainer());
        $this->addForm(new DeleteForm());
    }

}
