<?php

namespace ModulesGarden\WordpressManager\Core\UI;

use \ModulesGarden\WordpressManager\Core\Helper;
use \ModulesGarden\WordpressManager\Core\Http\Request;
use \ModulesGarden\WordpressManager\Core\ModuleConstants;
use \ModulesGarden\WordpressManager\Core\UI\Helpers\TemplateConstants;

/**
 * Main View Controller
 * @author Sławomir Miśkowicz <slawomir@modulesgarden.com>
 */
class View
{
    use Traits\AppLayouts;
    use Traits\CustomJsCode;
    use Traits\ViewBreadcrumb;

    /**
     * Controler for all widgets inside of View
     * @var \ModulesGarden\WordpressManager\Core\UI\MainContainer
     */
    protected $mainContainer = null;
    protected $template      = null;
    protected $name;
    protected $isBreadcrumbs = true;
    protected $templateDir   = null;
    protected $defaultComponentsJs = null;

    public function __construct ($template = null)
    {
        $this->setTemplate($template);
        $this->mainContainer = new \ModulesGarden\WordpressManager\Core\UI\MainContainer();
        $this->initBreadcrumbs();
    }

    /**
     * Adds elements to the root element
     */
    public function addElement($element, $containerName = null)
    {
        $this->mainContainer->addElement($element, $containerName);

        return $this;
    }

    /**
     * Generates all responses for UI elements
     */
    public function getResponse()
    {
        $this->mainContainer->setTemplate($this->getAppLayoutTemplateDir(), $this->getAppLayout());

        $request = Request::build();
        if ($request->get('ajax', false))
        {
            return $this->mainContainer->getAjaxResponse();
        }

        return Helper\response([
                    'tpl'    => $this->template,
                    'tplDir' => $this->templateDir,
                    'data'   => [
                        'mainContainer' => $this->mainContainer,
                        'customJsCode' => $this->getCustomJsCode(),
                        'breadcrumbs' => $this->getBreadcrumbs()
                    ]
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
    public function setTemplate ($template = null)
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

    /**
     * this function is deprecated
     * @return type
     */
    public function genResponse()
    {
        return $this->getResponse();
    }

}
