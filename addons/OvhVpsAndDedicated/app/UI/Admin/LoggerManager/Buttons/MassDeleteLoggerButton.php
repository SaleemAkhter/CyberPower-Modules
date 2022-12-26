<?php

namespace ModulesGarden\OvhVpsAndDedicated\App\UI\Admin\LoggerManager\Buttons;

use \ModulesGarden\OvhVpsAndDedicated\Core\UI\Widget\Buttons\ButtonMassActionContextLang;
use \ModulesGarden\OvhVpsAndDedicated\App\UI\Admin\LoggerManager\Modals\MassDeleteLoggerModal;
use \ModulesGarden\OvhVpsAndDedicated\Core\UI\Interfaces\AdminArea;

/**
 * Description of DeleteTldButton
 *
 * @author Sławomir Miśkowicz <slawomir@modulesgarden.com>
 */
class MassDeleteLoggerButton extends ButtonMassActionContextLang implements AdminArea
{
    protected $id    = 'massDeleteLoggerButton';
    protected $name  = 'massDeleteLoggerButton';
    protected $title = 'massDeleteLoggerButton';

    public function initContent()
    {
        $this->initLoadModalAction(new MassDeleteLoggerModal());
        $this->switchToRemoveBtn();
    }
}
