<?php

namespace ModulesGarden\Servers\DigitalOceanDroplets\Core\UI\Widget\Buttons;

use \ModulesGarden\Servers\DigitalOceanDroplets\Core\UI\Widget\Modals\ExampleModal;

/**
 * base button controller
 *
 * @author Sławomir Miśkowicz <slawomir@modulesgarden.com>
 */
class BaseMassActionButton extends BaseModalButton
{
    protected $id             = 'baseMassActionButton';
    protected $class          = ['lu-btn lu-btn--default lu-btn--link lu-btn--plain'];
    protected $icon           = 'lu-btn--icon lu-zmdi zlu-mdi-account';
    protected $title          = 'baseMassActionButton';
    protected $htmlAttributes = [
        'href'        => 'javascript:;'
    ];

    public function initContent()
    {
        $this->initLoadModalAction((new ExampleModal()));
    }

    public function switchToRemoveBtn()
    {
        $this->replaceClasses(['lu-btn lu-btn--danger lu-btn--link lu-btn--plain']);
        $this->setIcon('lu-btn--icon lu-zmdi lu-zmdi-delete');

        return $this;
    }
}
