<?php

namespace ModulesGarden\Servers\OvhVpsAndDedicated\Core\App\Controllers\Instances;

use \ModulesGarden\Servers\OvhVpsAndDedicated\Core\App\Controllers\Interfaces\AdminArea;
use \ModulesGarden\Servers\OvhVpsAndDedicated\Core\App\Controllers\Interfaces\ClientArea;
use \ModulesGarden\Servers\OvhVpsAndDedicated\Core\App\Controllers\Interfaces\DefaultController;
use ModulesGarden\Servers\OvhVpsAndDedicated\Core\App\Controllers\ResponseResolver;
use ModulesGarden\Servers\OvhVpsAndDedicated\Core\App\Controllers\Router;
use \ModulesGarden\Servers\OvhVpsAndDedicated\Core\DependencyInjection;
use ModulesGarden\Servers\OvhVpsAndDedicated\Core\ModuleConstants;

abstract class HttpController implements DefaultController
{
    use \ModulesGarden\Servers\OvhVpsAndDedicated\Core\Traits\Lang;
    use \ModulesGarden\Servers\OvhVpsAndDedicated\Core\Traits\Smarty;
    use \ModulesGarden\Servers\OvhVpsAndDedicated\Core\Traits\OutputBuffer;
    use \ModulesGarden\Servers\OvhVpsAndDedicated\Core\Traits\IsAdmin;
    use \ModulesGarden\Servers\OvhVpsAndDedicated\Core\UI\Traits\RequestObjectHandler;
    use \ModulesGarden\Servers\OvhVpsAndDedicated\Core\Traits\ErrorCodesLibrary;
    use \ModulesGarden\Servers\OvhVpsAndDedicated\Core\Traits\Params;
    use \ModulesGarden\Servers\OvhVpsAndDedicated\Core\Traits\AppParams;

    const ADMIN = 'admin';
    const CLIENT = 'client';

    protected $templateName = 'main';
    protected $templateContext = 'default';
    protected $templateDir = null;

    protected $controllerResult = null;

    /**
     * @var Router|null
     *
     */
    protected $router = null;
    protected $responseResolver = null;

    public function __construct()
    {
        $this->loadSmarty();
        $this->isAdmin();

        $this->router = new Router();
        $this->responseResolver = new ResponseResolver();
    }

    public function execute($params = null)
    {
        $this->setParams($params);

        if (!$this->router->isControllerCallable() || !$this->isAdminContextValid())
        {
            return $this->controllerResult = $this->getPageNotFound();
        }
        else
        {
            $this->setAppParam('HttpControlerName', $this->router->getControllerClass());
            $this->setAppParam('HttpControlerMethod', $this->router->getControllerMethod());
            
            $this->controllerResult = $this->getControllerResponse();
        }

        return $this->resolveResponse();
    }

    public function resolveResponse()
    {
        return $this->responseResolver->setResponse($this->controllerResult)
            ->setTemplateName($this->getTemplateName())
            ->setTemplateDir($this->getTemplateDir())
            ->setPageController($this)
            ->resolve();
    }

    public function isAdminContextValid()
    {
        if ($this->isAdmin() && !($this instanceof AdminArea))
        {
            return false;
        }

        if (!$this->isAdmin() && !($this instanceof ClientArea))
        {
            return false;
        }

        return true;
    }

    public function getPageNotFound()
    {
        $notFound = new Http\PageNotFound();

        return $notFound->execute();
    }

    protected function getControllerResponse()
    {
        $this->loadLang();
        $this->lang->setContext(($this->getType() . ($this->isAdmin() ? 'AA' : 'CA')), lcfirst($this->getControllerClass(true)));

        $result = DependencyInjection::create(
            $this->router->getControllerClass(),
            $this->router->getControllerMethod()
        );

        return $result;
    }

    public function getTemplateName()
    {
        return $this->templateName;
    }

    public function getTemplateDir()
    {
        if ($this->templateDir === null)
        {
            $this->templateDir = ModuleConstants::getTemplateDir() . DIRECTORY_SEPARATOR .
                ($this->isAdmin() ? self::ADMIN : (self::CLIENT . DIRECTORY_SEPARATOR . $this->getTemplateContext()))
               . DIRECTORY_SEPARATOR . 'controlers';
        }

        return $this->templateDir;
    }

    public function getTemplateContext()
    {
        return $this->templateContext;
    }

    public function getControllerClass($raw = false)
    {
        if ($raw)
        {
            $namespaceParts = explode('\\', $this->getControllerClass());

            return end($namespaceParts);
        }

        return $this->router->getControllerClass();
    }

    public function getControllerMethod()
    {
        return $this->router->getControllerMethod();
    }

    /**
     * @param null $controllerResult
     */
    public function setControllerResult ($controllerResult)
    {
        $this->controllerResult = $controllerResult;
    }

    /**
     * @return null
     */
    public function getControllerResult ()
    {
        return $this->controllerResult;
    }

    /**
     * @return string
     */
    protected function getType()
    {
        return 'addon';
    }
    
    public function runExecuteProcess($params = null)
    {
        return $this->execute($params);
    }    
}
