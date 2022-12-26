<?php

namespace ModulesGarden\Servers\OvhVpsAndDedicated\App\UI\Admin\LoggerManager\Buttons;

use \ModulesGarden\Servers\OvhVpsAndDedicated\Core\UI\Widget\Buttons\ButtonDataTableModalAction;
use \ModulesGarden\Servers\OvhVpsAndDedicated\App\UI\Admin\LoggerManager\Modals\DeleteLoggerModal;
use \ModulesGarden\Servers\OvhVpsAndDedicated\Core\UI\Interfaces\AdminArea;

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
