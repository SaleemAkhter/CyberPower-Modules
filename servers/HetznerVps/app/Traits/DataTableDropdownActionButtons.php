<?php

namespace ModulesGarden\Servers\HetznerVps\App\Traits;

/**
 * ParamsComponent trait
 *
 * @author Michal Zarow <michal.za@modulesgarden.com>
 */
trait DataTableDropdownActionButtons
{
    protected $hasDropdownActionButtons = false;
    protected $dropdownActionButtons    = [];
    protected $hasActionDropdownActionButtons = false;
    protected $actionDropdownActionButtons    = [];

    protected function enableDropdownActionButtons()
    {
        $this->hasDropdownActionButtons = true;
        return $this;
    }

    public function hasDropdownActionButtons()
    {
        return $this->hasDropdownActionButtons;
    }

    protected function enableActionDropdownActionButtons()
    {
        $this->hasDropdownActionButtons = true;
        return $this;
    }

    public function hasActionDropdownActionButtons()
    {
        return $this->hasDropdownActionButtons;
    }

    protected function addDropdownActionButton($button)
    {
        if (is_string($button))
        {
            $button = ServiceLocator::call($button);
        }
        
        $button->setWhmcsParams($this->whmcsParams);
        $button->setMainContainer($this->mainContainer);
        $id = $button->getId();
        if (!isset($this->dropdownActionButtons[$id]))
        {
            $this->dropdownActionButtons[$id] = $button;
            if ($button instanceof \ModulesGarden\Servers\HetznerVps\Core\UI\Interfaces\AjaxElementInterface)
            {
                $this->mainContainer->addAjaxElement($this->dropdownActionButtons[$id]);
            }
        }

        return $this;
    }

    public function getDropdownActionButtons()
    {
        return $this->dropdownActionButtons;
    }

    protected function addActionDropdownActionButtons($button)
    {
        if (is_string($button))
        {
            $button = ServiceLocator::call($button);
        }

        $button->setWhmcsParams($this->whmcsParams);
        $button->setMainContainer($this->mainContainer);
        $id = $button->getId();
        if (!isset($this->actionDropdownActionButtons[$id]))
        {
            $this->actionDropdownActionButtons[$id] = $button;
            if ($button instanceof \ModulesGarden\Servers\HetznerVps\Core\UI\Interfaces\AjaxElementInterface)
            {
                $this->mainContainer->addAjaxElement($this->actionDropdownButtons[$id]);
            }
        }

        return $this;
    }

    public function getActionDropdownActionButtons()
    {
        return $this->actionDropdownActionButtons;
    }
}
