<?php

namespace ModulesGarden\Servers\DigitalOceanDroplets\Core\UI\Widget\Buttons;

use \ModulesGarden\Servers\DigitalOceanDroplets\Core\UI\Builder\BaseContainer;

/**
 * base button for submiting standalone forms
 *
 * @author Sławomir Miśkowicz <slawomir@modulesgarden.com>
 */
class BaseSubmitButton extends BaseContainer
{

    use \ModulesGarden\Servers\DigitalOceanDroplets\Core\UI\Traits\SubmitButton;
    
    protected $id             = 'baseSubmitButton';
    protected $class          = ['lu-btn lu-btn--success'];
    protected $title          = 'submit';
    protected $htmlAttributes = [
        'href' => 'javascript:;'
    ];

    public function initContent()
    {
        $this->htmlAttributes['@click'] = 'submitForm(\'' . $this->getFormId() . '\', $event)';
        $this->htmlAttributes['@keyup'] = 'submitForm(\'' . $this->getFormId() . '\', $event)';
    }
}
