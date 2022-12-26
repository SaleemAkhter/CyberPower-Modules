<?php

namespace ModulesGarden\WordpressManager\Core\UI\Widget\Buttons;

use ModulesGarden\WordpressManager\Core\UI\Traits\DisableButtonByColumnValue;
use ModulesGarden\WordpressManager\Core\UI\Traits\ShowButtonByColumnValue;
use \ModulesGarden\WordpressManager\Core\UI\Widget\Modals\ExampleModal;

/**
 * base button controller
 *
 * @author Sławomir Miśkowicz <slawomir@modulesgarden.com>
 */
class ButtonMassAction extends ButtonModal
{
    use DisableButtonByColumnValue;
    use ShowButtonByColumnValue;

    protected $id             = 'baseMassActionButton';
    protected $class          = ['lu-btn lu-btn--link lu-btn--plain lu-btn--default'];
    protected $icon           = 'lu-btn__icon lu-zmdi lu-zmdi-account';
    protected $title          = 'baseMassActionButton';
    protected $showTitle      =true;
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
        $this->setIcon('lu-btn__icon lu-zmdi lu-zmdi-delete');

        return $this;
    }
    public function resetClass()
    {
        $this->class = [];

        return $this;
    }
    public function addClass($class)
    {
        if (is_string($class))
        {
            $this->class[] = $class;
        }

        return $this;
    }
}
