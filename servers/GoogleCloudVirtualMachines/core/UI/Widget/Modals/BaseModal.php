<?php

namespace ModulesGarden\Servers\GoogleCloudVirtualMachines\Core\UI\Widget\Modals;

use \ModulesGarden\Servers\GoogleCloudVirtualMachines\Core\UI\Builder\BaseContainer;

/**
 * BaseModal controller
 *
 * @author Sławomir Miśkowicz <slawomir@modulesgarden.com>
 */
class BaseModal extends BaseContainer
{

    use \ModulesGarden\Servers\GoogleCloudVirtualMachines\Core\UI\Traits\Forms;
    use \ModulesGarden\Servers\GoogleCloudVirtualMachines\Core\UI\Traits\Modal;
    use \ModulesGarden\Servers\GoogleCloudVirtualMachines\Core\UI\Traits\ModalActionButtons;

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
