<?php

namespace ModulesGarden\DirectAdminExtended\Core\UI\Widget\Modals;

use \ModulesGarden\DirectAdminExtended\Core\UI\Builder\BaseContainer;

/**
 * BaseModal controller
 *
 * @author Sławomir Miśkowicz <slawomir@modulesgarden.com>
 */
class BaseModal extends BaseContainer
{

    use \ModulesGarden\DirectAdminExtended\Core\UI\Traits\Forms;
    use \ModulesGarden\DirectAdminExtended\Core\UI\Traits\Modal;
    use \ModulesGarden\DirectAdminExtended\Core\UI\Traits\ModalActionButtons;

    protected $id    = 'baseModal';
    protected $name  = 'baseModal';
    protected $title = 'baseModal';

    public function runInitContentProcess()
    {
        $this->initActionButtons();
        if ($this->getRequestValue('ajax', false) == 1)
        {
            parent::runInitContentProcess();
        }
    }
}
