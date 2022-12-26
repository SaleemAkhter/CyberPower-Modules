<?php

namespace ModulesGarden\Servers\OvhVpsAndDedicated\App\UI\Admin\LoggerManager\Buttons;

use \ModulesGarden\Servers\OvhVpsAndDedicated\Core\UI\Widget\Buttons\ButtonModal;
use \ModulesGarden\Servers\OvhVpsAndDedicated\App\UI\Admin\LoggerManager\Modals\DeleteAllLoggersModal;
use \ModulesGarden\Servers\OvhVpsAndDedicated\Core\UI\Interfaces\AdminArea;

/**
 * Description of AssignTldButton
 *
 * @author Sławomir Miśkowicz <slawomir@modulesgarden.com>
 */
class DeleteAllLoggersButton extends ButtonModal implements AdminArea
{
    protected $id    = 'deleteAllLoggersButton';
    protected $name  = 'deleteAllLoggersButton';
    protected $title = 'deleteAllLoggersButton';

    protected $class = ['lu-btn lu-btn--danger'];
    protected $icon = 'lu-btn__icon lu-zmdi lu-zmdi-delete';
    
    public function initContent()
    {
        $this->initLoadModalAction(new DeleteAllLoggersModal());
    }
}
