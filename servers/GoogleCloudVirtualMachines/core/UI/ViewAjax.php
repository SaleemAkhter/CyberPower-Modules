<?php

namespace ModulesGarden\Servers\GoogleCloudVirtualMachines\Core\UI;

use \ModulesGarden\Servers\GoogleCloudVirtualMachines\Core\Helper;

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
        $this->mainContainer = new \ModulesGarden\Servers\GoogleCloudVirtualMachines\Core\UI\MainContainerAjax();
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

    public function initAjaxElementContext($namespace)
    {
        $this->setNamespace($namespace);
        $this->mainContainer->addElement(Helper\convertStringToNamespace($namespace));
    }
}
