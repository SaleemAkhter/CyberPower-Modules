<?php

namespace ModulesGarden\Servers\DigitalOceanDroplets\App\UI\Snapshots\Buttons;

use \ModulesGarden\Servers\DigitalOceanDroplets\Core\UI\Widget\Buttons\CustomAjaxActionButton;
/**
 * Description of CustomActionButton
 *
 * @author Kamil
 */
class CustomActionButton extends CustomAjaxActionButton {
    
    protected $buttonTitle  = 'ButtonTitle';
    protected $title        = '';
    protected $icon         = '';


    public function setButtonTitle($buttonTitle)
    {
        $this->buttonTitle = $buttonTitle;
    }

    public function getButtonTitle()
    {
        return $this->buttonTitle;
    }
}
