<?php

namespace ModulesGarden\Servers\DigitalOceanDroplets\Core\UI;

use \ModulesGarden\Servers\DigitalOceanDroplets\Core\Helper;
use \ModulesGarden\Servers\DigitalOceanDroplets\Core\Http\Request;
use \ModulesGarden\Servers\DigitalOceanDroplets\Core\ModuleConstants;
use \ModulesGarden\Servers\DigitalOceanDroplets\Core\UI\Helpers\TemplateConstants;
/**
 * Main Vuew Controler
 * @author Sławomir Miśkowicz <slawomir@modulesgarden.com>
 */
class View
{
    use \ModulesGarden\Servers\DigitalOceanDroplets\Core\UI\Traits\WhmcsParams; 
    
    /**
     * Controler for all widgets inside of View
     * @var \ModulesGarden\Servers\DigitalOceanDroplets\Core\UI\MainContainer
     */
    protected $mainContainer = null;
    protected $template      = null;
    protected $name;
    protected $isBreadcrumbs = true;
    protected $templateDir   = null;

    protected $elementList = [];
    
    public function __construct ($template = null)
    {
        

        $this->setTemplate($template);
        $this->mainContainer = new \ModulesGarden\Servers\DigitalOceanDroplets\Core\UI\MainContainer();
    }

    /**
     * Adds elements to the root element
     */
    public function addElement($element)
    {
        $this->elementList[] = $element;
        return $this;
    }

    public function initContent()
    {
        $this->mainContainer->setWhmcsParams($this->whmcsParams);

        foreach ($this->elementList as $element)
        {
            $this->mainContainer->addElement($element);
        }
        
        return $this;
    }
    
    /**
     * Generates all responses for UI elements
     */
    public function genResponse()
    {
        $request = Request::build();
        if ($request->get('ajax', false))
        {
            return $this->mainContainer->getAjaxResponse();
        }

        return Helper\response([
                    'tpl'    => $this->template,
                    'tplDir' => $this->templateDir,
                    'data'   => ['mainContainer' => $this->mainContainer]
                ])->setStatusCode(200)->setName($this->name)->setBreadcrumbs($this->isBreadcrumbs);
    }

    public function validateAcl ($isAdmin)
    {
        $this->mainContainer->valicateACL($isAdmin);

        return $this;
    }

    /**
     * Sets custom View template
     */
    public function setTemplate($template = null)
    {
        if ($template === null)
        {
            $this->template    = 'view';
            $this->templateDir = ModuleConstants::getTemplateDir()
                    . DS . (Helper\isAdmin() ? TemplateConstants::ADMIN_PATH : TemplateConstants::CLIENT_PATH)
                    . DS . TemplateConstants::MAIN_DIR;
            

            return;
        }

        $this->template    = $template;
        $this->templateDir = null;
    }

    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }

    public function disableBreadcrumbs()
    {
        $this->isBreadcrumbs = false;

        return $this;
    }
}
