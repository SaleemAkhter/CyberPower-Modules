<?php

namespace ModulesGarden\Servers\GoogleCloudVirtualMachines\App\UI\Client\Snapshots\Buttons;

use \ModulesGarden\Servers\GoogleCloudVirtualMachines\Core\UI\Widget\Buttons\CustomAjaxActionButton;
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
