<?php

namespace ModulesGarden\OvhVpsAndDedicated\App\UI\Admin\LoggerManager\Buttons;

use \ModulesGarden\OvhVpsAndDedicated\Core\UI\Widget\Buttons\ButtonDataTableModalAction;
use \ModulesGarden\OvhVpsAndDedicated\App\UI\Admin\LoggerManager\Modals\DeleteLoggerModal;
use \ModulesGarden\OvhVpsAndDedicated\Core\UI\Interfaces\AdminArea;

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

    public function initContent()
    {
        $this->initLoadModalAction(new DeleteLoggerModal());
        $this->switchToRemoveBtn();
    }
}
