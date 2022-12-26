<?php

namespace ModulesGarden\Servers\DigitalOceanDroplets\Core\UI\Widget\Buttons;

use \ModulesGarden\Servers\DigitalOceanDroplets\Core\UI\Interfaces\AjaxElementInterface;
use \ModulesGarden\Servers\DigitalOceanDroplets\Core\UI\Widget\Modals\ExampleModal;

/**
 * base button controller
 *
 * @author Sławomir Miśkowicz <slawomir@modulesgarden.com>
 */
class BaseModalDataTableActionButton extends BaseModalButton implements AjaxElementInterface
{
    use \ModulesGarden\Servers\DigitalOceanDroplets\Core\UI\Traits\DisableButtonByColumnValue;
    
    protected $id             = 'baseModalDataTableActionButton';
    protected $class          = ['lu-btn lu-btn-circle lu-btn-outline lu-btn-inverse lu-btn-success lu-btn-icon-only'];
    protected $icon           = 'fa fa-plus';
    protected $title          = 'baseModalDataTableActionButton';

    public function initContent()
    {
        $this->htmlAttributes['@click'] = 'loadModal($event, \'' . $this->id . '\')';

        $this->setModal(new ExampleModal());
    }
}
