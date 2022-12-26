<?php

namespace ModulesGarden\Servers\VultrVps\App\UI\Client\Home\Buttons;

use ModulesGarden\Servers\VultrVps\Core\Helper\BuildUrl;
use ModulesGarden\Servers\VultrVps\Core\UI\Widget\Buttons\ButtonModal;

class VpsActionButton extends ButtonModal
{
    protected $id = 'vpsActionButton';
    protected $name = 'vpsActionButton';
    protected $title = 'vpsActionButtonTitle';
    protected $class = ['lu-tile', 'lu-tile--btn'];

    protected $htmlAttributes = [
        'href'        => 'javascript:;',
        'data-toggle' => 'lu-tooltip'
    ];

    protected $vueComponent = true;
    protected $defaultVueComponentName = 'mg-vps-action-button';

    protected $componentStatus = 'statusWidget';

    protected $iconFileName = 'powerOnButton.png';

    /**
     * @return string
     */
    public function getIconFileName()
    {
        return BuildUrl::getAppAssetsURL() . '/img/' . $this->iconFileName;
    }

    /**
     * @param string $iconFileName
     * @return VpsActionButton
     */
    public function setIconFileName($iconFileName)
    {
        if (is_string($iconFileName) && trim($iconFileName) !== '')
        {
            $this->iconFileName = $iconFileName;
        }

        return $this;
    }

    /**
     * @return string
     */
    public function getComponentStatus()
    {
        return $this->componentStatus;
    }

    /**
     * @param string $componentStatus
     * @return VpsActionButton
     */
    public function setComponentStatus($componentStatus = null)
    {
        if (is_string($componentStatus) && trim($componentStatus) !== '')
        {
            $this->componentStatus = $componentStatus;
        }

        return $this;
    }
}
