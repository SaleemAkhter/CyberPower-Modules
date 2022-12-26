<?php

namespace ModulesGarden\Servers\DirectAdminExtended\Core\UI\Traits;

use \ModulesGarden\Servers\DirectAdminExtended\Core\ServiceLocator;

trait DatatableActionButtons
{
    protected $actionButtons = [];
    protected $actionDropdownWrapper = null;
    protected $searchActionBarButtonsVisible = 1;

    public function addActionButton($button)
    {
        if (is_string($button))
        {
            $button = ServiceLocator::call($button);
        }

        $button->setMainContainer($this->mainContainer);
        $id = $button->getId();
        if (!isset($this->actionButtons[$id]))
        {
            $this->actionButtons[$id] = $button;
            if ($button instanceof \ModulesGarden\Servers\DirectAdminExtended\Core\UI\Interfaces\AjaxElementInterface)
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
        return ($this->getActionButtonsCount() > 0) ? true : false;
    }

    public function getActionButtons()
    {
        return $this->actionButtons;
    }

    public function addActionButtonToDropdown($button)
    {
        if ($this->actionDropdownWrapper === null)
        {
            $this->actionDropdownWrapper = new \ModulesGarden\Servers\DirectAdminExtended\Core\UI\Widget\Buttons\DropdownButtonWrappers\ButtonDropdown('actionDropdown');
            $this->registerMainContainerAdditions($this->actionDropdownWrapper);
        }

        $this->actionDropdownWrapper->addButton($button);

        return $this;
    }

    public function getSearchActionBarButtonsVisible()
    {
        return $this->searchActionBarButtonsVisible;
    }

    public function getActionButtonsCount()
    {
        return count($this->actionButtons);
    }

    public function hasActionDropdownButton()
    {
        return $this->actionDropdownWrapper !== null;
    }

    public function getActionDropdownButtonHtml()
    {
        return $this->actionDropdownWrapper->getHtml();
    }
}
