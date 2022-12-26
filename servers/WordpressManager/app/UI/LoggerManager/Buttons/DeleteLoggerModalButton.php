<?php

namespace ModulesGarden\WordpressManager\App\UI\LoggerManager\Buttons;

use \ModulesGarden\WordpressManager\Core\UI\Widget\Buttons\ButtonDataTableModalAction;
use \ModulesGarden\WordpressManager\App\UI\LoggerManager\Modals\DeleteLoggerModal;
use \ModulesGarden\WordpressManager\Core\UI\Interfaces\AdminArea;

/**
 * Description of DeleteLabelModalButton
 *
 * @author Sławomir Miśkowicz <slawomir@modulesgarden.com>
 */
class DeleteLoggerModalButton extends ButtonDataTableModalAction implements AdminArea
{
    protected $id    = 'deleteLoggerModalButton';
    protected $name  = 'deleteLoggerModalButton';
    protected $title = 'deleteLoggerModalButton';
    protected $icon  = 'lu-btn__icon lu-zmdi lu-zmdi-delete';

    public function initContent()
    {
        $this->initLoadModalAction(new DeleteLoggerModal());
        
        $this->switchToRemoveBtn();
    }
}
