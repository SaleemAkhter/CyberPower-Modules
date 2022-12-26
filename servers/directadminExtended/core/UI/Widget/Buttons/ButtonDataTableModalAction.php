<?php

namespace ModulesGarden\Servers\DirectAdminExtended\Core\UI\Widget\Buttons;

use \ModulesGarden\Servers\DirectAdminExtended\Core\UI\Interfaces\AjaxElementInterface;
use ModulesGarden\Servers\DirectAdminExtended\Core\UI\Traits\Buttons;
use \ModulesGarden\Servers\DirectAdminExtended\Core\UI\Widget\Modals\ExampleModal;
use \ModulesGarden\Servers\DirectAdminExtended\Core\UI\Traits\HideButtonByColumnValue;

/**
 * base button controller
 *
 * @author Sławomir Miśkowicz <slawomir@modulesgarden.com>
 */
class ButtonDataTableModalAction extends ButtonModal implements AjaxElementInterface
{
    use \ModulesGarden\Servers\DirectAdminExtended\Core\UI\Traits\DisableButtonByColumnValue;
    use HideButtonByColumnValue;
    
    protected $id             = 'baseModalDataTableActionButton';
    protected $class          = ['lu-btn lu-btn--sm lu-btn lu-btn--link lu-btn--icon lu-btn--plain lu-btn--default'];
    protected $icon           = 'lu-btn__icon lu-zmdi lu-zmdi-edit';
    protected $title          = 'baseModalDataTableActionButton';

    public function initContent()
    {
        $this->initLoadModalAction(new ExampleModal());
    }

    public function switchToRemoveBtn()
    {
        $this->replaceClasses(['lu-btn lu-btn--sm lu-btn--danger lu-btn--link lu-btn--icon lu-btn--plain']);
        $this->setIcon('lu-btn__icon lu-zmdi lu-zmdi-delete');

        return $this;
    }
}
