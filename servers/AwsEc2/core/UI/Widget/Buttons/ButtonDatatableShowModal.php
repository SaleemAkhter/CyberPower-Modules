<?php

namespace ModulesGarden\Servers\AwsEc2\Core\UI\Widget\Buttons;

use \ModulesGarden\Servers\AwsEc2\Core\UI\Interfaces\AjaxElementInterface;
use \ModulesGarden\Servers\AwsEc2\Core\UI\Widget\Modals\ExampleModal;

/**
 * base button controller
 *
 * @author Sławomir Miśkowicz <slawomir@modulesgarden.com>
 */
class ButtonDatatableShowModal extends ButtonModal implements AjaxElementInterface
{
    protected $id             = 'baseDatatableModalButton';
    protected $title          = 'baseDatatableModalButton';
    protected $htmlAttributes = [
        'href'        => 'javascript:;',
        'data-toggle' => 'lu-tooltip',
    ];

    public function initContent()
    {
        $this->initLoadModalAction(new ExampleModal());
    }
}
