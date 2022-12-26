<?php

namespace ModulesGarden\WordpressManager\Core\UI\Widget\Forms\Sections;

/**
 * Base Form Section controler
 *
 * @author Sławomir Miśkowicz <slawomir@modulesgarden.com>
 */
class BoxSection extends BaseSection
{
    protected $id   = 'boxSection';
    protected $name = 'boxSection';
    protected $actionButtons = [];
    protected $customCollapse=false;

    public function enableCustomCollapse(){
        $this->customCollapse=true;
        return $this;
    }

    public function hasCustomCollapse()
    {
        return $this->customCollapse;
    }

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
            if ($button instanceof \ModulesGarden\WordpressManager\Core\UI\Interfaces\AjaxElementInterface)
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

        return $this->actionButtons;
    }

}
