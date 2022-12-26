<?php

namespace ModulesGarden\WordpressManager\App\UI\LoggerManager\Buttons;

use \ModulesGarden\WordpressManager\Core\UI\Widget\Buttons\ButtonMassActionContextLang;
use \ModulesGarden\WordpressManager\App\UI\LoggerManager\Modals\MassDeleteLoggerModal;
use \ModulesGarden\WordpressManager\Core\UI\Interfaces\AdminArea;

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
        $namespace                      = str_replace("\\", "_", self::class);
        $this->htmlAttributes['@click'] = 'loadModal($event, \'' . $this->id . '\',\'' . $namespace . '\')';

        $this->setModal(new MassDeleteLoggerModal());
        
        $this->switchToRemoveBtn();
    }
}
