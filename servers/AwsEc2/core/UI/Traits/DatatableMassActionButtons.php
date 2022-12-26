<?php

namespace ModulesGarden\Servers\AwsEc2\Core\UI\Traits;

use ModulesGarden\Servers\AwsEc2\Core\ServiceLocator;

trait DatatableMassActionButtons
{
    protected $massActionButtons = [];

    public function addMassActionButton($button)
    {
        if (is_string($button))
        {
            $button = ServiceLocator::call($button);
        }

        $button->setMainContainer($this->mainContainer);
        $id = $button->getId();
        if (!isset($this->massActionButtons[$id]))
        {
            $this->massActionButtons[$id] = $button;
            if ($button instanceof \ModulesGarden\Servers\AwsEc2\Core\UI\Interfaces\AjaxElementInterface)
            {
                $this->mainContainer->addAjaxElement($this->massActionButtons[$id]);
            }
        }

        return $this;
    }

    public function insertMassActionButton($buttonId)
    {
        if (!$this->massActionButtons[$buttonId])
        {
            //add exception
        }
        else
        {
            $button = $this->massActionButtons[$buttonId];

            return $button->getHtml();
        }

        return '';
    }

    public function hasMassActionButtons()
    {
        return (count($this->massActionButtons) > 0) ? true : false;
    }

    public function getMassActionButtons()
    {
        return $this->massActionButtons;
    }
}
