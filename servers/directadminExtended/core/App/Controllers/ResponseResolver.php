<?php

namespace ModulesGarden\Servers\DirectAdminExtended\Core\App\Controllers;

use ModulesGarden\Servers\DirectAdminExtended\Core\Helper\BuildUrl;
use ModulesGarden\Servers\DirectAdminExtended\Core\Http\JsonResponse;
use ModulesGarden\Servers\DirectAdminExtended\Core\Http\RedirectResponse;
use ModulesGarden\Servers\DirectAdminExtended\Core\Http\Response;
use ModulesGarden\Servers\DirectAdminExtended\Core\UI\View;

class ResponseResolver
{
    use \ModulesGarden\Servers\DirectAdminExtended\Core\Traits\Lang;
    use \ModulesGarden\Servers\DirectAdminExtended\Core\Traits\Smarty;
    use \ModulesGarden\Servers\DirectAdminExtended\Core\Traits\OutputBuffer;
    use \ModulesGarden\Servers\DirectAdminExtended\Core\Traits\IsAdmin;
    use \ModulesGarden\Servers\DirectAdminExtended\Core\UI\Traits\RequestObjectHandler;
    use \ModulesGarden\Servers\DirectAdminExtended\Core\Traits\Template;

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
        if (stripos(get_class($this->response), 'ModulesGarden\WordpressManager') === 0)
        {
            return $this->resolveWordpressManager();
        }
        else
        {
            return $this->resolveDirectAdmin();
        }
    }

    protected function resolveWordpressManager()
    {
        if ($this->response instanceof \ModulesGarden\WordpressManager\Core\UI\View)
        {
            $this->resolveView();
        }

        if ($this->response instanceof \ModulesGarden\WordpressManager\Core\Http\JsonResponse)
        {
            $this->resolveJson();
        }
        elseif ($this->response instanceof \ModulesGarden\WordpressManager\Core\Http\RedirectResponse)
        {
            $this->resolveRedirect();
        }
        elseif ($this->response instanceof Response)
        {
            $this->prepareResponse();

            return $this->resolveResponse();
        }
        elseif ($this->response instanceof \ModulesGarden\WordpressManager\Core\Http\Response)
        {
            $this->prepareResponse();
            //override lang variable
            $this->response->setLang(\ModulesGarden\WordpressManager\Core\ServiceLocator::get('lang'));

            $data = $this->resolveResponse();



            //todo add support for templates
            $data['tabOverviewReplacementTemplate'] = 'client/default/controlers/main';
            $data['vars']['assetsURL']              = BuildUrl::getAppAssetsURL();

            return $data;
        }
    }

    protected function resolveDirectAdmin()
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
         * @var $this- >response \ModulesGarden\Servers\DirectAdminExtended\Core\UI\View
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
     * resolve \ModulesGarden\Servers\DirectAdminExtended\Core\Http\JsonResponse
     */
    public function resolveJson()
    {
        $this->cleanOutputBuffer();
        /**
         * @var \ModulesGarden\Servers\DirectAdminExtended\Core\Http\JsonResponse
         */
        $this->response->send();
        die();
    }

    public function resolveRedirect()
    {
        /**
         * @var \ModulesGarden\Servers\DirectAdminExtended\Core\Http\RedirectResponse
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
