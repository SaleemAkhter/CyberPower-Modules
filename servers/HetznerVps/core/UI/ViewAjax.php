<?php

namespace ModulesGarden\Servers\HetznerVps\Core\UI;

use \ModulesGarden\Servers\HetznerVps\Core\Helper;
use ModulesGarden\Servers\HetznerVps\Core\ModuleConstants;

/**
 * Main Vuew Controler
 * @author Sławomir Miśkowicz <slawomir@modulesgarden.com>
 */
class ViewAjax extends View
{
    protected $elements  = [];
    protected $namespace = '';

    public function __construct($template = null)
    {
        $this->setTemplate($template);
        $this->mainContainer = new \ModulesGarden\Servers\HetznerVps\Core\UI\MainContainerAjax();
    }

    /**
     * Adds elements to the root element
     */
    public function addElement($element)
    {
        return $this;
    }

    public function validateAcl($isAdmin)
    {
        $this->mainContainer->valicateACL($isAdmin);

        return $this;
    }

    public function setNamespace($namespace)
    {
        $this->namespace = $namespace;
        $this->mainContainer->setNamespaceAjax($this->namespace);

        return $this;
    }

    /**
     * Generates all responses for UI elements
     */
    public function getResponse()
    {
        return $this->mainContainer->getAjaxResponse();
    }

    /**
     * @param $namespace
     * @return |null
     */
    public function initAjaxElementContext($namespace)
    {
        $this->setNamespace($namespace);
        if(!$this->isValidNamespace(Helper\convertStringToNamespace($namespace)))
        {
            return null;
        }
        $this->mainContainer->addElement(Helper\convertStringToNamespace($namespace));
    }

    /**
     * @param $namespace
     * @return bool
     */
    public function isValidNamespace($namespace)
    {
        if(strpos($namespace, ModuleConstants::getRootNamespace()) === false)
        {
            return false;
        }

        return true;
    }
}
