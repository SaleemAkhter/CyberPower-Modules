<?php

namespace ModulesGarden\Servers\VultrVps\Core\App\Controllers;

use ModulesGarden\Servers\VultrVps\Core\Http\JsonResponse;
use ModulesGarden\Servers\VultrVps\Core\Http\RedirectResponse;
use ModulesGarden\Servers\VultrVps\Core\Http\Response;
use ModulesGarden\Servers\VultrVps\Core\UI\View;

class ResponseResolver
{
    use \ModulesGarden\Servers\VultrVps\Core\Traits\Lang;
    use \ModulesGarden\Servers\VultrVps\Core\Traits\Smarty;
    use \ModulesGarden\Servers\VultrVps\Core\Traits\OutputBuffer;
    use \ModulesGarden\Servers\VultrVps\Core\Traits\IsAdmin;
    use \ModulesGarden\Servers\VultrVps\Core\UI\Traits\RequestObjectHandler;
    use \ModulesGarden\Servers\VultrVps\Core\Traits\Template;

    protected $response = null;

    /**
     * @var null|HttpController
     */
    protected $pageController = null;

    public function __construct($response = null)
    {
        $this->setResponse($response);

        $this->loadSmarty();
    }

    /**
     * @param null $response
     * @return $this
     */
    public function setResponse($response = null)
    {
        if ($response)
        {
            $this->response = $response;
        }

        return $this;
    }

    /**
     * resolves the response
     */
    public function resolve()
    {
        if ($this->response instanceof View)
        {
            $this->resolveView();
        }

        if ($this->response instanceof JsonResponse)
        {
            $this->resolveJson();
        }
        elseif ($this->response instanceof RedirectResponse)
        {
            $this->resolveRedirect();
        }
        elseif ($this->response instanceof Response)
        {
            $this->prepareResponse();

            return $this->resolveResponse();
        }
    }

    /**
     * resolve View object to the processable response
     */
    public function resolveView()
    {
        /**
         * @var $this->response \ModulesGarden\Servers\VultrVps\Core\UI\View
         */
        $this->response->validateAcl($this->isAdmin());

        $this->response = $this->response->getResponse();
    }

    public function prepareResponse()
    {
        $this->response->setLang($this->lang);
        $this->response->setTemplateName($this->getTemplateName());
        $this->response->setTemplateDir($this->getTemplateDir());
        //$this->smarty->setTemplateDir();
    }

    /**
     * resolve \ModulesGarden\Servers\VultrVps\Core\Http\JsonResponse
     */
    public function resolveJson()
    {
        $this->cleanOutputBuffer();
        /**
         * @var \ModulesGarden\Servers\VultrVps\Core\Http\JsonResponse
         */
        $this->response->send();
        die();
    }

    public function resolveRedirect()
    {
        /**
         * @var \ModulesGarden\Servers\VultrVps\Core\Http\RedirectResponse
         */
        die($this->response->send());
    }

    public function resolveResponse()
    {
        return $this->response->getHtmlResponse($this);
    }

    /**
     * @param null|HttpController $pageController
     */
    public function setPageController($pageController)
    {
        $this->pageController = $pageController;

        return $this;
    }

    /**
     * @return HttpController|null
     */
    public function getPageController()
    {
        return $this->pageController;
    }
}
