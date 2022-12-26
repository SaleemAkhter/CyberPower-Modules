<?php

namespace ModulesGarden\WordpressManager\Core\UI\Traits;

use function \ModulesGarden\WordpressManager\Core\Helper\sl;

/**
 * Main Container Elements related functions
 * Main Container Trait
 *
 * @author Sławomir Miśkowicz <slawomir@modulesgarden.com>
 */
trait MainContainerElements
{
    protected $ajaxElements        = [];
    protected $vueComponents       = [];
    
    protected $header = [];
    protected $footer = [];
    protected $navbar = null;

    public function addAjaxElement(\ModulesGarden\WordpressManager\Core\UI\Interfaces\AjaxElementInterface &$element)
    {
        $this->ajaxElements[] = &$element;
    }

    public function addVueComponent(&$element)
    {
        if ($element->isVueComponent())
        {
            $this->vueComponents[$element->getTemplateName()] = &$element;
        }
    }

    protected function prepareElemnentsContainers()
    {
        $this->addNewElementsContainer('header')
                ->addNewElementsContainer('footer')
                ->addNewElementsContainer('navbar');
    }

    public function loadDefaultNavbars()
    {
        //no navbar was added manually
        if ($this->navbar === null)
        {
            $this->navbar = [];
            $sidebars = sl('sidebar')->get();
            foreach ($sidebars as $bar)
            {
                $this->addElement($bar, 'navbar');
            }
        }        
    }
}
