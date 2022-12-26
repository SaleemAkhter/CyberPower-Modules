<?php

namespace ModulesGarden\Servers\DigitalOceanDroplets\Core\UI\Traits;

use ModulesGarden\Servers\DigitalOceanDroplets\Core\DependencyInjection;

trait Buttons
{
    protected $buttons = [];

    public function addButton($button)
    {
        if (is_string($button))
        {
            $button = DependencyInjection::create($button);
        }
        if($this->whmcsParams)
        {
           $button->setWhmcsParams($this->whmcsParams);
        }
        
        $button->setMainContainer($this->mainContainer);
        $id = $button->getId();
        if (!isset($this->buttons[$id]))
        {
            $this->buttons[$id] = $button;
            if ($button instanceof \ModulesGarden\Servers\DigitalOceanDroplets\Core\UI\Interfaces\AjaxElementInterface)
            {
                $this->mainContainer->addAjaxElement($this->buttons[$id]);
            }
        }

        return $this;
    }

    public function insertButton($buttonId)
    {
        if (!$this->buttons[$buttonId])
        {
            //add exception
        }
        else
        {
            $button = $this->buttons[$buttonId];

            return $button->getHtml();
        }

        return '';
    }
    
    public function getButtons()
    {
        return $this->buttons;
    }
}
