<?php

namespace ModulesGarden\Servers\DirectAdminExtended\Core\UI\Widget\Buttons;

use \ModulesGarden\Servers\DirectAdminExtended\Core\UI\Builder\BaseContainer;

/**
 * base button for submiting standalone forms
 *
 * @author Sławomir Miśkowicz <slawomir@modulesgarden.com>
 */
class ButtonSubmitForm extends BaseContainer
{
    use \ModulesGarden\Servers\DirectAdminExtended\Core\UI\Traits\SubmitButton;
    
    protected $id             = 'baseSubmitButton';
    protected $class          = ['lu-btn lu-btn--success mg-submit-form'];
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
