<?php

namespace ModulesGarden\Servers\DigitalOceanDroplets\Core\UI\Traits;

use \ModulesGarden\Servers\DigitalOceanDroplets\Core\DependencyInjection;
use \ModulesGarden\Servers\DigitalOceanDroplets\Core\UI\Widget\Buttons\ModalActionButtons\BaseAcceptButton;
use \ModulesGarden\Servers\DigitalOceanDroplets\Core\UI\Widget\Buttons\ModalActionButtons\BaseCancelButton;

/**
 * Modal Action Buttons related functions
 *
 * @author Sławomir Miśkowicz <slawomir@modulesgarden.com>
 */
trait ModalActionButtons
{
    protected $actionButtons = [];

    public function addActionButton($button)
    {
        $this->addButtonToList($button);

        return $this;
    }

    protected function addButtonToList($button)
    {
        if (is_string($button))
        {
            $button = DependencyInjection::create($button);
        }

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

        return $button;
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

    public function getActionButtons()
    {
        $this->initActionButtons();

        return $this->actionButtons;
    }

    protected function initActionButtons()
    {
        if (!empty($this->actionButtons))
        {
            return $this;
        }

        $this->addActionButton(new BaseAcceptButton);
        $this->addActionButton(new BaseCancelButton);

        return $this;
    }

    public function replaceSubmitButton($button)
    {
        $this->initActionButtons();

        $added = $this->addButtonToList($button);
        if (isset($this->actionButtons[$added->getId()]) &&
                isset($this->actionButtons['baseAcceptButton']))
        {
            $this->actionButtons['baseAcceptButton'] = $this->actionButtons[$added->getId()];
            unset($this->actionButtons[$added->getId()]);
        }

        return $this;
    }

    public function replaceSubmitButtonClesses($classes)
    {
        $this->initActionButtons();

        if (isset($this->actionButtons['baseAcceptButton']))
        {
            $this->actionButtons['baseAcceptButton']->replaceClasses($classes);
        }

        return $this;
    }

    public function setConfirmButtonDanger()
    {
        $this->replaceSubmitButtonClesses(['lu-btn lu-btn--danger submitForm']);

        return $this;
    }
}
