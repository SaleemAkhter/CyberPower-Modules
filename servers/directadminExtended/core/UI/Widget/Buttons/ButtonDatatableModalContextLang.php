<?php

namespace ModulesGarden\Servers\DirectAdminExtended\Core\UI\Widget\Buttons;

use \ModulesGarden\Servers\DirectAdminExtended\Core\UI\Interfaces\AjaxElementInterface;
use \ModulesGarden\Servers\DirectAdminExtended\Core\UI\Widget\Modals\ExampleModal;

/**
 * base button controller
 *
 * @author Sławomir Miśkowicz <slawomir@modulesgarden.com>
 */
class ButtonDatatableModalContextLang extends ButtonModal implements AjaxElementInterface
{
    protected $id             = 'ButtonDatatableModalContextLang';
    protected $icon           = 'lu-zmdi lu-zmdi-plus';
    protected $title          = 'ButtonDatatableModalContextLang';
    protected $htmlAttributes = [
        'href'        => 'javascript:;',
        'data-toggle' => 'lu-tooltip',
    ];

    public function initContent()
    {
        $this->initLoadModalAction(new ExampleModal());
    }
}
