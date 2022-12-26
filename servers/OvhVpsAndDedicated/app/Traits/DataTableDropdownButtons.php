<?php

namespace ModulesGarden\Servers\OvhVpsAndDedicated\App\Traits;

/**
 * ParamsComponent trait
 *
 * @author Michal Zarow <michal.za@modulesgarden.com>
 */
trait DataTableDropdownButtons
{
    protected $hasDropdownButtons = false;
    protected $dropdownButtons    = [];
    protected $hasActionDropdownButtons = false;
    protected $actionDropdownButtons    = [];

    protected function enableDropdownButtons()
    {
        $this->hasDropdownButtons = true;
        return $this;
    }

    public function hasDropdownButtons()
    {
        return $this->hasDropdownButtons;
    }

    protected function enableActionDropdownButtons()
    {
        $this->hasDropdownButtons = true;
        return $this;
    }

    public function hasActionDropdownButtons()
    {
        return $this->hasDropdownButtons;
    }

    protected function addDropdownButton($button)
    {
        if (is_string($button))
        {
            $button = ServiceLocator::call($button);
        }

        $button->setWhmcsParams($this->whmcsParams);
        $button->setMainContainer($this->mainContainer);
        $id = $button->getId();
        if (!isset($this->dropdownButtons[$id]))
        {
            $this->dropdownButtons[$id] = $button;
            if ($button instanceof \ModulesGarden\Servers\OvhVpsAndDedicated\Core\UI\Interfaces\AjaxElementInterface)
            {
                $this->mainContainer->addAjaxElement($this->dropdownButtons[$id]);
            }
        }

        return $this;
    }

    public function getDropdownButtons()
    {
        return $this->dropdownButtons;
    }

    protected function addActionDropdownButton($button)
    {
        if (is_string($button))
        {
            $button = ServiceLocator::call($button);
        }

        $button->setWhmcsParams($this->whmcsParams);
        $button->setMainContainer($this->mainContainer);
        $id = $button->getId();
        if (!isset($this->actionDropdownButtons[$id]))
        {
            $this->actionDropdownButtons[$id] = $button;
            if ($button instanceof \ModulesGarden\Servers\OvhVpsAndDedicated\Core\UI\Interfaces\AjaxElementInterface)
            {
                $this->mainContainer->addAjaxElement($this->actionDropdownButtons[$id]);
            }
        }

        return $this;
    }

    public function getActionDropdownButtons()
    {
        return $this->actionDropdownButtons;
    }
}
