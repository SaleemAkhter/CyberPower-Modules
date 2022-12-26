<?php

/**
 * User: Rafał Ossowski
 * Date: 11.01.18
 * Time: 13:39
 */

namespace ModulesGarden\Servers\DigitalOceanDroplets\Core\Http;

use ModulesGarden\Servers\DigitalOceanDroplets\Core\DependencyInjection;
use ModulesGarden\Servers\DigitalOceanDroplets\Core\UI\View;
use ModulesGarden\Servers\DigitalOceanDroplets\Core\ModuleConstants;

class AbstractProductPage extends AbstractController
{

    protected $controller;
    protected $action;
    protected $productPage = 'ProductPage';

    /**
     * @return string
     */
    public function getControllerClass()
    {
        return 'ModulesGarden\Servers\DigitalOceanDroplets\App\Http\Admin\Server\\' . $this->productPage;
    }

    /**
     * @return string
     */
    public function getController()
    {
        return $this->productPage;
    }

    /**
     * @return string
     */
    public function getAction()
    {
        return lcfirst($this->getParamByKey('action', 'index'));
    }

    public function execute()
    {
        if(!$this->isValidNamespace())
        {
            return;
        }

        $this->loadContectLang();
        try
        {
            $this->getSmarty()
                    ->setLang($this->lang)
                    ->setTemplateDir($this->getTemplateDir());


            $this->lang->stagCurrentContext('generate' . $this->getController());
            $this->lang->addToContext(lcfirst($this->getController()));


            $result = DependencyInjection::create(
                            $this->getControllerClass(), $this->getAction()
            );

            if ($result)
            {
                if ($context = $this->showPage($result))
                {
                    return $context;
                }
            }
        }
        catch (\ModulesGarden\Servers\DigitalOceanDroplets\Core\HandlerError\Exceptions\MGModuleException $exc)
        {

        }
        catch (\Exception $exc)
        {
            $newExc = new \ModulesGarden\Servers\DigitalOceanDroplets\Core\HandlerError\Exceptions\MGModuleException(self::class, $exc->getMessage(), $exc->getCode());
        }

        return null;
    }

    /**
     *
     * @param Response $result
     */
    protected function showPage($result)
    {
        if ($result instanceof View)
        {
            /**
             * @var View $result
             */
            $result->initContent();
            $result->validateAcl($this->isAdmin);
            $result = $result->genResponse();
        }

        //$result->setLang($this->lang);
        $this->getSmarty()->setTemplateDir($this->getTemplateDir(true));


        if ($result instanceof JsonResponse)
        {
            ob_clean();
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

            return $result->loadlayoutContenct(
                                    $this->getPageContext($result), $this->getController(), $this->getAction(), $this->getTemplateDir()
                            )
                            ->getContent();
        }
    }

    public function getTemplateDir($withController = false, $fullpathWithClient = false)
    {
        return ModuleConstants::getTemplateDir() . DS
                . (($this->isAdmin) ? 'admin' : 'client' . (DS . $this->getClientTemplate())) . DS
                . 'pages' . DS
                . strtolower($this->getController());
    }

    public function getControllerType()
    {
        $type           = $this->getType();
        $controllerType = ($this->isAdmin) ? 'Admin' : 'Client';
        $controllerType = ($this->getType() == 'addon') ? $controllerType : 'Server' . DIRECTORY_SEPARATOR . $controllerType;

        return $controllerType;
    }

}
