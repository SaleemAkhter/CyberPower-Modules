<?php

namespace ModulesGarden\Servers\DigitalOceanDroplets\Core\UI\Traits;

trait DatatableActionButtons
{
    protected $actionButtons = [];

    public function addActionButton($button)
    {
        if (is_string($button))
        {
            $button = ServiceLocator::call($button);
        }

        $button->setWhmcsParams($this->whmcsParams);
        $button->setMainContainer($this->mainContainer);
        $id = $button->getId();
        if (!isset($this->actionButtons[$id]))
        {
            $this->actionButtons[$id] = $button;
            if ($button instanceof \ModulesGarden\Servers\DigitalOceanDroplets\Core\UI\Interfaces\AjaxElementInterface)
            {
                $this->mainContainer->addAjaxElement($this->actionButtons[$id]);
            }
        }

        return $this;
    }

    public function insertActionButton($buttonId)
    {
        if (!$this->actionButtons[$buttonId])
        {
            //add exception
        }
        else
        {
            $button = $this->actionButtons[$buttonId];

            return $button->getHtml();
        }

        return '';
    }

    public function hasActionButtons()
    {
        return (count($this->actionButtons) > 0) ? true : false;
    }

    public function getActionButtons()
    {
        return $this->actionButtons;
    }
}
