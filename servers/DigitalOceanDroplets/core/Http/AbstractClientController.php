<?php

namespace ModulesGarden\Servers\DigitalOceanDroplets\Core\Http;

use ModulesGarden\Servers\DigitalOceanDroplets\Core\ServiceLocator;
use ModulesGarden\Servers\DigitalOceanDroplets\Core\Helper\BuildUrl;
use ModulesGarden\Servers\DigitalOceanDroplets\Core\Http\View\MainMenu;
use ModulesGarden\Servers\DigitalOceanDroplets\Core\Configuration\Addon;
use ModulesGarden\Servers\DigitalOceanDroplets\Core\Helper;
use ModulesGarden\Servers\DigitalOceanDroplets\Core\DependencyInjection;
use ModulesGarden\Servers\DigitalOceanDroplets\Core\UI\View;
use ModulesGarden\Servers\DigitalOceanDroplets\Core\UI\ViewAjax;
use ModulesGarden\Servers\DigitalOceanDroplets\Core\UI\ResponseTemplates\RawClientAreaResponse;

/**
 * Description of AbstractClientController
 *
 * @author Rafał Ossowski <rafal.os@modulesgarden.com>
 */
class AbstractClientController extends AbstractController
{

    public function execute()
    {
        $this->loadContectLang();

        $output = $this->generateClientOutput();
        $vars   = $this->generationClientOutputVars();
        $vars['breadcrumbs'] = $output['breadcrumb'];
        $vars['mainName']    = $output['pagetitle'];

        try
        {
            $this->getSmarty()->setLang($this->lang)->setTemplateDir($this->getTemplateDir());
            $this->lang->stagCurrentContext('generate' . $this->getController());
            $this->lang->addToContext(lcfirst($this->getController()));

            $result = DependencyInjection::create($this->getControllerClass(), $this->getAction());

            if ($result)
            {
                if ($result instanceof View)
                {               
                    $result->setWhmcsParams(self::$params);
                    $result->initContent();
                }
                
                $this->getPage($result, $vars);
            }
            else
            {
                $this->getPageNotFound($vars);
            }
        }
        catch (\ModulesGarden\Servers\DigitalOceanDroplets\Core\HandlerError\Exceptions\MGModuleException $ex)
        {
            $this->getFatalError($ex, $vars);
        }
        catch (\Exception $exc)
        {
            $newExc = new \ModulesGarden\Servers\DigitalOceanDroplets\Core\HandlerError\Exceptions\MGModuleException(self::class, $exc->getMessage(), $exc->getCode());

            $this->showFatalError($newExc, $vars);
        }
        $this->lang->unstagContext('generate' . $this->getController());
        $output['vars']       = $vars;
        $output['breadcrumb'] = $vars['breadcrumbs'];
        $output['pagetitle']  = $vars['mainName'];

        return $output;
    }

    /**
     *
     * @param Response $result
     */
    protected function getPage($result, array &$vars = [])
    {
        if ($result instanceof View)
        {
            $result->validateAcl($this->isAdmin);

            $result = $result->genResponse();
        }

        if ($result instanceof JsonResponse)
        {
            $this->cleanOutputBuffer();
            $result->send();
            die();
        }
        elseif ($result instanceof RedirectResponse)
        {
            $result->send();
            die();
        }
        elseif ($result instanceof Response)
        {
            $this->getPageContext($result, $vars);
        }
        elseif($result instanceof \ModulesGarden\WordpressManager\Core\UI\View)
        {   
            $result->validateAcl($this->isAdmin);
            $result = $result->genResponse();
            
            if ($result instanceof \ModulesGarden\WordpressManager\Core\Http\JsonResponse)
            {
                $this->cleanOutputBuffer();
                $result->send();
                die();
            }
            elseif($result instanceof \ModulesGarden\WordpressManager\Core\Http\RedirectResponse)
            {
                $result->send();
                die();
            }
            elseif($result instanceof \ModulesGarden\WordpressManager\Core\Http\Response)
            {
                $this->getPageContext($result, $vars);
            }
        }
    }

    /**
     *
     * @param Response $result
     * @param array $vars
     * @return string
     */
    protected function getPageContext($result, array &$vars = [])
    {
        try
        {
            $this->getSmarty()->setTemplateDir($this->getTemplateDir(true, true));
            $vars['content'] = $result->getPageContext();
            $vars['success'] = $result->getSuccess();
            $vars['error']   = ($result->getError()) ? $result->getError() : false;

            if ($name = $result->getName())
            {
                $vars['mainName'] = $name;
            }

            if ($result->isBreadcrumbs() === false)
            {
                $vars['breadcrumbs'] = null;
            }
        }
        catch (\Exception $exc)
        {
            $newExc          = new \ModulesGarden\Servers\DigitalOceanDroplets\Core\HandlerError\Exceptions\MGModuleException(self::class, $exc->getMessage(), $exc->getCode());
            $vars['success'] = false;
            $vars['error']   = $this->lang->absoluteT('generalErrorClientArea') . $this->lang->absoluteT('token') . $newExc->getToken();
        }
    }

    /**
     * Show Info Page Not fount
     */
    protected function getPageNotFound(array &$vars = [])
    {
        $this->lang->unstagContext('generate' . $this->getController());

        $response        = Helper\response()->setStatusCode(404)->withError($this->lang->T('pageNotFound'));
        $vars['content'] = $response->getContent();
        $vars['error']   = $response->getError();
        $vars['success'] = false;
    }

    /**
     * @param \ModulesGarden\Servers\DigitalOceanDroplets\Core\HandlerError\Exceptions\MGModuleException $error
     */
    protected function getFatalError($error, array &$vars = [])
    {
        $message  = $this->lang->absoluteT('generalErrorClientArea') . $this->lang->absoluteT('token') . $error->getToken();
        $this->lang->unstagContext('generate' . $this->getController());
        $response = Helper\response()->setStatusCode(404)->withError($message);

        $vars['success'] = false;
        $vars['content'] = $response->getContent();
        $vars['error']   = $response->getError();
    }

    protected function generationClientOutputVars()
    {
        return [
            'assetsURL'       => BuildUrl::getAssetsURL(),
            'mainURL'         => BuildUrl::getUrl(),
            'JSONCurrentUrl'  => BuildUrl::getUrl($this->getController()),
            'menu'            => DependencyInjection::create(MainMenu::class)->getMenu(),
            'isWHMCS6'        => version_compare($GLOBALS['CONFIG']['Version'], '6.0.0', '>='),
            'currentPageName' => strtolower($this->getController()),
            'MGLANG'          => $this->lang
        ];
    }

    protected function generateClientOutput()
    {
        $clientAreaName = ServiceLocator::call(Addon::class)->getConfig('clientareaName', 'default');
        $breadcrumb     = [BuildUrl::getUrl() => $clientAreaName];
        if ($this->getParamByKey('mg-page', false))
        {
            $url              = BuildUrl::getUrl($this->getParamByKey('mg-page', 'Home'));
            $breadcrumb[$url] = $this->getParamByKey('mg-page', 'Home');
        }

        $templateVarName = ($this->request->get('a', false) === 'management') ? 'tabOverviewReplacementTemplate' : 'templatefile';        
        
        $output = [
            'pagetitle'        => $clientAreaName,
            $templateVarName     => $this->getTemplateDir() . DS . 'main',
            'requirelogin' => $this->isClientLoggedIn(),
            'breadcrumb'       => $breadcrumb
        ];
        return $output;
    }

    protected function isClientLoggedIn()
    {
        return (bool) ($this->request->getSession('uid', false));
    }
    
    public function getControllerType()
    {
        $type           = $this->getType();
        $controllerType = ($this->isAdmin) ? 'Admin' : 'Client';
        $controllerType = ($this->getType() == 'addon') ? $controllerType : $controllerType . DIRECTORY_SEPARATOR . 'Server';

        return $controllerType;
    }    
}
