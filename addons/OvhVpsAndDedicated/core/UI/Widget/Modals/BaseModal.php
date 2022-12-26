<?php

namespace ModulesGarden\OvhVpsAndDedicated\Core\UI\Widget\Modals;

use \ModulesGarden\OvhVpsAndDedicated\Core\UI\Builder\BaseContainer;

/**
 * BaseModal controller
 *
 * @author Sławomir Miśkowicz <slawomir@modulesgarden.com>
 */
class BaseModal extends BaseContainer
{

    use \ModulesGarden\OvhVpsAndDedicated\Core\UI\Traits\Forms;
    use \ModulesGarden\OvhVpsAndDedicated\Core\UI\Traits\Modal;
    use \ModulesGarden\OvhVpsAndDedicated\Core\UI\Traits\ModalActionButtons;

    protected $id    = 'baseModal';
    protected $name  = 'baseModal';
    protected $title = 'baseModal';

    public function runInitContentProcess()
    {
        if ($this->getRequestValue('ajax', false) == 1)
        {
            parent::runInitContentProcess();
        }
    }
}
