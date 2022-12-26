<?php

/**
 * User: Rafał Ossowski
 * Date: 11.01.18
 * Time: 13:39
 */

namespace ModulesGarden\Servers\HetznerVps\Core\Http;

use ModulesGarden\Servers\HetznerVps\Core\DependencyInjection;
use ModulesGarden\Servers\HetznerVps\Core\UI\View;
use ModulesGarden\Servers\HetznerVps\Core\ModuleConstants;

class AbstractHooksClient extends AbstractController
{

    protected $controller;
    protected $action;

    /**
     * @return string
     */
    public function getControllerClass()
    {
        return 'ModulesGarden\Servers\HetznerVps\App\Http\Client\Hooks\\' . ucfirst($this->getController());
    }

    /**
     * @return string
     */
    public function getController()
    {
        return $this->getParamByKey('filename', null);
    }

    /**
     * @return string
     */
    public function getAction()
    {
        $tmpAction = str_replace('-', '', $this->getParamByKey('templatefile', ''));

        return $tmpAction && $tmpAction !== '' ? $tmpAction : 'index';
    }

    public function execute()
    {


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
        catch (\ModulesGarden\Servers\HetznerVps\Core\HandlerError\Exceptions\MGModuleException $exc)
        {
            
        }
        catch (\Exception $exc)
        {
            $newExc = new \ModulesGarden\Servers\HetznerVps\Core\HandlerError\Exceptions\MGModuleException(self::class, $exc->getMessage(), $exc->getCode());
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

//        $result->setLang($this->lang);
        $this->getSmarty()->setTemplateDir($this->getTemplateDir(true));

        if ($result instanceof JsonResponse)
        {
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

            return $result->loadlayoutHooksContenct(
                                    $this->getPageContext($result), $this->getController(), $this->getAction(), $this->getTemplateDir()
                            )
                            ->getContent();
        }
    }



    public function getTemplateDir($withController = false, $fullpathWithClient = false)
    {
        $root = ModuleConstants::getTemplateDir() . DS;


        $return = $root . strtolower($this->getControllerType())
                . (($this->isAdmin) ? '' : (DS . $this->getClientTemplate()))
                . (($withController) ? ( DS . 'pages' . DS . lcfirst($this->getController())) : '');


        return $return;
    }

    public function getControllerType()
    {
        $type           = $this->getType();
        $controllerType = ($this->isAdmin) ? 'Admin' : 'Client';
        $controllerType = ($this->getType() == 'addon') ? $controllerType : 'Server' . DIRECTORY_SEPARATOR . $controllerType;

        return $controllerType;
    }

}
