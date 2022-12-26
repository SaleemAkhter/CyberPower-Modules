<?php

namespace ModulesGarden\WordpressManager\Core\UI\Widget\Modals;

use \ModulesGarden\WordpressManager\Core\UI\Builder\BaseContainer;

/**
 * BaseModal controller
 *
 * @author Sławomir Miśkowicz <slawomir@modulesgarden.com>
 */
class BaseModal extends BaseContainer
{

    use \ModulesGarden\WordpressManager\Core\UI\Traits\Forms;
    use \ModulesGarden\WordpressManager\Core\UI\Traits\Modal;
    use \ModulesGarden\WordpressManager\Core\UI\Traits\ModalActionButtons;

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
